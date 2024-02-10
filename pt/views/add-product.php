<script type="text/javascript">
// Manage the form actions and submit the form
function submitForm(action) {
    var myForm = document.getElementById('product-form');
    var actionInput = document.getElementById('action');

    if (action === 'add') {
        myForm.action = 'index.php?action=addProduct';
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

/** If action is  addProduct and have $_POST data add new product*/
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addProduct' && isset($_POST['code']) && $_POST["code"] != "" && $_POST["Description"] != "" && $_POST["price"] != "") {    

    $regex ='/^[0-9]+\.?[0-9]*$/';

    if (preg_match($regex, $_POST["price"])){
        $code = new Product(0,$_POST["code"]);
        $exist = (new Model())->selectProductCode($code);
        
        if (!$exist){
            $product = new Product(0,$_POST["code"], $_POST["Description"], (float)$_POST["price"]);
            $add = (new Model())->insertProduct($product);

            if ($add){
                $_SESSION["added"] = "Product ".$product->getCode()." added";
                header("Location: index.php?action=listAllProducts");  
            } else {
                $error = "Product not added";
            }
        } else {
            $error = "Product code already exist";
        }
    } else {
        $error = "Price must be a number. If is a decimal number add '.' to indiacte this.";
    }


  
}


/** If acction is cancel show all products */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'cancel') {

    (new MainController())->listAllProducts();
}


$product = $params['product']??null;
$action = $params['action']??"add";
$result = $params['result']??null;

if (is_null($product)) {
    $product = new Product(0, "","",0.0);
}

/** Show form*/
echo <<<EOT
   <form id="product-form" method="post">
    <fieldset>
        <label for="code">code: </label><input type="text" name="code" id="code" placeholder="enter code" value="{$product->getCode()}"/>
        <label for="Description">Description: </label><input type="text" name="Description" id="Description" placeholder="enter Description" value="{$product->getDescription()}"/>
        <label for="Price">Price: </label><input type="text" name="price" id="price" placeholder="enter price" value="{$product->getPrice()}"/>

   </fieldset>
    <fieldset>
        <button type="button" id="addProduct" name="addProduct" onclick="return submitForm('addProduct');">Add</button>
        <button type="button" id="cancelButton" name="cancel" onclick="submitForm('cancel');">Cancel</button>
        
        <input name="action" id="action" hidden="hidden" value="add"/>
    </fieldset>
EOT;


/** Show an error message */
if (isset($error) && $error != null) {
    echo "<label></label>";
    echo "<p>Error: {$error}</p>";    
}
echo "</form>";