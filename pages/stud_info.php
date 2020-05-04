<?php include("library/checker.php");
include("library/lister.php"); ?>
<h1>Student Information</h1>

<div>
    <div style="border: 1px solid #444; background: #fff; padding: 10px;">
        <div class="div_title">Select Student</div>
        <div style="margin-top: 5px; padding-top: 10px;">
            ID Number: <input type="text" id="idnumsearch" size="6" maxlength="6" onchange="
				
				for(var i=0; i < document.studform.idnum.length; i++) {
					if(document.studform.idnum[i].value==document.getElementById('idnumsearch').value){
						document.studform.idnum[i].selected = true;
					}
				}
			" <?php check("idnum"); ?> />
            ==>

            <form method="post" name="studform" action="" style="display:inline-block">
                Name: <?php CreateList(
                            "idnum",
                            "idnum",
                            "fullname",
                            "SELECT idnum, CONCAT(lname, ', ',fname,' ' ,mi) AS 'fullname' 
				FROM stud_info ORDER BY lname, fname",
                            ""
                        ); ?>
                <input type="submit" value="Go" name="submit_open_stud_info" />
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['submit_open_stud_info'])) {
    $st = @mysqli_query($db, "SELECT * FROM stud_info WHERE idnum={$_POST['idnum']}");
    if (mysqli_num_rows($st) == 0) echo "<div class='error'>Fatal Error: Student Record Not Found!</div>";
    else $str = mysqli_fetch_assoc($st);

    $mos = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $bd = $str['bdate'];
    $yr = substr($bd, 0, 4);
    $mn = $mos[substr($bd, 5, 2) - 1];
    $dy = substr($bd, 8, 2);

    $birth_date = $mn . " " . $dy . ", " . $yr;
?>

    <div style="background: #fff;" id="stud_info">
        <input type="image" src="images/printButton.png" style="float:right;" class="noprint" onclick="printThisDiv('printFrame','stud_info');">
        <iframe id="printFrame" style="width:0px;height:0px;float:left;margin-left: -999px;"></iframe>
        <h3 align="center" style="width: 500px;">Student Information</h3>
        <table style="display: inline-block;margin-left:20px;">
            <tr>
                <td width="200"><strong>ID Number:</strong></td>
                <td><i><?php echo $str['idnum']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Name:</strong></td>
                <td><i><?php echo $str['lname'] . ", " . $str['fname'] . " " . $str['mi']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td><i><?php echo $str['addb'] . ", " . $str['addt'] . ', ' . $str['addp']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Gender:</strong></td>
                <td><i><?php echo $str['gender']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Date of birth:</strong></td>
                <td><i><?php echo $birth_date; ?></i></td>
            </tr>
            <tr>
                <td><strong>Civil Status:</strong></td>
                <td><i><?php echo $str['status']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Name of Father:</strong></td>
                <td><i><?php echo $str['father']; ?></i></td>
            </tr>
            <tr>
                <td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Occupation of Father:</strong></td>
                <td><i><?php echo $str['foccup']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Name of Mother:</strong></td>
                <td><i><?php echo $str['mother']; ?></i></td>
            </tr>
            <tr>
                <td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Occupation of Mother:</strong></td>
                <td><i><?php echo $str['moccup']; ?></i></td>
            </tr>
            <tr>
                <td><strong>Address of Parents:</strong></td>
                <td><i><?php echo $str['addparents']; ?></i></td>
            </tr>
        </table>
        <div style="width:150px;height:200px;float:right;margin-right:20px;">
        </div>
    </div>

<?php
}
?>