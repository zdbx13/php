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
     * @return array of products
     */
    public function selectDelMethods():array {
        return $this->OrdersDb->selectDelMethods();
    }


}