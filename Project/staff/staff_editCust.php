<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
	<script>
		function back(){
			location = "staff_searchCust.php";
		}
		
	</script>
</head>

<body>
<?php 
	require_once('../Connections/conn.php');
	if(isset($_POST['cID'])){
		extract($_POST);
		$sql = "UPDATE customer SET Surname='".$Sname."'
			, GivenName='".$Gname."'
			, DateOfBirth ='".$Dob."'
			, Gender ='".$rbGENDER."'
			, DateOfBirth ='".$Dob."'
			, Passport ='".$Pass."'
			, DateOfBirth ='".$Dob."'
			, MobileNo ='".$Mno."'
			, Nationality ='".$National."'
			WHERE CustID = '".$cID."';";	
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<script>back();</script>";	
	}else if (isset($_GET['custid'])) {
		$sql = "SELECT * FROM Customer WHERE CustID='" . $_GET['custid'] . "'";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$rc = mysqli_fetch_assoc($rs);
		$form = <<<EOD
		<div id="container"><h2 align="center"><u>Edit Customer Information</u></h2>
		<form method="POST"><table align="center">
			<tr><td>Customer ID</td><td><input type="text" name="cID" value="%s" readonly></td></tr>
			<tr><td>Surname:</td><td><input type="text" name="Sname" value="%s"></td></tr>
			<tr><td>Given Name:</td><td><input type="text" name="Gname" value="%s"></td></tr>
			<tr><td>Date Of Birth:</td><td><input type="text" name="Dob" value="%s"></td></tr>
			<tr><td>Gender:</td><td><input type="radio" name="rbGENDER" value="M" %s>Male
			<input type="radio" name="rbGENDER" value="F" %s>Female</td></tr>
			<tr><td>Passport:</td><td><input type="text" name="Pass" value="%s"></td></tr>
			<tr><td>Mobile Number:</td><td><input type="text" name="Mno" value="%s"></td></tr>
			<tr><td>Nationality:</td><td><input type="text" name="National" value="%s"></td></tr>
			<tr><td><input type="submit" id="ch" value="Update Record" ></td>
			<td><input type="button" onclick="back('%s')" value="Cancel"></td></tr>
			</table></form></div>
EOD;
		printf($form,$rc['CustID'],$rc['Surname'],$rc['GivenName']
			,$rc['DateOfBirth'],($rc['Gender']=='M'?'checked="checked"':'')
			,($rc['Gender']=='F'?'checked="checked"':'')
			,$rc['Passport'],$rc['MobileNo'],$rc['Nationality'],$rc['CustID']);
		mysqli_free_result($rs);
	}
 	mysqli_close($conn);
?>
</body>
</html>