<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/styles.css"> 
  </head>
  <body>
      <?php
        include "views/topmenu.php";
      ?>
      <?php
        //dynamic html content generated here by controller.
        require_once 'controllers/MainController.php';
        (new MainController())->processRequest();



      ?>
  </body>
</html>
