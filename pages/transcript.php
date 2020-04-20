<?php
include("library/lister.php");

function printTranscript($idnum, $sem_code){
    $rc = mysql_query("SELECT s.name, s.descript, se.rating, c.cunits
        FROM subjects s, sub_enrol se, class c, stud_enrol st
        WHERE s.sub_code=c.sub_code
        AND se.class_code=c.class_code
        AND se.idnum=st.idnum
        AND se.sem_code=c.sem_code
        AND se.sem_code=st.sem_code
        AND se.sem_code=$sem_code
        AND st.idnum=$idnum");
    return $rc;
}

function compute_weighted_average($ratings, $units){
    if(in_array("-", $ratings) || in_array("dr.", $ratings) ||in_array("wd", $ratings) ||in_array("NaN", $ratings)){
        return "-";
    }else {
        $weightedRating = 0;
        $sumOfWeights = 0;
        for($i=0; $i<count($ratings); $i++) {
            $weightedRating += $ratings[$i]*$units[$i];
            $sumOfWeights += $units[$i];
        }
        return ($sumOfWeights>0) ? $weightedRating / $sumOfWeights : 'n/a';
    }
}
?>

<h1>Transcript Generator</h1>

<?php if(!isset($_POST['idnum'])) { ?>
<form method="post" action="">
    <?php $sql = "SELECT idnum, CONCAT(lname, ', ', fname, ' ', mi) AS 'name' 
        FROM stud_info ORDER BY lname"; ?>
    <?php CreateList("idnum","idnum","name","$sql","",false); ?>
    <input type="submit" name="transcript" value="Go" />
</form>

<?php } else { ?>
<form method="post" action="" style="float: right">
    <input type="submit" value="X" />
</form>
    <?php $info=mysql_query("SELECT CONCAT(lname,', ',fname,' ',mi) AS 'name' 
        FROM stud_info WHERE idnum={$_POST['idnum']}"); ?>
    <?php $inr=mysql_fetch_assoc($info); ?>
<h2 align="center">Transcript of Records</h2>
<p><strong>Name: </strong><?php echo $inr['name'];?></p>
<hr />

    <?php $sems=mysql_query("SELECT s.*, c.course  FROM sems s, stud_enrol se, courses c
        WHERE s.sem_code=se.sem_code
        AND se.course=c.cr_num
        AND se.idnum={$_POST['idnum']}"); ?>
    <?php echo mysql_error();?>
    <?php $ogwt = 0; $semCount=0; ?>
    <?php while($sr=mysql_fetch_assoc($sems)) { ?>

<p><strong><?php echo $sr['sem'] . ", MATER DEI COLLEGE, Tubigon, Bohol";?></strong></p>
        <?php $rec = printTranscript($_POST['idnum'], $sr['sem_code']); ?>
<table style="border-collapse: collapse">
        <?php $ratings = []; $units_array=[]; ?>
        <?php while($rrow=mysql_fetch_assoc($rec)) { ?>
    <tr>
        <td width="150"><?php echo $rrow['name'];?></td>
        <td>&nbsp;</td>
        <td width="400"><?php echo $rrow['descript'];?></td>
        <td width="100"><?php echo $rrow['rating'];?></td>
        <?php if($rrow['rating']>3.0 || $rrow['rating']=='-' || 
                $rrow['rating']=='dr' || $rrow['rating']=='wd' ||
                empty($rrow['rating']) || $rrow['rating']=="NaN") { 
            $units=0;
        }else $units = $rrow['cunits']; ?>
        <td>&nbsp;</td>
        <td width="100"><?php echo $units;?></td>
    </tr> 
            <?php $ratings[] = $rrow['rating']; $units_array[]=$units; ?>
        <?php } ?>
</table>
<p>
Semestral Weighted Average: <?= $swa = compute_weighted_average($ratings, $units_array) ?>  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php 
$semCount++;
if($swa === "-" || $ogwt === "-") {
    $ogwt = "-";
}else {
    $ogwt += $swa;
}
?>
Overal Weighted Average: <?= $ogwt/$semCount; ?>
</p>
    <?php } ?>
<?php } ?>
