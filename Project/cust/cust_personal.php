<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/cust.css" />
	<link rel="stylesheet" href="css/cust_personal.css" />
	<script>
		function edit(){
			parent.location = "cust_home.php?page=edit";
		}
	</script>
</head>

<body>
<?php
	session_start();
	require_once('../Connections/conn.php');
	$sql = "SELECT * FROM customer WHERE CustID = '$_SESSION[uid]'";
	$ra = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	$rb = mysqli_fetch_assoc($ra);

	echo '<div id="container"><h2 align="center"><u>Personal Information</u></h2>';
	printf ('<span id="edit" onclick="edit()">Edit</span>');
	echo'<form><table align="center">
		<tr><td>Customer ID:</td><td>'.$rb['CustID'].'</td></tr>
		<tr><td>Surname:</td><td>'.$rb['Surname'].'</td></tr>
		<tr><td>Given Name:</td><td>'.$rb['GivenName'].'</td></tr>
		<tr><td>Date Of Birth:</td><td>'.$rb['DateOfBirth'].'</td></tr>
		<tr><td>Gender:</td><td>'.$rb['Gender'].'</td></tr>
		<tr><td>Passport:</td><td>'.$rb['Passport'].'</td></tr>
		<tr><td>Mobile Number:</td><td>'.$rb['MobileNo'].'</td></tr>
		<tr><td>Nationality:</td><td>'.$rb['Nationality'].'</td></tr>';
		
	$bonus = 0;
	$sql = "select totalAmt from flightbooking where custID = '$rb[CustID]'";
	$rs = mysqli_query($conn, $sql);
	while($rc=mysqli_fetch_array($rs)){
		$bonus += $rc[0];
	}
	echo '<tr><td>Bonus Point:</td><td>'.$bonus.'</td></tr></table></div>';
	$sql_bonus="Update customer set BonusPoint= $bonus where custID = '$rb[CustID]'";
	mysqli_query($conn,$sql_bonus);
?>

</body>
</html>