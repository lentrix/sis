<?php include("library/lister.php"); ?>
<?php include("library/checker.php"); ?>
<?php include("library/population.php"); ?>
<?php include("library/singles.php"); ?>
<?php include("library/computations.php"); ?>

<h1>Enrolment</h1>

<div>
	<div style="border: 1px solid #444; background: #fff; padding: 10px;">
		<div class="div_title">Select Student</div>
		<div style="margin-top: 5px; padding-top: 10px;">
			ID Number: <input type="text" id="idnumsearch" size="6" maxlength="6" onchange="
				
				for(var i=0; i < document.studform.idnum.length; i++) {
					if(document.studform.idnum[i].value==document.getElementById('idnumsearch').value){
						document.studform.idnum[i].selected = true;
					}
				}
			" <?php check("idnum"); ?> />
			==>

			<form method="post" name="studform" action="" style="display:inline-block">
				Name: <?php CreateList(
							"idnum",
							"idnum",
							"fullname",
							"SELECT idnum, CONCAT(lname, ', ',fname,' ' ,mi) AS 'fullname' 
				FROM stud_info ORDER BY lname, fname",
							""
						); ?>
				<input type="submit" value="Go" name="submit_idnum" />
			</form>
		</div>
	</div>
	<?php
	if (isset($_POST['submit_confirm_delete_enrol'])) {
		mysqli_query($db, "DELETE FROM stud_enrol WHERE idnum={$_POST['idnum']} AND sem_code={$_SESSION['sem_code']}");
		if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
		else echo "<div class='error'>Enrolment Record deleted.</div>";
	}
	if (isset($_POST['submit_confirm_withdraw_enrol'])) {
		mysqli_query($db, "UPDATE stud_enrol SET en_status='withdrawn' WHERE idnum={$_POST['idnum']} AND sem_code={$_SESSION['sem_code']}");
		mysqli_query($db, "UPDATE sub_enrol SET rating='W' WHERE idnum={$_POST['idnum']} AND sem_code={$_SESSION['sem_code']}");
		if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
		else echo "<div class='error'>Enrolment Record Withdrawn</div>";
	}

	if (isset($_REQUEST['idnum'])) {
		$en = mysqli_query($db, "SELECT * FROM stud_enrol WHERE idnum={$_REQUEST['idnum']} AND sem_code={$_SESSION['sem_code']}");
		if (mysqli_num_rows($en) && !isset($_POST['submit_enrol'])) {
			$enr = mysqli_fetch_assoc($en);
			$_REQUEST['course'] = $enr['course'];
			$_REQUEST['year'] = $enr['year'];
			$_REQUEST['en_status'] = $enr['en_status'];
		}
		if (isset($_POST['submit_delete_enrol'])) { ?>
			<div>
				<span style="color: #f33">Are you sure you want to delete this enrolment record?</span><br />
				<form method="post" action="">
					<input type="hidden" name="idnum" value="<?php echo $_POST['idnum']; ?>" />
					<input type="submit" name="submit_confirm_delete_enrol" value="Confirm" />
					<input type="submit" name="cancel" value="Cancel" />
				</form>
			</div>
		<?php
		}
		if (isset($_POST['submit_withdraw_enrol'])) { ?>
			<div>
				<span style="color: #f33">You are about to withdraw this enrolment record.</span><br />
				<form method="post" action="">
					<input type="hidden" name="idnum" value="<?php echo $_POST['idnum']; ?>" />
					<input type="submit" name="submit_confirm_withdraw_enrol" value="Confirm" />
					<input type="submit" name="cancel" value="Cancel" />
				</form>
			</div>
		<?php
		}
		?>
		<div style="border: 1px solid #444; background: #fff; padding: 10px;">
			<div class="div_title">Course & Year:</div>
			<div style="margin-top: 5px; padding-top: 10px;">
				<form action="" method="post" style="display: inline-block">
					<input type="hidden" name="idnum" value="<?php echo $_REQUEST['idnum']; ?>" />
					Course: <?php CreateList("course", "cr_num", "cr_acrnm", "SELECT cr_num, cr_acrnm FROM courses ORDER BY cr_acrnm", ""); ?>
					Year:
					<select name="year">
						<option></option>
						<option <?php if (isset($_REQUEST['year']) && $_REQUEST['year'] == 1) echo " selected"; ?>>1</option>
						<option <?php if (isset($_REQUEST['year']) && $_REQUEST['year'] == 2) echo " selected"; ?>>2</option>
						<option <?php if (isset($_REQUEST['year']) && $_REQUEST['year'] == 3) echo " selected"; ?>>3</option>
						<option <?php if (isset($_REQUEST['year']) && $_REQUEST['year'] == 4) echo " selected"; ?>>4</option>
						<option <?php if (isset($_REQUEST['year']) && $_REQUEST['year'] == 'Q') echo " selected"; ?>>Q</option>
					</select>
					Status:
					<select name="en_status">
						<option></option>
						<option<?php if (isset($_REQUEST['en_status']) && $_REQUEST['en_status'] == 'regular') echo " selected"; ?>>regular</option>
							<option<?php if (isset($_REQUEST['en_status']) && $_REQUEST['en_status'] == 'new') echo " selected"; ?>>new</option>
								<option<?php if (isset($_REQUEST['en_status']) && $_REQUEST['en_status'] == 'transferee') echo " selected"; ?>>transferee</option>
					</select>
					<input type="submit" name="submit_enrol" value="Update" />
				</form>
				<?php if (mysqli_num_rows($en)) { ?>
					<form action="" method="post" style="display:inline-block; float: right;">
						<input type="hidden" name="idnum" value="<?php echo $_REQUEST['idnum']; ?>" />
						<input type="submit" name="submit_delete_enrol" value="Delete" />
						<input type="submit" value="Withdraw" name="submit_withdraw_enrol" />
					</form>
				<?php
				}
				?>

				<?php //process saving of the stud_enrol...
				if (isset($_POST['submit_enrol'])) {
					if (mysqli_num_rows($en) == 1) {
						mysqli_query($db, "UPDATE stud_enrol SET course={$_REQUEST['course']}, year='{$_REQUEST['year']}', en_status='{$_REQUEST['en_status']}'
				    WHERE idnum={$_REQUEST['idnum']} AND sem_code={$_SESSION['sem_code']}");
					} else {
						$rate = getRate2($_REQUEST['idnum']);
						mysqli_query($db, "INSERT INTO stud_enrol (idnum, sem_code, course, year, en_status, rate)
					VALUES ({$_REQUEST['idnum']},{$_SESSION['sem_code']},{$_REQUEST['course']},'{$_REQUEST['year']}','{$_REQUEST['en_status']}', $rate)");
					}
					if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
					else echo "<div class='error'>Records updated.</div>";
				}
				?>

			</div>
		</div>
	<?php } ?>

	<?php

	if (isset($_POST['submit_add_student_subject'])) {

		$NewClassCode = $_REQUEST['new_class_code'];
		$NewSubCode = $_REQUEST['new_sub_code'];
		$idnum = $_REQUEST['idnum'];
		$ClassDetails = mysqli_query($db, "SELECT * FROM class_time WHERE class_code=$NewClassCode");
		$cdRow = mysqli_fetch_assoc($ClassDetails);
		$population = getClassPopulation($NewClassCode);
		$limit = getClassLimit($NewClassCode);
		if ($population >= $limit) {
			echo "<div class='error'>The subject is already closed ($population of $limit).</div>";
		} else {



			$start_time = $cdRow['t_start'];
			$end_time = $cdRow['t_end'];
			$day = $cdRow['day'];

			$checkConflict = checkStudentSubjectTimeConflict($start_time, $end_time, $idnum, $day);

			if (mysqli_error($db)) echo mysqli_error($db);
			if ($checkConflict) {
				$conflict = mysqli_fetch_assoc($checkConflict);
				echo "<div class='error'>The class you are about to add is in conflict with
				{$conflict['name']} - {$conflict['descript']}</div>";
			} else {
				mysqli_query($db, "INSERT INTO sub_enrol (class_code, sub_code, idnum,sem_code)
				VALUES ({$_REQUEST['new_class_code']}, {$_REQUEST['new_sub_code']}, 
				    {$_REQUEST['idnum']},{$_SESSION['sem_code']})");

				if (mysqli_error($db)) {
					echo "<div class='error'>Unable to add. Technical Description: <br/>" . mysqli_error($db) . "</div>";
				} else {
					echo "<div class='error'>Subject added. " . ($population + 1) . " of $limit</div>";
				}
			}
		}
	}

	if (isset($_POST['submit_delete_student_class'])) {
		mysqli_query($db, "DELETE FROM sub_enrol WHERE class_code={$_POST['class_code']}
			AND idnum={$_POST['idnum']}");
		if (mysqli_error($db)) echo "<div class='error'>Unable to delete! Technical description:<br>" . mysqli_error($db) . "</div>";
		else echo "<div class='error'>Subject Class deleted.</div>";
	}

	if (isset($_POST['submit_add_new_class'])) {
		$class_codes = explode(" ", $_POST['class_code']);
		foreach ($class_codes as $code) {
			$class = mysqli_query($db, "SELECT class_code, sub_code FROM class WHERE class_code=$code");
			if (mysqli_num_rows($class) > 0) {
				$classr = mysqli_fetch_assoc($class);
				$limit = getClassLimit($code);
				$population = getClassPopulation($code);

				if ($population >= $limit) {
					echo "<div class='error'>The class " . getClassNameAndTime($code, false) .
						" is already closed with $population students.</div>";
				} else {
					$time = mysqli_query($db, "SELECT * FROM class_time WHERE class_code=$code");
					$timer = mysqli_fetch_assoc($time);

					if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";

					$conflict = checkStudentSubjectTimeConflict($timer['t_start'], $timer['t_end'], $_REQUEST['idnum'], $timer['day']);
					if (!$conflict && mysqli_num_rows($time) > 1)
						$conflict = checkStudentSubjectTimeConflict($timer['t_start'], $timer['t_end'], $_REQUEST['idnum'], $timer['day']);
					if ($conflict) {
						$confr = mysqli_fetch_assoc($conflict);
						echo "<div class='error'>The class " . getClassNameAndTime($code, false) . " is in conflict with 
						{$confr['name']}-{$confr['descript']}.</div>";
					} else {
						mysqli_query($db, "INSERT INTO sub_enrol (class_code, sub_code, idnum,sem_code) 
									VALUES ($code, {$classr['sub_code']},{$_REQUEST['idnum']}, {$_SESSION['sem_code']})");
						if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
						else {
							echo "<div class='error'>Class Added: $code (" . ($population + 1) . " of $limit).</div>";
						}
					}
				}
			} else {
				echo "<div class='error'>Invalid Code: $code Class Not Found!</div>";
			}
		}
	}
	?>

	<?php if (isset($_REQUEST['idnum'], $_REQUEST['course'], $_REQUEST['year'])) { ?>

		<div style="border: 1px solid #444; background: #fff; padding: 10px; font-size: 8pt;">
			<div class="div_title">Subjects Enrolled:</div>

			<?php
			//Check if this enrolment record in withdrawn..
			$enstatus = mysqli_query($db, "SELECT en_status FROM stud_enrol WHERE idnum={$_REQUEST['idnum']} AND sem_code={$_SESSION['sem_code']}");
			if (mysqli_num_rows($enstatus) == 1) {
				$en = mysqli_fetch_row($enstatus);
				if ($en[0] == "withdrawn") {
					echo "<div style='background:#ff3;color:#f33;float:right;clear:both;'>This enrolment record has been withdrawn.</div>";
				} else { ?>

					<div style="border: 0px;" id="study_load">
						<input type="button" value="Search &amp; Add" style="float: right; margin-top: -8px; margin-right: -8px;" onclick="document.getElementById('add_stud_class').style.display='block'" class="noprint">
						<input type="image" src="images/printButton.png" class="noprint" onclick="printThisDiv('printStudyLoadFrame','study_load');" style="float: right; margin-top: -10px; margin-right: 30px;" />
						<iframe id="printStudyLoadFrame" style="width:0px; height: 0px; float: left; margin-left: -9999px;"></iframe>

				<?php
				}
			}
				?>
				<div style="margin-top: 20px; padding-top: 10px; background: #ccc;" id="study_load">
					<div class="not_displayed">
						<p align="center" style="margin: 3px;">Mater Dei College<br />Tubigon, Bohol</p>
						<p align="center" style="margin: 3px;"><strong>Study Load</strong></p>
						<p style="text-align: center; margin:3px;">
							<strong><u><?php getSemester($_SESSION['sem_code']); ?></u></strong>
						</p>
						<p style="text-align: left; margin-top:5px;">
							Name: <u><?php echo getFullName($_REQUEST['idnum']); ?> - [<?php echo $_REQUEST['idnum'] ?>]</u><br />
							Course &amp; Year: <u><?php echo getCourseAndYear($_REQUEST['idnum'], $_SESSION['sem_code']); ?></u>
						</p>
					</div>
					<?php include("library/stud_subjects.php");
					ShowStudentSubjects($_REQUEST['idnum'], $_SESSION['sem_code']); ?>
					<div style="border:0px;font-size:9pt;clear:both">Date Processed: <?php echo date('D - F d, Y'); ?></div>
					<div style="border: 5px; display: inline-block; margin-top: 50px;">
						<span style="text-transform: uppercase"><?php echo $_SESSION['fullname'] ?></span><br />
						Registrar Staff
					</div>
					<div style="border: 0px; float: right; margin-right: 50px;margin-top:50px;">
						JOSE RUEL B. ALAMPAYAN<br />
						Registrar
					</div>
					<div style="border: 0px;font-size: 7pt;margin-top:10px;">
						Please report any discrepancies immediately.<br />
						Note: Do not lose this schedule. In case of loss you will submit a NOTARIZED AFFIDAVIT OF LOSS and
						PAY A SERVICE CHARGE to get another copy.
					</div>
				</div>
					</div>
		</div>
	<?php } ?>
</div>


<div id="add_stud_class" style="background: url(images/black_transparent.png); display:none;
		width: 100%; height: 100%; position: fixed; top:0px; left: 0px;">
	<input type="image" src="images/close_button_big.png" style="float: right; clear: both; margin-right: 20px;margin-top:10px;" onclick="document.getElementById('add_stud_class').style.display='none'">
	<span style="color: #fff; font-size: 20pt; width: 800px; 
		margin-left:auto;margin-right:auto;display:block;margin-top:20px;">
		Add a Class
	</span>

	<div style="height: 100px;width:800px;margin-left:auto;margin-right:auto;margin-top: 10px;background: #ffc;">
		<form method="post" action="" style="width: 500px; margin-left:auto; margin-right:auto">
			Enter Class Codes separated by space: <br />
			<textarea name="class_code" style="width:500px; height: 60px"></textarea><br />
			<input type="hidden" name="idnum" value="<?php echo $_REQUEST['idnum']; ?>" />
			<input type="submit" name="submit_add_new_class" value="Add" style="float:right; width: 150px; font-size: 12pt;" />
		</form>
	</div>

	<div style="height: 50%;width:800px;margin-left:auto;margin-right:auto; 
		margin-top: 10px;background: #ffc;overflow:scroll">
		<table border=1 width="760" cellpadding=3>
			<tr style="background: #fff; border-bottom: 3px solid black;" height=30>
				<td width="50%">Description:</td>
				<td width="30%">Time/Room:</td>
				<td width="15%">Credit Units:</td>
				<td width="5%">*</td>
			</tr>
			<?php
			$clslst = mysqli_query($db, "SELECT class.class_code, class.sub_code, descript, 
			cunits FROM class, subjects 
			WHERE class.sub_code=subjects.sub_code 
			AND sem_code={$_SESSION['sem_code']} 
			ORDER BY descript");
			echo mysqli_error($db);
			while ($clsrow = mysqli_fetch_assoc($clslst)) {
				$time = getClassTimeRoom($clsrow['class_code'], true);
				echo "<tr onmouseover=\"this.style.backgroundColor='#fff';\" 
					onmouseout=\"this.style.backgroundColor=''\" height=30>
			<td>{$clsrow['descript']}</td>
			<td align=center>$time</td>
			<td align=center>{$clsrow['cunits']}</td>
			<td align=center>
				<form method='post'>
				<input type='hidden' name='new_class_code' value='{$clsrow['class_code']}'>
				<input type='hidden' name='new_sub_code' value='{$clsrow['sub_code']}'>
				<input type='hidden' name='idnum' value='{$_REQUEST['idnum']}'>
				<input type='hidden' name='course' value='{$_REQUEST['course']}'>
				<input type='hidden' name='year' value='{$_REQUEST['year']}'>
				<input type='submit' name='submit_add_student_subject' value='+'
					style=\"height:25px;display:inline-block;margin-bottom:-5px;float:right;margin-top:-5px;\"/>
				</form>
			</td></tr>";
			}
			?>
		</table>
	</div>
</div>

<div id="change_stud_class" style="background: url(images/black_transparent.png); display:none;
		width: 100%; height: 100%; position: fixed; top:0px; left: 0px;">
	<input type="image" src="images/close_button_big.png" style="float: right; margin-top: 20px; margin-right: 20px;" onclick="document.getElementById('change_stud_class').style.display='none'">
	<div style="width: 600px; background: #ffc; padding: 20px;margin-left: auto; margin-right:auto; margin-top: 200px;">
		<form method="post" action="" name="delete_class_form">
			<input type="hidden" value="" name="class_code">
			<input type="hidden" value="" name="idnum">
			<input type="hidden" value="<?php echo $_REQUEST['course']; ?>" name="course">
			<input type="hidden" value="<?php echo $_REQUEST['year']; ?>" name="year">
			<div id="class_detail_for_delete"></div>
			Delete this class? <input type="submit" name="submit_delete_student_class" value="Delete">
		</form>
	</div>
</div>