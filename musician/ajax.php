<?php session_start();
include "../utils.php";

if (isset($idb)) {
  exit("0.0");
}

$date = check($_POST['date']);
$start = check($_POST['start']);
$end = check($_POST['end']);
$room = check($_POST['room']);
$idb = check($_POST['id']);
$type = check($_POST['type']);


if ($date === "" || $start === "" || $end === "" || $room === "" || $idb === "" || $type === "") { 
  exit("0.0");
}


// check if booking exist
  $r = mysql_query("SELECT * FROM {$idb}_booking WHERE room='$room' and end='$end' and start='$start' and date='$date' ");
  $r = mysql_fetch_array($r);
  $price = $r['price'];
  $vkid  = $r['vkid'];
  
  if (!isset($r['start']) || empty($r['start'])) {
	 exit("0.0");
  } 
  unset($r);
// check if booking exist

  
// check if booking is below deadline
if (pastDeadline($date, $start,$idb)) {
  $deadlinefail = true;
} else {
  $deadlinefail = false;
}


if ($deadlinefail && $type == 1) {
  exit("0.1");
}

// check if booking is below deadline

// delete booking START

  	$del = mysql_query("DELETE FROM {$idb}_booking   WHERE room='$room' and end='$end' and start='$start' and date='$date' ");
  	$del2 = mysql_query("DELETE FROM {$vkid}_history  WHERE room='$room' and end='$end' and start='$start' and date='$date' and idb='$idb'");
    
    if ($r['accept']) {
      changeCurrent($idb,$vkid,-1);
    }
      
    
    cancelNotify($idb,$room,$start,$end,$date,$vkid); // notification 
// delete booking END




if($del && $del2 && $r1 && $r2 && !$deadlinefail) {
  exit("1.0");
} else if ($del && $del2 && $r1 && $r2 && $deadlinefail) {
  exit("1.1");
} else {
  exit("0.0");
}

?>