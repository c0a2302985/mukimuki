<?php
session_start();

require_once 'common.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM images WHERE user_id = :user_id ORDER BY uploaded_at DESC";
$params = [ ':user_id' => [$user_id, PDO::PARAM_INT] ];
$images = $db->fetchAll($sql, $params);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>マイページ</title>
</head>
<body>

<h2>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?> さん！</h2>

<h3>あなたのアップロードした画像</h3>

<?php if (count($images) > 0): ?>
    <?php foreach ($images as $image): ?>
        <div>
            <h4><?php echo htmlspecialchars($image['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
            <img src="<?php echo htmlspecialchars($image['file_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($image['title'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 300px;">
            <p><?php echo htmlspecialchars($image['comment'], ENT_QUOTES, 'UTF-8'); ?></p>
            <form action="delete_image.php" method="post" onsubmit="return confirm('本当にこの画像を削除しますか？');">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($image['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="submit" value="削除">
            </form>
        </div><br>
    <?php endforeach; ?>
<?php else: ?>
    <p>あなたの画像はまだアップロードされていません。</p>
<?php endif; ?>

<br>
<a href="upload_input.php">画像のアップロード</a>

</body>
</html>
