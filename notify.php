<?php
include "utils.php";

if (!isset($vkid) && !isset($idb)) {
	exit("[0,0]");
}

$output = Array();

if (isset($vkid)) {
	$output[0] = 2;
	$output[1] = musNotificationCount($vkid);
}

if (isset($idb)) {
	$output[0] = 1;
	$output[1] = notificationCount($idb);
}

$output = json_encode($output);


exit($output);
?>


