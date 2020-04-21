<?php function showClassList($class_code){ ?>

<table>
	<tr>
		<td class="thead" width="50" align="center">#</td>
		<td class="thead" width="100">ID Number:</td>
		<td class="thead" width="300">Name:</td>
		<td class="thead" width="150">Course & Year:</td>
	</tr>
<?php
$class_list = mysqli_query($db, "SELECT idext, stud_enrol.idnum, 
			CONCAT(lname,', ',fname,' ',mi) AS 'name', 
			cr_acrnm, year FROM stud_info, stud_enrol, courses 
			WHERE stud_enrol.idnum = stud_info.idnum 
			AND courses.cr_num=stud_enrol.course
			AND sem_code={$_SESSION['sem_code']}
			AND stud_enrol.idnum IN (SELECT sub_enrol.idnum FROM sub_enrol 
				WHERE sub_enrol.class_code=$class_code AND (rating is null or rating<>'W'))
			ORDER BY lname, fname");
$n=1;

while($cls=mysqli_fetch_assoc($class_list)) { ?>
	<tr>
		<td class="tcel"><?php echo $n++ . "."; ?></td>
		<td class="tcel"><a href="index.php?page=student_subjects&idnum=<?php echo $cls['idnum'];?>"
        		style="font-size: 9pt; font-weight: bold; text-decoration: none;">
			<?php echo $cls['idnum'] . "-" . $cls['idext']; ?></a></td>
		<td class="tcel"><?php echo $cls['name']; ?></td>
		<td class="tcel" style="text-align: center"><?php echo $cls['cr_acrnm'] . "-" . $cls['year']; ?></td>
	</tr>
<?php } ?>
<tr><td colspan="4" class="tfoot">&nbsp;</td></tr>
</table>

<?php } ?>
