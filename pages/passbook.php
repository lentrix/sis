<h1>Student Passbook</h1>

<form method="post" action="">
ID Number: <input type="text" name="idnum" />
<input type="submit" name="submit" value="Show Passbook" />
</form>

<hr/>

<?php
include("library/passbook.php"); 
if(isset($_POST['idnum'])) {
	$idnum = $_POST['idnum'];
?>

<input type="button" value="Print Passbook" style="float:right"
	onclick="printThisDiv('passbookframe','passbook')" />
<iframe id="passbookframe" style="position:absolute; left: -99999; width:1px;"></iframe>
<h3>Passbook</h3>
<div id="passbook">
<?php createPassbook($idnum, $_SESSION['sem_code'],true); ?>
</div>

<?php 
}
?>
