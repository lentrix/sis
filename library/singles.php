<?php
function getCourseName($cr_num){
	$cr = mysql_query("SELECT cr_acrnm FROM courses WHERE cr_num=$cr_num");
	$crow = mysql_fetch_row($cr);
	return $crow[0];
}
function getClassNameAndTime($class_code,$break){
	$sr = mysql_query("SELECT name, descript FROM class, subjects
			WHERE subjects.sub_code=class.sub_code AND class_code=$class_code");
	$srow = mysql_fetch_row($sr);
	if($break)
		return $srow[0] . " - " . $srow[1] . "<br/>" . getClassTime($class_code, false);
	else 
		return $srow[0] . " - " . $srow[1] . " " . getClassTime($class_code, false);
}

function getClassDetails($class_code,$break){
	$sr = mysql_query("SELECT class_code, name, descript FROM class, subjects
			WHERE subjects.sub_code=class.sub_code 
			AND class.class_code=$class_code");
	$srow = mysql_fetch_assoc($sr);
	if($break)
		return $srow['name'] . "-" . $srow['descript'] . "<br/>" . getClassTimeRoom($srow['class_code'],false);
	else 
		return $srow['name'] . "-" . $srow['descript'] . " " . getClassTimeRoom($srow['class_code'],true);
}

function getCollegeAccronym($clg_no){
	$cl = mysql_query("SELECT acrnm FROM colleges WHERE clg_no=$clg_no");
	$clrow = mysql_fetch_row($cl);
	return $clrow[0];
}
function getCollegeName($clg_no){
	$cl = mysql_query("SELECT college FROM colleges WHERE clg_no=$clg_no");
	$clrow = mysql_fetch_row($cl);
	return $clrow[0];
}
function getFullName($idnum){
	$name = mysql_query("SELECT lname, fname, mi FROM stud_info WHERE idnum=$idnum");
	$nrow = mysql_fetch_assoc($name);
	return $nrow['lname'] . ", " . $nrow['fname'] . " " . $nrow['mi'];
}
function getCourseAndYear($idnum, $sem_code){
	$cy=mysql_query("SELECT cr_acrnm, stud_enrol.year 
			FROM stud_enrol JOIN courses ON stud_enrol.course=courses.cr_num 
			WHERE idnum=$idnum AND sem_code=$sem_code");
	$cyrow = mysql_fetch_assoc($cy);
	return $cyrow['cr_acrnm'] . "-" . $cyrow['year'];
}
function getTeacherFullName($tch_num){
	$tn = mysql_query("SELECT CONCAT(fname,' ',mi,' ',lname) AS 'name' FROM teacher 
			WHERE tch_num=$tch_num");
	$tnr = mysql_fetch_row($tn);
	return $tnr[0];
}
function getDean($clg_no){
	$dn=mysql_query("SELECT dean FROM colleges WHERE clg_no=$clg_no");
	$dnr=mysql_fetch_row($dn);
	return $dnr[0];
}
function getClassTime($class_code, $break){
	$tm=mysql_query("SELECT * FROM class_time WHERE class_code=$class_code");
	$timeStr = "";
	$count=0;
	if($break) $br="<br />"; else $br=" ";
	while($tmr=mysql_fetch_assoc($tm)) {
		if($count>0) $timeStr.=",$br";
		$timeStr.=date('g:i', strtotime($tmr['t_start'])) . "-" . date('g:i', strtotime($tmr['t_end'])) . " " . $tmr['day'];
		$count++;		
	}
	return $timeStr;
}
function getClassTimeRoom($class_code, $break){
    $tm=mysql_query("SELECT class_time.*, rooms.room FROM class_time, rooms 
            WHERE class_time.rm_no=rooms.rm_no AND class_code=$class_code");
	$timeStr = "";
	$count=0;
	if($break) $br="<br />"; else $br=" ";
	while($tmr=mysql_fetch_assoc($tm)) {
		if($count>0) $timeStr.=",$br";
		$timeStr.=date('g:i', strtotime($tmr['t_start'])) . "-" . date('g:i', strtotime($tmr['t_end'])) . " " . $tmr['day'] . " Rm. " . $tmr['room'];
		$count++;		
	}
	return $timeStr;
}
function getClassName($class_code){
    $cls = mysql_query("SELECT s.name FROM class c, subjects s
        WHERE c.sub_code=s.sub_code AND c.class_code=$class_code");
    $row = mysql_fetch_row($cls);
    if($row) return $row[0];
    else return 'not found.';
}
function getClassDescription($class_code){
    $cls = mysql_query("SELECT s.descript FROM class c, subjects s
        WHERE c.sub_code=s.sub_code AND c.class_code=$class_code");
    $row = mysql_fetch_row($cls);
    if($row) return $row[0];
    else return 'not found.';
}
?>
