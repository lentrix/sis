<?php include("library/lister.php"); ?>
<?php include("library/singles.php"); ?>

<?php
function getSubjects($idnum){
   global $db;
   $subjects['names']="";
   $subjects['tunits']=0;
   $sbs = mysqli_query($db, "SELECT s.name, c.cunits FROM subjects s, sub_enrol se, class c
         WHERE s.sub_code = c.sub_code AND se.class_code = c.class_code
         AND se.idnum=$idnum AND se.sem_code={$_SESSION['sem_code']}");
   while($sbr=mysqli_fetch_row($sbs)){
      $subjects['names'] .= $sbr[0] . ", ";
      $subjects['tunits'] += $sbr[1];
   }
   return $subjects;
}
?>

<?php
function getYears($cr_num){
   global $db;
   $yrs = mysqli_query($db, "SELECT DISTINCT year FROM stud_enrol WHERE sem_code={$_SESSION['sem_code']} AND course=$cr_num");
   $years = array();
   while($yr=mysqli_fetch_row($yrs)) {
      $years[] = $yr[0];
   }
   return $years;
}
?>

<?php function createEnrolist($course, $year, $n){ ?>
   <?php global $db; ?>
      <?php
      $stud = mysqli_query($db, "SELECT st.idnum, st.lname, st.fname, st.mi, c.cr_acrnm, se.year, st.gender
            FROM stud_info st, courses c, stud_enrol se
            WHERE st.idnum=se.idnum AND se.course=c.cr_num
            AND se.en_status <> 'withdrawn' 
            AND c.cr_num=$course AND se.year='$year'
            AND se.sem_code={$_SESSION['sem_code']}
            ORDER BY lname, fname ASC");
      if(mysqli_error($db)) echo mysqli_error($db);
      while($stdr=mysqli_fetch_assoc($stud)) {
      ?>
      
      <tr>
         <td><?php echo $n++; ?></td>
         <td><?php echo $stdr['idnum']; ?></td>
         <td><?php echo $stdr['lname']; ?></td>
         <td><?php echo $stdr['fname']; ?></td>
         <td><?php echo $stdr['mi']; ?></td>
         <td><?php echo $stdr['cr_acrnm'] . "-" . $stdr['year']; ?></td>
         <td><?php echo $stdr['gender']; ?></td>
         <td>
            <?php $sbs = getSubjects($stdr['idnum']); ?>
            <?php echo $sbs['names']; ?>
         </td>
         <td>
            <?php echo $sbs['tunits']; ?>
         </td>
      </tr>
      
      <?php
      }
      ?>
<?php return $n; ?>      
<?php } ?>



<h1>Enrolment List</h1>

 <table border="1">
      <tr>
         <th>No.</th>
         <th width="50">ID No.</th>
         <th width="130">Last Name:</th>
         <th width="130">First Name:</th>
         <th width="20">MI:</th>
         <th width="100">Course & Year:</th>
         <th width="30">Gender:</th>
         <th width="350">Subjects:</th>
         <th width="20">Unt</th>
      </tr>
<?php 
//Generate course & year list....................

$courses = array();

$crs_list = mysqli_query($db, "SELECT DISTINCT s.course, cr_acrnm FROM stud_enrol s, courses c
        WHERE s.course=c.cr_num 
        AND sem_code={$_SESSION['sem_code']}
        ORDER BY c.cr_acrnm");
echo mysqli_error($db);
$n=1;
while($cr = mysqli_fetch_row($crs_list)){
   $years = getYears($cr[0]);
   
   foreach($years as $y){
      $n = createEnrolist($cr[0], $y, $n);
   } 
   
}
?>
</table>
