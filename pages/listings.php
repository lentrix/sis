<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>
<?php include("library/computations.php"); ?>

<h1>Student Listings</h1>
<div style="background: #fff; padding-top: 10px;height:28px;">
	<form method="post" action="" style="float:left;">
		List Category:
		<select name="category" id="category">
			<option value="course_category">Course</option>
			<option value="year_category">Year</option>
			<option value="course_year_category">Course &amp; Year</option>
			<option value="college_category">College</option>
			<option value="class_category">Class</option>
			<option value="address_category">Address</option>
		</select>
	</form>

	<form method="post" action="" style="float:left; margin-left: 10px;display:none" id="course_category" name="course_category">
		<?php CreateList("cr_num", "cr_num", "cr_acrnm", "SELECT cr_num, cr_acrnm FROM courses ORDER BY cr_acrnm", ""); ?>
		<input type="submit" name="submit_course_list" value="Go">
	</form>

	<form method="post" action="" style="float:left; margin-left: 10px;display:none" id="year_category">
		<select name="year">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>Q</option>
		</select>
		<input type="submit" name="submit_year_list" value="Go">
	</form>

	<form method="post" action="" style="float:left; margin-left: 10px;display:none" id="course_year_category">
		<?php CreateList("cr_num", "cr_num", "cr_acrnm", "SELECT cr_num, cr_acrnm FROM courses ORDER BY cr_acrnm", ""); ?>
		<select name="year">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>Q</option>
		</select>
		<input type="submit" name="submit_course_year_list" value="Go">
	</form>

	<form method="post" action="" style="float:left; margin-left: 10px;display:none" id="college_category">
		<?php CreateList("clg_no", "clg_no", "acrnm", "SELECT clg_no, acrnm FROM colleges ORDER BY acrnm", ""); ?>
		<input type="submit" name="submit_college_list" value="Go">
	</form>

	<form method="post" action="" style="float:left; margin-left: 10px;display:none" id="class_category">
		<?php
		$class_sql = "SELECT class.class_code, CONCAT(descript,' (',t_start,'-',t_end,')') AS 'subject' 
			FROM class, subjects, class_time WHERE class.sub_code=subjects.sub_code 
                        AND class.class_code=class_time.class_code
			AND sem_code={$_SESSION['sem_code']} ORDER BY descript";
		?>
		<?php CreateList("class_code", "class_code", "subject", $class_sql, "font-size:8pt;"); ?>
		<input type="submit" name="submit_class_list" value="Go">
	</form>

	<form action="" method="post" style="margin-left: 10px;display:none" id="address_category">
		&nbsp;<input type="text" name="barangay" id="barangay" placeholder="Barangay">
		</label><input type="text" name="town" id="town" placeholder="Town">
		<button type="submit" name="submit_address_list">Go</button>
	</form>

	<form method="post" action="">
		<input type="submit" value="Show All" name="submit_all" style="float: right;" />
	</form>
</div>


<!-- LIST RESULTS AREA -------------------------------------- -->

