<?php include("library/lister.php"); ?>
<?php include("library/checker.php"); ?>

<h1>User Management</h1>
<?php
if(isset($_POST['submit_new_user'])) {
	$check = checkForBlank($_REQUEST);
	if($check) echo "<div class='error'>One or more fields are left blank. E.G. $check</div>";
	else {
		$usr = mysql_query("SELECT user FROM users WHERE user='{$_REQUEST['user']}'");
		if(mysql_num_rows($usr)) {
			mysql_query("UPDATE users SET 
						fullname='{$_REQUEST['fullname']}',
						tch_num={$_REQUEST['tch_num']},
						clg_no={$_REQUEST['clg_no']},
						sys_pass=PASSWORD('{$_REQUEST['sys_pass']}')
						WHERE user='{$_REQUEST['user']}'");
			if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div><br />";
			else echo "<div class='error1'>The new users has been added successfully!</div><br />";
		}else{
			mysql_query("INSERT INTO users (user, fullname, tch_num, clg_no, sys_pass)
					VALUES ('{$_REQUEST['user']}', '{$_REQUEST['fullname']}', {$_REQUEST['tch_num']},
					{$_REQUEST['clg_no']},PASSWORD('{$_REQUEST['sys_pass']}'))");
			if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div><br />";
			else echo "<div class='error1'>The new users has been added successfully!</div><br />";
		}
	}
}else{
	if(isset($_REQUEST['user'])) {
		$usr = mysql_query("SELECT * FROM users WHERE user='{$_REQUEST['user']}'");
		if(mysql_num_rows($usr)) {
			$usrow = mysql_fetch_assoc($usr);
			$_REQUEST['fullname'] = $usrow['fullname'];
			$_REQUEST['tch_num'] = $usrow['tch_num'];
			$_REQUEST['clg_no'] = $usrow['clg_no'];
		}
	}
}

if(isset($_POST['submit_delete_user'])) {
	echo "<div style='background: yellow'><form method='post' action=''>
			<input type='hidden' name='user' value='{$_REQUEST['user']}'>
			You are about to delete this user {$_REQUEST['user']}
			<input type='submit' name='submit_confirm_delete_user' value='Confirm Delete' />
			<input type='submit' name='submit_cancel_delete' value='Cancel' />
			</form></div>";
}

if(isset($_POST['submit_confirm_delete_user'])) {
	mysql_query("DELETE FROM users WHERE user='{$_REQUEST['user']}'");
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
	else echo "<div class='error'>The user has been deleted.</div>";
}
?>
<br />
<div style="">
	<div class="div_title" style="cursor:pointer" onclick="
    obj = document.getElementById('new_user');
    if(obj.style.display == 'block') obj.style.display='none';
    else obj.style.display='block';
    ">User Details</div>
    <div id="new_user" style="display: none;">
    	<form method="post" action="" style="clear:both">
        <table>
        	<tr><td>User Name:</td><td><input type="text" name="user" <?php check("user");?> /></td></tr>
        	<tr><td>Full Name:</td><td><input type="text" name="fullname" <?php check("fullname");?> /></td></tr>
            <tr><td>Teacher:</td><td><?php CreateList("tch_num","tch_num","name",
				"SELECT CONCAT(lname,', ',fname,' ',mi) as 'name', tch_num FROM teacher ORDER BY lname, fname",""); ?></td></tr>
            <tr><td>College:</td><td><?php CreateList("clg_no","clg_no","college",
				"SELECT clg_no, college FROM colleges ORDER BY college",""); ?></td></tr>
            <tr><td>Password:</td><td><input type="password" name="sys_pass" <?php check("sys_pass"); ?> /></td></tr>
        </table>
        <input type="submit" name="submit_new_user" value="Submit" 
        		style="float: right;" />
        <span style="display:block; width:100%">&nbsp;</span>
        </form>
    </div>
</div>
<br />

<?php
if(isset($_POST['submit_add_module'])) {
	mysql_query("INSERT INTO modassgn (user,modlno) VALUES ('{$_REQUEST['user']}',{$_REQUEST['modlno']})");
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
}

if(isset($_POST['submit_remove_module'])) {
	mysql_query("DELETE FROM modassgn WHERE user='{$_REQUEST['user']}' AND modlno={$_REQUEST['modlno']}");
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
}
?>

<div>
	<div class="div_title">Manage Users</div>
    <div>
    	<br />
    	<form action="" method="post" style="float:left;border: 1px solid #777;padding: 10px;">
        	<?php CreateList("user","user","fullname","SELECT * FROM users ORDER BY fullname","","height: 180px;width:200px",1); ?>
            <br />
            <input type="submit" name="submit_show_user_modules" value="Show" />
            <input type="submit" name="submit_delete_user" value="Delete" />
        </form>
        
 <?php if(isset($_REQUEST['user']) && !empty($_REQUEST['user'])) {  ?>  
 		
        <form id="module_assignments" action="" method="post" 
        		style="display: inline-block;width:200px; border:1px solid #777;padding: 10px;margin-left: 10px;">
            <input type="hidden" name="user" value="<?php echo $_REQUEST['user']; ?>" />
            <span style="display:block;">Available Modules: </span>
        	<?php CreateList("modlno","modlno","module",
				"SELECT * FROM modules WHERE modlno NOT IN (SELECT modlno FROM modassgn WHERE user='{$_REQUEST['user']}')",
				"","height:150px;width:150px;float:left",1); ?>
            <input type="submit" name="submit_add_module" value="Add>>" style="float:left;clear:both;width:150px;">
        </form>
        
        <form id="assigned_modules" action="" method="post" 
        		style="display: inline-block;width:200px; border:1px solid #777;padding: 10px;margin-left: 10px;">
            <input type="hidden" name="user" value="<?php echo $_REQUEST['user']; ?>" />
            <span style="display:block;">Assigned Modules: </span>
        	<?php CreateList("modlno","modlno","module",
				"SELECT * FROM modules WHERE modlno IN (SELECT modlno FROM modassgn WHERE user='{$_REQUEST['user']}')",
				"","height:150px;width:150px;float:left",1); ?>
            <input type="submit" name="submit_remove_module" value="<<Remove" style="float:left;clear:both;width:150px;">
        </form>
<?php } ?>       
		<span style="width: 100%; display:block;border: 1px solid #888; clear:both">&nbsp;</span>
    </div>
</div>