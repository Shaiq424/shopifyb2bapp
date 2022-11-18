<?php

     $hostName = "localhost";

     $userName = "root";

     $password = "";

     $dbName = "alcl";

     $conn= new mysqli($hostName,$userName,$password,$dbName);

     if($conn){

        //echo "connected";

     }else{

        echo "DB not connected";

     }

?>