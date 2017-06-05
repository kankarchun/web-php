<?php
  $hostname = "127.0.0.1";
  $username = "root";
  $pwd = "";
  $db = "projectDB";
  $conn = mysqli_connect($hostname, 
          $username, $pwd, $db) 
          or die(mysqli_connect_error());
?>
