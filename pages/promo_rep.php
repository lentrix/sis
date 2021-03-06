<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>

<?php
// function getSubjects($idnum){
//    $subjects['names']="";
//    $subjects['tunits']=0;
//    $sbs = mysqli_query($db, "SELECT s.name, c.cunits, se.rating FROM subjects s, sub_enrol se, class c
//          WHERE s.sub_code = c.sub_code AND se.class_code = c.class_code
//          AND se.idnum=$idnum AND se.sem_code={$_SESSION['sem_code']}");
//    $subjects['names'] = "<table style='border-collapse: collapsed; width:100%' border='1'>";
//    $subjects['names'] .= "<tr style='font-weight: bold'><td width='50%'>Subject</td><td width='25%'>FG</td><td width='25%'>Units</td></tr>";
//    while($sbr=mysqli_fetch_row($sbs)){
//       $subjects['names'] .= "<tr>" . "<td>" . $sbr[0] . "</td><td>" . (is_numeric($sbr[2]) ? number_format($sbr[2],2) : $sbr[2]) . 
//             "</td><td>" . (is_numeric($sbr[2])?$sbr[1]:"-") . "</td></tr>";
//       $subjects['tunits'] += $sbr[1];
//    }
//    $subjects['names'] .= "</table>";
//    return $subjects;
// }

function getSubjects($idnum)
{
   global $db;
   $sbs = mysqli_query($db, "SELECT s.name, c.cunits, se.mgrade, se.rating
         FROM subjects s, sub_enrol se, class c
         WHERE s.sub_code = c.sub_code AND se.class_code = c.class_code
         AND se.idnum=$idnum AND se.sem_code={$_SESSION['sem_code']}");
   return $sbs;
}
?>

<?php
function getYears($cr_num)
{
   global $db;
   $yrs = mysqli_query($db, "SELECT DISTINCT year FROM stud_enrol WHERE sem_code={$_SESSION['sem_code']} AND course=$cr_num");
   $years = array();
   while ($yr = mysqli_fetch_row($yrs)) {
      $years[] = $yr[0];
   }
   return $years;
}
?>

<?php function createEnrolist($course, $year, $n)
{ ?>

   <?php
   global $db;
   $stud = mysqli_query($db, "SELECT st.idnum, st.lname, st.fname, st.mi, c.cr_acrnm, se.year, st.gender
            FROM stud_info st, courses c, stud_enrol se
            WHERE st.idnum=se.idnum AND se.course=c.cr_num
            AND c.cr_num=$course AND se.year='$year'
            AND se.sem_code={$_SESSION['sem_code']}
            ORDER BY lname, fname ASC");
   if (mysqli_error($db)) echo mysqli_error($db);
   while ($stdr = mysqli_fetch_assoc($stud)) {
      $sbs = getSubjects($stdr['idnum']);
      $rows = mysqli_num_rows($sbs);
   ?>

      <tr style="vertical-align: top">
         <td rowspan="<?= $rows ?>"><?php echo $n++; ?></td>
         <td rowspan="<?= $rows ?>"><?php echo $stdr['idnum']; ?></td>
         <td rowspan="<?= $rows ?>"><?php echo $stdr['lname']; ?></td>
         <td rowspan="<?= $rows ?>"><?php echo $stdr['fname']; ?></td>
         <td rowspan="<?= $rows ?>"><?php echo $stdr['mi']; ?></td>
         <td rowspan="<?= $rows ?>"><?php echo $stdr['cr_acrnm'] . "-" . $stdr['year']; ?></td>
         <td rowspan="<?= $rows ?>"><?php echo substr($stdr['gender'], 0, 1); ?></td>
         <?php while ($row = mysqli_fetch_row($sbs)) : ?>
            <?php
            $units = 0;
            if(is_numeric($row[3])) {
               if($row[3]>3) {
                  $units = 0;
               }else {
                  $units = $row[1];
               }
            }else {
               if(strcasecmp($row[3],'DR')==0 || strcasecmp($row[2],'DR')==0) {
                  $units = "Dr";
               }else {
                  $units = "-";
               }
            }
            ?>
            <td><?= $row[0] ?></td>
            <td><?= (is_numeric($row[2]) ? number_format($row[2], 2) : $row[2]) ?></td>
            <td><?= (is_numeric($row[3]) ? number_format($row[3], 2) : $row[3]) ?></td>
            <td><?= $units ?></td>
      </tr>
   <?php endwhile; ?>

<?php
   }
?>
<?php return $n; ?>
<?php } ?>



<h1>Promotional Report</h1>

<table border="1" width="100%">
   <tr>
      <th>No.</th>
      <th width="50">ID No.</th>
      <th width="130">Last Name:</th>
      <th width="130">First Name:</th>
      <th width="20">MI:</th>
      <th width="100">Course & Year:</th>
      <th width="30">Gdr:</th>
      <th>Subject</th>
      <th>MG</th>
      <th>FG</th>
      <th>Units</th>
   </tr>
   <?php
   //Generate course & year list....................

   $courses = array();

   $crs_list = mysqli_query($db, "SELECT DISTINCT s.course, cr_acrnm FROM stud_enrol s, courses c
        WHERE s.course=c.cr_num 
        AND sem_code={$_SESSION['sem_code']}
        ORDER BY c.cr_acrnm");
   echo mysqli_error($db);
   $n = 1;
   while ($cr = mysqli_fetch_row($crs_list)) {
      $years = getYears($cr[0]);

      foreach ($years as $y) {
         $n = createEnrolist($cr[0], $y, $n);
      }
   }
   ?>
</table>