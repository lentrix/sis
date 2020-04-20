<h1>Laboratory Fees</h1>
<table>
	<tr style="background: #09F">
    	<td style="width: 120px; border: 1px solid #333; padding: 3px;">Subject:</td>
        <td style="width: 330px; border: 1px solid #333; padding: 3px;">Description:</td>
        <td style="width: 200px; border: 1px solid #333; padding: 3px;">Time/Room:</td>
        <td style="width: 50px; border: 1px solid #333; padding: 3px;">P.Units:</td>
        <td style="width: 50px; border: 1px solid #333; padding: 3px;">C.Units:</td>
        <td style="width: 50px; border: 1px solid #333; padding: 3px;">Lab Fee:</td>
    </tr>

<?php
$cls = mysql_query("SELECT c.class_code, name, descript, punits, 
        cunits, c.lab_fee, CONCAT(t_start,'-',t_end,' ',day,' ',room) AS 'time_room'  
        FROM class c, subjects s, class_time ct, rooms r 
        WHERE s.sub_code=c.sub_code AND c.sem_code={$_SESSION['sem_code']}
        AND ct.class_code=c.class_code AND ct.rm_no=r.rm_no
        ORDER BY name");
echo mysql_error();
while($clsr = mysql_fetch_assoc($cls)) { ?>
	<tr style="background: #fff;">
    	<td style="border: 1px solid #333; color: #333; font-size: 11px;"><?php echo $clsr['name'];?></td>
        <td style="border: 1px solid #333; color: #333; font-size: 11px;"><?php echo $clsr['descript'];?></td>
        <td style="border: 1px solid #333; color: #333; font-size: 11px;"><?php echo $clsr['time_room'];?></td>
        <td style="border: 1px solid #333; color: #333; font-size: 11px;"><?php echo $clsr['punits']; ?></td>
        <td style="border: 1px solid #333; color: #333; font-size: 11px; text-align: center"><?php echo $clsr['cunits']; ?></td>
        <td style="border: 1px solid #333; color: #333">
			<input type="text" id="<?php echo $clsr['class_code'];?>"
            	value="<?php echo $clsr['lab_fee'];?>"
                onChange="
                	target = 'library/update_lab_fee.php?class_code=<?php echo $clsr['class_code'];?>&value=' + this.value;
                    recipient = 'out_<?php echo $clsr['class_code']*1;?>';
                    request(target, recipient);
                "
                onFocus="this.select();"
                style="width: 50px; border: 1px solid #777; font-size: 11px; 
                		font-family:'Courier New', Courier, monospace; text-align: right;" />
            <span id="out_<?php echo $clsr['class_code']*1;?>">
            </span>
        </td>
    </tr>
<?php
}	
?>
</table>
