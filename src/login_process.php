<?php
session_start();
require_once("common.php");

// CSRFトークンチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // トークンが一致しない場合は拒否
    die('不正なアクセスです。');
}

// ユーザー入力（POST）
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 入力チェック
if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = 'ユーザーネームとパスワードは必須です。';
    header('Location: login.html');
    exit;
}

// SQLとバインドパラメータ
$sql = "SELECT * FROM users WHERE username = :username";
$params = [
    ':username' => [$username, PDO::PARAM_STR],
];

$user = $db->fetch($sql, $params);

// パスワード照合（ハッシュ化パスワードを想定）
if ($user && password_verify($password, $user['password'])) {
    // ログイン成功時はセッションに保存し、エラーはクリア
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    unset($_SESSION['login_error']);
    unset($_SESSION['csrf_token']);  // ログイン後は破棄しても良い

    header('Location: index.php');
    exit;
} else {
    $_SESSION['login_error'] = 'ユーザー名またはパスワードが間違っています。';
    header('Location: login.php');
    exit;
}
?>
