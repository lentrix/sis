<?php include("library/population.php"); ?>

<h1>Enrolment Report</h1>

<table style="border-collapse:collapse; background:#fff;" border="1">
    <tr>
        <th width="119" rowspan="2">Course:</th>
        <th colspan="3">TOTAL</th>
        <th colspan="2">New</th>
        <th colspan="2">Trans</th>
        <th colspan="2">1st Yr</th>
        <th colspan="2">2nd Yr</th>
        <th colspan="2">3rd Yr</th>
        <th colspan="2">4th Yr</th>
        <th colspan="2">5th Yr</th>
    </tr>
    <tr>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33" style="background: #aac">M/F</th>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33">M</th>
        <th width="33">F</th>
        <th width="33">M</th>
        <th width="33">F</th>
    </tr>

    <?php
    $courses = mysqli_query($db, "SELECT cr_num, cr_acrnm, clg_no FROM courses ORDER BY clg_no, cr_acrnm");
    $totalMale = 0;
    $totalFemale = 0;
    $totalTransfereeMale = 0;
    $totalTransfereeFemale = 0;
    $totalNewMale = 0;
    $totalNewFemale = 0;
    $totalMale1 = 0;
    $totalFemale1 = 0;
    $totalMale2 = 0;
    $totalFemale2 = 0;
    $totalMale3 = 0;
    $totalFemale3 = 0;
    $totalMale4 = 0;
    $totalFemale4 = 0;
    $totalMale5 = 0;
    $totalFemale5 = 0;

    while ($crow = mysqli_fetch_assoc($courses)) {
        $totalMale += $male = countCourseGender($crow['cr_num'], "Male");
        $totalFemale += $female = countCourseGender($crow['cr_num'], "Female");
        $totalNewMale += $newMale = countCourseStatusGender($crow['cr_num'], "New", "Male");
        $totalNewFemale += $newFemale = countCourseStatusGender($crow['cr_num'], "New", "Female");
        $totalTransfereeMale += $transfereeMale = countCourseStatusGender($crow['cr_num'], "Transferee", "Male");
        $totalTransfereeFemale += $transfereeFemale = countCourseStatusGender($crow['cr_num'], "Transferee", "Female");
        $totalMale1 += $male1 = countCourseYearGender($crow['cr_num'], "1", "Male");
        $totalFemale1 += $female1 = countCourseYearGender($crow['cr_num'], "1", "Female");
        $totalMale2 += $male2 = countCourseYearGender($crow['cr_num'], "2", "Male");
        $totalFemale2 += $female2 = countCourseYearGender($crow['cr_num'], "2", "Female");
        $totalMale3 += $male3 = countCourseYearGender($crow['cr_num'], "3", "Male");
        $totalFemale3 += $female3 = countCourseYearGender($crow['cr_num'], "3", "Female");
        $totalMale4 += $male4 = countCourseYearGender($crow['cr_num'], "4", "Male");
        $totalFemale4 += $female4 = countCourseYearGender($crow['cr_num'], "4", "Female");
        $totalMale5 += $male5 = countCourseYearGender($crow['cr_num'], "5", "Male");
        $totalFemale5 += $female5 = countCourseYearGender($crow['cr_num'], "5", "Female");
    ?>
        <tr onmouseover="this.style.backgroundColor='#ddd'" onmouseout="this.style.backgroundColor='#fff'">
            <td><?php echo $crow['cr_acrnm']; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $male ?></td>
            <td style="text-align: center"><?php echo $female; ?></td>
            <td style="background: #aac; text-align:center"><?php echo ($male + $female); ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $newMale; ?></td>
            <td style="text-align: center"><?php echo $newFemale; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $transfereeMale; ?></td>
            <td style="text-align: center"><?php echo $transfereeFemale; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $male1; ?></td>
            <td style="text-align: center"><?php echo $female1; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $male2; ?></td>
            <td style="text-align: center"><?php echo $female2; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $male3; ?></td>
            <td style="text-align: center"><?php echo $female3; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $male4; ?></td>
            <td style="text-align: center"><?php echo $female4; ?></td>

            <td style="background: #ddd; text-align:center"><?php echo $male5; ?></td>
            <td style="text-align: center"><?php echo $female5; ?></td>
        </tr>
    <?php
    }
    ?>
    <tr style="background: #77f; font-weight:bold; text-align:center">
        <td>TOTAL</td>
        <td><?php echo $totalMale; ?></td>
        <td><?php echo $totalFemale; ?></td>
        <td><?php echo ($totalMale + $totalFemale); ?></td>
        <td><?php echo $totalNewMale; ?></td>
        <td><?php echo $totalNewFemale; ?></td>
        <td><?php echo $totalTransfereeMale; ?></td>
        <td><?php echo $totalTransfereeFemale; ?></td>
        <td><?php echo $totalMale1; ?></td>
        <td><?php echo $totalFemale1; ?></td>
        <td><?php echo $totalMale2; ?></td>
        <td><?php echo $totalFemale2; ?></td>
        <td><?php echo $totalMale3; ?></td>
        <td><?php echo $totalFemale3; ?></td>
        <td><?php echo $totalMale4; ?></td>
        <td><?php echo $totalFemale4; ?></td>
        <td><?php echo $totalMale5; ?></td>
        <td><?php echo $totalFemale5; ?></td>
    </tr>
</table>