<?php
function getWeightedAverage($idnum, $sem_code){
	global $db;
	$grd = mysqli_query($db, "SELECT rating, cunits FROM sub_enrol se, class c
		WHERE se.class_code = c.class_code AND idnum=$idnum AND se.sem_code=$sem_code");
	echo mysqli_error($db);
	$ws = 0;
	$wt = 0;

	while($grdr = mysqli_fetch_assoc($grd)) {
		$exclude = array("","NaN","-",NULL,"DR","wd","NG","ng","dr","WD","NULL");
		foreach($exclude as $exc){
			if($grdr['rating']==$exc) return 0;
		}
		if($grdr['rating']>=2.20) return 0;
		$ws += ($grdr['rating']*$grdr['cunits']);
		$wt += $grdr['cunits'];
	}
	if($wt<15) return 0;
	return ($ws/$wt);
}

include("library/student_grade.php");
?>

<script type="text/javascript">
function showGrade(idnum){
	box = document.getElementById('stbox_'+idnum).style.display='block';

}
</script>

<h1>Deans List Generator</h1>

<?php
$st = mysqli_query($db, "SELECT si.idnum, CONCAT(lname,', ', fname,' ', mi) as 'name', cr_acrnm, year FROM stud_info si, stud_enrol se, courses c
			WHERE si.idnum=se.idnum AND se.course=c.cr_num AND se.sem_code={$_SESSION['sem_code']} AND c.clg_no={$_SESSION['clg_no']}
			ORDER BY lname, fname");
$discarded = array();
?>
<table style="border-collapse: collapse" border="1" cellpadding="5">
	<tr>
		<th width="30">#</th>
		<th width="80">ID Number:</th>
		<th width="250">Name:</th>
		<th width="80">Course &amp; Year:</th>
		<th width="80">Weighted Ave:</th>
	</tr>
<?php $n=0; ?>
<?php while($str = mysqli_fetch_assoc($st)) { ?>
	<?php $wtave = getWeightedAverage($str['idnum'], $_SESSION['sem_code']); ?>

	<?php if($wtave) { ?>
		<?php if($wtave<=1.60) { ?>
		<td><?php echo ++$n; ?></td>
		<td>
			<a href="javascript:void(0);" onclick="showGrade('<?php echo $str['idnum'];?>');"><?php echo $str['idnum'];?></a>
			<div style="width: 600px; min-height: 300px; background: #222288; position: absolute; display: none; padding: 20px;"
					id="stbox_<?php echo $str['idnum']; ?>">
				<div style="text-align: right; border: 0px;"><a href="javascript:void(0)" style="color: #ddddff"
						onclick="document.getElementById('stbox_<?php echo $str['idnum']; ?>').style.display='none';">Close</a></div>
				<div id="stgrade_<?php echo $str['idnum'];?>" style="border: 0px; background: #ffffff">
					<?php showStudentGrade($str['idnum'], $_SESSION['sem_code']); ?>
				</div>
			</div>
		</td>
		<td><?php echo $str['name'];?></td>
		<td><?php echo $str['cr_acrnm']."-".$str['year'];?></td>
		<td><?php echo $wtave;?></td>
	</tr>
		<?php } ?>
	<?php } else { ?>
		<?php $discarded[] = array("idnum"=>$str['idnum'], "name"=>$str['name'], "course_year"=>$str['cr_acrnm']."-".$str['year']); ?>
	<?php } ?>
<?php } ?>
</table>

<h2>Discarded Students</h2>
<table style="border-collapse: collapse" border="1" cellpadding="5">
	<tr>
		<th width="30">#</th>
		<th width="80">ID Number:</th>
		<th width="250">Name:</th>
		<th width="80">Course &amp; Year:</th>
	</tr>
<?php $n=1; ?>
<?php foreach($discarded as $dsub) { ?>
	<tr>
		<td><?php echo $n++; ?></td>
		<td>
			<a href="javascript:void(0);" onclick="showGrade('<?php echo $dsub['idnum'];?>');"><?php echo $dsub['idnum'];?></a>
			<div style="width: 600px; min-height: 300px; background: #222288; position: absolute; display: none; padding: 20px;"
					id="stbox_<?php echo $dsub['idnum']; ?>">
				<div style="text-align: right; border: 0px;"><a href="javascript:void(0)" style="color: #ddddff"
						onclick="document.getElementById('stbox_<?php echo $dsub['idnum']; ?>').style.display='none';">Close</a></div>
				<div id="stgrade_<?php echo $dsub['idnum'];?>" style="border: 0px; background: #ffffff">
					<?php showStudentGrade($dsub['idnum'], $_SESSION['sem_code']); ?>
				</div>
			</div>
		</td>
		<td><?php echo $dsub['name'];?></td>
		<td><?php echo $dsub['course_year'];?></td>

	</tr>
<?php } ?>
</table>
