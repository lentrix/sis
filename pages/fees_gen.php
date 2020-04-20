<?php include("library/lister.php"); ?>
<h1>Fees Generator</h1>

<?php
if(isset($_POST['submit_fees'])) {
	$course = $_POST['cr_num'];
	$year = $_POST['year'];
	$amount = $_POST['amount'];
	$title = $_POST['fee_name'];
	
	$where = "";
	if($course && $year) $where = " AND course = $course AND year = $year ";
	else if($course && !$year) $where = " AND course=$course ";
	else if(!$course && $year) $where = " AND year=$year ";
	else $where = "";
	
	$affected = mysql_query("SELECT en.enrol_id FROM stud_enrol en WHERE sem_code={$_SESSION['sem_code']} $where");
	while($affr=mysql_fetch_assoc($affected)){
		$check = mysql_query("SELECT enrol_id FROM acct_item WHERE enrol_id={$affr['enrol_id']} AND acct_title='$title'");
		if(mysql_num_rows($check)==0){
			mysql_query("INSERT INTO acct_item (enrol_id, acct_title, amount) VALUES 
					({$affr['enrol_id']},'$title',{$amount})");
		}else{
			if($_POST['overwrite']=="on"){
				mysql_query("UPDATE acct_item SET amount=$amount WHERE enrol_id={$affr['enrol_id']} AND acct_title='$title'");
			}
		}
	}
	echo "<div class='error'>The fees have been updated.</div>";
}

?>

<h2>Fees Generator</h2>
<form method="post" action="">
	Name of Fee:
    <select name="fee_name">
    	<option value="misc">Miscellaneous Fee</option>
    	<option>PHC Lab</option>
        <option>Energy Fee</option>
        <option>Seminar Fee</option>
        <option value="stud_manual">Student & Library Manual</option>
        <option>RLE Fee</option>
        <option>Affiliation Fee</option>
        <option>Skills Lab Fee</option>
	<option>ID</option>
    </select>
    &nbsp;&nbsp;
    Amount: <input type="text" name="amount"  /> <br />
    Affected party: <?php CreateList("cr_num","cr_num","cr_acrnm","SELECT cr_num, cr_acrnm FROM courses ORDER BY cr_acrnm","",""); ?>
	<select name="year">
    	<option value="0"></option>
    	<option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
    </select>blank assumes all.
    <input type="submit" name="submit_fees" />
    <label><input type="checkbox" name="overwrite" />Overwrite existing data</label>
</form>
<hr />
