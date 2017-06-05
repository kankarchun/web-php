<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
<?php
require_once("../Connections/conn.php");

$sql_book= "delete from flightbooking where FlightNo='$_GET[flightno]' and DepDateTime='$_GET[depdate]'";
$sql= "delete from flightschedule where flightNo='".$_GET['flightno']."' and depDateTime='".$_GET['depdate']."'";
echo $sql_book;
echo $sql;
mysqli_query($conn, $sql_book);
mysqli_query($conn, $sql);
mysqli_close($conn);
?>
<script type="text/javascript">
	parent.location.href = "airline_home.php?page=flightSchedule";
</script>
</head>

<body>
</body>
</html>