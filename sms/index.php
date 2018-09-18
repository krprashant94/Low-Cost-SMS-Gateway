<?php
	include "core.inc.php";
	if(!isset($_SESSION['s_id'])){
		header("Location: login/index.php");
	}else{
		header("Location: send/index.php");
	}
?>