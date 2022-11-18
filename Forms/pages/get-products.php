<?php
    error_reporting(0);
    session_start();
    if ($_SESSION['User'] != null) {
        // require_once("../../inc/functions.php");
        // require_once("../../db/config.php");
        // include('../layouts/header.php');
        // $token = "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";
        // $shop = $_GET['shop'];
        // $query = array(
        //     "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
        // );
        // // Run API call to get all products
        // $products = shopify_call($token, $shop, "/admin/products.json", array('limit'=> 250), 'GET');
        // //$rand = "";
        // // Get response
        // $products = $products['response'];
        // $products = json_decode($products);
        // $products = $products->products;
        $api_url = 'https://ce31bb05c0c659b3ff1b26bf0766060d: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@atacsportwear-com.myshopify.com';
        $products_obj_url = $api_url . '/admin/products.json?limit=250';//&page='.($i+1);
        // echo $products_obj_url;
        $products_content = @file_get_contents( $products_obj_url );
        $products_json = json_decode( $products_content, true );
        $products = $products_json['products'];
        // print_r($products);
    }
?>