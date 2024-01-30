<?php
require_once 'ProductDaoInterface.php';
require_once "ProductPdoDbDao.php";

class ProductArrayDao implements ProductDaoInterface {
    
    private static $instance = null;
    private $ProductDb;
 
    private function __construct() {
        $this->ProductDb = ProductPdoDbDao::getInstance();
    }
 
    /**
     * Singleton implementation of user DAO.
     * perfoms persistance in session.
     * @return ArrayProductDao the single instance of this object.
     */
    public static function getInstance() {
        //create instance and test data only if not stored in session yet.
        if (isset($_SESSION['ProductDao'])) {
            self::$instance = unserialize($_SESSION['ProductDao']);
        } else {
            self::$instance = new self();
            self::$instance->ProductDb = null;
            $_SESSION['PrductDao'] = serialize(self::$instance);
        }

        // Reinitialize ProductDb if it is null
        if (self::$instance->ProductDb === null) {
            self::$instance->ProductDb = ProductPdoDbDao::getInstance();
        }
        return self::$instance;
    }


     /**
     * retrieves all products from data source.
     * @return array of products
     */
    public function selectAll():array {
        return $this->ProductDb->selectAll();
    }



    /**
     * Select product from id
     * @param Product id to search.
     * @return Product|bool product found or false if none found.
     */
    public function selectId(Product $product):Product|bool {
        return $this->ProductDb->selectId($product);
    }


    /**
     * Select product from id
     * @param Product id to search.
     * @return Product|bool product found or false if none found.
     */
    public function selectCode(Product $product):Product|bool {
        return $this->ProductDb->selectCode($product);
    }

    /**
     * retrieves product to add in the BBDD.
     * @param Product product to add.
     * @return bool true if product added or false if not added.
     */
    public function insert(Product $product):bool {

        $message = false;

        // Check if username exist
        $exist = $this->ProductDb->selectCode($product);

        if ($exist == false){
            $message = $this->ProductDb->insert($product);
        } 
        return $message;
    }


    /**
     * retrives product to delete in the BBDD.
     * @param Product id to delete.
     * @return bool true if deleted, false if not deleted.
     */
    public function delete(Product $product):bool {
        $deleted = false;

        $exist = $this->ProductDb->selectId($product);

        if($exist){
            $deleted = $this->ProductDb->delete($product);
        }
        return $deleted;
    }



    /**
     * retrives product to update in the BBDD.
     * @param Product product to update.
     * @return bool true if updated, false if not updated.
     */
    public function update(Product $product):bool {
        $update = $this->ProductDb->update($product);
        return $update;
    }
    
    /**
     * retrives product to select like description.
     * @param Product product to select.
     * @return bool|Product product if find some something, false if not find.
     */
    public function selectLikeDescription(Product $product):bool|array {
        $select = $this->ProductDb->selectLikeDescription($product);
        return $select;
    }
    
    /**
     * retrives product to select like code.
     * @param Product product to select.
     * @return bool|Product product if find some something, false if not find.
     */
    public function selectLikeCode(Product $product):bool|array {
        $select = $this->ProductDb->selectLikeCode($product);
        return $select;
    }
    
}