<?php
require_once 'common.php'; // Database クラスの読み込み

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 画像情報を取得するSQLクエリ
$sql = "SELECT * FROM images ORDER BY uploaded_at DESC"; // 画像の最新順に取得

// 画像情報を表示
$images = $db->fetchAll($sql);

if (count($images) > 0) {
    foreach ($images as $image) {
        echo '<div>';
        echo '<h3>' . htmlspecialchars($image['title']) . '</h3>';
        echo '<p>' . htmlspecialchars($image['comment']) . '</p>';
        echo '<img src="' . htmlspecialchars($image['file_path']) . '" alt="' . htmlspecialchars($image['title']) . '" style="max-width: 300px;"/>';
        echo '</div><br>';
    }
} else {
    echo '画像がありません。';
}
?>
