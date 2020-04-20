<?php
include("library/singles.php");

function createProspectus($prp_code){
	$pros = mysql_query("SELECT cr_num, courses.course, year FROM prospectus, courses
						WHERE prospectus.course=courses.cr_num AND prp_code=$prp_code");
	$prow = mysql_fetch_assoc($pros);
	echo "<p align='center'>Mater Dei College<br />Tubigon, Bohol</p>";
	echo "<p align='center'><strong>Prospectus / Curriculum</strong> <br />";
	echo $prow['course'];
	echo "<br />(" . $prow['year'] . ")</p>";
	
	$sems = mysql_query("SELECT DISTINCT semyr FROM pros_subj WHERE prp_code=$prp_code ORDER BY semyr");
	$sem = array(1=>"First Semester",2=>"Second Semester",3=>"Summer");
	$yrs = array(1=>"First Year",2=>"Second Year",3=>"Third Year",4=>"Fourth Year",5=>"Fifth Year");
	
	while($sy = mysql_fetch_row($sems)) {
		$semyear = $yrs[substr($sy[0],0,1)] . "-" . $sem[substr($sy[0],1,1)];
		echo "<div style='border: 0px;'>";
		echo "<span style='display: block; font-weight: bold;'>$semyear</span>";
		echo "<table border=1 style='border-collapse: collapse' width='700'>
				<tr>
					<td width='150'>Course No.:</td>
					<td width='350'>Description:</td>
					<td width='50' align='center'>Units:</td>
					<td width='50' align='center'>&nbsp;</td>
				</tr>";
		
		$psub = mysql_query("SELECT pros_subj.sub_code, name, descript, c_units FROM pros_subj, subjects
				WHERE pros_subj.sub_code=subjects.sub_code
				AND prp_code=$prp_code
				AND semyr={$sy[0]}");
		$tunits=0;
		while($psrow=mysql_fetch_assoc($psub)) {
			echo "<tr>
				  	<td>{$psrow['name']}</td>
					<td>{$psrow['descript']}</td>
					<td align='center'>{$psrow['c_units']}</td>
					<td align='center'>
						<form method='post' action='' style='display:inline'>
							<input type='hidden' name='sub_code' value='{$psrow['sub_code']}' />
							<input type='hidden' name='prp_code' value='{$_REQUEST['prp_code']}' />
							<input type='submit' name='submit_remove_pros_subj' value='x'
								style='border:0px;width:20px;height:20px;display:inline'
								title='Remove this subject from the prospectus.'
								class='noprint'/>
						</form>
					</td>
				  </tr>";
			$tunits+=$psrow['c_units'];
		}
		echo "<tr><td colspan='2'>TOTAL UNITS:</td><td align='center'>$tunits</td><td>&nbsp;</td></tr>";
		echo "</table>";
		echo "</div>";
	}
}

function showEvaluation($idnum, $prp_code) {
	$pros = mysql_query("SELECT cr_num, courses.course, year FROM prospectus, courses
						WHERE prospectus.course=courses.cr_num AND prp_code=$prp_code");
	$prow = mysql_fetch_assoc($pros);
	echo "<p align='center'>Mater Dei College<br />Tubigon, Bohol</p>";
	echo "<p align='center'><strong>EVALUATION</strong> <br />";
	echo getFullName($idnum) . "<br />";
	echo $prow['course'];
	echo "<br />(" . $prow['year'] . ")</p>";
	
	$sems = mysql_query("SELECT DISTINCT semyr FROM pros_subj WHERE prp_code=$prp_code ORDER BY semyr");
	$sem = array(1=>"First Semester",2=>"Second Semester",3=>"Summer");
	$yrs = array(1=>"First Year",2=>"Second Year",3=>"Third Year",4=>"Fourth Year",5=>"Fifth Year");
	
	while($sy = mysql_fetch_row($sems)) {
		$semyear = $yrs[substr($sy[0],0,1)] . "-" . $sem[substr($sy[0],1,1)];
		echo "<div style='border: 0px;'>";
		echo "<span style='display: block; font-weight: bold;'>$semyear</span>";
		echo "<table border=1 style='border-collapse: collapse' width='700'>
				<tr>
					<td width='150'>Course No.:</td>
					<td width='350'>Description:</td>
					<td width='50' align='center'>Units:</td>
					<td width='50' align='center'>Rating:</td>
				</tr>";
		
		$psub = mysql_query("SELECT pros_subj.sub_code, name, descript, c_units FROM pros_subj, subjects
				WHERE pros_subj.sub_code=subjects.sub_code
				AND prp_code=$prp_code
				AND semyr={$sy[0]}");
		while($psrow=mysql_fetch_assoc($psub)) {
			echo "<tr>
				  	<td>{$psrow['name']}</td>
					<td>{$psrow['descript']}</td>
					<td align='center'>{$psrow['c_units']}</td>
					<td align='center'>" .
						getRating($idnum,$psrow['sub_code'])
					. "</td>
				  </tr>";
		}
		
		echo "</table>";
		echo "</div>";
	}
}

function getRating($idnum, $sub_code) {
	$rt = mysql_query("SELECT rating FROM sub_enrol WHERE idnum=$idnum AND sub_code=$sub_code");
	if(mysql_num_rows($rt)==1) {
		$rtr = mysql_fetch_row($rt);
		if(empty($rtr[0])) return "*";
		else return $rtr[0];
	}else {
		return "";
	}
}
?>