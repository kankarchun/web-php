<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/hotel.css" />
	<link rel="stylesheet" href="css/input.css" />
	<link rel="stylesheet" href="css/searchRecord.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<title>Online Travel Information System - Hotel search record</title>
			<script type="text/javascript">
$(document).ready(function() {

  $('input').each(function() {

    $(this).on('focus', function() {
      $(this).parent('.css').addClass('active');
    });

    $(this).on('blur', function() {
      if ($(this).val().length == 0) {
        $(this).parent('.css').removeClass('active');
      }
    });

    if ($(this).val() != '') 
		$(this).parent('.css').addClass('active');

  });

});
	</script>
</head>

<body>
<div id="holder">
	<h3 align="center"><u>Check-in Check-out Record</u></h3>
	<form name="frmHotelRecord" method="Post" align="center">
		<div class="search" align="center">
  <div class="css">
    <label for="surname">Surname: </label>
    <input id="surname" type="text" name="surname"/>
  </div>
  <div class="css">
    <label for="phone">Mobile Number</label>
    <input id="phone" type="text" name="phone"/>
  </div>
</div>
		<input type="submit" value="Search" id="search">
	</form><br/>
</div>
	<?php
	session_start();
	require_once("../Connections/conn.php");
	$sql = "select surname,givenName,mobileNo,totalAmt,price,checkin,checkout,roomNum,roomType,remark from hotelbooking,customer where customer.custid=hotelbooking.custid and hotelID=".$_SESSION['uid'];
	if(!empty($_POST['surname']))
		$sql .= " and customer.surname='".$_POST['surname']."'";
	if(!empty($_POST['phone']))
		$sql .= " and customer.mobileNo='".$_POST['phone']."'";
	$rs = mysqli_query($conn, $sql);
	echo '<table width="100%">';
	echo "<tr><th>Customer Name</th>
		<th>Mobile No.</th>
		<th>Total Amount</th>
		<th>Price</td>
		<th>Check-in Date</th>
		<th>Check-out Date</th>
		<th>Room Type</th>
		<th>No. of Room Ordered</th>
		<th>Remark</th>
		</tr>";
	while($rc=mysqli_fetch_assoc($rs)){
		echo "<tr><td>".$rc['surname']." ".$rc['givenName']."</td>
			<td>".$rc['mobileNo']."</td>
			<td>$".$rc['totalAmt']."</td>
			<td>$".$rc['price']."</td>
			<td>".$rc['checkin']."</td>
			<td>".$rc['checkout']."</td>
			<td>".$rc['roomType']."</td>
			<td>".$rc['roomNum']."</td>
			<td>".$rc['remark']."</td>
			</tr>";
	}
	echo "</table>";
	mysqli_free_result($rs);
	mysqli_close($conn);
	?>
</body>
</html>