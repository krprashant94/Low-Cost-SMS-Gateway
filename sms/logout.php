<?php
	session_name('__RM__');
	session_start();
	session_destroy();
	header("Location: http://".$_SERVER['HTTP_HOST']."/sms");
?>