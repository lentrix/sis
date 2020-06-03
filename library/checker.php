<?php
function checkForBlank($arr){
	foreach($arr as $item_name => $item_value){
		if(empty($item_value)) return $item_name;
	}
	return false;
}

function check($item){
	
	if(isset($_REQUEST[$item])){
		if( empty($_REQUEST[$item])) echo "style=\"border-color: red;\"";
		else echo "value='{$_REQUEST[$item]}'";
	}
}

function checkRoomTimeConflict($start_time, $end_time, $rm_no, $day, $class_code=0){
	global $db;
    $start_time_plus = date('g:i', strtotime($start_time . " + 1min"));
    $end_time_minus = date('g:i', strtotime($end_time . " - 1min"));
	$sequel = "SELECT t_start, t_end, room, descript, day 
	    FROM class, subjects, rooms, class_time 
		WHERE class.sub_code=subjects.sub_code 
		AND rooms.rm_no=class_time.rm_no 
		AND class.class_code=class_time.class_code
		AND ((t_start BETWEEN '$start_time' AND '$end_time_minus') 
		OR (t_end BETWEEN '$start_time_plus' AND '$end_time'))  
		AND class_time.rm_no=$rm_no AND UPPER(day)=UPPER('$day')
		AND class.class_code<>$class_code
		AND class.sem_code={$_SESSION['sem_code']}";
	$conflict = mysqli_query($db, $sequel);
	echo mysqli_error($db);
	if(mysqli_num_rows($conflict)>0) return $conflict;
	else return false;
}

function checkTeacherTimeConflict($start_time, $end_time, $tch_num, $day, $class_code=0){
	global $db;
    $start_time_plus = date('g:i', strtotime($start_time . " + 1min"));
    $end_time_minus = date('g:i', strtotime($end_time . " - 1min"));
	$conflict = mysqli_query($db, "SELECT CONCAT(lname,', ',fname,' ',mi) AS 'teacher',t_start, t_end, descript, day	
		FROM class, class_time, subjects, teacher WHERE class.sub_code=subjects.sub_code 
		AND class_time.class_code=class.class_code
		AND teacher.tch_num=class.tch_num 
		AND ((t_start BETWEEN '$start_time' AND '$end_time_minus') 
		OR (t_end BETWEEN '$start_time_plus' AND '$end_time')) 
		AND class.tch_num=$tch_num AND UPPER(day)=UPPER('$day')
		AND class.class_code<>$class_code
		AND class.sem_code={$_SESSION['sem_code']}");
	
	if(mysqli_num_rows($conflict)>0) return $conflict;
	else return false;
}

function checkStudentSubjectTimeConflict($start_time, $end_time, $idnum, $day){
	global $db;
	$start_time_plus = date('g:i', strtotime($start_time . " + 1min"));
	$end_time_minus = date('g:i', strtotime($end_time . " - 1min"));

	$conflict = mysqli_query($db, "SELECT DISTINCT descript, class.class_code, name
		FROM stud_enrol, sub_enrol, class, subjects, class_time
		WHERE stud_enrol.idnum=sub_enrol.idnum
		AND sub_enrol.class_code=class.class_code
		AND class.sub_code = subjects.sub_code
		AND class_time.class_code=class.class_code
		AND sub_enrol.idnum=$idnum
		AND class.sem_code={$_SESSION['sem_code']}
		AND ((t_start BETWEEN '$start_time' AND '$end_time_minus')
		OR (t_end BETWEEN '$start_time_plus' AND '$end_time'))
		AND UPPER(day)=UPPER('$day')");

	if(mysqli_num_rows($conflict)>0) return $conflict;
	else return false;
}

?>
