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
		function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
		{
  		$datetime1 = date_create($date_1);
    	$datetime2 = date_create($date_2);
    
   		 $interval = date_diff($datetime1, $datetime2);
    
   	 	return $interval->format($differenceFormat);
    
		}


	session_start();
	require_once('../Connections/conn.php');
	if(isset($_POST['custID'])){
		$checkCust = false;
		$sql = "SELECT CustID FROM Customer";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$sql_id = "SELECT BookingID FROM HotelBooking";
		$rs_id = mysqli_query($conn, $sql_id) or die(mysqli_error($conn));
		$bookingID = 0;
		$date = date("Y-m-d");
		while($rc_id = mysqli_fetch_assoc($rs_id)){	
			if($rc_id['BookingID'] > $bookingID){
				$bookingID = $rc_id['BookingID'];
			}
		}
		
		$bookingID++;
		while($rc = mysqli_fetch_assoc($rs)){
			if($rc['CustID'] == $_POST['custID']){
			
				$checkCust = true;
				break;
			}
		}
		
		
		if($checkCust)
			{	
				if($_POST['roomNum'] == 0){
					echo '<script>alert("Room Num cannot be 0!")</script>';
				}else{
				$day = dateDifference($_POST['checkin'],$_POST['checkout']);
				$total = ($day * $_POST['roomNum'] * $_POST['price']);
				$sql = "INSERT INTO HotelBooking VALUES ('"
				.$bookingID."','"
				.$date."','"
				.$_POST['staffID']."','"
				.$_POST['custID']."','"
				.$_POST['hID']."','"
				.$_POST['roomType']."','"
				.$_POST['price']."','"
				.$_POST['roomNum']."','"
				.$total."','"
				.$_POST['checkin']."','"
				.$_POST['checkout']."','"
				.$_POST['remark']."')";
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
				$_SESSION['confirm_total_hotel'] = $total;
				$_SESSION['order_cust'] = $_POST['custID'];
				echo '<script language="javascript">';
				echo 'alert("Booking Successfully!\n'.$_POST['custID'].' Total Booking Amount of Hotel is $'.$total.'");';
				echo 'back();';
				echo '</script>';
				die();
				}
			}else echo '<script>alert("CustID Not Found!")</script>';
		
		}
		if(isset($_GET['hotelid'])){
		$sql = 'select a.HotelID, a.EngName, a.Address, a.Tel, b.RoomType, b.Price, b.RoomDesc, b.RoomSize from hotel a,room b where a.HotelID = b.HotelID and a.HotelID = "'.$_GET['hotelid'].'" and b.RoomType = "'.$_GET['roomtype'].'"';
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$rc = mysqli_fetch_assoc($rs);
		$form = <<<EOD
		<div id="container"><h2 align="center"><u>Hotel Booking Information</u></h2>
		<form method="POST"><table align="center">
			<tr><td>Hotel No.</td><td> %s </td></tr>
			<tr><td>Hotel Name:</td><td><i> %s </i></td></tr>
			<tr><td>Room Type:</td><td><i> %s </i></td></tr>
			<tr><td>Price:</td><td> %s </td></tr>
			<tr><td>Check In:</td><td> %s </td></tr>
			<tr><td>Check Out:</td><td> %s </td></tr>
			<tr><td>Cust ID:</td><td><input type="text" name="custID" value="" required></td></tr>
			<tr><td>Room Number:</td><td><input type="number" name="roomNum" value="1" min="1" max="%s"> You can book %s room only!</td></tr>
			<tr><td>Remark:</td><td><textarea name="remark" placeholder="Remark..." rows="4" cols="30"></textarea></td>
			<tr><td><input type="submit" id="confirm" value="Confirm" ></td>
			<td><input type="button" onclick="back()" value="Cancel"></td></tr>
			<input type="hidden" name="staffID" value="%s"> 
			<input type="hidden" name="checkin" value="%s"> 
			<input type="hidden" name="checkout" value="%s"> 
			<input type="hidden" name="price" value="%s"> 
			<input type="hidden" name="hID" value="%s"> 
			<input type="hidden" name="roomType" value="%s"> 
			</table></form></div>
EOD;
		printf($form,$rc['HotelID'],$rc['EngName'],$rc['RoomType'],$rc['Price'],$_GET['checkin'],
			$_GET['checkout'],$_GET['roomnum'],$_GET['roomnum'],$_SESSION['uid'],$_GET['checkin'],$_GET['checkout'],$rc['Price'],$rc['HotelID'],$rc['RoomType']);
		mysqli_free_result($rs);
		
		
	}
?>
</body>
</html>