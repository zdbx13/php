<?php
/**
 * 
 * Util class to connect webstoredb data base
 * Singleton pattern is applied
 *
 * @author ProvenSoft
 */
class DbConnection { 
    
    private static $dsn;
    private $opt;
    private $connection;
    private static $instance = null;
    
    private function __construct() {
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
    
    /**
     * Singleton implementation
     * @return DbConnection single instance of this object.
    */
    public static function getInstance() {
              
                if( self::$instance == null ) {
                    self::$instance = new self();
                }
                return self::$instance;
    } 

   
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Inits a transaction in current session
     */
    public function initTransaction() {
        $this->connection->beginTransaction();
    }
  

    /**
     * Ends a transaction in current session depending on $mode
     * @param String $mode COMMIT to commit current transaction, 
     *                     ROLLBACK to rollback current transaction 
     */
    public function endTransaction(String $mode) {
        switch ($mode){
            case 'COMMIT':
                $this->connection->commit();
                break;
            case 'ROLLBACK':
                $this->connection->rollBack();
                break;
        };
    }
}