<?php
session_start();
include("../config/dbc.php");
echo "<!------ [{$_GET['recipientID']}] -->";
if(isset($_SESSION['fullname'])){
	if(isset($_GET['class_code'], $_GET['value'])){
		mysql_query("UPDATE class SET lab_fee = {$_GET['value']} WHERE class_code={$_GET['class_code']}");
		if(mysql_error()) 
			echo "<a href='#' title='" . mysql_error() . "'>[*]</a>";
		else 
			echo "<a href='#' title='Lab Fee Amount successfully updated!'>[*]</a>";
	}else{
		echo "class not found.";
	}
}else{
	echo "invalid user.";
}
?>