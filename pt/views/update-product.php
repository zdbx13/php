<script type="text/javascript">
/** Manage form actions and submit the form*/
function submitForm(action) {
    let myForm = document.getElementById('product-form');
    let actionInput = document.getElementById('action');

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
    exit();
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
        <input type="hidden" name="id" id="id" placeholder="enter id" value="{$product->getId()}"/>
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

echo "</form>";

/** Show an error message  */
$message = null;

if (isset($_SESSION["updateError"])){
    $message = $_SESSION["updateError"];
    unset($_SESSION["updateError"]);
}
echo "<br><br><br><br><br><br><br>".$message;

