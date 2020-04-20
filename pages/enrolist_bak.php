<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>

<h1>Enrolment List</h1>
<form method="post" action="">
	Program <?php CreateList("cr_num","cr_num","cr_acrnm",
							 "SELECT cr_num, cr_acrnm FROM courses ORDER BY cr_acrnm","",""); ?>
    Year level: <select name="year">
    	<option></option>
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>Q</option>
    </select>
    <input type="submit" name="submit_enrolist">
</form>
<hr />

<div>
<?php
if(isset($_POST['submit_enrolist'])) {
?>
	<h2>Enrolment List for <?php echo getCourseName($_POST['cr_num']) . " - " . $_POST['year'];?></h2>
	<table>
	<tr>
    	<th width="50">ID No.</th>
        <th width="125">Last Name</th>
        <th width="125">First Name</th>
        <th width="30">MI</th>
        <th width="150">Courses</th>
        <th width="50">Units</th>
    </tr>
<?php
	$studs = mysql_query("SELECT s.idnum, s.lname, s.fname, mi FROM stud_info s, stud_enrol en
			WHERE s.idnum=en.idnum AND en.course={$_POST['cr_num']} AND year='{$_POST['year']}'
			 AND en.sem_code={$_SESSION['sem_code']} ORDER BY lname, fname, mi");

	while($str=mysql_fetch_assoc($studs)) { 
?>
		<tr>
        	<td><?php echo $str['idnum'];?></td>
        	<td><?php echo $str['lname'] . ",";?></td>
            <td><?php echo $str['fname'];?></td>
            <td><?php echo $str['mi'];?></td>
<?php
		$sub = mysql_query("SELECT descript, cunits FROM subjects s, sub_enrol se, class c
						   WHERE c.class_code=se.class_code AND c.sub_code = s.sub_code
						   AND se.idnum={$str['idnum']} AND se.sem_code={$_SESSION['sem_code']}"); 
		$subr=mysql_fetch_assoc($sub);
?>
			<td><?php echo $subr['descript']?></td>
            <td><?php echo $subr['cunits'];?></td>
        </tr>
<?php
		while($subr=mysql_fetch_assoc($sub)){ 
?>
		<tr>
        	<td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $subr['descript'];?></td>
            <td><?php echo $subr['cunits'];?></td>
        </tr>
<?php
		}
		echo "<tr><td colspan='6'>&nbsp;</td></tr>";
	}
?>
	</table>	
<?php
}
?>
</div>
