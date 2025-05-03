<?php
session_start(); // セッション開始

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

// 登録SQL（プレーンなINSERT）
$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([
    ':username' => $username,
    ':password' => $password
]);

if ($result) {
    // 登録成功 → ユーザー情報を取得してセッションに保存 → top.phpへ遷移
    $user_id = $pdo->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    header('Location: top.php');
    exit;
} else {
    echo '登録失敗';
}
?>
