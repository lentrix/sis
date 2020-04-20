<?php
	require_once("library/computations.php");
?>
<h1>Ledger</h1>
<form method="post" action="">
   ID Number: <input type="text" name="idnum" /> <input type="submit" name="view_ledger" value="Show Ledger" />
</form>


<?php
if(isset($_POST['view_ledger'])) {
   $idnum = $_POST['idnum'];
   $sem_code = $_SESSION['sem_code'];
   $sem_num = getSemTerm($_SESSION['sem_code']);
   
   $en = mysql_query("SELECT enrol_id, lname, fname, mi, cr_acrnm, year
		FROM stud_enrol se, stud_info si, courses c 
		WHERE se.idnum=si.idnum AND c.cr_num=se.course
		AND se.idnum=$idnum AND se.sem_code=$sem_code");
   
   $enr = mysql_fetch_assoc($en);
   $enrol_id = $enr['enrol_id'];
   $name = $enr['lname'] . ", " . $enr['fname'] . " " . $enr['mi'];
   $course_year = $enr['cr_acrnm'] . " - " . $enr['year'];
   
   $cunits = getTotalCreditUnits($idnum);
   $punits = getTotalPayUnits($idnum);
   $rate = getRate2($idnum);
   $misc = getAccountItem($enrol_id, "misc");
   $energy = getAccountItem($enrol_id,"Energy Fee");
   $labfees = getLabFees($idnum, $sem_code);
   $aff = getAccountItem($enrol_id,"Affiliation Fee");
   $sem = getAccountItem($enrol_id,"Seminar Fee");
   $rle = getAccountItem($enrol_id, "RLE Fee");
   $other = getOtherFees($enrol_id);
   $old = getAccountItem($enrol_id,"old");
   $tuition = $rate*$punits;
   
   $total = $tuition+$misc+$labfees+$energy+$aff+$sem+$old+$rle+$other;
   $entrance_raw = getEntrance($idnum, $_SESSION['sem_code']);
   $entrance_or = getEntranceOR($idnum, $_SESSION['sem_code']);
   if($entrance_raw) {
   		($sem_num==3) ? $entrance = $entrance_raw : $entrance = $entrance_raw-100;
   }else $entrance = 0;
?>
<div style="float: right; width: 340px; height: 30px; margin-top: -50px">
	<input type="button" value="Switch Side" onclick="
		obj = document.getElementById('ledger_details');
		if(obj.style.cssFloat) {
			if(obj.style.cssFloat=='left') obj.style.cssFloat='right';
			else obj.style.cssFloat='left';
		}else {
			if(obj.style.styleFloat=='left') obj.style.styleFloat='right';
			else obj.style.styleFloat='left';
		}
	" />
	<input type="button" value="hide heading" onclick="
		obj = document.getElementById('ledger_head');
		if(obj.style.visibility=='visible') obj.style.visibility='hidden';
		else obj.style.visibility='visible';
	" />
	<input type="button" value="Print" onclick="printThisDiv('print_frame','ledger_sheet')" />
	<iframe id="print_frame" style="width: 1px; margin-left: -99999; float: left"></iframe>
</div>
<div style="" id="ledger_sheet">
	<div id="ledger_head" style="visibility: visible; margin-top: 58px; height: 30px; position:relative">
		<div style="position: absolute; left: 85px; font-size: 18px; font-weight: bold"><?php echo $idnum; ?></div>
		<div style="position: absolute; left: 230px; font-size: 18px; font-weight: bold"><?php echo $name; ?></div>
		<div style="position: absolute; right: 20px; font-size: 18px; font-weight: bold"><?php printf("%.2f",$rate);?></div>
		</strong>
	</div>
	<?php
	$semnum = mysql_query("SELECT sem_num FROM sems WHERE sem_code = {$_SESSION['sem_code']}");
	$semnumr = mysql_fetch_row($semnum);
	if($semnumr[0]==1) $align="left";
	else $align="right";
	?>
	<div style="width: 306px; float: <?php echo $align; ?>; margin-top: 45px;" id="ledger_details">
		<table id="ledger" border=0 width="100%">
			<thead><?php getSemester($_SESSION['sem_code']); ?></thead>
			<tr><td colspan="6">Course &amp; Year: <?php echo $course_year; ?></td></tr>
			<tr><td>&nbsp;</td><td align="center">CU</td><td align="center">PU</td><td>&nbsp;</td></tr>
			<tr>
				<td>Tuition Fees</td>
				<td align="center"><?php printf("%.2f",$cunits); ?></td>
				<td align="center"><?php printf("%.2f",$punits); ?></td>
				<td align="center"><?php printf("%.2f",$rate); ?></td>
				<td align="right"><?php printf("%.2f", $tuition); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Mat/Misc Fees</td>
				<td align="right"><?php printf("%.2f", $misc);?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Lab Fees</td>
				<td align="right"><?php printf("%.2f", $labfees); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Energy Fee</td>
				<td align="right"><?php printf("%.2f", $energy); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Affiliation Fee</td>
				<td align="right"><?php printf("%.2f", $aff); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Seminar Fee</td>
				<td align="right"><?php printf("%.2f", $sem); ?></td>
				<td>&nbsp;</td>
			</tr>
            <tr>
				<td colspan="4" width="240">RLE Fee</td>
				<td align="right"><?php printf("%.2f", $rle); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Old Account</td>
				<td align="right"><?php printf("%.2f", $old); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="240">Others</td>
				<td align="right"><?php printf("%.2f", $other); ?></td>
				<td><?php printf("%.2f", $total); ?></td>
			</tr>
			<tr>
				<td colspan="4" width="240">Less: Entrance [OR# <?php echo $entrance_or; ?>]</td>
				<td align="right"><?php printf("%.2f", $entrance); ?></td>
				<td align="right"><?php printf("%.2f", $total-$entrance); ?></td>
			</tr>
		</table>
	</div>
	<div style="clear:both; border: 0px">&nbsp;</div>
</div>
<?php
}else {
?>
	<p>Enter ID Number on the ID Number field and click on Show Ledger Button to view a student's ledger.</p>
<?php
}
?>
