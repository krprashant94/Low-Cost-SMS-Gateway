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

				<form class="login100-form validate-form" method="post" id="smsForm">
					<span class="login100-form-title">
						Send a Message
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid phone is required: Ex : 1234567890">
						<input class="input100" type="text" name="phone" placeholder="Phone No">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Message required">
						<input class="input100" type="text" name="messsage" placeholder="Message" value="Download our latest app GATE CSE form play store bit.ly/GATECSE">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Invalid format.">
						<input class="input100" type="text" name="key" placeholder="API Key">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Send
						</button>
					</div>
					<div class="text-center p-t-12">
						<span class="txt2" style="cursor: pointer;" onclick="getLast()" >Get Last Status</span> |
						<span class="txt1">
							Generate
						</span>
						<a class="txt2" href="key.php" style="cursor: pointer;">
							API Key?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="../API/Refrence.pdf">
							API Documantation
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
                    <input type="hidden" name="last" value="0" />
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
		$('.js-tilt').tilt({
			scale: 1.1
		})
		function getLast(){
			id = document.getElementById("smsForm").last.value;
			console.log(id);
			key = document.getElementById("smsForm").key.value;
            $.ajax({
                type: 'POST',
                url: "../API/SMSGateway.php",
                data: "key="+key+"&id="+id+"&get",
                dataType: "JSON",
                success: function(getresultData) {
                    console.log(getresultData);
                    alert(getresultData);
                    $(".login100-form-btn").html('Send');
                    $(".login100-form-btn").prop('disabled', false);
                }
            });
		}
	</script>
	<script src="js/main.js"></script>

</body>
</html>