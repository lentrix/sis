<?php 
include("library/checker.php"); 
include("library/lister.php");
include("library/singles.php");
include("library/stud_subjects.php");
?>
<h1>View Student Account</h1>

<div>
    <div style="border: 1px solid #444; background: #fff; padding: 10px;">
		<div class="div_title">Select Student</div>
		<div style="margin-top: 5px; padding-top: 10px;">
		ID Number: <input type="text" id="idnumsearch" size="6" maxlength="6"
		onchange="
				
				for(var i=0; i < document.studform.idnum.length; i++) {
					if(document.studform.idnum[i].value==document.getElementById('idnumsearch').value){
						document.studform.idnum[i].selected = true;
					}
				}
			" <?php check("idnum");?>/>
		 ==> 
			
		<form method="post" name="studform" action="" style="display:inline-block">
			Name: <?php CreateList("idnum","idnum","fullname",
				"SELECT idnum, CONCAT(lname, ', ',fname,' ' ,mi) AS 'fullname' 
				FROM stud_info ORDER BY lname, fname","");?>
			<input type="submit" value="Go" name="submit_open_stud_account"/>
		</form>
		</div>
	</div>
</div>

<?php
if(isset($_POST['submit_open_stud_account'])) {   
    $idnum = $_POST['idnum'];
?>
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

<?php
}
?>


