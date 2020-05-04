<?php
include("library/singles.php");
include("library/classlist.php");
$class_code = $_GET['class_code'];

$dom = new DomDocument();
$class = $dom->createElement('class');

$code = $dom->createElement('code');
$code_str = $dom->createTextNode($class_code);
$code->appendChild($code_str);
$class->appendChild($code);

$courseno = $dom->createElement('courseno');
$courseno_str = $dom->createTextNode(getClassName($class_code));
$courseno->appendChild($courseno_str);
$class->appendChild($courseno);

$des = $dom->createElement('description');
$des_str = $dom->createTextNode(getClassDescription($class_code));
$des->appendChild($des_str);
$class->appendChild($des);

$timeroom = $dom->createElement('timeroom');
$timeroom_str = $dom->createTextNode(getClassTimeRoom($class_code, false));
$timeroom->appendChild($timeroom_str);
$class->appendChild($timeroom);

$students = $dom->createElement('Students');

$studs = mysqli_query($db, "SELECT idext, stud_enrol.idnum, 
			lname,fname, mi, cr_acrnm, year FROM stud_info, stud_enrol, courses 
			WHERE stud_enrol.idnum = stud_info.idnum 
			AND courses.cr_num=stud_enrol.course
			AND sem_code={$_SESSION['sem_code']}
			AND stud_enrol.idnum IN (SELECT sub_enrol.idnum FROM sub_enrol 
				WHERE sub_enrol.class_code=$class_code AND (rating is null or rating<>'W'))
			ORDER BY lname, fname");
while ($row = mysqli_fetch_assoc($studs)) {
    $stud = $dom->createElement('student');

    $idnum = $dom->createElement('idnum');
    $idnum_str = $dom->createTextNode($row['idnum']);
    $idnum->appendChild($idnum_str);
    $stud->appendChild($idnum);

    $lname = $dom->createElement('lastname');
    $lname_str = $dom->createTextNode($row['lname']);
    $lname->appendChild($lname_str);
    $stud->appendChild($lname);

    $fname = $dom->createElement('firstname');
    $fname_str = $dom->createTextNode($row['fname']);
    $fname->appendChild($fname_str);
    $stud->appendChild($fname);

    $mi = $dom->createElement('mi');
    $mi_str = $dom->createTextNode($row['mi']);
    $mi->appendChild($mi_str);
    $stud->appendChild($mi);

    $course = $dom->createElement('course');
    $course_str = $dom->createTextNode($row['cr_acrnm']);
    $course->appendChild($course_str);
    $stud->appendChild($course);

    $year = $dom->createElement('year');
    $year_str = $dom->createTextNode($row['year']);
    $year->appendChild($year_str);
    $stud->appendChild($year);

    $students->appendChild($stud);
}

$class->appendChild($students);

$dom->appendChild($class);

$dom->save('xml/Class_list.xml');
?>

<h1>Class XML File Generated</h1>
<p>
    Right click and save target as..<br />
    <a href="xml/Class_list.xml">Class List XML File</a>
</p>