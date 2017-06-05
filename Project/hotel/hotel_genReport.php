<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/hotel.css" />
	<style>
	  input[type="date"]:before {
    content: attr(placeholder) !important;
	font-size:17px;
    color: black;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
	</style>
</head>

<body>
<div id="holder">
<h3 align="center"><u>Hotel Room Statistics Report</u></h3>
<?php
session_start();
require_once("../Connections/conn.php");

if(isset($_GET['start']) && isset($_GET['end'])){
echo '<form method="get" id="reportform" align="center">
<input type="date" id="start" name="start" placeholder="Start room booking date:" value="'.$_GET["start"].'"> 
<input type="date" id="end" name="end" placeholder="End room booking date:" value="'.$_GET["end"].'">
<input type="submit" value="Generate Report" id="search" onclick="return genReport()">
</form><br/>';

$sql="SELECT RoomType,COUNT(CustID),SUM(RoomNum),SUM(TotalAmt),MAX(Remark) FROM hotelbooking where Checkin between '$_GET[start]' and '$_GET[end]' and HotelID='$_SESSION[uid]' GROUP BY RoomType";

$rs=mysqli_query($conn,$sql);
echo "<table align='center'>
<tr><th>RoomType</th>
<th>Number of customer ordered</th>
<th>Number of rooms booked</th>
<th>Total revenue</th>
</tr>";
while($rc=mysqli_fetch_assoc($rs)){
echo "<tr><td>".$rc["RoomType"]."</td>
<td>".$rc["COUNT(CustID)"]."</td>
<td>".$rc["SUM(RoomNum)"]."</td>
<td>".$rc["SUM(TotalAmt)"]."</td>
</tr>";
}
echo"</table>";
}else{
	echo '<form method="get" id="reportform" align="center">
				<input type="date" id="start" name="start" placeholder="Start room booking date:"> 
				<input type="date" id="end" name="end" placeholder="End room booking date:">
<input type="submit" value="Generate Report" id="search" onclick="return genReport()">
</form>';
}
?>
</div>
</body>
</html>