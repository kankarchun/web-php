<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff.css" />
    <link rel="stylesheet" href="css/staff_booking.css" />
<style>
#custID{
	text-color:black;
	}
</style>
</head>

<body>
<?php
	require_once('../Connections/conn.php');

	if(isset($_POST['custID'])){
		extract($_POST);
		$sql = "INSERT INTO Customer ( CustID, Password, Surname, GivenName, DateOfBirth, Gender, Passport, MobileNo, Nationality)
VALUES ('".$_POST['custID']."','".$_POST['password']."','".$_POST['surname']."','".$_POST['givenname']."','".$_POST['dob']."','".$_POST['gender']."','".$_POST['passport']."','".$_POST['mobileno']."','".$_POST['nation']."')";
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	mysqli_free_result($rs);
	mysqli_close($conn);
	header("location:staff_searchCust.php");
	}else{
		$form = <<<EOD
		<div id="container"><h2 align="center"><u>Cust Register form</u></h2>
		<form method="POST"><table align="center">
		<tr><td>Customer ID :</td><td><input type="text" id="custID" name="custID" value="" /></td></tr>
		<tr><td>Password :</td><td><input type="password" id="password" name="password" value ="" /></td></tr>
		<tr><td>SurName :</td><td><input type="text" id="surname" name="surname" value="" /></td></tr>
		<tr><td>Given Name :</td><td><input type="text" id="givenname" name="givenname" value="" /></td></tr>
		<tr><td>DateOFBirth :</td><td><input type="date" id="dob" name="dob" /></td></tr>
		<tr><td>Gender : </td><td><input type="radio" id="gender" name="gender" value="M" />M 
						<input type="radio" id="gender" name="gender" value="F" />F </td></tr>
		<tr><td>Passport :</td><td><input type="text" id="passport" name="passport" value ="" /></td></tr>
		<tr><td>Mobile Number :</td><td><input type="text" id="mobileno" name="mobileno" value="" /></td></tr>
		<tr><td>Nationality :</td><td><input type="text" id="nation" name="nation" value="" /></td></tr>
		<tr><td><input type="submit" value="Add Record"></td><td>
				<input type="button" value="Cancel" onclick="window.location.href=\'staff_searchCust.php\'" /></td></tr>
		</form></table></div>
EOD;
		printf($form);
	}
?>
</body>
</html>