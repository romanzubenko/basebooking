<?php session_start();
include "utils.php";
include "utils/client.php";
include "utils/user.php";

if (!isset($_POST['data'])) {
  exit("false");
}

$data = $_POST['data'];
$idb = check($data['idb']);
$start = check($data['start']);
$end = check($data['end']);
$date = check($data['date']);
$phone = check($data['phone']);
$band = check($data['band']);
$price = check($data['price']);
$room = check($data['room']);
$name = check($data['name']);

$base = new Base($data['idb'],"",0);

if ($base->isOwner()) {
   $admin = true; 
}

if (isset($_SESSION['vkid'])) {
    $user = new User($_SESSION['vkid']);
    $userInfo = $user->getInfo();
    $name = $userInfo['name'];
    $lastname = $userInfo['lastname'];
    $vkid = $userInfo['vkid'];
    $admin = false;
    $hash = "reserve";
}

if (isset($data['hash']) && !empty($data['hash'])) {
    $name = check($data['name']);
    $lastname = check($data['lastname']);
    $hash = check($data['hash']);
    $vkid = check($data['vkid']);
    $admin = false;
}

$baseInfo = $base->getInfo();
$nf = $baseInfo['NF'];

$bookcode = book($idb,$vkid,$date,$start,$end,$room,$admin,$band,$add,$phone,$name,$lastname,$hash,$price,$nf);
$errorbooking = errorMessage($bookcode);

$output = array();
$output[0] = $bookcode[0];
$output[1] = $errorbooking;
$strOutput = json_encode($output);

exit($strOutput);
 


?>
