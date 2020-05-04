<?php include("library/lister.php"); ?>

<h1>Student Subject View Selector</h1>

<div style="background: #fff;">

	<form method="get" action="">
		<?php
		$mods = mysqli_query($db, "SELECT * FROM modassgn WHERE user='{$_SESSION['user']}' AND (modlno=1 OR modlno=09)");
		if (mysqli_num_rows($mods) != 1) $college_filter = "AND courses.clg_no={$_SESSION['clg_no']}";
		else $college_filter = "";
		$sql_statement = "SELECT stud_info.idnum, CONCAT(lname,', ',fname,' ',mi,' ',cr_acrnm,'-',stud_enrol.year) as 'student'
		FROM stud_info, stud_enrol, courses
		WHERE stud_info.idnum=stud_enrol.idnum
		AND courses.cr_num=stud_enrol.course
		AND stud_enrol.sem_code={$_SESSION['sem_code']}
		$college_filter
		ORDER BY lname, fname"; ?>
		Select Student: <?php CreateList("idnum", "idnum", "student", $sql_statement, ""); ?>
		<input type="hidden" name="sem_code" value="<?php echo $_SESSION['sem_code']; ?>" />
		<input type="hidden" name="page" value="student_subjects" />
		<input type="submit" name="submit_view_grade" value="Show Grades">

	</form>
</div>