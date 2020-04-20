<?php 
    include("library/singles.php");
    include("library/page_limit.php");
    
    if(isset($_POST['submit_view_pending_referrals'])) {
        $_SESSION['referral_status']='pending';
    }else if(isset($_POST['submit_view_resolved_referrals'])) {
        $_SESSION['referral_status']='resolved';
    }else if(isset($_POST['submit_view_archived_referrals'])) {
        $_SESSION['referral_status'] = "archived";
    }else {
        if(!isset($_SESSION['referral_status'])) $_SESSION['refferal_status']='pending';
    }
    
    $status = $_SESSION['referral_status'];
?>

<h1>View Referrals</h1>

<br />
<div>
    <div class='div_title' style="text-transform: uppercase"><?php echo $status; ?> Referrals</div>
    <div>
        <br/ >
        <?php 
            $sqls = "SELECT * FROM referral WHERE status='$status' ORDER BY date DESC";
            $limit = PageLimit($sqls,"view_referrals",10);
        ?>    
        <table style="border-collapse: collapse; width: 100%" border="1">
            <tr>
                <td class="thead" width="15%">Date:</td>
                <td class="thead" width="30%">Name:</td>
                <td class="thead" width="30%">Referred by:</td>
                <td class="thead" width="25%">Observed Behaviour:</td>
            </tr>
            <?php
                $rfs = mysql_query($sqls . " " . $limit);
                while($rfr=mysql_fetch_assoc($rfs)) {
                    echo "<tr style='background: #fff;'>
                        <td>{$rfr['date']}</td>
                        <td><a href='index.php?page=view_referral&idnum={$rfr['idnum']}' 
                            style='text-decoration:none; font-weight:bold;'>" . getFullName($rfr['idnum']) . "</a></td>
                        <td>" . getTeacherFullName($rfr['tch_num']) . "</td>
                        <td><ul type='disc' style='padding-left:20px;'>";
                    if($rfr['tardiness']==1) echo "<li>Tardiness</li>";
                    if($rfr['irreg_attn']==1) echo "<li>Irregular Attendance</li>";
                    if($rfr['emotional']==1) echo "<li>Emotional</li>";
                    if($rfr['undr_achv']==1) echo "<li>Underachiever</li>";
                    if($rfr['others']==1) echo "<li>Others</li>";
                    echo "  </ul></td>";
                }
            ?>
        </table>
    </div>
</div>

<div>
    <form method="post" action="">
        <input type="hidden" name="referral_status" value="<?php echo $_POST['referral_status'];?>" />
        <input type="submit" name="submit_view_pending_referrals" value="View Pending Referrals" />
        <input type="submit" name="submit_view_resolved_referrals" value="View Resolved Referrals" />
        <input type="submit" name="submit_view_archived_referrals" value="View Archived Referrals" />
    </form>
</div>


