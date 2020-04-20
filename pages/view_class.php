<?php include("library/singles.php"); ?>
<h1>View Class</h1>

<div id="class_list">
<center>
<input type="image" src="images/printButton.png" class="noprint"
	onClick="printThisDiv('printFrame','class_list');"
	style="float: right; margin-top: 5px; margin-right: 5px;display:screen;">
<iframe name="printFrame" id="printFrame" style="width:0px;height:0px;float:left; margin-left: -9999px"></iframe>

<h2 style="width: 500px;">Class List</h2>
<span style="width: 500px;display:block;">
<?php 
	echo getClassNameAndTime($_GET['class_code'],true);
?>
</span>
<?php include("library/classlist.php"); showClassList($_REQUEST['class_code']); ?>
</center>
</div>