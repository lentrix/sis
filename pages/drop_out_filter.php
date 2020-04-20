<h1>Drop Out Filter</h1>
<form method="get" action="">
    <input type="hidden" name="page" value="drop_out_filter" />
    <select name="sem_code">
        <?php $sems = mysql_query("SELECT * FROM sems ORDER BY sem_code DESC"); ?>
        <?php while($sems_row = mysql_fetch_assoc($sems)) : ?>
        
        <option value="<?php echo $sems_row['sem_code']; ?>" 
            <?php if(isset($_REQUEST['sem_code'])) : ?>
                <?php if($_REQUEST['sem_code'] == $sems_row['sem_code']) : ?>
                selected
                <?php endif; ?>
            <?php endif; ?>
                >
            <?php echo $sems_row['sem']; ?>
        </option>
        
        <?php endwhile; ?>
    </select>
    <input type="submit" value="Show Dropped Students" name="show_dropped" />
</form>
<hr />
<input type="image" src="images/printButton.png" class="noprint"
	onClick="printThisDiv('print_frame','print_div');"
    style="float: right;" />
<iframe id="print_frame" style="float: left; margin-left: -9999px; width: 0px; height: 0px;"></iframe>
<?php if(isset($_REQUEST['show_dropped'])) : ?>
<div id="print_div">
    <?php $sem_code = $_REQUEST['sem_code']; ?>
    <?php $sem = mysql_query("SELECT sem FROM sems WHERE sem_code=" . $sem_code); ?>
    <?php $semrs = mysql_fetch_row($sem); ?>
    <h2>List of Dropped Students - <?php echo $semrs[0]; ?></h2>
    
    <?php $sql = "SELECT si.idnum, si.lname, si.fname, si.mi, c.cr_acrnm, se.year, COUNT(si.idnum) AS 'count' 
            FROM stud_info si, stud_enrol se, sub_enrol sb, courses c
            WHERE si.idnum=se.idnum AND si.idnum=sb.idnum
            AND se.sem_code = sb.sem_code
            AND c.cr_num=se.course
            AND sb.rating IN ('5.0', 'dr', 'DR', 'D')
            AND sb.sem_code = $sem_code
            GROUP BY si.lname, si.fname, si.mi
            ORDER BY count"; ?>
    <?php $rs = mysql_query($sql); ?>
<table>
    <tr>
        <th class="thead">#</th>
        <th class="thead">ID Number:</th>
        <th class="thead" width="150">Last Name:</th>
        <th class="thead" width="150">First Name:</th>
        <th class="thead">MI:</th>
        <th class="thead">Course:</th>
        <th class="thead">Year:</th>
        <th class="thead">Subjects:</th>
    </tr>
    <?php $n = 1; ?>
    <?php while($row = mysql_fetch_assoc($rs)) : ?>
        <?php if($row['count']>=3) : ?>
    <tr>
        <td class="tcel"><?php echo $n++; ?>.</td>
        <td class="tcel">
            <a href="index.php?page=student_grades&idnum=<?php echo $row['idnum']; ?>&sem_code=<?php echo $sem_code; ?>">
                <?php echo $row['idnum']; ?>
            </a>
        </td>
        <td class="tcel"><?php echo $row['lname']; ?></td>
        <td class="tcel"><?php echo $row['fname']; ?></td>
        <td class="tcel"><?php echo $row['mi']; ?></td>
        <td class="tcel"><?php echo $row['cr_acrnm']; ?></td>
        <td class="tcel"><?php echo $row['year']; ?></td>
        <td class="tcel"><?php echo $row['count']; ?></td>
    </tr>
        <?php endif; ?>
    <?php endwhile; ?>
</table>
</div>
<?php endif; ?>

