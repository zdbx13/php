<?php
require_once "model/Product.php";
require_once "model/Model.php";
require_once 'controllers/MainController.php';

/** Start session if not started */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name("loginSession");
    session_start();
}
?>

<script>

/** Confirm to delete and submit the form */
function confirmDelete(productId) {
    var confirmDelete = confirm("Are you sure you want to delete?");
    if (confirmDelete) {
        var form = document.getElementById("removeForm" + productId);
        form.submit();
    }
}

/** submit the update form action */
function updateProduct(id) {
    var form = document.getElementById("updateForm" + id);
    form.submit();
}

/** Submit add form */
function addProduct() {
    var form = document.getElementById("addProduct");
    form.submit();
}

/** Submit search value */
function searchProduct() {
    var form = document.getElementById("searchProductForm");
    form.submit();
}
</script>

<h2>List all Products</h2>

<?php
/** If action si removeProduct delete the product and reload the list all products page  */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'removeProduct') {
    
    $productId = new Product((int)$_POST["id"]);
    $product = (new Model())->selectProductId($productId);
    $delete = (new Model())->deleteProduct($product);
    
    if ($delete){
        $_SESSION["deleted"] = "Product " .$product->getCode(). " deleted.";
    } else {
        $_SESSION["deleted"] = "An error ocurred";
    }
    
    header("Location: index.php?action=listAllProducts");
}

/** If action is update update product */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
            
    (new MainController())->updateProduct(new Product($_POST["id"]));
    exit();
}

/** If action is addProduct  */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addProduct') {
    
    (new MainController())->addProduct();
    //header("Location: index.php?action=updateProduct");
    exit();
}

/** If action is searchProduct make a select like product with the $_post data  */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'searchProduct') {
    
    $searchValue = isset($_POST["search"]) ? $_POST["search"] : "";
    
    $product = new Product(0, "", $searchValue, 0.0);
    $products = (new Model())->selectLikeProduct($product);

    $_SESSION["search_results"] = $products; 
}


/** If user role is admin show the search bar and the add button */
if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    
    // Search bar
    echo "<div class='search-container'>";
    echo "<form method='post' id='searchProductForm' action='index.php'>";
    echo "<input type='text' placeholder='Search description...' name='search'>";
    echo "<input type='hidden' name='action' value='searchProduct'>";
    echo "<button type='button' value='search' onclick='searchProduct()'>Search</button>";
    echo "</form>";
    echo "</div>";

    // Add button
    echo "<form method='post' id='addProduct'>";
    echo "<input type='hidden' name='action' value='addProduct'>";
    echo "<input type='button' value='add' onclick='addProduct()'>";
    echo "</form>";
    echo "<br><br>";
}


// Display products in the table.
$productList = null; 

/** If session search_results is defined show the session data, 
 * else if has productList defined take his value,
 * if search result is not defined and productList is null show an error message. 
  */
if (isset($_SESSION["search_results"]) && is_array($_SESSION["search_results"]) && !empty($_SESSION["search_results"])) {

    var_dump($_SESSION["search_results"]);
    $productList = $_SESSION["search_results"];
    unset($_SESSION["search_results"]);

} elseif (isset($params["productList"])) {
    $productList = $params["productList"];

} else {
    $_SESSION["search_show"] = "Product not found";
    
    $productList = [new Product(null,null,null,null)];
}

// Show table data
echo "<table>";
echo "<tr>";
//echo "<th>Id</th>";
echo "<th>Code</th>";
echo "<th>Description</th>";
echo "<th>Price</th>";

// If user role is admin show action field
if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    echo "<th colspan='2'>Actions</th>";
}

echo "</tr>";

foreach ($productList as $product) {

 
    // Fields
    echo "<tr>";
    //echo "<td><input type='text' disabled name='id' value='{$product->getId()}'></td>";
    echo "<td><input type='text' disabled name='code' value='{$product->getCode()}'></td>";
    echo "<td><input type='text' disabled name='description' value='{$product->getDescription()}'></td>";
    echo "<td><input type='text' disabled name='product' value='{$product->getPrice()}'></td>";

    // If user role is admin and $product is not null show action buttons.
    if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin" && $product->getId() != null) {
        
        // Delete button
        echo "<form method='post' id='removeForm{$product->getId()}'>";
        echo "<input type='hidden' name='id' value='{$product->getId()}'>";
        echo "<input type='hidden' name='action' value='removeProduct'>";
        echo "<td><input type='button' value='Remove' onclick='confirmDelete({$product->getId()})'></td>";
        echo "</form>";

        // Update button
        echo "<form method='post' id='updateForm{$product->getId()}'>";
        echo "<input type='hidden' name='id' value='{$product->getId()}'>";
        echo "<input type='hidden' name='action' value='updateProduct'>";
        echo "<td><input type='button' value='Update' onclick='updateProduct({$product->getId()})'></td>";
        echo "</form>";
    }
    echo "</tr>";
    echo "</form>";
}
echo "</table>";


$message = null;

// Display a message when product is deleted
if (isset($_SESSION["deleted"])){
    unset($_SESSION["search_show"]);
    $message = $_SESSION["deleted"];
    unset($_SESSION["deleted"]);
}

// Display a message when product is updated
if (isset($_SESSION["updated"])){
    unset($_SESSION["search_show"]);
    unset($_SESSION["deleted"]);
    $message = $_SESSION["updated"];
    unset($_SESSION["updated"]);
}

// Display a message when product is added
if (isset($_SESSION["added"])){
    unset($_SESSION["search_show"]);
    unset($_SESSION["deleted"]);
    $message = $_SESSION["added"];
    unset($_SESSION["added"]);   
}

// Display a message when product is not found
if (isset($_SESSION["search_show"])){
    unset($_SESSION["deleted"]);
    $message = $_SESSION["search_show"];
    unset($_SESSION["search_show"]);
}

echo $message;
?>
