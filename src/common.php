<?php
    require_once 'utility/PDOclass.php';

    // DB接続情報
    $host = 'db';
    $dbname = 'myapp';
    $user = 'myuser';
    $pass = 'mypass';

    $db = new Database($host, $dbname, $user, $pass);

    function generate_csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function validate_csrf_token($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
?>