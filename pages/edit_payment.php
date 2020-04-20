<?php

include_once("library/lister.php");

$pmt = mysql_query("SELECT * FROM payments WHERE orno=" . $_GET['orno']);


$row = mysql_fetch_assoc($pmt);

$stud = mysql_query("SELECT CONCAT(lname, ', ', fname, ' ', mi) AS 'name' FROM stud_info WHERE idnum=" . $row['idnum']);

$_REQUEST['acct_code'] = $row['acct_no'];

?>

<?php
//process submission

if(isset($_POST['orno'])){
    if(isset($_POST['edit_payment'])){
        $sql = "UPDATE payments SET orno=" . $_POST['orno'] . ", "
                . "date='" . $_POST['date'] . "', "
                . "idnum=" . $_POST['idnum'] . ", "
                . "name='" . $_POST['name'] . "', "
                . "acct_no=" . $_POST['acct_code'] . ", "
                . "amount=" . $_POST['amount'] . " WHERE orno=" . $_GET['orno'];
        //echo $sql;
        mysql_query($sql);
        //echo mysql_error();
    }
    echo "<script>document.location='index.php?page=payments';</script>";
}
?>
<h1>Edit Payment OR # <?php echo $_GET['orno']; ?></h1>
<form method="post" action="">
    <p>
        Date<br />
        <input type="text" name="date" value="<?php echo $row['date']; ?>" />
    </p>
    <p>
        OR Number<br />
        <input type="text" name="orno" value="<?php echo $row['orno']; ?>" />
    </p>
    <p>
        Student<br />
        <select name="idnum">
            <option value="0"></option>
            <?php $studs = mysql_query("SELECT idnum, CONCAT(lname, ', ', fname, ' ',mi) 
                AS 'name' FROM stud_info ORDER BY lname, fname"); ?>
            <?php echo mysql_error(); ?>
            <?php while($stdsrow=mysql_fetch_assoc($studs)) : ?>
            <option value="<?php echo $stdsrow['idnum']; ?>"
                    <?php if($row['idnum']==$stdsrow['idnum']) echo " selected"; ?>
                    ><?php echo $stdsrow['name']; ?></option>
            <?php endwhile; ?>
            
        </select> or <br />
        <input type="text" name="name" value="<?php echo $row['name']; ?>" placeholder="Enter name here" />
    </p>
    <p>
        Account Title<br />
        <?php CreateList("acct_code","acct_code","acct_title",
                        "SELECT acct_code, acct_title FROM chart_of_accts ORDER BY acct_title",
                        "width: 100px;","");?>
    </p>
    <p>
        Amount<br />
        <input type="text" name="amount" value="<?php echo $row['amount']; ?>" />
    </p>
    <input type="submit" name="edit_payment" value="Save Changes" />
    <input type="submit" name="cancel" value="Cancel" />
</form>
