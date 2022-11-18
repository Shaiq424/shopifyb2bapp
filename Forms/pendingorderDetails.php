<?php

error_reporting(0);

session_start();

if ($_SESSION['User'] != null) {

    require_once("../inc/functions.php");

    include('layouts/header.php');

    $id = $_GET['id'];

    // Store the cipher method

    $ciphering = "AES-128-CTR";

    // Non-NULL Initialization Vector for decryption

    $decryption_iv = '1234567891011121';

    

    // Store the decryption key

    $decryption_key = "alcl_App";

    

    // Use openssl_decrypt() function to decrypt the data

    $decryption=openssl_decrypt ($id, $ciphering, 

            $decryption_key, $options, $decryption_iv);

 

    $userId = $_SESSION['User'];

    $api_url = 'https://coderouting.com/alclapi/cart/get_pendingcartDetails.php?id=' . $decryption;

    // Read JSON file

    $json_data = file_get_contents($api_url);

    // Decode JSON data into PHP array

    $response_data = json_decode($json_data);



    // All user data exists in 'data' object

    $pendingCart_data = (array)$response_data;



    // Cut long data into small & select only first 10 records

    $pendingCart_data = array_slice($pendingCart_data, 0, 9);



    //Getting Variation Details

    $shop = $_GET['shop'];

    $token = "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";

    $query = array(

        "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format

    );

    function cmp($a, $b) {

        return strcmp($a->productid, $b->productid);

    }

?>

    <div class="container">

        <?php include('layouts/headermenu.php'); ?>

        <div class="row">

            <?php include('layouts/sidemenu.php'); ?>

            <div class="col-sm-9">

                <input type="hidden" id="userId" value="<?php echo $userId; ?>" />

                <input type="hidden" id="cartid" value="<?php echo $decryption;?>" />

                <h1>Order Details</h1>

                <br />

                <div class="row">

                <div class="col-md-3">

                <?php

                    $orderNO ="";

                    if($pendingCart_data){

                        $total = 0;

                        foreach ($pendingCart_data as $PenCart_data) {

                            

                            $PenCart_data = json_decode(json_encode($PenCart_data), true);

                            foreach ($PenCart_data as $data) {

                                $orderNO = $data['order_no'];

                            }

                            // echo "PO NO". $orderNO;

                        }

                    }

                ?>

                <input type="text" value="<?php echo $orderNO;?>" id="orderno" class="d-none"/>

                <table class="table thead-dark" >

                <thead class="text-center table-bordered">

                        <tr>

                            <th><?php echo 'Files'; ?></th>

                        </tr>

                    </thead>

                    <tbody class="text-center table-bordered">

                        

                <?php

        

            if($pendingCart_data){

                $total = 0;

                foreach ($pendingCart_data as $PenCart_data) {

                    

                    $PenCart_data = json_decode(json_encode($PenCart_data), true);

                    foreach ($PenCart_data as $data) {

                        $orderNO = $data['order_no'];

                        $value = json_decode($data['value']);

                        usort($value, "cmp");

                        $arr = [];

                        foreach($value as $val){

                            array_push($arr,$val->productid);

                        }

                        $arr = array_unique($arr);

                        foreach ($arr as $a){

                            // Run API call to get specific product

                            $products = shopify_call($token, $shop, "/admin/products/". $a .".json", array(), 'GET');

                                    

                            // Get response

                            $products = $products['response'];

                            $products = json_decode($products);

                            $products = $products->product;

                            ?> 

                            

                            <tr  class="product">

                               

                            <td>

                            <?php echo ucfirst($products->title);?> Files

                            <br />

                            <div class="w-100" id="proID">

                                         <?php

                                            $api_url = 'https://coderouting.com/alclapi/cartfiles/readFilebyProId_Items.php?productID=' . $products->id ."&userID=".$_SESSION['User'];

                                            // Read JSON file

                                            $json_data = file_get_contents($api_url);

                                            // Decode JSON data into PHP array

                                            $response_data = json_decode($json_data);

                                        

                                            // All user data exists in 'data' object

                                            $files_data = (array)$response_data;

                                            // print_r($files_data);

                                            if($files_data['records'][0]->error == 1){

                                                echo "<b>".$files_data['records'][0]->message."</b>";

                                            } else {

                                                    foreach($files_data['records'] as $rec){

                                                        if($rec->roaster_file){

                                                ?>

                                                <!--Removed from line below-->

                                                <?php 

                                                // echo $products->title;

                                                ?>

                                                <br />

                                                        <a href="<?php echo $rec->roaster_file; ?>" class="text-dark">Roster File</a>

                                                <?php

                                                        }

                                                        if($rec->art_file){

                                            

                                            ?>

                                                <!--Removed from line below-->

                                                <?php 

                                                // echo $products->title;

                                                ?>

                                                <a href="<?php echo $rec->art_file; ?>" target="_blank" class="text-dark">Art File</a>

                                                <br />

                                                <?php

                                                        }

                                                 }

                                            }

                                        ?>

                                       </div>

                            

                                       <div class="w-100 my-2">

                                            <div class="div file btn btn-primary rounded-0">

                                                    <small>Update Roster</small> 

                                                <input type="file" class="input roaster" id="roaster<?php echo $products->id; ?>" name="upload_roster" onchange="roaster()" />

                                            </div>

                                       </div>

                                        <div class="w-100 my-2">

                                            <div class="div file btn btn-primary rounded-0">

                                                <small>Update ART</small>

                                                <input type="file" class="input art" id="art<?php echo $products->id; ?>" name="upload_art" onchange="art()" />

                                            </div>

                                            <p id="Error_<?php echo $products->id; ?>" class="m-0"></p>

                                            <input type="hidden" class="days" data-id="<?php echo $products->id; ?>" id="productId<?php echo $products->id; ?>" value="<?php echo $products->id; ?>" />

                                       </div>

                                       </td>

                    </tr>

                            <?php

                        }

                        

                    }

                }

            }

                ?>

                    </tbody>

                </table>

                </div>

                <div class="col-md-9">

                <table class="table thead-dark" id="orderdetails">

                    <thead class="text-center table-bordered">

                        <tr>

                            <th><?php echo 'Image'; ?></th>

                            <th><?php echo 'Product'; ?></th>

                            <th><?php echo 'Quantity'; ?></th>

                            <th><?php echo 'Price'; ?></th>

                            <th><?php echo 'Amount'; ?></th>

                        </tr>

                    </thead>

                    <tbody class="text-center table-bordered">

                    <?php

                       

                       

                        if($pendingCart_data){

                            $total = 0;

                            foreach ($pendingCart_data as $PenCart_data) {

                                $PenCart_data = json_decode(json_encode($PenCart_data), true);

                                foreach ($PenCart_data as $data) {

                                    $value = json_decode($data['value']);

                                    usort($value, "cmp");

                                    foreach($value as $val){

                                        

                                        // Run API call to get specific product

                                        $products = shopify_call($token, $shop, "/admin/products/". $val->productid .".json", array(), 'GET');

                                    

                                        // Get response

                                        $products = $products['response'];

                                        $products = json_decode($products);

                                        $products = $products->product;

                                    

                                ?>

                                <tr>

                                    <td>

                                        <img class="img-fluid img-thumbnail rounded-0" src="<?php echo $products->images[0]->src; ?>" width="100" height="100" alt="<?php echo $products->title; ?>"/>

                                        <br />

                                        <?php echo ucfirst($products->title);?>

                                    </td>

                                    <td>

                                        <?php

                                            $variants = shopify_call($token, $shop, "/admin/variants/". $val->variationid .".json", array(), 'GET');

                                            $variants = $variants['response'];

                                            $variants = json_decode($variants);

                                            echo $variants->variant->title;

                                        ?>

                                    </td>

                                    <td>

                                        <?php

                                            echo $val->quantity;

                                        ?>

                                    </td>

                                    <td>

                                        <?php

                                            // Run API call to get specific product

                                            $variants = shopify_call($token, $shop, "/admin/variants/". $val->variationid .".json", array(), 'GET');

                                            $variants = $variants['response'];

                                            $variants = json_decode($variants);

                                            echo '$ '.$variants->variant->price;

                                            // echo $variants->variant->price;

                                        ?>

                                    </td>

                                    <td>

                                        <?php

                                            // Run API call to get specific product

                                            $variants = shopify_call($token, $shop, "/admin/variants/". $val->variationid .".json", array(), 'GET');

                                            $variants = $variants['response'];

                                            $variants = json_decode($variants);

                                            $total = $variants->variant->price * $val->quantity;

                                            echo '$ '. number_format($total,2,".",",");

                                        ?>

                                        <input type="text" style="display:none;" value="<?php echo $total; ?>" class="txtCal" />

                                    </td>

                                </tr>

                            <?php

                             

                                        }

                                    }

                                }

        

                        }else{

                            ?>

                            <tr> 

                                <td colspan="6">

                                    <?php echo "NO Items Found.."?>

                                </td>

                            </tr>

                            <?php

                        }

                    ?>

                    </tbody>

                    <?php 

                        if($pendingCart_data){

                    ?>

                    <tfoot>

                        <tr class="text-center">

                            <td>&nbsp;</td>

                            <td>&nbsp;</td>

                            <td><b>Total Amount:</b></td>

                            <td> <label id="total_sum_value"></label></td>

                            <td>

                              

                               <input type="hidden" id="cartValue" />

                                <button class="btn scpBtn rounded-0" id="addCart">Check out</button>

                                <br/>

                                <lable id="message"></label>

                            </td>

                        </tr>

                    </tfoot>

                    <?php

                        }

                    ?>

                </table>

                </div>

                </div>

            </div>

        </div>

    </div>

    </div>

<?php

    include('layouts/footer.php');

} else {

    header('Location:https://780c-154-6-19-146.ngrok.io/alcl_App/Forms/login.php?shop=onlinedevelopmentstore.myshopify.com');

}

