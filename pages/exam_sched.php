<?php function subjectsTable() { ?>
	<table style="border-collapse:collapse" border="1">
	<?php foreach($_SESSION['raw_subjects'] as $sub_code=>$subject) { ?>
		<tr style="background: #fff;" onmouseover="this.style.backgroundColor='#aaa'" onmouseout="this.style.backgroundColor='#fff'">
        	<td><input type="checkbox" name="<?php echo $sub_code;?>" id="<?php echo $sub_code;?>" /></td>
            <td><label for="<?php echo $sub_code;?>"><?php echo $subject['subject'] ?></label></td>
        	<td><label for="<?php echo $sub_code;?>"><?php echo $subject['descript'] ?></label></td>
            <td><?php echo $subject['pop'] ?></td>
        </tr>
	<?php } ?> 
	</table>                      
<?php } ?>

<?php
function contains($arr, $value){
	foreach($arr as $v){
		if($v==$value) return true;
	}
	return false;
}
function contains_array($arr1, $arr2){
	foreach($arr1 as $a1){
		foreach($arr2 as $a2){
			if($a1==$a2) return true;
		}
	}
	return false;
}
?>

<h1>Examination Scheduler</h1>
<h2>Subjects List</h2>

<?php
if(isset($_POST['exclude'])){
	foreach($_POST as $name=>$value){
		if($value=="on"){
			unset($_SESSION['raw_subjects'][$name]);
		}
	}
}else if(isset($_POST['fuse'])){
	$fuse_ids = array();
	$fuse_code = "";
	$fuse_desc = "";
	$fuse_name = "";
	$fuse_idnum = "";
	foreach($_POST as $name=>$value){
		if($value=="on"){
			foreach($_SESSION['raw_subjects'][$name]['idnums'] as $id){
				if(!contains($fuse_ids, $id)) {
					$fuse_ids[] = $id;
				}
			}
			$fuse_code .= $name . "_";
			$fuse_desc .= $_SESSION['raw_subjects'][$name]['descript'];
			$fuse_name .= $_SESSION['raw_subjects'][$name]['subject'];
			unset($_SESSION['raw_subjects'][$name]);
		}
	}
	$_SESSION['raw_subjects'][$fuse_code] = array("subject"=>$fuse_name,"descript"=>$fuse_desc, "pop"=>count($fuse_ids), "idnums"=>$fuse_ids);
	echo "Fused Ids = " . count($fuse_ids);
}else if(isset($_POST['toperize'])){
	$rows = count($_SESSION['raw_subjects']);
	$index = 1;
	$batches = array();
	$batch_ids = array();
	while($rows){
		foreach($_SESSION['raw_subjects'] as $code=>$subject){
			if(!contains_array($subject['idnums'], $batch_ids)){
				$batch_ids = array_merge($subject['idnums'], $batch_ids);
				$batches[$index][$code] = $_SESSION['raw_subjects'][$code];
				unset($_SESSION['raw_subjects'][$code]);
			}
		}
		$batch_ids = array();
		$rows = count($_SESSION['raw_subjects']);
		$index++;
	}
	echo "<input type='button' value='Reset' onclick=\"document.location='index.php?page=exam_sched'\" />";
	echo "<input type=\"image\" src=\"images/printButton.png\" class=\"noprint\"
				onclick=\"printThisDiv('printOutputFrame','output');\"
				style=\"float: right; margin-top: -10px; margin-right: 30px;\"/>
		<iframe id=\"printOutputFrame\" style=\"width:0px; height: 0px; float: left; margin-left: -9999px;\"></iframe>";
	echo "<div id='output'>";
	echo "<table border='1' style='border-collapse: collapse'>";
	echo "<tr style='background: #fff;'><th width='40'>Batch</th><th>Subject:</th><th width='300'>Description</th><th width='80'>Population</th></tr>";
	foreach($batches as $batch=>$sub){
		foreach($sub as $code=>$details){
			echo "<tr style='background: #fff;'><td>$batch</td><td style='font-size: 10px;'>{$details['subject']}</td>
			   <td style='font-size: 10px;'>{$details['descript']}</td><td>{$details['pop']}</td></tr>";
		}
	}
	echo "</table></div>";
}else{
	$subj = mysql_query("SELECT s.sub_code, name, descript, COUNT(idnum) AS 'pop' FROM subjects s, sub_enrol se, class c 
					WHERE s.sub_code=c.sub_code AND c.class_code=se.class_code AND se.sem_code={$_SESSION['sem_code']} 
					GROUP BY s.sub_code  ORDER BY pop DESC");
	$_SESSION['raw_subjects'] = array();
	while($subjr=mysql_fetch_assoc($subj)) {
		$idnums = mysql_query("SELECT idnum FROM sub_enrol se, class c WHERE c.class_code=se.class_code AND c.sub_code={$subjr['sub_code']} AND c.sem_code={$_SESSION['sem_code']}");
		$ids = array();
		while($idnumsr=mysql_fetch_row($idnums)){
			$ids[] = $idnumsr[0];
		}
		$_SESSION['raw_subjects'][$subjr['sub_code']] = array("subject"=>$subjr['name'],"descript"=>$subjr['descript'], "pop"=>$subjr['pop'], "idnums"=>$ids);
	}
}
if(!isset($_POST['toperize'])) {
?>
<form action="" method="post" enctype="multipart/form-data">
<input type="submit" name="exclude" value="Exclude Selected" />
<input type="submit" name="fuse" value="Fuse Selected" />
<input type="submit" name="toperize" value="Toperize" />
<?php subjectsTable(); ?>
</form>
<?php
}
?>
