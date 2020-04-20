<?php 
include("library/lister.php");
?>

<h1>Teachers</h1>

<?php
if(isset($_POST['submit_add_teacher'])){
	mysql_query("INSERT INTO teacher (lname,fname,mi,clg_no)
		VALUES ('{$_REQUEST['lname']}','{$_REQUEST['fname']}','{$_REQUEST['mi']}',{$_SESSION['clg_no']})");
	
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
	else echo "<div class='error'>The teacher has been added successfully!</div>";
}
?>

<?php
if(isset($_POST['submit_delete_teacher'])){
	echo "<div>You are about to delete a teacher. Please confirm<br/>";
	echo "<form action='' method='post'>";
	echo "<input type='hidden' name='tch_num' value='{$_REQUEST['tch_num']}'>";
	echo "<input type='submit' name='confirm_delete_teacher' value='Confirm Delete'>";
	echo "<input type='button' value='Cancel' onClick=\"document.location='index.php?page=teachers'\">";
	echo "</form>";
}
?>

<?php 
if(isset($_POST['confirm_delete_teacher'])){
	mysql_query("DELETE FROM teacher WHERE tch_num={$_REQUEST['tch_num']}");
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
	else echo "<div class='error'>The teacher record has been deleted.</div>";
}
?>

<?php
if(isset($_POST['submit_view_teacher_classes'])) {
	//header("location:index.php?page=view_teacher_classes&tch_num={$_REQUEST['tch_num']}");
	echo "<script type='text/javascript'>
	    document.location = 'index.php?page=view_teacher_classes&tch_num={$_REQUEST['tch_num']}';
	    </script>";
}
?>

<div id="teacher_list" style="height: 180px;">
	<div style="float: left; height: 160px;">
	<form method="post" action="">
	<?php CreateList("tch_num","tch_num","name",
			"SELECT tch_num, CONCAT(lname,', ',fname, ' ',mi) as 'name' 
			FROM teacher WHERE clg_no={$_SESSION['clg_no']}","","width: 200px; height: 130px;",1);?>
	<br />
	<input type="submit" value="Delete" name="submit_delete_teacher">
    <input type="submit" value="View Classes" name="submit_view_teacher_classes" />
	</form>
	</div>
	<div style="margin-left: 20px; float: left;background:#fff; height: 130px;">
		<form method="post" action="">
			<table>
			<tr><td>Last Name:</td><td><input type="text" name="lname"></td></tr>
			<tr><td>First Name:</td><td><input type="text" name="fname"></td></tr>
			<tr><td>M.I.:</td><td><input type="text" name="mi" maxlength="2" size="1">
			<tr>
            	<td colspan="2" align="right">
            		<input type="submit" name="submit_add_teacher" value="Add Teacher">
			</td></tr>
			</table>
		</form>
	</div>
</div>
