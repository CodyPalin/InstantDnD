<?php
	function filter($string){
		$string = htmlspecialchars($string);
		//$string = mysql_real_escape_string($string);
		return $string;
	}
?>