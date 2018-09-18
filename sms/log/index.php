<?php
	include("../core.inc.php");
	require "../db.inc.php";
	$query1 = $conn->prepare("SELECT * FROM `sms_getway` WHERE `user_id`=?");
	$res = "";
	try{
		$conn->beginTransaction();
		$query1->execute(array(1));
		$res = $query1->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e){
		$conn->rollBack();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Send Message</title>
	<style type="text/css">
		.update{
			border-radius: 25px;
			background: #57b846;
			cursor: pointer;
			padding: 5px 10px;
			color: #FFF;
		}
	</style>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form id="smsForm" style="width: 100%;">
					<div class="wrap-input100 validate-input" data-validate = "Invalid format.">
						<input class="input100" type="text" id="key" value="PSPxYL8PURjGG97myYEdC2GyCWLeVbs2ZknUkQ8QSkMnFdXpAwuexYN7ds4HmG7N" readonly="readonly">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
				</form>
				<table class="table">
					<tr>
						<th>Date</th>
						<th>Message</th>
						<th>Phone</th>
						<th>Status</th>
						<th>Update</th>
					</tr>
					<?php
						foreach ($res as $value) {
					?>
					<tr>
						<td><?php echo date('d-m-y, H:m:s', $value['time']); ?></td>
						<td><?php echo $value['sms']; ?></td>
						<td><?php echo $value['ph']; ?></td>
						<td><?php echo $value['status']; ?></td>
						<td><span onClick="getLast(<?php echo $value['id']; ?>);" class="update">Update</span></td>
					</tr>
					<?php } ?>
				</table>
				
			</div>
		</div>
	</div>
	
	

	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
		function getLast(id){
			key = document.getElementById("smsForm").key.value;
			console.log(id+","+key);
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