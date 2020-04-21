<?php
function getCourseName($cr_num){
	global $db;
	$cr = mysqli_query($db, "SELECT cr_acrnm FROM courses WHERE cr_num=$cr_num");
	$crow = mysqli_fetch_row($cr);
	return $crow[0];
}
function getClassNameAndTime($class_code,$break){
	global $db;
	$sr = mysqli_query($db, "SELECT name, descript FROM class, subjects
			WHERE subjects.sub_code=class.sub_code AND class_code=$class_code");
	$srow = mysqli_fetch_row($sr);
	if($break)
		return $srow[0] . " - " . $srow[1] . "<br/>" . getClassTime($class_code, false);
	else
		return $srow[0] . " - " . $srow[1] . " " . getClassTime($class_code, false);
}

function getClassDetails($class_code,$break){
	global $db;
	$sr = mysqli_query($db, "SELECT class_code, name, descript FROM class, subjects
			WHERE subjects.sub_code=class.sub_code
			AND class.class_code=$class_code");
	$srow = mysqli_fetch_assoc($sr);
	if($break)
		return $srow['name'] . "-" . $srow['descript'] . "<br/>" . getClassTimeRoom($srow['class_code'],false);
	else
		return $srow['name'] . "-" . $srow['descript'] . " " . getClassTimeRoom($srow['class_code'],true);
}

function getCollegeAccronym($clg_no){
	global $db;
	$cl = mysqli_query($db, "SELECT acrnm FROM colleges WHERE clg_no=$clg_no");
	$clrow = mysqli_fetch_row($cl);
	return $clrow[0];
}
function getCollegeName($clg_no){
	global $db;
	$cl = mysqli_query($db, "SELECT college FROM colleges WHERE clg_no=$clg_no");
	$clrow = mysqli_fetch_row($cl);
	return $clrow[0];
}
function getFullName($idnum){
	global $db;
	$name = mysqli_query($db, "SELECT lname, fname, mi FROM stud_info WHERE idnum=$idnum");
	$nrow = mysqli_fetch_assoc($name);
	return $nrow['lname'] . ", " . $nrow['fname'] . " " . $nrow['mi'];
}
function getCourseAndYear($idnum, $sem_code){
	global $db;
	$cy=mysqli_query($db, "SELECT cr_acrnm, stud_enrol.year 
			FROM stud_enrol JOIN courses ON stud_enrol.course=courses.cr_num 
			WHERE idnum=$idnum AND sem_code=$sem_code");
	$cyrow = mysqli_fetch_assoc($cy);
	return $cyrow['cr_acrnm'] . "-" . $cyrow['year'];
}
function getTeacherFullName($tch_num){
	global $db;
	$tn = mysqli_query($db, "SELECT CONCAT(fname,' ',mi,' ',lname) AS 'name' FROM teacher 
			WHERE tch_num=$tch_num");
	$tnr = mysqli_fetch_row($tn);
	return $tnr[0];
}
function getDean($clg_no){
	global $db;
	$dn=mysqli_query($db, "SELECT dean FROM colleges WHERE clg_no=$clg_no");
	$dnr=mysqli_fetch_row($dn);
	return $dnr[0];
}
function getClassTime($class_code, $break){
	global $db;
	$tm=mysqli_query($db, "SELECT * FROM class_time WHERE class_code=$class_code");
	$timeStr = "";
	$count=0;
	if($break) $br="<br />"; else $br=" ";
	while($tmr=mysqli_fetch_assoc($tm)) {
		if($count>0) $timeStr.=",$br";
		$timeStr.=date('g:i', strtotime($tmr['t_start'])) . "-" . date('g:i', strtotime($tmr['t_end'])) . " " . $tmr['day'];
		$count++;
	}
	return $timeStr;
}
function getClassTimeRoom($class_code, $break){
	global $db;
    $tm=mysqli_query($db, "SELECT class_time.*, rooms.room FROM class_time, rooms
            WHERE class_time.rm_no=rooms.rm_no AND class_code=$class_code");
	$timeStr = "";
	$count=0;
	if($break) $br="<br />"; else $br=" ";
	while($tmr=mysqli_fetch_assoc($tm)) {
		if($count>0) $timeStr.=",$br";
		$timeStr.=date('g:i', strtotime($tmr['t_start'])) . "-" . date('g:i', strtotime($tmr['t_end'])) . " " . $tmr['day'] . " Rm. " . $tmr['room'];
		$count++;
	}
	return $timeStr;
}
function getClassName($class_code){
	global $db;
    $cls = mysqli_query($db, "SELECT s.name FROM class c, subjects s
        WHERE c.sub_code=s.sub_code AND c.class_code=$class_code");
    $row = mysqli_fetch_row($cls);
    if($row) return $row[0];
    else return 'not found.';
}
function getClassDescription($class_code){
	global $db;
    $cls = mysqli_query($db, "SELECT s.descript FROM class c, subjects s
        WHERE c.sub_code=s.sub_code AND c.class_code=$class_code");
    $row = mysqli_fetch_row($cls);
    if($row) return $row[0];
    else return 'not found.';
}
?>
