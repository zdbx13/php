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


}
?>