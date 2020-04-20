<?php include("library/lister.php");?>

<h1>Student Grades</h1>

<div style="background: #fff;">
<form method="get" action="">
<?php 
    $mods = mysql_query("SELECT * FROM modassgn WHERE user='{$_SESSION['user']}' AND (modlno=1 OR modlno=09)");
    if(mysql_num_rows($mods)!=1) $college_filter = "AND courses.clg_no={$_SESSION['clg_no']}";
    else $college_filter="";
    $sql_statement = "SELECT stud_info.idnum, CONCAT(lname,', ',fname,' ',mi,' ',cr_acrnm,'-',stud_enrol.year) as 'student'
		FROM stud_info, stud_enrol, courses
		WHERE stud_info.idnum=stud_enrol.idnum
		AND courses.cr_num=stud_enrol.course
		AND stud_enrol.sem_code={$_SESSION['sem_code']}
		$college_filter
		ORDER BY lname, fname"; ?>
Select Student: <?php CreateList("idnum","idnum","student",$sql_statement,""); ?>
	<input type="hidden" name="sem_code" value="<?php echo $_SESSION['sem_code']; ?>" />
	<input type="hidden" name="page" value="student_grades" />
<input type="submit" name="submit_view_grade" value="Show Grades">

</form>
</div>


<?php 
if(isset($_REQUEST['submit_view_grade']) || isset($_GET['idnum'])){
	include("library/student_grade.php"); ?>
<div id="student_grades">
<input type="image" src="images/printButton.png" class="noprint"
	onClick="printThisDiv('subgrade_print','student_grades');"
    style="float: right;" />
<?php	if(isset($_GET['sem_code'])) {
		$semcode = $_GET['sem_code']; 
	}else {
		$semcode=$_SESSION['sem_code'];
	}
	showStudentGrade($_REQUEST['idnum'], $semcode); ?>
</div>
<?php	
}
?>

<iframe id="subgrade_print" style="float: left; margin-left: -9999px; width: 0px; height: 0px;"></iframe>
