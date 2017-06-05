<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
	<title>Online Travel Information System - Hotel search </title>
</head>
<script>
function setDeflt(hotel,checkin,nights){//set default value for input by old input
	document.getElementById('hotelGetID').value = hotel;
	document.getElementById('checkin').value = checkin;
	document.getElementById('night').value = nights;
}
function setDefault(hotel){
	document.getElementById('hotelGetID').value = hotel;
}
</script>
<body>

	<h2 align="center"><u>Hotel Search</u></h2>
	<form action="staff_searchHotel.php" id="frmHotelSearch" name="frmHotelSearch" method="Get">
    <?php
	session_start();
	require_once('../Connections/conn.php');
	$sql = "SELECT hotelID,engName FROM hotel";//get all hotel
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	echo '<span id="lbl">Hotel: ';//show hotel in select
	echo '<select id="hotelGetID" name="hotelGetID">
		<option value="all">--All--</option>';
	while($rc = mysqli_fetch_assoc($rs)){
    echo '<option value="'.$rc['hotelID'].'">'.$rc['engName'].'</option>';
	}
	echo '
	</select>
    </span> 
    <span id="lbl">Check-in Date: <input type="date" name="checkin" id="checkin"></span> 
    <span id="lbl">No. of Nights to stay: <input type="number" min="1" name="night" id="night"/></span>
	<input type="submit" value="Search" id="search" onClick="javascript:setValue();">
	</form>';
	
	//add to order
	if(isset($_POST['hotelid'])&&isset($_POST['roomtype'])&&isset($_POST['checkin'])&&isset($_POST['checkout'])&&isset($_POST['roomNUM'])){
		$array = array('hotelID' => $_POST['hotelid'], 'roomType' => $_POST['roomtype'], 'checkIn'=>$_POST['checkin'], 'checkOut'=>$_POST['checkout'],  'roomNum' => $_POST['roomNUM']);
		if(isset($_SESSION['hotelOrder']))
			$_SESSION['hotelOrder'][count($_SESSION['hotelOrder'])] = $array;
		else
			$_SESSION['hotelOrder'][0] = $array;
		echo "<script>alert('Order added');</script>";
	}
	
	//show search result
	echo "<div id='recon'>";
	if(isset($_GET['hotelGetID']) and $_GET['checkin']!= "" and $_GET['night'] > 0){//show have cal room num result
		echo "<script>setDeflt('$_GET[hotelGetID]','$_GET[checkin]','$_GET[night]')</script>";
		$sql = 'select * from hotel a,room b where a.HotelID = b.HotelID';
		if($_GET['hotelGetID'] != "all")
			$sql .= " and a.HotelID = '$_GET[hotelGetID]'";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$array = array();
		$i=0;
		while($rc = mysqli_fetch_assoc($rs)){
			$array[$i] = $rc;
			$i++;
		}
		//cal checkout date
		$checkout = new DateTime($_GET['checkin']);
		$checkout->modify('+'.$_GET['night'].' day');
		$checkout = $checkout->format('Y-m-d');
		$sql = "select roomType, roomNum, checkIn, checkOut,hotelID from hotelbooking";
		if($_GET['hotelGetID'] != "all")
			$sql .= " where HotelID = '$_GET[hotelGetID]'";
		$rs = mysqli_query($conn, $sql);
		while($rc=mysqli_fetch_assoc($rs)){
		if(($rc['checkIn']>=$_GET['checkin'] and $rc['checkIn']<=$checkout)
				or ($rc['checkOut']>=$_GET['checkin'] and $rc['checkOut']<=$checkout)){
				for($i=0;$i<count($array);$i++){
					if($array[$i]['RoomType'] == $rc['roomType'] && $array[$i]['HotelID'] == $rc['hotelID']){
						$array[$i]['RoomNum'] -= $rc['roomNum'];
						break;
					}
				}
			}
		}
		//show result
		for($i=0;$i<count($array);$i++){
			$array[$i]['Price'] *= $_GET['night'];
			echo '<div id="result">
			<table>
			<tr><td>Hotel Name:</td><td><i>'.$array[$i]['EngName'].'</i></td></tr>
			<tr><td>Address:</td><td><i>'.$array[$i]['Address'].'</i></td></tr>
			<tr><td>Contact:</td><td><i>'.$array[$i]['Tel'].'</i></td></tr>
			<tr><td>Room Type:</td><td><i>'.$array[$i]['RoomType'].'</i></td></tr>
			<tr><td>Room left:</td><td><i>'.$array[$i]['RoomNum'].'</i></td></tr>
			<tr><td>Price (each room):</td><td><i>$'.$array[$i]['Price'].'</i></td></tr>
			<tr><td>Room Size:</td><td><i>'.$array[$i]['RoomSize'].'m<sup>2</sup></i></td></tr>
			<tr><td>Room Desc:</td><td><i>'.$array[$i]['RoomDesc'].'</i></td></tr>
			</table>';
			if($checkout>date("Y-m-d")){
			echo '<form width="100%" method="post">
			<input type="submit" id="addorder" value="" />
			<input type="hidden" name="hotelid" value="'.$array[$i]["HotelID"].'">
			<input type="hidden" name="roomtype" value="'.$array[$i]["RoomType"].'">
			<input type="hidden" name="checkin" value="'.$_GET['checkin'].'">
			<input type="hidden" name="checkout" value="'.$checkout.'">
			<input type="hidden" name="roomNUM" value="'.$array[$i]['RoomNum'].'">
			</form>';
			}
			echo '</div>';
		}	
	}else{//show no room num ver result
		$sql = 'select a.EngName, a.Address, a.Tel, b.RoomType, b.Price, b.RoomDesc, b.RoomSize from hotel a,room b where a.HotelID = b.HotelID';
		if(isset($_GET['hotelGetID']) && $_GET['hotelGetID'] != "all")
			$sql .= " and a.HotelID = '$_GET[hotelGetID]'";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		while($rc=mysqli_fetch_assoc($rs)){
			echo '<div id="result"><table>
				<tr><td>Hotel Name:</td><td><i>'.$rc['EngName'].'</i></td></tr>
				<tr><td>Address:</td><td><i>'.$rc['Address'].'</i></td></tr>
				<tr><td>Contact:</td><td><i>'.$rc['Tel'].'</i></td></tr>
				<tr><td>Room Type:</td><td><i>'.$rc['RoomType'].'</i></td></tr>
				<tr><td>Price (each room):</td><td><i>'.$rc['Price'].' (per night)</i></td></tr>
				<tr><td>Room Size:</td><td><i>'.$rc['RoomSize'].'</i></td></tr>
				<tr><td>Room Desc:</td><td><i>'.$rc['RoomDesc'].'</i></td></tr></table>
				</div>';
		}
	}
	echo "</div>";
	mysqli_free_result($rs);
	mysqli_close($conn);
	?>
</body>
</html>