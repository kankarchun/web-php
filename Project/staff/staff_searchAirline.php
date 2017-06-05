<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
	<title>Online Travel Information System - Hotel search </title>
	<script>
		function setDefault(company){//set default value for input by old input
			document.getElementById('selectair').value = company;
		}
		function submitfrm(){
			document.getElementById('frmAirlineSearch').submit();;
		}
	</script>
</head>

<body>
	<h2 align="center"><u>Airline Company Search</u></h2>
	<?php
	session_start();
	require_once("../Connections/conn.php");
	//add to order
	if(isset($_POST['flightNo']) && isset($_POST['depDateTime']) && isset($_POST['class'])){
		$array = array("flightNo" => $_POST['flightNo'],"depDateTime" => $_POST['depDateTime'],"class" => $_POST['class']);
		if(isset($_SESSION['flightOrder'])){
			$_SESSION['flightOrder'][count($_SESSION['flightOrder'])] = $array;}
		else{
			$_SESSION['flightOrder'][0] = $array;}
		echo "<script>alert('Order added');</script>";
	}
	//show search form
	echo '<form id="frmAirlineSearch" name="frmAirlineSearch" method="Get">';
	$sql = "SELECT airlineName,airlineCode FROM airline";
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    echo '<span id="lbl">Air Company:</span><select name="airlineCode" id="selectair" onchange="submitfrm()">
		<option value="all">--ALL--</option>';
	while($rc = mysqli_fetch_assoc($rs)){
		echo '<option value="'.$rc['airlineCode'].'">'.$rc['airlineName'].'</option>';
	}
	echo '</select><br/></form>';
	
	//show search result
	$sql = "SELECT a.depDateTime, a.arrDateTime,  a.depAirport, a.arrAirport , a.flightNo ,b.class, b.price_Adult, b.price_Children, b.price_Infant, b.tax
, c.airlineName, c.icon FROM flightSchedule a, flightClass b,airline c where a.flightNo = b.flightNo and b.airlineCode = c.airlineCode";
	if(isset($_GET['airlineCode']) && $_GET['airlineCode'] != "all"){
		$sql .= " and b.airlineCode = '$_GET[airlineCode]'";
		echo "<script>setDefault('$_GET[airlineCode]')</script>";
	}
	$sql .= " order by depDateTime desc";
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	echo "<div id='recon'>";
	while($rc = mysqli_fetch_assoc($rs)){
		$rc['depDateTime'] = (new DateTime($rc['depDateTime']))->format('Y-m-d H:i');
		$rc['arrDateTime'] = (new DateTime($rc['arrDateTime']))->format('Y-m-d H:i');
		echo "<div id='result'>
			<img src='../airline/img/AirlineCarrier/$rc[icon]' id='airicon'>
			<table><tr><td>Flight No:</td><td><i>$rc[flightNo] ($rc[airlineName])</i></td></tr>
			<tr><td>Departure:</td><td><i>$rc[depAirport] ($rc[depDateTime])</i></td></tr>
			<tr><td>Arrival:</td><td><i>$rc[arrAirport] ($rc[arrDateTime])</i></td></tr>
			<tr><td>Flight Class:</td><td><i>$rc[class]</i></td></tr>
			<tr><td>Adult Price:</td><td><i>$$rc[price_Adult]</i></td></tr>
			<tr><td>Children Price:</td><td><i>$$rc[price_Children]</i></td></tr>
			<tr><td>Infant Price:</td><td><i>$$rc[price_Infant]</i></td></tr>
			<tr><td>Tax:</td><td><i>$$rc[tax]</i></td></tr></table>";
		if($rc['depDateTime']>date('Y-m-d H:i')){	//show add to order button if not old ticket
		echo "<form method='post'>
			<input type='hidden' name='flightNo' value='$rc[flightNo]'>
			<input type='hidden' name='depDateTime' value='$rc[depDateTime]'>
			<input type='hidden' name='class' value='$rc[class]'>
			<input type='submit' id='addorder' value='' /></form>";
		}
		echo "</div>";
	}
	echo "</div>";
	mysqli_free_result($rs);
	mysqli_close($conn);
	?>
</body>
</html>