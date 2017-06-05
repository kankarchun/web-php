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
	
if(isset($_GET['hotelbookingid'])){//find hotel booking
		$sql = "SELECT * FROM HotelBooking WHERE BookingID='" . $_GET['hotelbookingid'] . "'";
		
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$rc = mysqli_fetch_assoc($rs);
		//get the detail of hotel
		$sql_roomtype = "SELECT RoomType, Price FROM room where HotelID = '".$rc['HotelID']."'";
		$rs_roomtype = mysqli_query($conn, $sql_roomtype) or die(mysqli_error($conn));
		//show form
		echo '<div id="container"><h2 align="center"><u>Hotel Booking Information</u></h2>';
		echo '<form action="staff_checkNum.php" method="GET"><table align="center">';
		echo '<tr><td>Hotel ID</td><td><input type="text" name="hID" value="'.$rc['HotelID'].'" readonly></td></tr>';
		echo '<tr><td>Order Date:</td><td><input type="text" name="OrderDate" value="'.$rc['OrderDate'].'" readonly></td></tr>';
		echo '<tr><td>Room Number:</td><td><input type="number" name="RoomNum" value="'.$rc['RoomNum'].'" min="1" ></td></tr>';
		//show room type
		echo '<tr><td>Room Type:</td><td><select name="SelectRoomType" id="SelectRoomType">';
		while($rc_roomtype = mysqli_fetch_assoc($rs_roomtype)){
		echo '<option value="'.$rc_roomtype['RoomType'].'">'.$rc_roomtype['RoomType'].'</option>';}
		echo '</select></td></tr>';
		echo '<tr><td>Check In:</td><td><input type="date" name="Checkin" value="'.$rc['Checkin'].'" ></td></tr>';
		echo '<tr><td>Check out:</td><td><input type="date" name="Checkout" value="'.$rc['Checkout'].'" ></td></tr>';
		echo '<tr><td>Remark:</td><td><input type="text" name="Remark" value="'.$rc['Remark'].'"></td></tr>';
		echo '<tr><td><input type="submit" id="ch" value="Update Record" ></td>';
		echo '<td><input type="button" onclick="'."back('".$rc['CustID']."'".')" value="Cancel"></td></tr>';
		echo '<input type="hidden" value="'.$rc['CustID'].'" name="custID" id="custID">';
		echo '<input type="hidden" value="'.$_GET['hotelbookingid'].'" name="bookID" id="bookID">';
		echo '<input type="hidden" value="'.$rc['RoomNum'].'" name="bookedRoom" id="bookedRoom">';
		echo '</table></form></div>';
		mysqli_free_result($rs);
	}
	
 	mysqli_close($conn);
?>
</body>
</html>