<?php include("../config/dbc.php"); ?>
<?php $sem = mysqli_query($db, "SELECT sem FROM sems WHERE sem_code={$_POST['sem_code']}") ?>
<?php $srow = mysqli_fetch_row($sem); ?>
<?php $title = "Graduates Report - $srow[0]"; ?>
<!doctype html>
<html>

<head>
	<title><?php echo $title; ?></title>
</head>

<body>
	<h1><?php echo $title; ?></h1>
	<?php $grd = mysqli_query($db, "SELECT i.idnum, i.idext, i.bdate, i.lname, i.fname, 
					i.mi, i.gender, g.dt_grad, c.course, c.major, 
					g.poa_auth_num, g.year_granted 
			FROM stud_info i, grad_details g, courses c
			WHERE i.idnum=g.idnum AND g.cr_num=c.cr_num AND g.sem_code={$_POST['sem_code']}"); ?>
	<?php if (mysqli_error($db)) echo mysqli_error($db); ?>
	<table border="1">
		<tr>
			<th>ID #</th>
			<th>Date of Birth</th>
			<th>Last Name</th>
			<th>First Name</th>
			<th>M.I.</th>
			<th>Sex</th>
			<th>Date Graduated</th>
			<th>Program</th>
			<th>Major</th>
			<th>Poa_Number</th>
			<th>Year Granted</th>
		</tr>
		<?php while ($row = mysqli_fetch_assoc($grd)) { ?>
			<tr>
				<td><?php echo $row['idnum'] . '-' . $row['idext']; ?></td>
				<td><?php echo date('M d, Y', strtotime($row['bdate'])); ?></td>
				<td><?php echo $row['lname']; ?></td>
				<td><?php echo $row['fname']; ?></td>
				<td><?php echo $row['mi']; ?></td>
				<td><?php echo $row['gender']; ?></td>
				<td><?php echo date('M d, Y', strtotime($row['dt_grad'])); ?></td>
				<td><?php echo $row['course']; ?></td>
				<td><?php echo $row['major']; ?></td>
				<td><?php echo $row['poa_auth_num']; ?></td>
				<td><?php echo $row['year_granted']; ?></td>
			</tr>
		<?php } ?>
	</table>
</body>

</html>