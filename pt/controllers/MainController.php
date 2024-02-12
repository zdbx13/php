<?php

require_once "lib/ViewLoader.php";
require_once "model/Model.php";
//require_once "views/cart.php";


/**
 * Main controller
 */
class MainController {
    private $view;
    private $Model;
    private $action;


    public function __construct() {
        $this->view  = new viewLoader();
        $this->Model = new Model();
    }

    public function processRequest(){
        //var_dump($_SERVER['REQUEST_METHOD']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->processGetRequest();

        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processPostRequest();
        }
    }
    


    private function processGetRequest() {
        $this->action = "";
    
        if (filter_has_var(INPUT_GET, "action")){
            $this->action = filter_input(INPUT_GET, "action");
        }
        //var_dump($this->action);
    
        switch ($this->action) {
            case "home":
                $this->home();
                break;
            case "cart":
                $this->cart();
                break;
    
            case "listAllUsers":
                $this->listAllUsers();
                break;
            
            case "loginForm":
                $this->login();
                break;
            
            case "logout":
                $this->logout();
                break;
            
            case "listAllProducts":
                $this->listAllProducts();
                break;
            
            default:
                $this->home();
                break;
        }
    }
    
    private function processPostRequest() {
        $this->action = "";
        
        if (filter_has_var(INPUT_POST, "action")){
            $this->action = filter_input(INPUT_POST, "action");
        }

        if ($this->action == "updateProduct"   && isset($_POST['id'])) {
            $productId = new Product($_POST["id"]);
            $this->updateProduct($productId);
        }

        //var_dump($this->action);

        switch ($this->action) {
            case "buyProduct":
                $this->buyProduct();
                break;

            case "confirmBuy":
                $this->confirmBuy();
                break;

            case "cancelBuyForm":
                $this->cancelOrder();
                break;

            case "removeProduct":
                $this->removeProduct();
                break;
            
            case "cancel":
                $this->listAllProducts();
                break;

            case "update":
                $this->update();
                break;
            case "addProduct":
                $this->addProduct();
                break;
            case "login":
                $this->login();
                break;

            case "searchProduct":
                $this->listSelectProducts();
                break;

            
            default:  
                $this->home();
                break;

        }
    }

    /** Show home page */
    public function home(){
        $this->view->show("home.php", null);
    }
    
    /** Show login page */
       public function login(){
        $this->view->show("login.php", null);
    }

    /** Show cart page */
    public function cart(){
        $this->view->show("cart.php", null);
    }


    /** Show users list page */
    public function listAllUsers(){
        $userList = $this->Model->selectAllUsers();
        $data["userList"] = $userList;
        $this->view->show("list-users.php",$data);
    }


    /** Show products list page */
    public function listAllProducts(){
        $productList = $this->Model->selectAllProducts();
        $data["productList"] = $productList;
        $this->view->show("list-products.php",$data);
    }


    /** Delete the session and redirect to the index page */
    public function logout(){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name("login");
            session_start();
        }

        session_unset();
        session_destroy();
        header("Location: index.php");
        //$this->home();
    }

    
    /** Open the form to update the product and the product to show in the form.
     * @param Product product to show in the form.
     */
    public function updateProduct(Product $product){
        $fullProduct = $this->Model->selectProductId($product);
        $data["product"] = $fullProduct;
        $this->view->show("update-product.php",$data);
    }



    /** Update the product data */
    public function update(){

        $id = $_POST["id"];
        $code = $_POST["code"];
        $desc = $_POST["Description"];
        $price = $_POST["price"];
        
        $product = new Product($id,$code,$desc,$price);

        $codeUsed = $this->Model->selectProductCode(new Product(0,$code));
        $productId = $this->Model->selectProductId(new Product($id));

        if(!$codeUsed){
            $this->Model->updateProduct($product);
            $this->listAllProducts();
            $_SESSION["updated"] = "Product " . $code. " updated.";

        } elseif($code == $productId->getCode()){
            $this->Model->updateProduct($product);
            $this->listAllProducts();
            $_SESSION["updated"] = "Product " . $code. " updated.";

        } else {
            $_SESSION["updateError"] = "Code " . $code . " already in use.";
            $this->updateProduct($product);
        }    
    }


    /** Show the form to add a new product */
    public function addProduct(){
        $this->view->show("add-product.php", null);
    }

    /** Show the updated list product with the product founded */
    public function listSelectProducts(){
        $data = isset($_SESSION["search_results"]) ? $_SESSION["search_results"] : null;
        $this->view->show("list-products.php", $data);
    }

    
    /**
     * Delete the product selected
     */
    public function removeProduct(){
        $product = $this->Model->selectProductId(new Product($_POST["id"]));
        $delete = $this->Model->deleteProduct($product);

        if ($delete){
            $_SESSION["deleted"] = "Product " .$product->getCode(). " deleted.";
            //(new MainController())->listAllProducts();
    
        } else {
            $_SESSION["deleted"] = "An error ocurred";
        }

        $this->listAllProducts();
    }


    /**
     * Check the product quantity is more than 1 and save data in the session.
     */
    public function buyProduct(){

        /** Start session if not started */
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name("login");
            session_start();
        }

        $id = $_POST["id"];
        $quantity = $_POST["quantity"];
        $error = null;

        if ($quantity <1){
            $error = "The minimum number must be 1";
        }

        $product = $this->Model->selectProductId(new Product((int)$id));

        if ($error){
            $_SESSION["buyError"] = $error;

        } else {

            $sessionData = array();
            $_SESSION["buyProduct"] = isset($_SESSION["buyProduct"]) ? $_SESSION["buyProduct"] : null;
            
            /** If the session is null create an array and save the first product in the session. */
            if ($_SESSION["buyProduct"] == null) {
                $sessionData[] = array($product, $quantity);
                $_SESSION["buyProduct"] = serialize($sessionData);

            /** If the session is not null add the new product data at the end of the session. */
            } else {
                $productsList = isset($_SESSION["buyProduct"]) ? unserialize($_SESSION["buyProduct"]) : array();
                $sessionData = array($product, $quantity);

                $updatedList = array_merge($productsList, array($sessionData));
                $_SESSION["buyProduct"] = serialize($updatedList);
            }

            $_SESSION["buyError"] = "Product added to the cart";
        }
        
        $this->listAllProducts();

    }


    /** 
     * Delete the buyProduct session.
     */
    public function cancelOrder(){
        /** Start session if not started */
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name("login");
            session_start();
        }

        unset($_SESSION["buyProduct"]);

        $this->home();       
        
    }


    /**
     * Give the order data and insert them in the tables with the corresponding data
     */
    public function confirmBuy(){
        /** Start session if not started */
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name("login");
            session_start();
        }

        // Recive userDataBuy session data
        $userData = unserialize($_SESSION["userDataBuy"]);
        
        // Prepare to insert the new order
        $insertOrders = array($userData[3], (int)$userData[0]);
        $this->Model->insertOrder($insertOrders);

        // Select the new order id
        $maxId = $this->Model->selectMaxId();

        // Recive the BuyProductData session data
        $proudctsList = unserialize($_SESSION["BuyProductData"]);
        
        // Prepare to add the detais of the new order
        foreach ($proudctsList as $product){
            foreach ($product as $prd){
         
                $insertOrderDetails = array($maxId->getId(), $prd[0]->getId(), (float)$prd[1], $prd[0]->getPrice());
                $this->Model->insertOrderDetails($insertOrderDetails);
            }
        }

        $this->cart();
    }



}