<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
	<script>
		function back(custID){//back to custDetail page
			location = "staff_custDetail.php?custid="+custID;
		}
		
	</script>
</head>

<body>
<?php 
	require_once('../Connections/conn.php');
	
	if(isset($_POST['fID'])){//update database
		extract($_POST);//calculate total 
		$total = ($AdultPrice * $AdultNum) + ($ChildPrice * $ChildNum) + ($InfantPrice * $InfantNum);
		//update database
		$sql = "UPDATE flightBooking SET Class ='".$Class."'
			, OrderDate ='".$OrderDate."'
			, AdultPrice ='".$AdultPrice."'
			, ChildPrice ='".$ChildPrice."'
			, InfantPrice ='".$InfantPrice."'
			, AdultNum ='".$AdultNum."'
			, ChildNum ='".$ChildNum."'
			, InfantNum ='".$InfantNum."'
			, TotalAmt ='".$total."'
			WHERE BookingID = '".$bookID."';";	
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<script>back('$custID')</script>";		
	}else if(isset($_GET['flightbookingid'])){//show form
		$sql = "SELECT * FROM FlightBooking WHERE BookingID='" . $_GET['flightbookingid'] . "'";//find booking
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$rc = mysqli_fetch_assoc($rs);
		//show form
		$form = <<<EOD
		<div id="container"><h2 align="center"><u>Flight Booking Information</u></h2>
		<form method="POST"><table align="center">
			<tr><td>Flight No.</td><td><input type="text" name="fID" value="%s" readonly></td></tr>
			<tr><td>Departure DateTime:</td><td><input type="text" name="DepDateTime" value="%s" readonly></td></tr>
			<tr><td>Class:</td><td><input type="text" name="Class" value="%s" readonly></td></tr>
			<tr><td>Order Date:</td><td><input type="text" name="OrderDate" value="%s" readonly></td></tr>
			<tr><td>Adult Price:</td><td><input type="text" name="AdultPrice" value="%s" readonly></td></tr>
			<tr><td>Child Price:</td><td><input type="text" name="ChildPrice" value="%s" readonly></td></tr>
			<tr><td>Infant Price:</td><td><input type="text" name="InfantPrice" value="%s" readonly></td></tr>
			<tr><td>Adult Number:</td><td><input type="number" name="AdultNum" value="%s" min="0"></td></tr>
			<tr><td>Child Number:</td><td><input type="number" name="ChildNum" value="%s" min="0"></td></tr>
			<tr><td>Infant Number:</td><td><input type="number" name="InfantNum" value="%s" min="0"></td></tr>
			
			<tr><td><input type="submit" id="ch" value="Update Record" ></td>
			<td><input type="button" onclick="back('%s')" value="Cancel"></td></tr>
			<input type="hidden" value="%s" name="custID" id="custID">
			<input type="hidden" value="%s" name="bookID" id="bookID">
			</table></form></div>
EOD;
		printf($form,$rc['FlightNo'],$rc['DepDateTime'],$rc['Class'],$rc['OrderDate'],$rc['AdultPrice'],
			$rc['ChildPrice'],$rc['InfantPrice'],$rc['AdultNum'],$rc['ChildNum'],$rc['InfantNum'], $rc['CustID'], $rc['CustID'],$_GET['flightbookingid']);
		mysqli_free_result($rs);
		
		
	}
	
 	mysqli_close($conn);
?>
</body>
</html>