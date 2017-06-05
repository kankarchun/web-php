<!doctype html>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/all.css" />
<link rel="stylesheet" href="css/cust.css" />
<link rel="stylesheet" href="css/cust_Bonus.css" />
<title>Online Travel Information System - Customer - Personal(edit)</title>
</head>

<body>
<?php
	session_start();
	require_once('../Connections/conn.php');
	$bonuspt = 0;
	$sql = "select totalAmt from flightbooking where custID = '$_SESSION[uid]'";
	$rs = mysqli_query($conn, $sql);
	while($rc=mysqli_fetch_array($rs)){
		$bonuspt += $rc[0];
	}
	echo '<u>Bonus Point: '.$bonuspt.'</u>';
	$sql="Update customer set BonusPoint= $bonuspt where custID = '$_SESSION[uid]'";
	mysqli_query($conn,$sql);
	
	date_default_timezone_set('Asia/Taipei');
	$freeticket = ($bonuspt / 5) ;
	$tickettype = 0;//0=adult,1=child,2=infant
	while($tickettype <= 2){
		$sql = "SELECT * FROM airline,flightschedule,flightclass WHERE airline.airlineCode = flightclass.airlinecode and flightclass.flightNo = flightschedule.flightNo and depDateTime > '".date('Y-m-d H:i:s')."'";
		switch($tickettype){
			case 0:
				$sql .= " and flightclass.price_adult <= $freeticket order by price_adult desc";
				break;
			case 1:
				$sql .= " and flightclass.price_children <= $freeticket order by price_children desc";
				break;
			case 2:
				$sql .= " and flightclass.price_infant <= $freeticket order by price_infant desc";
				break;
		}
		$rs = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if(mysqli_num_rows($rs)>0){
			$head = "";
			switch($tickettype){
				case 0:
					$head = "Adult";
					break;
				case 1:
					$head = "Children";
					break;
				case 2:
					$head = "Infant";
					break;
			}
			printf("<h3 align='center'><u>%s Air Ticket(Free)</u></h3><div id='recon'>",$head);
			while($rc = mysqli_fetch_assoc($rs)){
				$depDateTime = new DateTime($rc['depDateTime']);
				$depDateTime = $depDateTime->format('Y-m-d H:i');
				echo "<div id='result'>
					<img src='../airline/img/AirlineCarrier/$rc[icon]' id='carriericon'>
					<b><i>$rc[airlineName]</i></b> Flight Number: <i>$rc[flightNo]</i> 
					Class: <i>$rc[class]</i><br/>";
				switch($tickettype){
				case 0:
					echo "<span id='pr'>$$rc[price_adult]</span>";
					break;
				case 1:
					echo "<span id='pr'>$$rc[price_children]</span>";
					break;
				case 2:
					echo "<span id='pr'>$$rc[price_infant]</span>";
					break;
				}
				echo " Departure Date&Time: <i>$depDateTime</i></div>";
			}
		}
		echo "</div>";
		$tickettype++;
	}	
	mysqli_free_result($rs);
	mysqli_close($conn);
?>
</body>
</html>