?>

<script>

    var count = $("#total").val();

    var span = 1;

    var prevTD = "";

    var prevTDVal = "";

    var userId= $("#userId").val();

    $("#count").html(count);

    $(document).ready(function() {

        var userId = $("#userId").val();

        var cartid = $("#cartid").val();

        var cartValue = $('#cartValue').val();

        $.ajax({

            url: 'https://coderouting.com/alclapi/cart/getcartbyid.php?cart_id='+ cartid,

            type: "GET"

        }).then(function(data) {

            $('#cartValue').val(data.value);

        });

        $("#addCart").click(function() {

            // alert(userId);

            var items = $('#cartValue').val();

            var uri = "https://coderouting.com/alclapi/cart/recreate.php";

            $.ajax({

                url: uri,

                type: "POST",

                data: JSON.stringify({

                    userID: userId,

                    cart_id: cartid,

                    value: items

                }),

            }).then(function(data) {

                setTimeout(function() {

                    $('#message').fadeOut('slow');

                }, 2000);

                setTimeout(function() {

                    window.location.href="https://atacsportswear.com/cart";

                }, 2000);

                $('#message').html(data.message);

                $('#message').css("font-size", "14px");

                $('#message').css("color", "green");

            });

        });

            var calculated_total_sum = 0;

            

            $("#orderdetails .txtCal").each(function () {

                var get_textbox_value = $(this).val();

                if ($.isNumeric(get_textbox_value)) {

                    calculated_total_sum += parseFloat(get_textbox_value);

                    }                  

                });

            calculated_total_sum = calculated_total_sum;

            calculated_total_sum = Number(calculated_total_sum).toFixed(2);

            calculated_total_sum =  "$ "+ calculated_total_sum;

            $("#total_sum_value").html(calculated_total_sum);

    });

    //For single columns for Same data first td

    $("#orderdetails tr td:first-child").each(function() { //for each first td in every tr

                var $this = $(this);

                if ($this.text() == prevTDVal) { // check value of previous td text

                    span++;

                    if (prevTD != "") {

                        prevTD.attr("rowspan", span); // add attribute to previous td

                        $this.remove(); // remove current td

                    }

                } else {

                    prevTD     = $this; // store current td 

                    prevTDVal  = $this.text();

                    span       = 1;

                }

            });



            // For single columns for Same data last td

            // $("#orderdetails tr td:last-child").each(function() { //for each last td in every tr

            //     var $this = $(this);

            //     if ($this.text() == prevTDVal) { // check value of previous td text

            //         span++;

            //         if (prevTD != "") {

            //             prevTD.attr("rowspan", span); // add attribute to previous td

            //             $this.remove(); // remove current td

            //         }

            //     } else {

            //         prevTD     = $this; // store current td 

            //         prevTDVal  = $this.text();

            //         span       = 1;

            //     }

            // });

    function roaster(e) {

            $('.product').each(function() {

                var $productId = $(this).find('input[type=hidden]');

                var cart_id = $("#cartid").val();

                 var orderno = $("#orderno").val();

                var $filenameroaster = $(this).find("#roaster" + $productId.val());

                var $Error_ = $(this).find("#Error_" + $productId.val());

                if ($filenameroaster[0].files[0] != null) {

                    roaster_files = $filenameroaster[0].files[0];

                    var formData1 = new FormData();

                    formData1.append('roasterfile', roaster_files);

                    $.ajax({

                        type: "POST",

                        url: "uploadfiles/roasterFiles.php",

                        contentType: false,

                        processData: false,

                        data: formData1,

                        success: function(data) {

                            if (JSON.parse(data).result.status == "NOT") {

                                $Error_.html(JSON.parse(data).result.message);

                                $Error_.css("color", "red");

                                return false;

                            } else if (JSON.parse(data).result.status == "OK") {

                                var fileUploded = JSON.parse(data).result.fileurl;

                                $.ajax({

                                    url: "https://coderouting.com/alclapi/cartfiles/updateRoaster_Item.php",

                                    type: "POST",

                                    data: JSON.stringify({

                                        "user_id": userId,

                                        "product_id": $productId.val(),

                                        "roaster_file": fileUploded,

                                        "cart_id": cart_id

                                    }),

                                    success: function(dataResult) {

                                        if (dataResult['error'] == "1") {

                                            $Error_.html(dataResult['message']);

                                            $Error_.css("color", "red");

                                        } else {

                                            setTimeout(function() {

                                                $.ajax({

                                                url: "https://coderouting.com/alclapi/cartfiles/updatewp_Roaster_Item.php",

                                                type: "POST",

                                                data: JSON.stringify({

                                                    "roaster_file": fileUploded,

                                                    "orderno" : orderno

                                                }),

                                                success: function(dataResult) {

                                                    if (dataResult['error'] == "1") {

                                                        $Error_.html(dataResult['message']);

                                                        $Error_.css("color", "red");

                                                    } else {

                                                        // setTimeout(function() {

                                                            location.reload();

                                                        // },2000);

                                                    }

                                            }

                                        });

                                            },2000);

                                        }

                                    }

                                });

                                $('.qtysuccess').html("");

                                setTimeout(function() {

                                    $Error_.fadeOut('slow');

                                }, 2000);

                                $Error_.html(JSON.parse(data).result.message);

                                $Error_.css("color", "green");

                                return false;

                            }

                        }

                    });

                    $filenameroaster.val("");

                }

            });

        }

        $("#foo").on('click', '.edit', function(){

                var tr = $(this).closest('tr'), 

                    rowspan = 0,

                    text, td;



                while(tr.length && !rowspan ) {

                    td = tr.find('td:first');

                    rowspan = td.attr('rowspan');

                    tr = tr.prev();             

                }



                alert(td.find("#days").val());

            })



        function art(e) {

            $('.product').each(function() {

                var $productId = $(this).find('input[type=hidden]');

                var cart_id = $("#cartid").val();
                
                var orderno = $("#orderno").val();
                
                var art = "#art" + $productId.val();

                var $filenameart = $(this).find(".art");

                var $filenameroaster = $(this).find("#roaster" + $productId.val());

                var $Error_ = $(this).find("#Error_" + $productId.val());

              

                if ($filenameart[0].files[0] != null) {

                    art_file = $filenameart[0].files[0];

                    console.log(art_file);

                    var formData1 = new FormData();

                    formData1.append('art_file', art_file);

                    $.ajax({

                        type: "POST",

                        url: "uploadfiles/artFiles.php",

                        contentType: false,

                        processData: false,

                        data: formData1,

                        success: function(data) {

                            if (JSON.parse(data).result.status == "NOT") {

                                $Error_.html(JSON.parse(data).result.message);

                                $Error_.css("color", "red");

                                return false;

                            } else if (JSON.parse(data).result.status == "OK") {

                                var fileUploded = JSON.parse(data).result.fileurl;

                                $.ajax({

                                    url: "https://coderouting.com/alclapi/cartfiles/updateart_Items.php",

                                    type: "POST",

                                    data: JSON.stringify({

                                        "user_id": userId,

                                        "product_id": $productId.val(),

                                        "art_file": fileUploded,

                                        "cart_id": cart_id

                                    }),

                                    success: function(dataResult) {

                                        console.log('data');

                                        if (dataResult['error'] == "1") {

                                            $Error_.html(dataResult['message']);

                                            $Error_.css("color", "red");

                                        } else {

                                            console.log('ss');

                                            setTimeout(function() {
                                                $.ajax({
                                                url: "https://coderouting.com/alclapi/cartfiles/updatewp_Art_Item.php",
                                                type: "POST",
                                                data: JSON.stringify({
                                                    "art_file": fileUploded,
                                                    "orderno" : orderno
                                                }),
                                                success: function(dataResult) {
                                                    if (dataResult['error'] == "1") {
                                                        $Error_.html(dataResult['message']);
                                                        $Error_.css("color", "red");
                                                    } else {
                                                        // setTimeout(function() {
                                                            location.reload();
                                                        // },2000);
                                                    }
                                            }
                                        });
                                            },2000);

                                        }

                                    }

                                });

                                $('.qtysuccess').html("");

                                setTimeout(function() {

                                    $Error_.fadeOut('slow');

                                }, 2000);

                                $Error_.html(JSON.parse(data).result.message);

                                $Error_.css("color", "green");

                                return false;

                            }

                        }

                    });

                    $filenameart.val("");

                }

            });

        }

</script>