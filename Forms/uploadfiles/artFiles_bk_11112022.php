<?php

    // // print_r($_FILES['artfiles']);

    // $name =str_replace(' ', '',basename($_FILES["art_file"]["name"]));

    // // $fileName = time().'_'.$name; 

    // $fileName = $name; 

    // // File upload path 

    // $targetDir = "../uploads/artFiles/"; 

    // $targetFilePath = $targetDir . $fileName; 

    // // Allow certain file formats 

    // $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 

    // $allowTypes = array('AI', 'EPS', 'PDF', 'JPG', 'JPEG', 'ai', 'eps', 'pdf', 'jpg', 'jpeg'); 

    // if(in_array($fileType, $allowTypes)){ 

    //     // Upload file to server 

    //     if(move_uploaded_file($_FILES["art_file"]["tmp_name"], $targetFilePath)){ 

            

    //         // Success response 

    //         $response['result'] = array(

    //             "status" => "OK",

    //             "message" => "Art Files has been Uploaded",

    //             "fileurl" =>  " https://9ee3-119-152-124-98.in.ngrok.io/alcl_App/Forms/uploadfiles/".$targetFilePath

    //         );

    //     }else{ 

    //         $response['status'] = 'err'; 

    //     } 

    // }else{ 

    //     $response['result'] = array(

    //         "status" => "NOT",

    //         "message" => "Only AI,EPS,JPG Files Allowed...",

    //     );

    // } 

     

    // // Render response data in JSON format 

    // echo json_encode($response); 

     // print_r($_FILES);

  

    // print_r($_FILES);

    $name =str_replace(' ', '',basename($_FILES["art_file"]["name"]));

    $fileName = time().'_'.$name; 

    // File upload path 

    $targetDir = "../uploads/artfiles/"; 

    $targetFilePath = $targetDir . $fileName; 

    // Allow certain file formats 

    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 

    $allowTypes = array('AI', 'EPS', 'PDF', 'JPG', 'JPEG', 'ai', 'eps', 'pdf', 'jpg', 'jpeg');  

    if(in_array($fileType, $allowTypes)){ 

        // Upload file to server 

        if(move_uploaded_file($_FILES["art_file"]["tmp_name"], $targetFilePath)){ 

            

            // Success response 

            $response['result'] = array(

                "status" => "OK",

                "message" => "Art File Uploaded",

                "fileurl" => " https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/uploads/artfiles/".$fileName

            );

        }else{ 

            $response['status'] = 'err'; 

        } 

    }else{ 

        $response['result'] = array(

            "status" => "NOT",

            "message" =>"Only AI,EPS,JPG Files Allowed",

        );

    } 

     

    // Render response data in JSON format 

    echo json_encode($response); 

?>