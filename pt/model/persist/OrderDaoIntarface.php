<?php
require_once 'model/Order.php';

/**
 * Order persistence class.
 * @author Matí
 */
interface OrderDaoIntarface {
    
    /**
     * retrieves all distint delivery methods from orders data source.
     * @return array of orders.
     */
    public function selectDelMethods():array;
    
    /**
     * retrieves max id order from data source.
     * @return Order with the max id
     */
    public function selectMaxId():Order;

    /**
     * Add a order in the data source.
     * @param array array with data to add in the order.
     * @return bool ture if added, false if not added.
     */
    public function insertOrder(array $order):bool;


    /**
     * Add order details in the data source.
     * @param array array with data to add.
     * @return bool ture if added, false if not added.
     */
    public function insertOrderDetails(array $order):bool;

}
?>