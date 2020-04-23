<?php

function getInsertPreviousOrdinal($transcriptSemId, $idnum) {
    global $db;
    $sems = $db->query("SELECT id, ordinal FROM transcript_sem WHERE idnum=$idnum ORDER BY ordinal");

    $data = $sems->fetch_all(MYSQLI_ASSOC);

    if($data[0]['id']==$transcriptSemId) {
        return $data[0]['ordinal'] - 500;
    }

    $previousOrdinal = 0;
    $currentOrdinal = $data[0]['ordinal'];
    foreach($data as $sem) {
        $currentOrdinal = $sem['ordinal'];
        if($sem['id']!=$transcriptSemId) {
            $previousOrdinal = $currentOrdinal;
        }else {
            break;
        }
    }

    return $currentOrdinal - (abs($currentOrdinal-$previousOrdinal) / 2);
}

function getInsertNextOrdinal($transcriptSemId, $idnum) {
    global $db;
    $sems = $db->query("SELECT id, ordinal FROM transcript_sem WHERE idnum=$idnum ORDER BY ordinal");

    while($sem = $sems->fetch_object()) {
        if($sem->id == $transcriptSemId) {
            $next = $sems->fetch_object();
            if($next) {
                return $sem->ordinal + (abs($sem->ordinal - $next->ordinal) /2);
            }else {
                return $sem->ordinal + 500;
            }
        }
    }
}

function moveDown($transcriptSemId) {
    global $db;
    $theSem = $db->query("SELECT * FROM transcript_sem WHERE id=$transcriptSemId")->fetch_object();

    $nextSem = $db->query("SELECT * FROM transcript_sem
            WHERE idnum=$theSem->idnum AND ordinal>$theSem->ordinal ORDER BY ordinal")->fetch_object();

    if($nextSem) {
        $db->query("UPDATE transcript_sem SET ordinal = $theSem->ordinal WHERE id=$nextSem->id");
        $db->query("UPDATE transcript_sem SET ordinal = $nextSem->ordinal WHERE id=$theSem->id");
    }
}

function moveUp($transcriptSemId) {
    global $db;

    $theSem = $db->query("SELECT * FROM transcript_sem WHERE id=$transcriptSemId")->fetch_object();

    $prevSem = $db->query("SELECT * FROM transcript_sem
            WHERE idnum=$theSem->idnum AND ordinal<$theSem->ordinal ORDER BY ordinal DESC")->fetch_object();

    if($prevSem) {
        $db->query("UPDATE transcript_sem SET ordinal=$prevSem->ordinal WHERE id=$theSem->id");
        $db->query("UPDATE transcript_sem SET ordinal=$theSem->ordinal WHERE id=$prevSem->id");
    }
}

