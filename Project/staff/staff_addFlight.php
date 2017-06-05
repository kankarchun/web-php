<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
     <link rel="stylesheet" href="css/staff_booking.css" />
	<title>Online Travel Information System - Add Flight Booking</title>
    <script>
		function back(){
			location = "staff_order.php";
		}
		
	</script>
</head>

<body>
<?php
	session_start();
	require_once('../Connections/conn.php');
	if(isset($_POST['custID'])){
		$checkCust = false;
		$sql = "SELECT CustID FROM Customer";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		while($rc = mysqli_fetch_assoc($rs))
			{if($rc['CustID'] == $_POST['custID']){
				$checkCust = true;
				break;}
			}
			
		$sql_id = "SELECT BookingID FROM FlightBooking";
		$rs_id = mysqli_query($conn, $sql_id) or die(mysqli_error($conn));
		$bookingID = 0;
		$date = date("Y-m-d");
		while($rc_id = mysqli_fetch_assoc($rs_id)){	
			if($rc_id['BookingID'] > $bookingID){
				$bookingID = $rc_id['BookingID'];
			}
		}
		$bookingID++;

		if($checkCust)
			{
			if($_POST['AdultNum']+$_POST['ChildNum']+$_POST['InfantNum'] == 0){
					echo '<script>alert("Total ticket cannot be 0!")</script>';
				}else{
				$total = ($_POST['AdultPrice'] * $_POST['AdultNum']) + ($_POST['ChildPrice'] * $_POST['ChildNum']) + ($_POST['InfantPrice'] * $_POST['InfantNum']);
				$sql = "INSERT INTO FlightBooking(BookingID,OrderDate,StaffID,CustID,AdultNum,ChildNum,InfantNum,AdultPrice,ChildPrice,InfantPrice,TotalAmt,FlightNo,Class,DepDateTime) VALUES ('"
				.$bookingID."','"
				.$date."','"
				.$_POST['staffID']."','"
				.$_POST['custID']."','"
				.$_POST['AdultNum']."','"
				.$_POST['ChildNum']."','"
				.$_POST['InfantNum']."','"
				.$_POST['AdultPrice']."','"
				.$_POST['ChildPrice']."','"
				.$_POST['InfantPrice']."','"
				.$total."','"
				.$_POST['fID']."','"
				.$_POST['Class']."','"
				.$_POST['depDateTime']."')";
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
				$_SESSION['confirm_total_flight'] = $total;
				$_SESSION['order_cust'] = $_POST['custID'];
				echo '<script language="javascript">';
				echo 'alert("Booking Successfully!\n'.$_POST['custID'].' Total Booking Amount of AirTicket is \\$'.$total.'");
				back();';
				echo '</script>';}
			}else{
				echo '<script language="javascript">';
				echo 'alert("CustID not found!")';
				echo '</script>';}
		
		}
		if(isset($_GET['flightNo'])){
		$sql = "SELECT a.depDateTime, a.arrDateTime,  a.depAirport, a.arrAirport , a.flightNo ,b.class, b.price_Adult, b.price_Children, b.price_Infant, b.tax
, c.airlineName, c.icon FROM flightSchedule a, flightClass b,airline c where a.flightNo = b.flightNo and b.airlineCode = c.airlineCode and a.flightNo = '" . $_GET['flightNo'] . "'";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$rc = mysqli_fetch_assoc($rs);
		$form = <<<EOD
		<div id="container"><h2 align="center"><u>Flight Booking Information</u></h2>
		<form method="POST"><table align="center">
			<img src='../airline/img/AirlineCarrier/$rc[icon]' style='float:right;width:60px;height:auto;'>
			<tr><td>Flight No.</td><td> %s </td></tr>
			<tr><td>Departure:</td><td><i>%s (%s)</i></td></tr>
			<tr><td>Arrival:</td><td><i>%s (%s)</i></td></tr>
			<tr><td>Class:</td><td> %s </td></tr>
			<tr><td>Tax:</td><td> %s </td></tr>
			<tr><td>Adult Price:</td><td> %s </td></tr>
			<tr><td>Child Price:</td><td> %s </td></tr>
			<tr><td>Infant Price:</td><td> %s </td></tr>
			<tr><td>Cust ID:</td><td><input type="text" name="custID" value="" required></td></tr>
			<tr><td>Adult Number:</td><td><input type="number" name="AdultNum" min="0" value="0"></td></tr>
			<tr><td>Child Number:</td><td><input type="number" name="ChildNum" min="0" value="0"></td></tr>
			<tr><td>Infant Number:</td><td><input type="number" name="InfantNum" min="0" value="0"></td></tr>
			<tr><td><input type="submit" id="confirm" value="Confirm" ></td>
			<td><input type="button" onclick="back()" value="Cancel"></td></tr>
			<input type="hidden" name="depDateTime" value="%s"> 
			<input type="hidden" name="staffID" value="%s"> 
			<input type="hidden" name="fID" value="%s"> 
			<input type="hidden" name="Class" value="%s"> 
			<input type="hidden" name="Tax" value="%s"> 
			<input type="hidden" name="AdultPrice" value="%s"> 
			<input type="hidden" name="ChildPrice" value="%s"> 
			<input type="hidden" name="InfantPrice" value="%s">
			</table></form></div>
EOD;
		printf($form,$rc['flightNo'],$rc['depAirport'],$rc['depDateTime'],$rc['arrAirport'],$rc['arrDateTime'],
			$rc['class'],$rc['tax'],$rc['price_Adult'],$rc['price_Children'],$rc['price_Infant'],$rc['depDateTime'],$_SESSION['uid'],$rc['flightNo'],$rc['class'],$rc['tax'],$rc['price_Adult'],$rc['price_Children'],$rc['price_Infant']);
		mysqli_free_result($rs);
		
		
	}
?>
</body>
</html>