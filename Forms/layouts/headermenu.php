<?php

if (isset($_POST['logout'])) {

    session_destroy();

?>

    <script>

        localStorage.clear();

    </script>

<?php

    header('Location: https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/login.php?shop=atacsportwear-com.myshopify.com/');

}
// require_once("../../inc/functions.php");
// $shop = $_GET['shop'];
// $token = "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";
// $query = array(
//     "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
// );
$custId = $_SESSION['User'];

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5 mt-3 border border-dark">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

    <span class="navbar-toggler-icon"></span>

  </button>



  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">

      <li class="nav-item active">

        <a class="nav-link" href="https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/dashboard.php?shop=atacsportwear-com.myshopify.com/">Home</a> 

      </li>

      <li class="nav-item">

        <a class="nav-link" href="https://1699-154-6-26-100.ngrok.io/custom-b2b-app/Forms/pages/productlist.php?shop=atacsportwear-com.myshopify.com">Add Cart</a>

      </li>

      <li class="nav-item">

        <a class="nav-link" href="https://atacsportwear-com.myshopify.com/pages/contact">Contact</a>

      </li>

      

    </ul>
    <?php
        // $customers = shopify_call($token, $shop, "/admin/customers/".$custId.".json", array(), 'GET');
        // $customers = $customers['response'];
        // $customers = json_decode($customers);
        // $customers= $customers->customer;
        // foreach($customers as $cust){
        //   if($_SESSION['User'] == $cust->customer_id)
        //   {
        //     echo  "Welcome ".$cust->first_name.' '.$cust->last_name;
        //   }
        // }
    ?>
    <!--<form class="form-inline my-2 my-lg-0" method="post" action="">-->

    <div class="my-2 my-lg-0 text-center">

        <a class="form-control mr-3 text-decoration-none text-dark rounded-0" href="https://atacsportwear-com.myshopify.com/cart" target="_blank">View Cart</a>

    </div>

        

        <!--<input type="submit" name="logout" value="Logout" class="form-control mr-sm-2 rounded-0" />-->

    <!--</form>-->

  </div>

</nav>