<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user-entered OTP
    $userOTP = $_POST['otp'];

    // Check if the OTP matches the one stored in the session
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $userOTP) {
        // OTP verification successful
        // Reset the OTP session variable
        unset($_SESSION['otp']);

        $_SESSION['otpv']='true';

        $_SESSION["uid"] = $_SESSION['id'];
		$_SESSION["name"] = $_SESSION['Name'];
		$ip_add = getenv("REMOTE_ADDR");
		//we have created a cookie in login_form.php page so if that cookie is available means user is not login
			if (isset($_COOKIE["product_list"])) {
				$p_list = stripcslashes($_COOKIE["product_list"]);
				//here we are decoding stored json product list cookie to normal array
				$product_list = json_decode($p_list,true);
				for ($i=0; $i < count($product_list); $i++) { 
					//After getting user id from database here we are checking user cart item if there is already product is listed or not
					$verify_cart = "SELECT id FROM cart WHERE user_id = $_SESSION[uid] AND p_id = ".$product_list[$i];
					$result  = mysqli_query($con,$verify_cart);
					if(mysqli_num_rows($result) < 1){
						//if user is adding first time product into cart we will update user_id into database table with valid id
						$update_cart = "UPDATE cart SET user_id = '$_SESSION[uid]' WHERE ip_add = '$ip_add' AND user_id = -1";
						mysqli_query($con,$update_cart);
					}else{
						//if already that product is available into database table we will delete that record
						$delete_existing_product = "DELETE FROM cart WHERE user_id = -1 AND ip_add = '$ip_add' AND p_id = ".$product_list[$i];
						mysqli_query($con,$delete_existing_product);
					}
				}
				//here we are destroying user cookie
				setcookie("product_list","",strtotime("-1 day"),"/");
				//if user is logging from after cart page we will send cart_login
				echo "cart_login";
                header('Location: cart.php');
				exit();
				
			}
			//if user is login from page we will send login_success

			echo "login_success";
            header('Location: profile.php');
			exit();
        // TODO: Add your further actions here


    } else {
        // OTP verification failed
        echo 'Invalid OTP. Please try again.';
        header('Location: logout.php');
    }
}
?>

<!-- HTML form for OTP verification -->
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="otp">Enter OTP:</label>
    <input type="text" name="otp" id="otp" required>
    <button type="submit">Verify OTP</button>
</form>
