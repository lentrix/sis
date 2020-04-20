<?php include("library/singles.php"); ?>
<?php include("library/lister.php"); ?>
<?php include("library/stud_subjects.php"); ?>

<h1>Student Subjects</h1>
<?php if(isset($_GET['idnum'])) { ?>
<?php 	$idnum = $_GET['idnum']; ?>

<div id="study_load_div">
<input type="image" src="images/printButton.png" class="noprint"
		onClick="printThisDiv('printFrame','study_load_div');"
        style="float: right;" />
<center>
<h2 style="width: 500px;">Study Load</h2>
<strong><?php echo getFullName($idnum); ?> - <?php echo getCourseAndYear($idnum, $_SESSION['sem_code']); ?></strong><br/>
<?php echo getSemester($_SESSION['sem_code']); ?> <br/>

<?php ShowStudentSubjects($idnum, $_SESSION['sem_code'],false); ?>
</center>
</div>
<?php } ?>
<iframe id="printFrame" style="width: 0px; height: 0px; float:left; margin-left: -9999px;"></iframe>
