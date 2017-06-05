<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
    <link rel="stylesheet" href="css/staff_booking.css" />
<title>Customer Order</title>
</head>

<body>
<h3 align="center"><u>Customer Order</u></h3>
<?php
	function clear()//clear order
		{
		unset($_SESSION['confirm_total_flight']);
		unset($_SESSION['confirm_total_hotel']);
		unset($_SESSION['flightOrder']);
		unset($_SESSION['hotelOrder']);
		unset($_SESSION['order_cust']);
		}
	session_start();
	$k = 0;
	$j = 0;
	//clear oder button
	echo '<form method="GET"><input type="hidden" name="clear"><input type="submit" align="right" id="clear" value="Clear Order" onClick="clear()" />Remember to clear order after finish each booking</form>';
	require_once("../Connections/conn.php");
	if(isset($_GET['clear'])){clear();}
	
	if(isset($_SESSION['confirm_total_flight'])){//show flight total price
			echo '<h1 align="center">'.$_SESSION['order_cust'].' Total Booking of AirTicket is $'.$_SESSION['confirm_total_flight'].'</h1>';
		}else{
	if(isset($_SESSION['flightOrder'])){//show flight in the order
	$sql = "SELECT a.depDateTime, a.arrDateTime,  a.depAirport, a.arrAirport , a.flightNo ,b.class, b.price_Adult, b.price_Children, b.price_Infant, b.tax
, c.airlineName, c.icon FROM flightSchedule a, flightClass b,airline c where a.flightNo = b.flightNo and b.airlineCode = c.airlineCode and (";
		for($i =0;$i<count($_SESSION['flightOrder']);$i++){//get order flight
			if($i != 0){
					$sql .= ' or ';
				}
			$sql .= ' a.flightNo = "'.$_SESSION['flightOrder'][$i]['flightNo'].'"';
			$sql .= ' and a.depDateTime = "'.$_SESSION['flightOrder'][$i]['depDateTime'].'"';
			$sql .= ' and b.class = "'.$_SESSION['flightOrder'][$i]['class'].'"';
			}$sql .= ")";
		
	//show ticket
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	echo "<div id='recon'>";
	while($rc = mysqli_fetch_assoc($rs)){
		$rc['depDateTime'] = (new DateTime($rc['depDateTime']))->format('Y-m-d H:i');
		$rc['arrDateTime'] = (new DateTime($rc['arrDateTime']))->format('Y-m-d H:i');
		echo '<div id="result"><form action="staff_addFlight.php" method="GET">';
		echo "<img src='../airline/img/AirlineCarrier/$rc[icon]' id='airicon'>
			<table><tr><td>Flight No:</td><td><i>$rc[flightNo] ($rc[airlineName])</i></td></tr>
			<tr><td>Departure:</td><td><i>$rc[depAirport] ($rc[depDateTime])</i></td></tr>
			<tr><td>Arrival:</td><td><i>$rc[arrAirport] ($rc[arrDateTime])</i></td></tr>
			<tr><td>Flight Class:</td><td><i>$rc[class]</i></td></tr>
			<tr><td>Adult Price:</td><td><i>$$rc[price_Adult]</i></td></tr>
			<tr><td>Children Price:</td><td><i>$$rc[price_Children]</i></td></tr>
			<tr><td>Infant Price:</td><td><i>$$rc[price_Infant]</i></td></tr>
			<tr><td>Tax:</td><td><i>$$rc[tax]</i></td></tr>";
		echo '<tr><td></td><td><i><input type="hidden" name="flightNo" value="'.$rc['flightNo'].'"><input type="submit" id="bookorder" value="Book"></i></td></tr>
			</table></form></div>';
	}
	echo "</div>";
	}}
		
	echo '<br />';
	
	if(isset($_SESSION['confirm_total_hotel'])){//show hotel total
		echo '<h1 align="center">'.$_SESSION['order_cust'].' Total Booking of Hotel is $'.$_SESSION['confirm_total_hotel'].'</h1>';
		}else{
	if(isset($_SESSION['hotelOrder'])){//show hotel order
		$sql = 'select a.HotelID, a.EngName, a.Address, a.Tel, b.RoomType, b.Price, b.RoomDesc, b.RoomSize from hotel a,room b where a.HotelID = b.HotelID and (';
		for($i =0;$i<count($_SESSION['hotelOrder']);$i++){
			if($i != 0){
					$sql .= ' or ';
				}
			$sql .= ' a.HotelID = "'.$_SESSION['hotelOrder'][$i]['hotelID'].'"';
			$sql .= ' and b.RoomType = "'.$_SESSION['hotelOrder'][$i]['roomType'].'"';
			}$sql .= ")";
			$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			
		//show
	echo "<div id='recon'>";
	$r = 0;
	while($rc = mysqli_fetch_assoc($rs)){
		echo '<div id="result"><form action="staff_addHotel.php" method="GET">';
		echo "<table><tr><td>Hotel Name:</td><td><i>$rc[EngName]</i></td></tr>
			<tr><td>Address:</td><td><i>$rc[Address]</i></td></tr>
			<tr><td>Contact:</td><td><i>$rc[Tel]</i></td></tr>
			<tr><td>Room Type:</td><td><i>$rc[RoomType]</i></td></tr>
			<tr><td>Room left:</td><td><i>".$_SESSION['hotelOrder'][$r]['roomNum']."</i></td></tr>
			<tr><td>Price (each room):</td><td><i>$$rc[Price]</i></td></tr>
			<tr><td>Room Size:</td><td><i>$rc[RoomSize]m<sup>2</sup></i></td></tr>
			<tr><td>Room Desc:</td><td><i>$rc[RoomDesc]</i></td></tr>";
			
			echo '<tr><td></td><td><i><input type="submit" id="bookorder" value="Book" /></i></td></tr>
			<input type="hidden" name="hotelid" value="'.$rc['HotelID'].'">
			<input type="hidden" name="roomtype" value="'.$rc['RoomType'].'">
			<input type="hidden" name="roomnum" value="'.$_SESSION['hotelOrder'][$r]['roomNum'].'">
			<input type="hidden" name="checkin" value="'.$_SESSION['hotelOrder'][$r]['checkIn'].'">
			<input type="hidden" name="checkout" value="'.$_SESSION['hotelOrder'][$r]['checkOut'].'">
			
			</table></form></div>';
			$r++;
			}
			echo "</div>";
	}}
	//show total amount
	if(isset($_SESSION['confirm_total_flight']) && isset ($_SESSION['confirm_total_hotel'])){
			$amount = $_SESSION['confirm_total_flight'] + $_SESSION['confirm_total_hotel'];
			echo '<br /><h1 align="center">'.$_SESSION['order_cust'].' Total Booking Amount is $'.$amount.'!</h1>';
		}
		//show no order
	if(!isset($_SESSION['hotelOrder']) && !isset($_SESSION['flightOrder']))
	{
		echo '<h1 align="center">There have not booking order!</h1>';
		}	
	
?>
</body>
</html>