<?php

    session_start();

if( $_SESSION['User'] != null){

    require_once("../inc/functions.php");

    include('layouts/header.php');

    $shop = $_GET['shop'];

    $token = "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";

    $query = array(

        "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format

    );



    // Run API call to get all products

    $products = shopify_call($token, $shop, "/admin/products.json", array(), 'GET');

    

    // Get response

    $products = $products['response'];

    $products = json_decode($products);

    $products = $products->products;

    ?>

    




            <?php include('layouts/headermenu.php');?>



        <div class="row">



            <?php include('layouts/sidemenu.php');?>



            <div class="col-sm-9">



                <?php $shopName = explode('.',$shop)[0];?>



                <h1>Dashboard</h1>



                <h2>Welcome <?php echo strtoupper($shopName); ?></h2>



                    



            </div>



        </div>



    </div>

  <?php

      include('layouts/footer.php');

    }else{

        header('Location:https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/login.php?shop=atacsportwear-com.myshopify.com/');

    }

  ?>

