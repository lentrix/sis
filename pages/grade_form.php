<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>

<?php
function isSubmitted($classCode)
{
	global $db;
	$c = mysqli_query($db, "SELECT grd_sub FROM class WHERE class_code=$classCode");
	$crs = mysqli_fetch_row($c);
	return $crs[0] == 1;
}
?>


<script type="text/javascript">
	function computeRating(num, obj) {
		mid = document.getElementById('mid' + num);
		fin = document.getElementById('fin' + num);
		rt = document.getElementById('rt' + num);

		if (mid.value == 'dr' || mid.value == 'w' || mid.value == 'nc' || mid.value == 'inc' || mid.value == 'ng') {
			rt.value = mid.value;
			fin.value = mid.value;
		} else if (fin.value == 'dr' || fin.value == 'w' || fin.value == 'nc' || fin.value == 'inc' || fin.value == 'ng') {
			rt.value = fin.value;
		} else {

			rating = (16 - (16 - ((mid.value * 1) + (fin.value * 1)) / 2));

			obj.style.backgroundColor = '#cc6666';

			if (mid.value && fin.value) {
				if (rating > 3) rt.value = '5.0';
				else rt.value = rating;
				rt.style.backgroundColor = '#cc6666';
			} else {
				rt.value = '';
				rt.style.backgroundColor = '#cc6666';
			}
		}
	}
</script>


<h1>Grade Form</h1>
<br />

