<!DOCTYPE html>
<html lang="en">
<head>
	<title>Send Message</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<!--
		bootstrap(MIT), and material icon(google)
	-->
	<link rel="stylesheet" type="text/css" href="api/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="api/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="api/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="api/select2/select2.min.css">
	<!--
		main designs
	-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<a href="../logout.php"><img src="images/img-01.png" alt="Logout"></a>
				</div>

				<form class="login100-form validate-form" method="post">
					<span class="login100-form-title">
						Your API Key is
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Invalid format.">
						<input class="input100" type="text" id="key" value="PSPxYL8PURjGG97myYEdC2GyCWLeVbs2ZknUkQ8QSkMnFdXpAwuexYN7ds4HmG7N" readonly="readonly">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" onClick="copy()">
							Copy
						</button>
					</div>

					<div class="text-center p-t-12">
						<a class="txt2" href="#">
							Refresh Key?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="../API/Refrence.pdf">
							API Documantation
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
                    <input type="hidden" name="callback" value="../sms/send/index.php" />
                    <input type="hidden" name="operation" value="sms" />
				</form>
			</div>
		</div>
	</div>
	
	

	<script src="api/jquery/jquery-3.2.1.min.js"></script>
	<script src="api/bootstrap/js/popper.js"></script>
	<script src="api/bootstrap/js/bootstrap.min.js"></script>
	<script src="api/select2/select2.min.js"></script>
	<script src="api/tilt/tilt.jquery.min.js"></script>
	<script >
		function copy() {
			event.preventDefault();
			var copyText = document.getElementById("key");
			copyText.select();
			document.execCommand("Copy");
			alert("API key Copied.");
		}
	</script>
</body>
</html>