<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/all.css" />
<link rel="stylesheet" href="css/cust.css" />
<link rel="stylesheet" href="css/cust_edit.css" />
<title>Online Travel Information System - Customer - Personal(edit)</title>
<script type="text/javascript">
function back(){
	parent.location = 'cust_home.php?page=personal';
}
</script>
</head>

<body>
<?php
session_start();
require_once('../Connections/conn.php');

$customer = "SELECT * FROM customer WHERE customer.CustID = '$_SESSION[uid]'";
$ra = mysqli_query($conn,$customer) or die(mysqli_error($conn));
$rb = mysqli_fetch_assoc($ra);
$form = <<<EOD
<div id="container"><h2 align="center"><u>Edit</u></h2>
<form id="update" method="POST"><table align="center">
<tr><td colspan="2">Change password(*optional):</td></tr>
<tr><td>　　Old Password:</td><td><input type="password" name="oPwd"></td></tr>
<tr><td>　　New Password:</td><td><input type="password" name="nPwd"></td></tr>
<tr><td>Surname:</td><td><input type="text" name="Sname" value="%s"></td></tr>
<tr><td>Given Name:</td><td><input type="text" name="Gname" value="%s"></td></tr>
<tr><td>Date Of Birth:</td><td><input type="text" name="Dob" value="%s"></td></tr>
<tr><td>Gender:</td><td><input type="radio" name="rbGENDER" value="M" %s>Male
<input type="radio" name="rbGENDER" value="F" %s>Female</td></tr>
<tr><td>Passport:</td><td><input type="text" name="Pass" value="%s"></td></tr>
<tr><td>Mobile Number:</td><td><input type="text" name="Mno" value="%s"></td></tr>
<tr><td>Nationality:</td><td><input type="text" name="National" value="%s"></td></tr>
<tr><td><input type="submit" id="ch" value="Update Record" ></td>
<td><input type="button" onclick="back()" value="Canel"></td></tr>
</table><input type="hidden" name="tfID" value="%s"></form></div>
EOD;

printf($form
,$rb['Surname']
,$rb['GivenName']
,$rb['DateOfBirth']
,($rb['Gender']=='M'?'checked="checked"':'')
,($rb['Gender']=='F'?'checked="checked"':'')
,$rb['Passport']
,$rb['MobileNo']
,$rb['Nationality']
,$_SESSION['uid']
);

if(isset($_POST['tfID'])){
	extract($_POST);
	$sql = "select Password from customer where custID='$tfID'";	
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	$rc = mysqli_fetch_assoc($rs);
	$cust = "UPDATE customer SET Surname='".$Sname."'
		, GivenName='".$Gname."'
		, DateOfBirth ='".$Dob."'
		, Gender ='".$rbGENDER."'
		, DateOfBirth ='".$Dob."'";
	if($rc['Password']==$oPwd)
		$cust .= ", Password ='".$nPwd."'";
	else
		$cust .= ", Password ='$rc[Password]'";
	$cust .= ", Passport ='".$Pass."'
		, DateOfBirth ='".$Dob."'
		, MobileNo ='".$Mno."'
		, Nationality ='".$National."'
		WHERE CustID = '".$tfID."';";
	mysqli_query($conn, $cust) or die(mysqli_error($conn));
	mysqli_close($conn);
	echo "<script>back()</script>";
}
?>
</body>
</html>