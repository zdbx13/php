<?php
require_once 'model/Model.php';
require_once 'OrderDaoIntarface.php';
require_once 'DbConnection.php';


class OrderPdoDbDao implements OrderDaoIntarface {

    private static $instance = null;
    private $connection;
    private static $TABLE_NAME = 'orders';
    private $queries;

    private function __construct() {
        try {
            //PDO object creation.
            $this->connection = DbConnection::getInstance()->getConnection();
              
            //query definition.
            $this->queries['SELECT_DISTINCT_DELMETHODS'] = \sprintf(
                    "SELECT DISTINCT delMethod FROM %s;", 
                    self::$TABLE_NAME
            );
            $this->queries['INSERT'] = \sprintf(
                    "insert into %s values (:id, :code, :description, :price)", 
                    self::$TABLE_NAME
            );
            $this->queries['UPDATE'] = \sprintf(
                    "update %s set code = :code, description = :description, price = :price where id = :id", 
                    self::$TABLE_NAME
            );
            $this->queries['DELETE'] = \sprintf(
                    "delete from %s where id = :id", 
                    self::$TABLE_NAME
            );   
                        
        } catch (PdoException $e) {
            print "Error Code <br>".$e->getCode();
            print "Error Message <br>".$e->getMessage();
            print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }        

    }


    /**
     * Singleton implementation of user ADO.
     * perfoms persistance in session.
     * @return DbProductDao the single instance of this object.
     */
    public static function getInstance() {
        if( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }  



  /**
     * Select all the orders in the orders table. 
     * @return array an empty array if not find nothing or an array with the orders founded.
    */
    public function selectDelMethods(): array {
        $data = array();
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_DISTINCT_DELMETHODS']);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP
            
                    try {
                        $rows = $stmt->fetchAll();
            
                        // Map the results to the Product object manually
                        $data = [];
                        foreach ($rows as $row) {
                            //var_dump($row);
                            $data[] = new Order(null, null, $row['delMethod']);
                        }
                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }   
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (PDOException $e) {
            echo $e->getTraceAsString(); 
        }   
        return $data;   
    }


}
?>