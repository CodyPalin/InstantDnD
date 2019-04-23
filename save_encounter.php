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
for($i = 1; i<=8; i++)
{
	if(isset($_POST["monster".$i]){
		$_POST["monster".$i] = filter($_POST["monster".$i]);
		
		if(strlen($_POST["monster".$i]) >64){
		$_SESSION['message'] = "Limit: 64 characters.";
		header("Location: dungeons.php");
		exit();
		}
	}
	else {
		$numMonsters = $i-1;
		break;
	}
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