<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/all.css" />
	<link rel="stylesheet" href="css/home.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<title>Online Travel Information System</title>
	<script type="text/javascript">
$(document).ready(function() {

  $('input').each(function() {

    $(this).on('focus', function() {
      $(this).parent('.css').addClass('active');
    });

    $(this).on('blur', function() {
      if ($(this).val().length == 0) {
        $(this).parent('.css').removeClass('active');
      }
    });

    if ($(this).val() != '') 
		$(this).parent('.css').addClass('active');

  });

});
		function showMsg() {
			document.getElementById("msg").style.display="inline";
		}
		function focusID(){
			document.getElementById("tid").focus();
		}
		function focusPW(){
			document.getElementById("tpw").focus();
		}
		function chkForm() {
			if (document.getElementById("tid").value == "" ){
				showMsg();
				document.getElementById("msg").innerHTML="Please Enter ID!<br/>";
				focusID();
				return false;
			}else if(document.getElementById("tpw").value == ""){
				showMsg();
				document.getElementById("msg").innerHTML="Please Enter Password!<br/>";
				focusPW();
				return false;
			}else {
				document.getElementById("frm").submit();
			}
		}
	</script>
	<?php
		function chkpw($user,$pw,$rs,$location,$type){
			if($rc = mysqli_fetch_assoc($rs) and $rc['Password'] == $pw){
				session_start();
				$_SESSION['uid'] = $user;
				$_SESSION['type']=$type;
				mysqli_free_result($rs);
				mysqli_close($conn);
				header($location);
			}else{
				html($user,"Password Not Match!");	
				die();
			}

		}
		function html($uid,$message){
			if($uid!="" or $message!=""){
				echo '<script type="text/javascript">
					for(var i=1;i<=3;i++){
						document.getElementById("icon"+i).style.animationName = "none";
						document.getElementById("icon"+i).style.webkitAnimationName = "none";
					}
				</script>';
			}
			echo'<form method="post" enctype="multipart/form-data" id="frm" align="center">
				<h2 align="center" id="title">Online Travel Information System</h2>
<div class="login">
  <div class="css">
    <label for="ID">ID</label>
    <input type="text" name="id" id="tid" value="'.$uid.'"/><br/><br/>
  </div>
  <div class="css">
    <label for="password">Password</label>
    <input type="password" name="pw" id="tpw"/><br/><br/>
  </div>
</div>
				<span id="input">
					<span id="msg">';
						if($message != "")
							echo "$message <br/><script type='text/javascript'>showMsg()</script>";
						if($message == "Password Not Match!")
							echo "<script type='text/javascript'>focusPW()</script>";
						if($message == "Invalid ID!")
							echo "<script type='text/javascript'>focusID()</script>";
					echo '</span>
					<input type="submit" onclick="return chkForm()" id="btnlogin" value="Login" />
				</span>
			</form>';
		}
	?>
</head>

<body>
	<div id="bg">
	<div id="icon1"></div><div id="icon2"></div><div id="icon3"></div>
	<?php
	if(isset($_POST['id']) && isset($_POST['pw'])) {
		$user = trim($_POST['id']);
		$pw = trim($_POST['pw']);

		require_once("Connections/conn.php");

		if(is_numeric($user)){
			$sql = "SELECT * FROM hotel WHERE HotelID='$user'";	//hotel
			$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			if(mysqli_num_rows($rs) > 0)
				chkpw($user,$pw,$rs,"Location:hotel/hotel_home.php","hotel");
		} else {
			if(strlen($user) <= 2){	//airline
				$sql = "SELECT * FROM airline WHERE AirlineCode='$user'";
				$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
				if(mysqli_num_rows($rs) > 0)
					chkpw($user,$pw,$rs,"Location:airline/airline_home.php","airline");
			}
			if(substr($user,0,1) == "C" && strlen($user) == 4){	//customer
				$sql = "SELECT * FROM customer WHERE CustID='$user'";
				$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
				if(mysqli_num_rows($rs) > 0)
					chkpw($user,$pw,$rs,"Location:cust/cust_home.php","cust");
			}
			$sql = "SELECT * FROM staff WHERE StaffID='$user'";	//staff
			$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			if(mysqli_num_rows($rs) > 0)
				chkpw($user,$pw,$rs,"Location:staff/staff_home.php","staff");
		}
		html("","Invalid ID!");
	}else
		html("","");
	?>
	</div> 		
</body>
</html>