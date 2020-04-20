<?php include("../config/dbc.php"); ?>
<!--  [<?php echo $_GET['recipientID']; ?>] -->
<?php
if(isset($_GET['c'], $_GET['v'])){
	$code = $_GET['c'];
	$val = $_GET['v'];
	mysql_query("UPDATE class SET punits=$val WHERE class_code=$code");
	if(mysql_error()) echo mysql_error();
	else echo "&radic;";
}else{
	echo "Error!";
}
?>