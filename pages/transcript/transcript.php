<?php
include("library/singles.php");
include("library/transcript_functions.php");

//move down
if (isset($_POST['moveDown'])) {
    moveDown($_POST['semId']);
}

//move Up
if (isset($_POST['moveUp'])) {
    moveUp($_POST['semId']);
}

//save details
if (isset($_POST['save_details'])) {
    $idnum = $_POST['idnum'];
    $place_of_birth = $_POST['place_of_birth'];
    $nationality = $_POST['nationality'];
    $guardians = $_POST['guardians'];
    $religion = $_POST['religion'];
    $gd_address = $_POST['gd_address'];
    $dt_admitted = $_POST['dt_admitted'];
    $entrance_data = $_POST['entrance_data'];
    $college = $_POST['college'];
    $orno = $_POST['orno'];
    $or_date = $_POST['or_date'];
    $remarks = $_POST['remarks'];
    $date_issued = $_POST['date_issued'];
    $elem = $_POST['elem'];
    $elem_sy = $_POST['elem_sy'];
    $secn = $_POST['secn'];
    $secn_sy = $_POST['secn_sy'];
    $tcry = $_POST['tcry'];
    $tcry_sy = $_POST['tcry_sy'];

    $year = date('Y');

    $db->query("UPDATE transcript_details SET
        place_of_birth='$place_of_birth', nationality='$nationality', guardians='$guardians',
        gd_address='$gd_address', dt_admitted='$dt_admitted',
        entrance_data='$entrance_data', college='$college', religion='$religion',
        orno='$orno', or_date='$or_date', remarks='$remarks',
        date_issued='$date_issued', elem='$elem', elem_sy='$elem_sy',
        secn='$secn', secn_sy='$secn_sy', tcry='$tcry', tcry_sy='$tcry_sy',
        revised=$year
        WHERE idnum=$idnum");

    if (mysqli_error($db)) {
        echo mysqli_error($db);
    }
}

?>

<h1>Transcript Generator</h1>

<?php if (!isset($_GET['idNumber'])) : ?>

    <form action="" method="get">
        <label for="idNumber">Enter ID Number</label>
        <input type="text" name="idNumber" id="idNumber">
        <input type="hidden" name="page" value="transcript/transcript">
        <button type="submit" name="submit">Get Transcript</button>
    </form>

<?php else : include("generator.php"); ?>
    <span style="float: right">
        <form action="/sis/pages/transcript/pdf.php" method="get" style="display: inline" target="_blank">
            <input type="hidden" name="idNumber" value="<?= $_GET['idNumber'] ?>">
            <button type="submit">PDF</button>
        </form>
        <button type="button" onClick="window.location='index.php?page=transcript/transcript'">X</button>
    </span>
    <div>
        <strong>Name</strong> <?= getFullName($id); ?>
    </div>

    <?php $sems = $db->query("SELECT * FROM transcript_sem WHERE idnum=$id ORDER BY ordinal"); ?>
    <?php while ($sem = mysqli_fetch_object($sems)) : ?>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th colspan="4" class="right" style="background-color:white; padding: 5px">
                        <button style="font-size: 12px" onclick="window.location='index.php?page=transcript/insert&direction=above&idnum=<?= $id ?>&sem=<?= $sem->id ?>'">+ Before</button>
                        <button style="font-size: 12px" onclick="window.location='index.php?page=transcript/insert&direction=below&idnum=<?= $id ?>&sem=<?= $sem->id ?>'">+ After</button>
                        <?php if ($sem->type == 'ext') : ?>
                            <form action="" method="post" style="display: inline">
                                <input type="hidden" name="semId" value="<?= $sem->id ?>">
                                <button style="font-size: 12px" name="moveDown" type="submit">Move Down</button>
                            </form>
                            <form action="" method="post" style="display: inline">
                                <input type="hidden" name="semId" value="<?= $sem->id ?>">
                                <button style="font-size: 12px" name="moveUp" type="submit">Move Up</button>
                            </form>

                            <button style="font-size: 12px" onclick="window.location='index.php?page=transcript/edit&sem=<?= $sem->id ?>'">Edit</button>
                            <button style="font-size: 12px" onclick="window.location='index.php?page=transcript/delete&sem=<?= $sem->id ?>'">Delete</button>
                        <?php endif; ?>
                    </th>
                </tr>
                <tr>
                    <th class="thead left" style="font-size: 1.1em" colspan="4"><?= $sem->sy ?>, <?= $sem->school ?>, <?= $sem->address ?> - <?= $sem->program ?></th>
                </tr>
                <tr>
                    <th class="thead left">Course No.:</th>
                    <th class="thead left">Description:</th>
                    <th class="thead">Rating:</th>
                    <th class="thead">Units:</th>
                </tr>
            </thead>
            <?php $rows = $db->query("SELECT * FROM transcript_row WHERE transcript_sem_id=$sem->id"); ?>
            <?php while ($row = mysqli_fetch_object($rows)) : ?>
                <tr>
                    <td class="tcel"><?= $row->course ?></td>
                    <td class="tcel"><?= $row->description ?></td>
                    <td class="tcel center"><?= $row->rating ?></td>
                    <td class="tcel center"><?= $row->units ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <hr>
    <?php endwhile; ?>
    <h4>Additional Information</h4>

    <?php
    //transcript_details
    $td = $db->query("SELECT * FROM transcript_details WHERE idnum={$_GET['idNumber']}")->fetch_object();
    ?>
    <form action="" method="post">
        <input type="hidden" name="idnum" value="<?= $_GET['idNumber'] ?>">
        <table>
            <tr>
                <td><label for="place_of_birth">Place of Birth:</label></td>
                <td colspan="3"><input type="text" name="place_of_birth" id="place_of_birth" style="width: 100%" value="<?= $td->place_of_birth ?>"></td>
            </tr>
            <tr>
                <td><label for="nationality">Nationality:</label></td>
                <td><input type="text" name="nationality" id="nationality" value="<?= $td->nationality ?>"></td>
                <td><label for="religion">Religion:</label></td>
                <td><input type="text" name="religion" id="religion" value="<?= $td->religion ?>"></td>
            </tr>
            <tr>
                <td><label for="guardians">Parents/Guardians:</label></td>
                <td colspan="3"><input type="text" name="guardians" id="guardians" style="width: 100%" value="<?= $td->guardians ?>"></td>
            </tr>
            <tr>
                <td style="text-align: right"><label for="gd_address">Address:</label></td>
                <td colspan="3"><input type="text" name="gd_address" id="gd_address" style="width: 100%" value="<?= $td->gd_address ?>"></td>
            </tr>
            <tr>
                <td><label for="dt_admitted">Date Admitted:</label></td>
                <td><input type="date" name="dt_admitted" id="dt_admitted" value="<?= $td->dt_admitted ?>"></td>
                <td><label for="entrance_data">Entrance Data:</label></td>
                <td><input type="text" name="entrance_data" id="entrance_data" value="<?= $td->entrance_data ?>"></td>
            </tr>
            <tr>
                <td><label for="clg">College:</label></td>
                <td colspan="3"><input type="text" name="college" id="clg" style="width: 100%" value="<?= $td->college ?>"></td>
            </tr>
            <tr>
                <td><label for="orno">OR. NO.:</label></td>
                <td><input type="text" name="orno" id="orno" value="<?= $td->orno ?>"></td>
                <td><label for="or_date">Date Issued (OR):</label></td>
                <td><input type="date" name="or_date" id="or_date" value="<?= $td->or_date ?>"></td>
            </tr>
            <tr>
                <td><label for="remarks">Remarks</label></td>
                <td><input type="text" name="remarks" id="remarks" value="<?= $td->remarks ?>"></td>
                <td><label for="date">Date Issued (Transcript):</label></td>
                <td><input type="date" name="date_issued" id="date" value="<?= $td->date_issued ?>"></td>
            </tr>
            <tr>
                <td><label for="elem">Elementary:</label></td>
                <td><input type="text" name="elem" id="elem" value="<?= $td->elem ?>"></td>
                <td><label for="elem_sy">School Year:</label></td>
                <td><input type="text" name="elem_sy" id="elem_sy" value="<?= $td->elem_sy ?>"></td>
            </tr>
            <tr>
                <td><label for="secn">Secondary:</label></td>
                <td><input type="text" name="secn" id="secn" value="<?= $td->secn ?>"></td>
                <td><label for="secn_sy">School Year:</label></td>
                <td><input type="text" name="secn_sy" id="secn_sy" value="<?= $td->secn_sy ?>"></td>
            </tr>
            <tr>
                <td><label for="tcry">Tertiary:</label></td>
                <td><input type="text" name="tcry" id="tcry" value="<?= $td->tcry ?>"></td>
                <td><label for="tcry_sy">School Year:</label></td>
                <td><input type="text" name="tcry_sy" id="tcry_sy" value="<?= $td->tcry_sy ?>"></td>
            </tr>
        </table>
        <br>
        <button type="submit" name="save_details">Save Details</button>
    </form>
<?php endif; ?>