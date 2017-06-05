<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>

<body>
<?php
	session_start();
	require_once("../Connections/conn.php");
	if(isset($_POST["price"])){
		$sql= "update room set Price=".$_POST['price']." where RoomType='".$_POST['roomtype']."' and HotelID=".$_SESSION['uid'];
		mysqli_query($conn, $sql);
	}
	if(isset($_POST["size"])){
		$sql= "update room set RoomSize=".$_POST['size']." where RoomType='".$_POST['roomtype']."' and HotelID=".$_SESSION['uid'];
		mysqli_query($conn, $sql);
	}
	if(isset($_POST["adult"])){
		$sql= "update room set AdultNum=".$_POST['adult']." where RoomType='".$_POST['roomtype']."' and HotelID=".$_SESSION['uid'];
		mysqli_query($conn, $sql);
	}
	if(isset($_POST["child"])){
		$sql= "update room set ChildNum=".$_POST['child']." where RoomType='".$_POST['roomtype']."' and HotelID=".$_SESSION['uid'];
		mysqli_query($conn, $sql);
	}
	if(isset($_POST["smoke"])){
		$sql= "update room set NonSmoking=".$_POST['smoke']." where RoomType='".$_POST['roomtype']."' and HotelID=".$_SESSION['uid'];
		mysqli_query($conn, $sql);
	}
	if(isset($_POST["desc"])){
		$sql= "update room set RoomDesc='".$_POST['desc']."' where RoomType='".$_POST['roomtype']."' and HotelID=".$_SESSION['uid'];
		mysqli_query($conn, $sql);
	}
	mysqli_close($conn);
	setCookie("success","success",time()+20);
?>
	<script type="text/javascript">
		parent.location.href = "hotel_searchRoom.php?success=1";
	</script>
</body>
</html>