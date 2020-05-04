<?php
if (isset($_POST['submit_change_status'])) {
	mysqli_query($db, "UPDATE class SET grd_sub=0, sub_dt=NULL WHERE class_code={$_REQUEST['class_code']}");
}
if (isset($_POST['submit_reopen_all'])) {
	mysqli_query($db, "UPDATE class SET grd_sub=0 WHERE sem_code={$_SESSION['sem_code']}");
}
if (isset($_POST['submit_close_all'])) {
	mysqli_query($db, "UPDATE class SET grd_sub=1 WHERE sem_code={$_SESSION['sem_code']}");
}

?>

<h1>Grade Sheets</h1>

<table border="1">
	<tr>
		<td class="thead" width="50">#</td>
		<td class="thead" width="90">Class Code:</td>
		<td class="thead" width="230">Description:</td>
		<td class="thead" width="170">Teacher:</td>
		<td class="thead" width="90">Submitted:</td>
		<td class="thead" width="80">Action:</td>
	</tr>
	<?php
	$grds = mysqli_query($db, "SELECT class_code, descript, CONCAT(lname,', ',fname,' ',mi) as 'fullname', sub_dt FROM class, subjects, teacher
		WHERE class.sub_code=subjects.sub_code
		AND class.tch_num=teacher.tch_num
		AND sem_code={$_SESSION['sem_code']}
		AND grd_sub=1
		ORDER BY descript");
	$n = 1;
	while ($gr = mysqli_fetch_assoc($grds)) {
		echo "<tr><td class='tcel'>" . ($n++) . "</td>";
		echo "<td class='tcel'><a href='index.php?page=view_gradesheet&class_code={$gr['class_code']}'
			style='font-size: 9pt; text-decoration:none;font-weight:bold;'>{$gr['class_code']}</td>";
		echo "<td class='tcel'>{$gr['descript']}</td>";
		echo "<td class='tcel'>{$gr['fullname']}</td>";
		echo "<td class='tcel'>" . date('M-d-Y', strtotime($gr['sub_dt'])) . "</td>";
		echo "<td class='tcel' align='center'>";
		echo "<form method='post' action='' style='display:inline'>
	      <input type='hidden' name='class_code' value='{$gr['class_code']}' />
		  <input type='submit' name='submit_change_status' value='Re-open' title='Open this gradesheet for resubmission.'
		  		style='height:23px; font-size:9pt; padding: 0px;'/>
		  </form>";
		echo "</td>";
	}
	?>
</table>
<form method="post" action="">
	<input type="submit" name="submit_reopen_all" value="Re Open All" />
	<input type="submit" name="submit_close_all" value="Close All" />
</form>