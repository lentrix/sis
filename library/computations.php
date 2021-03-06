<?php
function getAccountItem($enrol_id, $acct_title) {
	global $db;
	$acc = mysqli_query($db, "SELECT * FROM acct_item WHERE enrol_id=$enrol_id AND acct_title='$acct_title'");
	if($acc) {
		$accr = mysqli_fetch_assoc($acc);
		return $accr['amount'];
	}else {
		return "0.00";
	}
}

function getOtherFees($enrol_id){
	global $db;
	$oth = mysqli_query($db, "SELECT SUM(amount) FROM acct_item
								WHERE enrol_id=$enrol_id AND acct_title NOT
								IN ('misc','RLE Fee','Seminar Fee','Affiliation Fee','old', 'Energy Fee')");
	$othr = mysqli_fetch_row($oth);
	if($othr[0]) {
		return $othr[0];
	}else {
		return "0.00";
	}
}
function getLabFees($idnum, $sem_code){
	global $db;
	$lbf = mysqli_query($db, "SELECT SUM(lab_fee) AS 'lab_fees' FROM class WHERE class_code IN 
								(SELECT class_code FROM sub_enrol WHERE idnum=$idnum AND sem_code=$sem_code)");
	$lbfr = mysqli_fetch_row($lbf);
	if($lbfr[0]) 
		return $lbfr[0];
	else
		return "0.00";
}
function getRate($enrol_id, $idnum){
	global $db;
    $rt = mysqli_query($db, "SELECT rate FROM stud_enrol WHERE enrol_id=$enrol_id");
	
	if($rt){
		$rtr = mysqli_fetch_row($rt);
		$rate = $rtr[0];
		if($rate>0) return $rate;
		else {
			$bs = mysqli_query($db, "SELECT base_rate FROM stud_info WHERE idnum=$idnum");
			$bsr = mysqli_fetch_row($bs);
			return $bsr[0];
		}
	}else {
		return "0.00";
	}
}
function getRate2($idnum){
	global $db;
    $rt = mysqli_query($db, "SELECT rate FROM stud_enrol WHERE idnum=$idnum AND sem_code={$_SESSION['sem_code']}");
	
	if($rt){
		$rtr = mysqli_fetch_row($rt);
		$rate = $rtr[0];
		if($rate>0) return $rate;
		else {
			$bs = mysqli_query($db, "SELECT base_rate FROM stud_info WHERE idnum=$idnum");
			$bsr = mysqli_fetch_row($bs);
			return $bsr[0];
		}
	}else {
		return "0.00";
	}
}
function getBaseRate($idnum){
	global $db;
    $bs = mysqli_query($db, "SELECT base_rate FROM stud_info WHERE idnum=$idnum");
	$bsr = mysqli_fetch_row($bs);
	return $bsr[0];
}

function getTotalPayUnits($idnum){
	global $db;
	$pn = mysqli_query($db, "select sum(punits) AS 'punits' from class 
					where class_code IN (select class_code from sub_enrol where idnum=$idnum and sem_code={$_SESSION['sem_code']})");

	$pnr = mysqli_fetch_row($pn);
	return $pnr[0];
}

function getTotalCreditUnits($idnum){
	global $db;
	$pn = mysqli_query($db, "select sum(cunits) AS 'cunits' from class 
					where class_code IN (select class_code from sub_enrol where idnum=$idnum and sem_code={$_SESSION['sem_code']})");

	$pnr = mysqli_fetch_row($pn);
	return $pnr[0];
}

function getAllFees($idnum){
	global $db;
	$rate = getRate2($idnum);

	$punits = getTotalPayUnits($idnum);

	$lab = getLabFees($idnum,$_SESSION['sem_code']);

	$tuition = $rate * $punits;

	$fees = mysqli_query($db, "SELECT SUM(amount) FROM acct_item WHERE enrol_id =
									(SELECT enrol_id FROM stud_enrol WHERE idnum=$idnum AND sem_code={$_SESSION['sem_code']})");
	$feesr = mysqli_fetch_row($fees);
	$other_fees = $feesr[0];

	return $tuition + $other_fees + $lab;
}

function getEntrance($idnum, $sem_code){
	global $db;
	$en = mysqli_query($db, "SELECT amount FROM payments WHERE idnum=$idnum AND sem_code=$sem_code AND acct_no=
												(SELECT acct_code FROM chart_of_accts WHERE acct_title='Entrance')");
	if($en) $enr = mysqli_fetch_row($en);
	return $enr[0];
}

function getEntranceOR($idnum, $sem_code){
	global $db;
	$en = mysqli_query($db, "SELECT orno FROM payments WHERE idnum=$idnum AND sem_code=$sem_code AND acct_no=
												(SELECT acct_code FROM chart_of_accts WHERE acct_title='Entrance')");
	if($en) {
		$enr = mysqli_fetch_row($en);
		return $enr[0];
	}else{
		return "-";
	}
}

function getSemTerm($sem_code){
	global $db;
    $sem = mysqli_query($db, "SELECT sem_num FROM sems WHERE sem_code=$sem_code");
    $r = mysqli_fetch_row($sem);
    return $r[0];
}
?>
