<?php 
    include("singles.php");
    include("page_limit.php");
?>
<h1>Resolved Referrals</h1>

<br />
<div>
    <div class='div_title' style="cursor:pointer"
        onclick="
            if(document.getElementById('resolved').style.display=='block') document.getElementById('resolved').style.display='none';
            else document.getElementById('resolved').style.display='block';
        ">Resolved Referrals</div>
    <div id="resolved" style="display:none">
        <br/ >
        <?php 
            $sqls = "SELECT * FROM referral WHERE status='resolved' ORDER BY date DESC";
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
