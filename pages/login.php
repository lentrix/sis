<div id="login_wrapper">
	<form name="form1" method="post" action="" id="login_form">
		<table width="400" border="0" cellpadding="10">
			<tr>
				<td width="101">User Name:</td>
				<td width="253"><input type="text" name="username" id="username"></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="login" id="login" value="Login"></td>
			</tr>
		</table>
	</form>

	<?php
	if (isset($_POST['login'])) {
		include("config/dbc.php");
		$user = $_REQUEST['username'];
		$pwrd = $_REQUEST['password'];
		$lg = mysqli_query($db, "SELECT * FROM users
			WHERE user='$user' AND sys_pass=PASSWORD('$pwrd')") or die(mysqli_error($db));
		if (mysqli_num_rows($lg) == 1) {
			$lgr = mysqli_fetch_assoc($lg);
			$_SESSION['user'] = $lgr['user'];
			$_SESSION['fullname'] = $lgr['fullname'];
			$_SESSION['clg_no'] = $lgr['clg_no'];
			$_SESSION['tch_num'] = $lgr['tch_num'];
			$sem = mysqli_query($db, "SELECT MAX(sem_code) FROM sems");
			$semr = mysqli_fetch_row($sem);
			$_SESSION['sem_code'] = $semr[0];

			header("location:index.php?page=home");
		} else {
			echo "<div class='error1'>Invalid User Name or Password.</div>";
		}
	}
	?>
</div>