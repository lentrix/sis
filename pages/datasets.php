<?php include("library/lister.php"); ?>
<h1>Data Sets</h1>

<?php
if(isset($_POST['submit_new_sem'])) {
    echo "<div class='error'>";
    mysql_query("INSERT INTO sems (sem, sem_num) VALUES ('{$_POST['new_sem']}',{$_POST['sem_num']})");
    if(mysql_error()) echo mysql_error();
    else echo "Semester successfully added.";
    echo "</div>";
}
?>

<div style="margin-top: 30px; background: #fff; min-height: 50px;">
	<div class="div_title">List of Semesters</div>
	<span style="margin-top: 20px; display: block; padding-bottom: 20px;">
		<form method="post"  action="" style="display: block">
			New Semester: <input type="text" name="new_sem">
			<select name="sem_num">
				<option value="1">First Sem</option>
				<option value="2">Second Sem</option>
				<option value="3">Summer</option>
			</select>
			&nbsp;&nbsp; <input type="submit" name="submit_new_sem" value="Add">
		</form>
		<?php CreateList("sem_code","sem_code","sem","SELECT sem_code, sem FROM sems ORDER BY sem_code DESC","","height: 100px; margin-left:50px;",1); ?>
	</span>
</div>


<div style="margin-top: 30px; background: #fff; min-height: 50px;">

	<div class="div_title">List of Courses</div>
	<span style="margin-top: 20px; display: block; padding-bottom: 20px;">
<?php
    if(isset($_POST['submit_delete_course'])) {
        mysql_query("DELETE FROM courses WHERE cr_num={$_POST['cr_num']}");
        if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
        else echo "<div class='error'>Course deleted.</div>";
    }
?>	
		<form method="post" action="">
			Course: <input type="text" name="new_course" id="new_course"> 
			    Accronym: <input type="text" name="new_course_accronym" maxlength="10" size="10">
			<input type="submit" name="submit_new_course" value="Add">
		</form>
		<form method="post" action="">
		<?php CreateList("cr_num","cr_num","course1",
			"SELECT cr_num, CONCAT(course,'(',cr_acrnm,')') AS 'course1' FROM courses ORDER BY course",
			"","height: 150px; margin-left: 50px;",1);?>
		<br />
		<input type="submit" value="Delete Selected" name="submit_delete_course"
		    style="margin-left:50px; margin-top: 10px;" />		
		</form>
	</span>
</div>

<div style="margin-top: 30px; background: #fff; min-height: 50px;">
	<div class="div_title">List of Colleges</div>
	<span style="margin-top: 20px; display: block; padding-bottom: 20px;">
		<form method="post" action="">
			New College: <input type="text" name="new_college"> 
			Accronym:<input type="text" name="new_college_accronym" maxlength=10 size=10>
			<input type="submit" name="submit_new_college" value="Add">
		</form>
		<?php CreateList("clg_no","clg_no","coll",
			"SELECT clg_no, CONCAT(college,'(',acrnm,')') AS 'coll' FROM colleges ORDER BY college",
			"","height: 150px; margin-left: 50px;",1);?>
	</span>
</div>
