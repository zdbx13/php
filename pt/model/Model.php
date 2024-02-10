<?php
require_once 'User.php';
require_once 'persist/UserArrayDao.php';

require_once 'Product.php';
require_once 'persist/ProductArrayDao.php';

require_once 'Order.php';
require_once 'persist/OrderArrayDao.php';

/**
 * Service class to provide data.
 * @author Martí
 */
class Model {
    
    /**
     * ADO class to get access to data souce.
     * @var UserDaoInterface 
     */
    private $userDataSource;
    private $productDataSource;
    private $orderDataSource;
 
    public function __construct() {
        $this->userDataSource = UserArrayDao::getInstance();        
        $this->productDataSource = ProductArrayDao::getInstance();
        $this->orderDataSource = OrderArrayDao::getInstance();
    }




    /**
     * -------------------------------
     * USER
     * -------------------------------
     */


    /**
     * finds all user.
     * @return array list of user or empty array if none found.
     */
    public function selectAllUsers() {
        $user = $this->userDataSource->selectAll();
        return $user;
    }
    
    /**
     * Select user by credentials.
     * @param User user the user to search.
     * @return User|bool user who match with the credentials or nll if not found any.
    */
    public function selectCredentials(User $user):User|bool {
        
        if ($user->getUsername() !="" && $user->getPassword()!=""){
            $user = $this->userDataSource->selectCredentials($user);
        }
        
        return $user;
    }


    /**
     * Check the user fields to insert the user.
     * @param User user to insert.
     * @return bool true if user added or false if some required field is empy. 
     */
    public function insertUser(User $user):bool {
        $added = true;
        //var_dump($user);
        if (!is_null($user)){
            $check = true;
            
            // Check if all required has a value
            if ($user->getId() == "")      { $check = false; }
            if ($user->getUsername() == ""){ $check = false; }
            if ($user->getPassword() == ""){ $check = false; }
            if ($user->getRole() == "")    { $check = false; }
            if ($user->getEmail() == "")   { $check = false; }
            if ($user->getDob() == "")     { $check = false; }
               
            if ($check){
                $this->userDataSource->insert($user);
            }
        }

        return $added;
    }



    /**
     * Check the user to delete in the BBDD.
     * @param User user to delete.
     * @return bool true if user deleted or false if user id is empty.
     */
    public function deleteUser(User $user):bool {
        $deleted = true;

        if (!is_null($user)){
            if ($user->getId() == ""){ $deleted = false; }
        }

        if ($deleted){
            $this->userDataSource->delete($user);
        }

        return $deleted;
    }



    /**
     * Check the user to serch for id.
     * @param User user to check.
     * @return User|bool user serched or false if not found.
     */
    public function selectUserId(User $user):User|bool {
        $userId = false; 
        $check = true;

        if (!is_null($user)){
            if ($user->getId() == ""){ $check = false; }
        }

        if ($check){
            $userId = $this->userDataSource->selectId($user);
        }

        return $userId;
    }


    /**
     * Check the user to update.
     * @param User user to update.
     * @return bool true if user is updated or false if not updated.
     */

    public function updateUser(User $user):bool{
        $updated = false;
        $check = true;

        if (!is_null($user)){
            if ($user->getId() == "")      { $check = false; }
            if ($user->getPassword() == ""){ $check = false; }
            if ($user->getEmail() == "")   { $check = false; }
            if ($user->getDob() == "")     { $check = false; }
        }

        if ($check){
            $updated = $this->userDataSource->update($user);
        }

        return $updated;
    }



    /**
     * -------------------------------
     * PRODUCT
     * -------------------------------
     */

    /**
     * finds all products.
     * @return array list of products or empty array if none found.
     */
    public function selectAllProducts():array {
        $product = $this->productDataSource->selectAll();
        return $product;
    }


    /**
     * Select product from id
     * @param Product id to search.
     * @return Product|bool product found or false if none found.
     */
    public function selectProductId(Product $product):Product|bool {
        $productId = false; 
        $check = true;

        if (!is_null($product)){
            if ($product->getId() == ""){ $check = false; }
        }

        if ($check){
            $productId = $this->productDataSource->selectId($product);
        }

        return $productId;
    }


    /**
     * Select product from code
     * @param Product code to search.
     * @return Product|bool product found or false if none found.
     */
    public function selectProductCode(Product $product):Product|bool {
        $productCode = false; 
        $check = true;

        if (!is_null($product)){
            if ($product->getCode() == ""){ $check = false; }
        }

        if ($check){
            $productCode = $this->productDataSource->selectCode($product);
        }

        return $productCode;
    }

    
    /**
     * Check the product fields to insert the product.
     * @param Product product to insert.
     * @return bool true if product added or false if some required field is empy. 
     */
    public function insertProduct(Product $product):bool {
        $added = false;
        if (!is_null($product)){
            $check = true;
            
            // Check if all required has a value
            if ($product->getId() == "")         { $check = false; }
            if ($product->getCode() == "")       { $check = false; }
            if ($product->getDescription() == ""){ $check = false; }
            if ($product->getPrice() == "")      { $check = false; }
               
            if ($check){
                $added = $this->productDataSource->insert($product);
            }
        }
        return $added;
    }



    /**
     * Check the product id to delete the product.
     * @param Product product to delete.
     * @return bool true if product deleted or false if id is empy. 
     */
    public function deleteProduct(Product $product):bool {
        $delete = false;
        if (!is_null($product)){
            $check = true;
            
            if ($product->getId() == ""){ $check = false; }               
            
            if ($check){
                $delete = $this->productDataSource->delete($product);
            }
        }
        return $delete;
    }


    /**
     * Check the product to update.
     * @param Product product to update.
     * @return bool true if product updated or false if id is empy. 
     */
    public function updateProduct(Product $product):bool {
        $update = false;
        if (!is_null($product)){
            $check = true;

            if ($product->getId() == "")         { $check = false; }
            if ($product->getCode() == "")       { $check = false; }
            if ($product->getDescription() == ""){ $check = false; }               
            if ($product->getPrice() == "")      { $check = false; }  
            
            if ($check){
                $update = $this->productDataSource->update($product);
            }
        }   
        return $update;
    }

     
    /**
     * Check the product to select.
     * @param Product product to select.
     * @return bool|array array of products if find some something, false if not find.
     */
    public function selectLikeProduct(Product $product):bool|array {
        $select = false;
        if (!is_null($product)){
            /*
            if ($product->getCode() != "")       { 
                $select = $this->productDataSource->selectLikeCode($product); 
            }
            */
            if ($product->getDescription() != ""){ 
                $select = $this->productDataSource->selectLikeDescription($product); 
            }               
        }
        return $select;
    }




    /**
    * -------------------------------
    * ORDER
    * -------------------------------
    */

    /**
     * finds all diferents delivery methods in orders table.
     * @return array list of orders or empty array if none found.
     */
    public function selectDelMethods():array {
        $order = $this->orderDataSource->selectDelMethods();
        return $order;
    }
    
}
