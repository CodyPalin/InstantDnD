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
$_POST["name"] = filter($_POST["name"]);
$_POST["race"] = filter($_POST["race"]);
$_POST["background"] = filter($_POST["background"]);
$_POST["alignment"] = filter($_POST["alignment"]);
$_POST["weapons"] = filter($_POST["weapons"]);
$_POST["loots"] = filter($_POST["loots"]);
$_POST["damages"] = filter($_POST["damages"]);


if(strlen($_POST["weapons"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for weapon type.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["alignment"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for alignment.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["name"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for name.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["race"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for race.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["damages"]) > 64){
	$_SESSION['message'] = "Limit: 64 characters for weapon damage.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["weapons"]) == 0){
	$_SESSION['message'] = "NPC must have a weapon.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["damages"]) == 0){
	$_SESSION['message'] = "Weapon must have a damage.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["name"]) == 0){
	$_SESSION['message'] = "NPC must have a name.";
	header("Location: NPCs.php");
	exit();
}
else if(strlen($_POST["race"]) == 0){
	$_SESSION['message'] = "NPC must have a race.";
	header("Location: NPCs.php");
	exit();
}
else{
	$id = $_SESSION['userid'];
	if(!isset($_POST["saved_id"])){
		$sql = "INSERT INTO saved_npcs (user_id, NPC_name, NPC_race, NPC_background, char_alignment, NPC_weapon, NPC_loot, NPC_weapon_damage) VALUES (?,?,?,?,?,?,?,?)";
		$insertstmt = $myPDO->prepare($sql);
		$insertstmt->execute([$id, $_POST["name"],$_POST["race"],$_POST["background"],$_POST["alignment"],$_POST["weapons"],$_POST["loots"],$_POST["damages"]]);
		
		$numstmt = $myPDO->prepare("UPDATE user SET saved_NPCs = saved_NPCs+1 WHERE id = '$id'");
		$numstmt->execute();
		$_SESSION['message'] = "Saved Successfully!";
	}
	else
	{
		//update saved
		$sql = "UPDATE saved_npcs SET NPC_name ='".$_POST['name']."', NPC_race='".$_POST['race']."', char_alignment='".$_POST['alignment']."', NPC_weapon='".$_POST['weapons']."', NPC_loot='".$_POST['loots']."', NPC_weapon_damage='".$_POST['damages']."' WHERE id =".$_POST['saved_id'];
		$updatestmt = $myPDO->prepare($sql);
		$updatestmt->execute();
		$_SESSION['message'] = "Updated Successfully!";
	}
	
	
	
	header("Location: NPCs.php");
	exit();
}
?>