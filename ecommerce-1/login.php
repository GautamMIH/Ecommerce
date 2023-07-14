<?php
include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

session_start();


#Login script is begin here
#If user given credential matches successfully with the data available in database then we will echo string login_success
#login_success string will go back to called Anonymous funtion $("#login").click() 
if(isset($_POST["email"]) && isset($_POST["password"])){
	$email = mysqli_real_escape_string($con,$_POST["email"]);
	$password = md5($_POST["password"]);
	$sql = "SELECT * FROM user_info WHERE email = '$email' AND password = '$password'";
	$run_query = mysqli_query($con,$sql);
	$count = mysqli_num_rows($run_query);
	//if user record is available in database then $count will be equal to 1
	if($count == 1){
				$otp = rand(100000, 999999); // You can adjust the range as per your requirement
				$_SESSION['otp'] = $otp;



				$mail = new PHPMailer(true);

				try {
					// SMTP configuration
					$mail->isSMTP();
					$mail->Host = 'smtp.office365.com';
					$mail->SMTPAuth = true;
					$mail->Username = 'gautam.mudbhari16@outlook.com';
					$mail->Password = 'q:FH8.<CfK=U+:m@';
					$mail->SMTPSecure = 'tls';
					$mail->Port = 587;

					// Email content
					$mail->setFrom('gautam.mudbhari16@outlook.com', 'GBuy');
					$mail->addAddress($email, 'User');
					$mail->Subject = 'Your OTP';
					$mail->Body = 'Dear User'.$email.'Please enter the following OTP code to further proceed with your actions on our website. Please note that this will work only for the current session.
					Your OTP is: ' . $otp;

					// Send the email
					$mail->send();
					echo 'Email sent successfully.';
					header('Location: otpverify.php');
				} catch (Exception $e) {
					echo 'Failed to send email. Error: ' . $mail->ErrorInfo;
				}
				
				

		
		$row = mysqli_fetch_array($run_query);

		$_SESSION["id"] = $row["user_id"];
		$_SESSION["Name"] = $row["first_name"];

		}else{
			echo "<span style='color:red;'>Please register before login..!</span>";
			exit();
		}

}



?>