<span id="list_result">

	<?php

	if (isset($_POST['submit_all'])) {
		$list = mysqli_query($db, "SELECT stud_enrol.idnum, idext, cr_num, 
            CONCAT(lname,', ',fname,' ',mi) AS 'Name', cr_acrnm, year 
            FROM stud_enrol, stud_info, courses 
            WHERE stud_enrol.idnum=stud_info.idnum
            AND stud_enrol.course=courses.cr_num 
            AND sem_code={$_SESSION['sem_code']}
            ORDER BY lname,fname,mi");
		$list_detail = "List of all students for the semester";
	}

	if (isset($_POST['submit_course_list'])) {
		$list = mysqli_query($db, "SELECT stud_enrol.idnum, idext, cr_num, 
		CONCAT(lname,', ',fname,' ',mi) AS 'Name', cr_acrnm, year 
		FROM stud_enrol, stud_info, courses 
		WHERE stud_enrol.idnum=stud_info.idnum 
		AND stud_enrol.course=courses.cr_num 
		AND sem_code={$_SESSION['sem_code']} 
		AND stud_enrol.course={$_REQUEST['cr_num']}
		ORDER BY lname,fname,mi");

		$list_detail = "List of " . getCourseName($_REQUEST['cr_num']) . " Students";
	}
	if (isset($_POST['submit_year_list'])) {
		$list = mysqli_query($db, "SELECT stud_enrol.idnum, idext, cr_num, 
		CONCAT(lname,', ',fname,' ',mi) AS 'Name', cr_acrnm, year 
		FROM stud_enrol, stud_info, courses 
		WHERE stud_enrol.idnum=stud_info.idnum 
		AND stud_enrol.course=courses.cr_num 
		AND sem_code={$_SESSION['sem_code']} 
		AND stud_enrol.year='{$_REQUEST['year']}'
		ORDER BY lname,fname,mi");
		switch ($_REQUEST['year']) {
			case 1:
				$list_detail = "First Year";
				break;
			case 2:
				$list_detail = "Second Year";
				break;
			case 3:
				$list_detail = "Third Year";
				break;
			case 4:
				$list_detail = "Fourth Year";
				break;
			case 'Q':
				$list_detail = "Qualifying";
				break;
			default:
				$list_detail = "Blank coursed";
				break;
		}
		$list_detail = "List of " . $list_detail . " Students";
	}
	if (isset($_POST['submit_course_year_list'])) {
		$list = mysqli_query($db, "SELECT idext, stud_enrol.idnum, cr_num, 
			CONCAT(lname,', ',fname,' ',mi) AS 'Name', 
			cr_acrnm, year FROM stud_info, stud_enrol, courses 
			WHERE stud_enrol.idnum = stud_info.idnum 
			AND courses.cr_num=stud_enrol.course 
			AND stud_enrol.course={$_REQUEST['cr_num']} AND year='{$_REQUEST['year']}' 
			AND sem_code={$_SESSION['sem_code']} ORDER BY lname, fname");

		switch ($_REQUEST['year']) {
			case 1:
				$list_detail = "First Year";
				break;
			case 2:
				$list_detail = "Second Year";
				break;
			case 3:
				$list_detail = "Third Year";
				break;
			case 4:
				$list_detail = "Fourth Year";
				break;
			case 'Q':
				$list_detail = "Qualifying";
				break;
			default:
				$list_detail = "Blank coursed";
				break;
		}
		$list_detail = "List of " . $list_detail . " " . getCourseName($_REQUEST['cr_num']) . " Students";
	}

	if (isset($_POST['submit_class_list'])) {
		$list = mysqli_query($db, "SELECT idext, stud_enrol.idnum, cr_num, 
			CONCAT(lname,', ',fname,' ',mi) AS 'Name', 
			cr_acrnm, year FROM stud_info, stud_enrol, courses 
			WHERE stud_enrol.idnum = stud_info.idnum 
			AND courses.cr_num=stud_enrol.course 
			AND sem_code={$_SESSION['sem_code']}
			AND stud_enrol.idnum IN (SELECT sub_enrol.idnum FROM sub_enrol 
				WHERE sub_enrol.class_code={$_REQUEST['class_code']})
			ORDER BY lname, fname");
		$list_detail = getClassNameAndTime($_REQUEST['class_code'], true);
	}

	if (isset($_POST['submit_college_list'])) {
		$list = mysqli_query($db, "SELECT idext, stud_enrol.idnum, cr_num,
			CONCAT(lname,', ',fname,' ',mi) AS 'Name',
			cr_acrnm, year FROM stud_info, stud_enrol, courses
			WHERE stud_enrol.idnum = stud_info.idnum
			AND courses.cr_num=stud_enrol.course
			AND sem_code={$_SESSION['sem_code']}
			AND stud_enrol.course IN
				(SELECT cr_num FROM courses WHERE clg_no={$_REQUEST['clg_no']})
			ORDER BY lname, fname");
		$list_detail = "List of " . getCollegeAccronym($_REQUEST['clg_no']) . " Students";
	}

	if (isset($_POST['submit_address_list'])) {
		$town = $_REQUEST['town'];
		$brgy = $_REQUEST['barangay'];

		$hasBar = $brgy ? " AND stud_info.addb='$brgy'" : "";

		$list = $db->query("SELECT idext, stud_enrol.idnum, cr_num,
		CONCAT(lname,', ',fname,' ',mi) AS 'Name',
		cr_acrnm, year FROM stud_info, stud_enrol, courses
		WHERE stud_enrol.idnum = stud_info.idnum
		AND courses.cr_num=stud_enrol.course
		AND sem_code={$_SESSION['sem_code']}
		AND stud_info.addt='$town'" . $hasBar);
		$hasbar = $brgy ? "$brgy, " : "";
		$list_detail = "List of students from $hasbar $town";
	}
	?>
	<?php if (isset($list)) { ?>
		<div style="text-align:center">

			<input type="image" src="images/printButton.png" class="noprint" onclick="printThisDiv('printFrame','list_result')" style="float: right;">
			<iframe id="printFrame" style="width: 0px; height: 0px; float: left; margin-left: -9999px"></iframe>

			<h2 style="width:500px;" class="center">
				<?php echo $list_detail ?>
			</h2>
			<p style="margin-top: 0px;">(<?php getSemester($_SESSION['sem_code']); ?>) <br />
				No. of records found: <?php echo mysqli_num_rows($list); ?>
			</p>
		</div>
		<table style="width: 550px; margin-left: auto; margin-right: auto;">
			<tr>
				<td class="thead" width="50">No.:</td>
				<td class="thead" width="100">ID Number:</td>
				<td class="thead" width="350">Name:</td>
				<td class="thead" width="150">Course:</td>
				<td class="thead" width="50">Year:</td>
				<td class="thead" width="50">Pay Units:</td>
				<td class="thead" width="50">Cr. Units:</td>
			</tr>
			<?php $n = 0;
			$tpu = 0;
			$tcu = 0;
			while ($lrow = mysqli_fetch_assoc($list)) { ?>
				<tr>
					<td class="tcel"> <?php echo ++$n . "."; ?></td>
					<td class="tcel"><a href="index.php?page=student_subjects&idnum=<?php echo $lrow['idnum']; ?>&course=<?php echo $lrow['cr_num']; ?>&year=<?php echo $lrow['year']; ?>">
							<?php echo $lrow['idnum'] . "-" . $lrow['idext'] ?></a></td>
					<td class="tcel"> <?php echo $lrow['Name'] ?></td>
					<td class="tcel"> <?php echo $lrow['cr_acrnm'] ?></td>
					<td class="tcel" align="center"><?php echo $lrow['year'] ?></td>
					<?php $pu = getTotalPayUnits($lrow['idnum']); ?>
					<?php $cu =  getTotalCreditUnits($lrow['idnum']); ?>
					<td class="tcel" align="center"><?php echo $pu; ?></td>
					<td class="tcel" align="center"><?php echo $cu; ?></td>
					<?php $tpu += $pu;
					$tcu += $cu; ?>
					</td>
				<?php } ?>
				<tr>
					<td colspan="5" class="tcel">TOTAL UNITS: </td>
					<td class="tcel"><?php echo $tpu; ?></td>
					<td class="tcel"><?php echo $tcu; ?></td>
				</tr>
				<tr>
					<td colspan="7" class="tfoot">&nbsp;</td>
				</tr>
		</table>
	<?php } ?>
</span>

<script>
	$(document).ready(function() {
		function switchForm(n) {
			forms = new Array();
			forms = new Array("course_category", "year_category", "course_year_category", "college_category", "class_category", "address_category");

			for (i = 0; i < forms.length; i++) {
				if (forms[i] == n) document.getElementById(forms[i]).style.display = 'block';
				else document.getElementById(forms[i]).style.display = 'none';
			}

		}

		$("#category").change(function(){
			var categ = $("#category option:selected").val();
			switchForm(categ);
		})
	})
</script>