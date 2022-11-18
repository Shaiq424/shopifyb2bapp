<?php

// Set variables for our request
$shop = $_GET['shop'];
// $api_key = "197e1fd30262981f7a8ccae54d8be8be";
$api_key = "ce31bb05c0c659b3ff1b26bf0766060d";
$scopes = "read_orders,write_products,read_customers";
$redirect_uri = "https://1699-154-6-26-100.ngrok.io/custom-b2b-app/token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);
// Redirect
header("Location: " . $install_url);
die();