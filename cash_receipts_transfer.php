<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transfer Cash Receipts</title>
</head>

<body>
<?php
if(isset($_POST['submit_cash_receipts_transfer'])){
	$cxn1 = mysqli_connect("localhost",$_POST['user'],$_POST['password'],"cashier");
	$cxn2 = mysqli_connect("localhost",$_POST['user'],$_POST['password'],"mdc");
	$cr = mysqli_query($cxn1,
		"SELECT cr.date, cr.orno, cr.payor, cr.description, cr.amount
		FROM cash_receipts cr
		WHERE acct_title IN (33,34,35)
		AND date
		BETWEEN '{$_POST['start_date']}'
		AND '{$_POST['end_date']}'");
	echo mysqli_error($cxn1);
	echo "<table border='1'>";
	while($crr=mysqli_fetch_assoc($cr)){
		
		echo "<tr><td>{$crr['date']}</td>
				<td>{$crr['orno']}</td>
				<td>{$crr['payor']}</td>
				<td>{$crr['amount']}</td>
				<td>{$crr['description']}</td></tr>";

		
		mysqli_query($cxn2,"INSERT INTO payments (orno, sem_code, idnum, date, amount, acct_no, descr)
				VALUES ({$crr['orno']},{$_POST['sem_code']},{$crr['payor']}, '{$crr['date']}', {$crr['amount']}, 
						(SELECT acct_code FROM chart_of_accts WHERE acct_title='Entrance'),
						'{$_POST['description']}')");
		if(mysqli_error($cxn2)){
			echo "<li>" . mysqli_error($cxn2) . "</li>";
		}
		
	}
	echo "</table>";
}
?>
<form method="post" action="">
<table>
    <tr><td>User: </td><td><input type="text" name="user" /></td></tr>
    <tr><td>Password: </td><td><input type="password" name="password"  /></td></tr>
    <tr><td>Start date: </td><td><input type="text" name="start_date" /> </td></tr>
    <tr><td>End date: </td><td><input type="text" name="end_date" /></td></tr>
    <tr><td>Sem Code: </td><td><input type="text" name="sem_code" /></td></tr>
    <tr><td>Description: </td><td><input type="text" name="description" /></td></tr>
    <tr><td colspan="2"><input type="submit" name="submit_cash_receipts_transfer" value="Start Transfer" /></td></tr>
</form>
</body>
</html>
	
