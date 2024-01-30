<?php
include_once 'model/Model.php';

// Instantiate model
$myModel = new Model();

print_r ("*****************PRODUCTS*****************".PHP_EOL);
/*
print_r ("***Select All***".PHP_EOL);

print_r("TEST: search all [find]".PHP_EOL);
$products = $myModel->selectAllProducts();
//print_r($users[0]);
foreach($products as $product){
    print_r($product);
}
print_r(PHP_EOL);
*/


print_r ("***Select ID***".PHP_EOL);

print_r("TEST: select Id [null]".PHP_EOL);
$result = $myModel->selectProductId(new Product());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select Id [not exist]".PHP_EOL);
$result = $myModel->selectProductId(new Product(24));
print_r($result);
print_r(PHP_EOL);


print_r("TEST: select Id [exist]".PHP_EOL);
$result = $myModel->selectProductId(new Product(2));
print_r($result);
print_r(PHP_EOL);


/*
print_r ("***Select CODE***".PHP_EOL);

print_r("TEST: select CODE [null]".PHP_EOL);
$result = $myModel->selectProductCode(new Product());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select CODE [not exist]".PHP_EOL);
$result = $myModel->selectProductCode(new Product(0, "P23"));
print_r($result);
print_r(PHP_EOL);


print_r("TEST: select CODE [exist]".PHP_EOL);
$result = $myModel->selectProductCode(new Product(2, "P2"));
print_r($result);
print_r(PHP_EOL);
*/

/*
print_r ("***Insert***".PHP_EOL);

print_r("TEST: Insert [null]".PHP_EOL);
$result = $myModel->insertProduct(new Product(0,"", "", 0.0));
print_r($result);
print_r(PHP_EOL);


print_r("TEST: Insert [not exist]".PHP_EOL);
$result = $myModel->insertProduct(new Product(0, "P15", "product 15", 15.00));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: Insert [code exist]".PHP_EOL);
$result = $myModel->insertProduct(new Product(0, "P1", "product1", 12.00));
print_r($result);
print_r(PHP_EOL);
*/

/*
print_r ("***Delete***".PHP_EOL);

print_r("TEST: delete [null]".PHP_EOL);
$result = $myModel->deleteProduct(new Product());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: delete [not exist]".PHP_EOL);
$result = $myModel->deleteProduct(new Product(21));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: delete [exist]".PHP_EOL);
$result = $myModel->deleteProduct(new Product(6));
print_r($result);
print_r(PHP_EOL);
*/

/*
print_r ("***Update***".PHP_EOL);

print_r("TEST: update [null]".PHP_EOL);
$result = $myModel->updateProduct(new Product());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: update [not update id, code]".PHP_EOL);
$result = $myModel->updateProduct(new Product(6, "P16","product1", 10.99));
print_r($result);
print_r(PHP_EOL);
*/



/*
print_r ("***Select Like***".PHP_EOL);

print_r("TEST: select like [null]".PHP_EOL);
$result = $myModel->selectLikeProduct(new Product());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select like [not exist description]".PHP_EOL);
$result = $myModel->selectLikeProduct(new Product(0, "","tasd", 0.0));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select like [exist description]".PHP_EOL);
$result = $myModel->selectLikeProduct(new Product(0, "","ct", 0.0));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select like [not exist code]".PHP_EOL);
$result = $myModel->selectLikeProduct(new Product(0, "ABC","", 0.0));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select like [exist code]".PHP_EOL);
$result = $myModel->selectLikeProduct(new Product(0, "1","", 0.0));
print_r($result);
print_r(PHP_EOL);

*/