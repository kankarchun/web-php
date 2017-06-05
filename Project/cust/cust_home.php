<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/cust.css" />
	<title>Online Travel Information System - Customer</title>
	<?php
		session_start();
		if(!(isset($_SESSION['uid']) && !empty($_SESSION['uid']))) {
			header('Location: ../home.php');
		}
	?>
	<script type="text/javascript">
		function logout(){
			location.href = "../logout.php";
		}
		function changeframe(lc) {
			var locat = "cust_"+lc+".php";
			if(document.getElementById("childframe").src = locat)
				return;
			document.getElementById("childframe").src = "../pagechanger.php"
			window.frames["childframe"].changepage(locat);
		}
	</script>
</head>

<body>
	<div id="header">
		<?php
			require_once("../Connections/conn.php");
			$sql = "SELECT * FROM Customer WHERE CustID = '". $_SESSION['uid']."'";
			$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			$rc = mysqli_fetch_assoc($rs);
			echo "<div id='headertitle' onclick='parent.location=\"cust_home.php\"'><b>Welcome, ";
			if($rc['Gender'] == "M")
				echo "Mr.";
			else
				echo "Miss ";
			echo $rc['Surname'] . " $rc[GivenName] ( ".$rc['CustID']." )</b></div>";
			
			if(isset($_COOKIE['remind'])){
				$first=true;
				date_default_timezone_set('Asia/Taipei');
				echo "<div id='remindmsg'>";
				foreach($_COOKIE['remind'] as $key => $value){
					$date = date('Y-m-d H:i:s');
					$date = strtotime("$date +7 day");
					$date = date('Y-m-d H:i:s', $date);
					$sql="select * from flightbooking where CustID='".$_SESSION['uid']."' and BookingID=".$value." and depDateTime <= '$date'";
					$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
					$rc = mysqli_fetch_assoc($rs);
					if(mysqli_num_rows($rs)>0){
						$dep=new DateTime($rc['DepDateTime']);
						$dep=$dep->format('Y-m-d H:i');
						if($first){
							echo "You have a current flight booking<br/>";
							$first = false;
						}
						echo "Flight no:$rc[FlightNo] Depature Date Time:$dep<br/>";
					}
				}
				echo "</div>";
			}
			mysqli_free_result($rs);
			mysqli_close($conn);
		echo '
		<div id="menu"><form method="get"></form>
			<ul><li><a href="cust_home.php?page=personal">Personal</a><div class="und"></div></li></ul>
			<ul><li><a href="cust_home.php?page=booking">Your booking</a><div class="und"></div></li></ul>
			<ul><li><a href="cust_home.php?page=bonus">Bonus</a><div class="und"></div></li></ul>
			<ul><li><a href="javascript:logout()">Logout</a><div class="und"></div></li></ul>	
		</div><div id="icon"></div>
	</div>
	<div id="main">
		<iframe src="../pagechanger.php" name="childframe" id="childframe"></iframe>
	</div>';
	if(isset($_GET['page']))
		echo "<script type='text/javascript'>changeframe('$_GET[page]')</script>";
	?>
</body>
</html>