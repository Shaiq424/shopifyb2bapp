<?php include('layouts/footer.php') ?>
<!-- Only include one of the below in your theme. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/shopify-cartjs/1.1.0/cart.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/shopify-cartjs/1.1.0/rivets-cart.min.js"></script>
<!-- <script src="http://cdn.shopify.com/s/shopify/api.jquery.js?a1c9e2b858c25e58ac6885c29833a7872fcea2ba"></script> -->
<!-- <button class="ad_to_cart">Add to Cart</button> -->
<!-- <input type="checkbox" class="ad_to_cart_id" value='43347943293156' ></p> -->
<button id="button">Add Cart</button>

<script type="text/javascript">
    let formData = {
        'items': [{
        'id': 43347943293156,
        'quantity': 2
        }]
    };
    $('#button').click(function() {
        localStorage.setItem("cart", JSON.stringify(formData));
    });
</script>