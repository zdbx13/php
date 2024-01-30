<?php
include_once 'model/Model.php';

// Instantiate model
$myModel = new Model();

print_r ("*****************USERS*****************".PHP_EOL);

/*
print_r ("***Select All***".PHP_EOL);

print_r("TEST: search all [find]".PHP_EOL);
$users = $myModel->selectAllUsers();
//print_r($users[0]);
foreach($users as $user){
    print_r($user);
}
print_r(PHP_EOL);
*/


print_r ("***Select Credentials***".PHP_EOL);

print_r("TEST: select Credentials [null]".PHP_EOL);
$result = $myModel->selectCredentials(new User(0,"",""));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select Credentials [not exist]".PHP_EOL);
$result = $myModel->selectCredentials(new User(0,"user47", "pass2"));
print_r($result);
print_r(PHP_EOL);


print_r("TEST: select Credentials [exist]".PHP_EOL);
$result = $myModel->selectCredentials(new User(0,"user4", "pass4"));
print_r($result);
print_r(PHP_EOL);



/*
print_r ("***Insert***".PHP_EOL);

print_r("TEST: Insert [null]".PHP_EOL);
$result = $myModel->insertUser(new User(0,"", "", "", "", new DateTime("2000-10-02")));
print_r($result);
print_r(PHP_EOL);


print_r("TEST: Insert [not exist]".PHP_EOL);
$result = $myModel->insertUser(new User(0, "user15", "password15", "regitered", "email15@example.com", new DateTime("2000-10-02")));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: Insert [username exist]".PHP_EOL);
$result = $myModel->insertUser(new User(0, "user1", "password114", "regitered", "email114@example.com", new DateTime("2000-10-02")));
print_r($result);
print_r(PHP_EOL);
*/

/*
print_r ("***Delete***".PHP_EOL);

print_r("TEST: delete [null]".PHP_EOL);
$result = $myModel->deleteUser(new User());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: delete [not exist]".PHP_EOL);
$result = $myModel->deleteUser(new User(21));
print_r($result);
print_r(PHP_EOL);

print_r("TEST: delete [exist]".PHP_EOL);
$result = $myModel->deleteUser(new User(5));
print_r($result);
print_r(PHP_EOL);
*/


/*
print_r ("***Select ID***".PHP_EOL);

print_r("TEST: select Id [null]".PHP_EOL);
$result = $myModel->selectUserId(new User());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: select Id [not exist]".PHP_EOL);
$result = $myModel->selectUserId(new User(24));
print_r($result);
print_r(PHP_EOL);


print_r("TEST: select Id [exist]".PHP_EOL);
$result = $myModel->selectUserId(new User(2));
print_r($result);
print_r(PHP_EOL);
*/

/*
print_r ("***Update***".PHP_EOL);

print_r("TEST: update [null]".PHP_EOL);
$result = $myModel->updateUser(new User());
print_r($result);
print_r(PHP_EOL);

print_r("TEST: update [not update id, username, role]".PHP_EOL);
$result = $myModel->updateUser(new User(6, "user12","password114", "regitered", "email114@example.com", new DateTime("2000-10-02")) );
print_r($result);
print_r(PHP_EOL);
*/
