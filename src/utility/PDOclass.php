///////////////////////////////////////////////////////////////////////////////
// クラス名称   : PDOクラス
// 処理内容     : SQLserverに接続・結果取得(MySQLを想定して記述されています)
// ファイル名称 : PDOclass.php
// 作成日付     : 2025/05 (基盤作成時)
// 備考         : このクラスはデータベース処理時における基本的な内容が記述されています。
//                アプリケーションに応じて適宜処理を変更してください
//                Ubuntu Serverで検証済み
///////////////////////////////////////////////////////////////////////////////

<?php

class Database
{
    
    private     $pdo;
    private     $stmt;
    
    /**********************************************************
      関数  ：__construct
      機能  ：初期化(データベース接続)
      引数  ：host              [IN] ホスト
              dbname            [IN] データベース名称
              user              [IN] ユーザー
              pass              [IN] パスワード
              charset           [IN] 文字コード
      戻り値：なし
      備考  ：接続時のエラー処理を追加してください。
              オプションを適宜追加してください。
    **********************************************************/
    public function     __construct($host, $dbname, $user, $pass, $charset='utf8mb4')
    {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // エラーは例外で処理
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // 連想配列で取得
            PDO::ATTR_EMULATE_PREPARES   => false,                  // プリペアドステートメントのエミュレーション無効
        ];

        try 
        {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        }
        catch (PDOException $e)
        {
            // pass
        }
    }
   
    /**********************************************************
      関数  ：__destruct
      機能  ：終了時の処理
      引数  ：なし
      戻り値：なし
      備考  ：
    **********************************************************/
    public function     __destruct()
    {
        // pass
    }
    
    /**********************************************************
      関数  ：bindParams
      機能  ：実行時のバインド処理
      引数  ：parms             [IN] バインドする内容
      戻り値：なし
      備考  ：
    **********************************************************/
    private function    bindParams(array parms = [])
    {
    
    }
    
    /**********************************************************
      関数  ：query
      機能  ：クエリの処理
      引数  ：sql                [IN] SQL文
            ：parms              [IN] バインドする内容
      戻り値：なし
      備考  ：
    **********************************************************/
    public function     query($sql, array parms = [])
    {
    
    }
    
    /**********************************************************
      関数  ：fetch
      機能  ：実行結果の取得
      引数  ：sql                [IN] SQL文
            ：parms              [IN] バインドする内容
      戻り値：実行結果
      備考  ：
    **********************************************************/
    public function     fetch($sql, array parms = [])
    {
        return      $this->query($sql, $parms)->fetch();
    }
    
    /**********************************************************
      関数  ：query
      機能  ：実行結果の取得
      引数  ：sql                [IN] SQL文
            ：parms              [IN] バインドする内容
      戻り値：実行結果
      備考  ：
    **********************************************************/
    public function     fetchAll($sql, array parms = [])
    {
        return      $this->query($sql, $parms)->fetchAll();
    }
    
    /**********************************************************
      関数  ：query
      機能  ：実行結果の取得
      引数  ：sql                [IN] SQL文
            ：parms              [IN] バインドする内容
      戻り値：実行結果
      備考  ：
    **********************************************************/
    public function     execute($sql, array parms = [])
    {
    
    }
    
    /**********************************************************
      関数  ：query
      機能  ：実行結果の取得
      引数  ：sql                [IN] SQL文
            ：parms              [IN] バインドする内容
      戻り値：実行結果
      備考  ：
    **********************************************************/
    public function rollback() 
    {
        return $this->pdo->rollBack();
    }
    
    
    
    
    
    
    
    
    /**********************************************************

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }
    
    **********************************************************/

}

?>