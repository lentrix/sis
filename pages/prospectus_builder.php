<?php include("library/lister.php"); ?>
<?php include("library/checker.php"); ?>

<h1>Prospectus Builder</h1>
<?php
if(isset($_POST['submit_create_prospectus'])) {
	if(isset($_REQUEST['prp_code'])) {
		mysql_query("UPDATE prospectus SET course={$_POST['cr_num']}, year={$_POST['year']}, clg_no={$_SESSION['clg_no']}
						WHERE prp_code={$_REQUEST['prp_code']}");
		if(mysql_error()) echo "<div style='float: none' class='error'>" . mysql_error() . "</div>";
		else echo "<div class='error1' style='float:none'>Prospectus Updated.</div>";
	}else {
		mysql_query("INSERT INTO prospectus (course, year, clg_no) 
					VALUES ({$_POST['cr_num']}, {$_POST['year']}, {$_SESSION['clg_no']})");

		$lid=mysql_query("SELECT LAST_INSERT_ID() AS lid");
		$lidr=mysql_fetch_row($lid);
		$_REQUEST['prp_code'] = $lidr[0];
		
		if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
		else echo "<div class='error1' style='float:none'>Prospectus Created.</div>";
	}
}
if(isset($_POST['prp_code'])) {
	$pros = mysql_query("SELECT course as 'cr_num' , year FROM prospectus WHERE prp_code={$_POST['prp_code']}");
	$prow = mysql_fetch_assoc($pros);
	$_REQUEST['cr_num'] = $prow['cr_num'];
	$_REQUEST['year'] = $prow['year'];	
}
if(isset($_POST['submit_insert_subject'])) {
	$check = checkForBlank($_REQUEST);
	if($check) echo "<div class='error' style='float:none'>One or more fields are left blank. E. G. $check</div>";
	else {
		$semyr = $_REQUEST['pyear'] . $_REQUEST['sem'];
		mysql_query("INSERT INTO pros_subj (prp_code, sub_code, semyr, c_units) 
					VALUES ({$_REQUEST['prp_code']}, {$_REQUEST['sub_code']}, $semyr,{$_REQUEST['c_units']})");
		if(mysql_error()) echo "<div class='error' style='float:none'>" . mysql_error() . "</div>";
		else echo "<div class='error1' style='float:none'>Subject Inserted to the prospectus</div>";
	}
}
if(isset($_POST['submit_remove_pros_subj'])) {
	mysql_query("DELETE FROM pros_subj WHERE prp_code={$_REQUEST['prp_code']} AND sub_code={$_REQUEST['sub_code']}");
	if(mysql_error()) echo "<div class='error' style='float:none'>" . mysql_error() . "</div>";
	else echo "<div class='error1' style='float:none'>Subject is removed from the prospectus</div>";
}
?>
<div>
	<form method="post" action="">
    	Course: <?php CreateList("cr_num","cr_num","course","SELECT cr_num, course 
						FROM courses WHERE clg_no={$_SESSION['clg_no']} ORDER BY course","");?>
        Year: <input type="text" name="year" maxlength="4" size="4" <?php check("year"); ?>/>
<?php 
	if(isset($_REQUEST['prp_code'])) echo "<input type='hidden' value={$_REQUEST['prp_code']} name='prp_code' />";
?>
        <input type="submit" name="submit_create_prospectus" value="Create/Update" />
    </form>
</div>

<?php
if(isset($_POST['prp_code'])) {
?>
<div>
	<div>
    	<div class="div_title">Insert Subjects</div>
        <form action="" method="post" style="clear:both">
        	Subject: <?php CreateList("sub_code","sub_code","descript","SELECT sub_code, descript FROM subjects ORDER BY descript",
									  "width: 400px;");?><br />
            Year:
           	<select name="pyear">
            	<option <?php if(isset($_REQUEST['pyear']) && $_REQUEST['pyear']=='1') echo "selected";?>>1</option>
                <option <?php if(isset($_REQUEST['pyear']) && $_REQUEST['pyear']=='2') echo "selected";?>>2</option>
                <option <?php if(isset($_REQUEST['pyear']) && $_REQUEST['pyear']=='3') echo "selected";?>>3</option>
                <option <?php if(isset($_REQUEST['pyear']) && $_REQUEST['pyear']=='4') echo "selected";?>>4</option>
                <option <?php if(isset($_REQUEST['pyear']) && $_REQUEST['pyear']=='5') echo "selected";?>>5</option>
            </select>
            Sem:
            <select name="sem">
            	<option value="1" <?php if(isset($_REQUEST['sem']) && $_REQUEST['sem']=='1') echo "selected";?>>First Semester</option>
                <option value="2" <?php if(isset($_REQUEST['sem']) && $_REQUEST['sem']=='2') echo "selected";?>>Second Semester</option>
                <option value="3" <?php if(isset($_REQUEST['sem']) && $_REQUEST['sem']=='3') echo "selected";?>>Summer</option>
            </select>
            Units: <input type="text" maxlength="2" size="2" name="c_units" <?php check("c_units"); ?> />
            <input type="hidden" value="<?php echo $_POST['prp_code']; ?>" name="prp_code" />
            <input type="submit" name="submit_insert_subject" value="Insert" />
        </form>
    </div>
</div>

<iframe name="printFrame" id="printFrame" style="width:0px;height:0px;float:left; margin-left: -9999px"></iframe>

<div style="background:#fff;" id="prospectus">
	<input type="image" src="images/printButton.png" class="noprint"
	onClick="printThisDiv('printFrame','prospectus');"
	style="float: right; margin-top: 0px; margin-right: 5px;display:screen;">
<?php
	include("library/prospectus_generator.php");
	createProspectus($_REQUEST['prp_code']);
?>
</div>

<?php
}
?>

