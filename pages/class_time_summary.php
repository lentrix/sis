<?php include("library/population.php"); ?>
<h1>Class Time Summary</h1>

<?php $tms = mysql_query("SELECT DISTINCT t_end, t_start, day 
    FROM class_time 
    WHERE class_code IN (SELECT class_code FROM class WHERE sem_code={$_SESSION['sem_code']})
    ORDER BY day, t_start" ); ?>
<?php while($tmsr = mysql_fetch_assoc($tms)) : ?>
    <?php $sql = "SELECT c.class_code, s.name, s.descript, rm.room, CONCAT(t.lname,', ',t.fname) AS 'teacher' 
    FROM class c, class_time ct, subjects s, rooms rm, teacher t
    WHERE c.class_code = ct.class_code
    AND s.sub_code=c.sub_code
    AND ct.rm_no=rm.rm_no
    AND c.sem_code = {$_SESSION['sem_code']}
    AND t.tch_num=c.tch_num ";
    if($tmsr['t_start']) $sql .= " AND t_start='" . $tmsr['t_start'] . "' ";
    if($tmsr['t_end']) $sql .= " AND t_end='" . $tmsr['t_end'] . "' ";
    if($tmsr['day']) $sql .= " AND day='" . $tmsr['day'] . "'"; ?>
    
    <?php $cls = mysql_query($sql);?>
    
    <h2><?php echo $tmsr['t_start'] . '-' . $tmsr['t_end'] . ' ' . $tmsr['day']; ?></h2>
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
<?php endwhile; ?>
