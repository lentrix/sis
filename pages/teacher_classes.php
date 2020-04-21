<?php include("library/population.php"); ?>
<style type="text/css">
	<!--
	-->
</style>
<h1>My Classes</h1>

<?php

include("library/singles.php");
include("library/classlist.php");

if (empty($_SESSION['tch_num'])) {
	echo "<div class='error'>You appear to have no teacher record.
			<br>Please inform your dean to create your teacher record.</div>";
} else {
?>

	<div id="teacher_classes">

		<input type="image" src="images/printButton.png" class="noprint" onclick="printThisDiv('printFrame','teacher_classes');" style="width:30px; height: 30px; float: right;" />
		<iframe id="printFrame" style="width:0px;height:0px; float:left; margin-left: -9999px"></iframe>

		<center>

			<?php
			$stm = "SELECT class_code, name, descript, sub_dt FROM subjects, class
        WHERE class.sub_code = subjects.sub_code
        AND tch_num={$_SESSION['tch_num']}
        AND sem_code={$_SESSION['sem_code']}";
			$tc_class = mysqli_query($db, $stm);
			if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "<br/>SQL: " . $stm . "</div>";
			$n = 1;
			?>
			<h2 style="width:400px;">Teaching Load</h2>
			<p style="margin-top: 0px">(<?php echo $_SESSION['fullname']; ?>)<br/>
				<?php getSemester($_SESSION['sem_code']); ?>
			</p>

			<table>
				<tr>
					<td class="thead" width="30">#</td>
					<td class="thead" width="70">Name:</td>
					<td class="thead" width="280">Description:</td>
					<td class="thead" width="150">Time/Room:</td>
					<td class="thead" width="40">Num:</td>
					<td class="thead" width="50">Submitted:</td>
				</tr>
				<?php while ($tcrow = mysqli_fetch_assoc($tc_class)) { ?>
					<?php $time = getClassTimeRoom($tcrow['class_code'], true); ?>
					<tr>
						<td class="tcel"><?php echo $n++ . "."; ?></td>
						<td class="tcel">
							<a href="index.php?page=teacher_classes&class_code=<?php echo $tcrow['class_code']; ?>" style="font-size: 9pt;">
								<?php echo $tcrow['name']; ?>
							</a>
						</td>
						<td class="tcel"><?php echo $tcrow['descript']; ?></td>
						<td class="tcel"><?php echo $time; ?></td>
						<td class="tcel" align="center"><?php echo getClassPopulation($tcrow['class_code']); ?></td>
						<td class="tcel"><?php echo ($tcrow['sub_dt']) ? date('M-d-y', strtotime($tcrow['sub_dt'])) : "not submitted"; ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td colspan="5" class="tfoot">&nbsp;</td>
				</tr>
			</table>
		</center>
	</div>
	<?php if (isset($_GET['class_code'])) { ?>
		<div id="class_list">
			<input type="image" src="images/printButton.png" class="noprint" onclick="printThisDiv('printFrame2','class_list');" style="width:30px; height: 30px; float: right;" />
			<iframe id="printFrame2" style="width:0px;height:0px; float:left; margin-left: -9999px"></iframe>
			<center>
				<h3 style="width: 400px;"><?php echo getClassDetails($_GET['class_code'], true); ?></h3>
				<p style="text-align: center; margin-top: 0px;"><?php getSemester($_SESSION['sem_code']); ?></p>
				<?php showClassList($_REQUEST['class_code']); ?>
			</center>
			<a href="index.php?page=teacher_classes_xml&class_code=<?php echo $_REQUEST['class_code']; ?>">Create XML File</a>
		</div>
	<?php 	} ?>
<?php } ?>