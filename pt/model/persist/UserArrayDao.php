<?php
require_once 'UserDaoInterface.php';
require_once "UserPdoDbDao.php";

class UserArrayDao implements UserDaoInterface {
    
    private static $instance = null;
    private $UserDb;
 
    private function __construct() {
        $this->UserDb = UserPdoDbDao::getInstance();
    }
 
    /**
     * Singleton implementation of user DAO.
     * perfoms persistance in session.
     * @return ArrayUserDao the single instance of this object.
     */
    public static function getInstance() {
        //create instance and test data only if not stored in session yet.
        if (isset($_SESSION['userDao'])) {
            self::$instance = unserialize($_SESSION['userDao']);
        } else {
            self::$instance = new self();
            self::$instance->UserDb = null;
            $_SESSION['userDao'] = serialize(self::$instance);
        }

        // Reinitialize UserDb if it is null
        if (self::$instance->UserDb === null) {
            self::$instance->UserDb = UserPdoDbDao::getInstance();
        }
        return self::$instance;
    }

    /**
     * retrieves all users from data source.
     * @return array of users
     */
    public function selectAll():array {
        return $this->UserDb->selectAll();
    }

    /**
     * retrieves user who match with credentials.
     * @param User credentials to serch the user.
     * @return User|bool user finded or false if not find any.
     */
    public function selectCredentials(User $user):User|bool {
        return $this->UserDb->selectCredentials($user);
    }

    /**
     * retrieves user to add in the BBDD.
     * @param User user to add.
     * @return bool|string true if user added, string message if user exist.
     */
    public function insert(User $user):bool|string {

        $message = false;

        // Check if username exist
        $exist = $this->UserDb->selectUsername($user);

        if ($exist == false){
            $message = $this->UserDb->insert($user);
        } else {
            $message = "Username already exist";
            var_dump($message);
        }

        return $message;
    }


    /**
     * retrieves user to delete from the BBDD.
     * @param user user to delete.
     * @return bool|string true if can delete the user, string message if user not found.
     */
    public function delete(User $user):bool|string {
        $message = false;
        $exist = $this->UserDb->selectId($user);

        if ($exist){
            $message = $this->UserDb->delete($user);
        } else {
            $message = "User not found";
            var_dump($message);
        }
        return $message;
    }
    

    /**
     * retrieves user selected by id.
     * @param user id from the user to search.
     * @return user|bool user finded or false if can't find any user.
     */
    public function selectId(User $user):User|bool{
        $exist = $this->UserDb->selectId($user);    
        return $exist;
    }

    /**
     * retrieves user to update.
     * @param user user to update.
     * @return bool true if user updated, false if can't update user.
     */
    public function update(User $user):bool {
        $updated = $this->UserDb->update($user);
        return $updated;
    }
    
}