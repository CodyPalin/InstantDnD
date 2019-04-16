<?php
session_start();
include 'Dao.php';
include 'filter.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();
$_POST["username"] = filter($_POST["username"]);
$_POST["password"] = hashFunction($_POST["password"]);
$password_in_the_database = $myPDO->query("select password from user where username = '$_POST[username]'")->fetch()[0];
if ($password_in_the_database != $_POST["password"]) {
	$_SESSION['message'] = "Error, the password was incorrect";
	header("Location: login.php");
	exit();
}
else if ($_POST["username"] == "") {
	$_SESSION['message'] = "Error, username cannot be empty";
	header("Location: login.php");
	exit();
}
else if ($_POST["password"] == "") {
	$_SESSION['message'] = "Error, password cannot be empty";
	header("Location: login.php");
	exit();
}
else {

	$_SESSION['logged_in'] = true;
	$_SESSION['user'] = $_POST["username"];
	
	$idstmt= $myPDO->prepare("select id from user where username = '$_SESSION[user]'");
	$idstmt->execute();
	$id = $idstmt->fetch()[0];
	
	$_SESSION['userid']=$id;
	
	header("Location:".$_SESSION['current_page']);
	
}
?>