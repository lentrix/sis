<?php
function year($name){
	$year_now = date('Y') * 1;
	echo "<select name='$name'";
	check($name);
	echo "><option></option>";
	for($y=1920; $y<=$year_now; $y++){
		echo "<option value=$y ";
		if(isset($_REQUEST[$name]) && $_REQUEST[$name]==$y) echo " selected";
		echo ">$y</option>";
	}echo "</select>";
}
function month($name){
	$months = array(1=>"January",2=>"Feburary",3=>"March",4=>"April",5=>"May",6=>"June",7=>"July",8=>"August",
					9=>"September",10=>"October",11=>"November",12=>"December");
	echo "<select name='$name'";
	check($name);
	echo "><option></option>";
	foreach($months as $month_num=>$month_name){
		echo "<option value='$month_num'";
		if(isset($_REQUEST[$name]) && $_REQUEST[$name]==$month_num) echo " selected";
		echo ">$month_name</option>";
	}
	echo "</select>";
}
function day($name){
	echo "<select name='$name'";
	check($name);
	echo "><option></option>";
	for($d=1; $d<=31; $d++){
		echo "<option";
		if(isset($_REQUEST[$name]) && $_REQUEST[$name]==$d) echo " selected";
		echo ">$d</option>";
	}echo "</select>";
}
?>