<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/hotel.css" />
    
    <script>
		function back(custID){
			location = "staff_custDetail.php?custid="+custID;
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
	
	if(isset($_GET['hID']) and $_GET['Checkin']!= ""){
		require_once('../Connections/conn.php');
		$sql = 'select * from hotel a,room b where a.HotelID = b.HotelID and a.HotelID = "'.$_GET['hID'].'"';
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$array = array();
		$day = dateDifference($_GET['Checkin'],$_GET['Checkout']);
		$i=0;
		while($rc = mysqli_fetch_assoc($rs)){
			$array[$i] = $rc;
			$i++;
		}
		$checkout = new DateTime($_GET['Checkin']);
		$checkout->modify('+'.$day.' day');
		$checkout = $checkout->format('Y-m-d');
		$sql = "select roomType, roomNum, checkIn, checkOut,HotelID from hotelbooking";
		$rs = mysqli_query($conn, $sql);
		while($rc=mysqli_fetch_assoc($rs)){
		if(($rc['checkIn']>=$_GET['Checkin'] and $rc['checkIn']<=$checkout)
				or ($rc['checkOut']>=$_GET['Checkin'] and $rc['checkOut']<=$checkout)){
				for($i=0;$i<count($array);$i++){
					if($array[$i]['RoomType'] == $rc['roomType'] && $array[$i]['HotelID'] == $rc['HotelID']){
						$array[$i]['RoomNum'] -= $rc['roomNum'];
						break;
					}
				}
			}
			for($i=0;$i<count($array);$i++)
			if($array[$i]['RoomType'] == $_GET['SelectRoomType']){
				$compareNum = $array[$i]['RoomNum'];
				break;
			}else continue;
		}
		
		$compareNum += $_GET['bookedRoom'];
		
		if($compareNum < $_GET['RoomNum'])
			{
				echo "There are not enough room! You can only booking ".$compareNum."!";
			}
			else {
		$sql_price = 'Select Price from Room where HotelID = "'.$_GET['hID'].'" and RoomType = "'.$_GET['SelectRoomType'].'"';
		$rs_price = mysqli_query($conn, $sql_price) or die(mysqli_error($conn));
		$rc_price = mysqli_fetch_assoc($rs_price);
		$Price = $rc_price['Price'];
		$day = dateDifference($_GET['Checkin'], $_GET['Checkout']);
		$custID = $_GET['custID'];
		$total =  $_GET['RoomNum'] * $Price * $day;
		if($day > 1){
			$Remark = $day." days";}
		else $Remark = "1 day";
		
		$sql = "UPDATE HotelBooking SET RoomType='".$_GET['SelectRoomType']."'
			, RoomNum ='".$_GET['RoomNum']."'
			, OrderDate ='".$_GET['OrderDate']."'
			, Price ='".$Price."'
			, Checkin ='".$_GET['Checkin']."'
			, Checkout ='".$_GET['Checkout']."'
			, Remark ='".$Remark."'
			, TotalAmt ='".$total."'
			WHERE BookingID = '".$_GET['bookID']."';";
		echo "<script>back('".$custID."')</script>";}
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
	}
?>
</body>
</html>