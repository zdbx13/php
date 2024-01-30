<?php
class DbConnection { 
    
    private static $dsn;
    private $opt;
    private $connection;
    
    public function __construct() {
        //connection data.
        $host = 'localhost';
        $db = 'ptdb';
        $user = 'martiusr';
        $pass = 'martiPassword';
        $charset = 'utf8';
        self::$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $this->opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];
        //PDO object creation.
        $this->connection = new \PDO(self::$dsn, $user, $pass, $this->opt);
    }    
    
    public function getConnection() {
        return $this->connection;
    }
  
}