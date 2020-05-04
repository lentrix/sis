<h1>Faculty Report</h1>
<?php
$fac = mysqli_query($db, "SELECT tch_num, lname,fname,mi 
					FROM teacher WHERE tch_num IN 
					(SELECT tch_num FROM class 
					WHERE sem_code={$_SESSION['sem_code']}) ORDER BY lname, fname");
$n=0;
?>
<table border="1" width="100%">
<tr>
	<th width="50">No.</th>
    <th width="150">Last Name:</th>
    <th width="150">First Name:</th>
    <th width="50">MI:</th>
    <th>Subjects:</th>
</tr>
<?php while($facr=mysqli_fetch_assoc($fac)) { ?>
<tr>
	<td style="font-size: 13px;"><?php echo ++$n; ?></td>
    <td style="font-size: 13px;"><?php echo $facr['lname'];?></td>
    <td style="font-size: 13px;"><?php echo $facr['fname'];?></td>
    <td style="font-size: 13px;"><?php echo $facr['mi'];?></td>
    <td style="font-size: 13px;">
    	<?php $sb = mysqli_query($db, "SELECT name FROM subjects s, class c
								WHERE c.sub_code=s.sub_code
								AND c.sem_code={$_SESSION['sem_code']}
								AND c.tch_num={$facr['tch_num']}"); ?>
        <?php while($sbr=mysqli_fetch_row($sb)) { ?>
        	<?php echo $sbr[0] . ", "; ?>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>
