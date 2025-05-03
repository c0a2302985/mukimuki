<?php
session_start(); // セッション開始（これが一番上）

// ログイン済みかどうか確認
if (!isset($_SESSION['user_id'])) {
    die('ログインしていません。');
}

$user_id = $_SESSION['user_id']; // ログイン中のユーザーIDを取得

// DB接続情報
$host = 'db'; // docker-composeのサービス名
$dbname = 'myapp';
$user = 'myuser';
$pass = 'mypass';

// // ユーザーID（仮に1とする、実際にはログインユーザーのIDを取得）
// $user_id = 1;

// 画像アップロード処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $title = $_POST['title'] ?? '';  // タイトル
    $comment = $_POST['comment'] ?? '';  // コメント

    // ファイル名の設定
    $fileName = bin2hex(random_bytes(16)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION); // ランダムなファイル名
    $uploadDir = __DIR__ . '/uploads/'; // アップロード先ディレクトリのパス
    $filePath = $uploadDir . $fileName;  // 保存先パス
    $filePath2 = '/uploads/' . $fileName;

    // // アップロード先のディレクトリがなければ作成
    // if (!is_dir($uploadDir)) {
    //     if (!mkdir($uploadDir, 0777, true)) {
    //         die('アップロードディレクトリの作成に失敗しました。');
    //     }
    // }

    // ファイルのアップロード
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // DB接続
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        } catch (PDOException $e) {
            die('DB接続失敗: ' . $e->getMessage());
        }

        // DBに画像情報を保存
        $sql = "INSERT INTO images (title, comment, file_name, file_path, user_id) VALUES (:title, :comment, :file_name, :file_path, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':comment' => $comment,
            ':file_name' => $fileName,
            ':file_path' => $filePath2,
            ':user_id' => $user_id
        ]);

        echo '画像がアップロードされました！';
    } else {
        echo '画像のアップロードに失敗しました。';
    }
}
?>
