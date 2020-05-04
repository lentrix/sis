<?php
include("library/alpha_page.php");
include("library/population.php");
include("library/singles.php");
?>

<h1>Class Density</h1>

<div style="width:620px; margin-left: auto; margin-right:auto; background: #FFF;">
<?php showPagination("index.php?page=class_density"); ?>
    <a href="index.php?page=class_density&alphapage=allreg">View All</a>
</div>

<?php
if(isset($_GET['alphapage'])) {
    if($_GET['alphapage']=="all") {
		$alpha_page = "AND class.clg_no={$_SESSION['clg_no']} ";
    }else if($_GET['alphapage']=="allreg"){
	    $alpha_page = "";
    }else{
	    $alpha_page = " AND name LIKE '{$_GET['alphapage']}%' ";
    }
}else $alpha_page="AND name LIKE 'A%' ";
$sequel = "SELECT class_code, name, descript, cunits, punits,
	CONCAT(fname,' ',lname) AS 'teacher' FROM class, teacher, subjects
	WHERE class.tch_num=teacher.tch_num AND class.sub_code=subjects.sub_code
	$alpha_page AND sem_code={$_SESSION['sem_code']} ORDER BY name";

$cls = mysqli_query($db, $sequel);
?>

<table style="border-collapse: collapse; width: 100%">
<tr>
	<td class="thead" width="10%">Code:</td>
	<td class="thead" width="10%">Course No.:</td>
	<td class="thead" width="20%">Description:</td>
	<td class="thead" width="20%">Time/Room:</td>
	<td class="thead" width="5%">Cr Unit:</td>
	<td class="thead" width="5%">Pay Unit:</td>
	<td class="thead" width="5%">Num:</td>
    <td class="thead" width="20%">Instructor:</td>
</tr>

<?php while($row = mysqli_fetch_assoc($cls)) { ?>
<tr>
	<td class="tcel" width="10%"><a href="index.php?page=view_class&class_code=<?php echo $row['class_code'];?>" 
        	style="font-size: 9pt; text-decoration: none;font-weight:bold"><?php echo $row['class_code'];?></a></td>
	<td class="tcel" width="10%"><?php echo $row['name'];?></td>
	<td class="tcel"><?php echo $row['descript']; ?></td>
	<td class="tcel" width="20%"><?php echo getClassTimeRoom($row['class_code'], true);;?></td>
	<td class="tcel" width="5%"><?php echo $row['cunits'];?></td>
	<td class="tcel" width="5%"><?php echo $row['punits'];?></td>
	<td class="tcel" width="5%"><?php echo getClassPopulation($row['class_code']); ?></td>
    <td class="tcel" width="20%"><?php echo $row['teacher'];?></td>
</tr>

<?php } ?>
</table>
