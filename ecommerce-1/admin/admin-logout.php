<?php

session_start();

if (isset($_SESSION["admin_id"])) {
	session_unset();
	session_destroy();
	header("location:login.php");
}else{
	header("location:index.php");
}


?>