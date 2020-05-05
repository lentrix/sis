<?php 
	session_start();
	//error_reporting(0);
	if(isset($_POST['submit_set_semester'])){
		$_SESSION['sem_code'] =  $_REQUEST['sem_code'];
	}
	include("config/dbc.php");
	function getSemester($sem_code){
		global $db;
		$sem=mysqli_query($db, "SELECT sem FROM sems WHERE sem_code=$sem_code");
		$semr=mysqli_fetch_row($sem);
		echo $semr[0];
	}
?>
<html>
<head><title>Student Information System</title>
<meta charset="utf-8">
<link href="css/main.css" rel="stylesheet" type="text/css">
<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<script src="library/jquery.js"></script>
<script type="text/javascript" src="library/async.js"></script>
<script type="text/javascript" src="library/compute_grade.js"></script>
<script>
function printThisDiv(xframe, xdiv){
	try{
		var oIframe = document.getElementById(xframe);
		var oContent = document.getElementById(xdiv).innerHTML;
		var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
		if (oDoc.document) oDoc = oDoc.document;
		oDoc.write("<html><head><link rel='stylesheet' href='css/print.css' type='text/css'><title>title</title>");
		oDoc.write("</head><body onload='this.focus(); this.print();'>");
		oDoc.write(oContent + "</body></html>");
		oDoc.close();
	}catch(e){
		alert(e);
	}
}

function checkValue(total, score){
    digit = /^\d/;
    if(score.value.search(digit)==-1){
        alert("Please enter a valid number.");
        score.style.backgroundColor="#f44";
	}else if((total.value*1) >= (score.value*1)) {
		score.style.backgroundColor='#9fc';
	}else {
		alert("The score you entered " + score.value + " is greater than the total score of " + total.value + ", or is an invalid character.");
		score.style.backgroundColor='#f44';
		score.focus();
	}
}

</script>
</head>
<body>
<div id="heading">
	<div id="logo"></div>
	
    <div id="title">
    	<h2>Mater Dei College <br>Student Information System</h2>
    </div>
	<div id="sem">
		<?php if(isset($_SESSION['sem_code'])) getSemester($_SESSION['sem_code']);?>
	</div>
</div>
<div id="strip">
    <div id="college">
	<?php 
	if(isset($_SESSION['clg_no'])){
		$cg = mysqli_query($db, "SELECT college, acrnm FROM colleges WHERE clg_no={$_SESSION['clg_no']}");
		$cgr=mysqli_fetch_assoc($cg);
		echo $cgr['college'] . "(" . $cgr['acrnm'] . ")";
	}
	?>
    </div>
	<div id="user">
	<?php
	if(isset($_SESSION['fullname'])){
		echo "Welcome! " . $_SESSION['fullname'];
	}
	?>
	</div>
</div>

<div id="wrapper">
   	<?php 
		if(isset($_GET['page'])){
			echo "<div id='widget_area'>";
			include("widgets/user_widget.php");
			include("config/module_assignments.php");
			$md = mysqli_query($db, "SELECT * FROM modules NATURAL JOIN modassgn WHERE user='{$_SESSION['user']}'");
			while($mdr=mysqli_fetch_assoc($md)){
				if(file_exists("widgets/" . $mdr['module'] . "_widget.php"))
					include("widgets/" . $mdr['module'] . "_widget.php");
				foreach($modules[$mdr['module']] as $pages_assigned)	{
					$_SESSION[$pages_assigned] = TRUE;
				}
			}
			include("widgets/semesters_widget.php");
			echo "</div>";

			echo "<div id='container'>";
			if(!isset($_SESSION['user'])) header("location:index.php");
			if(isset($_SESSION[$_GET['page']])){
				if(file_exists("pages/" . $_GET['page'] . ".php"))
				include("pages/" . $_GET['page'] . ".php");
				echo "</div>";
			}else {
				echo "<h1>The page <span style=\"text-transform: uppercase;\">{$_GET['page']}</span> is not assigned to you.</h1>";
				echo "<ul type='disc'>";
				foreach($_SESSION as $nn=>$sess){
					echo "<li>" . $nn . "=" . $sess . "</li>";
				}
				echo "</ul>";
			}
		}else{
			include("pages/login.php");
		}
	?>
</div>
<div id="footer1">
	Copyright &copy; 2011. Mater Dei College<br>
    Tubigon, Bohol, Philippines.<br/>
    <span style="float: right; color: #333; font-size: 9pt; margin-right: 10px;font-style: oblique">Developed by: Benjie B. Lenteria</span>
</div>
</body>
</html>
