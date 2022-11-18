<?php



session_start();


error_reporting(0);



if ($_SESSION['User'] != null) {



    require_once("../inc/functions.php");



    include('layouts/header.php');



    $userId = $_SESSION['User'];



    $api_url = 'https://coderouting.com/alclapi/cart/get_pendingcart.php?userID=' . $userId;



    // Read JSON file



    $json_data = file_get_contents($api_url);




    // Decode JSON data into PHP array



    // $response_data = json_decode($json_data);



    $response_data = json_decode($json_data);







    // All user data exists in 'data' object



    // $user_data = $response_data->data;



    $pendingCart_data = (array)$response_data;





    // Cut long data into small & select only first 10 records



    $pendingCart_data = array_slice($pendingCart_data, 0, 9);



?>





        <?php include('layouts/headermenu.php'); ?>



        <div class="row">



            <?php include('layouts/sidemenu.php'); ?>



            <div class="col-sm-9">



                



                <input type="hidden" id="totalOrders" value="<?php echo count($pendingCart_data['records']); ?>" />



                <h1>Total Pending Orders : <span id="count"></span></h1>



                <br />



                <?php



                $sno = 1;



                foreach ($pendingCart_data as $PenCart_data) {



                    $PenCart_data = json_decode(json_encode($PenCart_data), true);



                    foreach ($PenCart_data as $data) {



                        $dat = json_decode(json_encode($data));



                ?>



                        <div class="row order border my-3 py-3">



                            <div class="col-sm-2 text-center" style="align-self: center;">



                                <?php echo $dat->order_no;//echo $sno++; ?>



                            </div>



                            <div class="col-sm-8 text-center" style="align-self: center;">



                                <input type="hidden" id="check" value="<?php echo $dat->cart_id; ?>" />



                                <?php



                                    echo "Order Pending on: " . $dat->date_created;



                                ?>



                                <input type="hidden" id='cartItems<?php echo $dat->id; ?>' value='<?php echo $dat->value; ?>' />



                            </div>



                            <div class="col-sm-2 text-center" style="align-self: center;">



                                <?php



                                    // Store the cipher method



                                        $ciphering = "AES-128-CTR";



                                        



                                        // Use OpenSSl Encryption method



                                        $iv_length = openssl_cipher_iv_length($ciphering);



                                        $options = 0;



                                        



                                        // Non-NULL Initialization Vector for encryption



                                        $encryption_iv = '1234567891011121';



                                        



                                        // Store the encryption key



                                        $encryption_key = "alcl_App";



                                        



                                        // Use openssl_encrypt() function to encrypt the data



                                        $encryption = openssl_encrypt($dat->cart_id, $ciphering,$encryption_key, $options, $encryption_iv);



                                ?>



                                <a href="https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/pendingorderDetails.php?shop=atacsportwear-com.myshopify.com&id=<?php echo $encryption; ?>" class="form-control text-dark rounded-0">View Order</a>



                            </div>



                        </div>







                <?php



                    }



                }







                ?>



            </div>



        </div>



    </div>



<?php



    include('layouts/footer.php');



} else {



    header('Location:https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/login.php?shop=atacsportwear-com.myshopify.com/');



}



?>



<script>



    var count = $("#totalOrders").val();



    $("#count").html(count);



    $(document).ready(function() {



        



        $('.order').each(function() {



            var $pendingOrderId = $(this).find('#check');



            var cart_id = "#" + $pendingOrderId.val();



            var cartItems = "#cartItems<?php echo $dat->id; ?>";



            var items = $(cartItems).val();



            var $message = $(this).find('#message' + $pendingOrderId.val());



            $(cart_id).click(function() {



                // alert(userId);



                var uri = "https://coderouting.com/alclapi/cart/recreate.php";



                $.ajax({



                    url: uri,



                    type: "POST",



                    data: JSON.stringify({



                        userID: userId,



                        cart_id: $pendingOrderId.val(),



                        value: items



                    }),



                }).then(function(data) {



                    setTimeout(function() {



                        $message.fadeOut('slow');



                    }, 2000);



                    setTimeout(function() {



                        location.reload();



                    }, 3000);



                    $message.html(data.message);



                    $message.css("font-size", "14px");



                    $message.css("color", "green");



                });







            });







        });



    });







</script>