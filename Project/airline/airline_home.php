<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline.css" />
	<title>Online Travel Information System - Airline Company</title>
	<?php
		session_start();
		if(!(isset($_SESSION['uid']) && !empty($_SESSION['uid']))) {
			header('Location: ../home.php');
		}
	?>
	<script type="text/javascript">
		function logout(){
			location.href = "../logout.php";
		}
		function changeframe(lc) {
			var locat = "airline_"+lc+".php";
			if(document.getElementById("childframe").src = locat)
				return;
			document.getElementById("childframe").src = "../pagechanger.php"
			window.frames["childframe"].changepage(locat);
		}
	</script>
</head>

<body>
	<div id="header">
		<?php
			require_once("../Connections/conn.php");
			$sql = "SELECT * FROM Airline WHERE AirlineCode = '". $_SESSION['uid']."'";
			$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			$rc = mysqli_fetch_assoc($rs);

			echo "<div id='headertitle' onclick='parent.location=\"airline_home.php\"'><b>Welcome, ". $rc['airlineName'] . "</b></div>";
			mysqli_free_result($rs);
			mysqli_close($conn);

		echo '<div id="menu"><form method="get"></form>
			<ul><li><a href="airline_home.php?page=flightSchedule">Flight Schedule</a><div class="und"></div></li></ul>
			<ul><li><a href="airline_home.php?page=flightClass">Flight Class</a><div class="und"></div></li></ul>
			<ul><li><a href="airline_home.php?page=searchPassenger">Passenger</a><div class="und"></div></li></ul>
			<ul><li><a href="airline_home.php?page=genReport">Report</a><div class="und"></div></li></ul>
			<ul><li><a href="javascript:logout()">Logout</a><div class="und"></div></li></ul>	
		</div>
	</div>

	<div id="main">
		<iframe src="../pagechanger.php" name="childframe" id="childframe"></iframe>
	</div>';
	if(isset($_GET['page']))
		echo "<script type='text/javascript'>changeframe('$_GET[page]')</script>";
	?>
</body>
</html>