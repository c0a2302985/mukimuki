<?php
session_start(); // セッション開始

// ログインしていない場合はアクセス拒否
if (!isset($_SESSION['user_id'])) {
    die('ログインしていません。先にログインしてください。');
}

$user_id = $_SESSION['user_id']; // ログイン中のユーザーIDを取得

// DB接続情報
$host = 'db'; // docker-composeのサービス名
$dbname = 'myapp';
$user = 'myuser';
$pass = 'mypass';

// DB接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die('DB接続失敗: ' . $e->getMessage());
}

// ログインユーザーの画像を取得するSQLクエリ
$sql = "SELECT * FROM images WHERE user_id = :user_id ORDER BY uploaded_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);

// 画像情報を表示
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
