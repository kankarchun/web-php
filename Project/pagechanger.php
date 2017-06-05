<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/all.css" />
	<link rel="stylesheet" href="css/pagechanger.css" />
	<script type="text/javascript">
		function changepage(locat) {
			location.href = locat;
		}
	</script>
</head>

<body>
<div id="pagechanger">
<?php
session_start();
		if(isset($_SESSION["type"])){
			if($_SESSION["type"]=="hotel"){
			echo '<div id="Hotelcontent" >
			<a href="hotel/hotel_home.php?page=searchRecord" target="_parent">
			<figure>
				<img src="hotel/img/hotelRecord.jpg" alt="searchRecord"></img>
				<figcaption>Search Record</figcaption>
			</figure>
			</a>
			<a href="hotel/hotel_home.php?page=searchRoom" target="_parent">
			<figure>
				<img src="hotel/img/hotelRoom.jpg" alt="searchRoom"></img>
				<figcaption>Search Room</figcaption>
				</figure>
			</a>
			<a href="hotel/hotel_home.php?page=genReport" target="_parent">
			<figure>
				<img src="hotel/img/genReport.jpg" alt="genReport"></img>
				<figcaption>Generate Report</figcaption>
				</figure>
			</a>
		</div>';
		}
		if($_SESSION["type"]=="airline"){
		echo '<div id="Aircontent" >
			<a href="airline/airline_home.php?page=flightSchedule" target="_parent">
			<figure>
				<img src="airline/img/flightSchedule.jpg" alt="flightschedule"></img>
				<figcaption>Flight Scheule</figcaption>
				</figure>
			</a>
			<a href="airline/airline_home.php?page=flightclass" target="_parent">
			<figure>
				<img src="airline/img/flightclass.jpg" alt="flightclass"></img>
				<figcaption>Flight Class</figcaption>
				</figure>
			</a>
			<a href="airline/airline_home.php?page=searchPassenger" target="_parent">
			<figure>
				<img src="airline/img/flightPassenger.jpg" alt="searchpassenger"></img>
				<figcaption>Search Passenger</figcaption>
				</figure>
			</a>
			<a href="airline/airline_home.php?page=genReport" target="_parent">
			<figure>
				<img src="airline/img/flightReport.jpg" alt="genReport"></img>
				<figcaption>Generate Report</figcaption>
				</figure>
			</a>
		</div>';
		}
		if($_SESSION["type"]=="staff"){
		echo '<div id="Staffcontent" >
			<a href="staff/staff_home.php?page=searchAirline" target="_parent">
			<figure>
				<img src="staff/img/staffAirline.jpg" alt="searchAirline"></img>
				<figcaption>Search Airline</figcaption>
				</figure>
			</a>
			<a href="staff/staff_home.php?page=searchHotel" target="_parent">
			<figure>
				<img src="staff/img/staffHotel.jpg" alt="searchHotel"></img>
				<figcaption>Search Hotel</figcaption>
				</figure>
			</a>
			<a href="staff/staff_home.php?page=searchCust" target="_parent">
			<figure>
				<img src="staff/img/staffCust.jpg" alt="searchCust"></img>
				<figcaption>Search Customer</figcaption>
				</figure>
			</a>
			<a href="staff/staff_home.php?page=order" target="_parent">
			<figure>
				<img src="staff/img/staffOrder.jpg" alt="order"></img>
				<figcaption>Order</figcaption>
				</figure>
			</a>
		</div>';
		}
		if($_SESSION["type"]=="cust"){
		echo '<div id="Custcontent" >
			<a href="cust/cust_home.php?page=personal" target="_parent">
			<figure>
				<img src="cust/img/custPersonal.jpg" alt="personal"></img>
				<figcaption>Personal</figcaption>
				</figure>
			</a>
			<a href="cust/cust_home.php?page=booking" target="_parent">
			<figure>
				<img src="cust/img/custBooking.jpg" alt="booking"></img>
				<figcaption>Booking</figcaption>
				</figure>
			</a>
			<a href="cust/cust_home.php?page=bonus" target="_parent">
			<figure>
				<img src="cust/img/custbonus.jpg" alt="bonus"></img>
				<figcaption>Bonus</figcaption>
				</figure>
			</a>
		</div>';
		}
		
		}
?>
</div>
</body>
</html>