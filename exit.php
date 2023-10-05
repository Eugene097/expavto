<?php
	session_start();
	include_once("link.php");

	$token=$_SESSION["Token"];

	$query="UPDATE users SET Token=NULL WHERE Token='$token'";
	mysqli_query($link, $query);

	header("Location: index.php");

	session_destroy();
?>