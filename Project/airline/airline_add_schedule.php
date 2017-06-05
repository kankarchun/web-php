<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline_add.css" />
<script type="text/javascript">
function getTotalDays(){
	var depDate=document.getElementById('depDate').value;
	var arrDate=document.getElementById('arrDate').value;
	datetime1 = new Date(depDate);
	datetime2 = new Date(arrDate);
	var diffDays = 0;
	if(datetime1 <= datetime2)
		diffDays = (datetime2.getTime() - datetime1.getTime()) / (1000*60);
  	document.getElementById("flyminute").value=diffDays;
}

function chkTextField() {
	var fit = true;
	var flightno = document.getElementById('flightno').value;
	var depairport = document.getElementById('depairport').value;
	var arrairport = document.getElementById('arrairport').value;
	var flyminute= document.getElementById('flyminute').value;
	var aircraft= document.getElementById('aircraft').value;
	fit = (flightno	&& depairport && arrairport && arrairport && aircraft && flyminute);
	if(fit){
		if(flyminute == "0"){
			document.getElementById("error").innerHTML = "Deperture datetime could Not Later than Arrival datetime";
			return false;
		}
		var printForm = "----------Confirm----------";
		printForm += "\nFlight Number: " + flightno;
		printForm += "\nDeperture Airport: " + depairport;
		printForm += "\nArrival Airport: " + arrairport;
		printForm += "\nAircraft: " + aircraft;
		printForm += "\nDeperture Date and Time: " + document.getElementById("depDate").value;
		printForm += "\nArrival Date and Time: " + document.getElementById("arrDate").value;
		printForm += "\nFly Minute: " + flyminute;
		return(confirm(printForm));
	}else{
		 document.getElementById("error").innerHTML = "Please input ALL Field";
		 return false;
	}
}

</script>

</head>

<body>
<div id="addschedulefrm">
<h3 align="center"><u>Add Flight Schedule</u></h3>
<form name="frmFlightAdd" method="Post"><table>
	<tr><td>FlightNo:</td><td><select id="flightno" name="flightno">
<?php
	session_start();
	require_once("../Connections/conn.php");
	$sql = "select flightNo from flightclass where airlineCode = '$_SESSION[uid]' group by flightNo";
	$result=mysqli_query($conn,$sql);
	while($rc=mysqli_fetch_assoc($result)){
		echo '<option value="'.$rc["flightNo"].'">'.$rc["flightNo"].'</option>';
	}
		echo '</select></td></tr>
	<tr><td>Deperture Airport:</td><td><input id="depairport" type="text" name="depairport"  ></td></tr>
	<tr><td>Arrival Airport:</td><td><input id="arrairport" type="text" name="arrairport"></td></tr>
	<tr><td>Aircraft:</td><td><input id="aircraft" type="text" name="aircraft" ></td></tr>
	<tr><td>Deperture Date and Time:</td><td><input id="depDate" type="datetime-local" name="depDate" onchange="getTotalDays();" /></td></tr>
	<tr><td>Arrival Date and Time:</td><td><input id="arrDate" type="datetime-local" name="arrDate" onchange="getTotalDays();" /></td></tr>
	<tr><td>Fly Minute:</td><td><input type="text" id="flyminute" name="flyminute" readonly></td></tr>
	</table><span id="error" style="color:yellow"></span>
	<input id="addschedule" type="submit" onclick="return chkTextField()" value="Add record">
</form></div>';
if(isset($_POST['flightno'])){
	$depDate = new DateTime($_POST['depDate']);
	$depDate = $depDate->format('Y-m-d H:i:s');
	$arrDate = new DateTime($_POST['arrDate']);
	$arrDate = $arrDate->format('Y-m-d H:i:s');
	$sql="select flightNo,depDateTime from flightschedule where flightNo='$_POST[flightno]' and depDateTime='$depDate'";
	$result=mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0){
		echo "Record is duplicated";
	}else{
		$sql= "insert into flightschedule values ('$_POST[flightno]','$depDate','$arrDate','$_POST[depairport]','$_POST[arrairport]','$_POST[flyminute]','$_POST[aircraft]');";
		mysqli_query($conn, $sql);
		echo "<script>parent.location.href = 'airline_home.php?page=flightSchedule'</script>";
	}
}
mysqli_free_result($result);
mysqli_close($conn);
?>

</body>
</html>