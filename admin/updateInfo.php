<?php session_start();
include "../utils.php";
include "../utils/client.php";
$login = $_SESSION['login'];
$base = new Base(0,"",$_SESSION['pid']);


$info = $_POST['data'];
$r = $base->updateInfo($info);
$r = json_encode($r);
exit($r);

?>
