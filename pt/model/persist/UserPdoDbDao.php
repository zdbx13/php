<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once 'model/Model.php';
require_once 'UserDaoInterface.php';
require_once 'DbConnection.php';


class UserPdoDbDao implements UserDaoInterface {

    private static $instance = null;
    private $connection;
    private static $TABLE_NAME = 'users';
    private $queries;
    
    private function __construct() {
        try {
            //PDO object creation.
            $this->connection = (new DbConnection())->getConnection();  
              
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
                    "insert into %s values (:id, :username, :password, :role, :email, :dob)", 
                    self::$TABLE_NAME
            );
            $this->queries['UPDATE'] = \sprintf(
                    "update %s set password = :password, email = :email, dob = :dob  where id = :id", 
                    self::$TABLE_NAME
            );
            $this->queries['DELETE'] = \sprintf(
                    "delete from %s where id = :id", 
                    self::$TABLE_NAME
            );   
            $this->queries["SELECT_WHERE_CREDENTIALS"] = \sprintf(
                    "select * from %s where username = :username and password = :password",
                    self::$TABLE_NAME
            );
            $this->queries["SELECT_WHERE_USERNAME"] = \sprintf(
                    "select * from %s where username = :username",
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
     * @return DbUserDao the single instance of this object.
     */
    public static function getInstance() {
        if( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }  
    
    /**
     * Select an user from the BBDD searching for the username.
     * @param User user to search.
     * @return bool true if find or false if not find it.
     */
    public function selectUsername(User $username): bool {
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_WHERE_USERNAME']);
            $stmt->bindValue(':username', $username->getUsername(), PDO::PARAM_STR);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP

                    try {
                        $row = $stmt->fetchAll();
                        $data = true;

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
     * Select an user from the BBDD when the credentials are valid.
     * @param User user user to search.
     * @return User|bool return the user finded or false if no find any user. 
    */
    public function selectCredentials(User $user): User|bool {
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_WHERE_CREDENTIALS']);
            $stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP

                    try {
                        $row = $stmt->fetchAll();
                        //var_dump($row[0]["id"]);
                        $data = new User($row[0]['id'], $row[0]['username'], $row[0]['password'], $row[0]['role'], $row[0]['email'], new DateTime($row[0]['dob']));

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
            
                        // Map the results to the User object manually
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = new User($row['id'], $row['username'], $row['password'], $row['role'], $row['email'], new DateTime($row['dob']));
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
     * Insert an user in the BBDD.
     * @param User $user the user to add.
     * @return bool true if add the user, false if no add them.
     */
    public function insert(User $user): bool {
        $added = false;
        try {
            $stmt = $this->connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(':role', $user->getRole(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':dob', $user->getDob()->format('Y-m-d'), PDO::PARAM_STR);
            $success = $stmt->execute();
            $added  = $success?true:fasle;
        } catch (PDOException $e) {
            echo $e->getTraceAsString();
            $added  = false;
        }
        return $added ;
    }


    /**
     * Edit an user.
     * @param User user to edit.
     * @return bool.
     */
    public function update(User $user): bool {
        $numAffected = false;
        try {
            $stmt = $this->connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':dob', $user->getDob()->format('Y-m-d'), PDO::PARAM_STR);

            $success = $stmt->execute();
            $numAffected = $success?true:false;
        } catch (PDOException $e) {
            $numAffected = false0;
        }
        return $numAffected;  
    }

    /**
     * Delete an user from the BBDD.
     * @param User user to delete.
     * @return bool true if delete false if not delete.
     */
    public function delete(User $user): bool {
        $deleted = false;
        try {
            $stmt = $this->connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
            $success = $stmt->execute(); 
            $deleted = $success?true:false;
        } catch (PDOException $e) {
            $deleted = false;
        }
        return $deleted;        
    }    



    /**
     * Select an user from the id.
     * @param User user to search.
     * @return bool|User the user found or false if not found the user.
     */
    public function selectId(User $user):bool|User {
        $data = false;
        try {
            $stmt = $this->connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
            $success = $stmt->execute();
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC provides a consistent interface for accessing databases in PHP

                    try {
                        $row = $stmt->fetchAll();
                        //var_dump($row[0]["id"]);
                        $data = new User($row[0]['id'], $row[0]['username'], $row[0]['password'], $row[0]['role'], $row[0]['email'], new DateTime($row[0]['dob']));

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

}