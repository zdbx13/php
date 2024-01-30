<?php
/** If not admin redirect to index.php */
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["role"]) && $_SESSION["role"] != "admin" || $_SESSION["role"] == null ) {
    header("Location: index.php");
    //var_dump($_SESSION["username"]);
    exit();
}

?>
<table>
    <h2>List all users</h2>
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Role</th>
        <th>Email</th>
        <th>Date of birth</th>  
    </tr>
    <?php
    $userList = $params['userList'];
    // $params contains variables passed in from the controller.
    foreach ($userList as $user) {
        $dob = $user->getDob();
        //var_dump($dob instanceof DateTime);
        $fdob = $dob->format('Y-m-d');
        //var_dump($fdob);
        echo <<<EOT
        <tr>
            <td>{$user->getId()}</td>
            <td>{$user->getUsername()}</td>
            <td>{$user->getRole()}</td>
            <td>{$user->getEmail()}</td>
            <td>{$fdob}</td>
        </tr>               
EOT;
    }
    ?>
</table>
