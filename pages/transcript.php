<?php 

include("library/singles.php");

?>


<h1>Transcript Generator</h1>

<?php if(!isset($_POST['submit'])): ?>

<form action="" method="post">
    <label for="idNumber">Enter ID Number</label>
    <input type="text" name="idNumber" id="idNumber">
    <button type="submit" name="submit">Get Transcript</button>
</form>

<?php else : include("transcript_generator.php"); ?>
    <span style="float: right">
        <form action="" method="post">
            <button type="submit">X</button>
        </form>
    </span>
    <div>
        <strong>Name</strong> <?= getFullName($id); ?>
    </div>

    <?php $sems = $db->query("SELECT * FROM transcript_sem WHERE idnum=$id ORDER BY ordinal"); ?>
    <?php while($sem = mysqli_fetch_object($sems)) : ?>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th colspan="4" class="right">
                        <button style="font-size: 0.9em">Insert Before</button>
                        <button style="font-size: 0.9em">Insert After</button>
                        <button style="font-size: 0.9em">Move Down</button>
                        <button style="font-size: 0.9em">Move Up</button>
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
            <?php while($row = mysqli_fetch_object($rows)) : ?>
                <tr>
                    <td class="tcel"><?= $row->course ?></td>
                    <td class="tcel"><?= $row->description ?></td>
                    <td class="tcel center"><?= $row->rating ?></td>
                    <td class="tcel center"><?= $row->units ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endwhile; ?>
<?php endif; ?>