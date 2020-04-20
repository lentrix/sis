<?php include_once("library/computations.php"); ?>
<?php function createPassbook($idnum, $sem_code, $view=false){ ?>

<?php 
	$st = mysql_query("SELECT CONCAT(lname,', ',fname,' ',mi) AS 'name', e.idnum, idext, cr_acrnm, year, enrol_id  
					  FROM stud_info s, stud_enrol e, courses c
					  WHERE s.idnum=e.idnum AND e.course=c.cr_num
					  AND e.sem_code=$sem_code AND e.idnum=$idnum");
	echo mysql_error();
	$str = mysql_fetch_assoc($st);
    $sem_num = getSemTerm($_SESSION['sem_code']);
?>
	<?php if($view){ ?>
    <div style="width: 97%; background: #fff; height: 230px; display: screen;" class="noprint"> 
    	<span style="font-size: 10pt; clear: both; border-bottom: 1px solid #333;">
        	ID Number: <?php echo $str['idnum']; ?> &nbsp;&nbsp;&nbsp;&nbsp; Name: <?php echo $str['name']; ?> 
            &nbsp;&nbsp;&nbsp;&nbsp Course & Year: <?php echo $str['cr_acrnm'] . "-" . $str['year']; ?>
        </span>
        <span style="display: block font-size: 10pt; margin-top: 10px; float: left">
        	<table width="200">
        	<?php 
			$sub = mysql_query("SELECT d.enrol_id, s.name, c.cunits, c.lab_fee, punits, rate
								FROM subjects s, class c, sub_enrol e, stud_enrol d
								WHERE s.sub_code=c.sub_code AND c.class_code=e.class_code AND d.idnum=e.idnum AND d.sem_code=c.sem_code
								AND c.sem_code=$sem_code AND e.idnum=$idnum");
            
			echo mysql_error();
			$tuition=0;
			$rate=0;
			$tpunits = 0;
			$tcunits = 0;
			while($subr=mysql_fetch_assoc($sub)) { 
			    if(!$rate) $rate = getRate($subr['enrol_id'], $idnum);
			    $ttuition=$subr['punits'] * $rate;
			    $tuition += $ttuition;
			    $tpunits += $subr['punits'];
			    $tcunits += $subr['cunits'];
			?>
        	<tr>
            	<td style=" font-size: 10px; width: 0.5in; text-align:right"><?php if($subr['lab_fee']>0) printf("%.2f",$subr['lab_fee']); else echo "&nbsp;" ?></td>
                <td style=" font-size: 10px; width: 0.5in; text-align:right"><?php printf("%.2f",$ttuition);?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $subr['punits'];?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $subr['cunits'];?></td>
                <td style=" font-size: 10px;" width="40"><?php echo $subr['name'];?></td>
            </tr>
            <?php
			}
			$lab_fees = getLabFees($idnum, $sem_code);
			?>
			<tr style="border-top: 1px solid #333; border-bottom: 3px double #333">
			    <td style=" font-size: 10px; width: 0.5in; text-align: right;"><?php printf("%.2f",$lab_fees); ?></td>
                <td style=" font-size: 10px; width: 0.5in; text-align: right;"><?php printf("%.2f",$tuition);?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $tpunits;?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $tcunits;?></td>
                <td style=" font-size: 10px; width: 1.5in">&nbsp;</td>
			</tr>
        </table>
        </span>
        <span style="display: block font-size: 10pt; margin-top: 10px; float: left">
            <table style="width: 100px" border="0" cellpadding="0">
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f %.2f",$rate,$tuition);?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'misc'));?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", $lab_fees);?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'RLE Fee')); ?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'Energy Fee')); ?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'Affiliation Fee')); ?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'Seminar Fee')); ?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'old')); ?></td></tr>
                <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getOtherFees($str['enrol_id'])); ?></td></tr>
                <?php $total = getAllFees($idnum); ?>
                <tr><td style="font-size: 11px; text-align: right; border-top: 1px solid #333; border-bottom: 3px double #333">TOTALS......<?php printf("%.2f", $total); ?></td></tr>
            </table>	
        </span>
        
        <span style="display: block font-size: 10pt; margin-top: 10px; float: left">
			<?php $entrance = getEntrance($idnum, $_SESSION['sem_code']); ?>
            <?php if($entrance && $sem_num<3) $env = $entrance-100; else $env=0; ?>
            <?php $assess = ($total - $env) / 4; ?>
            <table cellpadding="6">
                <?php
                    $ent = mysql_query("SELECT * FROM payments 
                        WHERE idnum=$idnum AND acct_no=
                        (SELECT acct_code FROM chart_of_accts WHERE acct_title='Entrance')
                        AND sem_code={$_SESSION['sem_code']}");
                    if($entr = mysql_fetch_assoc($ent)){
                        $dt = $entr['date']    ;
                        $orno = $entr['orno'];
                        ($sem_num<3) ? $amt = $entr['amount']-100 : $amt = $entr['amount'];
                        $bal = $total-$amt;
                    }else{
                        $dt="";
                        $orno="";
                        $amt="";
                        $bal="";
                    }
                ?>
                <tr height="40"><td valign="bottom"  style="margin-top: 10px;"><?php printf("%.2f", $assess);?></td>
                    <td style="font-size: 8pt;vertical-align:top"><?php echo $dt ?></td>
                    <td style="font-size: 8pt;vertical-align:top"><?php echo $orno ?></td>
                    <td style="font-size: 8pt;vertical-align:top"><?php echo $amt ?></td>
                    <td style="font-size: 8pt;vertical-align:top;"><?php printf("%.2f",$bal); ?></td></tr>
                <tr><td><?php printf("%.2f", $assess);?></td><td colspan="4"></td></tr>
                <tr><td><?php printf("%.2f", $assess);?></td><td colspan="4"></td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </span>
    </div>
    <? } ?>
    
    
	<div style="width: 5.5in; height: 8.5in; position:relative; background: #fff;">
    	<div id="idnum" style="position:absolute; left: 0.5in; top: 0.9in; font-size: 11pt; font-weight:bold; border: 0px;">
        	<?php echo $str['idnum']; ?>
        </div>
        <div id="name" style="position:absolute; left: 0.5in; top: 1.2in; font-size: 11pt; font-weight:bold; border: 0px;">
        	<?php echo $str['name']; ?>
        </div>
        <div id="semst" style="position:absolute; left: 3.5in; top: 0.9in; font-size: 9pt; font-weight:bold; border: 0px;">
        	<?php echo getSemester($_SESSION['sem_code']);  ?>
        </div>
        <div id="course_year" style="position:absolute; left: 3.6in; top: 1.2in; font-size: 11pt; font-weight:bold; border: 0px;">
        	<?php echo $str['cr_acrnm'] . " - " . $str['year']; ?>
        </div>
        
        <table id="subjs" style="position: absolute; left: 0.03in; top: 2.15in;" border="0">
        	<?php 
			$sub = mysql_query("SELECT d.enrol_id, s.name, c.cunits, c.lab_fee, punits, rate
								FROM subjects s, class c, sub_enrol e, stud_enrol d
								WHERE s.sub_code=c.sub_code AND c.class_code=e.class_code AND d.idnum=e.idnum AND d.sem_code=c.sem_code
								AND c.sem_code=$sem_code AND e.idnum=$idnum");
            
			echo mysql_error();
			$tuition=0;
			$rate=0;
			$tpunits = 0;
			$tcunits = 0;
			while($subr=mysql_fetch_assoc($sub)) { 
			    if(!$rate) $rate = getRate($subr['enrol_id'], $idnum);
			    $ttuition=$subr['punits'] * $rate;
			    $tuition += $ttuition;
			    $tpunits += $subr['punits'];
			    $tcunits += $subr['cunits'];
			?>
        	<tr>
            	<td style=" font-size: 10px; width: 0.5in; text-align:right"><?php if($subr['lab_fee']>0) printf("%.2f",$subr['lab_fee']); else echo "&nbsp;" ?></td>
                <td style=" font-size: 10px; width: 0.5in; text-align:right"><?php printf("%.2f",$ttuition);?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $subr['punits'];?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $subr['cunits'];?></td>
                <td style=" font-size: 10px; width: 1.5in"><?php echo $subr['name'];?></td>
            </tr>
            <?php
			}
			$lab_fees = getLabFees($idnum, $sem_code);
			?>
			<tr style="border-top: 1px solid #333; border-bottom: 3px double #333">
			    <td style=" font-size: 10px; width: 0.5in; text-align: right;"><?php printf("%.2f",$lab_fees); ?></td>
                <td style=" font-size: 10px; width: 0.5in; text-align: right;"><?php printf("%.2f",$tuition);?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $tpunits;?></td>
                <td style=" font-size: 10px; width: 0.35in; text-align:center"><?php echo $tcunits;?></td>
                <td style=" font-size: 10px; width: 1.5in">&nbsp;</td>
			</tr>
        </table>
        <table style="position: absolute; right: 3.25in; top: 4.45in;" border="0" cellpadding="0">
        	<tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f %.2f",$rate,$tuition);?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'misc'));?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", $lab_fees);?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'RLE Fee')); ?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'Energy Fee')); ?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'Affiliation Fee')); ?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'Seminar Fee')); ?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getAccountItem($str['enrol_id'], 'old')); ?></td></tr>
            <tr><td style="font-size: 9px; text-align: right; padding-bottom:1px"><?php printf("%.2f", getOtherFees($str['enrol_id'])); ?></td></tr>
            <?php $total = getAllFees($idnum); ?>
            <tr><td style="font-size: 11px; text-align: right; border-top: 1px solid #333; border-bottom: 3px double #333">TOTALS......<?php printf("%.2f", $total); ?></td></tr>
        </table>
        
        <?php $entrance = getEntrance($idnum, $_SESSION['sem_code']); ?>
        <?php if($entrance) {
			if($sem_num<3)
				$env = $entrance-100;
			else $env=$entrance; 
		}else {
			$env=0;
		}
		?>
        <?php ($sem_num<3) ? $assess = ($total - $env) / 4 : $assess = ($total-$env) / 2; ?>
        <table style="position: absolute; left: 1.05in; top: 6.55in; font-size: 10px;" cellpadding="6">
            <?php
                $ent = mysql_query("SELECT * FROM payments 
                    WHERE idnum=$idnum AND acct_no=
                    (SELECT acct_code FROM chart_of_accts WHERE acct_title='Entrance')
                    AND sem_code={$_SESSION['sem_code']}");
                if($entr = mysql_fetch_assoc($ent)){
                    $dt = $entr['date']    ;
                    $orno = $entr['orno'];
                    if($sem_num<3)
                    	$amt = $entr['amount']-100;
                    else
                    	$amt = $entr['amount'];
                    $bal = $total-$amt;
                }else{
                    $dt="";
                    $orno="";
                    $amt="";
                    $bal="";
                }
            ?>
            <?php if($sem_num<3) { ?>
        	<tr height="40"><td valign="bottom"  style="margin-top: 10px;"><?php printf("%.2f", $assess);?></td>
        	    <td style="font-size: 8pt;vertical-align:top"><?php echo $dt ?></td>
        	    <td style="font-size: 8pt;vertical-align:top"><?php echo $orno ?></td>
        	    <td style="font-size: 8pt;vertical-align:top" width="80"><?php echo $amt ?></td>
        	    <td style="font-size: 8pt;vertical-align:top;"><?php printf("%.2f",$bal); ?></td></tr>
            <tr><td><?php printf("%.2f", $assess);?></td><td colspan="4"></td></tr>
            <tr><td><?php printf("%.2f", $assess);?></td><td colspan="4"></td></tr>
            <tr><td>&nbsp;</td></tr>
            <?php } else { ?>
            <tr height="40"><td valign="bottom"  style="margin-top: 10px;">&nbsp;</td>
        	    <td style="font-size: 8pt;vertical-align:top"><?php echo $dt ?></td>
        	    <td style="font-size: 8pt;vertical-align:top"><?php echo $orno ?></td>
        	    <td style="font-size: 8pt;vertical-align:top" width="80"><?php echo $amt ?></td>
        	    <td style="font-size: 8pt;vertical-align:top;"><?php printf("%.2f",$bal); ?></td></tr>
            <tr><td><?php printf("%.2f", $assess);?></td><td colspan="4"></td></tr>
            <tr><td>&nbsp;</td><td colspan="4"></td></tr>
            <tr><td>&nbsp;</td></tr>
            <?php } ?>
        </table>
    </div>
<?php } ?>
