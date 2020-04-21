<h1>Pay Units Editor</h1>
<?php
$result = mysqli_query($db, "SELECT c.class_code, s.name, s.descript, c.cunits, c.punits
					  FROM class c, subjects s
					  WHERE c.sub_code=s.sub_code AND c.sem_code = {$_SESSION['sem_code']}
					  ORDER BY name");
if (mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";
?>
<div>
        <table border="1" style="border-collapse: collapse;" cellpadding="5">
                <tr bgcolor="#CCFF99" style="height: 30px;">
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Credit Units</th>
                        <th>Pay Units</th>
                </tr>
                <?php $col = "#ddddff"; ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr bgcolor="<?php echo $col; ?>">
                                <td style="font-size: 12px;"><?php echo $row['name']; ?></td>
                                <td style="font-size: 12px;"><?php echo $row['descript']; ?></td>
                                <td style="font-size: 12px; text-align: center"><?php echo $row['cunits']; ?></td>
                                <td>
                                        <span id="pu_<?php echo $row['class_code']; ?>" style="font-size: 10px; color: #0C6"></span>
                                        <input type="text" name="punits" value="<?php echo $row['punits']; ?>" style="font-size: 12px; text-align:center; width: 40px" onmouseup="this.select()" onChange="request('library/change_payunits.php?c=<?php echo $row['class_code']; ?>&v=' + this.value,'pu_<?php echo $row['class_code']; ?>');" />
                                </td>
                        </tr>
                        <?php ($col == "#ddddff") ? $col = "#ffffdd" : $col = "#ddddff"; ?>
                <?php } ?>
        </table>
</div>