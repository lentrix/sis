<?php include("library/singles.php"); ?>
<?php
$stm = "SELECT c.class_code, name, descript,
		(SELECT COUNT(se.idnum) FROM sub_enrol se WHERE se.class_code=c.class_code) AS 'num'
		FROM class c
		JOIN subjects s USING(sub_code)
		WHERE c.sem_code={$_SESSION['sem_code']} AND tch_num={$_GET['tch_num']}
		ORDER BY descript";

$tc_class = mysqli_query($db, $stm);
echo mysqli_error($db);
$n = 1;

if (isset($_SESSION['view_edit_class'])) $page = "view_edit_class";
else $page = "view_class";

?>
<h1>View Teaching Load</h1>

<div id="teaching_load">
	<input type="image" src="images/printButton.png" class="noprint" style="float: right;" onClick="printThisDiv('printFrame','teaching_load')" />
	<center>
		<h2 style="width: 500px;">Teaching Load</h2>
		<span style="display:block;"><?php echo getTeacherFullName($_GET['tch_num']); ?></span>
		<span style="display:block;"><?php echo getSemester($_SESSION['sem_code']); ?></span>
		<table>
			<tr>
				<td class="thead" width="50">#</td>
				<td class="thead" width="100">Course No.:</td>
				<td class="thead" width="250">Description:</td>
				<td class="thead" width="150">Time/Room:</td>
				<td class="thead" width="80">No. of Students:</td>
			</tr>
			<?php while ($tcrow = mysqli_fetch_assoc($tc_class)) { ?>
				<?php $time = getClassTimeRoom($tcrow['class_code'], true); ?>
				<tr>
					<td class="tcel"><?php echo $n++ . "."; ?></td>
					<td class="tcel"><?php echo $tcrow['name']; ?></td>
					<td class="tcel">
						<a href="index.php?page=<?php echo $page; ?>&class_code=<?php echo $tcrow['class_code']; ?>" style="font-size: 9pt;text-decoration:none;font-weight:bold">
							<?php echo $tcrow['descript']; ?>
						</a>
					</td>
					<td class="tcel"><?php echo $time; ?></td>
					<td class="tcel" align="center"><?php echo $tcrow['num']; ?></td>
				</tr>
			<?php } ?>
		</table>
	</center>
</div>
<iframe id="printFrame" style="width:0px; height:0px; float:left; margin-left: -9999px;">
</iframe>