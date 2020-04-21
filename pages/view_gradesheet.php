<?php
include("library/singles.php");

if (isset($_GET['class_code'])) {
  $class_code = $_GET['class_code'];
  $cls = mysqli_query($db, "SELECT class_code, descript, cunits, CONCAT(fname,' ',mi,' ',lname) as 'name', 
					 sem_code, teacher.tch_num, class.clg_no, sub_dt 
					 FROM class, subjects, teacher 
					 WHERE class.sub_code=subjects.sub_code
					 AND class.tch_num=teacher.tch_num
					 AND class_code=$class_code");
  $clrow = mysqli_fetch_assoc($cls);
?>
  <h1>View GradeSheet</h1>

  <div id="grade_sheet" style="background:#fff;">
    <input type="button" style="width:30px;height:30px;background:url(images/printButton.png);float:right;border:0px;" class="noprint" onclick="printThisDiv('printFrame','grade_sheet');" />
    <iframe id="printFrame" style="width:0px; height: 0px;float:left;margin-left:-9999"></iframe>
    <table width="700" border="1" cellspacing="0" cellpadding="2" style="border-collapse: collapse;" align="center">
      <tr>
        <td colspan="10" align="center">
          <p>Mater Dei College<br>
            Tubigon, Bohol<br>
            <br>
            <strong>GRADE SHEET</strong></p><br />
        </td>
      </tr>
      <tr>
        <td width="32">&nbsp;</td>
        <td colspan="3">Subject: <?php echo $clrow['descript']; ?></td>
        <td colspan="6" align="right"><?php echo getCollegeName($clrow['clg_no']); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3">Units: <?php echo $clrow['cunits']; ?> &nbsp;&nbsp;
          Time: <?php echo getClassTime($clrow['class_code'], false); ?></td>
        <td width="117" align="right">Semester:</td>
        <td colspan="5"><?php getSemester($clrow['sem_code']); ?></td>
      </tr>
      <tr align="center" style="font-weight:bold">
        <td>#</td>
        <td width="125">Last Name:</td>
        <td width="125">First Name:</td>
        <td width="40">MI:</td>
        <td>Course &amp; Year:</td>
        <td width="37">Mid:</td>
        <td width="37">Fin:</td>
        <td width="38">Rt:</td>
        <td width="39">Units:</td>
        <td width="110">Remarks:</td>
      </tr>
      <?php
      $stds = mysqli_query($db, "SELECT lname, fname, mi, CONCAT(cr_acrnm,'-',year) AS 'cy', mgrade, fgrade, rating
			FROM stud_info, stud_enrol, sub_enrol, courses
			WHERE stud_info.idnum=stud_enrol.idnum
			AND stud_enrol.idnum=sub_enrol.idnum
			AND sub_enrol.rating<>'W'
			AND stud_enrol.course = courses.cr_num
			AND stud_enrol.sem_code = sub_enrol.sem_code
			AND stud_enrol.sem_code={$clrow['sem_code']}
			AND sub_enrol.class_code=$class_code ORDER BY lname, fname");
      echo mysqli_error($db);
      $n = 1;
      while ($srow = mysqli_fetch_assoc($stds)) {
        if ($srow['rating'] > 3) {
          $rt = 5.0;
          $units = 0;
          $rem = "Failed";
        } else if ($srow['rating'] == null || $srow['rating'] == 0) {
          $rt = "-";
          $units = "-";
          $rem = "-";
        } else {
          $rt = $srow['rating'];
          $units = $clrow['cunits'];
          $rem = "Passed";
        }
      ?>
        <tr>
          <td><?php echo $n++; ?></td>
          <td><?php echo $srow['lname']; ?></td>
          <td><?php echo $srow['fname']; ?></td>
          <td><?php echo $srow['mi']; ?></td>
          <td><?php echo $srow['cy']; ?></td>
          <td align="center"><?php echo $srow['mgrade']; ?></td>
          <td align="center"><?php echo $srow['fgrade']; ?></td>
          <?php
          if (empty($srow['mgrade']) || empty($srow['fgrade'])) {
            $rt = '';
            $units = '';
            $rem = '';
          }
          ?>
          <td align="center"><?php if (!empty($rt)) printf("%.1f", $rt); ?></td>
          <td align="center"><?php echo $units; ?></td>
          <td><?php echo $rem; ?></td>
        </tr>
      <?php
      }
      ?>
    </table>
    <span style="text-align:center;display:inline-block;margin-top: 50px; margin-left: 72px;">
      <u><span style="text-transform:uppercase;font-weight:bold"><?php echo getTeacherFullName($clrow['tch_num']); ?></span></u><br />
      Instructor
    </span>

    <span style="text-align:center;display:inline-block;margin-top: 50px; margin-left: 40px;">
      <u><span style="text-transform:uppercase;font-weight:bold"><?php echo getDean($clrow['clg_no']); ?></span></u><br />
      Dean/Chair
    </span>
    <span style="text-align:right;display:block;margin-right: 40px;clear:both;color:#777;font-size:8pt;">
      Date Submitted: <?php echo $clrow['sub_dt']; ?></span>
    </span>
  </div>

  <div style="background:#fff;">
    <h3>Submission History</h3>
    <table style="border-collapse: collapse; width: 100%">
      <tr>
        <th class="thead">Date</th>
        <th class="thead">Remarks</th>
      </tr>
      <?php $sh = mysqli_query($db, "SELECT date, remarks FROM subm_history WHERE class_code=$class_code") ?>
      <?php while ($shr = mysqli_fetch_assoc($sh)) : ?>
        <tr>
          <td class="tcel"><?= date('M-d-Y', strtotime($shr['date'])) ?></td>
          <td class="tcel"><?= $shr['remarks'] ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>

<?php
}
?>