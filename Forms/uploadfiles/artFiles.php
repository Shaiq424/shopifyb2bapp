<?php
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
            // $Files = array();
            // $productid = array();
            // $art_file = $_FILES["art_file"]["name"];
            // if(isset($art_file)){
            //     array_push($Files,"https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/uploads/artfiles/".$fileName);
            // }
            // if(isset($_POST["productid"])){
            //     array_push($productid,$_POST["productid"]);
            // }
            // if(isset($Files) && isset($productid)){
            //     $ArrayFiles = array_combine($productid, $Files);
            // }
        if(move_uploaded_file($_FILES["art_file"]["tmp_name"], $targetFilePath)){ 
            // Success response 
            $response['result'] = array(
                "status" => "OK",
                "message" => "Art File Uploaded",
                "fileurl" => "https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/uploads/artfiles/".$fileName
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