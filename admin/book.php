<?php session_start();
include "../utils.php";
$today=10000*date("Y")+100*date("n")+date("j");

if (!isset($_SESSION['login']) || !isset($idb)) {
  exit("0");
} 


  //get data
  $name  = check($_POST['name']);
  $band  = check($_POST['band']);
  $add   = check($_POST['add']);
  $start = check($_POST['start']);
  $end   = check($_POST['end']);
  $room  = check($_POST['room']);
  $date  = check($_POST['date']);
  $phone = check($_POST['phone']);
  $price = check($_POST['price']);

$error   = book($idb,0,$date,$start,$end,$room,1,$band,$add,$phone,$name,0,0,$price);
$message = errorMessage($error);
$array   = $date.",".$start."-".$end;

$output = $error[0]."$&".$message."$&".$room."$&".$array;

// output
print($output);
?>