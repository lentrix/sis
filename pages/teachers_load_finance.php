<?php include("library/population.php"); ?>
<?php include("library/singles.php"); ?>
<h1>Teacher's Load</h1>

<?php $sql = "SELECT teacher.fname, teacher.lname, class_code, `name`, descript, cunits, punits
	 FROM class, teacher, subjects
	WHERE class.tch_num=teacher.tch_num AND class.sub_code=subjects.sub_code 
    AND sem_code={$_SESSION['sem_code']} ORDER BY lname, fname, `name`"; ?>
    
<?php $list = mysqli_query($db, $sql); ?>

<table style="border-collapse: collapse; width: 100%">
<tr>
    <td class="thead" width="20%">Instructor:</td>
    <td class="thead" width="10%">Course No.:</td>
    <td class="thead" width="20%">Description:</td>
    <td class="thead" width="20%">Time/Room:</td>
    <td class="thead" width="5%">Cr Unit:</td>
    <td class="thead" width="5%">Pay Unit:</td>
    <td class="thead" width="5%">Num:</td>
</tr>
<?php while($row=mysqli_fetch_assoc($list)) : ?>
<tr>
    <td class="tcel" width="20%"><?php echo $row['lname'] . ', ' . $row['fname'];?></td>
    <td class="tcel" width="10%"><?php echo $row['name'];?></td>
    <td class="tcel"><?php echo $row['descript']; ?></td>
    <td class="tcel" width="20%"><?php echo getClassTimeRoom($row['class_code'], true);;?></td>
    <td class="tcel" width="5%"><?php echo $row['cunits'];?></td>
    <td class="tcel" width="5%"><?php echo $row['punits'];?></td>
    <td class="tcel" width="5%"><?php echo getClassPopulation($row['class_code']); ?></td>
</tr>
<?php endwhile; ?>
</table>