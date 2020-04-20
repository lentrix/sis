
<?php	function ShowStudentSubjects($idnum, $sem_code, $del=true) {  ?>
<table style="border-collapse: collapse" border="1" cellpadding="0" cellspacing="0">
	<tr>
	    <td class="thead" width="80">Name:</td>
		<td class="thead" width="240">Description:</td>
		<td class="thead" width="120">Time/Room:</td>
		<td class="thead" width="30">Cr Unit:</td>
		<td class="thead" width="30">Pay Unit:</td>
		<td class="thead" width="130">Instructor:</td>
	</tr>
<?php
		if(isset($_SESSION['view_edit_class'])) $page = "view_edit_class";
		else $page = "view_class";

		$stsub = mysql_query("SELECT class.class_code, name, descript,  
			cunits, punits, CONCAT(fname,' ',lname) AS 'teacher', class.limit 
			FROM sub_enrol, stud_enrol, class, subjects, teacher 
			WHERE sub_enrol.idnum=stud_enrol.idnum 
			AND sub_enrol.class_code = class.class_code 
			AND subjects.sub_code=class.sub_code 
			AND class.tch_num=teacher.tch_num 
			AND stud_enrol.idnum=$idnum 
			AND stud_enrol.sem_code = sub_enrol.sem_code
			AND stud_enrol.sem_code=$sem_code");
		echo mysql_error();
		if(mysql_num_rows($stsub)){
		    $tcunits = 0;
		    $tpunits = 0;
			while($stsrow=mysql_fetch_assoc($stsub)){
				echo "<tr><td class='tcel'>";
				$time = getClassTimeRoom($stsrow['class_code'],true);
				if($del) {
					echo	"<a style='font-size: 8pt; text-decoration: none;cursor: pointer;font-weight:bold'
						onclick=\"
						document.getElementById('change_stud_class').style.display='block';
						document.delete_class_form.class_code.value='{$stsrow['class_code']}';
						document.delete_class_form.idnum.value='$idnum';
						document.getElementById('class_detail_for_delete').innerHTML=
							'{$stsrow['descript']}-'\" title='{$stsrow['class_code']}'>";
				} else { 
					echo "<a href='index.php?page=$page&class_code={$stsrow['class_code']}' 
						style='font-size: 8pt; text-decoration: none;cursor: pointer;font-weight:bold'
						 title='{$stsrow['class_code']}'>" ;
				}
				echo "{$stsrow['name']}</a></td>";
				echo "<td class='tcel'>{$stsrow['descript']}</td>";
				echo "<td class='tcel' style='font-size:8pt;'>$time</td>";
				echo "<td class='tcel' align='center' style='font-size:8pt;'>{$stsrow['cunits']}</td>";
				echo "<td class='tcel' align='center' style='font-size:8pt;'>{$stsrow['punits']}</td>";
				echo "<td class='tcel' style='font-size:8pt;'>{$stsrow['teacher']}</td></tr>";
				$tcunits+=$stsrow['cunits'];
				$tpunits+=$stsrow['punits'];
			}
			echo "<tr><td colspan='3' class='tcel'><strong>Total Units:</strong></td>
			    <td class='tcel' align='center'>$tcunits</td>
			    <td class='tcel' align='center'>$tpunits</td>
			    <td class='tcel'>&nbsp;</td></tr>";
		}
?>
</table>

<?php 	} ?>
