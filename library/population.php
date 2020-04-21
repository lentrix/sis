<?php
function getClassPopulation($class_code){
    global $db;
	$pop = mysqli_query($db, "SELECT count(idnum) FROM sub_enrol
	    WHERE class_code=$class_code AND (rating<>'W' OR rating<>'w') AND sem_code={$_SESSION['sem_code']}");
	$poprow = mysqli_fetch_row($pop);
	return $poprow[0];
}
function getClassLimit($class_code){
    global $db;
	$limit = mysqli_query($db, "SELECT class.limit FROM class where class_code=$class_code");
	if(mysqli_num_rows($limit)==1){
		$l = mysqli_fetch_row($limit);
		return $l[0];
	}
}

function countCourseYearGender($course, $year, $gender){
    global $db;
    $count = mysqli_query($db, "SELECT COUNT(stud_enrol.idnum)
        FROM stud_enrol, stud_info
        WHERE stud_enrol.idnum=stud_info.idnum
        AND course=$course
        AND year='$year'
        AND UPPER(gender)=UPPER('$gender')
        AND stud_enrol.en_status<>'withdrawn'
        AND sem_code={$_SESSION['sem_code']}");
    $row = mysqli_fetch_row($count);
    return $row[0];
}

function countCourseGender($course, $gender){
    global $db;
    $count = mysqli_query($db, "SELECT COUNT(stud_enrol.idnum) FROM stud_enrol, stud_info
        WHERE stud_enrol.idnum=stud_info.idnum
        AND course=$course
        AND stud_enrol.en_status<>'withdrawn'
        AND UPPER(gender)=UPPER('$gender')
        AND sem_code={$_SESSION['sem_code']}");

    $row = mysqli_fetch_row($count);
    return $row[0];
}

function countCourseStatusGender($course, $status, $gender){
    global $db;
    $count = mysqli_query($db, "SELECT COUNT(stud_enrol.idnum) FROM stud_enrol, stud_info
        WHERE stud_enrol.idnum=stud_info.idnum
        AND course=$course
        AND en_status='$status'
        AND gender='$gender'
        AND stud_enrol.en_status<>'withdrawn'
        AND sem_code={$_SESSION['sem_code']}");
    $row = mysqli_fetch_row($count);
    return $row[0];
}
?>
