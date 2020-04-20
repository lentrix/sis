<h1>Student</h1>
<?php
include("library/checker.php");
if(isset($_POST['submit_new_student'])){
	$check = checkForBlank($_REQUEST);
	if($check){
		echo "<div class='error'>One or more fields are left empty. E.G. $check</div>";
	}else{
		foreach($_REQUEST as $nm=>$vl){
			$$nm=$vl;
		}
		mysql_query("INSERT INTO stud_info (idnum, idext, lname, fname, mi, addb, addt, addp, 
					gender, bdate, status, father, mother, foccup, moccup, addparents, remarks)
					VALUES ($idnum, '$idnum_text', '$lname', '$fname','$mi', '$bar','$town','$prov',
							'$gender','$year-$month-$day','$status','$father','$mother','$occupf',
							'$occupm','$addparents','$remarks')");
		if(mysql_error()) echo "<div class='error'>". mysql_error() . "</div>";
		else echo "<div class='error'>Student record was successfully save.</div>";
	}
}
?>
<div id="new_stud" style=" overflow: hidden;">
<span><strong><u>Student Information (New Student)</u></strong></span>
<input type="button" value=">>" onclick=
	"if(this.value=='>>') {
    	document.getElementById('stud_form').style.display='block';
        this.value='<<';
    }else {
    	document.getElementById('stud_form').style.display='none';
        this.value='>>';
    }" style="float: right"/>

<span style="display:none" id="stud_form">
<form action="" method="post">
  <table width="670" border="0" cellpadding="2">
    <tr>
      <td width="95">ID Number</td>
      <td width="205"><input name="idnum" type="text" id="idnum" size="6" maxlength="6" <?php check('idnum');?> />-
        <input name="idnum_text" type="text" id="idnum_text" size="4" maxlength="4" <?php check('idnum_text');?> /></td>
      <td width="126">Father's Name:</td>
      <td width="218"><input type="text" name="father" id="father" <?php check('father');?> /></td>
    </tr>
    <tr>
      <td>Last Name:</td>
      <td><input type="text" name="lname" id="lname" <?php check('lname');?> /></td>
      <td>&nbsp;&nbsp;&nbsp;Occupation:</td>
      <td><input type="text" name="occupf" id="occupf" <?php check('occupf');?> /></td>
    </tr>
    <tr>
      <td>First Name:</td>
      <td><input type="text" name="fname" id="fname" <?php check('fname');?> /></td>
      <td>Mother's Name:</td>
      <td><input type="text" name="mother" id="mother" <?php check('mother');?> /></td>
    </tr>
    <tr>
      <td>MI:</td>
      <td><input type="text" name="mi" id="mi" <?php check('mi');?> /></td>
      <td>&nbsp;&nbsp;&nbsp;Occupation:</td>
      <td><input type="text" name="occupm" id="occupm" <?php check('occupm');?> /></td>
    </tr>
    <tr>
      <td>Gender:</td>
      <td><select name="gender" id="gender" <?php check('gender');?>>
        <option></option>
        <option value="FEMALE"<?php if(isset($_REQUEST['gender']) && $_REQUEST['gender']=='FEMALE') echo " selected";?>>Female</option>
        <option value="MALE"<?php if(isset($_REQUEST['gender']) && $_REQUEST['gender']=='MALE') echo " selected";?>>Male</option>
      </select></td>
      <td>Parent's Address:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Status:</td>
      <td><select name="status" id="status" <?php check('status');?>>
        <option></option>
        <option value="SINGLE" <?php if(isset($_REQUEST['status']) && $_REQUEST['status']=='SINGLE') echo " selected";?>>Single</option>
        <option value="MARRIED"<?php if(isset($_REQUEST['status']) && $_REQUEST['status']=='MARRIED') echo " selected";?>>Married</option>
        <option value="WIDOW"<?php if(isset($_REQUEST['status']) && $_REQUEST['status']=='WIDOW') echo " selected";?>>Widow</option>
        <option value="WIDOWER"<?php if(isset($_REQUEST['status']) && $_REQUEST['status']=='WIDOWER') echo " selected";?>>Widower</option>
      </select></td>
      <td colspan="2"><input name="addparents" type="text" id="addparents" size="40" <?php check('addparents');?> /></td>
      </tr>
    <tr>
      <td>Birth Date:</td>
      <td colspan="3">
      	<?php include("library/dateform.php");?>
        <?php year("year"); month("month"); day("day"); ?>
      </td>
    </tr>
    <tr>
      <td>Address:</td>
      <td colspan="3"><input type="text" name="bar" id="bar" <?php check('bar');?> />
              <input type="text" name="town" id="town" <?php check('town');?> />
              <input type="text" name="prov" id="prov" <?php check('prov');?> />
      </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td><em>(Barangay)</em></td>
      <td><em>(Town)</em></td>
      <td><em>(Province)</em></td>
    </tr>
    <tr>
      <td>Remarks:</td>
      <td colspan="3"><input name="remarks" type="text" id="remarks" size="50" <?php check('remarks');?> /></td>
      </tr>
    <tr>
      <td height="49" colspan="4" align="right">
      	<input type="submit" name="submit_new_student" id="submit_new_student" value="Save New Student"
        style="width: 150px; height: 40px; font-weight: bold;" /></td>
      </tr>
  </table>
</form>
</span>  
</div>

<div>
<form action="" method="post">
Search: 
  <select name="field" id="field">
    <option value="lname">Last Name</option>
    <option value="fname">First Name</option>
    <option value="addt">Town</option>
    <option value="addp">Province</option>
  </select>
  <input type="text" name="value" id="value" />
  <input type="submit" name="submit_search" id="submit_search" value="Submit" />
</form>
</div>
<?php
if(isset($_POST['submit_search'])){
	$field = $_REQUEST['field'];
	$value = $_REQUEST['value'];
	$sr = mysql_query("SELECT * FROM stud_info WHERE $field LIKE '%$value%' ORDER BY lname, fname");
	if(mysql_num_rows($sr)>0){  ?>
		<table width="653">
		<tr valign="top"><td width='81'>ID Number:</td><td width='199'>Name:</td><td width='200'>Address:</td></tr>
<?php	while($srow = mysql_fetch_assoc($sr)){ ?>
			<tr><td><strong>
            	<a href="index.php?page=view_edit_student&view_idnum=<?php echo $srow['idnum']?>"><?php echo $srow['idnum'] ?></a></strong></td>
            	<td><?php echo $srow['lname'] . ", " . $srow['fname'] . " " . $srow['mi'];?></td>
                <td><?php echo $srow['addb'] . ", " . $srow['addt'] . ", " . $srow['addp'];?></td></tr>
<?php		} ?>
		</table>
<?php }
}
?>