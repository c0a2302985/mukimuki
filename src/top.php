<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Top Page</title>
</head>
<body>
    <h1>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん！</h1>
    <p>ここに投稿一覧やメニューを追加していきましょう。</p>
    <a href="mypage.php">マイページ</a>
    <a href="upload_input.php">画像のアップロード</a>
    <a href="search.php">検索ページ</a>
</body>
</html>
