<?php

//delete old data
if (!$db->query("DELETE FROM transcript_sem WHERE type='int'")) {
    die(mysqli_error($db));
}

//generate transcript data..
$id = $_GET['idNumber'];

$enrolsSQL = mysqli_query($db, "SELECT se.enrol_id, se.en_status, sm.sem, sm.sem_code, cr.cr_acrnm, se.year FROM stud_enrol se
    LEFT JOIN courses cr ON cr.cr_num=se.course
    LEFT JOIN sems sm ON sm.sem_code=se.sem_code
    WHERE se.idnum=$id
    ORDER BY sm.sem_code");

$ord = 20000;

while ($enrols = mysqli_fetch_object($enrolsSQL)) {

    //Insert a trascript_sem
    mysqli_query($db, "INSERT INTO transcript_sem (
        `idnum`, `ordinal`, `sy`, `school`, `address`, `program`, `type`
    ) VALUES (
        $id,
        $ord,
        '$enrols->sem',
        'Mater Dei College',
        'Tubigon, Bohol',
        '$enrols->cr_acrnm-$enrols->year',
        'int'
    )");

    $insert_id = mysqli_insert_id($db);

    //Insert transcript_rows to this transcript_sem

    //Retrieve enrolled subjects..
    $classesSQL = mysqli_query($db, "SELECT subjects.name, subjects.descript, sub_enrol.rating, class.cunits FROM sub_enrol
        LEFT JOIN class ON class.class_code=sub_enrol.class_code
        LEFT JOIN subjects ON subjects.sub_code = class.sub_code
        WHERE sub_enrol.idnum = $id AND sub_enrol.sem_code=$enrols->sem_code");

    while ($classes = mysqli_fetch_object($classesSQL)) {
        $rating = $classes->rating=='pass' ? 'Passed' : ( is_numeric($classes->rating) ? number_format($classes->rating, 2) : '-');
        $units = (is_numeric($classes->cunits) && (is_numeric($classes->rating) && $classes->rating<=3.0) || $classes->rating=='pass' ) ? $classes->cunits : 0;
        
        if($enrols->en_status=="withdrawn") {
            $rating = "w";
            $units = 0;
        }

        mysqli_query($db, "INSERT INTO transcript_row (
            `transcript_sem_id`, `course`, `description`, `rating`,`units`
        ) VALUES (
            $insert_id,
            '$classes->name',
            '$classes->descript',
            '$rating',
            '$units'
        )");

        if (mysqli_error($db)) die(mysqli_error($db) . " == " . $rating);
    }

    $ord = $ord + 1000;
}//while $enrols


//create if not exists transcript_details
$transcript_details = $db->query("SELECT * FROM transcript_details WHERE idnum=$id")->fetch_object();

if(!$transcript_details) {
    $db->query("INSERT INTO transcript_details (idnum) VALUES ($id)");
}