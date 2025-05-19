<?php
session_start(); // セッション開始

require_once "common.php";

// // DB接続情報
// $host = 'db';
// $dbname = 'myapp';
// $user = 'myuser';
// $pass = 'mypass';

// ユーザー入力（POST）
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 入力値チェック
if (empty($username) || empty($password)) {
    echo 'ユーザーネームとパスワードは必須です。';
    exit;
}

// // DB接続
// $db = new Database($host, $dbname, $user, $pass);

// ユーザー登録処理
$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
$params = [
    ':username' => [$username, PDO::PARAM_STR],
    ':password' => [$password, PDO::PARAM_STR],
];
$result = $db->execute($sql, $params);

if ($result > 0) {
    // 登録成功 → ID取得 → セッション保存 → topへ
    $_SESSION['user_id'] = $db->lastInsertId();
    $_SESSION['username'] = $username;
    header('Location: index.php');
    exit;
} else {
    echo '登録失敗';
}
?>
