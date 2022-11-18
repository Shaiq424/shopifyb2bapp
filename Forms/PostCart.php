<?php
    session_start();
    require_once("../db/config.php");
    if( $_SESSION['User'] != null){
        $value = $_POST['value'];
        $userId = $_POST['user'];
        
        $user = mysqli_query($conn,'select * from cart where userID='.$userId);
        if (mysqli_num_rows($user) > 0) {
            $user = "DELETE FROM cart where userID=$userId";
            if ($conn->query($user) === TRUE) {
                $insert = "INSERT INTO `cart`( `value`,`userID`) 
                VALUES ('$value','$userId')";
                if (mysqli_query($conn, $insert)) {
                    echo json_encode(array("statusCode"=>200));
                } 
                else {
                    echo json_encode(array("statusCode"=>201));
                }
            } else {
            echo "Error deleting record: " . $conn->error;
            }
        }else{
            $insert = "INSERT INTO `cart`( `value`,`userID`) 
            VALUES ('$value','$userId')";
           
            if (mysqli_query($conn, $insert)) {
                echo json_encode(array("statusCode"=>200));
            } 
            else {
                echo json_encode(array("statusCode"=>201));
            }
        }
    }
