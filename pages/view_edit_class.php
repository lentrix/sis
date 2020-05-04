<?php include("library/checker.php"); ?>
<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>
<?php
if (!isset($_POST['submit_update_class']) && isset($_GET['class_code'])) {
	$cls = mysqli_query($db, "SELECT subjects.sub_code, class_code, cunits, punits, class.tch_num,
		CONCAT(fname,' ',lname) AS 'teacher', class.limit, class.clg_no FROM class, teacher, subjects 
		WHERE class.tch_num=teacher.tch_num AND class.sub_code=subjects.sub_code 
		AND class_code={$_GET['class_code']}");
	$clrow = mysqli_fetch_assoc($cls);
	$tm = mysqli_query($db, "SELECT * FROM class_time WHERE class_code={$_GET['class_code']}");

	foreach ($clrow as $n => $v) $_REQUEST[$n] = $v;

	$tmr = mysqli_fetch_assoc($tm);
	$_REQUEST['start_time1'] = date('g:i', strtotime($tmr['t_start']));
	$_REQUEST['end_time1'] = date('g:i', strtotime($tmr['t_end']));
	$_REQUEST['day1'] = $tmr['day'];
	$_REQUEST['rm_no1'] = $tmr['rm_no'];
	$_REQUEST['classTimeSerial1'] = $tmr['serial'];

	if (mysqli_num_rows($tm) > 1) {
		$tmr = mysqli_fetch_assoc($tm);
		$_REQUEST['start_time2'] = date('g:i', strtotime($tmr['t_start']));
		$_REQUEST['end_time2'] = date('g:i', strtotime($tmr['t_end']));
		$_REQUEST['day2'] = $tmr['day'];
		$_REQUEST['rm_no2'] = $tmr['rm_no'];
		$_REQUEST['classTimeSerial2'] = $tmr['serial'];
	}
}
?>
<script type="text/javascript">
	function showAddTime() {
		linkObj = document.getElementById('addtimelink');
		if (linkObj.innerHTML == "Add Time") {
			sourceObj = document.getElementById('time2_content');
			targetObj = document.getElementById('newtime');

			targetObj.innerHTML = sourceObj.innerHTML;
			linkObj.innerHTML = "Remove Second Time";
		} else {
			targetObj.innerHTML = "";
			linkObj.innerHTML = "Add Time";
		}
	}
</script>
<div id="time2_content" style="display:none">
	<?php showTimeInput("start_time2"); ?>
	&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp; <?php showTimeInput("end_time2"); ?>
	Day: <input type="text" name="day2" maxlength="3" size="3" <?php check("day2"); ?> />

	Room: <?php CreateList("rm_no2", "rm_no", "room", "SELECT * FROM rooms ORDER BY room", ""); ?>
</div>

<h1>View / Edit Class</h1>

<?php
if (isset($_POST['submit_confirm_delete_class'])) {
	mysqli_query($db, "DELETE FROM class WHERE class_code={$_GET['class_code']}");
	if (mysqli_error($db)) {
		if (mysqli_errno($db) == 1451) echo "<div class='error'>Sorry! You cannot delete a class that is not empty.</div>";
		else echo "<div class='error'>MySQL Error! Code: " . mysqli_errno($db) . "</div>";
	} else {
		echo "<div class='error'>The class has been deleted. 
			<a href='index.php?page=classes&pagenum=1'>Click Here</a> to go back to the list of classes.</div>";
	}
}
if (isset($_POST['submit_update_class'])) {
	$check = checkForBlank($_REQUEST);
	if ($check) {
		echo "<div class='error'>One or more items are left blank. E.G. $check</div>";
	} else {
		foreach ($_REQUEST as $n => $v) $$n = $v;

		$checkRT = checkRoomTimeConflict($_REQUEST['start_time1'], $_REQUEST['end_time1'], $_REQUEST['rm_no1'], $_REQUEST['day1'], $class_code);

		if (!$checkRT && isset($_REQUEST['start_time2'], $_REQUEST['end_time2'], $_REQUEST['day2']))
			$checkRT = checkRoomTimeConflict($_REQUEST['start_time2'], $_REQUEST['end_time2'], $_REQUEST['rm_no2'], $_REQUEST['day2'], $class_code);

		if ($checkRT) {
			echo "<div class='error'>The class you are trying to add is in conflict with the following class: <br/ >";
			echo "<ul type='disc'>\n";
			while ($checkRTrow = mysqli_fetch_assoc($checkRT)) {
				echo "<li>" . $checkRTrow['descript'] . " (" . $checkRTrow['t_start'] .
					" - " . $checkRTrow['t_end'] . " " . $checkRTrow['day'] . ") Room: " . $checkRTrow['room'] . "</li>\n";
			}
			echo "</ul></div>";
		}

		$checkTT = checkTeacherTimeConflict($_REQUEST['start_time1'], $_REQUEST['end_time1'], $tch_num, $_REQUEST['day1'], $class_code);
		if ($checkTT && isset($_REQUEST['start_time2'], $_REQUEST['end_time2'], $_REQUEST['day2']))
			$checkTT = checkTeacherTimeConflict($_REQUEST['start_time2'], $_REQUEST['end_time2'], $tch_num, $_REQUEST['day2'], $class_code);

		if ($checkTT) {
			$checkTTrow = mysqli_fetch_assoc($checkTT);
			echo "<div class='error'>Teacher {$checkTTrow['teacher']} is already assigned in the following class:";
			echo "<ul type='disc'>\n";
			echo "<li>" . $checkTTrow['descript'] . " (" . $checkTTrow['t_start'] .
				" - " . $checkTTrow['t_end'] . " " . $checkTTrow['day'] . ")</li>";
			echo "</ul></div>";
		}
		if (!$checkRT && !$checkTT) {
			mysqli_query($db, "UPDATE class SET sub_code=$sub_code, tch_num=$tch_num, clg_no={$_SESSION['clg_no']}, 
			    cunits=$cunits, punits=$punits, sem_code={$_SESSION['sem_code']}, class.limit=$limit 
				WHERE class_code = {$_GET['class_code']}");

			mysqli_query($db, "UPDATE class_time SET 
			        t_start='{$_REQUEST['start_time1']}', 
			        t_end='{$_REQUEST['end_time1']}',
			        day='{$_REQUEST['day1']}',
			        rm_no={$_REQUEST['rm_no1']}
			        WHERE serial={$_REQUEST['classTimeSerial1']}");
			if (isset($_REQUEST['start_time2'], $_REQUEST['end_time2'], $_REQUEST['day2'])) {

				if (!isset($_REQUEST['classTimeSerial2'])) {
					$ssql = "INSERT INTO class_time (class_code, t_start, t_end, day, rm_no)
			                VALUES (
			                   {$_GET['class_code']}, '{$_REQUEST['start_time2']}',
			                   '{$_REQUEST['end_time2']}',UPPER('{$_REQUEST['day2']}'),{$_REQUEST['rm_no2']}
			                )";
				} else {
					$ssql = "UPDATE class_time SET 
			            t_start='{$_REQUEST['start_time2']}', 
			            t_end='{$_REQUEST['end_time2']}',
			            day='{$_REQUEST['day2']}',
			            rm_no={$_REQUEST['rm_no2']}
			            WHERE serial = {$_REQUEST['classTimeSerial2']}";
				}
				mysqli_query($db, $ssql);
			}
			echo "<div class='error'>";
			if (mysqli_error($db)) echo "Update: " . $ssql . "<br />" . mysqli_error($db);
			else echo "The class has been updated. ";
			echo "</div>";
		} else {
			echo "<div class='error'>The class was not successfully updated due to conflicts.</div>";
		}
	}
}
?>

<center>
	<div style="border: 1px solid #777; padding: 10px; display: inline-block;background: #ffc;" id="new_class">

		<?php
		$mdl = mysqli_query($db, "SELECT * FROM modassgn WHERE modlno=1 AND user='{$_SESSION['user']}'");

		if ($_REQUEST['clg_no'] == $_SESSION['clg_no'] || mysqli_num_rows($mdl) == 1) {
		?>

			<form id="form1" name="form1" method="post" action="">
				<table>
					<tr>
						<td>Subject:</td>
						<td>
							<?php CreateList(
								"sub_code",
								"sub_code",
								"descript",
								"SELECT sub_code, descript FROM subjects ORDER BY descript",
								"font-size:9pt;"
							); ?>
						</td>
					</tr>
					<tr>
						<td>Time:</td>
						<td><?php showTimeInput("start_time1"); ?>
							&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp; <?php showTimeInput("end_time1"); ?>
							Day: <input type="text" name="day1" maxlength="5" size="3" <?php check("day1"); ?> />
							<input type="hidden" name="classTimeSerial1" value="<?php echo $_REQUEST['classTimeSerial1']; ?>" />
							Room: <?php CreateList("rm_no1", "rm_no", "room", "SELECT * FROM rooms ORDER BY room", ""); ?>
							<?php if (isset($_REQUEST['start_time2'], $_REQUEST['end_time2'], $_REQUEST['day2'])) { ?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php showTimeInput("start_time2"); ?>
							&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp; <?php showTimeInput("end_time2"); ?>
							Day: <input type="text" name="day2" maxlength="3" size="3" <?php check("day2"); ?> />
							<input type="hidden" name="classTimeSerial2" value="<?php echo $_REQUEST['classTimeSerial2']; ?>" />
							Room: <?php CreateList("rm_no2", "rm_no", "room", "SELECT * FROM rooms ORDER BY room", ""); ?>
						</td>
					</tr>
				<?php } else { ?>
					<a style="cursor:pointer;text-decoration:underline;color:blue" id="addtimelink" onclick="showAddTime()">Add Time</a>
					</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td id="newtime"></td>
					</tr>

				<?php } ?>
				<tr>
					<td>Credit Units: </td>
					<td><input type="text" name="cunits" size="4" maxlength="4" <?php check("cunits"); ?>>
						Pay Units: <input type="text" name="punits" size="4" maxlength="4" <?php check("punits"); ?>></td>
				</tr>
				<tr>
					<td>Instructor:</td>
					<td>
						<?php CreateList(
							"tch_num",
							"tch_num",
							"name",
							"SELECT tch_num, CONCAT(lname,', ',fname,' ',mi) AS 'name' FROM teacher ORDER BY lname, fname",
							""
						); ?>
						Limit: <input type="text" name="limit" maxlength="3" size="3" <?php check("limit"); ?>>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="hidden" name="clg_no" value="<?php echo $_REQUEST['clg_no']; ?>" />
						<input type="submit" name="submit_delete_class" value="Delete" />
						<input type="submit" name="submit_update_class" value="Submit" />
					</td>
				</tr>
				</table>

				<?php
				if (isset($_POST['submit_delete_class'])) {
					echo "<div style=\"border: 2px solid #f33; margin-top: 10px;\">You are are about to delete this class.
	        Please understand that students may have already enrolled to this class. If you continue
	        deleting, it will be removed from the study load of the students.<br/>";
					echo "<input type='submit' value='Confirm Delete' name='submit_confirm_delete_class'>";
					echo "<input type='submit' value='Cancel Delete' name='cancel'>";
					echo "</div>";
				}
				?>
			</form>

		<?php
		} else { //if(clg_no...)
			echo "You cannot change this class. This class was created by " . getCollegeAccronym($_REQUEST['clg_no']);
		}
		?>

	</div>
</center>


<div id="class_list">
	<center>
		<input type="image" src="images/printButton.png" class="noprint" onClick="printThisDiv('printFrame','class_list');" style="float: right; margin-top: 5px; margin-right: 5px;display:screen;">
		<iframe name="printFrame" id="printFrame" style="width:0px;height:0px;float:left; margin-left: -9999px"></iframe>

		<h2 style="width: 500px;">Class List</h2>
		<span style="width: 500px;display:block;">
			<?php
			echo getClassNameAndTime($_GET['class_code'], true);
			?>
		</span>
		<?php include("library/classlist.php");
		showClassList($_REQUEST['class_code']); ?>
	</center>
</div>