
<?php
        require_once("../inc/functions.php");
        $shop = $_GET['shop'];
        include('layouts/header.php');
        $shop = $_GET['shop'];
        //$token = "shpua_94b8e3f880b6b4942434bfa17ebe7edd";
        $token = "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";
        $query = array(
            "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
        );
        $metaFields = array (
            '0' => 
          array (
            'key' => 'contact_number',
            'value' => '2222',
            'value_type' => 'string',
            'namespace' => 'global'
          ),
          '1' =>
          array (
            'key' => 'date_of_birth',
            'value' => '2019/02/29',
            'value_type' => 'string',
            'namespace' => 'global'
          ),
        );
        
            if(isset($_POST['submit'])){

                $metaFields = json_encode($metaFields);
        $date =  date("m/d/Y h:i:s a", time());
        // Run API call to get all products
        $data = array ('customer' => 
                  array (
                    'accepts_marketing' => false,
                    'created_at' => $date,
                    'email' => $_POST['email'],
                    'first_name' => $_POST['fname'],
                    //'id' => $_POST['id'],
                    'last_name' => $_POST['lname'],
                    'password'=> $_POST['password'],
                    'password_confirmation' => $_POST['password'],
                    'last_order_id' => null,
                    'multipass_identifier' => null,
                    'note' => null,
                    'orders_count' => 0,
                    'state' => 'enabled',
                    'total_spent' => '0.00',
                    'updated_at' => $date,
                    'verified_email' => true,
                    'tags' => 'newsletter, prospect',
                    'last_order_name' => null,
                    'default_address' => 
                    array (
                    'address1' => $_POST['addressone'],
                    'address2' => $_POST['addresstwo'],
                    'city' => $_POST['city'],
                    'company' => $_POST['company'],
                    'country' => $_POST['country'],
                    'first_name' => $_POST['fname'],
                    //   'id' => $_POST['id'],
                    'last_name' => $_POST['lname'],
                    'phone' => $_POST['phone'],
                    'province' => null,
                    'zip' => '2000',
                    'name' => 'first_name'.'last_name',
                    'province_code' => $_POST['province'],
                    'country_code' => $_POST['country'],
                    'country_name' => null,
                    'default' => true,
                    ),
                'addresses' => 
                array (
                  0 => 
                  array (
                    'address1' => $_POST['addressone'],
                    'address2' => $_POST['addresstwo'],
                    'city' => $_POST['city'],
                    'company' => '',
                    'country' => $_POST['country'],
                    'first_name' => $_POST['fname'],
                    // 'id' => $_POST['id'],
                    'last_name' => $_POST['lname'],
                    'phone' => $_POST['phone'],
                    'province' => null,
                    'zip' => $_POST['zip'],
                    'name' => $_POST['fname'].$_POST['lname'],
                    'province_code' => $_POST['province'],
                    'country_code' => $_POST['country'],
                    'country_name' => null,
                    'default' => true,
                  ),
                ),
              ),
            );
                $users = shopify_call($token, $shop, "/admin/api/2022-07/customers.json", $data, 'POST');
                //echo "<pre />";
                //print_r($users);
                if($users){
                    // header('Location:productlist.php?shop=' . $shop);
                   echo "User has ben registered!!";
                }
            }
           
        ?>
        <h1>Register Page</h1>
            <form method="POST">
                <table>
                    <tr>
                        <td>First Name</td>
                        <td><input type="text" name="fname" id="fname" /></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type="text" name="lname" id="lname" /></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="password" id="password" /></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="text" name="email" id="email" /></td>
                    </tr>
                    <tr>
                        <td>Address One</td>
                        <td><textarea name="addressone" id="addressone" ></textarea></td>
                    </tr>
                    <tr>
                        <td>Address Two</td>
                        <td><textarea name="addresstwo" id="addresstwo" ></textarea></td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td><input name="company" id="company" /></td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td><input name="phone" id="phone" /></td>
                    </tr>
                    <tr>
                        <td>Province</td>
                        <td>
                            <select name="province" id="province" >
                                <option value=""> Select Province </option>
                                <option value="SINDH"> Sindh </option>
                                <option value="PUNJAB"> Punjab </option>
                                <option value="BALOCH"> Balochistan </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Zip</td>
                        <td><input name="zip" id="zip" /></td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td>
                            <select name="country" id="country" >
                                <option value=""> Select Country </option>
                                <option value="PAK"> Pakistan </option>
                                <option value="IND"> India </option>
                                <option value="ENG"> England </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td>
                            <select name="city" id="city" >
                                <option value=""> Select City </option>
                                <option value="KHI"> Karachi </option>
                                <option value="LHR"> Lahore </option>
                                <option value="PSW"> Peshawar </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="submit" id="submit" class="btn btn-primary" /></td>
                    </tr>
                </table>
            </form>
        <?php
    include('layouts/footer.php');
?>