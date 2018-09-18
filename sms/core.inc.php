<?php
	session_name('__RM__');
	/*ob_start('callback');*/
	session_start();
	
	/*function callback($buffer){
		return (str_replace('', '', $buffer));
	}*/
?>