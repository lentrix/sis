<?php
$semId = $_GET['sem'];
$idnum = $_GET['idnum'];
$sem = $db->query("SELECT * FROM transcript_sem WHERE id=$semId")->fetch_object();

if (isset($_POST['grad'])) {
    $degree = $db->real_escape_string($_POST['degree']);
    $date = $db->real_escape_string($_POST['date']);
    $so = $db->real_escape_string($_POST['so']);
    $remarks = $db->real_escape_string($_POST['remarks']);

    $db->query("INSERT INTO transcript_grad_tag (`idnum`, `ordinal`, `degree`, `date`, `so`, `remarks`) VALUES (
        $idnum, $sem->ordinal, '$degree','$date','$so','$remarks'
    )");

    if (mysqli_error($db)) {
        die("Error:" . mysqli_error($db));
    }else {
        echo "<script>window.location='index.php?page=transcript/transcript&idNumber={$_GET['idnum']}'</script>";
    }

}

?>

<h1>Add Graduated Tag</h1>
<h2><?= $sem->sy ?></h2>

<form action="" method="post">

    <table style="width: 100%">
        <tr>
            <td style="width: 20%"><label for="degree">Degree: </label></td>
            <td style="width: 100%"><input type="text" name="degree" id="degree" style="width: 100%"></td>
        </tr>
        <tr>
            <td><label for="date">Date: </label></td>
            <td><input type="date" name="date" id="date" style="width: 100%"></td>
        </tr>
        <tr>
            <td><label for="so">Special Order: </label></td>
            <td><input type="text" name="so" id="so" style="width: 100%"></td>
        </tr>
        <tr>
            <td><label for="remarks">Remarks: </label></td>
            <td><input type="text" name="remarks" id="remarks" style="width: 100%"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right">
                <button type="button" onclick="window.location='/sis/index.php?page=transcript/transcript&idNumber=<?= $_GET['idnum'] ?>';">Cancel</button>
                <button type="submit" name="grad">Submit</button>
            </td>
        </tr>
    </table>

</form>