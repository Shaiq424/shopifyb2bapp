<?php
require_once("../inc/functions.php");
include('layouts/header.php');
  $shop = $_GET['shop'];
  $token = "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";
  $query = array(
      "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
  );
  $args =[
      "id" => $_POST['id'],
      "quantity" => $_POST['quantity'],
  ]; 
?>