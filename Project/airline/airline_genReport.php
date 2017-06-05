<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/all.css" />
	<link rel="stylesheet" href="css/airline_genReport.css" />
	
	<script type="text/javascript">
		function setValue(group,output,format,start,end){
			document.getElementById('flight').value = group;
			document.getElementById('total').value = output;
			document.getElementById('form').value = format;
			document.getElementById('start').value = start;
			document.getElementById('end').value = end;
		}
		var barindex = 0;
		function setBar(id,val,largest){
			document.getElementById('bar'+id).style.height = (val/largest*350)+"px";
			document.getElementById('barct'+id).style.left = (barindex * 120 + 20)+"px";
			barindex++;
		}
	</script>
</head>

<body>
	<div id='report'>
		<form method='get' id='frm'>
			<h3 align='center'><u>Air Flight Analysis Report</u></h3>
			<select id='flight' name='flight'>
				<option value='flightno'>Flight No.</option>
				<option value='flightclass'>Flight Class</option>
			</select>
			<select id='total' name='total'>
				<option value='passengers'>Total Passengers</option>
				<option value='revenue'>Total Revenue</option>
			</select>
			<select id='form' name='form'>
				<option value='text'>Text</option>
				<option value='chart'>Graphical Chart</option>
			</select><br/>
			<u>Start Departure Date:</u> <input type='date' id='start' name='start'  required>
			<u>End Departure Date:</u> <input type='date' id='end' name='end' required><br />
			<input type='submit' value='Generate Report'>
		</form>
		<div id='content'>
<?php
	session_start();
	require_once("../Connections/conn.php");
	if(isset($_GET['form']) && isset($_GET['flight']) && isset($_GET['total']) && isset($_GET['start']) && isset($_GET['end'])){
		echo "<script>setValue('$_GET[flight]','$_GET[total]','$_GET[form]','$_GET[start]','$_GET[end]');</script>";
		$start= str_replace('T', ' ', $_GET['start']);
		$end= str_replace('T', ' ', $_GET['end']);
		$sql="SELECT ";
		if($_GET['flight']=="flightno")
			$sql.="flightbooking.FlightNo";
		else if($_GET['flight']=="flightclass")
			$sql.="flightbooking.Class";
		$sql.=",SUM(AdultNum+ChildNum+InfantNum) AS passengers,SUM(TotalAmt) AS revenue FROM flightbooking,flightclass where flightbooking.flightNo = flightclass.flightNo and flightbooking.class = flightclass.class and airlineCode = '$_SESSION[uid]' and DepDateTime between '".$start."' and '".$end."'";
		if($_GET['flight']=="flightno")
			$sql.=" GROUP BY flightbooking.FlightNo";
		else if($_GET['flight']=="flightclass")
			$sql.=" GROUP BY flightbooking.Class";
		if($_GET['total']=="passengers")
			$sql.=" ORDER BY passengers desc";
		else if($_GET['total']=="revenue")
			$sql.=" ORDER BY revenue desc";
		$rs=mysqli_query($conn,$sql);
		if($_GET['form']=="text"){
			if($_GET['flight']=="flightno")
				echo "<h3><u>Flight No.";
			else if($_GET['flight']=="flightclass")
				echo "<h3><u>Flight Class";
			if($_GET['total']=="passengers")
				echo " -- Total Passengers";
			else if($_GET['total']=="revenue")
				echo " -- Total Revenue";
			echo " ($_GET[start] to $_GET[end])</h3></u><br/>";
			$table = "<table border='1' align='center'><tr>";
			if($_GET['flight']=="flightno")
				$table.="<th width='40%'>Flight No</th>";
			else if($_GET['flight']=="flightclass")
				$table.="<th width='40%'>Flight Class</th>";
			if($_GET['total']=="passengers")
				$table.="<th width='60%'>Total Passengers</th>";
			else if($_GET['total']=="revenue")
				$table.="<th width='60%'>Total Revenue</th>";
			$table.="</tr>";
			while($rc=mysqli_fetch_array($rs)){
				$table.="<tr><td>".$rc[0]."</td><td>";
				if($_GET['total']=="passengers")
					$table.= $rc[1];
				else if($_GET['total']=="revenue")
					$table.= "$".$rc[2];
			}
			echo $table."</td></tr></table>";
		}else if($_GET['form']=="chart"){
			if($_GET['flight']=="flightno")
				echo "<b><u>Flight No.";
			else if($_GET['flight']=="flightclass")
				echo "<b><u>Flight Class";
			if($_GET['total']=="passengers")
				echo " -- Total Passengers";
			else if($_GET['total']=="revenue")
				echo " -- Total Revenue";
			echo " ($_GET[start] to $_GET[end])</b></u>";
			echo "<div id='chart'>";
			$maxpass = 0;
			$maxrev = 0;
			while($rc=mysqli_fetch_array($rs)){
				if($rc[1]> $maxpass)
					$maxpass = $rc[1];
				if($rc[2]> $maxrev)
					$maxrev = $rc[2];
			}
			$rs=mysqli_query($conn,$sql);
			while($rc=mysqli_fetch_array($rs)){
				echo "<div id='barct$rc[0]' class='barct'><div class='scale'>";
				if($_GET['total']=="passengers"){
					echo "$rc[1]</div><div id='bar$rc[0]' class='bar'></div>$rc[0]</div>";
					echo "<script>setBar('$rc[0]',$rc[1],$maxpass)</script>";
				}else if($_GET['total']=="revenue"){
					echo "$rc[2]</div><div id='bar$rc[0]' class='bar'></div>$rc[0]</div>";
					echo "<script>setBar('$rc[0]',$rc[2],$maxrev)</script>";
				}
			}
			echo "</div>";
			
		}
	}else{
		date_default_timezone_set('Asia/Taipei');
		echo "<script>setValue('flightno','passengers','text','".date("Y-m-d",strtotime('-1 years'))."','".date("Y-m-d")."');</script>";
	}
?></div></div>
</body>
</html>