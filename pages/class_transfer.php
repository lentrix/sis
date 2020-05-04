<?php include("library/lister.php"); ?>
<?php include("library/population.php"); ?>
<?php include("library/checker.php"); ?>
<?php include("library/singles.php"); ?>

<?php
function getSubCodeFromClass($class_code){
    global $db;
    $sc = mysqli_query($db, "SELECT sub_code FROM class WHERE class_code=$class_code");
    if(mysqli_num_rows($sc)==1){
        $scr = mysqli_fetch_row($sc);
        return $scr[0];
    }else return false;
}

function getList($class_code){
    global $db;
    $list1 = mysqli_query($db, "SELECT idext, stud_enrol.idnum,
			CONCAT(lname,', ',fname,' ',mi) AS 'name',
			cr_acrnm, year FROM stud_info, stud_enrol, courses
			WHERE stud_enrol.idnum = stud_info.idnum
			AND courses.cr_num=stud_enrol.course
			AND sem_code={$_SESSION['sem_code']}
			AND stud_enrol.idnum IN (SELECT sub_enrol.idnum FROM sub_enrol
				WHERE sub_enrol.class_code=$class_code AND rating<>'W')
			ORDER BY lname, fname");
	return $list1;
}

function showList($class_code) {
    $list1 = getList($class_code);
	echo "<ol type='1'>";
	while($lr=mysqli_fetch_assoc($list1)) {
	    echo "<li>" . $lr['name'] . "[" . $lr['cr_acrnm'] . "-" . $lr['year'] . "]</li>";
	}
	echo "</ol>";
}
function analyze($class_code1, $class_code2){
    global $db;
    $issues = array();

    $list = getList($class_code1);

    $classDetail = mysqli_query($db, "SELECT * FROM class WHERE class_code=$class_code2");
    $classDetailRow = mysqli_fetch_assoc($classDetail);

    //check population..
    $pop1 = getClassPopulation($class_code1);
    $pop2 = getClassPopulation($class_code2);
    $limit = getClassLimit($class_code2);
    if($limit < ($pop1+$pop2)) $issues[] = "The numbers of students to be added to the receiving class will exceed its limit";

    //check student time conflict...
	$frm_class = mysqli_query($db, "SELECT * FROM class_time WHERE class_code=$class_code2");
	$frm_row = mysqli_fetch_assoc($frm_class);
    $start_time1 = $frm_row['t_start'];
	$end_time1 = $frm_row['t_end'];
	$day1 = $frm_row['day'];

	if(mysqli_num_rows($frm_class)>1) {
		$frm_row = mysqli_fetch_assoc($frm_class);
		$start_time2 = $frm_row['t_start'];
		$end_time2 = $frm_row['t_end'];
		$day2 = $frm_row['day'];
	}

    while($listrow=mysqli_fetch_assoc($db, $list)) {
        $conflict = checkStudentSubjectTimeConflict($start_time1,$end_time1,$listrow['idnum'],$day1);
		if(!$conflict) $conflict = checkStudentSubjectTimeConflict($start_time1,$end_time1,$listrow['idnum'],$day1);
        if($conflict){
            while($conrow = mysqli_fetch_assoc($conflict)) {
                $issues[] = getFullName($listrow['idnum']) . " has a conflicting subject: " . 
                    $conrow['descript'] . " (" . $conrow['time'] . " " . $conrow['day'] . ")";
            }
        }
    }
    return $issues;
}
?>

<h1>Class To Class Transfer</h1>

<?php
if(isset($_POST['submit_transfer'])) {

    $fromClassCode=$_POST['class_code1'];
    $toClassCode = $_POST['class_code2'];

    $list = getList($fromClassCode);
    $sub_code = getSubCodeFromClass($toClassCode);

    $issues = analyze($fromClassCode, $toClassCode);

    if(count($issues)>0){
        echo "<div class='error'>The transfer cannot be performed due to the following issues:";
        echo "<ul type='disc'>";
        foreach($issues as $issue){
            echo "<li>$issue</li>";
        }
        echo "</ul></div>";

    }else{
        while($row=mysqli_fetch_assoc($list)) {
            $idnum = $row['idnum'];
            mysqli_query($db, "UPDATE sub_enrol SET class_code=$toClassCode, sub_code=$sub_code WHERE class_code=$fromClassCode AND idnum=$idnum");
            if(mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
        }
        echo "<div class='error'>Transfer finished.</div>";
        $datetime=date('Y-m-d');
        $time = localtime(time(),true);
        $timestr = $time['tm_hour'] . ":" . $time['tm_min'];
        mysqli_query($db, "INSERT INTO log (user, date, detail)
            VALUES ('{$_SESSION['user']}','$datetime $timestr','Transfered the students from " . getClassNameAndTime($fromClassCode,false) .
                " to " . getClassNameAndTime($toClassCode,false) . "')");
        $_POST['submit_view_list']="Review List";
    }
}
?>

<div style="background: #fff;">
<h2 style="color: #f33">Warning!</h2>
<p style="font-size:10pt">This facility allows you to transfer all the students from one class to another. This is intended for Dean's use only.
If you are not a Dean or College Chairman, or authorized faculty, please refrain from using this facility and inform
the administrator immediately. Please be very careful in using this facility. It has a substantial level of automation that
cannot be undone automatically.</p>
<p style="font-size:10pt">In using this facility, certain issues may arise including but not limitted to: conflicting student's schedule. Please study the
existing data carefully before you proceed.</p>
<p style="font-size:10pt">Depending on your discretion, you may transfer all the students from one class to another class having a different 
subject description but it is not normal and certainly not recommended nor advised.</p>
<p style="font-size:10pt">Due to the level of risk involved in the use of this facility, your actions will be recorded in an event log.
<br /><strong>(Please proceed with caution)</strong></p>
</div>

<?php
$s1 = "SELECT class_code, name, descript
		FROM class, subjects
		WHERE class.sub_code=subjects.sub_code
		AND sem_code={$_SESSION['sem_code']}
		ORDER BY name";
?>

<div style="background: #fff;">
    <form method="post" action="">
        <div>
            <input type="submit" name="submit_view_list" value="Review List" />
        </div>
        <table width="100%" style="border-collapse:collapse;" border="1">
            <tr><th width="50%">FROM:</th><th width="50%">TO:</th></tr>
            <tr>
                <td width="50%">
                    <?php ListClass("class_code1","class_code",$s1,"font-size:8pt;","width:100%;font-size:8pt");?>
                </td>
                <td width="50%">
                    <?php ListClass("class_code2","class_code",$s1,"font-size:8pt;","width:100%;font-size:8pt;");?>
                </td>
            </tr>

<?php
if(isset($_POST['submit_view_list'])) {
    $cc1 = $_POST['class_code1'];
    $cc2 = $_POST['class_code2'];
    echo "<tr><td valign='top'>";
    showList($cc1);
    echo "</td><td valign='top'>";
    showList($cc2);
    echo "</td></tr>";
    echo "<tr><td colspan='2' align='right'>
        <input type='submit' name='submit_transfer' value='Proceed with the transfer' />
        </td></tr>";
}
?>

        </table>
    </form>
</div>
