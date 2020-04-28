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

?>


<h1>Transcript Generator</h1>

<?php if (!isset($_GET['idNumber'])) : ?>

    <form action="" method="get">
        <label for="idNumber">Enter ID Number</label>
        <input type="text" name="idNumber" id="idNumber">
        <input type="hidden" name="page" value="transcript">
        <button type="submit" name="submit">Get Transcript</button>
    </form>

<?php else : include("transcript_generator.php"); ?>
    <span style="float: right">
        <button type="button" onClick="window.location='index.php?page=transcript'">X</button>
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
                        <button style="font-size: 12px" onclick="window.location='index.php?page=transcript_insert&direction=above&idnum=<?= $id ?>&sem=<?= $sem->id ?>'">Insert Before</button>
                        <button style="font-size: 12px" onclick="window.location='index.php?page=transcript_insert&direction=below&idnum=<?= $id ?>&sem=<?= $sem->id ?>'">Insert After</button>
                        <form action="" method="post" style="display: inline">
                            <input type="hidden" name="semId" value="<?= $sem->id ?>">
                            <button style="font-size: 12px" name="moveDown" type="submit">Move Down</button>
                        </form>
                        <form action="" method="post" style="display: inline">
                            <input type="hidden" name="semId" value="<?= $sem->id ?>">
                            <button style="font-size: 12px" name="moveUp" type="submit">Move Up</button>
                        </form>
                        <?php if ($sem->type == 'ext') : ?>
                            <button style="font-size: 12px" onclick="window.location='index.php?page=transcript_edit&sem=<?= $sem->id ?>'">Edit</button>
                            <button style="font-size: 12px" onclick="window.location='index.php?page=transcript_delete&sem=<?= $sem->id ?>'">Delete</button>
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

    <form action="pages/transcript_pdf.php" method="get" target="_blank">
        <input type="hidden" name="idnum" value="<?= $_GET['idNumber'] ?>">
        <table>
            <tr>
                <td><label for="orno">OR. NO.:</label></td>
                <td><input type="text" name="orno" id="orno"></td>
                <td><label for="ordate">Date Issued (OR):</label></td>
                <td><input type="text" name="ordate" id="ordate"></td>
            </tr>
            <tr>
                <td><label for="remarks">Remarks</label></td>
                <td><input type="text" name="remarks" id="remarks"></td>
                <td><label for="date">Date Issued (Transcript):</label></td>
                <td><input type="text" name="date" id="date"></td>
            </tr>
        </table>
        <br>
        <button type="submit" name="generate">Generate PDF</button>
    </form>
<?php endif; ?>