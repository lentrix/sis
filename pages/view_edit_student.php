<?php include("library/checker.php");
if (isset($_GET['view_idnum'])) {
  if (isset($_POST['submit_update_student'])) {
    $check = checkForBlank($_REQUEST);
    if ($check) echo "<div class='error'>One or more fields are left empty. E.G. $check.</div>";
    else {
      foreach ($_REQUEST as $nm => $vl) {
        $$nm = $vl;
      }
      mysqli_query($db, "UPDATE stud_info SET 
						idext='$idext',
						lname='$lname',
						fname='$fname',
						mi='$mi',
						addb='$addb',
						addt='$addt',
						addp='$addp',
						gender='$gender',
						bdate='$year-$month-$day',
						status='$status',
						father='$father',
						mother='$mother',
						foccup='$foccup',
						moccup='$moccup',
						addparents='$addparents',
						remarks='$remarks'
						WHERE idnum=$idnum");
      if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
      else echo "<div class='error'>The student record has been updated.</div>";
    }
  } else {
    $stinfo = mysqli_query($db, "SELECT * FROM stud_info WHERE idnum={$_GET['view_idnum']}");
    $strow = mysqli_fetch_assoc($stinfo);
    foreach ($strow as $nm => $vl) {
      $_REQUEST[$nm] = $vl;
    }
    $year = substr($strow['bdate'], 0, 4);
    $_REQUEST['year'] = $year;
    $_REQUEST['month'] = substr($strow['bdate'], 5, 2);
    $_REQUEST['day'] = substr($strow['bdate'], 8, 2);
  }
}
?>
<h1><u>View / Edit Student Information</u></h1>
<div style="background: #fff; width: 680px; margin-left: auto; margin-right: auto;padding-top: 10px;">
  <form action="" method="post">
    <table width="670" border="0" cellpadding="2">
      <tr>
        <td width="95">ID Number</td>
        <td width="205"><input name="idnum" type="text" id="idnum" size="6" maxlength="6" readonly="readonly" <?php check('idnum'); ?> />-
          <input name="idext" type="text" id="idext" size="5" maxlength="5" <?php check('idext'); ?> /></td>
        <td width="126">Father's Name:</td>
        <td width="218"><input type="text" name="father" id="father" <?php check('father'); ?> /></td>
      </tr>
      <tr>
        <td>Last Name:</td>
        <td><input type="text" name="lname" id="lname" <?php check('lname'); ?> /></td>
        <td>&nbsp;&nbsp;&nbsp;Occupation:</td>
        <td><input type="text" name="foccup" id="foccup" <?php check('foccup'); ?> /></td>
      </tr>
      <tr>
        <td>First Name:</td>
        <td><input type="text" name="fname" id="fname" <?php check('fname'); ?> /></td>
        <td>Mother's Name:</td>
        <td><input type="text" name="mother" id="mother" <?php check('mother'); ?> /></td>
      </tr>
      <tr>
        <td>MI:</td>
        <td><input type="text" name="mi" id="mi" <?php check('mi'); ?> /></td>
        <td>&nbsp;&nbsp;&nbsp;Occupation:</td>
        <td><input type="text" name="moccup" id="moccup" <?php check('moccup'); ?> /></td>
      </tr>
      <tr>
        <td>Gender:</td>
        <td><select name="gender" id="gender" <?php check('gender'); ?>>
            <option></option>
            <option value="FEMALE" <?php if (isset($_REQUEST['gender']) && $_REQUEST['gender'] == 'FEMALE') echo " selected"; ?>>Female</option>
            <option value="MALE" <?php if (isset($_REQUEST['gender']) && $_REQUEST['gender'] == 'MALE') echo " selected"; ?>>Male</option>
          </select></td>
        <td>Parent's Address:</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Status:</td>
        <td><select name="status" id="status" <?php check('status'); ?>>
            <option></option>
            <option value="SINGLE" <?php if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'SINGLE') echo " selected"; ?>>Single</option>
            <option value="MARRIED" <?php if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'MARRIED') echo " selected"; ?>>Married</option>
            <option value="WIDOW" <?php if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'WIDOW') echo " selected"; ?>>Widow</option>
            <option value="WIDOWER" <?php if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'WIDOWER') echo " selected"; ?>>Widower</option>
          </select></td>
        <td colspan="2"><input name="addparents" type="text" id="addparents" size="40" <?php check('addparents'); ?> /></td>
      </tr>
      <tr>
        <td>Birth Date:</td>
        <td colspan="3">
          <?php include("library/dateform.php"); ?>
          <?php year("year");
          month("month");
          day("day"); ?>
        </td>
      </tr>
      <tr>
        <td>Address:</td>
        <td colspan="3"><input type="text" name="addb" id="bar" <?php check('addb'); ?> />
          <input type="text" name="addt" id="addt" <?php check('addt'); ?> />
          <input type="text" name="addp" id="addp" <?php check('addp'); ?> />
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
        <td colspan="3"><input name="remarks" type="text" id="remarks" size="50" <?php check('remarks'); ?> /></td>
      </tr>
      <tr>
        <td height="49" colspan="4" align="right">
          <input type="submit" name="submit_update_student" id="submit_update_student" value="Save Changes" style="width: 150px; height: 40px; font-weight: bold;" /></td>
      </tr>
    </table>
  </form>
</div>