<?php
session_start(); // セッション開始

// ログインしていない場合はアクセス拒否
if (!isset($_SESSION['user_id'])) {
    die('ログインしていません。先にログインしてください。');
}

// DB接続情報
$host = 'db';
$dbname = 'myapp';
$user = 'myuser';
$pass = 'mypass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die('DB接続失敗: ' . $e->getMessage());
}

// 検索キーワードの取得
$title_search = $_GET['title_search'] ?? ''; // タイトル検索
$comment_search = $_GET['comment_search'] ?? ''; // コメント検索
$username_search = $_GET['username_search'] ?? ''; // 作者名検索

// 検索用SQL（それぞれのフィールドに対してLIKEを使用）
$sql = "SELECT images.*, users.username FROM images 
        JOIN users ON images.user_id = users.id 
        WHERE images.title LIKE :title_search
        AND images.comment LIKE :comment_search
        AND users.username LIKE :username_search
        ORDER BY images.uploaded_at DESC";

// SQLの準備と実行
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':title_search' => '%' . $title_search . '%',
    ':comment_search' => '%' . $comment_search . '%',
    ':username_search' => '%' . $username_search . '%'
]);

// 検索結果を取得
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像検索</title>
</head>
<body>

<h2>画像検索</h2>

<!-- 検索フォーム -->
<form action="search.php" method="get">
    <div>
        <label for="title_search">タイトルで検索：</label>
        <input type="text" name="title_search" value="<?php echo htmlspecialchars($title_search); ?>" placeholder="タイトルで検索">
    </div>
    <div>
        <label for="comment_search">コメントで検索：</label>
        <input type="text" name="comment_search" value="<?php echo htmlspecialchars($comment_search); ?>" placeholder="コメントで検索">
    </div>
    <div>
        <label for="username_search">作者名で検索：</label>
        <input type="text" name="username_search" value="<?php echo htmlspecialchars($username_search); ?>" placeholder="作者名で検索">
    </div>
    <button type="submit">検索</button>
</form>

<!-- 検索条件の表示（空でない場合のみ表示） -->
<h3>検索条件:</h3>
<p>
    <?php if (!empty($title_search)): ?>
        <strong>タイトル:</strong> <?php echo htmlspecialchars($title_search); ?><br>
    <?php endif; ?>
    
    <?php if (!empty($comment_search)): ?>
        <strong>コメント:</strong> <?php echo htmlspecialchars($comment_search); ?><br>
    <?php endif; ?>
    
    <?php if (!empty($username_search)): ?>
        <strong>作者名:</strong> <?php echo htmlspecialchars($username_search); ?><br>
    <?php endif; ?>
</p>

<h3>検索結果</h3>

<?php
if (count($images) > 0) {
    foreach ($images as $image) {
        echo '<div>';
        echo '<h4>' . htmlspecialchars($image['title']) . '</h4>';
        echo '<p>' . htmlspecialchars($image['comment']) . '</p>';
        echo '<p>投稿者: ' . htmlspecialchars($image['username']) . '</p>';
        echo '<img src="' . htmlspecialchars($image['file_path']) . '" alt="' . htmlspecialchars($image['title']) . '" style="max-width: 300px;"/>';
        echo '</div><br>';
    }
} else {
    echo '該当する画像はありませんでした。';
}
?>

</body>
</html>
