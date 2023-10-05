<?php
    include_once("link.php");

	$name=$_POST["couse"];
	$description=$_POST["description"];
	$idAnnouncements=$_POST["report"];
	$query_report="INSERT INTO complaints(nameComplaint, description, idAnnouncements) VALUES ('$name', '$description', $idAnnouncements)";
	echo $query_report;
	mysqli_query($link, $query_report);
?>