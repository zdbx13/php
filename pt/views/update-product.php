<script type="text/javascript">
/** Manage form actions and submit the form*/
function submitForm(action) {
    var myForm = document.getElementById('product-form');
    var actionInput = document.getElementById('action');

    if (action === 'update') {
        myForm.action = 'index.php?action=update';
    } else if (action === 'cancel') {
        myForm.action = 'index.php?action=cancel';
    }

    actionInput.value = action;
    myForm.submit();
}
</script>

<?php
require_once "model/Model.php";
require_once 'controllers/MainController.php';

/** Start session if not started */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name("loginSession");
    session_start();
}


/** If not admin redirect to index.php */
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["role"]) && $_SESSION["role"] != "admin" || $_SESSION["role"] == null ) {
    header("Location: index.php");
    //var_dump($_SESSION["username"]);
    exit();
}


$error = null;

/** If acction is update , upate the product data and redirect to all products list*/
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
   
    $product = new Product((int)$_POST["id"],$_POST["code"], $_POST["Description"], $_POST["price"]);
    $update = (new Model())->updateProduct($product);

    if ($update){
        $_SESSION["updated"] = "Product ".$product->getCode()." updated";
        header("Location: index.php?action=listAllProducts");

    } else {
        $error = "Product not updated";
    }
}


/** If action is cancel show all products list  */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'cancel') {

    (new MainController())->listAllProducts();
}

$product = $params['product']??null;  
$action = $params['action']??"update";
$result = $params['result']??null;

if (is_null($product)) {
    $product = new Product(0, "","",0.0);
}
    
echo <<<EOT
   <form id="product-form" method="post">
    <fieldset>
        <label for="id">Id: </label><input type="text" name="id" id="id" placeholder="enter id" value="{$product->getId()}" />
        <label for="code">code: </label><input type="text" name="code" id="code" placeholder="enter code" value="{$product->getCode()}"/>
        <label for="Description">Description: </label><input type="text" name="Description" id="Description" placeholder="enter Description" value="{$product->getDescription()}"/>
        <label for="Price">Price: </label><input type="text" name="price" id="price" placeholder="enter price" value="{$product->getPrice()}"/>

   </fieldset>
    <fieldset>
        <button type="button" id="update" name="update" onclick="submitForm('update');">Modify</button>
        <button type="button" id="cancelButton" name="cancel" onclick="submitForm('cancel');">cancel</button>
        
        <input name="action" id="action" hidden="hidden" value="update"/>
    </fieldset>
EOT;


/** Show an error message  */
if (isset($error) && $error != null) {
    echo "<label></label>";
    echo "<p>Error: {$error}</p>";
}
echo "</form>";