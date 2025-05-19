<?php
session_start();
require_once "common.php";

// CSRFトークンチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST' 
    || !isset($_POST['csrf_token']) 
    || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('不正なアクセスです。');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['register_error'] = 'ユーザーネームとパスワードは必須です。';
    header('Location: register.php');
    exit;
}

// ユーザー名重複チェック
$sql = "SELECT COUNT(*) FROM users WHERE username = :username";
$params = [ ':username' => [$username, PDO::PARAM_STR] ];
$count = $db->fetchColumn($sql, $params);

if ($count > 0) {
    $_SESSION['register_error'] = 'そのユーザー名はすでに使われています。';
    header('Location: register.php');
    exit;
}

// パスワードハッシュ化
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
$params = [
    ':username' => [$username, PDO::PARAM_STR],
    ':password' => [$hashed_password, PDO::PARAM_STR],
];
$result = $db->execute($sql, $params);

if ($result > 0) {
    $_SESSION['user_id'] = $db->lastInsertId();
    $_SESSION['username'] = $username;
    unset($_SESSION['register_error']);
    unset($_SESSION['csrf_token']);
    header('Location: index.php');
    exit;
} else {
    $_SESSION['register_error'] = '登録に失敗しました。';
    header('Location: register.php');
    exit;
}
