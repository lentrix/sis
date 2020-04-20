<?php include_once("library/lister.php"); ?>

<?php
if(isset($_POST['submit_payment'])) {
	echo "<div class='error'>";
	if(empty($_POST['amount']) || empty($_POST['date']) || empty($_POST['orno'])) {
		echo "The one or more required field is empty.";
	}else {
		mysql_query("INSERT INTO payments (orno, sem_code, idnum, name, date, amount, acct_no)
					VALUES ({$_POST['orno']}, {$_SESSION['sem_code']}, {$_POST['idnum']}, 
						'{$_POST['st_name']}','{$_POST['date']}',{$_POST['amount']},'{$_POST['acct_code']}')");
		if(mysql_error()) echo mysql_error();
		else {
                    unset($_POST['idnum']);
                    echo "The payment has been recorded successfully!!!";
                }
                
	}
	echo "</div>";
}
$last_orno = mysql_query("SELECT orno FROM payments ORDER BY orno DESC LIMIT 0, 1");
$lor = mysql_fetch_row($last_orno);
$next_orno = $lor[0] + 1;
?>


<h1>Payments</h1>
<hr />
<h3>Record Payments</h3>
<div>
    <form method="post" action="">
        <table style="border-collapse: collapse; max-height: 300px; overflow: auto;">
            <tr>
                <th width="95">Date:</th>
                <th width="110">ORNo.:</th>
                <th width="200">Student:</th>
                <th width="100">Title:</th>
                <th width="150">Amount:</th>
                <th width="50">&nbsp;</th>
            </tr>
            <tr>
                <td><input type="text" name="date" value="<?php echo date('Y-m-d');?>" 
                           style="width:95px;" /></td>
                <td><input type="text" name="orno" 
                           value="<?php echo $next_orno; ?>"
                           style="width: 110px;"/></td>
                <td>
                    <select name="idnum" style="width: 200px;" id="nameSelect">
                        <option value="NULL"></option>
                        <option value="NULL" onClick="changeInput()">Enter name...</option>
                        <?php
                        $result = mysql_query("SELECT idnum, lname, fname, mi 
                            FROM stud_info ORDER BY lname, fname");
                        while($row = mysql_fetch_assoc($result)){ ?>
                        
                        <option value="<?php echo $row['idnum']; ?>">
                            <?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mi']; ?>
                        </option>
                        
                        <?php 
                        }
                        ?>
                    </select>
                    <input type="text" class="detail" id="nameField" name="st_name" style="display: none" />
                </td>
                <td><?php CreateList("acct_code","acct_code","acct_title",
                        "SELECT acct_code, acct_title FROM chart_of_accts ORDER BY acct_title",
                        "width: 100px;","");?>
                </td>
                <td>
                    <input type="text" name="amount" style="width: 100px; text-align: right" />
                </td>
                <td>
                    <input type="submit" name="submit_payment" value="Go" />
                </td>
            </tr>
            <?php $date = date('Y-m-d'); ?> 
            <?php $pmt = mysql_query("SELECT p.date, p.orno, 
                    (SELECT CONCAT(lname, ', ' , fname) FROM stud_info WHERE stud_info.idnum=p.idnum) AS 'st_name', 
                    p.idnum, p.name, c.acct_title, p.amount  
                    FROM payments p, chart_of_accts c
                    WHERE c.acct_code=p.acct_no 
                    ORDER BY p.orno DESC
                    LIMIT 0, 100") ?>
            <?php echo mysql_error(); ?>
            <?php while($prow = mysql_fetch_assoc($pmt)) { ?>
            <tr>
                <td style="border: 1px solid #888888; overflow: hidden;"><?php echo $prow['date'];?></td>
                <td style="border: 1px solid #888888; overflow: hidden;">
                    <a href="index.php?page=edit_payment&orno=<?php echo $prow['orno']; ?>"> 
                        <?php echo $prow['orno'];?>
                    </a>
                </td>
                <?php ($prow['st_name']=="") ? $name = $prow['name'] : $name=$prow['st_name']; ?>
                <td style="border: 1px solid #888888; overflow: hidden;"><?php echo $name;?></td>
                <td style="border: 1px solid #888888; overflow: hidden;"><?php echo $prow['acct_title'];?></td>
                <td align="right" style="border: 1px solid #888888; overflow: hidden;"><?php echo $prow['amount'];?></td>
                <td style="border: 1px solid #888888; overflow: hidden;">&nbsp;</td>
            </tr>
            <?php } ?>
        </table>
    </form>
</div>
<script>
    function changeInput(){
        document.getElementById('nameSelect').style.display='none';
        document.getElementById('nameField').style.display='inline';
    }
</script>