<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transfer Rates</title>
</head>

<body>
<?php
if(isset($_POST['submit_start_transfer'])){
	$user = $_POST['user'];
	$password = $_POST['password'];
	$cxn1 = mysqli_connect("localhost",$user, $password, "cashier");
	$cxn2 = mysqli_connect("localhost",$user, $password, "mdc");
	
	$source = mysqli_query($cxn1, "SELECT stud_no, rate FROM students WHERE rate>0")
	or die(mysqli_error($cxn1));
	while($sourcer = mysqli_fetch_assoc($source)){
		mysqli_query($cxn2,"UPDATE stud_info SET base_rate={$sourcer['rate']} 
							WHERE idnum={$sourcer['stud_no']}");
		if(mysqli_error($cxn2)) echo "<h2>" . mysqli_error($cxn2) . "</h2>";
	}
}
?>
<form method="post" action="">
	User Name: <input type="text" name="user" /> Password: <input type="password" name="password" />
	<input type="submit" name="submit_start_transfer" value="Start Transfer" />
</form>
</body>
</html>