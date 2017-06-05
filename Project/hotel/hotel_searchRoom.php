<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/hotel.css" />
	<link rel="stylesheet" href="css/hotel_searchRoom.css" />
	<link rel="stylesheet" href="css/success.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<title>Online Travel Information System - Hotel search room</title>
	<script type="text/javascript">
	//no showing the update iframe if the user did not click the update button
		function noshow(){
			document.getElementById("updateframe").style.display = "none";
		}
	//show the related record of the user clicked update button and show the style inline
		function update(type) {
			document.getElementById("updateframe").style.display = "inline";
			document.getElementById("updateframe").src="hotel_updateRoom.php?roomtype="+type;
		}
	//set the value of user is inputted in the searching area ( checkin , nights, type)
		function setValue(checkin,nights,type){
			document.getElementById('checkin').value = checkin;
			document.getElementById('nights').value = nights;
			document.getElementById('type').value = type;
		}
	</script>
</head>

<body>
	<?php
		session_start();
		require_once("../Connections/conn.php");
		echo '<div id="holder"><form method="get" align="center">
		<h3 align="center"><u>Hotel search room</u></h3>
			<span id="lbl">Check-in Date: <input type="date" value="'.date("Y-m-d").'" name="checkin" id="checkin"></span>
			<span id="lbl">No. of Nights to stay: <input type="number" min="1" name="nights" id="nights" value="1"/></span>
			<span id="lbl">Room type: 
			<select name="type" id="type">';
				$sql = "SELECT RoomType FROM room WHERE HotelID = ".$_SESSION['uid'];
				$rs = mysqli_query($conn, $sql);
				echo '<option value="all" selected="selected">All Type</option>';
				while($rc=mysqli_fetch_assoc($rs)){
					echo '<option>'.$rc["RoomType"].'</option>';
				}
			?>
		</select></span>
		<input type="submit" value="Search" id="search" />
	</form>
	</div><br/>

	<?php
	if(isset($_GET['checkin']) and isset($_GET['nights']) and isset($_GET['type'])){
		echo "<script>setValue('$_GET[checkin]',$_GET[nights],'$_GET[type]')</script>";
		$sql = "select * from Room where hotelID=".$_SESSION['uid'];
		if($_GET['type'] != "all")
			$sql .= " and RoomType= '".$_GET['type']."'";
		$rs = mysqli_query($conn, $sql);
		$array = array();
		$totalroom = array();
		$i=0;
		while($rc = mysqli_fetch_assoc($rs)){
			$array[$i] = $rc;
			$totalroom[$i] = $array[$i]['RoomNum'];
			$i++;
		}
		$checkout = new DateTime($_GET['checkin']);
		$checkout->modify('+'.$_GET['nights'].' day');
		$checkout = $checkout->format('Y-m-d');
		$sql = "select roomType, roomNum, checkIn, checkOut from hotelbooking where hotelID=".$_SESSION['uid'];
		if($_GET['type'] != "all")
			$sql .= " and RoomType= '".$_GET['type']."'";
		$rs = mysqli_query($conn, $sql);
		while($rc=mysqli_fetch_assoc($rs)){
			if(($rc['checkIn']>=$_GET['checkin'] and $rc['checkIn']<=$checkout)
				or ($rc['checkOut']>=$_GET['checkin'] and $rc['checkOut']<=$checkout)){
				for($i=0;$i<count($array);$i++){
					if($array[$i]['RoomType'] == $rc['roomType']){
						$array[$i]['RoomNum'] -= $rc['roomNum'];
						break;
					}
				}
			}
		}
		//
		for($i=0;$i<count($array);$i++){
			if($array[$i]['RoomNum']<=0)
				continue;
			echo "<div id='room'><span id='rtype'>".$array[$i]['RoomType']."</span>";
			if($array[$i]['NonSmoking']==1)
				echo " (Non-Smoking)";
			else
				echo " (Allow Smoking)";
			echo '<span id="rtype"><b> '.$array[$i]["RoomNum"].' Room(s) Left</b> (Total:'.$totalroom[$i].')</span>';
			echo '<div id="btn"><button type="button" id="update" onClick="javascript:update(';
				echo "'".$array[$i]['RoomType']."'";
				echo ')">Update</button></div>';
			echo '<div id="price">$'.$array[$i]["Price"].'</div>';
			echo '<div id="desc">';
			echo ' Size:'.$array[$i]["RoomSize"].'m<sup>2</sup>'; 
			echo " Max Adult:".$array[$i]['AdultNum'];
			echo " Max Child:".$array[$i]['ChildNum']."<br/>";
			echo $array[$i]["RoomDesc"].'</div>';
			echo '</div>';
		}
		echo '<iframe id="updateframe"></iframe><script type="text/javascript">noshow()</script>';
	}
	mysqli_free_result($rs);
	mysqli_close($conn);
	

	if(isset($_GET['success'])){
	echo "<div class='success'>Update Room successfully</div>";
	}
	?>
	<! update success message will show in 3s and dismiss !>
	<script type="text/javascript">
	$(document).ready(function(){
    $('.success').hide(0).fadeIn(400).delay(3000).fadeOut(400); 
});
	</script>

</body>
</html>