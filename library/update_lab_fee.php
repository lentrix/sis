<?php
session_start();
include("../config/dbc.php");
echo "<!------ [{$_GET['recipientID']}] -->";
if(isset($_SESSION['fullname'])){
	if(isset($_GET['class_code'], $_GET['value'])){
		mysqli_query($db, "UPDATE class SET lab_fee = {$_GET['value']} WHERE class_code={$_GET['class_code']}");
		if(mysqli_error($db))
			echo "<a href='#' title='" . mysqli_error($db) . "'>[*]</a>";
		else
			echo "<a href='#' title='Lab Fee Amount successfully updated!'>[*]</a>";
	}else{
		echo "class not found.";
	}
}else{
	echo "invalid user.";
}
?>