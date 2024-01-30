<?php
require_once 'model/User.php';

/**
 * User persistence class.
 * @author Matí
 */
interface UserDaoInterface {
    
    /**
     * retrieves all users from data source.
     * @return array of users.
     */
    public function selectAll():array;


    /**
     * retrieves user matched with credentials from data source.
     * @param User user to check credentials.
     * @return User|bool return the user matched or false if user not found.
     */
    public function selectCredentials(User $user): User|bool;
    
    /**
     * retrieves user added  in the data source.
     * @param User user to add.
     * @return bool true if add the user, false if no add them or string message if user alreay exist.
     */
    public function insert(User $user): bool|string;

    /**
     * retrieves user deleted in the data source.
     * @param User user to delete
     * @return bool|string true if delete the user, false if no delete them, or string message if not found user.
     */
    public function delete(User $user): bool|string;

    /** 
     * retrieves user serched by id in the data source.
     * @param User user to select id.
     * @return bool|User user founded or false if not found user.
    */
    public function selectId(User $user): User|bool;

}