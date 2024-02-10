<?php
require_once ("model/Product.php");
require_once ("model/User.php");
require_once ("model/Model.php");
require_once ("controllers/MainController.php");

?>
<script>
/** Confirm to delete and submit the form */
function confirmDelete() {
    var confirmDelete = confirm("Are you sure you want to delete the order?");
    if (confirmDelete) {

        var form = document.getElementById("cancelBuyForm");
        form.submit();
    }
}

</script>

<?php
/** If not registered redirect to index.php */
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["role"]) && $_SESSION["role"] != "registered" || $_SESSION["role"] == null ) {
    header("Location: index.php");
    //var_dump($_SESSION["username"]);
    exit();
}

/** Start session if not started */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name("login");
    session_start();
}


/** If buyProduct session is not empty display data */
if (isset($_SESSION["buyProduct"]) && $_SESSION["buyProduct"] != null) {


    /** Show user data */
    if (isset($_SESSION["userData"])) {
        $serializedUser = $_SESSION["userData"];
        $user = unserialize($serializedUser);

        $delOptions = (new Model())->selectDelMethods();
        
        echo "<label>Username: {$user->getUsername()}</label><br>";
        echo "<label>Email: {$user->getEmail()}</label><br>";
        echo "<label>Method: ";
        
        echo "<select name='delMethod' id='delMethod'>";
        
        for ($i = 0; $i < count($delOptions); $i++) {
            echo "<option value={$delOptions[$i]->getDelMethod()} >{$delOptions[$i]->getDelMethod()}</option>";
        }
        
        echo "</select></label><br><br>";


    } else {
        echo "User data not found in session.";
    }


    $data = null;

    $serializedProduct = $_SESSION["buyProduct"];
    //var_dump($serializedProduct);
    $data = unserialize($serializedProduct);

    /** Show data in table format */
    echo "<table>";
    echo "<tr>";
    echo "<th>Code</th>";   
    echo "<th>Description</th>";
    echo "<th>Price</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total price</th>";
    echo "</tr>";

    $totalValues = array();

    foreach ($data as $row) {

        $product = $row[0];
        $quantity = $row[1];

        /** Calculate the final price for the product and quantity */
        $totalValue = $product->getPrice() * $quantity;
        $totalValues[] = $totalValue;

        echo '<tr>';
        echo '<td>' . $product->getCode() . '</td>';
        echo '<td>' . $product->getDescription() . '</td>';
        echo '<td>' . $product->getPrice() . '</td>';
        echo '<td>' . $quantity . '</td>';
        echo '<td>' . ($totalValue) . '</td>';
        echo '</tr>';
    }
    
    /** Show the sum of all the prices */
    echo "<td colspan=4><td>".array_sum($totalValues)."</td>";
    echo '</table>';

    echo '<input type="submit" action="confirmBuy" value="Buy">';

    echo '<form id="cancelBuyForm" method="post" action="index.php?action=home">';
    echo '<input type="button" onclick="confirmDelete()" value="Cancel">';
    echo '<input type="hidden" name="action" value="cancelBuyForm">';
    echo '</form>';
    
    echo '<form method="get">';
    echo '<input type="hidden" name="action" value="listAllProducts">';
    echo '<input type="submit" value="Keep buying">';
    echo '</form>';

}else {
    
    /** Display a message */
    echo "No products added";
}


//echo "<form id='removeForm' method='post' action=''></form>"



?>