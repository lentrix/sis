<?php
$sems = mysql_query("SELECT * FROM sems ORDER BY sem_code DESC");
echo "<div class='widget'>\n";
echo "<div class='widget_head'>Semesters</div>\n";
echo "<form method='post' action=''>\n";
echo "<center>";
echo "<select name='sem_code' style='margin-top: 5px;'>\n";
while($semrow=mysql_fetch_assoc($sems)){
	echo "<option value='{$semrow['sem_code']}'>{$semrow['sem']}</option>\n";
}
echo "</select>\n";
echo "<input type='submit' name='submit_set_semester' value='Set Semester'>\n</form>";
echo "</center>";
echo "</div>";
?>