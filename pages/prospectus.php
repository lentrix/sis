<h1>Prospectus</h1>

<span style="display:block;float:right">
	<input type="button" onclick="document.location='index.php?page=prospectus_builder'" value="Create Prospectus" />
</span>

<div>
<?php
$mods = mysql_query("SELECT * FROM modassgn WHERE user='{$_SESSION['user']}' AND modlno=1");
if(mysql_num_rows($mods)!=1) $college_filter = "AND courses.clg_no={$_SESSION['clg_no']}";
else $college_filter="";

$pr = mysql_query("SELECT prp_code, courses.course, prospectus.year FROM prospectus, courses
		WHERE prospectus.course=courses.cr_num
		$college_filter ORDER BY course");
echo "<table>";
while($prow=mysql_fetch_assoc($pr)) {
	echo "<tr><td width='350'>{$prow['course']}</td>
			<td width='50'>{$prow['year']}</td>
			<td width='50'>
				<form method='post' action='index.php?page=prospectus_builder' style='display:inline'>
					<input type='hidden' value='{$prow['prp_code']}' name='prp_code' />
					<input type='submit' name='open' value='Open' style='display:inline' />
				</form>
			</td>
			</tr>";
}
echo "</table>";
?>
</div>
