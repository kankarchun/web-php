<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/staff_searchCust.css" />
	<title>Online Travel Information System - Update Customer Record </title>
</head>
<script>
function setValue(){//set the default value of input by old input
	var e = document.getElementById("select");
	var value = e.options[e.selectedIndex].value;
	document.getElementById('selected').value = value;
	document.getElementById('frmCustSearch').submit();
}
</script>
<body>
		<h2 align="center"><u>Customer Record</u></h2>
		<div id="frm">
		<form method="post" id="frmsearch">
			Customer ID: <input type="text" name="custid" id="tf">
			<input type="submit" value="" id="search">
		</form>
        <form action="staff_addCust.php"><input type="submit" value="Add" id="add"></form>
		</div>
        <?php
		require_once('../Connections/conn.php');
		$sql = "SELECT * FROM Customer";//find customer
		if(isset($_POST['custid']))
			$sql .= " where custID like '$_POST[custid]%'";
		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<div id='recon'>";
		while($rc = mysqli_fetch_assoc($rs)){//show customer information
			echo "<div id='rs' onclick='location = \"staff_custDetail.php?custid=$rc[CustID]\"'>
				Customer ID: <i>$rc[CustID]</i><br/>
				Surname: <i>$rc[Surname]</i><br/>
				Given Name: $rc[GivenName]</i><br/>
				Gender: <i>$rc[Gender]</i><br/>
				Mobile No: <i>$rc[MobileNo]</i>
				</div>";
		}  
		echo "</div>";
		mysqli_free_result($rs);
		mysqli_close($conn);

?>
</body>
</html>