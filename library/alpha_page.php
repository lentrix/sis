<?php
function showPagination($url){
    $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N',
        'O','P','Q','R','S','T','U','V','W','X','Y','Z');
    echo "<span class='paging'>Page: ";
    foreach($letters as $lt){
        echo "[<a href='$url&alphapage=$lt'>$lt</a>] ";
    }
    echo "</span>";
}
?>
