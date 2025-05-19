<?php
session_start(); // セッション開始（これが一番上）

require_once("common.php"); // クラス読み込み

// ログイン済みかどうか確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // ログイン中のユーザーIDを取得

// CSRFトークン検証
if (
    !isset($_POST['csrf_token']) ||
    !isset($_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    die('不正なリクエスト（トークン不一致）です。');
}

// 画像アップロード処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $title = $_POST['title'] ?? '';
    $comment = $_POST['comment'] ?? '';

    // ランダムなファイル名生成
    $fileName = bin2hex(random_bytes(16)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $uploadDir = __DIR__ . '/uploads/';
    $filePath = $uploadDir . $fileName;
    $filePath2 = '/uploads/' . $fileName;

    // アップロード先がなければ作成
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die('アップロードディレクトリの作成に失敗しました。');
        }
    }

    // ファイルのアップロード
    if (move_uploaded_file($file['tmp_name'], $filePath)) {

        // DBに画像情報を保存
        $sql = "INSERT INTO images (title, comment, file_name, file_path, user_id) 
                VALUES (:title, :comment, :file_name, :file_path, :user_id)";
        $params = [
            ':title'     => [$title, PDO::PARAM_STR],
            ':comment'   => [$comment, PDO::PARAM_STR],
            ':file_name' => [$fileName, PDO::PARAM_STR],
            ':file_path' => [$filePath2, PDO::PARAM_STR],
            ':user_id'   => [$user_id, PDO::PARAM_INT],
        ];

        $result = $db->execute($sql, $params);

        if ($result > 0) {
            header("Location: index.php"); // 自動でトップページに戻る
            exit(); // 必ず exit を書くs
        } else {
            $_SESSION['error'] = '画像情報の保存に失敗しました。';
            header("Location: upload_input.php");
            exit();
        }
    } else {
        $_SESSION['error'] = '画像のアップロードに失敗しました。';
        header("Location: upload_input.php");
        exit;
    }
}
?>
