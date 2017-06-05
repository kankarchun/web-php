<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline.css" />
	<link rel="stylesheet" href="css/airline_searchPassenger.css" />
<script type="text/javascript">
	function checkTextField() {
		var name = document.getElementById('surname').value;
		var phone= document.getElementById('mobileno').value;
		if(!name && !phone){
			document.getElementById("error").textContent="Please input a Surname or Mobile Number";
			return false;
		}else{
			return true;
		}
	}
</script>
</head>

<body>
<div id="holder"><h3 align="center"><u>Search Passenger</u></h3>
<form name="frmPassenger" method="GET">
<?php
	session_start();
	require_once("../Connections/conn.php");
	$sql="select Surname from flightbooking,flightclass,customer where flightbooking.CustID=customer.CustID and flightbooking.flightNo = flightclass.flightNo and flightbooking.class = flightclass.class and airlineCode = '$_SESSION[uid]' group by Surname";
	$rs = mysqli_query($conn, $sql);
	echo "Surame: <input list='surnames' id='surname' name='surname'><datalist id='surnames'>";
	while($rc=mysqli_fetch_assoc($rs)){
		echo "<option value='".$rc['Surname']."'>".$rc['Surname']."</option>";
	}
	echo "</datalist> ";
	$sql="select MobileNo from flightbooking,flightclass,customer where flightbooking.CustID=customer.CustID and flightbooking.flightNo = flightclass.flightNo and flightbooking.class = flightclass.class and airlineCode = '$_SESSION[uid]' group by MobileNo";
	$rs = mysqli_query($conn, $sql);
	echo "Mobile Number: <input list='mobilenos' id='mobileno' name='mobileno'><datalist id='mobilenos'>";
	while($rc=mysqli_fetch_assoc($rs)){
		echo "<option value='".$rc['MobileNo']."'>".$rc['MobileNo']."</option>";
	}
	echo "</datalist><br />
		<span id='error' style='color:yellow'></span><br/>
		<input type='submit' onclick='return checkTextField()' value='Search Passenger'>
		</form></div>";

	if((isset($_GET['surname']) && isset($_GET['mobileno'])) || isset($_GET['custid'])){
		$customerid = "";
		if(isset($_GET['surname']) && isset($_GET['mobileno'])){
			$sql="select * from flightbooking,customer,flightclass where flightbooking.flightNo = flightclass.flightNo and flightbooking.class = flightclass.class and flightbooking.CustID=customer.CustID and airlineCode = '$_SESSION[uid]'";
			if(isset($_GET['surname']) && trim($_GET['surname']) != "")
				$sql.=" and surname = '$_GET[surname]'";
			if(isset($_GET['mobileno']) && trim($_GET['mobileno']) != "")
				$sql.=" and mobileno = '$_GET[mobileno]'";
			$sql.=" group by customer.custID";
			$rs = mysqli_query($conn,$sql);
			if(mysqli_num_rows($rs)>1){
				echo "Please select a Passenger.<br />";
				echo "<div id='passengerlist'>";
				while($rc=mysqli_fetch_assoc($rs)){
					echo "<input type='button' id='selectbox' 
						value='Passenger: $rc[Surname] $rc[GivenName] (Tel: $rc[MobileNo])'
						onclick='location.href=\"airline_searchPassenger.php?custid=".$rc['CustID']."\"'>";
				}
				echo "</div>";
			}else{
				$rc = mysqli_fetch_assoc($rs);
				$customerid = $rc['CustID'];
			}
		}else if(isset($_GET['custid']))
			$customerid = $_GET['custid'];
		if($customerid != ""){
			$sql="select * from flightbooking,flightclass,customer where customer.CustID='$customerid' and flightbooking.flightNo = flightclass.flightNo and flightbooking.class = flightclass.class and flightbooking.CustID=customer.CustID and airlineCode = '$_SESSION[uid]' group by bookingid";
			$rs = mysqli_query($conn, $sql);
			$rc = mysqli_fetch_assoc($rs);
			//cal bonus
			$bonus = 0;
			$bsql = "select totalAmt from flightbooking where custID = '$customerid'";
			$brs = mysqli_query($conn, $bsql);
			while($brc=mysqli_fetch_array($brs)){
				$bonus += $brc[0];
			}
			$bsql="Update customer set BonusPoint= $bonus where custID = '$customerid'";
			mysqli_query($conn,$bsql);
			//bonus end
			echo "<div id='info'>Passenger Name: ".$rc['Surname']." ".$rc['GivenName']."<br />";
			echo "Passenger Tel: ".$rc['MobileNo']."<br/>
				Bonus Points: $bonus</div>";
			mysqli_data_seek($rs,0);
			echo "<table border='1' align='center'>";
			echo "<tr><th>Order Date</th>
				<th>Flight Number</th>
				<th>Departure</th>
				<th>Class</th>
				<th>Adult Number</th>
				<th>Children Number</th>
				<th>Infant Number</th>
				<th>Total Amount</th></tr>";
			while($rc=mysqli_fetch_assoc($rs)){
				$depdate = new DateTime($rc['DepDateTime']);
				$depdate = $depdate->format('Y-m-d H:i');
				echo "<tr><td>$rc[OrderDate]</td>
					<td>$rc[FlightNo]</td>
					<td>$depdate</td>
					<td>$rc[class]</td>
					<td>$rc[AdultNum]</td>
					<td>$rc[ChildNum]</td>
					<td>$rc[InfantNum]</td>
					<td>$$rc[TotalAmt]</td></tr>";
			}
			echo "</table>";
		}
	}else{
		$sql="select * from flightbooking,customer,flightclass where flightbooking.flightNo = flightclass.flightNo and flightbooking.class = flightclass.class and flightbooking.CustID=customer.CustID and airlineCode = '$_SESSION[uid]'";
		$sql.=" group by customer.custID";
		$rs = mysqli_query($conn,$sql);
		if(mysqli_num_rows($rs)>0){
			echo "Please select a Passenger.<br />";
			echo "<div id='passengerlist'>";
			while($rc=mysqli_fetch_assoc($rs)){
				echo "<input type='button' id='selectbox' 
				value='Passenger: $rc[Surname] $rc[GivenName] (Tel: $rc[MobileNo])'
				onclick='location.href=\"airline_searchPassenger.php?custid=".$rc['CustID']."\"'>";
			}
			echo "</div>";
		}else{
			echo "No Passenger";
		}
	}
	mysqli_free_result($rs);
	mysqli_close($conn);
?>
</body>
</html>