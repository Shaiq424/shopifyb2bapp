<?php

    // print_r($_FILES);

    $name =str_replace(' ', '',basename($_FILES["roasterfile"]["name"]));

    $fileName = time().'_'.$name; 

    // File upload path 

    $targetDir = "../uploads/roasterfiles/"; 

    $targetFilePath = $targetDir . $fileName; 

    // Allow certain file formats 

    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 

    $allowTypes = array('xlsx','XLSX'); 

    if(in_array($fileType, $allowTypes)){ 

        // Upload file to server 

        if(move_uploaded_file($_FILES["roasterfile"]["tmp_name"], $targetFilePath)){ 

            

            // Success response 

            $response['result'] = array(

                "status" => "OK",

                "message" => "Roster File Uploaded",

                "fileurl" => " https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/uploadfiles/".$targetFilePath

            );

        }else{ 

            $response['status'] = 'err'; 

        } 

    }else{ 

        $response['result'] = array(

            "status" => "NOT",

            "message" => "Only xlsx Files Allowed...",

        );

    } 

     

    // Render response data in JSON format 

    echo json_encode($response); 

?>