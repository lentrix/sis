<?php

include("library/lister.php");

if(isset($_POST['submit_poa_auth_num'])){
    $cr_nums = $_POST['cr_num'];
    $po_auth_nums = $_POST['po_auth_num'];
    $n = count($cr_nums);
    $has_errors = false;
    for($i=0; $i<$n; $i++){
        mysql_query("UPDATE courses SET po_auth_num={$po_auth_nums[$i]} 
        WHERE cr_num={$cr_nums[$i]}");
        if(mysql_error()) {
            echo "<div class='error'>" .mysql_error () . "</div>";
            $has_errors = true;
        }
    }
    if(!$has_errors){
        echo "<div class='msg'>Program Authorization Numbers updated.</div>";
    }
}

if(isset($_POST['add_graduate'])) { 
    foreach($_POST as $key=>$val) $$key = addslashes($val);
    $sql = "INSERT INTO grad_details (idnum, sem_code, poa_auth_num, cr_num, dt_grad, year_granted) 
        VALUES (
            $idnum,
            {$_SESSION['sem_code']},
            '$po_auth_num',
            $cr_num,
            '$dt_grad',
            $year
        )";
    //echo $sql;
    mysql_query($sql);
    if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
    else {
        echo "<div class='msg'>A graduate has been added.</div>";
    }
}

if(isset($_POST['confirm_delete_grad'])) {
    mysql_query("DELETE FROM grad_details WHERE id={$_POST['id']}");
    if(mysql_error()) echo "<div class='error'>" . mysql_error () . "</div>";
    else echo "<div class='msg'>The Student has been deleted from the graduates list.</div>";
}
   
?>

<?php if(isset($_POST['delete_grad'])) { ?>
<div class="error">
    You are about to delete this student from the graduates list: <br />
    <?php echo $_POST['name']; ?>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />
        <input type="submit" name="confirm_delete_grad" value="Delete" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>
</div>
<?php } ?>

<h1>Graduates Report</h1>

<div id="students" class="clipable" style="height: auto;">
    <a href="javascript:void(0)" onclick="expandContract(document.getElementById('students'));"
       style="float: right; text-decoration: none">
        >>>
    </a>
    <h3>Students</h3>
    <span>Select students and enter details.</span>
    <span style="float: right; margin-top: -3px;">
        <form method="post" action="pages/generate_graduates_report.php" target="_blank" style="display: inline">
            <input type="hidden" name="sem_code" value="<?php echo $_SESSION['sem_code']; ?>" />
            <input type="submit" name="generate_graduates" value="Generate Gradutes Report" />
        </form>
    </span>
    <hr />
    
    <div style="width: 300px; height: 300px; overflow: hidden; float: left; border: 0px;">
        <strong>List of students</strong><br />
        
        <form method="post" action="">
            <?php $sql = "SELECT i.idnum, i.idext, CONCAT(lname, ', ', fname,' ', mi,' ',c.cr_acrnm,' - ', e.year) AS name 
            FROM stud_info i, stud_enrol e, courses c
            WHERE i.idnum=e.idnum 
            AND e.course=c.cr_num
            AND e.sem_code={$_SESSION['sem_code']}
            AND i.idnum NOT IN (SELECT idnum FROM grad_details WHERE sem_code={$_SESSION['sem_code']})
            ORDER BY lname, fname"; ?>
            <?php CreateList("idnum", "idnum", "name", $sql, "font-size: 13px;","width:250px"); ?>
            <input type="submit" value="OK" name="select_student" style="font-size: 13px;" />
        </form>
        <?php if(isset($_POST['select_student'])) { ?>
            <?php $std = mysql_query("SELECT CONCAT(i.lname,', ', i.fname,' ',i.mi) AS 'name', i.idnum, 
                i.idext, c.course, c.po_auth_num, e.course as 'cr_num', major
                FROM stud_info i, stud_enrol e, courses c
                WHERE i.idnum=e.idnum AND e.course=c.cr_num
                AND i.idnum = {$_POST['idnum']}"); ?>
            <?php echo mysql_error(); ?>
            <?php $strow = mysql_fetch_assoc($std); ?>
        <form method="post" action="">
            <strong>ID Number:</strong><?php echo $strow['idnum'] . "-" . $strow['idext']; ?><br />
            <strong>Name:</strong> <?php echo $strow['name']; ?><br />
            <strong>Authorization Code:</strong> <?php echo $strow['po_auth_num']; ?><br />
            <strong>Program:</strong> <?php echo $strow['course']; ?><br />
            <strong>Program Major:</strong> <?php echo $strow['major']; ?><br />
            <strong>Date Graduated:</strong> <input type="text" name="dt_grad" style="width: 100px;" placeholder="yyyy-mm-dd" /><br />
            <strong>Year granted:</strong>
            <input type="text" name="year" style="width: 100px" value="<?php echo date('Y'); ?>" />
            
            <input type="hidden" name="idnum" value="<?php echo $strow['idnum']; ?>" />
            <input type="hidden" name="po_auth_num" value="<?php echo $strow['po_auth_num']; ?>" />
            <input type="hidden" name="cr_num" value="<?php echo $strow['cr_num']; ?>" /><br />
            <input type="submit" name="add_graduate" value="Add Graduate >>" />
            <input type="submit" name="cancel" value="Cancel" />
        </form>
        <?php } ?>
    </div>
    
    <div style="width: 380px; float: right; border: 0px; overflow: auto; border-left: 1px solid #888">
        <strong>List of Graduates</strong><br />
        <?php $grad = mysql_query("SELECT g.id, CONCAT(i.lname,', ',i.fname,' ', i.mi,' [', c.cr_acrnm,']') AS 'grad' 
            FROM stud_info i, courses c, grad_details g
            WHERE i.idnum=g.idnum AND g.cr_num=c.cr_num
            AND g.sem_code={$_SESSION['sem_code']}
            ORDER BY lname, fname, mi")  ?>
        <ol type="1">
        <?php while($grow = mysql_fetch_assoc($grad)) { ?>
            <li onmouseover="document.getElementById('<?php echo $grow['id']; ?>').style.visibility='visible'"
                onmouseout="document.getElementById('<?php echo $grow['id']; ?>').style.visibility='hidden'"
                style="font-size: 12px;">
                <?php echo $grow['grad']; ?>
                <form method="post" action="" style="display: inline; visibility: hidden" id="<?php echo $grow['id']; ?>">
                    <input type="hidden" name="id" value="<?php echo $grow['id']; ?>" />
                    <input type="hidden" name="name" value="<?php echo $grow['grad']; ?>" />
                    <input type="submit" name="delete_grad" value="X"
                           style="font-size: 10px;"/>
                </form>
            </li>
        <?php } ?>
        </ol>
        
    </div>
</div>

<div id="poa" class="clipable">
    <a href="javascript:void(0)" onclick="expandContract(document.getElementById('poa'));"
       style="float: right; text-decoration: none">
        >>>
    </a>
    <h3>Program to Operate Authorization Numbers</h3>
    
    <form method="post" action="">
        <table>
            <tr>
                <th colspan="2">Course</th>
                <th>Authorization No.</th>
            </tr>
<?php $result = mysql_query("SELECT * FROM courses ORDER BY clg_no, cr_acrnm"); ?>
<?php while($row = mysql_fetch_assoc($result)) { ?>
            <input type="hidden" name="cr_num[]" value="<?php echo $row['cr_num']; ?>" />
            <tr>
                <td width="100">
                    <?php echo $row['cr_acrnm']; ?>
                </td>
                <td width="250">
                    <?php echo $row['course']; ?>
                </td>
                <td width="100">
                    <input type="text" name="po_auth_num[]" value="<?php echo $row['po_auth_num']; ?>"
                        style="width:80px; border: 1px solid #333"/>
                </td>
            </tr>
<?php } ?>
        </table>
        <input type="submit" name="submit_poa_auth_num" value="Update Authorization Numbers" />
    </form>
</div>



<script>
function expandContract(obj){
    if(obj.style.height=='40px') {
        obj.style.height = 'auto';
        obj.style.backgroundImage = 'url(../images/triangle_up.png)';
    }else{
        obj.style.height = '40px';
        obj.style.backgroundImage = 'url(../images/triangle_down.png)';
    }
}    
</script>
