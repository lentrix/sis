<html>
    <head>
        <title>Enrolment List</title>
    </head>
    <body>

<?php 
include("config/dbc.php");
$studs = mysql_query("SELECT stud_enrol.idnum,lname, fname, mi, cr_acrnm, year 
		FROM stud_info, stud_enrol, courses
		WHERE stud_info.idnum=stud_enrol.idnum
		AND courses.cr_num=stud_enrol.course
		AND sem_code=6
		ORDER BY lname, fname, mi");
if(mysql_error()) echo mysql_error();		
while($strow=mysql_fetch_assoc($studs)) {
    echo $strow['idnum'] . ";" . $strow['lname'] . ";" .
        $strow['fname'] . ";" .
        $strow['mi'] . ";" .
        $strow['cr_acrnm'] . ";" .
        $strow['year'] . ";";
    $subs = mysql_query("SELECT subjects.name, punits 
        FROM sub_enrol, subjects, class
        WHERE sub_enrol.class_code=class.class_code
        AND class.sub_code=subjects.sub_code
        AND sub_enrol.sem_code=6
        AND sub_enrol.idnum={$strow['idnum']}");
    while($subrow=mysql_fetch_assoc($subs)) {
        echo $subrow['name'] . ";" .
            $subrow['punits'] . ";";
    }
    echo "<br />";
}		
?>
        
    </body>
</html>
