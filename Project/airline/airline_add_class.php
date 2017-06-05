<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline_add.css" />
<script type="text/javascript">
	function chkTextField() {
		var flightno = document.getElementById('flightno').value;
		if(!flightno){
			document.getElementById("error").textContent="Please input Flight Number!";
			return false;
		}else{
			return(confirm("Are you sure to save this class?"));
		}
	}
</script>

</head>

<body>

<?php
	session_start();
	echo '<div id="addclassfrm">
	<h3 align="center"><u>Add Flight Class</u></h3>
	<i>Airline Code: '.$_SESSION["uid"].'</i>
		<form name="frmFlightAddClass" method="Post"><table>
		<tr><td>Flight Number:</td><td><input id="flightno" type="text" name="flightno"></td></tr>
		<tr><td>Flight Class:</td><td><select id="class" name="class">
			<option value="Busniess">Business</option>
			<option value="Econ">Econ</option>
		</select></td></tr>
		<tr><td>Adult Price:</td><td><input id="priceAdult" type="number" min="0" name="priceAdult" required="required" value="0"></td></tr>
		<tr><td>Children Price:</td><td><input id="priceChild" type="number" min="0" name="priceChild" required="required" value="0"></td></tr>
		<tr><td>Infant Price:</td><td><input id="priceInfant" type="number" min="0" name="priceInfant" required="required" value="0"></td></tr>
		<tr><td>Tax:</td><td><input id="tax" type="number" min="0" name="tax" required="required" value="0"></td></tr></table>
		<span id="error" style="color:yellow"></span>
		<input id="addclass" type="submit" onclick="return chkTextField()" value="Add Record">
	</form></div>';

	if(isset($_POST['flightno'])){
		require_once("../Connections/conn.php");
		$sql="select * from flightclass where flightNo='".$_POST['flightno']."' and class='".$_POST['class']."'";
		$result=mysqli_query($conn,$sql);
		if(mysqli_num_rows($result)>0){
			echo "<h3>Record is duplicated</h3>";
		}else{
			$sql= "insert into flightclass values ('$_POST[flightno]','$_POST[class]','$_SESSION[uid]',$_POST[priceAdult],$_POST[priceChild],$_POST[priceInfant],$_POST[tax])";
			mysqli_query($conn, $sql);
			echo '<script type="text/javascript">
				parent.location.href = "airline_home.php?page=flightClass"
				</script>';
		}
		mysqli_free_result($result);
		mysqli_close($conn);
	}
?>
</body>
</html>