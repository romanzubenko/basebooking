<?php session_start();
include "../utils.php";
include "../utils/client.php";
include "../utils/user.php";

if (!isset($_POST['data'])) {
  exit("false");
}

$pid = $_SESSION['pid'];
$data = $_POST['data'];

$base = new Base(0,"",$pid);


$output = $base->deleteBooking($data);
$strOutput = json_encode($output);

exit($strOutput);
 


?>
