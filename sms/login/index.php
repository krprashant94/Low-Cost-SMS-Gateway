<?php
	include "../core.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<!--
		bootstrap(MIT), and material icon(google)
	-->
	<link rel="stylesheet" type="text/css" href="api/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="api/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="api/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="api/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="api/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="api/daterangepicker/daterangepicker.css">
	<!--
		main designs
	-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body >
	
	<div class="limiter">
		<div class="container-login100" style='background-image: url("images/lake-lucerne.jpg");'>
			<div class="wrap-login100">
				<form class="login100-form validate-form">
					<span class="login100-form-title p-b-26">
						Welcome
					</span>
					<span class="login100-form-title p-b-48">
						<i class="zmdi zmdi-font"></i>
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is: a@b.c">
						<input class="input100" type="text" name="email">
						<span class="focus-input100" data-placeholder="Email"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass" autocomplete="new-password">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								Login
							</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<a href="../index.php"><span class="txt1">
							Don’t have an account?
						</span></a>

						<a class="txt2" href="#">
							Sign Up
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	<!--
			jQuery, bootstrap and Lib's.
	-->	
	<script src="api/jquery/jquery-3.2.1.min.js"></script>
	<script src="api/animsition/js/animsition.min.js"></script>
	<script src="api/bootstrap/js/popper.js"></script>
	<script src="api/bootstrap/js/bootstrap.min.js"></script>
	<!--
		User Lib
	-->
	<script src="api/select2/select2.min.js"></script>
	<script src="api/daterangepicker/moment.min.js"></script>
	<script src="api/daterangepicker/daterangepicker.js"></script>
	<script src="api/countdowntime/countdowntime.js"></script>
	<!--
			Main script file
	-->
	<script src="js/main.js"></script>

</body>
</html>