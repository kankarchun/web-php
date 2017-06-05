<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Delete Record</title>
</head>

<body>
<?php 
	require_once('../Connections/conn.php');
	if(isset($_GET['flightbookingid'])){
		$sql = "select custID from flightbooking where bookingid ='$_GET[flightbookingid]'";
		$rs = mysqli_query($conn, $sql);
		$rc = mysqli_fetch_array($rs);
		$sql = "delete from flightbooking where bookingid ='$_GET[flightbookingid]'";
		$rs = mysqli_query($conn, $sql);
		header("location:staff_custDetail.php?custid=$rc[custID]");
	}else if(isset($_GET['hotelbookingid'])){
		$sql = "select custID from hotelbooking where bookingid ='$_GET[hotelbookingid]'";
		$rs = mysqli_query($conn, $sql);
		$rc = mysqli_fetch_array($rs);
		$sql = "delete from hotelbooking where bookingid ='$_GET[hotelbookingid]'";
		$rs = mysqli_query($conn, $sql);
		header("location:staff_custDetail.php?custid=$rc[custID]");
	}
	mysqli_free_result($rs);
	mysqli_close($conn);
?>
</body>
</html>