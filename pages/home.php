<?php include("library/lister.php"); ?>
<?php include("library/checker.php"); ?>

<h1>Home Page</h1>

<?php
if (isset($_POST['forward'])) {
	//$class_codes_array = explode($_POST['class_codes']);
	mysqli_query($db, "UPDATE class SET grd_sub=1 WHERE class_code IN ({$_POST['class_codes']} 0)");
}
?>


<?php
if (isset($_POST['submit_delete_message'])) {
	mysqli_query($db, "DELETE FROM messages WHERE idmessages={$_REQUEST['idmessages']}");
	if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
	else echo "<div class='error'>The message has been deleted.</div>";
}
?>

<div style="background: #fff; margin-top: 30px; min-height: 100px;">
	<?php $mdl = mysqli_query($db, "SELECT * FROM modassgn WHERE modlno=3 AND user='{$_SESSION['user']}'"); ?>
	<?php if (mysqli_num_rows($mdl)) : ?>

		<div class="div_title">Grades Submitted</div>
		<div style="font-size: 9pt; color: #777;">
			<br />
			<span style="display:block; max-height:300px; overflow: auto;">

				<?php
				$grds = mysqli_query($db, "SELECT c.class_code, sb.name, sb.descript, t.lname, t.fname, c.sub_dt 
					FROM class c, subjects sb, teacher t
					WHERE c.sub_code = sb.sub_code 
					AND c.tch_num = t.tch_num
					AND c.sem_code = {$_SESSION['sem_code']}
					AND c.clg_no={$_SESSION['clg_no']}
					AND c.grd_sub=0 AND sub_dt IS NOT NULL");
				?>
				<?php if (mysqli_num_rows($grds) > 0) : ?>
					<?php $classCodes = ""; ?>
					<table>
						<tr>
							<th class="thead">Class Code</th>
							<th class="thead">Name</th>
							<th class="thead">Description</th>
							<th class="thead">Teacher</th>
							<th class="thead">Date Submitted</th>
						</tr>
						<?php while ($row = mysqli_fetch_object($grds)) : ?>
							<?php $classCodes .= $row->class_code . "," ?>
							<tr>
								<td class="tcel"><a href="index.php?page=view_gradesheet&class_code=<?php echo $row->class_code; ?>"><?php echo $row->class_code; ?></a></td>
								<td class="tcel"><?php echo $row->name; ?></td>
								<td class="tcel"><?php echo $row->descript; ?></td>
								<td class="tcel"><?php echo $row->lname . ", " . $row->fname; ?></td>
								<td class="tcel"><?php echo $row->sub_dt; ?></td>
							</tr>
						<?php endwhile; ?>
					</table>
					<form method="post" action="">
						<input type="hidden" name="class_codes" value="<?php echo $classCodes; ?>" />
						<input type="submit" value="Forward Gradesheet to Registrar>>" name="forward" />
					</form>
				<?php else : ?>
					<p>There are no pending grades.</p>
				<?php endif; ?>

			</span>
		</div>
	<?php endif; ?>
</div>

<div style="background: #fff; margin-top: 30px; min-height: 100px;">


	<div class="div_title">My Messages</div>
	<div style="font-size: 9pt; color: #777;">
		<br />
		<span style="display:block; max-height:300px; overflow: auto;">
			<?php
			$msgs = mysqli_query($db, "SELECT * FROM messages WHERE receiver='{$_SESSION['user']}' ORDER BY date_sent DESC");
			if (mysqli_num_rows($msgs) > 0) {
				while ($mgrow = mysqli_fetch_assoc($msgs)) {
					echo "<div style=\"background: #8aa\">From: {$mgrow['sender']} ";
					echo "<form method='post' action='' style=\"font-size: 8.5pt; color: #444; float: right;\">
				Date Sent: {$mgrow['date_sent']}
					<input type='hidden' name='idmessages' value='{$mgrow['idmessages']}'>
					<input type='submit' name='submit_delete_message' value='X' />
				</form>
				<br />";
					echo "Subject: {$mgrow['subject']} <br />";
					echo "<div style=\"background: #ff9\">" . $mgrow['message'] . "</div>";
					echo "</div>";
				}
			} else echo "<i>You have no message</i>";
			?>
		</span>
	</div>
</div>

<?php
if (isset($_POST['submit_message'])) {
	$check = checkForBlank($_REQUEST);
	if ($check) echo "<div class='error'>One or more required fields are left blank. e.g. $check</div>";
	else {
		$date_time = date('Y-m-d h:m:s');
		foreach ($_REQUEST['recipient'] as $recipient) {
			mysqli_query($db, "INSERT INTO messages (sender, receiver, subject, message, date_sent)
					VALUES ('{$_SESSION['user']}', '$recipient','{$_REQUEST['subject']}',
					'{$_REQUEST['message']}', '$date_time')");
			if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
			else echo "<div class='error'>The message has been sent to $recipient</div>";
		}
	}
}
if (isset($_POST['submit_new_password'])) {
	echo "<div class='error'>";
	if ($_POST['pwd1'] == $_POST['pwd2']) {
		$user = mysqli_query($db, "SELECT user FROM users WHERE user='{$_SESSION['user']}' AND sys_pass=PASSWORD('{$_POST['old']}')");
		if (mysqli_num_rows($user) == 1) {
			mysqli_query($db, "UPDATE users SET sys_pass=PASSWORD('{$_POST['pwd1']}') WHERE user='{$_SESSION['user']}'");
			if (mysqli_error($db)) echo mysqli_error($db);
			else  echo "Your password has been changed.";
		} else {
			echo "Invalid password.";
		}
	} else {
		echo "Your new passwords do not match.";
	}
	echo "</div>";
}
?>

<div style="background: #fff; margin-top: 30px;">
	<div class="div_title" style="cursor:pointer" onClick="
	        obj = document.getElementById('send_message');
	        if(obj.style.display=='none') obj.style.display='block';
	        else obj.style.display='none';
	    ">Send Message</div>
	<div style="font-size: 9pt;display:none" id="send_message">
		<br />
		<form method="post" action="">
			<table align="center">
				<tr>
					<td valign="middle">To:</td>
					<td><?php createList(
							"recipient[]",
							"user",
							"user",
							"SELECT user FROM users ORDER BY user",
							"",
							"width: 300px; height:70px;float:none",
							1
						); ?>
						<span style="float: right;font-size: 8pt; color: #999; font-style:italic;">
							Hint: Hold CTRL while clicking <br />to select two or more recipients
						</span>
					</td>
				</tr>
				<tr>
					<td valign="top">Subject:</td>
					<td><input type="text" name="subject" style="width: 500px;border:1px solid #777;font-size: 9pt; color: #444;" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<textarea name="message" style="width: 500px; font-family:Arial, Helvetica, sans-serif; 
                		font-size:9pt; color: #444; border:1px solid #777;height: 150px;"></textarea>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="right">
						<input type="submit" name="submit_message" value="S E N D" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<br />
<div style="background: #fff">
	<div class="div_title" style="cursor:pointer" onclick="
            obj = document.getElementById('change_password');
            if(obj.style.display=='none') obj.style.display='block';
            else obj.style.display='none';
        ">Change Password</div>
	<div id="change_password" style="display:none">
		<form method="post" action="" style="clear:both;">
			<table>
				<tr>
					<td>Old Password:</td>
					<td><input type="password" name="old" /></td>
				</tr>
				<tr>
					<td>New Password:</td>
					<td><input type="password" name="pwd1" /></td>
				</tr>
				<tr>
					<td>Retype New Password:</td>
					<td><input type="password" name="pwd2" /></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" name="submit_new_password" value="Change Password" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>