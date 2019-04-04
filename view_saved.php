<?php
session_start();

$_SESSION['view_saved'] = !$_SESSION['view_saved'];
header("Location: ".$_SESSION['current_page']);
?>