<?php

error_reporting(0);
session_start();
$_SESSION['User'] = '6462764089564';
if ($_SESSION['User'] != null) {
    require_once("../../inc/functions.php");
    require_once("../../db/config.php");
    include('../layouts/header.php');
    $shop = $_GET['shop'];
    $token = "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";
    $query = array(
        "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
    );
    $api_url = 'https://coderouting.com/alclapi/AssignedProduct/getproducts.php?userID=' . $_SESSION['User'];
    // Read JSON file
    $json_data = file_get_contents($api_url);
    // Decode JSON data into PHP array
    $response_data = json_decode($json_data);
    $productId = $response_data->productId;
    $product_arr = [];
    foreach ($productId as $proId) {
        // Run API call to get all products
        $products = shopify_call($token, $shop, "/admin/products/" . $proId . ".json", array(), 'GET');
        // Get response
        $products = $products['response'];
        $products = json_decode($products);
        $products = $products->product;
        array_push($product_arr, $products);
        $rand = "";
        $sno = 0;
        $api_url = 'https://coderouting.com/alclapi/cart/read_one.php?userID=' . $_SESSION['User'];
        // Read JSON file
        $json_data = file_get_contents($api_url);
        // Decode JSON data into PHP array
        $variatonData = array();
        $response_data = json_decode($json_data);
        $responce_val = json_decode($response_data->value);
        foreach ($responce_val as $value) {
            $variatons = [
                    "variatonId" => $value->variationid,
                    "quantity" => $value->quantity,
            ];
            array_push($variatonData, $variatons);
        }
        $cartid = 0;
        if($response_data) {
            $cartid = $response_data->id;
        } else {
            $cartid = 0;
        }
?>
    <?php include('../layouts/headermenu.php'); ?>
    <div class="row">
    <?php include('../layouts/sidemenu.php'); ?>
        <div class="col-sm-9">
            <input type="hidden" id="userId" value="<?php echo $_SESSION['User']; ?>" />
            <h1>Total Record : <span id="count"></span></h1>
            <input type="hidden" id="cartId" value="<?php echo $cartid; ?>" />
            <input type="hidden" id="userId_" value="" />
            <input type="hidden" id="value" value="" />
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Uploaded Files</th>
                        <th>Files</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    <?php

                    foreach ($product_arr as $value) {

                        if ($value->variants[0]->title != 'Default Title') {

                    ?>

                            <tr class="product">

                                <td>

                                    <img src="<?php echo $value->images[0]->src; ?>" width="100" height="100" class="img-fluid img-thumbnail rounded-0" alt="<?php echo $value->title; ?>">

                                </td>

                                <td>

                                    <?php echo ucfirst($value->title); ?>

                                </td>

                                <td>

                                    <?php

                                    
                                    //if($cartid == 0){
                                        $api_url = 'https://coderouting.com/alclapi/cartfiles/readFilebyProId.php?productID=' . $value->id . "&userID=" . $_SESSION['User'];    
                                    //}else if($cartid != 0){
                                      //  $api_url = 'https://coderouting.com/alclapi/cartfiles/readFilebyProIdAfterCart.php?productID=' . $value->id . "&userID=" . $_SESSION['User'];    
                                    //}
                                    

                                    // Read JSON file

                                    $json_data = file_get_contents($api_url);

                                    // Decode JSON data into PHP array

                                    $response_data = json_decode($json_data);



                                    // All user data exists in 'data' object

                                    $files_data = (array)$response_data;

                                    if ($files_data['records'][0]->error == 1) {

                                        echo "<b>" . $files_data['records'][0]->message . "</b>";
                                    } else {

                                        foreach ($files_data['records'] as $rec) {

                                            if ($rec->roaster_file) {

                                    ?>

                                                <a href="<?php echo $rec->roaster_file; ?>" id="roasterfile" data-value="1">Roster File</a>

                                            <?php

                                            }

                                            if ($rec->art_file) {



                                            ?>



                                                <br />

                                                <a href="<?php echo $rec->art_file; ?>" id="artfile" target="_blank" data-value="1">Art File</a>

                                                <br />

                                    <?php

                                            }
                                        }
                                    }

                                    ?>

                                </td>

                                <td>

                                    <div class="div file btn btn-primary rounded-0">

                                        <small>Upload Roster</small>

                                        <input type="file" class="input roaster" id="roaster<?php echo $value->id; ?>" name="upload_roster" onchange="roaster()" />

                                    </div>

                                    <div class="div file btn btn-primary rounded-0">

                                        <small>Upload ART</small>

                                        <input type="file" class="input art" id="art<?php echo $value->id; ?>" name="upload_art" onchange="art()" />

                                    </div>

                                    <br />

                                    <small style="color:blue;">*Both files should be Uploaded</small>

                                    <p id="Error_<?php echo $value->id; ?>" class="m-0"></p>

                                    <input type="hidden" data-id="<?php echo $value->id; ?>" id="productId<?php echo $value->id; ?>" value="<?php echo $value->id; ?>" />

                                </td>

                            </tr>

                            <tr class="productdetails">

                                <td colspan="4">

                                    <?php

                                    $saveddata = [];

                                    $givendata = json_decode($response_data->value);



                                    ?>

                                    <?php

                                    foreach ($value->variants as $variant) {

                                        if ($variant->title != "Default Title") {

                                    ?>

                                            <table class="table table-bordered item">

                                                <tr class="qty">

                                                    <td style="width:20%;"><?php echo ++$sno; ?></td>

                                                    <td style="width:20%;"><?php echo $variant->title; ?></td>

                                                    <td style="width:20%;"><?php echo '$ ' . $value->variants[0]->price; ?></td>

                                                    <td style="width:20%;">

                                                        <?php

                                                        ?>

                                                        <?php

                                                        $cartdata = count($variatonData);

                                                        if ($cartdata == 0) {

                                                        ?>

                                                            <div class="quantity">

                                                                <input type="number" id="qty<?php echo $variant->id; ?>" min="0" max="100" step="1" value="0">

                                                            </div>

                                                            <?php



                                                        } else {

                                                            foreach ($variatonData as $variadata) {

                                                                if (in_array($variant->id, $variadata)) {

                                                            ?>

                                                                    <input type="text" style="display:none;" id="variationqty" value="<?php echo $variadata['quantity']; ?>" />

                                                                    <div class="quantity">

                                                                        <input type="number" class="qty<?php echo $value->id; ?>" id="qty<?php echo $variant->id; ?>" min="0" max="100" step="1" value="<?php echo $variadata['quantity']; ?>">

                                                                    </div>

                                                                <?php

                                                                } else {

                                                                ?>

                                                                    <input type="text" style="display:none;" id="variationqtyzero" value="<?php echo "0"; ?>" />

                                                                    <div class="quantity quantity_">

                                                                        <div class="emquantity">

                                                                            <input type="number" id="qty<?php echo $variant->id; ?>" min="0" max="100" step="1" value="0">

                                                                        </div>

                                                                    </div>



                                                        <?php

                                                                }
                                                            }
                                                        }

                                                        ?>

                                                        <p id="qtyerror<?php echo $variant->id; ?>" class="m-0"></p>

                                                    </td>

                                                    <td>

                                                        <?php



                                                        if ($variatonData) {

                                                            foreach ($variatonData as $variadata) {

                                                                if (in_array($variant->id, $variadata)) {

                                                        ?>

                                                                    <button data-id="<?php echo $variant->id; ?>" data-value="fileexits" id="variant<?php echo $variant->id; ?>" class="btn btn-primary rounded-0 cartItem cartItems<?php echo $value->id; ?>" value="Add to cart"><small>Add to cart</small></button>

                                                                    <input type="hidden" id="variantProductId<?php echo $variant->id; ?>" value="<?php echo $value->id; ?>" />

                                                                    <p id="qtysuccess<?php echo $variant->id; ?>" class="m-0 qtysuccess"></p>

                                                                <?php

                                                                } else {

                                                                ?>

                                                                    <button data-id="<?php echo $variant->id; ?>" id="variant<?php echo $variant->id; ?>" class="btn btn-primary rounded-0 cartItem cartItems<?php echo $value->id; ?>" value="Add to cart"><small>Add to cart</small></button>

                                                                    <input type="hidden" id="variantProductId<?php echo $variant->id; ?>" value="<?php echo $value->id; ?>" />

                                                                    <p id="qtysuccess<?php echo $variant->id; ?>" class="m-0 qtysuccess"></p>

                                                            <?php

                                                                }
                                                            }
                                                        } else {

                                                            ?>

                                                            <button data-id="<?php echo $variant->id; ?>" id="variant<?php echo $variant->id; ?>" class="btn btn-primary rounded-0 cartItem cartItems<?php echo $value->id; ?>" value="Add to cart"><small>Add to cart</small></button>

                                                            <input type="hidden" id="variantProductId<?php echo $variant->id; ?>" value="<?php echo $value->id; ?>" />

                                                            <p id="qtysuccess<?php echo $variant->id; ?>" class="m-0 qtysuccess"></p>

                                                        <?php

                                                        }

                                                        ?>

                                                    </td>

                                                </tr>

                                            </table>

                                    <?php



                                        }
                                    }

                                    ?>

                                </td>



                            </tr>

                    <?php

                        }
                    }

                    ?>

                </tbody>

            </table>

            <?php

            // }

            ?>

        </div>

    </div>



    <?php

    include('../layouts/footer.php');

    ?>

    <script>
        // for Total Records Starts

        var totalVariant = 0;

        var roasterFile;

        var artFile;

        var userId = $("#userId").val();

        var roaster_file = "";

        var art_file = "";

        var cartId = $("#cartId").val();

        $('.item').each(function() {

            totalVariant++;

        });

        // var count = $(".count").val();

        $("#count").html(totalVariant);

        // for Total Records Ends

        $(document).ready(function() {

            $.ajax({

                url: "https://coderouting.com/alclapi/cartfiles/getFilesbyroaster_idUserId.php",

                type: "GET",

                datatype: "json",

                async: false,

                data: {

                    userID: userId

                },

                success: function(dataResult) {

                    if (dataResult['records'][0].error != 1) {

                        roasterFile = "roasterFile exits";

                    } else {

                        roasterFile = "roasterFile not exits";

                    }

                }

            });



            $.ajax({

                url: "https://coderouting.com/alclapi/cartfiles/getFilesbyart_idUserId.php",

                type: "GET",

                datatype: "json",

                async: false,

                data: {

                    userID: userId

                },

                success: function(dataResult) {

                    if (dataResult['records'][0].error != 1) {

                        artFile = "artFile exits";

                    } else {

                        artFile = "artFile not exits";

                    }

                }

            });

            if (roasterFile == 'roasterFile exits' && artFile == 'artFile exits') {

                console.log('roasterFile & artFile boht exits');

                $.ajax({

                    url: "https://coderouting.com/alclapi/cartfiles/getFilesbyUserProduct.php",

                    type: "GET",

                    data: {

                        userID: userId

                    },

                    success: function(dataResult) {

                        var productID = [];

                        for (var i = 0; i < dataResult['records'].length; i++) {

                            var product_id = dataResult['records'][i].product_id;

                            productID.push(product_id);

                        }

                        var $qty = "";

                        var proID = [];

                        var $button = "";

                        var $productId = "";

                        var $button = "";

                        var hello = 0;

                        $('.item').each(function() {

                            $button = $(this).find('button');

                            $productId = $(this).find('input[type=hidden]');

                            $qty = $(this).find('.qty' + $productId.val());

                            proID.push($productId.val());

                            var $cartItems = $(this).find('.cartItems' + $productId.val());

                            cartvariant = "#" + $cartItems.attr("id");

                            $cartItems.attr("disabled", true);

                        });



                        var productID = getUnique(productID);

                        var proID = getUnique(proID);

                        var result = findCommonElements(productID, proID);

                        if (result == true) {

                            var cartItems = "";

                            var cartvariant = "";

                            Object.keys(productID).forEach(function(key) {

                                cartItems = ".cartItems" + productID[key];

                                cartvariant = $(cartItems).attr("id");

                                $(cartItems).addClass("filexists");

                                $(cartItems).attr("disabled", false);

                            });



                            $('.filexists').each(function() {

                                var btnid = $(this).attr("data-value");

                                if (typeof btnid != "undefined") {

                                    $(this).attr("disabled", true);

                                }

                            });

                            $('.item').each(function(item, val) {

                                var $button = $(this).find('button');

                                if ($button.hasClass("filexists")) {

                                } else {

                                    ($button).not(':first').css("display", "none");

                                }

                            });

                            $('.filexists').each(function() {

                                if ($(this).attr("disabled") != "disabled") {

                                    $(this).addClass("enabled");

                                }

                            });

                            $.each($('.filexists'), function(key, value) {

                                var filesexits = $(this).attr("data-value");

                                if (typeof filesexits != "undefined") {

                                    $(this).closest('td').addClass('current');

                                }

                            });

                            var bseen = {};

                            $('.current').each(function() {

                                if ($(this).find(".enabled")) {

                                    console.log('2');

                                    $(this).find(".enabled").remove();

                                }

                            });

                            var seen = [];

                            $('.enabled').each(function() {

                                var txt = $(this).attr("id");

                                txt = "#" + txt;

                                seen.push(txt);

                            });

                            var temp = [];

                            var varseen = [];

                            // $.each(seen, function (key, value) {

                            // if($.inArray(value, temp) === -1) {

                            //         temp.push(value);

                            //     }else{

                            //         console.log(value+" is a duplicate value");

                            //         values = value;

                            //             // $(value).not(':first').css("display","none");

                            //             // // if (seen[txt])

                            //             //     $(this).remove();

                            //             // else

                            //             //     seen[txt] = true;



                            //     }

                            // });



                            $.each($('.enabled'), function(key, value) {

                                value = $(value).attr("id");

                                if ($.inArray(value, temp) === -1) {

                                    temp.push(value);

                                } else {

                                    $(this).remove();

                                }

                            });

                            var roasterfileData = "";

                            var artfileData = "";

                            var $productId = "";

                            var enabledProduct = [];

                            var disabledProduct = [];

                            $.each($('.product'), function(key, value) {

                                $productId = $(this).find('input[type=hidden]');

                                var roasterfile = $(this).find("#roasterfile").attr("id");

                                roasterfile = "#" + roasterfile;

                                roasterfileData = $(roasterfile).attr("data-value");

                                var artfile = $(this).find("#artfile").attr("id");

                                artfile = "#" + artfile;

                                artfileData = $(artfile).attr("data-value");

                                if (roasterfileData == "1" && artfileData == "1") {

                                    enabledProduct.push($productId.val());

                                } else {

                                    disabledProduct.push($productId.val());

                                }

                            });

                            Object.keys(disabledProduct).forEach(function(key) {

                                cartItems = ".cartItems" + disabledProduct[key];

                                // cartvariant = $(cartItems).attr("id");

                                $(cartItems).attr("disabled", true);

                            });



                        }

                    }

                });

            } else {

                console.log('roasterFile & artFile boht not exits');

                $('.cartItem').attr("disabled", true);

            }



            $('#data').after('<div id="nav"></div>');

            var rowsShown = 12;

            var rowsTotal = $('#data tbody tr').length;

            var numPages = rowsTotal / rowsShown;

            for (i = 0; i < numPages; i++) {

                var pageNum = i + 1;

                $('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');

            }

            $('#data tbody tr').hide();

            $('#data tbody tr').slice(0, rowsShown).show();

            $('#nav a:first').addClass('active');

            $('#nav a').bind('click', function() {

                $('#nav a').removeClass('active');

                $(this).addClass('active');

                var currPage = $(this).attr('rel');

                var startItem = currPage * rowsShown;

                var endItem = startItem + rowsShown;

                $('#data tbody tr').css('opacity', '0.0').hide().slice(startItem, endItem).

                css('display', 'table-row').animate({
                    opacity: 1
                }, 300);

            });

        });

        $('.item').each(function() {

            var $button = $(this).find('button');

            var $productId = $(this).find('input[type=hidden]');



            // var $variationqty = $(this).find('#variationqty');

            // var $qty1 = $(this).find('#qty1'+$productId.val());

            var $cartItems = $(this).find('.cartItems' + $productId.val());

            var $qtysuccess = $(this).find('.qtysuccess');



            $button.click(function() {

                var variationId = $(this).data("id");

                var qty = '#qty' + variationId;



                var qty_ = $(qty).val();

                var qtysuccess = "#" + $qtysuccess.attr("id");

                var qtyerror = $('#qtyerror' + variationId).attr("id");

                // console.log('$qty1', $qty1.val());

                qtyerror = "#" + qtyerror;

                // console.log('qty', qty_);

                // console.log('variationId', variationId);

                if (qty_ == 0 || qty_ == '') {

                    $(qtyerror).html('Field Required....');

                    $(qtyerror).css('color', 'red');

                    return false;

                } else if (qty_ != 0 || qty_ != '') {

                    $(qtyerror).html('');

                }

                const Item = [{

                    'productid': $productId.val(),

                    'variationid': variationId,

                    'quantity': qty_,

                    'user': userId,

                    "newrecord": "1"

                }];
                $.ajax({

                    url: "https://coderouting.com/alclapi/cart/create.php",

                    type: "POST",

                    data: JSON.stringify({

                        "value": Item,

                        "userID": userId,

                        "productId": $productId.val(),

                        "cartId": cartId,

                    }),

                    success: function(dataResult) {
                        // console.log(dataResult.message);
                        // if(dataResult.message == "Roster or Art files any of this is not Uploaded"){
                        //     $(qtysuccess).html(dataResult.message);
                        //     $(qtysuccess).css("color", "red");
                        //     return false;
                        // }else{
                            $.ajax({
    
                                url: "https://coderouting.com/alclapi/cartfiles/lastcart.php",
    
                                type: "GET",
    
                                success: function(dataResult) {
    
                                    // $("#cartId").val(dataResult.records[0].id);
    
                                    $("#userId_").val(dataResult.records[0].userID);
    
                                    $("#value").val(dataResult.records[0].value);
    
    
    
    
    
                                    setTimeout(function() {
    
                                        $qtysuccess.fadeOut('slow');
    
                                    }, 2000);
    
                                    $(qtysuccess).html("Item has been Added to Cart...");
    
                                    $(qtysuccess).css("color", "green");
    
                                    $cartItems.attr("disabled", true);
    
    
    
                                }
    
                            });
                        // }

                    }

                });

            });

        });



        function roaster(e) {

            $('.product').each(function() {

                var $productId = $(this).find('input[type=hidden]');

                var cart_id = $("#cartId").val();

                var $filenameroaster = $(this).find("#roaster" + $productId.val());

                var $Error_ = $(this).find("#Error_" + $productId.val());

                if ($filenameroaster[0].files[0] != null) {

                    roaster_files = $filenameroaster[0].files[0];

                    var formData1 = new FormData();

                    formData1.append('roasterfile', roaster_files);

                    $.ajax({

                        type: "POST",

                        url: "../uploadfiles/roasterFiles.php",

                        contentType: false,

                        processData: false,

                        data: formData1,

                        success: function(data) {

                            if (JSON.parse(data).result.status == "NOT") {

                                console.log('hello', JSON.parse(data).result.status);

                                // setTimeout(function() {

                                // $Error_.fadeOut('slow');

                                // }, 2000);

                                $Error_.html(JSON.parse(data).result.message);

                                $Error_.css("color", "red");

                                return false;

                            } else if (JSON.parse(data).result.status == "OK") {

                                var fileUploded = JSON.parse(data).result.fileurl;

                                $.ajax({

                                    url: "https://coderouting.com/alclapi/cartfiles/createRoaster.php",

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

                                                location.reload();

                                            }, 2000);

                                            $Error_.html(dataResult['message']);

                                            $Error_.css("color", "green");

                                            var art_roasterfile;

                                            var art_artfile;

                                            $.ajax({

                                                url: "https://coderouting.com/alclapi/cartfiles/getFilesbyroaster_idUserIdpro.php",

                                                type: "GET",

                                                datatype: "json",

                                                async: false,

                                                data: {

                                                    userID: userId,

                                                    product_id: $productId.val()

                                                },

                                                success: function(dataResult) {

                                                    if (dataResult['records'][0].error != 1) {

                                                        art_roasterfile = "roasterFile exits";



                                                    } else {

                                                        art_roasterfile = "roasterFile not exits";

                                                    }

                                                }

                                            });

                                            $.ajax({

                                                url: "https://coderouting.com/alclapi/cartfiles/getFilesbyart_idUserIdpro.php",

                                                type: "GET",

                                                datatype: "json",

                                                async: false,

                                                data: {

                                                    userID: userId,

                                                    product_id: $productId.val()

                                                },

                                                success: function(dataResult) {

                                                    if (dataResult['records'][0].error != 1) {

                                                        art_artfile = "artFile exits";

                                                    } else {

                                                        art_artfile = "artFile not exits";

                                                    }

                                                }

                                            });

                                            if (art_roasterfile == "roasterFile exits" && art_artfile == "artFile exits") {

                                                $.ajax({

                                                    url: "https://coderouting.com/alclapi/cartfiles/getFilesbyUserId.php",

                                                    type: "GET",

                                                    datatype: "json",

                                                    async: false,

                                                    data: {

                                                        userID: userId

                                                    },

                                                    success: function(dataResult) {

                                                        var productID = [];

                                                        for (var i = 0; i < dataResult['records'].length; i++) {

                                                            var product_id = dataResult['records'][i].product_id;

                                                            productID.push(product_id);

                                                        }

                                                        var proID = [];

                                                        $('.item').each(function() {

                                                            var $productId = $(this).find('input[type=hidden]');

                                                            proID.push($productId.val());

                                                            var $cartItems = $(this).find('.cartItems' + $productId.val());

                                                            $cartItems.attr("disabled", true);

                                                        });



                                                        var result = findCommonElements(productID, proID);

                                                        if (result == true) {

                                                            Object.keys(productID).forEach(function(key) {

                                                                var cartItems = ".cartItems" + productID[key];

                                                                $(cartItems).attr("disabled", false)

                                                            });

                                                        }

                                                    }

                                                });

                                            }

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

        function art(e) {

            $('.product').each(function() {

                var $productId = $(this).find('input[type=hidden]');

                var cart_id = $("#cartId").val();

                var $filenameart = $(this).find("#art" + $productId.val());

                var $filenameroaster = $(this).find("#roaster" + $productId.val());

                var $Error_ = $(this).find("#Error_" + $productId.val());

                if ($filenameart[0].files[0] != null) {

                    art_file = $filenameart[0].files[0];

                    var formData1 = new FormData();

                    formData1.append('art_file', art_file);

                    $.ajax({

                        type: "POST",

                        url: "../uploadfiles/artFiles.php",

                        contentType: false,

                        processData: false,

                        data: formData1,

                        success: function(data) {

                            if (JSON.parse(data).result.status == "NOT") {

                                // setTimeout(function() {

                                //     $Error_.fadeOut('slow');

                                // }, 2000);

                                $Error_.html(JSON.parse(data).result.message);

                                $Error_.css("color", "red");

                                return false;

                            } else if (JSON.parse(data).result.status == "OK") {

                                var fileUploded = JSON.parse(data).result.fileurl;

                                $.ajax({

                                    url: "https://coderouting.com/alclapi/cartfiles/createArt.php",

                                    type: "POST",

                                    data: JSON.stringify({

                                        "user_id": userId,

                                        "product_id": $productId.val(),

                                        "art_file": fileUploded,

                                        "cart_id": cart_id

                                    }),

                                    success: function(dataResult) {

                                        if (dataResult['error'] == "1") {

                                            $Error_.html(dataResult['message']);

                                            $Error_.css("color", "red");

                                        } else {

                                            $('.qtysuccess').html("");

                                            $Error_.html(JSON.parse(data).result.message);

                                            $Error_.css("color", "green");

                                            setTimeout(function() {

                                                location.reload();

                                            }, 2000);

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

        function getUnique(array) {

            var uniqueArray = [];



            // Loop through array values

            for (i = 0; i < array.length; i++) {

                if (uniqueArray.indexOf(array[i]) === -1) {

                    uniqueArray.push(array[i]);

                }

            }

            return uniqueArray;

        }



        function findCommonElements(arr1, arr2) {

            // Create an empty object

            let obj = {};



            // Loop through the first array

            for (let i = 0; i < arr1.length; i++) {



                // Check if element from first array

                // already exist in object or not

                if (!obj[arr1[i]]) {



                    // If it doesn't exist assign the

                    // properties equals to the

                    // elements in the array

                    const element = arr1[i];

                    obj[element] = true;

                }

            }



            // Loop through the second array

            for (let j = 0; j < arr2.length; j++) {



                // Check elements from second array exist

                // in the created object or not

                if (obj[arr2[j]]) {

                    return true;

                }

            }

            return false;

        }

        if (cartId != "0") {

            $('.qty').each(function(item, val) {

                var $quantity = $(this).find('#variationqty');

                var $emquantity = $(this).find('.emquantity');

                var $emquantity = $(this).find('.emquantity');

                var $quantity_ = $(this).find('.quantity_');



                if ($quantity.length) {

                    if ($emquantity.val() == 0) {

                        ($emquantity).remove();

                        ($quantity_).remove();

                    }

                } else {

                    $('.quantity').each(function() {

                        if ($emquantity.length > 1) {

                            // $($emquantity).not(':first').remove();

                            $($emquantity).not(':first').css("display", "none");

                            // $($emquantity).addClass('first');

                        }

                    });

                }

            });

        }
    </script>

<?php
}
} else {

    header('Location:https://atacsportswear.com/account/');
    // header('Location:https://1699-154-6-26-100.ngrok.io/alcl_App/Forms/pages/login.php?shop=onlinedevelopmentstore.myshopify.com/');

}

?>