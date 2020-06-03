<?php

include("library/singles.php");

if (isset($_GET['sem'])) :
    $semId = $_GET['sem'];

    $sem = $db->query("SELECT * FROM transcript_sem
            WHERE id=$semId")->fetch_object();

    $rows = $db->query("SELECT * FROM transcript_row
            WHERE transcript_sem_id=$semId")->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['submit'])) {

        $sy = $db->real_escape_string($_POST['sy']);
        $school = $db->real_escape_string($_POST['school']);
        $address = $db->real_escape_string($_POST['address']);
        $program = $db->real_escape_string($_POST['program']);

        $db->query("UPDATE transcript_sem
                SET sy='$sy', school='$school', address='$address', program='$program'
                WHERE id=$semId");

        if (mysqli_error($db)) {
            die(mysqli_error($db));
        }

        for ($i = 0; $i < 15; $i++) {
            $rowId = $_POST['rowId'][$i];
            $course = $db->real_escape_string($_POST['course'][$i]);
            $description = $db->real_escape_string($_POST['description'][$i]);
            $rating = $db->real_escape_string($_POST['rating'][$i]);
            $units = $db->real_escape_string($_POST['units'][$i]);

            if (!$course && $rowId) {
                $db->query("DELETE FROM transcript_row WHERE id=$rowId");
            } else if ($course && $rowId) {
                $db->query("UPDATE transcript_row SET course='$course', description='$description', rating='$rating', units='$units'
                                WHERE id=$rowId");
            } else if ($course && !$rowId) {
                $db->query("INSERT INTO transcript_row (`course`, `description`, `rating`, `units`, `transcript_sem_id`) VALUES (
                            '$course','$description','$rating','$units',$semId
                        )");
            }

            if (mysqli_error($db)) {
                die(mysqli_error($db));
            }
        }

        echo "<script>window.location='index.php?page=transcript/transcript&idNumber=$sem->idnum';</script>";
    }
?>

    <h1>Edit Transcript</h1>
    <p>
        <strong>Name:</strong> <?= getFullName($sem->idnum) ?>
    </p>

    <form action="" method="post">

        <table style="width: 100%">
            <tr>
                <td>
                    <label for="sy">School Year: </label>
                </td>
                <td>
                    <input type="text" name="sy" id="sy" value="<?= $sem->sy ?>">
                </td>
                <td>
                    <label for="school">Institution: </label>
                </td>
                <td>
                    <input type="text" name="school" id="school" value="<?= $sem->school ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="address">Address: </label>
                </td>
                <td>
                    <input type="text" name="address" id="address" value="<?= $sem->address ?>">
                </td>
                <td>
                    <label for="program">Program: </label>
                </td>
                <td>
                    <input type="text" name="program" id="program" value="<?= $sem->program ?>">
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
                <?php for ($i = 0; $i < 15; $i++) : ?>
                    <input type="hidden" name="rowId[]" value="<?= isset($rows[$i]) ? $rows[$i]['id'] : '' ?>">
                    <tr>
                        <td>
                            <input type="text" name="course[]" style="width: 100%" value="<?= isset($rows[$i]) ? $rows[$i]['course'] : '' ?>">
                        </td>
                        <td>
                            <input type="text" name="description[]" style="width: 100%" value="<?= isset($rows[$i]) ? $rows[$i]['description'] : '' ?>">
                        </td>
                        <td>
                            <input type="text" name="rating[]" style="width: 100%" value="<?= isset($rows[$i]) ? $rows[$i]['rating'] : '' ?>">
                        </td>
                        <td>
                            <input type="text" name="units[]" style="width: 100%" value="<?= isset($rows[$i]) ? $rows[$i]['units'] : '' ?>">
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="right">
            <button type="button" onclick="window.location='index.php?page=transcript/transcript&idNumber=<?= $sem->idnum ?>'">Cancel</button>
            <button type="submit" name="submit">Submit</button>
        </div>
    </form>

<?php else : ?>

    Invalid Access! Missing Transcript Sem ID.

<?php endif; ?>