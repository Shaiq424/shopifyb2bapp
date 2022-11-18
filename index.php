<?php







    require_once("inc/functions.php");



    $shop_url = $_GET['shop'];







    header('Location: Forms/login.php?shop=' . $shop_url);







    //header('Location: install.php?shop=' . $shop_url);







    exit();







    







