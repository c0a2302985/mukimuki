<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    // トークン生成
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
</head>
<body>
    <?php
    if (!empty($_SESSION['login_error'])) {
        echo '<p style="color:red;">' . htmlspecialchars($_SESSION['login_error'], ENT_QUOTES, 'UTF-8') . '</p>';
        unset($_SESSION['login_error']);
    }
    ?>
    <form action="login_process.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>" />
        ユーザー名: <input type="text" name="username" required><br>
        パスワード: <input type="password" name="password" required><br>
        <input type="submit" value="ログイン">
        <a href="register.php">register</a>
    </form>
</body>
</html>
