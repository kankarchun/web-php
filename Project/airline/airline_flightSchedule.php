<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline.css" />
	<title>Online Travel Information System - Flight</title>

	<script type="text/javascript">
		function add(){
			parent.location.href = "airline_home.php?page=add_schedule";
		}
		function del(flightno,depDateTime){
			location.href = "airline_delete.php?flightno="+flightno+"&depDate="+depDateTime;
		}
	</script>
</head>

<body>
	<?php
		session_start();
		require_once("../Connections/conn.php");

		echo '<div id="holder"><h3 align="center"><u>Flight Schedule</u></h3>';
		echo '<input id="add" name="add" type="submit" value="Add" onClick="add()"></div>';

		$sql = "select flightschedule.flightNo,depDateTime,arrDateTime,depAirport,arrAirport,flyMinute,aircraft from flightschedule,flightclass where flightclass.flightNo = flightschedule.flightNo and flightclass.airlineCode = '$_SESSION[uid]' group by flightNo,depDateTime";
		$rs = mysqli_query($conn, $sql);
		echo "<table border='1' align='center'>";
		echo "<tr><th>FlightNo</th>
			<th>Deperture Airport</th>
			<th>Arrival Airport</th>
			<th>Deperture Date&Time</th>
			<th>Arrival Date&Time</th>
			<th>Fly Minute</th>
			<th>Aircraft</th><th></th></tr>";
		while($rc=mysqli_fetch_assoc($rs)){
			$rc["depDateTime"] = new DateTime($rc["depDateTime"]);
			$rc["depDateTime"] = $rc["depDateTime"]->format('Y-m-d H:i');
			$rc["arrDateTime"] = new DateTime($rc["arrDateTime"]);
			$rc["arrDateTime"] = $rc["arrDateTime"]->format('Y-m-d H:i');
			echo '<tr><td>'.$rc["flightNo"].'</td>
				<td>'.$rc["depAirport"].'</td>
				<td>'.$rc["arrAirport"].'</td>
				<td>'.$rc["depDateTime"].'</td>
				<td>'.$rc["arrDateTime"].'</td>
				<td>'.$rc["flyMinute"].'</td>
				<td>'.$rc["aircraft"].'</td>';
			echo "<td><input id='delete' name='delete' type='button' value='Delete' onClick='del(\"".$rc['flightNo']."\",\"".$rc['depDateTime']."\")'></td></tr>";
		}
		echo "</table>";
		mysqli_free_result($rs);
		mysqli_close($conn);
	?>
</body>
</html>