<!doctype html>
<?php
include("config/dbc.php");

$result = mysql_query("SELECT idnum, lname, fname, mi, CONCAT(addb,', ',addt,', ',addp) AS 'addr',
    father, mother, bdate 
    FROM stud_info  WHERE idnum IN (SELECT idnum FROM stud_enrol) ORDER BY lname, fname");
echo mysql_error();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<html>
    <head>
        <meta charset="latin1_swedish_ci" />
        <title>Information Sheet</title>
    </head>
    <body>
        <table>
            <tr>
                <th>Name:</th>
                <th>ID No.:</th>
                <th>Course:</th>
                <th>Term Enrolled:</th>
                <th>Address:</th>
                <th>Father:</th>
                <th>Mother:</th>
                <th>Birth Date:</th>
            </tr>
<?php $n=1; ?>
<?php while($row=mysql_fetch_assoc($result)) { ?>
    <?php $res2 = mysql_query("SELECT c.cr_acrnm, sem FROM courses c, sems s, stud_enrol se
        WHERE c.cr_num=se.course AND se.sem_code=s.sem_code AND se.idnum={$row['idnum']} 
        ORDER BY se.sem_code LIMIT 1"); ?>
    
    <?php echo mysql_error(); ?>
    <?php $row2=mysql_fetch_assoc($res2); ?>
            <tr>
                <td><?php echo $n++ . ". " . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mi']; ?></td>
                <td><?php echo $row['idnum'];?></td>
                <td><?php echo $row2['cr_acrnm'];?></td>
                <td><?php echo $row2['sem'];?></td>
                <td><?php echo $row['addr'];?></td>
                <td><?php echo $row['father'];?></td>
                <td><?php echo $row['mother'];?></td>
                <td><?php echo $row['bdate'];?></td>
            </tr>
<?php } ?>

        </table>
    </body>
</html>