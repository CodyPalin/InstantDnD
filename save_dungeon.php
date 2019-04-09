<?php
session_start();
include 'filter.php';
include 'Dao.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();

$_POST["name"] = filter($_POST["name"]);
$_POST["dungeon_environment"] = filter($_POST["dungeon_environment"]);
$_POST["dungeon_lighting"] = filter($_POST["dungeon_lighting"]);
$_POST["loot_locations"] = filter($_POST["loot_locations"]);

if(strlen($_POST["name"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for dungeon type.";
	header("Location: dungeons.php");
	exit();
}
else if(strlen($_POST["dungeon_environment"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for dungeon location.";
	header("Location: dungeons.php");
	exit();
}
else if(strlen($_POST["dungeon_lighting"]) > 64){
	$_SESSION['message'] = "Limit: 64 characters for dungeon lighting.";
	header("Location: dungeons.php");
	exit();
}
else if(strlen($_POST["name"]) == 0){
	$_SESSION['message'] = "Dungeon must have a type.";
	header("Location: dungeons.php");
	exit();
}
else if(strlen($_POST["dungeon_environment"]) == 0){
	$_SESSION['message'] = "Dungeon must have a location.";
	header("Location: dungeons.php");
	exit();
}
else if(strlen($_POST["dungeon_lighting"]) == 0){
	$_SESSION['message'] = "Dungeon must have lighting.";
	header("Location: dungeons.php");
	exit();
}
else{
	$id = $_SESSION['userid'];
	if(!isset($_POST["saved_id"])){
		$sql = "INSERT INTO saved_dungeons (user_id, dungeon_name, dungeon_environment, dungeon_lighting, loot_locations) VALUES (?,?,?,?,?)";
		$insertstmt = $myPDO->prepare($sql);
		$insertstmt->execute([$id, $_POST["name"],$_POST["dungeon_environment"],$_POST["dungeon_lighting"],$_POST["loot_locations"]]);
		
		$numstmt = $myPDO->prepare("UPDATE user SET saved_dungeons = saved_dungeons+1 WHERE id = '$id'");
		$numstmt->execute();
		$_SESSION['message'] = "Saved Successfully!";
	}
	else
	{
		//update saved
		$sql = "UPDATE saved_dungeons SET dungeon_name ='".$_POST['name']."', dungeon_environment='".$_POST['dungeon_environment']."', dungeon_lighting='".$_POST['dungeon_lighting']."', loot_locations='".$_POST['loot_locations']."' WHERE id =".$_POST['saved_id'];
		$updatestmt = $myPDO->prepare($sql);
		$updatestmt->execute();
		$_SESSION['message'] = "Updated Successfully!";
	}
	
	
	
	header("Location: dungeons.php");
	exit();
}
?>