<?php
    require_once 'utility/PDOclass.php';

    // DB接続情報
    $host = 'db';
    $dbname = 'myapp';
    $user = 'myuser';
    $pass = 'mypass';

    $db = new Database($host, $dbname, $user, $pass);
?>