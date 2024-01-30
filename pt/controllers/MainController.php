<?php

require_once "lib/ViewLoader.php";
require_once "model/Model.php";


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

        //var_dump($_POST);
        //var_dump($this->action);

        switch ($this->action) {

            case "removeProduct":
                $this->listAllProducts();
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

    /** Show users list page */
    public function listAllUsers(){
        $userList = $this->Model->selectAllUsers();
        $data["userList"] = $userList;
        $this->view->show("list-users.php",$data);
    }


    /** Show users list page */
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
    }

    
    /** Open the form to update the product and the product to show in the form.
     * @param Product product to show in the form.
     */
    public function updateProduct(Product $product){
        $fullProduct = $this->Model->selectProductId($product);
        $data["product"] = $fullProduct;
        $this->view->show("update-product.php",$data);
    }

    /** Show the list all products*/
    public function update(){
        $this->listAllProducts();
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

}