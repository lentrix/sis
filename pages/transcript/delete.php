<?php

include("library/singles.php");

if (isset($_GET['sem'])) :

    $semId = $_GET['sem'];
    $sem = $db->query("SELECT * FROM transcript_sem WHERE id=$semId")->fetch_object();

    $rows = $db->query("SELECT * FROM transcript_row WHERE transcript_sem_id=$semId")->fetch_all(MYSQLI_ASSOC);

    if(isset($_POST['delete'])) {

        $db->query("DELETE FROM transcript_sem WHERE id=$semId");

        echo "<script>window.location='index.php?page=transcript/transcript&idNumber=$sem->idnum'</script>";

    }
?>

    <h1>Delete Transcript</h1>
    <p>
        <strong>Name:</strong> <?= getFullName($sem->idnum); ?> <br>
        <?= $sem->sy ?>
    </p>

    <table style="width: 100%">
        <tr>
            <th class="thead left">Course No.:</th>
            <th class="thead left">Description:</th>
            <th class="thead">Rating:</th>
            <th class="thead">Units:</th>
        </tr>
        <?php foreach ($rows as $row) : ?>

            <tr>
                <td class="tcel"><?= $row['course'] ?></td>
                <td class="tcel"><?= $row['description'] ?></td>
                <td class="tcel"><?= $row['rating'] ?></td>
                <td class="tcel"><?= $row['units'] ?></td>
            </tr>

        <?php endforeach; ?>
    </table>

    <p>
        <form action="" method="post">
            Are you sure about deleting this transcript semester? <br><br>
            <button type="button" onclick="window.location='index.php?page=transcript/transcript&idNumber=<?= $sem->idnum ?>';">Cancel</button>
            <button type="submit" name="delete"> Yes </button>
        </form>
    </p>

<?php endif; ?>