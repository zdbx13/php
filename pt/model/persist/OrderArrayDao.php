<?php
require_once 'OrderDaoIntarface.php';
require_once "OrderPdoDbDao.php";

class OrderArrayDao implements OrderDaoIntarface {
    
    private static $instance = null;
    private $OrdersDb;
 
    private function __construct() {
        $this->OrdersDb = OrderPdoDbDao::getInstance();
    }

    /**
     * Singleton implementation of user DAO.
     * perfoms persistance in session.
     * @return ArrayOrdersDao the single instance of this object.
     */
    public static function getInstance() {
        //create instance and test data only if not stored in session yet.
        if (isset($_SESSION['OrdersDao'])) {
            self::$instance = unserialize($_SESSION['OrdersDao']);
        } else {
            self::$instance = new self();
            self::$instance->OrdersDb = null;
            $_SESSION['PrductDao'] = serialize(self::$instance);
        }

        // Reinitialize OrdersDb if it is null
        if (self::$instance->OrdersDb === null) {
            self::$instance->OrdersDb = OrderPdoDbDao::getInstance();
        }
        return self::$instance;
    }


     /**
     * retrieves all products from data source.
     * @return array of orders
     */
    public function selectDelMethods():array {
        return $this->OrdersDb->selectDelMethods();
    }


    /**
     * retrieves max id order from data source.
     * @return Order with the max id
     */
    public function selectMaxId():Order {
        return $this->OrdersDb->selectMaxId();
    }


    /**
     * Add a order in the data source.
     * @param array array with data to dadd in the order.
     * @return bool ture if added, false if not added.
     */
    public function insertOrder(array $order):bool{
        return $this->OrdersDb->insertOrder($order);
    }

    /**
     * Add order details in the data source.
     * @param array array with data to add.
     * @return bool ture if added, false if not added.
     */
    public function insertOrderDetails(array $order):bool{
        return $this->OrdersDb->insertOrderDetails($order);
    }

}