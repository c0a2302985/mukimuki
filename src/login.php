<?php
// DB接続情報
$host = 'db'; // docker-composeのサービス名
$dbname = 'myapp';
$user = 'myuser';
$pass = 'mypass';

// ユーザー入力（仮：POSTで受け取る）
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 入力値チェック（ユーザーネームとパスワードが空でないか確認）
if (empty($username) || empty($password)) {
    echo 'ユーザーネームとパスワードは必須です。';
    exit; // 処理をここで終了
}

// DB接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die('DB接続失敗: ' . $e->getMessage());
}

// ユーザー名からパスワードを取得
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ユーザーが存在し、パスワードが一致するか確認
if ($user && $user['password'] === $password) {
    // セッション開始して、ログイン成功
    session_start();
    $_SESSION['user_id'] = $user['id']; // ユーザーIDをセッションに保存
    $_SESSION['username'] = $user['username']; // ユーザー名も保存
    echo 'ログイン成功';
} else {
    // ログイン失敗
    echo 'ユーザー名またはパスワードが間違っています';
}
?>
