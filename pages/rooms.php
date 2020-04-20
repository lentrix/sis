<?php
include("library/lister.php");
include("library/population.php");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<input type="image" src="images/printButton.png" class="noprint"
	onClick="printThisDiv('printFrame','printdiv');"
	style="float: right; margin-top: 0px; margin-right: 5px;display:screen;">
<iframe name="printFrame" id="printFrame" style="width:0px;height:0px;float:left; margin-left: -9999px"></iframe>
<div id="printdiv">
<h1>Rooms and Class Schedule</h1>
<form method="post" action="">
    <?php    CreateList("rm_no", "rm_no", "room", 'SELECT * FROM rooms ORDER BY room', ""); ?>
    <input type="submit" name="view_sched" value="View Class Schedule" />
</form>
<hr />

<?php
if(isset($_POST['view_sched'])){
    $sql = "SELECT CONCAT(ct.t_start, '-', ct.t_end,' ', day) AS 'time', s.name, s.descript, 
        CONCAT(t.lname, ', ', t.fname) AS 'teacher', c.class_code 
        FROM class c, class_time ct, subjects s, teacher t
        WHERE c.class_code=ct.class_code 
        AND c.sub_code=s.sub_code
        AND c.tch_num=t.tch_num
        AND ct.rm_no={$_POST['rm_no']}
        AND c.sem_code={$_SESSION['sem_code']}
        ORDER BY ct.day, ct.t_start, ct.t_end"; ?>
<table style="border-collapse: collapse; width: 100%">
    <tr>
        <th class="thead">Time</th>
        <th class="thead">Course No.</th>
        <th class="thead">Description</th>
        <th class="thead">Teacher</th>
        <th class="thead">Population</th>
    </tr>
    <?php $rs = mysql_query($sql); ?>
    <?php while($row=mysql_fetch_assoc($rs)) { ?>
    <tr>
        <td class="tcel"><?php echo $row['time']; ?></td>
        <td class="tcel"><?php echo $row['name']; ?></td>
        <td class="tcel"><?php echo $row['descript']; ?></td>
        <td class="tcel"><?php echo $row['teacher']; ?></td>
        <td class="tcel" align="center"><?php echo getClassPopulation($row['class_code']); ?></td>
    </tr>
    <?php } ?>
</table>
<?php } ?>
</div>