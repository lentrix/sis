<?php
include("library/population.php");
include("library/singles.php");
include("library/classlist.php");

?>

<h1>Teaching Load</h1>
<?php $tch_list = mysql_query("SELECT DISTINCT teacher.tch_num, lname, fname, mi FROM teacher
LEFT JOIN class ON class.tch_num=teacher.tch_num
WHERE class.sem_code={$_SESSION['sem_code']}
AND (lname <> '' OR fname <> '')
ORDER BY lname, fname;");
?>

<form method="post" action="">
    <label for="tch_num">Select Teacher</label>
    <select name="tch_num" id="tch_num">
        <option value=""></option>
        <?php while($row = mysql_fetch_assoc($tch_list)) : ?>
            <option value="<?= $row['tch_num'] ?>" <?= ( isset($_POST['tch_num']) && $_POST['tch_num']==$row['tch_num']) ? "selected": "" ?>>
                <?= $row['lname'] . ", " . $row['fname'] . " " . $row['mi'] ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Go</button>
</form>
<hr>

<?php if(isset($_POST['tch_num'])) : ?>


<?php
$stm = "SELECT class_code, name, descript, sub_dt FROM subjects, class
        WHERE class.sub_code = subjects.sub_code
        AND tch_num={$_POST['tch_num']}
        AND sem_code={$_SESSION['sem_code']}";
$tc_class = mysql_query($stm);
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "<br/>SQL: " . $stm . "</div>";
	$n=1;
?>
<h2 style="width:400px;">Teaching Load</h2>
<p style="margin-top: 0px">(<?php echo getTeacherFullName($_POST['tch_num']); ?>)<br/ >
	<?php getSemester($_SESSION['sem_code']); ?>
</p>

<table>
	<tr>
		<td class="thead" width="30">#</td>
		<td class="thead" width="70">Name:</td>
		<td class="thead" width="280">Description:</td>
		<td class="thead" width="150">Time/Room:</td>
		<td class="thead" width="40">Num:</td>
                <td class="thead" width="50">Submitted:</td>
	</tr>
<?php while($tcrow=mysql_fetch_assoc($tc_class)) { ?>
<?php 		$time = getClassTimeRoom($tcrow['class_code'],true); ?>
	<tr>
            <td class="tcel"><?php echo $n++ . "."; ?></td>
            <td class="tcel">
                    <a href="index.php?page=teacher_classes&class_code=<?php echo $tcrow['class_code']; ?>"
                            style="font-size: 9pt;">
                    <?php echo $tcrow['name']; ?>
                    </a>
            </td>
            <td class="tcel"><?php echo $tcrow['descript'];?></td>
            <td class="tcel"><?php echo $time; ?></td>
            <td class="tcel" align="center"><?php echo getClassPopulation($tcrow['class_code']);?></td>
            <td class="tcel"><?php echo ($tcrow['sub_dt']) ? date('M-d-y', strtotime($tcrow['sub_dt'])) : "not submitted"; ?></td>
	</tr>
<?php } ?>
<tr><td colspan="5" class="tfoot">&nbsp;</td></tr>
</table>



<?php endif; ?>