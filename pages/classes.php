<?php
include("library/lister.php");
include("library/checker.php");
include("library/population.php");
include("library/singles.php");
include("library/alpha_page.php");
?>

<script type="text/javascript">
function enableSecondTime(sourceObj){
    obj = document.getElementById('second_time');
    if(sourceObj.innerHTML=="Enable second time"){
        obj.innerHTML = document.getElementById('time2').innerHTML;
        obj.style.display='block';
        sourceObj.innerHTML="Disable Second Time";
    }else {
        obj.innerHTML="";
        obj.style.display='none';
        sourceObj.innerHTML="Enable second time";
    }
}
</script>
<div style="display:none" id="time2">
<?php showTimeInput("start_time2"); ?>
  &nbsp;&nbsp;&nbsp;to&nbsp;&nbsp; <?php showTimeInput("end_time2"); ?>
  Day: <input type="text" name="day2" maxlength="3" size="3" <?php check("day2");?>/ >
  Room: <?php CreateList("rm_no2","rm_no","room","SELECT * FROM rooms ORDER BY room",""); ?>
</div>

<h1>Classes</h1>
<?php 
$check = checkForBlank($_REQUEST);
if($check) echo "<div class='error'>One or more fields are left blank. E.G. $check.</div>";
else{
	if(isset($_POST['submit_new_class'])){
		foreach($_REQUEST as $n=>$v) $$n=$v;
	    
	    
    	$checkRT = checkRoomTimeConflict($start_time,$end_time, $rm_no1, $day);
    	if(!$checkRT && isset($start_time2, $end_time2, $day2)) 
    	    $checkRT = checkRoomTimeConflict($start_time2,$end_time2, $rm_no2, $day2);

		if($checkRT){
			echo "<div class='error'>The class you are trying to add is in 
				conflict with the following class: <br/ >";
			echo "<ul type='disc'>\n";
			while($checkRTrow = mysql_fetch_assoc($checkRT)){
				echo "<li>" . $checkRTrow['descript'] . " (" . $checkRTrow['t_start'] . 
					" - " . $checkRTrow['t_end'] . " " . $checkRTrow['day'] . ") Room: " . 
					$checkRTrow['room'] . "</li>\n";
			}
			echo "</ul></div>";
		}
		
		$checkTT = checkTeacherTimeConflict($start_time, $end_time, $tch_num, $day);
        if($checkTT && isset($start_time2, $end_time2, $day2)) 
            $checkTT = checkTeacherTimeConflict($start_time2, $end_time2, $tch_num, $day2);
            
		if($checkTT){
			$checkTTrow = mysql_fetch_assoc($checkTT);
			echo "<div class='error'>Teacher {$checkTTrow['teacher']} is already assigned in the following class:";
			echo "<ul type='disc'>\n";
			echo "<li>" . $checkTTrow['descript'] . " (" . $checkTTrow['t_start'] . 
				" - " . $checkTTrow['t_end'] . " " . $checkTTrow['day'] . ")</li>";
			echo "</ul></div>";
		}
		if(!$checkRT && !$checkTT) {
		    
			mysql_query("INSERT INTO class (sub_code, tch_num, clg_no, cunits, punits, sem_code, class.limit)
				VALUES ($sub_code, $tch_num, 
					{$_SESSION['clg_no']}, $cunits, $punits, {$_SESSION['sem_code']}, $limit)");

			if(mysql_error()) echo "<div class='error'>Class Detail: " . mysql_error() . "</div>";

			$newid = mysql_query("SELECT LAST_INSERT_ID() AS id");
			$newidr = mysql_fetch_row($newid);
			
			mysql_query("INSERT INTO class_time (class_code, t_start, t_end, day, rm_no) 
			        VALUES ({$newidr[0]}, '$start_time', '$end_time', UPPER('$day'), $rm_no1)");
            if(isset($start_time2, $end_time2, $day2)) {
                mysql_query("INSERT INTO class_time (class_code, t_start, t_end, day, rm_no) 
			        VALUES ({$newidr[0]}, '$start_time2', '$end_time2', UPPER('$day2'),$rm_no2)");
            }
			
			if(mysql_error()) echo "<div class='error'>Class Time Room: " . mysql_error() . "</div>";
			else {
				echo "<div class='error'>The class has been successfully added!</div>";
				$_REQUEST = array();
			}
		}else{
			echo "<div class='error'>The class was not successfully added due to conflicts.</div>";
		}
	}
}
?>
<div style="min-height: 30px;">
<div style="margin-right: 10px; border: 0px; font-weight: bold; float: left;">
	Add New Class
</div>
<span>
	<input type="button" value=">>" style="float: right;"
    	onclick=
        "if(this.value=='>>') {
        	document.getElementById('new_class').style.display='inline-block';
            this.value='<<';
         }else {
         	document.getElementById('new_class').style.display='none';
            this.value='>>';
         }">
</span>
<div style="border: 1px solid #777; padding: 10px; display: none;background: #ffc;" id="new_class">
<form id="form1" name="form1" method="post" action="">
<table>
<tr><td>Subject:</td>
	<td>
    <?php CreateList("sub_code","sub_code","descript",
		"SELECT sub_code, descript FROM subjects WHERE name IS NOT NULL ORDER BY descript",
		"font-size:9pt;","font-size:9pt;"); ?>
    </td></tr>
<tr><td>Time:</td><td><span  id="time1"><?php showTimeInput("start_time"); ?>
  &nbsp;&nbsp;&nbsp;to&nbsp;&nbsp; <?php showTimeInput("end_time"); ?>
  Day: <input type="text" name="day" maxlength="3" size="3" <?php check("day");?>/ >
  Room: <?php CreateList("rm_no1","rm_no","room","SELECT * FROM rooms WHERE active=1 ORDER BY room",""); ?>
  </span><a onclick="enableSecondTime(this);" style="text-decoration:underline;cursor:pointer">Enable second time</a>
  <span id="second_time" style="display: none; "></span>
  </td></tr>

<tr>
	<td>Credit Units:</td><td><input type="text" name="cunits" size="4" maxlength="4" <?php check("cunits");?>>
	Pay Units: <input type="text" name="punits" size="4" maxlength="4" <?php check("punits");?>></td>
</tr>
<tr><td>Instructor:</td>
  <td>
  <?php CreateList("tch_num","tch_num","name",
		"SELECT tch_num, CONCAT(lname,', ',fname,' ',mi) AS 'name' FROM teacher ORDER BY lname, fname",""); ?>
  Limit: <input type="text" name="limit" maxlength="3" size="3" <?php check("limit");?>>
  </td></tr>
  <tr><td colspan="2" align="right">
	<input type="submit" name="submit_new_class" value="Submit" />
  </td></tr>
</table>
</form>
</div>
</div>

<input type="image" src="images/printButton.png" class="noprint"
	onClick="printThisDiv('printFrame','printdiv');"
	style="float: right; margin-top: 0px; margin-right: 5px;display:screen;">
<span style="float:right;display: inline-block">
	<?php
		$mdl = mysql_query("SELECT * FROM modassgn WHERE modlno=1 AND user='{$_SESSION['user']}'");
		// if(mysql_num_rows($mdl)) { ?>
        [<a style="color:#03C;" href="index.php?page=classes&alphapage=allreg">
    	Show all Offerings
    </a>]&nbsp;&nbsp;&nbsp;
    <?php
	//}
	?>
	[<a style="color:#03C;" href="index.php?page=classes&alphapage=all">
    	Show all <?php echo getCollegeAccronym($_SESSION['clg_no']);?> Offerings
    </a>]&nbsp;&nbsp;&nbsp;
</span>
<iframe name="printFrame" id="printFrame" style="width:0px;height:0px;float:left; margin-left: -9999px"></iframe>


<h1 style="">List of Classes</h1>

<center>
<div style="width:620px; margin-left: auto; margin-right:auto; background: #FFF;">
<?php
showPagination("index.php?page=classes");


if(isset($_GET['alphapage'])){
	if($_GET['alphapage']=="all") {
		$alpha_page = "AND class.clg_no={$_SESSION['clg_no']} ";
	}else if($_GET['alphapage']=="allreg"){
		$alpha_page = "";
	}else{
		$alpha_page = " AND descript LIKE '{$_GET['alphapage']}%' ";
	}
}else $alpha_page="AND descript LIKE 'A%' ";
$sequel = "SELECT name, class_code, descript, cunits, punits, 
	CONCAT(fname,' ',lname) AS 'teacher' FROM class, teacher, subjects
	WHERE class.tch_num=teacher.tch_num AND class.sub_code=subjects.sub_code 
	$alpha_page AND sem_code={$_SESSION['sem_code']} ORDER BY descript";

?>
</div>
<div id="printdiv">
<table style="border-collapse: collapse; width: 100%">
<tr>
	<td class="thead" width="10%">Code:</td>
	<td class="thead" width="10%">Course No.</td>
	<td class="thead" width="35%">Description:</td>
	<td class="thead" width="15%">Time/Room:</td>
	<td class="thead" width="5%">Cr Unit:</td>
	<td class="thead" width="5%">Num:</td>
    <td class="thead" width="20%">Instructor:</td>
</tr>

<?php
$class_list = mysql_query($sequel);

while($class_row=mysql_fetch_assoc($class_list)){   ?>
<tr>
	<td class="tcel">
		<a href="index.php?page=view_edit_class&class_code=<?php echo $class_row['class_code'];?>" 
        	style="font-size: 9pt; text-decoration: none;font-weight:bold"
		title="<?php echo $class_row['name']; ?>">
		<?php echo $class_row['class_code'];?>
		</a>
	</td>
	<td class="tcel"><?php echo $class_row['name'];?></td>
    <td class="tcel"><?php echo $class_row['descript']; ?></td>
	<td class="tcel"><?php echo getClassTimeRoom($class_row['class_code'], true); ?></td>
	<td class="tcel"><?php echo $class_row['cunits'];?></td>
	<td class="tcel" align="center"><?php echo getClassPopulation($class_row['class_code']);?></td>
    <td class="tcel"><?php echo $class_row['teacher'];?></td>
</tr>
<?php } ?>
<tr><td colspan="8" style="background-color:#006; -moz-border-radius: 0 0 10 10;">&nbsp;</td></tr>
</table>
</div>
</center>
