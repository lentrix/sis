<?php include("library/lister.php"); ?>
<?php include("library/prospectus_generator.php"); ?>
<h1>Evaluation</h1>

<div>
	<form method="post" action="">
		Student:
		<?php CreateList(
			"idnum",
			"idnum",
			"name",
			"SELECT stud_enrol.idnum, CONCAT(lname,', ',fname,' ',mi,' ',cr_acrnm,'-',year) as 'name'
		FROM stud_info, stud_enrol, courses
		WHERE stud_info.idnum=stud_enrol.idnum
		AND courses.cr_num=stud_enrol.course
		AND stud_enrol.sem_code={$_SESSION['sem_code']}
		AND courses.clg_no={$_SESSION['clg_no']}
		ORDER BY lname, fname",
			"font-size: 9pt;",
			"font-size:9pt;width:250px;"
		); ?>
		Prospectus:
		<?php CreateList(
			"prp_code",
			"prp_code",
			"course_year",
			"SELECT CONCAT(courses.course,' - ',year) AS 'course_year', prp_code FROM prospectus, courses
		WHERE prospectus.course=courses.cr_num
		AND courses.clg_no={$_SESSION['clg_no']}",
			"font-size:9pt",
			"font-size:9pt;width:250px;"
		); ?>
		<input type="submit" name="submit_show_evaluation" value="Show" />
	</form>
</div>

<?php
if (isset($_POST['submit_change_credit'])) {
	mysqli_query($db, "UPDATE sub_enrol SET sub_code={$_REQUEST['sub_code']} 
		WHERE idnum={$_REQUEST['idnum']} AND class_code={$_REQUEST['class_code']}");
	if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
	else echo "<div class='error'>The subject has been credited.</div>";
}

if (isset($_REQUEST['idnum'], $_REQUEST['prp_code'])) {
	echo "<div style='background-color: #fff;' id='printable'>";

	echo '<input type="image" src="images/printButton.png" class="noprint"
			onClick="printThisDiv(\'printFrame\',\'printable\');"
			style="float: right; margin-top: 5px; margin-right: 5px;display:screen;">';

	echo '<iframe name="printFrame" id="printFrame" style="width:0px;height:0px;float:left; margin-left: -9999px"></iframe>';

	showEvaluation($_REQUEST['idnum'], $_REQUEST['prp_code']);
	echo "</div>"; ?>
	<br />
	<div>
		<div class="div_title">Uncredited Subjects</div>
		<div>
			<br />
			<table border="1">
				<tr>
					<td width="300">Subject:</td>
					<td width="350">Credit to:</td>
				</tr>
				<?php
				$ucs = mysqli_query($db, "SELECT class.class_code, sub_enrol.sub_code, descript FROM sub_enrol, subjects, class
			WHERE idnum={$_REQUEST['idnum']} AND sub_enrol.sub_code NOT IN 
			(SELECT pros_subj.sub_code FROM pros_subj WHERE prp_code={$_REQUEST['prp_code']})
			AND sub_enrol.sub_code=subjects.sub_code
			AND class.class_code=sub_enrol.class_code");

				while ($ucr = mysqli_fetch_assoc($ucs)) {
					echo "<tr><td>{$ucr['descript']}</td>
				<td>
					<form method='post' action='' style='display:inline'>
					<input type='hidden' name='idnum' value='{$_REQUEST['idnum']}' />
					<input type='hidden' name='prp_code' value='{$_REQUEST['prp_code']}' />
					<input type='hidden' name='class_code' value='{$ucr['class_code']}' />";
					createList(
						"sub_code",
						"sub_code",
						"descript",
						"SELECT pros_subj.sub_code, descript FROM subjects, pros_subj
							WHERE subjects.sub_code=pros_subj.sub_code
							AND pros_subj.prp_code={$_REQUEST['prp_code']}
							AND pros_subj.sub_code NOT IN 
							(SELECT sub_code FROM sub_enrol WHERE idnum={$_REQUEST['idnum']}) ORDER BY descript",
						"font-size:9pt",
						"font-size:9pt;width:300px"
					);
					echo "			<input type='submit' name='submit_change_credit' value='Set' />
					</form>
				</td>
			  </tr>";
				}
				?>
			</table>
		</div>
	</div>

<?php
}
?>