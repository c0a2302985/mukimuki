<?php
session_start();
require_once("utility/PDOclass.php");

// DB接続情報
$host = 'db'; // docker-compose のサービス名
$dbname = 'myapp';
$dbuser = 'myuser';
$dbpass = 'mypass';

// ユーザー入力（POST）
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 入力チェック
if (empty($username) || empty($password)) {
    echo 'ユーザーネームとパスワードは必須です。';
    exit;
}

// DB接続
$db = new Database($host, $dbname, $dbuser, $dbpass);

// SQLとバインドパラメータ
$sql = "SELECT * FROM users WHERE username = :username";
$params = [
    ':username' => [$username, PDO::PARAM_STR],
];

$user = $db->fetch($sql, $params);

// パスワード照合（平文で保存されている前提／ハッシュなら password_verify を使う）
if ($user && $user['password'] === $password) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header('Location: top.php');
    exit;
} else {
    echo 'ユーザー名またはパスワードが間違っています';
}
?>
