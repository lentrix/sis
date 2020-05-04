<?php include("library/checker.php"); ?>

<h1>View / Edit Subject</h1>
<?php
if (isset($_POST['submit_update_subject'])) {
	$check = checkForBlank($_REQUEST);
	if ($check) echo "<div class='error'>One or more fields is left blank. E.g. $check</div>";
	else {
		mysqli_query($db, "UPDATE subjects SET 
		name='{$_REQUEST['name']}', 
		descript='{$_REQUEST['descript']}',
		acad={$_REQUEST['acad']} WHERE sub_code={$_GET['sub_code']}");
		if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
		else echo "<div class='error'>The subject has been updated.</div>";
	}
} else {
	$subv = mysqli_query($db, "SELECT * FROM subjects WHERE sub_code={$_GET['sub_code']}");
	$subr = mysqli_fetch_assoc($subv);
	foreach ($subr as $n => $v) {
		$_REQUEST[$n] = $v;
	}
}
if (isset($_POST['submit_confirm_delete_subject'])) {
	mysqli_query($db, "DELETE FROM subjects WHERE sub_code={$_GET['sub_code']}");
	if (mysqli_error($db)) echo "<div class='error'>Unable to delete. 
		A class or classes may have already been created based on this subject.<br/>
		Students may have already been enrolled in these classes.<br/>
		Please contact the administrator for more information.<br/>
		Technical Description:<br/>" . mysqli_error($db) . "</div>";
	else echo "<div class='error'>The subject has been deleted. 
		<a href='index.php?page=subjects&pagnum=1'>Click here</a> 
		to go back to the subjects list.</div>";
}
?>
<div>
	<form action="" method="post">
		<table width="600" border="0" cellpadding="2">
			<tr>
				<th width="161" scope="row">Name</th>
				<td width="425"><input type="text" name="name" id="name" <?php check('name'); ?>> </td>
			</tr>
			<tr>
				<th scope="row">Description:</th>
				<td><input name="descript" type="text" id="descript" size="40" <?php check('descript'); ?>></td>
			</tr>
			<tr>
				<th scope="row">Academic:</th>
				<td><select name="acad" id="acad">
						<option value="1" <?php if (isset($_REQUEST['acad']) && $_REQUEST['acad'] == 1) echo " selected"; ?>>yes</option>
						<option value="00" <?php if (isset($_REQUEST['acad']) && $_REQUEST['acad'] == 0) echo " selected"; ?>>no</option>
					</select>
					<input type="submit" value="Save Changes" name="submit_update_subject" />
					<input type="submit" value="Delete Subject" name="submit_delete_subject" />
				</td>
			</tr>
		</table>

		<?php
		if (isset($_POST['submit_delete_subject'])) {
			echo "<div style=\"border: 3px solid red; display: block; width: 250px; margin-left: auto; margin-right: auto;margin-top: 20px;\">";
			echo "<span>You are about to delete this subject</span><br />";
			echo "<input type='submit' value='Confirm Delete' name='submit_confirm_delete_subject'>";
			echo "<input type='submit' value='Cancel Delete'>";
			echo "</div>";
		}
		?>

	</form>
</div>