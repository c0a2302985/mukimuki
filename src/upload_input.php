<?php
session_start();

// ログインしていない場合はアクセス拒否
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// CSRFトークン生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロード</title>
</head>
<body>

<h2>画像アップロード</h2>
<p>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?> さん！</p>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <label for="title">画像のタイトル：</label>
    <input type="text" name="title" id="title" required><br><br>

    <label for="comment">コメント：</label>
    <textarea name="comment" id="comment" rows="4" cols="50"></textarea><br><br>

    <label for="image">画像ファイルを選択：</label>
    <input type="file" name="image" id="image" required><br><br>

    <button type="submit">アップロード</button>
</form>

</body>
</html>
