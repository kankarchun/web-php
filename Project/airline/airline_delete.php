<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline.css" />
<script type="text/javascript">
	function cancel(){
		parent.location.href = "airline_home.php?page=flightSchedule";
	}
</script>
</head>

<body>
<h3 align="center"><u>Air Flight delete</u></h3>
<?php
	session_start();
	require_once("../Connections/conn.php");
	$sql = "select * from flightschedule where FlightNo='$_GET[flightno]' and DepDateTime='$_GET[depDate]'";
	$rs = mysqli_query($conn, $sql);
	echo "<table border='1' align='center'>";
	echo "<tr><th>FlightNo</th>
		<th>Deperture Airport</th>
		<th>Arrival Airport</th>
		<th>Deperture Date&Time</th>
		<th>Arrival Date&Time</th>
		<th>Fly Minute</th>
		<th>Aircraft</th></tr>";
	$rc=mysqli_fetch_assoc($rs);
	echo '<tr><td>'.$rc["flightNo"].'</td>
		<td>'.$rc["depAirport"].'</td>
		<td>'.$rc["arrAirport"].'</td>
		<td>'.$rc["depDateTime"].'</td>
		<td>'.$rc["arrDateTime"].'</td>
		<td>'.$rc["flyMinute"].'</td>
		<td>'.$rc["airCraft"].'</td>';
	echo "</table>";

	$depDate = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode(@$_GET['depDate'])); 
	$depDate = html_entity_decode(@$_GET['depDate'],null,'UTF-8');
	
	$sql_cust="select * from flightbooking,customer where FlightNo='".@$_GET['flightno']."' and DepDateTime='". @$_GET['depDate']."' and flightbooking.CustID=customer.CustID";
	$rs_cust = mysqli_query($conn, $sql_cust);
	echo "<h3 align='center'>Affected Passengers</h3>
		<table border='1' align='center'>
		<tr><th>Passenger Name</th>
		<th>Mobile No.</th>
		<th>Nationality</th>
		<th>Class</th>
		<th>Adult No.</th>
		<th>Children No.</th>
		<th>Infant No.</th>
		<th>Order Date</th>
		<th>Total Amount</th></tr>";
	while($rc_cust=mysqli_fetch_assoc($rs_cust)){
		echo "<tr><td>$rc_cust[Surname] $rc_cust[GivenName]</td>
			<td>$rc_cust[MobileNo]</td>
			<td>$rc_cust[Nationality]</td>
			<td>$rc_cust[Class]</td>
			<td>$rc_cust[AdultNum]</td>
			<td>$rc_cust[ChildNum]</td>
			<td>$rc_cust[InfantNum]</td>
			<td>$rc_cust[OrderDate]</td>
			<td>$$rc_cust[TotalAmt]</td></tr>";
	}
	echo "</table><br/>";
	
	echo '<form name="frmDelete" method="get" action="airline_delete_sql.php">
		<input type="hidden" name="flightno" value="'.$_GET["flightno"].'">
		<input type="hidden" name="depdate" value="'.$depDate.'">
		<input type="submit" value="Delete">
		<input type="button" value="Cancel" id="btncancel" onclick="cancel()">
	</form>';
	mysqli_free_result($rs_cust);
	mysqli_close($conn);
?>
</body>
</html>