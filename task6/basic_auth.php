<?php

if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
    requireLogin();
}

$user = 'u53895';
$pass = '8030057';
$db = new PDO('mysql:host=localhost;dbname=u53895', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

$stmt = $db->prepare("SELECT login, password FROM admins WHERE login = ?");
$stmt->execute([$_SERVER['PHP_AUTH_USER']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $validUser = $row['login'];
    $validPassHash = $row['password'];
} else {
    requireLogin();
}

if ($_SERVER['PHP_AUTH_USER'] != $validUser || md5($_SERVER['PHP_AUTH_PW']) != $validPassHash) {
    requireLogin();
}

function requireLogin() {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}