<?php
if (isset($_POST['submit_save_grades'])) {
	$class_code = $_POST['class_code'];
	$n = count($_POST['idnum']);
	for ($i = 0; $i < $n; $i++) {
		if (!empty($_POST['mid'][$i])) $mid = $_POST['mid'][$i];
		else $mid = '';
		if (!empty($_POST['fin'][$i])) $fin = $_POST['fin'][$i];
		else $fin = '';
		if (!empty($_POST['rating'][$i])) $rating = $_POST['rating'][$i];
		else $rating = '-';

		$idnum = $_POST['idnum'][$i];

		mysqli_query($db, "UPDATE sub_enrol SET mgrade='$mid', fgrade='$fin', rating='$rating' WHERE idnum=$idnum AND class_code=$class_code");
		if (mysqli_error($db)) echo mysqli_error($db);
	}
	$date = date('Y-m-d g:i:s');
	$class_detail = getClassDetails($class_code, false);
	mysqli_query($db, "INSERT INTO log (user, date, detail) 
							VALUES ('{$_SESSION['user']}', '$date',
							'Updated the grade of the class $class_detail.')");

	//$date = date('Y-m-d');
	mysqli_query($db, "INSERT INTO subm_history (date, remarks, class_code) 
				VALUES ('$date', 'submitted through the Grade Form.', $class_code)");
	mysqli_query($db, "UPDATE class SET sub_dt = '$date', grd_sub=0 WHERE class_code=$class_code");

	if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
	else echo "<div class='error'>The gradesheet has been updated. A log entry has been recorded about this event.</div><br />";
}
?>

<?php if (!isset($_POST['class_code'])) { ?>
	<div>
		<div class="div_title">
			Select Class:
		</div>
		<form method="post" action="">
			<?php $mdl = mysqli_query($db, "SELECT * FROM modassgn WHERE modlno=1 AND user='{$_SESSION['user']}'"); ?>
			<?php if (mysqli_num_rows($mdl)) { ?>
				<?php ListClass(
					"class_code",
					"class_code",
					"SELECT class_code, name, descript 
						    FROM class, subjects
						    WHERE class.sub_code=subjects.sub_code
						    AND sem_code={$_SESSION['sem_code']}
						    ORDER BY name",
					"",
					"width: 600px;"
				); ?>
			<?php } else { ?>
				<?php ListClass(
					"class_code",
					"class_code",
					"SELECT class_code, name, descript 
						    FROM class, subjects
						    WHERE class.sub_code=subjects.sub_code
						    AND sem_code={$_SESSION['sem_code']}
						    AND class.tch_num={$_SESSION['tch_num']}
						    ORDER BY name",
					"",
					"width: 600px;"
				); ?>
			<?php } ?>
			<input type="submit" value="Open" name="submit_open_class" />
		</form>
	</div>
<?php	} else { ?>
	<div style="background-color: #fff">
		<div class="div_title">Grade Form [Class Code: <?php echo $_POST['class_code']; ?>]</div>
		<input type="button" value="X" onclick="document.location='index.php?page=grade_form'" style="float:right;" />
		<br />

		<?php
		$clsrec = mysqli_query($db, "SELECT * FROM class_record WHERE class_code={$_POST['class_code']}");
		if (mysqli_num_rows($clsrec) > 0) {
			echo "<div class='error'>Warning!<br/ >There is an existing electronic class record for this class. Changing the contents of this gradesheet
						may cause inconistency of data between gradesheet and class record.</div>";
		}

		?>
		<p align="center"><strong>GRADESHEET FORM</strong></p>
		<p align="center"><?php echo getClassDetails($_POST['class_code'], true); ?></p>

		<form action="" method="post">
			<input type="hidden" name="class_code" value="<?php echo $_POST['class_code']; ?>" />
			<table border="1" style="border-collapse: collapse" align="center">
				<tr>
					<th width="30">#</th>
					<th width="180">Last Name:</th>
					<th width="180">First Name:</th>
					<th width="30">MI:</th>
					<th width="100">Course:</th>
					<th width="25">Year:</th>
					<th width="40">Mid:</th>
					<th width="40">Fin:</th>
					<th width="40">Rating:</th>
				</tr>
				<?php
				$stn = mysqli_query($db, "SELECT stud_enrol.idnum, lname, fname, mi, cr_acrnm, year, mgrade, fgrade, rating  
							FROM stud_info, stud_enrol, sub_enrol, class, courses
							WHERE stud_info.idnum=stud_enrol.idnum
							AND stud_enrol.idnum=sub_enrol.idnum
							AND sub_enrol.class_code=class.class_code
							AND stud_enrol.course=courses.cr_num
							AND stud_enrol.sem_code={$_SESSION['sem_code']}
							AND class.class_code={$_POST['class_code']}
							ORDER BY lname, fname");
				echo mysqli_error($db);
				$n = 0;
				while ($stnr = mysqli_fetch_assoc($stn)) {
				?>
					<tr>
						<td>
							<?php echo ++$n . "."; ?>
							<input type="hidden" name="idnum[]" value="<?php echo $stnr['idnum']; ?>" />
						</td>
						<td><?php echo $stnr['lname']; ?></td>
						<td><?php echo $stnr['fname']; ?></td>
						<td><?php echo $stnr['mi']; ?></td>
						<td><?php echo $stnr['cr_acrnm']; ?></td>
						<td><?php echo $stnr['year']; ?></td>
						<td><input type="text" id="mid<?php echo $n; ?>" name="mid[]" style="width: 35px; border: 0px;" onChange="computeRating(<?php echo $n; ?>, this);" value="<?php if (isset($stnr['mgrade'])) echo $stnr['mgrade']; ?>" /></td>
						<td><input type="text" id="fin<?php echo $n; ?>" name="fin[]" style="width: 35px; border: 0px;" onChange="computeRating(<?php echo $n; ?>, this);" value="<?php if (isset($stnr['fgrade'])) echo $stnr['fgrade']; ?>" /></td>
						<td><input type="text" id="rt<?php echo $n; ?>" name="rating[]" style="width: 35px; border: 0px;" value="<?php if (isset($stnr['rating']))
																																	if ($stnr['rating'] == 'NULL') echo '';
																																	else echo $stnr['rating']; ?>" /></td>
					</tr>
				<?php 		} ?>
				<tr>
					<td colspan="9" align="right">
						<?php if (!isSubmitted($_POST['class_code'])) : ?>
							<input type="submit" name="submit_save_grades" value="Submit" style="font-size: 14pt; width: 150px" />
						<?php else : ?>
							<p>
								Sorry this class is already closed.<br />
								Please request for re-opening of the class from the Registrar's Office.
							</p>
						<?php endif; ?>
					</td>
				</tr>
			</table>

		</form>
		<a href="index.php?page=view_gradesheet&class_code=<?php echo $_POST['class_code']; ?>">
			View Grade Sheet
		</a>
	</div>
<?php 	} ?>