<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once 'model/Model.php';
require_once 'ProductDaoInterface.php';
require_once 'DbConnection.php';


class ProductPdoDbDao implements ProductDaoInterface {

    private static $instance = null;
    private $connection;
    private static $TABLE_NAME = 'products';
    private $queries;
    
    private function __construct() {
        try {
            //PDO object creation.
            $this->connection = DbConnection::getInstance()->getConnection();
              
            //query definition.
            $this->queries['SELECT_ALL'] = \sprintf(
                    "select * from %s", 
                    self::$TABLE_NAME
            );
            $this->queries['SELECT_WHERE_ID'] = \sprintf(
                    "select * from %s where id = :id", 
                    self::$TABLE_NAME
            );
            $this->queries['INSERT'] = \sprintf(
                    "insert into %s values (:id, :code, :description, :price)", 
                    self::$TABLE_NAME
            );
            $this->queries['UPDATE'] = \sprintf(
                    "update %s set code = :code, description = :description, price = :price where id = :id", 
                    self::$TABLE_NAME
            );
            $this->queries['DELETE'] = \sprintf(
                    "delete from %s where id = :id", 
                    self::$TABLE_NAME
            );   
            $this->queries["SELECT_WHERE_CODE"] = \sprintf(
                    "select * from %s where code = :code",
                    self::$TABLE_NAME
            );
            $this->queries["SELECT_LIKE_DESCRIPTION"] = \sprintf(
                    "select * from %s where description like :description",
                    self::$TABLE_NAME
            );
            $this->queries["SELECT_LIKE_CODE"] = \sprintf(
                "select * from %s where code like :code",
                self::$TABLE_NAME
            );

            
        } catch (PdoException $e) {
            print "Error Code <br>".$e->getCode();
            print "Error Message <br>".$e->getMessage();
            print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }        

    }


    /**
     * Singleton implementation of user ADO.
     * perfoms persistance in session.
     * @return DbProductDao the single instance of this object.
     */
    public static function getInstance() {
        if( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }  


    /**
     * Select all the users in the users table. 
     * @return array an empty array if not find nothing or an array with the users founded.
    */
    public function selectAll(): array {
        $data = array();
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_ALL']);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP
            
                    try {
                        $rows = $stmt->fetchAll();
            
                        // Map the results to the Product object manually
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = new Product($row['id'], $row['code'], $row['description'], $row['price']);
                        }
                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }   
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (PDOException $e) {
            echo $e->getTraceAsString(); 
        }   
        return $data;   
    }


    /**
     * Select a product from the id.
     * @param Product product to search.
     * @return bool|Product the product found or false if not found the product.
     */
    public function selectId(Product $product):Product|bool {
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $product->getId(), PDO::PARAM_INT);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP

                    try {
                        $row = $stmt->fetchAll();
                        //var_dump($row[0]["id"]);
                        $data = new Product($row[0]['id'], $row[0]['code'], $row[0]['description'], $row[0]['price']);

                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }  
                } else {
                    $data = false;
                }
            } else {
                $data = false;
            }

        } catch (PDOException $e) {
        }   
        return $data;
    }


    /**
     * Select a product from the code.
     * @param Product product to search.
     * @return bool|Product the product found or false if not found the product.
     */
    public function selectCode(Product $product):Product|bool {
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_WHERE_CODE']);
            $stmt->bindValue(':code', $product->getCode(), PDO::PARAM_STR);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP

                    try {
                        $row = $stmt->fetchAll();
                        //var_dump($row[0]["id"]);
                        $data = new Product($row[0]['id'], $row[0]['code'], $row[0]['description'], $row[0]['price']);

                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }  
                } else {
                    $data = false;
                }
            } else {
                $data = false;
            }

        } catch (PDOException $e) {
        }   
        return $data;
    }


    /**
     * Insert a product in the BBDD.
     * @param Product product to add.
     * @return bool true if add the user, false if no add them.
     */
    public function insert(Product $product): bool {
        $added = false;
        try {
            $stmt = $this->connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':id', $product->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':code', $product->getCode(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(':price', $product->getPrice(), PDO::PARAM_INT);
            $success = $stmt->execute();
            $added  = $success?true:fasle;
        } catch (PDOException $e) {
            echo $e->getTraceAsString();
            $added  = false;
        }
        return $added ;
    }



    /**
     * Delete a product from the BBDD.
     * @param Product product to delete.
     * @return bool true if delete false if not delete.
     */
    public function delete(Product $product): bool {
        $deleted = false;
        try {
            $stmt = $this->connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $product->getId(), PDO::PARAM_INT);
            $success = $stmt->execute(); 
            $deleted = $success?true:false;
        } catch (PDOException $e) {
            $deleted = false;
        }
        return $deleted;        
    }



    /**
     * Update product data.
     * @param Product product to edit.
     * @return bool.
     */
    public function update(Product $product): bool {
        $numAffected = false;

        try {
            $stmt = $this->connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':id', $product->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':code', $product->getCode(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(':price', $product->getPrice(), PDO::PARAM_INT);

            $success = $stmt->execute();
            $numAffected = $success?true:false;
        } catch (PDOException $e) {
            $numAffected = false;
        }
        
        return $numAffected;  
    }


    /**
     * Select product data like description.
     * @param Product product to select.
     * @return bool|Product product if find some something, false if not find.
     */
    public function selectLikeDescription(Product $product): bool|array {
        $data = false;
        //var_dump($product);
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_LIKE_DESCRIPTION']);
            //var_dump($stmt);
            $stmt->bindValue(':description', "%".$product->getDescription()."%", PDO::PARAM_STR);
            $success = $stmt->execute();
            //var_dump($success);
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP
            
                    try {
                        $rows = $stmt->fetchAll();
            
                        // Map the results to the Product object manually
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = new Product($row['id'], $row['code'], $row['description'], $row['price']);
                        }
                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }   
                } else {
                    $data = array();
                }
            } else {
                $data = false;
            }

        } catch (PDOException $e) {
            var_dump($e);
        }   
        return $data;
    }

    
    /**
     * Select product data like code.
     * @param Product product to select.
     * @return bool|Product product if find some something, false if not find.
     */
    public function selectLikeCode(Product $product): bool|array {
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_LIKE_CODE']);
            $stmt->bindValue(':code', "%".$product->getCode()."%", PDO::PARAM_STR);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP
            
                    try {
                        $rows = $stmt->fetchAll();
            
                        // Map the results to the Product object manually
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = new Product($row['id'], $row['code'], $row['description'], $row['price']);
                        }
                    } catch (PDOException $e) {
                        // Handle the exception
                        echo "Error: " . $e->getMessage();
                    }   
                } else {
                    $data = array();
                }
            } else {
                $data = false;
            }

        } catch (PDOException $e) {
        }   
        return $data;
    }
}