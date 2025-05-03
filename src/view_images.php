<?php
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

// 画像情報を取得するSQLクエリ
$sql = "SELECT * FROM images ORDER BY uploaded_at DESC"; // 画像の最新順に取得
$stmt = $pdo->prepare($sql);
$stmt->execute();

// 画像情報を表示
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($images) > 0) {
    foreach ($images as $image) {
        echo '<div>';
        echo '<h3>' . htmlspecialchars($image['title']) . '</h3>';
        echo '<p>' . htmlspecialchars($image['comment']) . '</p>';
        echo htmlspecialchars($image['file_path']);
        echo '<img src="' . htmlspecialchars($image['file_path']) . '" alt="' . htmlspecialchars($image['title']) . '" style="max-width: 300px;"/>';
        echo '</div><br>';
    }
} else {
    echo '画像がありません。';
}
?>
