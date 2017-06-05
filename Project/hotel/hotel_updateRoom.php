<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/hotel.css" />
	<link rel="stylesheet" href="css/hotel_updateRoom.css" />
	<title>Online Travel Information System - Hotel update room</title>
	<script type="text/javascript">
		function cancel() {
			parent.window.document.getElementById("updateframe").style.display = "none";
		}
	</script>
</head>

<body>
	<?php	
		if(isset($_GET['roomtype'])){
			session_start();
			require_once("../Connections/conn.php");
			$sql = "select * from room where hotelID =".$_SESSION['uid']." and roomtype ='".$_GET['roomtype']."'";
			$rs = mysqli_query($conn, $sql);
			$rc = mysqli_fetch_assoc($rs);
			echo '<h3 align="center"><u>Hotel update room</u></h3>
			<form name="frmHotelUpdate" method="Post" action="hotel_updateRoom_sql.php" ><table>
				<tr><td>Room type:</td><td><input id="roomtype" type="text" name="roomtype" value="'.$rc["RoomType"].'" readonly/></td></tr>
				<tr><td>Room price:</td><td><input id="price" type="number" name="price" min="0" value="'.$rc["Price"].'"/></td></tr>
				<tr><td>Smoking:</td><td>';
					if($rc['NonSmoking']==1){
						echo '<input id="smoke" type="radio" name="smoke" value="1" checked="checked"/>Non-smoking
							<input id="smoke" type="radio" name="smoke" value="0"/>Allow smoking</td></tr>';
					}else
						echo '<input id="smoke" type="radio" name="smoke" value="1"/>Non-smoking
							<input id="smoke" type="radio" name="smoke" value="0" checked="checked"/>Allow smoking</td></tr>';
				echo '<tr><td>Room size:</td><td><input id="size" type="number" name="size" value="'.$rc["RoomSize"].'"/>m<sup>2</sup></td></tr>
				<tr><td>Max Adult:</td><td><input id="adult" type="number" name="adult" min="0" value="'.$rc["AdultNum"].'"/></td></tr>
				<tr><td>Max Child:</td><td><input id="child" type="number" name="child" min="0" value="'.$rc["ChildNum"].'"/></td></tr>
				<tr><td>Room Description:</td><td><textarea id="desc" rows="4" cols="50" name="desc">'.$rc["RoomDesc"].'</textarea></td></tr></table>
				<input type="submit" id="updatebtn" value="Update Room">
				<input type="button" id="cancelbtn" value="Cancel" onClick="javascript:cancel()">
			</form>';
			mysqli_free_result($rs);
			mysqli_close($conn);
		}else
			echo '<script type="text/javascript">cancel();</script>';
	?>
</body>
</html>