<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>
<?php include_once("library/computations.php"); ?>
<?php

//Submit Action...........................
if(isset($_POST['submit_account'])) {
	mysql_query("UPDATE stud_enrol SET rate={$_POST['rate']} WHERE enrol_id={$_POST['enrol_id']}");
	
	$n = count($_POST['acct_name']);

	for($i=0; $i<$n; $i++){
		
		if($_POST['acct_amount'][$i]){
			$accti = mysql_query("SELECT * FROM acct_item WHERE enrol_id={$_POST['enrol_id']} AND acct_title='{$_POST['acct_name'][$i]}'");
			if(mysql_num_rows($accti)>0){
				$sql = "UPDATE acct_item SET amount = {$_POST['acct_amount'][$i]} 
					WHERE enrol_id={$_POST['enrol_id']} AND acct_title = '{$_POST['acct_name'][$i]}'";
				mysql_query($sql);
				echo $sql ."<br>";
			}else{
				mysql_query("INSERT INTO acct_item (enrol_id, acct_title, amount) 
							VALUES ({$_POST['enrol_id']},'{$_POST['acct_name'][$i]}',{$_POST['acct_amount'][$i]})");
			}
		}
	}
	
	echo "<div class='error'>Account Successfully Updated!</div>";
	
}

if(isset($_POST['add_other_account'])) {
	if(!empty($_POST['acct_name']) && !empty($_POST['amount'])){
		mysql_query("INSERT INTO acct_item (enrol_id, acct_title, amount)
				VALUES ({$_POST['enrol_id']},'{$_POST['acct_name']}',{$_POST['amount']})");
		echo mysql_error();
	}else{
		echo "Blank: " . $_POST['acct_name'] . " - " . $_POST['amount'];
	}
}

if(isset($_POST['delete_other'])) { ?>
	<div class='error'>You are about to remove the item <?php echo $_POST['acct_name']; ?> from this account. Confirm?
    <form method="post" action="">
    	<input type="hidden" name="enrol_id" value="<?php echo $_POST['enrol_id']; ?>" />
        <input type="hidden" name="serial" value="<?php echo $_POST['serial']; ?>" />
        <input type="submit" name="confirm_delete_other" value="Confirm" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>
    </div>
<?php	
}

if(isset($_POST['confirm_delete_other'])) {
	mysql_query("DELETE FROM acct_item WHERE serial={$_POST['serial']}");
	if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
	else echo "<div class='error'>Item deleted.</div>";
}
?>
<h1>Accounts</h1>

<?php if(!isset($_POST['enrol_id']) && !isset($_POST['idnum'])) { ?>
<form method="post" action="">
	Select Student: <br />
    ID Number: <input type="text" name="idnum" style="width: 100px;" />
    <?php CreateList("enrol_id","enrol_id","detail",
			"SELECT enrol_id, CONCAT(lname,', ',fname,' ',mi,' [',cr_acrnm,' - ', year,']') AS 'detail'
			FROM stud_enrol e, stud_info s, courses c
			WHERE e.idnum=s.idnum AND e.course=c.cr_num
			AND e.sem_code={$_SESSION['sem_code']}
			ORDER BY lname, fname",
			"font-size: 10pt;"); ?>
    <input type="submit" value="Open Account" name="submit_open_account" />
</form>
<?php } else { 
			if(!$_POST['idnum']){
				$en = mysql_query("SELECT * FROM stud_enrol WHERE enrol_id={$_POST['enrol_id']}");
			}else{
				$en = mysql_query("SELECT * FROM stud_enrol WHERE idnum={$_POST['idnum']} AND sem_code={$_SESSION['sem_code']}");
			}
			
			$enr = mysql_fetch_assoc($en);
			$idnum = $enr['idnum'];
			$enrol_id = $enr['enrol_id'];

			$pay_units = getTotalPayUnits($idnum);
			$lab_fees = getLabFees($idnum,$_SESSION['sem_code']);
			$misc = getAccountItem($enrol_id, 'misc');
			$rle = getAccountItem($enrol_id, 'RLE Fee');
			$aff = getAccountItem($enrol_id, 'Affiliation Fee');
			$sem = getAccountItem($enrol_id, 'Seminar Fee');
			$energy = getAccountItem($enrol_id, 'Energy Fee');
			$old = getAccountItem($enrol_id, 'Old Account');
			$other = getOtherFees($enrol_id) + $rle + $aff + $sem + $energy;
			
			$rate = getRate($enrol_id, $idnum);
			$tuition = $pay_units * $rate;
			
			$total = $tuition + $other + $misc + $lab_fees;
			$entrance = getEntrance($idnum,$_SESSION['sem_code'])-100;
			$bal = $total - $entrance;
?>
<form method="post" action="" style="float:right">
	<input type="submit" value="X" />
</form>

<table>
	<tr><td valign="top">
    	<form method="post" action="">
        <input type="hidden" name="enrol_id" value="<?php echo $enrol_id;?>" />
        <input type="hidden" name="idnum" value="<?php echo $idnum;?>" />
        <table style="border-collapse:collapse; border: 1px solid #333" border=1 cellpadding="3">
            <tr><th colspan="2">Student Name: <?php echo getFullName($idnum);?></th></tr>
            <tr><th>Course & Year:</th><td><?php echo getCourseAndYear($idnum, $_SESSION['sem_code']);?></td></tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
            	<th align="left">Tuition Fee: 
					<?php echo $pay_units ; ?> @ <input type="text" name="rate" style="width: 50px;" value="<?php echo $rate; ?>" />
                </th>
                <td align="right">
                	<input type="text" name="tuition" style=" text-align:right; width: 100px;" readonly="readonly"
                   		value="<?php echo $tuition; ?>"/>
                </td>
            </tr>
            <tr><th align="left">Miscellaneous Fee:</th>
                <td align="right">
                	<input type="hidden" name="acct_name[]" value="misc" />
                	<input type="text" name="acct_amount[]" style=" text-align:right; width: 100px;" 
                    	value="<?php echo $misc; ?>"/></td></tr>
            <tr><th align="left">Laboratory Fees:</th>
            	<td align="right"><input type="text" name="lab_fee" style=" text-align:right; width: 100px;" readonly="readonly"
                   	value="<?php echo $lab_fees; ?>"/></td></tr>
            
            <tr><th align="left">Old Account:</th>
            	<td align="right"><input type="hidden" name="acct_name[]" value="old" />
                	<input type="text" name="acct_amount[]" style=" text-align:right; width: 100px;" 
                    	value="<?php echo $old; ?>"/></td></tr>
            <tr>
                <th align="left">Others:</th>
            	<td valign="top"><input type="text" name="others" style=" text-align:right; width: 100px;" 
                    	value="<?php echo $other; ?>" readonly="readonly" /></td></tr>
            
            <tr><th align="left">TOTAL</th><td align="right"><strong><?php printf("%.2f",$total);?></strong></td></tr>
            <tr><th align="left">LES: Entrance Fee</th><td align="right"><strong><?php printf("%.2f",$entrance);?></strong></td></tr>
            <tr><th align="left">BALANCE</th><td align="right"><strong><?php printf("%.2f",$bal);?></strong></td></tr>
        </table>
        <input type="submit" name="submit_account" value="Save Account" />
        </form>
	</td>
    <td valign="top" style="padding-left: 20px;">
    	<table style="border-collapse:collapse" border="1" cellpadding="3" align="right">
        	<tr>
            	<th>#</th><th>Class Code:</th><th>Subject:</th><th>Cr Units:</th><th>Pay Units:</th>
            </tr>
<?php
	$sub = mysql_query("SELECT class_code, name, cunits, punits FROM subjects s, class c
					   WHERE s.sub_code=c.sub_code AND c.class_code IN 
					   (SELECT class_code FROM sub_enrol WHERE idnum=$idnum AND sem_code={$_SESSION['sem_code']})");
	$n=1;
	$total_punits = 0;
	while($subr=mysql_fetch_assoc($sub)) { 
		$total_punits+=$subr['punits'];
?>
			<tr>
            	<td><?php echo $n++; ?></td>
                <td align="center"><?php echo $subr['class_code']; ?></td>
                <td><?php echo $subr['name']; ?></td>
                <td><?php echo $subr['cunits']; ?></td>
                <td><?php echo $subr['punits']; ?></td>
            </tr>
<?php
	}
?>
			<tr><th colspan="4">TOTAL PAY UNITS:</th><td><?php echo $total_punits;?></td></tr>
        </table>
    </td></tr>
</table>
<h3>Other Accounts:</h3>
<form method="post" action="">
<input type="hidden" name="enrol_id" value="<?php echo $enrol_id; ?>" />
Account Title: <input type="text" name="acct_name" /> &nbsp; Amount: <input type="text" name="amount" />
<input type="submit" value="Add" name="add_other_account" />
</form>
<table style="border-collapse:collapse;" cellpadding="0" border="1">
	<tr>
    	<th width="200">Account Title:</th>
        <th width="100" align="right">Amount</th>
        <th width="40">&nbsp;</th>
    </tr>
<?php 
	$oth_acc = mysql_query("SELECT * FROM acct_item WHERE enrol_id=$enrol_id AND acct_title 
	        NOT IN ('misc','old')");
	while($oth_accr=mysql_fetch_assoc($oth_acc)){ ?>
	<tr>
    	<td><?php echo $oth_accr['acct_title'];?></td>
        <td align="right"><?php echo $oth_accr['amount'];?></td>
        <td>
        	<form method="post" action="" style="display:inline">
            	<input type="hidden" name="enrol_id" value="<?php echo $enrol_id;?>" />
            	<input type="hidden" name="serial" value="<?php echo $oth_accr['serial'];?>" />
                <input type="hidden" name="acct_name" value="<?php echo $oth_accr['acct_title'];?>" />
                <input type="submit" name="delete_other" value="X" />
            </form>
        </td>
    </tr>
<?php
	}
?>
</table>

<h3>Payments</h3>
<div>
	<table border="1" style="background:#fff">
    	<tr>
        	<th width="100">Date:</th>
            <th width="100">OR. #:</th>
            <th width="200">Title:</th>
            <th width="100">Amount</th>
        </tr>
	<?php $pm = mysql_query("SELECT p.*, c.acct_title FROM payments p, chart_of_accts c 
							WHERE p.acct_no = c.acct_code AND
							idnum=$idnum AND sem_code={$_SESSION['sem_code']} ORDER BY date"); ?>
    <?php while($pmr=mysql_fetch_assoc($pm)) { ?>
    	<tr>
        	<td><?php echo $pmr['date'];?></td>
            <td><?php echo $pmr['orno'];?></td>
            <td><?php echo $pmr['acct_title'];?></td>
            <td align="right"><?php printf("%.2f",$pmr['amount']);?></td>
        </tr>
    <?php } ?>
    </table>
</div>

<input type="button" value="Print Passbook" style="float:right"
	onclick="printThisDiv('passbookframe','passbook')" />
<iframe id="passbookframe" style="position:absolute; left: -99999; width:1px;"></iframe>
<h3>Passbook</h3>
<div id="passbook">
<?php include("library/passbook.php"); createPassbook($idnum, $_SESSION['sem_code']); ?>
</div>

<?php } ?>
