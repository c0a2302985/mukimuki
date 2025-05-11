<?php
session_start(); // セッション開始

require_once 'utility/PDOclass.php'; // Database クラス読み込み

// ログインしていない場合はアクセス拒否
if (!isset($_SESSION['user_id'])) {
    die('ログインしていません。先にログインしてください。');
}

$user_id = $_SESSION['user_id']; // ログイン中のユーザーIDを取得

// DB接続情報
$host = 'db';
$dbname = 'myapp';
$user = 'myuser';
$pass = 'mypass';

// Database クラスを使ってDB接続
$db = new Database($host, $dbname, $user, $pass);

// ログインユーザーの画像を取得するSQL
$sql = "SELECT * FROM images WHERE user_id = :user_id ORDER BY uploaded_at DESC";

// 実行パラメータ
$params = [
    ':user_id' => [$user_id, PDO::PARAM_INT]
];

// 画像情報を取得
$images = $db->fetchAll($sql, $params);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
</head>
<body>

<h2>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?> さん！</h2>

<h3>あなたのアップロードした画像</h3>

<?php
if (count($images) > 0) {
    foreach ($images as $image) {
        echo '<div>';
        echo '<h4>' . htmlspecialchars($image['title']) . '</h4>';
        echo '<img src="' . htmlspecialchars($image['file_path']) . '" alt="' . htmlspecialchars($image['title']) . '" style="max-width: 300px;"/>';
        echo '<p>' . htmlspecialchars($image['comment']) . '</p>';
        echo '</div><br>';
    }
} else {
    echo 'あなたの画像はまだアップロードされていません。';
}
?>
<a href="upload_input.php">画像のアップロード</a>

</body>
</html>
