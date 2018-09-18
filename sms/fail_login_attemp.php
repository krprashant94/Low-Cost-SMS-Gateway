<?php	
	function log_login($user, $conn){
		$e_query = $conn->prepare("INSERT INTO `"._RMDB_."`.`dtdc_fail_login` (`id`, `user_id`, `time` ) VALUES( NULL , ?, ?)");
		$e_query->execute(array($user, time()));
	}
?>