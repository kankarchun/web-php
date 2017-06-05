<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
	<link rel="stylesheet" href="css/staff_custDetail.css" />
</head>

<body>
<?php 
if(isset($_GET['custid'])){
	require_once('../Connections/conn.php');
	$sql = "select * from customer where custID = '$_GET[custid]'";
	$rs = mysqli_query($conn, $sql);
	$rc = mysqli_fetch_assoc($rs);
	echo "<div id='custinfo'>
		<span id='edit'><a href='staff_editCust.php?custid=$rc[CustID]'>Edit</a></span>
		<table align='center'><tr><td>Customer ID:</td><td><i>$rc[CustID]</i></td></tr>
			<tr><td>Surname:</td><td><i>$rc[Surname]</i></td></tr>
			<tr><td>Given Name:</td><td><i>$rc[GivenName]</i></td></tr>
			<tr><td>Date Of Birth:</td><td><i>$rc[DateOfBirth]</i></td></tr>
			<tr><td>Gender:</td><td><i>$rc[Gender]</i></td></tr>
			<tr><td>Passport:</td><td><i>$rc[Passport]</i></td></tr>
			<tr><td>Mobile No:</td><td><i>$rc[MobileNo]</i></td></tr>
			<tr><td>Nationality:</td><td><i>$rc[Nationality]</i></td></tr>
		</table></div>";
    $sql = "SELECT * FROM Customer, FlightBooking where Customer.CustID = FlightBooking.CustID and Customer.CustID='$rc[CustID]'";
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	echo "<h3>Flight Booking</h3>";
	if(mysqli_num_rows($rs)>0){
	echo '<table class="rstable" width="100%"><tr>
	    <th>Action</th>
		<th>Flight No.</th>
		<th>Departure DateTime</th>
		<th>Class</th>
		<th>Order Date</th>
		<th>Adult Number</th>
		<th>Child Number</th>
		<th>Infant Number</th>
		<th>Total Amount</th></tr>';
	while($rc = mysqli_fetch_assoc($rs)){
		printf('<tr><td><a href="staff_updateFlightBooking.php?flightbookingid=%s">Update</a> 
			<a href="staff_delete.php?flightbookingid=%s">Delete</a></td>
	        <td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', 
	       $rc['BookingID'],$rc['BookingID'],
		   $rc['FlightNo'],
		   $rc['DepDateTime'],
		   $rc['Class'],
		   $rc['OrderDate'],
		   $rc['AdultNum'],
		   $rc['ChildNum'],
		   $rc['InfantNum'],
		   $rc['TotalAmt']);
	}
	echo "</table>";
	}else{
		echo "No flight booking";
	}
	$sql = "SELECT * FROM Customer, HotelBooking, Hotel where Hotel.HotelID = HotelBooking.HotelID and Customer.CustID = HotelBooking.CustID and Customer.CustID='$_GET[custid]'";
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	echo "<h3>Hotel Booking</h3>";
	if(mysqli_num_rows($rs)>0){
	echo '<table width="100%" class="rstable"><tr>
		<th>Action</th>
	    <th>Hotel Name</th>
		<th>Hotel ID</th>
		<th>Room Type</th>
		<th>Room Number</th>
		<th>Check In</th>
		<th>Check Out</th>
		<th>Total Amount</th>
		<th>Remark</th></tr>';
	while($rc = mysqli_fetch_assoc($rs)){
		printf('<tr><td><a href="staff_updateHotelBooking.php?hotelbookingid=%s">Update</a> 
			<a href="staff_delete.php?hotelbookingid=%s">Delete</a></td>
	        <td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
	       $rc['BookingID'],$rc['BookingID'],
		   $rc['EngName'],$rc['HotelID'],$rc['RoomType'],$rc['RoomNum'],
		   $rc['Checkin'],$rc['Checkout'],$rc['TotalAmt'],$rc['Remark']);
	}
	echo "</table>";
	}else{
		echo "No hotel booking";
	}
}
?>
</body>
</html>