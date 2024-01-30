<nav>
    <ul>
        <li><a href="index.php?action=home">Home</a></li>
        <li><a href="index.php?action=listAllProducts">List Products</a></li>

<?php
session_name("loginSession");
session_start();

// If user logged show the logout option and hidde the login option
if (isset($_SESSION["username"])) {

    //var_dump($_SESSION["username"]);
    //var_dump($_SESSION["role"]);

    // If user is admin show the listAllUsers page
    if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
        echo "<li><a href='index.php?action=listAllUsers'>List all users</a></li>";
    }
    echo "<li><a href='index.php?action=logout'>Logout</a></li>";
    echo "<li><p>". $_SESSION["username"]."</p></li>";

} else {
    echo "<li><a href='index.php?action=loginForm'>Login</a></li>";
}


?>

    </ul>
</nav>
