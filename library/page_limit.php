<?php
function PageLimit($sql, $pageName, $rows){
	$scount = mysql_query($sql);
	$scountr=mysql_fetch_row($scount);
	$pages = ($scountr[0]/$rows) + 1;
	
	if(isset($_GET['pagenum'])){
		echo "<center><span><a href='index.php?page=$pageName&pagenum=";
		if($_GET['pagenum']==1) echo "1"; else echo ($_GET['pagenum']-1);
		echo "' class=\"arrow_left\">&nbsp;</a> Page {$_GET['pagenum']}</span> <a href='index.php?page=$pageName&pagenum="; 
		if($_GET['pagenum']<$pages) echo ($_GET['pagenum']+1); else printf("%d",$pages);
		echo "' class='arrow_right'>&nbsp;</a><br/></center>";
	}
	echo "<center>";
	for($i=1; $i<=$pages; $i++){
		echo "[<a href='index.php?page=$pageName&pagenum=$i'>$i</a>]";
	}
	echo "</center>";
	if(isset($_GET['pagenum'])) $start = ($_GET['pagenum']-1) * $rows;
	else $start = 0;
	return "LIMIT $start, $rows";
}
?>