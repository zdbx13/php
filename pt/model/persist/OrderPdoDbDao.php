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
            $this->queries['INSERT_ORDER'] = \sprintf(
                    "insert into %s (delMethod, customer) values (:delMethod, :customer)", 
                    self::$TABLE_NAME
            );
            $this->queries['SELECT_MAX_ID'] = \sprintf(
                    "SELECT MAX(id) AS max_id FROM %s", 
                    self::$TABLE_NAME
            );
            $this->queries['INSERT_ORDER_DETAILS'] = \sprintf(
                "insert into orderproducts (orderId, productId, quantity, unitPrice) values (:orderId, :productId, :quantity, :unitPrice)", 
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
     * @return DborderDao the single instance of this object.
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
            
                        // Map the results to the order object manually
                        $data = [];
                        foreach ($rows as $row) {
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




    /**
     * Select max id in the table. 
     * @return Order the order with the max id in the table.
    */
    public function selectMaxId(): Order{
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_MAX_ID']);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP

                    try {
                        $row = $stmt->fetchAll();
                        $data = new Order($row[0]['max_id']);

                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }  
                } else {
                    $data = false;
                }
            } else {
                $data = false;
            }

        } catch (PDOException $e) {
        }   
        return $data;
    }


    /**
     * Insert a order in the BBDD.
     * @param array with order data to  add.
     * @return bool true if add, false if no add them.
     */
    public function insertOrder(array $order): bool {
        $added = false;

        try {
            $stmt = $this->connection->prepare($this->queries['INSERT_ORDER']);
            $stmt->bindValue(':delMethod', $order[0], PDO::PARAM_STR);
            $stmt->bindValue(':customer', $order[1], PDO::PARAM_INT);
            $success = $stmt->execute();
            $added  = $success?true:fasle;
        } catch (PDOException $e) {
            echo $e->getTraceAsString();
            $added  = false;
        }
        return $added ;
    }


     /**
     * Insert a order  details in the BBDD.
     * @param array with order data to  add.
     * @return bool true if add, false if no add them.
     */
    public function insertOrderDetails(array $order): bool {
        $added = false;
        try {
            $stmt = $this->connection->prepare($this->queries['INSERT_ORDER_DETAILS']);
            $stmt->bindValue(':orderId', $order[0], PDO::PARAM_INT);
            $stmt->bindValue(':productId', $order[1], PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $order[2], PDO::PARAM_INT);
            $stmt->bindValue(':unitPrice', $order[3], PDO::PARAM_STR);
    
            $success = $stmt->execute();
            $added = $success ? true : false;
        } catch (PDOException $e) {
            echo $e->getTraceAsString();
            $added = false;
        }
    
        return $added;
    }
    
}
?>