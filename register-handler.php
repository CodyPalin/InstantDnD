<?php
session_start();
include 'Dao.php';
include 'filter.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();
$_POST["username"] = filter($_POST["username"]);

if(!isset($_SESSION['current_page']))
	$_SESSION['current_page'] = "items.php";
$stmt1 = $myPDO->prepare("select username from user where username = '$_POST[username]'");
$stmt1->execute();
$user_exists = (($stmt1->fetch()[0]) != null);
if ($_POST["password"] != $_POST["confirm_password"])
{
	$_SESSION['message'] = "Error, the passwords do not match";
	header("Location: register.php");
	exit();
}
else if ($user_exists) {
	$_SESSION['message'] = "This username is already taken";
	header("Location: register.php");
	exit();
} 
else if ($_POST["username"] == "") {
	$_SESSION['message'] = "Error, username cannot be empty";
	header("Location: register.php");
	exit();
}
else if ($_POST["password"] == "") {
	$_SESSION['message'] = "Error, password cannot be empty";
	header("Location: register.php");
	exit();
}
else {
	$_POST["password"] = hashFunction($_POST["password"]);
	$sql = "INSERT INTO user (username, password) VALUES (?,?)";
	//prepare query to prevent sql injection attacks
	$stmt= $myPDO->prepare($sql);
	$stmt->execute([$_POST["username"], $_POST["password"]]);
	$_SESSION['logged_in'] = true;
	$_SESSION['user'] = $_POST["username"];
	
	$idstmt= $myPDO->prepare("select id from user where username = '$_SESSION[user]'");
	$idstmt->execute();
	$id = $idstmt->fetch()[0];
	
	$_SESSION['userid']=$id;
	header("Location:".$_SESSION['current_page']);
	exit();
	
}
?>