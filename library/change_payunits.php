<?php include("../config/dbc.php"); ?>
<!--  [<?php echo $_GET['recipientID']; ?>] -->
<?php
if(isset($_GET['c'], $_GET['v'])){
	$code = $_GET['c'];
	$val = $_GET['v'];
	mysqli_query($db, "UPDATE class SET punits=$val WHERE class_code=$code");
	if(mysqli_error($db)) echo mysqli_error($db);
	else echo "&radic;";
}else{
	echo "Error!";
}
?>