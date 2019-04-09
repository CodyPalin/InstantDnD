<?php
session_start();
include 'filter.php';
include 'Dao.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();

$_POST["weapons"] = filter($_POST["weapons"]);
$_POST["adjectives"] = filter($_POST["adjectives"]);
$_POST["backstories"] = filter($_POST["backstories"]);
$_POST["damages"] = filter($_POST["damages"]);
$_POST["effects"] = filter($_POST["effects"]);

if(strlen($_POST["weapons"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for weapon type.";
	header("Location: items.php");
	exit();
}
else if(strlen($_POST["adjectives"]) >64){
	$_SESSION['message'] = "Limit: 64 characters for weapon adjective.";
	header("Location: items.php");
	exit();
}
else if(strlen($_POST["damages"]) > 64){
	$_SESSION['message'] = "Limit: 64 characters for base weapon damage.";
	header("Location: items.php");
	exit();
}
else if(strlen($_POST["weapons"]) == 0){
	$_SESSION['message'] = "Item must have a type.";
	header("Location: items.php");
	exit();
}
else if(strlen($_POST["damages"]) == 0){
	$_SESSION['message'] = "Item must have a damage.";
	header("Location: items.php");
	exit();
}
else{
	$id = $_SESSION['userid'];
	if(!isset($_POST["saved_id"])){
		$sql = "INSERT INTO saved_items (user_id, item_type, item_adjective, item_backstory, item_damage, item_effects) VALUES (?,?,?,?,?,?)";
		$insertstmt = $myPDO->prepare($sql);
		$insertstmt->execute([$id, $_POST["weapons"],$_POST["adjectives"],$_POST["backstories"],$_POST["damages"],$_POST["effects"]]);
		
		$numstmt = $myPDO->prepare("UPDATE user SET saved_items = saved_items+1 WHERE id = '$id'");
		$numstmt->execute();
		$_SESSION['message'] = "Saved Successfully!";
	}
	else
	{
		//update saved
		$sql = "UPDATE saved_items SET item_type ='".$_POST['weapons']."', item_adjective='".$_POST['adjectives']."', item_backstory='".$_POST['backstories']."', item_damage='".$_POST['damages']."', item_effects='".$_POST['effects']."' WHERE id =".$_POST['saved_id'];
		$updatestmt = $myPDO->prepare($sql);
		$updatestmt->execute();
		$_SESSION['message'] = "Updated Successfully!";
	}
	
	
	
	header("Location: items.php");
	exit();
}
?>