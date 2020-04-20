<?php include("library/lister.php"); ?>
<h1>Student Payments</h1>
<form method="post" action="">
    <?php $semcode = $_SESSION['sem_code']; ?>
    <?php echo CreateList("idnum", "idnum", "name", 
            "SELECT stud_enrol.idnum, CONCAT(lname,', ',fname,' ',mi) AS 'name' 
                FROM stud_info, stud_enrol
                WHERE stud_info.idnum=stud_enrol.idnum
                AND stud_enrol.sem_code=$semcode ORDER BY lname, fname", ""); ?>
    <input type="submit" name="student_payments" value="View Payments" />
</form>

<?php if(isset($_POST['student_payments'])) : ?>

<table border="1">
    <tr>
        <th width="100">OR Number</th>
        <th width="100">Date</th>
        <th width="250">Account</th>
        <th width="150">Amount</th>
    </tr>
    <?php $rs = mysql_query("SELECT payments.*, acct_title FROM payments,chart_of_accts 
        WHERE idnum={$_POST['idnum']} AND sem_code=$semcode
        AND chart_of_accts.acct_code=payments.acct_no"); ?>
    <?php echo mysql_error(); ?>
    <?php while($row=mysql_fetch_assoc($rs)) : ?>
    
    <tr>
        <td><?php echo $row['orno'];?></td>
        <td><?php echo $row['date'];?></td>
        <td><?php echo $row['acct_title'];?></td>
        <td align="right"><?php echo $row['amount'];?></td>
    </tr>
        
    <?php endwhile; ?>

</table>
<?php endif; ?>
