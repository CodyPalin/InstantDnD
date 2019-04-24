<?php
session_start();
include 'filter.php';
include 'Dao.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();
if(!$_SESSION['logged_in']){
	$_SESSION['message'] = "You must be logged in to do this.";
	header("Location: ".$_SESSION['current_page']);
	exit();
}
$numMonsters = 8;
$params = [];
$params[0] = $_SESSION['userid'];
for($i = 1; $i<=8; $i++)
{
	if(isset($_POST["monster".$i])){
		$_POST["monster".$i] = filter($_POST["monster".$i]);
		
		if(strlen($_POST["monster".$i]) >64){
			$_SESSION['message'] = "Limit: 64 characters.";
			header("Location: dungeons.php");
			exit();
		}
		$params[$i] = $_POST["monster".$i];
	}
	else {
		$numMonsters = $i-1;
		break;
	}
}

$id = $_SESSION['userid'];
if(!isset($_POST["saved_id"])){
	$sql = "INSERT INTO saved_encounters (user_id";
	for($i = 1; $i<=$numMonsters; $i++)
	{
		$sql.=","."monster".$i;
	}
	$sql.=") VALUES (?";
	for($i = 1; $i<=$numMonsters; $i++)
	{
		$sql.=",?";
	}
	$sql.=")";
	$insertstmt = $myPDO->prepare($sql);
	$insertstmt->execute($params);
	
	$numstmt = $myPDO->prepare("UPDATE user SET saved_encounters = saved_encounters+1 WHERE id = '$id'");
	$numstmt->execute();
	$_SESSION['message'] = "Saved Successfully!";
}
else
{
	//update saved
	$sql = "UPDATE saved_encounters SET ";
	for($i = 1; $i<=$numMonsters; $i++)
	{
		if($i == $numMonsters)
			$sql .= "monster".$i." = '".$_POST['monster'.$i];
		else
			$sql .= "monster".$i." = '".$_POST['monster'.$i]."', ";
	}
	$sql .= "' WHERE id =".$_POST['saved_id'];
	$updatestmt = $myPDO->prepare($sql);
	$updatestmt->execute();
	$_SESSION['message'] = "Updated Successfully!";
}


header("Location: encounters.php");
exit();
?>