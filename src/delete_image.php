<?php
session_start();
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('不正なアクセスです。');
}

if (!isset($_SESSION['user_id'])) {
    die('ログインしてください。');
}

// CSRFトークン検証
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('不正なトークンです。');
}

if (!isset($_POST['id'])) {
    die('画像IDが指定されていません。');
}

$image_id = (int) $_POST['id'];
$user_id = $_SESSION['user_id'];

// 本人の画像か確認
$sql = "SELECT file_name FROM images WHERE id = :id AND user_id = :user_id";
$params = [
    ':id' => [$image_id, PDO::PARAM_INT],
    ':user_id' => [$user_id, PDO::PARAM_INT]
];
$result = $db->fetch($sql, $params);

if (!$result) {
    die('この画像は削除できません。');
}

$filename = $result['file_name'];

// DBとファイル削除
$sql = "DELETE FROM images WHERE id = :id AND user_id = :user_id";
$db->execute($sql, $params);

$image_path = 'uploads/' . $filename;
if (file_exists($image_path)) {
    unlink($image_path);
}

header("Location: mypage.php");
exit;
?>