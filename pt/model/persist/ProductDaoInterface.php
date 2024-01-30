<?php
require_once 'model/Product.php';

/**
 * Product persistence class.
 * @author Matí
 */
interface ProductDaoInterface {
    
    /**
     * retrieves all products from data source.
     * @return array of products.
     */
    public function selectAll():array;


    /**
     * retrieves id from product to search in data source.
     * @param Product id to search.
     * @return Product|bool product selected, false if not found.
     */
    public function selectId(Product $product):Product|bool;

    /**
     * retrieves code from product to search in data source.
     * @param Product code to search.
     * @return Product|bool product selected, false if not found.
     */
    public function selectCode(Product $product):Product|bool;

    /**
     * retrieves product to add in data source.
     * @param Product to add.
     * @return bool true if product added, or false if product code exist.
     */
    public function insert(Product $product):bool;

    /**
     * retrieves product to delete in data source.
     * @param Product to delete.
     * @return bool true if product deleted, false if product not deleted.
     */
    public function delete(Product $product):bool;

    /**
     * retrieves product to update in data source.
     * @param Product to update.
     * @return bool true if product updated, false if product not updated.
     */
    public function update(Product $product):bool;


    /**
     * retrieves product to select like description.
     * @param Product to select.
     * @return bool|Product product if find something, false if not found.
     */
    public function selectLikeDescription(Product $product):bool|array;

    /**
     * retrieves product to select like code.
     * @param Product to select.
     * @return array|bool array of products if find something, false if not found.
     */
    public function selectLikeCode(Product $product):bool|array;
}