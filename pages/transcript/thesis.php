<?php
$semId = $_GET['sem'];
$idnum = $_GET['idnum'];
$sem = $db->query("SELECT * FROM transcript_sem WHERE id=$semId")->fetch_object();

if (isset($_POST['thesis'])) {
    $title = $db->real_escape_string($_POST['title']);
    $remarks = $db->real_escape_string($_POST['remarks']);
    
    $db->query("INSERT INTO transcript_thesis_tag (`idnum`,`ordinal`,`title`, `remarks`) VALUES (
        $idnum, $sem->ordinal, '$title', '$remarks'
    )");

    if (mysqli_error($db)) {
        die("Error:" . mysqli_error($db));
    }else {
        echo "<script>window.location='index.php?page=transcript/transcript&idNumber={$_GET['idnum']}'</script>";
    }

}

?>

<h1>Add Thesis Tag</h1>
<h2><?= $sem->sy ?></h2>

<form action="" method="post">

    <table style="width: 100%">
        <tr>
            <td style="width: 20%"><label for="title">Title: </label></td>
            <td style="width: 100%"><input type="text" name="title" id="thesis-title" style="width: 100%"></td>
        </tr>
        <tr>
            <td><label for="remarks">Remarks: </label></td>
            <td><input type="text" name="remarks" id="remarks" style="width: 100%"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right">
                <button type="button" onclick="window.location='/sis/index.php?page=transcript/transcript&idNumber=<?= $_GET['idnum'] ?>';">Cancel</button>
                <button type="submit" name="thesis">Submit</button>
            </td>
        </tr>
    </table>

</form>