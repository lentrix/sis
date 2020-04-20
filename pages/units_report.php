<?php include("library/lister.php");?>
<h1>Total Units Report</h1>

<form method="post" action="" >
	Select College: 
	<?php CreateList("clg_no","clg_no","acrnm","SELECT * FROM colleges ORDER BY acrnm",""); ?>
	<input type="submit" name="report" value="Generate Total Units Report" />
</form>

<hr />

<?php 
if(isset($_POST['report'])) {
	if(!$_POST['clg_no']){
		$sqlstm = "SELECT i.idnum, i.lname, i.fname, i.mi, c.cr_acrnm, se.year, SUM(cl.punits) AS 'tpunits' ,SUM(cl.cunits) AS 'tcunits'
FROM stud_info i, stud_enrol se, courses c, class cl, sub_enrol sb
WHERE i.idnum=se.idnum AND se.course=c.cr_num AND cl.class_code=sb.class_code AND sb.idnum=se.idnum
AND se.sem_code=12 AND se.sem_code=cl.sem_code
GROUP BY se.idnum
ORDER BY lname, fname";
	}else{
		$sqlstm = "SELECT i.idnum, i.lname, i.fname, i.mi, c.cr_acrnm, se.year, SUM(cl.punits) AS 'tpunits' ,SUM(cl.cunits) AS 'tcunits'
FROM stud_info i, stud_enrol se, courses c, class cl, sub_enrol sb
WHERE i.idnum=se.idnum AND se.course=c.cr_num AND cl.class_code=sb.class_code AND sb.idnum=se.idnum
AND se.sem_code=12 AND se.sem_code=cl.sem_code AND c.clg_no={$_POST['clg_no']}
GROUP BY se.idnum
ORDER BY lname, fname";
	}
	
	
	$res = mysql_query($sqlstm);
	if(mysql_error()) echo "<p>" . mysql_error() . "</p>";
}
?>

<?php if(isset($_POST['report'])) { ?>

<table>
	<tr>
		<th>ID Number:</th>
		<th>Last Name: </th>
		<th>First Name: </th>
		<th>MI:</th>
		<th>Course:</th>
		<th>Year:</th>
		<th>T.Pay Units:</th>
		<th>T.Cr Units:</th>
	</tr>
	<?php $totalPayUnits = 0; $totalCreditUnits=0; ?>
	<?php while($row = mysql_fetch_assoc($res)) { ?>
	<tr>
		<td><?php echo $row['idnum']; ?></td>
		<td><?php echo $row['lname']; ?></td>
		<td><?php echo $row['fname']; ?></td>
		<td><?php echo $row['mi']; ?></td>
		<td><?php echo $row['cr_acrnm']; ?></td>
		<td><?php echo $row['year']; ?></td>
		<td><?php echo $row['tpunits']; ?></td>
		<td><?php echo $row['tcunits']; ?></td>
	</tr>
		<?php $totalPayUnits+=$row['tpunits']; ?>
		<?php $totalCreditUnits += $row['tcunits']; ?>
	<?php } ?>
	<tr>
		<td colspan="6"><strong>TOTAL</string></td>
		<td><strong><?php echo $totalPayUnits; ?></strong></td>
		<td><strong><?php echo $totalCreditUnits; ?></strong></td>
	</tr>
</table>

<?php } ?>

