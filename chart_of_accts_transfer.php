<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
if(isset($_POST['submit_transfer_chart_of_accts'])){
	$cxn1 = mysqli_connect("localhost",$_POST['user'],$_POST['password'],"cashier");
	$cxn2 = mysqli_connect("localhost",$_POST['user'],$_POST['password'],"mdc");
	
	if($cxn1 && $cxn2){
		$chart = mysqli_query($cxn1,"SELECT * FROM chart_of_accts ORDER BY acct_title");
		while($cr=mysqli_fetch_assoc($chart)){
			mysqli_query($cxn2,"INSERT INTO chart_of_accts (acct_title, acct_type)
					VALUES ('{$cr['acct_title']}','{$cr['acct_type']}')");
			if(mysqli_error($cxn2)){
				echo "<h3>" . mysqli_error($cxn2) . "</h3>";
			}
		}
	}else{
		echo "<h1>Invalid Entry!</h1>";
	}
}
?>
<form method="post" action="">
	User <input type="text" name="user" /> <br />
    Password: <input type="password" name="password" />
	<input type="submit" value="Submit" name="submit_transfer_chart_of_accts" />
</form>
</body>
</html>