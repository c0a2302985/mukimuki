<?php
session_start();
require_once 'utility/PDOclass.php';

if (!isset($_SESSION['user_id'])) {
    die('ログインしてください。');
}

if (!isset($_GET['id'])) {
    die('画像IDが指定されていません。');
}

$image_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// DB接続情報
$host = 'db';
$dbname = 'myapp';
$user = 'myuser';
$pass = 'mypass';

$db = new Database($host, $dbname, $user, $pass);

// 本人の画像か確認
$sql = "SELECT file_name FROM images WHERE :id AND :user_id";
$params = [
    ':id' => [$image_id, PDO::PARAM_INT],
    ':user_id' => [$user_id, PDO::PARAM_INT]
];
$result = $db->fetch($sql, $params);

if (!$result) {
    die('この画像は削除できません。');
}

$filename = $result['file_name'];

// データベースから削除
$sql = "DELETE FROM images WHERE :id AND :user_id";
$db->execute($sql, $params);

// サーバー上のファイルも削除
$image_path = 'uploads/' . $filename;
if (file_exists($image_path)) {
    unlink($image_path);
}

// ✅ マイページにリダイレクト
header("Location: mypage.php");
exit;
