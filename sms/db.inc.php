<?php
	$db_host = "localhost";
	$db_user ="root";
	$db_pass = "";
	define('_RMDB_', 'redmorus_data', true);
	$conn = new PDO('mysql:host='.$db_host.';dbname='._RMDB_.'',$db_user,$db_pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
