<?php
	function filter($string){
		$string = htmlspecialchars($string);
		return $string;
	}
	function hashFunction($password){
		$salt = "l$*FDgJ#$69#dsflkj";
		return hash("sha256", $password.$salt);
	}
?>