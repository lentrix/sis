<?php
include("library/transcript_functions.php");
include("library/singles.php");

$semId = $_GET['sem'];
$idnum = $_GET['idnum'];
$direction = $_GET['direction'];

$nextOrdinal = ($direction == "above") ? getInsertPreviousOrdinal($semId, $idnum) : getInsertNextOrdinal($semId, $idnum);

if(isset($_POST['submit'])) {

    $sy = $db->real_escape_string($_POST['sy']);
    $school = $db->real_escape_string($_POST['school']);
    $address = $db->real_escape_string($_POST['address']);
    $program = $db->real_escape_string($_POST['program']);

    $db->query("INSERT INTO transcript_sem (
        `idnum`, `ordinal`, `sy`, `school`, `address`, `program`, `type`
    ) VALUES (
        $idnum, $nextOrdinal, '$sy', '$school', '$address', '$program', 'ext'
    )");

    if(mysqli_error($db)) {
        die("Error:" . mysqli_error($db));
    }

    $generated_key = mysqli_insert_id($db);

    for($i=0; $i<12; $i++) {
        if($_POST['course'][$i]) {
            $course = $_POST['course'][$i];
            $description = $_POST['description'][$i];
            $rating = $_POST['rating'][$i];
            $units = $_POST['units'][$i];

            $db->query("INSERT INTO transcript_row (
                `transcript_sem_id`, `course`, `description`, `rating`, `units`
            ) VALUES (
                $generated_key, '$course', '$description', '$rating', '$units'
            )");
        }
    }

    echo "<script>window.location='index.php?page=transcript/transcript&idNumber=$idnum'</script>";
}

?>

<h1>Add Transcript</h1>
<p>
    <strong>Name:</strong> <?= getFullName($idnum) ?>
    will be inserted <?= $direction ?> <?= getTranscriptSemName($semId) ?>
</p>

<form action="" method="post">

    <table style="width: 100%">
        <tr>
            <td>
                <label for="sy">School Year: </label>
            </td>
            <td>
                <input type="text" name="sy" id="sy">
            </td>
            <td>
                <label for="school">Institution: </label>
            </td>
            <td>
                <input type="text" name="school" id="school">
            </td>
        </tr>
        <tr>
            <td>
                <label for="address">Address: </label>
            </td>
            <td>
                <input type="text" name="address" id="address">
            </td>
            <td>
                <label for="program">Program: </label>
            </td>
            <td>
                <input type="text" name="program" id="program">
            </td>
        </tr>
    </table>

    <hr>

    <table style="width: 100%">
        <thead>
            <tr>
                <th style="width: 25%">Course</th>
                <th>Description</th>
                <th style="width: 10%">Rating</th>
                <th style="width: 10%">Units</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0; $i<12; $i++): ?>
                <tr>
                    <td>
                        <input type="text" name="course[]" style="width: 100%">
                    </td>
                    <td>
                        <input type="text" name="description[]" style="width: 100%">
                    </td>
                    <td>
                        <input type="text" name="rating[]" style="width: 100%">
                    </td>
                    <td>
                        <input type="text" name="units[]" style="width: 100%">
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div class="right">
        <button type="button" onclick="window.location='index.php?page=transcript/transcript&idNumber=<?= $idnum ?>'">Cancel</button>
        <button type="submit" name="submit">Submit</button>
    </div>
</form>