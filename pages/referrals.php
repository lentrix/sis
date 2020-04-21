<?php include("library/lister.php");
include("library/singles.php"); ?>

<h1>Guidance Counselor Referrals</h1>

<?php
$stud_sql = "SELECT DISTINCT stud_info.idnum, CONCAT(lname,', ',fname,' ',mi) AS 'name'
        FROM stud_info, stud_enrol, sub_enrol
        WHERE stud_info.idnum = stud_enrol.idnum
        AND stud_enrol.idnum
        IN (
            SELECT idnum
            FROM sub_enrol, class
            WHERE sub_enrol.class_code = class.class_code
            AND class.tch_num ={$_SESSION['tch_num']}
            AND class.sem_code = {$_SESSION['sem_code']}
        )
        AND stud_enrol.sem_code ={$_SESSION['sem_code']}
        ORDER BY lname, fname, mi";

if (isset($_POST['submit_referral'])) {
    $idnum = $_POST['idnum'];
    $tch_num = $_SESSION['tch_num'];

    if (isset($_POST['tardiness'])) $tardiness = 1;
    else $tardiness = 0;
    if (isset($_POST['irreg_attn'])) $irreg_attn = 1;
    else $irreg_attn = 0;
    if (isset($_POST['emotional'])) $emotional = 1;
    else $emotional = 0;
    if (isset($_POST['undr_achv'])) $undr_achv = 1;
    else $undr_achv = 0;
    if (isset($_POST['others'])) $others = 1;
    else $others = 0;

    $desc = $_POST['desc'];

    $dt = date('Y-m-d');

    mysqli_query($db, "INSERT INTO referral (idnum, tch_num, date, tardiness, irreg_attn, emotional, undr_achv, others, referral.desc, status)
            VALUES ($idnum, $tch_num, '$dt', $tardiness, $irreg_attn, $emotional, $undr_achv, $others, '$desc','pending')");
    if (mysqli_error($db)) echo "<div class='error' style='display:block'>" . mysqli_error($db) . "</div>";
    else {
        echo "<div class='error' style='display:block'>Referral Submitted.</div>";
        $_REQUEST['idnum'] = -1;
    }
}
?>
<br />
<div>
    <div class="div_title">New Referral</div>
    <div>
        <table width="100%">
            <form method="post" action="">
                <tr>
                    <td>Select Student:</td>
                    <td><?php CreateList("idnum", "idnum", "name", $stud_sql, ""); ?></td>
                </tr>
                <tr>
                    <td>Observed Behaviour:</td>
                    <td>
                        <label style="color:#116"><input type="checkbox" name="tardiness">Tardiness</label>
                        <label style="color:#116"><input type="checkbox" name="irreg_attn">Irregular Attendance</label>
                        <label style="color:#116"><input type="checkbox" name="emotional">Emotional</label><br />
                        <label style="color:#116"><input type="checkbox" name="undr_achv">Under Achiever</label>
                        <label style="color:#116"><input type="checkbox" name="others">Others</label>
                    </td>
                </tr>
                <tr>
                    <td colspan=2>Detailed description of the act or incident:</td>
                </tr>
                <tr>
                    <td colspan=2><textarea name='desc' style="width:100%;height:150px;font-family:arial, helvetica, sans;"></textarea></td>
                </tr>
                <tr>
                    <td colspan=2 align="right"><input type="submit" name="submit_referral" value="Submit Referral" /></td>
                </tr>
            </form>
        </table>
    </div>
</div>

<br />
<div>
    <div class="div_title">Pending Referrals</div>
    <div>
        <table style="border-collapse:collapse; width:100%" border="1">
            <tr>
                <td class="thead" width="15%">Date:</td>
                <td class="thead" width="40%">Name of Student:</td>
                <td class="thead" width="30%">Behaviours:</td>
                <td class="thead" width="15%">Status:</td>
            </tr>
            <?php
            $ref = mysqli_query($db, "SELECT referral.* FROM referral WHERE tch_num={$_SESSION['tch_num']} AND status='pending' ORDER BY date DESC");
            while ($rfr = mysqli_fetch_assoc($ref)) {
                echo "<tr>
                <td>{$rfr['date']}</td>
                <td>" . getFullName($rfr['idnum']) . "</td>
                <td><ul type='disc'>";
                if ($rfr['tardiness'] == 1) echo "<li>Tardiness</li>";
                if ($rfr['irreg_attn'] == 1) echo "<li>Irregular Attendance</li>";
                if ($rfr['emotional'] == 1) echo "<li>Emotional</li>";
                if ($rfr['undr_achv'] == 1) echo "<li>Underachiever</li>";
                if ($rfr['others'] == 1) echo "<li>Others</li>";
                echo "  </ul></td>
                <td>{$rfr['status']}</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<br />
<div>
    <div class="div_title">Resolved Referrals</div>
    <div>
        <table style="border-collapse:collapse; width:100%" border="1">
            <tr>
                <td class="thead" width="15%">Date:</td>
                <td class="thead" width="40%">Name of Student:</td>
                <td class="thead" width="30%">Behaviours:</td>
                <td class="thead" width="15%">Status:</td>
            </tr>
            <?php
            $ref = mysqli_query($db, "SELECT referral.* FROM referral WHERE tch_num={$_SESSION['tch_num']} AND status='resolved' ORDER BY date DESC");
            while ($rfr = mysqli_fetch_assoc($ref)) {
                echo "<tr>
                <td>{$rfr['date']}</td>
                <td>" . getFullName($rfr['idnum']) . "</td>
                <td><ul type='disc'>";
                if ($rfr['tardiness'] == 1) echo "<li>Tardiness</li>";
                if ($rfr['irreg_attn'] == 1) echo "<li>Irregular Attendance</li>";
                if ($rfr['emotional'] == 1) echo "<li>Emotional</li>";
                if ($rfr['undr_achv'] == 1) echo "<li>Underachiever</li>";
                if ($rfr['others'] == 1) echo "<li>Others</li>";
                echo "  </ul></td>
                <td>{$rfr['status']}</td></tr>";
            }
            ?>
        </table>
    </div>
</div>