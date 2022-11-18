<?php



session_start();



if ($_SESSION['User'] != null) {



    require_once("../inc/functions.php");

    require_once("../db/config.php");

    include('layouts/header.php');

    $userId = $_SESSION['User'];

    $api_url = 'https://coderouting.com/alclapi/orders/readOrdersByUser.php?userID='.$userId;

    $json_data = file_get_contents($api_url);

    // Decode JSON data into PHP array

    $response_data = json_decode($json_data);

    $shop = $_GET['shop'];

    $token = "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";

    $query = array(

        "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format

    );



?>





        <?php include('layouts/headermenu.php'); ?>
        <style>
            .paginationBtn.active{
    font-weight: bold !important;
    background-color: black !important;
    color: white !important;
    }
    .paginationBtn:hover{
        font-weight: bold !important;
    background-color: black !important;
    color: white !important;
    }
        </style>


        <div class="row">



            <?php include('layouts/sidemenu.php'); ?>



            <div class="col-sm-9">



                <?php //$shopName = explode('.',$shop)[0];



                ?>



                <h1>Total Record : <span id="count"></span></h1>

                <table id="data" class="table table-bordered text-center">

                <thead>

                    <tr>

                        <th>Order#</th>

                        <th>Date</th>

                        <th>Name</th>

                        <th>Total ($)</th>

                        <th>Status</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php 

                        foreach($response_data as $res){

                    ?>

                        <tr class="rows">

                        <td><?php echo $res->shopifyorderNo; ?></td>

                        <td><?php $dt = new DateTime($res->shopifydate, new DateTimeZone('UTC'));

                                    $dt->setTimezone(new DateTimeZone('America/Denver'));

                                    echo $dt->format('F j, Y');

                        ?></td>

                        <td><?php echo ucfirst($res->OrderNo); ?></td>

                        <td><?php echo "$" .$res->order_total; ?></td>

                        <td>

                        <table class="table">

                            <thead>

                                <tr>

                                    <th>

                                        Art

                                    </th>

                                    <th>

                                        Roster

                                    </th>

                                    <th>

                                        Product

                                    </th>

                                    <th>

                                        Payment

                                    </th>

                                    <th>

                                        Fullfilled

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                

                                <tr>

                                    <td> <?php 

                                            $files = $res->art_file;

                                            $files = json_decode($files);

                                                ?>

                                                <ul style="list-style: none;" class="p-0">

                                                <?php

                                                foreach ($files as $itm){

                                                    

                                                ?>

                                                <?php $itmVal = substr($itm, strrpos($itm, '/') + 1); //$artfile = explode("artfiles/",$rec->art_file); ?>

                                                    <li><small><a href="<?php echo $itm; ?>">Download ART</a></small></li>

                                                <?php

                                                }

                                        ?>

                                        </ul>

                                    </td>

                                    <td>

                                       <?php

                                             $files = $res->roaster_file;

                                                $files = json_decode($files);

                                            ?>

                                            <ul style="list-style: none;" class="p-0">

                                            <?php

                                            foreach ($files as $itm){

                                                $itmVal = substr($itm, strrpos($itm, '/') + 1);

                                            ?>

                                                <li><small><a href="<?php echo $itm; ?>">Download Roster</a></small></li>

                                            <?php

                                                }

                                            

                                            ?>

                                            </ul>

                                    </td>

                                    <td>

                                    <ul style="list-style:none;" class="p-1">

                                    <?php
                                        //preg_match_all('`"([^"]*)"`', $res->productids, $results);
                                        $productids = unserialize ($res->productids);
                                        foreach($productids as $pro){

                                            $products = shopify_call($token, $shop, "/admin/products/".$pro.".json", array(), 'GET');

                                            // Get response

                                            $products = $products['response'];

                                            $products = json_decode($products);

                                            echo "<li>".$products->product->title;
                                            // $variationsids = unserialize($res->variationsids);//json_decode($ord->variationsids);
                                            
                                            // foreach ($variationsids as $variantID => $qty) {
                                            //     // echo $variantID. "_". $qty;
                                            //     $variant= shopify_call($token, $shop, "/admin/variants/" . $variantID . ".json", array(), 'GET');
                                            //     //GET Reponce
                                            //     $variant = $variant['response'];
                                            //     $variant = json_decode($variant);
                                            //     $variant = $variant->variant;
                                            //     echo "</li><p style='margin-bottom: 0px; padding: 0px;'><i><small style='font-weight:bold;'>Variation Name:</small></i></p><p><small>" . $variant->title . "</small><br /> X ". $qty ."=". $variant->price ."</p>";
                                            //     // foreach ($variant as $var) {
                                            //         // echo "<pre />";
                                            //         // print_r($var);
                                            //         // if ($var->id == $variantID) {
                                            //             // echo "</li><p style='margin-bottom: 0px; padding: 0px;'><i><small style='font-weight:bold;'>Variation Name:</small></i></p><p><small>" . $var->title . "</small></p>";
                                            //         // }
                                            //     // }
                                            // }
                                            $variants = shopify_call($token, $shop, "/admin/products/".$pro."/variants.json", array(), 'GET');

                                            //GET Reponce

                                            $variants = $variants['response'];

                                            $variants = json_decode($variants);

                                            $variant = $variants->variants;

                                            // $variationsids = json_decode($res->variationsids);
                                                $variationsids = unserialize($res->variationsids);
                                                
                                            foreach($variationsids as $variantID => $qty){
                                                    foreach($variant as $var){

                                                    if($var->id == $variantID){

                                                        echo "</li><p class='m-0 p-0'><i><small style='font-weight:bold;'>Variation Name:</small></i></p><p><small>".$var->title ."</small><br /> <b>Qty:</b> ". $qty ." <br /> <b>Price:</b> ". $var->price ." <br /> <b>Total:</b> ". $qty * $var->price ."</p>";

                                                    }

                                                }

                                            }

                                        }

                                    ?>

                                        </ul>

                                    </td>

                                    <td><?php echo "$".$res->payments; ?></td>

                                    <td><?php echo ucfirst($res->fullfilled); ?></td>

                                </tr>

                            </tbody>

                        </table>

                        </td>

                        </tr>

                    </tbody>

                    <?php

                        }

                    ?>

                </table>

                

            </div>



        </div>



    </div>



<?php



    include('layouts/footer.php');



} else {



    // header('Location:https://1529-202-47-32-187.ap.ngrok.io/alcl_App/Forms/login.php?shop=onlinedevelopmentstore.myshopify.com/');

header('Location:https://atacsportswear.com/account/');

}



?>



<script>



    var count = $(".rows").length;



    $("#count").html(count);

        $('#data').after ('<div id="nav" class="text-center mb-5"></div>');  
        var rowsShown = 6;  
        var rowsTotal = $('#data tbody tr').length;  
        var numPages = rowsTotal/rowsShown;  
        for (i = 0;i < numPages;i++) {  
            var pageNum = i + 1;  
            $('#nav').append ('<a href="#" rel="'+i+'" class="btn paginationBtn border">'+pageNum+'</a> ');  
        }  
        $('#data tbody tr').hide();  
        $('#data tbody tr').slice (0, rowsShown).show();  
        $('#nav a:first').addClass('active');  
        $('#nav a').bind('click', function() {  
        $('#nav a').removeClass('active');  
       $(this).addClass('active');  
            var currPage = $(this).attr('rel');  
            var startItem = currPage * rowsShown;  
            var endItem = startItem + rowsShown;  
            $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).  
            css('display','table-row').animate({opacity:1}, 300);  
        });  

</script>