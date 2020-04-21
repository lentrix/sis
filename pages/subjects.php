<h1>Subjects</h1>

<?php
include("library/checker.php");
if (isset($_POST['submit_new_subject'])) {
  $ch = checkForBlank($_REQUEST);
  if ($ch) echo "<div class='error'>One or more field is left blank. E.g. $ch</div>";
  else {
    mysqli_query($db, "INSERT INTO subjects (name, descript, acad, clg_no) 
			VALUES ('{$_REQUEST['name']}', '{$_REQUEST['descript']}', 
			{$_REQUEST['acad']},{$_SESSION['clg_no']})");
    if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
    else echo "<div class='error'>The subject has beed added.</div>";
  }
}
?>

<div id="new_subject" style="overflow: hidden; height:30px;">
  <span>
    <input type="button" value=">>" style="float: right;" onclick="if(this.value=='>>') {
        	document.getElementById('new_subject').style.height='150px';
            this.value='<<';
         }else {
         	document.getElementById('new_subject').style.height='30px';
            this.value='>>';
         }">
  </span>

  <h2>Add Subject</h2>
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
            <option value="1">yes</option>
            <option value="0">no</option>
          </select>
          <input type="submit" value="Submit" name="submit_new_subject" />
        </td>
      </tr>
    </table>
  </form>
</div>
<div style="width:620px; margin-left: auto; margin-right:auto; background: #FFF;">
  <?php include("library/alpha_page.php"); ?>
  <?php showPagination("index.php?page=subjects"); ?>
</div>
<span style="display: block; margin-left: auto; margin-right: auto; width: 651px;">
  <table width="650" border="0" cellpadding="2">
    <tr>
      <th width="109" scope="col" class="thead">Sub Code:</th>
      <th width="360" scope="col" class="thead">Description:</th>
    </tr>
  </table>
</span>
<span style="display: block; margin-left: auto; margin-right: auto; width: 651px;">
  <table width="651">
    <?php
    if (isset($_GET['alphapage'])) $alphapage = $_GET['alphapage'];
    else $alphapage = "A";
    $subs = mysqli_query($db, "SELECT * FROM subjects WHERE descript LIKE '$alphapage%' ORDER BY descript");
    while ($subr = mysqli_fetch_assoc($subs)) {  ?>
      <tr>
        <td width="105" class="tcel">&nbsp;&nbsp;<a href="index.php?page=view_edit_subject&sub_code=<?php echo $subr['sub_code'] ?>" style="font-size: 10pt; text-decoration: none;"><?php echo $subr['sub_code']; ?></a></td>
        <td width="363" class="tcel">&nbsp;&nbsp;<?php echo $subr['descript']; ?></td>
      </tr>
    <?php }    ?>
    <tr>
      <td colspan="6" style="background-color:#006; -moz-border-radius: 0 0 10 10;">&nbsp;</td>
    </tr>
  </table>
</span>