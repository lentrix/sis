<?php

function CreateList($name, $value_field, $display_field, $sql_statement, $style, $style2="", $multiple=0){
	global $db;
	$list=mysqli_query($db, $sql_statement);
	if(mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";

	echo "<select name='$name' id='$name'";
	if($multiple) echo " multiple='multiple'";
	if(isset($_REQUEST[$name]) && empty($_REQUEST[$name])) echo " style=\"border: 1px solid red;";
	else echo "style=\"";
	if($style2) echo $style2 . "\"";
	else echo "\"";
	echo ">\n<option value=0></option>";
	while($list_row = mysqli_fetch_assoc($list)){
		echo "<option value={$list_row[$value_field]} style=\"$style\"";
		if(isset($_REQUEST[$name]) && $_REQUEST[$name]==$list_row[$value_field]) echo " selected";
		echo ">{$list_row[$display_field]}</option>\n";
	}
	echo "</select>\n";
}

function ListClass($name, $value_field, $sql_statement, $style, $style2="", $multiple=0) {
	global $db;
    $cls=mysqli_query($db, $sql_statement);
    if(mysqli_error($db)) echo "<div class='error'>" . mysqli_error($db) . "</div>";

    echo "<select name='$name' id='$name' ";
    if($multiple) echo "multiple='multiple;";
    if(isset($_REQUEST['$name']) && empty($_REQUEST[$name])) echo " style=\"border: 1px solid red;";
    else echo "style=\"";
    if($style2) echo $style2 . "\"";
    else echo "\"";
    echo ">\n<option value=0></option>";
    while($list_row = mysqli_fetch_assoc($cls)) {
        echo "<option value={$list_row[$value_field]} style=\"$style\"";
        if(isset($_REQUEST['name']) && $_REQUEST[$name]==$list_row[$value_field]) echo " selected";
        echo ">{$list_row['name']} (" . getClassTime($list_row['class_code'],false) .")</option>\n";
    }
    echo "</selected>";
}

function showTimeInput($time_name){
	$minutes = array("00","05","10","15","20","25","30","35","40","45","50","55");

	echo "<select name='$time_name'>";
	echo "<option value='NULL'></option>";
	for($h=1; $h<=12; $h++){
		foreach($minutes as $min) {
			echo "<option value=$h:$min";
			if(isset($_REQUEST[$time_name]) && $_REQUEST[$time_name]=="$h:$min") echo " selected";
			echo ">$h:$min</option>\n";
		}
	}
	echo "</select> ";
}
?>
