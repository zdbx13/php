<?php

include_once 'model/Model.php';

// Instantiate model
$myModel = new Model();

print_r ("*****************ORDERS*****************".PHP_EOL);

/*
print_r ("***Select All Delivery Methods***".PHP_EOL);
print_r("TEST: search all [find]".PHP_EOL);
$orders = $myModel->selectDelMethods();

foreach($orders as $order){
    print_r($order);
}
print_r(PHP_EOL);
*/
/*
print_r ("***Select Bigger Order Id***".PHP_EOL);
print_r("TEST: search max id [find]".PHP_EOL);
$order = $myModel->selectMaxId();

print_r($order);

print_r(PHP_EOL);
*/

/*
print_r ("***Insert Order***".PHP_EOL);

print_r("TEST: Insert Order [null]".PHP_EOL);
$result = $myModel->insertOrder(array());
print_r($result);
print_r(PHP_EOL);


print_r("TEST: Insert Order [empty values]".PHP_EOL);
$result = $myModel->insertOrder(array( "", 2));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: Insert Order [correct]".PHP_EOL);
$result = $myModel->insertOrder(array('Click & Collect', 4));
print_r($result);
print_r(PHP_EOL);
*/

/*
print_r ("***Insert Order Details***".PHP_EOL);
print_r("TEST: Insert Order Details [null]".PHP_EOL);
$result = $myModel->insertOrder(array());
print_r($result);
print_r(PHP_EOL);


print_r("TEST: Insert Order Details [empty values]".PHP_EOL);
$result = $myModel->insertOrder(array( null, 2, 2, 2.2));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: Insert Order Details [correct]".PHP_EOL);
$result = $myModel->insertOrder(array(4,2,2, 4));
print_r($result);
print_r(PHP_EOL);
*/

?>