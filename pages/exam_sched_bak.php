<?php

function doesNotContain($ids, $idlist){
    while($idsr=mysql_fetch_row($ids)) {
        foreach($idlist as $id){
            if($id==$idsr[0]) return false;
        }
    }
    return true;
}

function renewList() {
    $subs=mysql_query("SELECT DISTINCT sub_code FROM class WHERE sem_code={$_SESSION['sem_code']}");
    mysql_query("DELETE FROM sub_batch");
    while($subr=mysql_fetch_assoc($subs)) {
        mysql_query("INSERT INTO sub_batch (batch,sub_code) VALUES (0,{$subr['sub_code']})");
    }
}

function getSubs($batch) {
    $subb=mysql_query("SELECT sub_batch.sub_code, name, descript, 
            (SELECT COUNT(sub_enrol.class_code) FROM sub_enrol WHERE sub_enrol.sub_code=sub_batch.sub_code) AS 'num'
            FROM sub_batch, subjects
            WHERE sub_batch.sub_code=subjects.sub_code
            AND batch=$batch
            ORDER BY num DESC");
    return $subb;
}

if(isset($_POST['renew'])) {
    renewList();
}

if(isset($_POST['exclude'])) {
    foreach($_POST as $name=>$post) {
        if($name!="exclude"){
            mysql_query("UPDATE sub_batch SET batch=-1 WHERE sub_code=$name");
            if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
        }
    }
}

if(isset($_POST['restore'])) {
    foreach($_POST as $name=>$post) {
        if($name!="restore"){
            mysql_query("UPDATE sub_batch SET batch=0 WHERE sub_code=$name");
            if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
        }
    }
}

if(isset($_POST['restore_batch'])) {
    mysql_query("UPDATE sub_batch SET batch=0 WHERE batch NOT IN (0,-1)");
}

if(isset($_POST['toperize'])) {

    $subb=mysql_query("SELECT sub_batch.sub_code,
            (SELECT COUNT(sub_enrol.class_code) FROM sub_enrol WHERE sub_enrol.sub_code=sub_batch.sub_code) AS 'num'
            FROM sub_batch
            WHERE batch=0
            ORDER BY num DESC");
    
    while(mysql_num_rows($subb)>0) {    
        $idlist = array();
        
        $maxbatch = mysql_query("SELECT MAX(batch) FROM sub_batch");
        $maxbatchr=mysql_fetch_row($maxbatch);            
        $nextBatch = $maxbatchr[0]+1;

        while($subbr=mysql_fetch_assoc($subb)) {
            $ids=mysql_query("SELECT idnum FROM sub_enrol WHERE sub_code={$subbr['sub_code']} AND sem_code={$_SESSION['sem_code']}");
            $ids2 = mysql_query("SELECT idnum FROM sub_enrol WHERE sub_code={$subbr['sub_code']} AND sem_code={$_SESSION['sem_code']}");
            
            if(doesNotContain($ids,$idlist)) {
                
                while($idsr=mysql_fetch_assoc($ids2)) {
                    $idlist[] = $idsr['idnum'];
                }
                
                mysql_query("UPDATE sub_batch SET batch=$nextBatch WHERE sub_code={$subbr['sub_code']}");
                if(mysql_error()) echo "<div class='error'>" . mysql_error() . "</div>";
            }
        }
        
        $subb=mysql_query("SELECT sub_batch.sub_code,
            (SELECT COUNT(sub_enrol.class_code) FROM sub_enrol WHERE sub_enrol.sub_code=sub_batch.sub_code) AS 'num'
            FROM sub_batch
            WHERE batch=0
            ORDER BY num DESC");
    }
}
?>

<h1>Examination Scheduler</h1>

<!--   GENERATED SUBJECT BATCH ------------------->

<div style="float: left; display: block; clear: all;border: 0px; padding: 0px;">
    <form action="" method="post">
        <input type="submit" value="Renew List" name="renew" />
        <input type="submit" value="Toperize" name="toperize" />
    </form>
</div>

<div class="exam_sched_table" style="float:right; height:400px; clear: left;">
    <strong>Generated Subject Batch:</strong>
    <table style="border-collapse:collapse;" border=1>
        <tr>
            <th width="200">Subject:</th>
            <th width="100">Number:</th>
            <th width="50">Batch</th>
        </tr>
<?php
    $subb=mysql_query("SELECT sub_batch.sub_code, descript, batch,
            (SELECT COUNT(sub_enrol.class_code) FROM sub_enrol WHERE sub_enrol.sub_code=sub_batch.sub_code) AS 'num'
            FROM sub_batch, subjects
            WHERE sub_batch.sub_code=subjects.sub_code
            AND batch>0
            ORDER BY batch ASC, num DESC");
    while($subbr=mysql_fetch_assoc($subb)) {  ?>
        <tr style="background: <?php if(($subbr['batch']%2)==1) echo "#ffb"; else echo "#cf9"?>">
            <td><?php echo $subbr['descript'];?></td>
            <td><?php echo $subbr['num'];?></td>
            <td><?php echo $subbr['batch'];?></td>
        </tr>
<?php
    }
?>      
    </table>
    <form method="post" action="">
        <input type="submit" value="Restore" name="restore_batch" />
    </form>
</div>

<!---    UNASSIGNED SUBJECTS ------------------ -->

<div class="exam_sched_table" style="height: 250px; clear: left;">
    <strong>Unassigned Subjects:</strong>
    <form method="post" action="">
    <table style="border-collapse:collapse;" border=1>
        <tr>
            <th width="30">*</th>
            <th width="250">Subject:</th>
            <th width="50">Number:</th>
        </tr>
<?php
    $subb=getSubs(0);
    while($subbr=mysql_fetch_assoc($subb)) {  ?>
        <tr onMouseOver="this.style.backgroundColor='#999'"
            onMouseOut="this.style.backgroundColor='#fff'">
            <td><input type="checkbox" name="<?php echo $subbr['sub_code'];?>" id="<?php echo $subbr['sub_code'];?>" /></td>
            <td><label for="<?php echo $subbr['sub_code'];?>"><?php echo $subbr['descript'];?></label></td>
            <td><?php echo $subbr['num'];?></td>
        </tr>
<?php
    }
?>
        
    </table>
    <input type="submit" value="Exclude" name="exclude" />
    </form>

</div>

<!--    EXCLUDED SUBJECTS ---------------------------->

<div class="exam_sched_table" style="height: 200px;">
    <strong>Excluded Subjects:</strong>
    <form method="post" action="">
    <table style="border-collapse:collapse;" border=1>
        <tr>
            <th width="30">*</th>
            <th width="250">Subject:</th>
            <th width="50">Number:</th>
        </tr>
<?php
    $subb=getSubs(-1);
    while($subbr=mysql_fetch_assoc($subb)) {  ?>
        <tr onMouseOver="this.style.backgroundColor='#999'"
            onMouseOut="this.style.backgroundColor='#fff'">
            <td><input type="checkbox" name="<?php echo $subbr['sub_code']; ?>" id="<?php echo $subbr['sub_code']; ?>" />
            <td><label for="<?php echo $subbr['sub_code']; ?>"><?php echo $subbr['descript'];?></label></td>
            <td><?php echo $subbr['num'];?></td>
        </tr>
<?php
    }
?>
        
    </table>
    <input type="submit" value="Restore" name="restore" />
    </form>
</div>

