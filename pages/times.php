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
<h1>Times and Class Schedule</h1>
<form method="get" action="">
    <input type="hidden" name="page" value="times" />
    <label><strong>Start Time</strong>
        <?php CreateList('t_start','t_start', 't_start', "SELECT DISTINCT t_start FROM class_time ORDER BY t_start",""); ?>
    </label>
    <label><strong>End Time</strong>
        <?php CreateList('t_end','t_end', 't_end', "SELECT DISTINCT t_end FROM class_time ORDER BY t_end",""); ?>
    </label>
    <label><strong>Day</strong>
        <?php CreateList('day','day', 'day', "SELECT DISTINCT day FROM class_time ORDER BY day",""); ?>
    </label>
    <input type="submit" name="submit" value="View Classes" />
    <input type="button" value="View All" onclick="document.location='index.php?page=class_time_summary'" />
</form>

<hr />

<?php if(isset($_REQUEST['submit'])) : ?>
    <?php $sql = "SELECT c.class_code, s.name, s.descript, rm.room, CONCAT(t.lname,', ',t.fname) AS 'teacher' 
    FROM class c, class_time ct, subjects s, rooms rm, teacher t
    WHERE c.class_code = ct.class_code
    AND s.sub_code=c.sub_code
    AND ct.rm_no=rm.rm_no
    AND c.sem_code = {$_SESSION['sem_code']}
    AND t.tch_num=c.tch_num ";
    if($_REQUEST['t_start']) $sql .= " AND t_start='" . $_REQUEST['t_start'] . "' ";
    if($_REQUEST['t_end']) $sql .= " AND t_end='" . $_REQUEST['t_end'] . "' ";
    if($_REQUEST['day']) $sql .= " AND day='" . $_REQUEST['day'] . "'"; ?>
    
    <table style="border-collapse: collapse; width: 100%">
        <tr>
            <th class="thead" width="100">Subject</th>
            <th class="thead" width="300">Description</th>
            <th class="thead" width="50">Room</th>
            <th class="thead" width="50">Num</th>
            <th class="thead" width="150">Teacher</th>
        </tr>
    <?php $sb = mysql_query($sql); ?>
    <?php while($sbr = mysql_fetch_assoc($sb)) : ?>
        <tr>
            <td class="tcel"><a href="index.php?page=view_class&class_code=<?php echo $sbr['class_code']; ?>"><?php echo $sbr['name']; ?></a></td>
            <td class="tcel"><?php echo $sbr['descript']; ?></td>
            <td class="tcel"><?php echo $sbr['room']; ?></td>
            <td class="tcel"><?php echo getClassPopulation($sbr['class_code']); ?></td>
            <td class="tcel"><?php echo $sbr['teacher']; ?></td>
        </tr>
    <?php endwhile; ?>
    </table>
<?php endif; ?>
</div>