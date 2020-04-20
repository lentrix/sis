<?php
$u = "root";
$p = "";

$cxn1 = mysqli_connect("localhost","root","");
mysqli_select_db($cxn1, "cashier");

$cxn2 = mysqli_connect("localhost","root","");
mysqli_select_db($cxn2, "mdc");

$from = mysqli_query($cxn1,"SELECT * FROM students");

echo mysql_error(); 

while($fr=mysqli_fetch_assoc($from)) {
	mysqli_query($cxn2, "UPDATE stud_info SET base_rate={$fr['rate']} WHERE idnum={$fr['STUD_NO']}");
	if(mysql_error()) echo "<p>" . mysql_error() . "</p>";
}
?>