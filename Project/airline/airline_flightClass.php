<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline.css" />
	<title>Online Travel Information System - Flight</title>

	<script type="text/javascript">
		function add(){
			parent.location.href = "airline_home.php?page=add_class";
		}
	</script>
</head>

<body>
	<?php
		session_start();
		require_once("../Connections/conn.php");

	echo '<div id="holder"><h3 align="center"><u>Flight Class</u></h3>';

	echo '<input id="add" name="add" type="submit" value="Add" onClick="add()"></div>';

		$sql ="select * from flightclass where airlineCode='$_SESSION[uid]'";
		$rs = mysqli_query($conn, $sql);
		echo "<table border='1' align='center'>";
		echo "<tr><th>FlightNo</th>
			<th>Flight Class</th>
			<th>Adult Price</th>
			<th>Children Price</th>
			<th>Infant Price</th>
			<th>Tax</th></tr>";
		while($rc=mysqli_fetch_assoc($rs)){
			echo '<tr><td>'.$rc["flightNo"].'</td>
				<td>'.$rc["class"].'</td>
				<td>'.$rc["price_adult"].'</td>
				<td>'.$rc["price_children"].'</td>
				<td>'.$rc["price_infant"].'</td>
				<td>'.$rc["tax"].'</td>
				</tr>';
		}
		echo "</table><br/><br/>";

		mysqli_free_result($rs);
		mysqli_close($conn);
	?>
</body>
</html>