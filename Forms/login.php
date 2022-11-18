<?php

session_start();
require_once("../inc/functions.php");
unset($_SESSION["User"]);
$_SESSION['User'] =  $_GET['uid'];
$shop = $_GET['shop'];
include('layouts/header.php');
$shop = $_GET['shop'];
//$token = "shpua_94b8e3f880b6b4942434bfa17ebe7edd";
$token = "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";
$query = array(
    "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
);
// Run API call to get all products
$users = shopify_call($token, $shop, "/admin/api/2022-07/customers.json", array(), 'GET');
if (isset($_POST['submit'])) {
    $users = json_decode($users['response']);
    foreach ($users->customers as $value) {
        if ($_POST['username'] == $value->email) {
            $_SESSION['User'] = $value->id;
            header('Location:pages/productlist.php?shop=' . $shop);
        }
    }
}else{
        header('Location:pages/productlist.php?shop=' . $shop);
}
?>
<div class="container" style="margin-top: 150px;">

    <div class="row">
      
       <div class="col-md-12 text-center">
            <h1>Login</h1>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 text-center">

            <form method="post" action="">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Email" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="******" />
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" />
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
<?php
include('layouts/footer.php');

?>