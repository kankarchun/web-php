<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/all.css" />
<link rel="stylesheet" href="css/cust.css" />
<link rel="stylesheet" href="css/cust_booking.css" />
<title>Online Travel Information System - Customer - Personal</title>
<script type="text/javascript">
function checkReminder(bookId,depDateTime){
	if(document.getElementById(bookId).checked==true){
		var time=Date.parse(depDateTime);
		var date= new Date(time);
		var expires = "expires=" + date.toGMTString();
		document.cookie="remind['"+bookId+"']"+"="+bookId+";"+expires;
	}else if(document.getElementById(bookId).checked==false){
		var d=new Date();
		d.setTime(d.getTime()-1);
		var expires="expires="+d.toGMTString();
		document.cookie="remind['"+bookId+"']"+"="+bookId+";"+expires;
	}
	parent.location='cust_home.php?page=booking';
}
</script>
</head>

<body>

<?php
	session_start();
	require_once('../Connections/conn.php');
	date_default_timezone_set('Asia/Taipei');
	$today=date('Y-m-d H:i:s');

	echo'<h3 align="center"><u>Air Ticket</u></h3>';
	$sql = "select * from flightbooking where custid = '$_SESSION[uid]' order by DepDateTime desc";
	$rs = mysqli_query($conn,$sql);
	if(mysqli_num_rows($rs) <= 0)
		echo'<h3>No Air Ticket Booking Record</h3>';
	else{
		while($rc=mysqli_fetch_assoc($rs)){
			$DepDateTime = new DateTime($rc['DepDateTime']);
			$DepDateTime = $DepDateTime->format('Y-m-d H:i');
			echo "<div id='result'>Flight Number: <i>$rc[FlightNo]</i> <br/>
				Departure Date&Time: <i>$DepDateTime</i><br/>
				Flight Class: <i>$rc[Class]</i> 
				Order Date: <i>$rc[OrderDate]</i><br/>
				Adult: <i>$$rc[AdultPrice] X $rc[AdultNum]</i> 
				Child: <i>$$rc[ChildPrice] X $rc[ChildNum]</i> 
				Infant: <i>$$rc[InfantPrice] X $rc[InfantNum]</i><br/> 
				Total Amount: <i>$$rc[TotalAmt]</i>";
			if(strtotime($today) < strtotime($rc['DepDateTime']))
				echo "<input type='checkbox' class='reminder' name='$rc[BookingID]' id='$rc[BookingID]' onclick='checkReminder(\"$rc[BookingID]\",\"$rc[DepDateTime]\")' />
					<label for='$rc[BookingID]' class='containReminder'></label>";
			echo "</div>";
		}
	}
	
	echo'<h3 align="center"><u>Hotel Booking</u></h3>';
	$sql = "select * from hotelbooking where custid = '$_SESSION[uid]'";
	$rs = mysqli_query($conn,$sql);
	
	if(mysqli_num_rows($rs) <= 0)
		echo'No Hotel Booking Record';
	else{
		while($rf = mysqli_fetch_assoc($rs)){
			echo"<div id='result'>";
			echo "Hotel ID: <i>$rf[HotelID]</i> Room Type: <i>$rf[RoomType]</i><br/>
				Price: <i>$$rf[Price]</i> Room Ordered: <i>$rf[RoomNum]</i><br/>
				Check-in Date: <i>$rf[Checkin]</i> Check-out Date: <i>$rf[Checkout]</i><br/>
				Remark: <i>$rf[Remark]</i>";
			echo"</div>";
		}
	}
	mysqli_free_result($rs);
	mysqli_close($conn);


if(isset($_COOKIE['remind'])){
	foreach($_COOKIE['remind'] as $key => $value){
		echo "<script type=\"text/javascript\">
			 document.getElementById(".$value.").checked=true;
			 </script>
			 ";
	}
	
}



?>
</body>
</html>