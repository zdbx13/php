<?php

require_once "model/Model.php";

/** Start session if not started */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name("loginSession");
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {

    // Verify user credentials
    $user = new User(0,$_POST["username"], $_POST["password"]);
    $credentials = (new Model())->selectCredentials($user);

    if ($credentials) {

        $_SESSION["username"] = $credentials->getUsername();
        $_SESSION["role"] = $credentials->getRole();
        $_SESSION["userData"] = serialize($credentials);
  
        // Redirect after setting session
        header("Location: index.php");
        exit();
    } else {

        // Show form with credentials introduced
        $data['username'] = $_POST['username'];
        $data['password'] = $_POST['password']; 
        $params['error'] = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <div>
        <h2>Login</h2>
        <form method="POST">
            <?php
                // If logged dispaly the error message
                if (isset($_SESSION['name'])) {
                    echo "<p>".$_SESSION['name']. ", are already logged.</p>";
                }
            ?>
            <br>

            <input type="hidden" name="action" value="login">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($data) && isset($data['username']) ? htmlspecialchars($data['username']) : ''; ?>">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo isset($data) && isset($data['password']) ? htmlspecialchars($data['password']) : ''; ?>">

             
            <?php
                // Display error message
                if (isset($params['error']) && !empty($params['error'])) {
                    echo "<label></label>";
                    echo "<p>Error: {$params['error']}</p>";
                }
            
                 
                echo "<label></label>";
                
                // Disable the button if logged
                if (isset($_SESSION['name'])) {
                    echo '<input type="submit" value="Login" disabled>';
                } else {
                    echo '<input type="submit" value="Login">';
                }
            

            ?>
        </form>
    </div>



</body>
</html>


