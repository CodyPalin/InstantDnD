<?php
	function randomRow($numRows){
		$row = rand(0,$numRows-1);
		return $row*10+2;
	}
?>