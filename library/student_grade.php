
<?php
include("library/singles.php");
//---------------- Student Grade -------------------

function showStudentGrade($idnum, $sem_code){
	global $db;
	$sqlst = "SELECT class.class_code, descript, cunits, mgrade, fgrade, rating, CONCAT(fname,' ',lname) AS 'teacher'
			FROM sub_enrol, stud_enrol, class, subjects, teacher
			WHERE sub_enrol.idnum=stud_enrol.idnum
			AND sub_enrol.class_code = class.class_code
			AND subjects.sub_code=class.sub_code
			AND class.tch_num=teacher.tch_num
			AND stud_enrol.idnum=$idnum
			AND stud_enrol.sem_code=$sem_code
			AND sub_enrol.sem_code=$sem_code";
    $subgrade = mysqli_query($db, $sqlst); ?>
    <span style="display:block;text-align:center">
        MATER DEI COLLEGE<br />
        Tubigon, Bohol<br /><br />
        GRADES<br />
        <?php echo getSemester($sem_code); ?><br /><br />
    </span>

    <span style="display:block; float: right; text-align: right; margin-right: 10px;">
        Student No.: <?php echo $idnum; ?><br />
        Course &amp; Year: <?php echo getCourseAndYear($idnum, $sem_code); ?>
    </span>
    <span style="display:block;">
        Name: <?php echo getFullName($idnum); ?><br />
        Address:
        <?php
            $addr = mysqli_query($db, "SELECT CONCAT(addb,', ',addt,', ',addp) AS 'addr', father, addparents 
                    FROM stud_info WHERE idnum=$idnum");
            $addrr=mysqli_fetch_assoc($addr);
            echo $addrr['addr'];
        ?>
    </span>


	<table border="1" style="border-collapse: collapse;">
    <tr>
        <td class="thead" width="300">Class:</td>
        <td class="thead" width="50">Units:</td>
        <td class="thead" width="50">Midterm:</td>
        <td class="thead" width="50">Final:</td>
        <td class="thead" width="50">Rating:</td>
        <td class="thead" width="150">Instructor:</td>
    </tr>
<?php
    $wave = 0;
    $wsum = 0;
    $wts = 0;
	while($sgrow=mysqli_fetch_assoc($subgrade)){
		if($sgrow['mgrade']=='NULL') $mgrade='-'; else $mgrade=$sgrow['mgrade'];
		if($sgrow['fgrade']=='NULL') $fgrade='-'; else $fgrade=$sgrow['fgrade'];
		if($sgrow['rating']=='NULL') $rating='-'; else $rating=$sgrow['rating'];
		if($sgrow['rating']>3) $cunits=0;
		else if(empty($sgrow['rating']) || $sgrow['rating']=="-" || $sgrow['rating']==0) $cunits="-";
		else $cunits = $sgrow['cunits'];
		echo "<tr>
			<td class='tcel'>{$sgrow['descript']}</td>
			<td class='tcel' align='center'>$cunits</td>
			<td class='tcel' align='center'>$mgrade</td>
			<td class='tcel' align='center'>$fgrade</td>
			<td class='tcel' align='center'>$rating</td>
			<td class='tcel' align='left'>{$sgrow['teacher']}</td>
			</tr>";
	    if($cunits!="-"){
	        $wsum += ($cunits*$rating);
	        $wts += $cunits;
	    }
	}
	if($wts==0)
		$wave = "-";
	else
		$wave = $wsum / $wts;

	echo "</table>"; ?>
	<span style="display: block; margin-top: 20px;">
	    Weighted Average: <?php printf($wave); ?>
	</span>
	<span style="display: block; margin-top: 20px; width: 300px; float: left">
	    Prepared by: <br /><br />
	    <span style="text-transform: uppercase"><?php echo $_SESSION['fullname']; ?></span><br />
	    Registrar Staff<br />
	    Date Processed: <?php echo date('l - F j, Y'); ?>
	</span>
	<span style="display: block; margin-top: 20px; float: right; margin-right: 10px; text-align: center">
	    Verified by: <br /><br />
	    JOSE RUEL B. ALAMPAYAN<br />
	    Registrar
	</span>

	<span style="display: block; margin-top: 35px; clear:both; float: left">
	    Name of Father: <?php echo $addrr['father']; ?><br />
	    Address: <?php echo $addrr['addparents']; ?>
	</span>
<?php
}
?>

