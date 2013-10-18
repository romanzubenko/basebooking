<?php session_start();
include "../utils.php";

if (!isset($idb) || empty($idb)) {
  exit("0");
}

function notcome($date,$start,$end,$room,$vkid,$price,$idb,$admin) {
    
  $done = 0;
  $query1 = mysql_query("UPDATE {$idb}_booking  SET `done`='$done' WHERE vkid='$vkid' and date='$date' and start='$start' and room='$room'");
  
  if (!$admin) {
    $query2 = mysql_query("UPDATE {$vkid}_history SET `done`='$done' WHERE idb='$idb' and date='$date' and start='$start' and room='$room'");
    userNotify(4,$idb,$room,$start,$end,$date,$vkid);
    changePast($idb,$vkid,-1);
  }

// output code
  if ($query1 && !$admin) {
    $output = "-1";
  } else if ($query1 && $admin) {
    $output = "-2";
  } else {
    $output = "0";
  }
// output code

  exit ($output);
}

function addDebt($vkid,$price,$idb) {
  if ($vkid == 0) {
    exit ("0");
  }
  
  $r = mysql_query("SELECT * FROM {$idb}_list WHERE vkid='$vkid'");
  $debt = mysql_fetch_array($r);
  $debt = $debt['debt']+$price;
  $r1 = mysql_query("UPDATE {$idb}_list SET `debt`='$debt' WHERE vkid='$vkid'");

  if ($r1) { 
    if ($vkid == 0) {
      exit ("2");
    } else {
      exit ("1");
    }
  } else {
    exit ("0");
  }
}

function BL($vkid,$idb) {  
  $query = mysql_query("UPDATE {$idb}_list SET `bl`='1' WHERE vkid='$vkid'");
  userNotify(5,$idb,"","","","",$vkid);
  if ($query) {
    $output = -1;
  } else {
    $output = 0;
  }
  exit ($output);
}

function delete($date,$start,$end,$room,$vkid,$price,$idb,$admin) {
 
  $notexist = bookingNotExist($idb,$start,$end,$date,$room);
  
  if ($notexist) {
    exit ("2,0");
  }
  
  $deadlineViolation = pastDeadline($date,$start,$idb);

  $del = mysql_query("DELETE FROM {$idb}_booking   WHERE room='$room' and end='$end' and start='$start' and date='$date' ");
  
  if (!$admin) {
    $del2 = mysql_query("DELETE FROM {$vkid}_history  WHERE room='$room' and end='$end' and start='$start' and date='$date' and idb='$idb'");
    userNotify(2,$idb,$room,$start,$end,$date,$vkid);
    changeCurrent($idb,$vkid,-1);
    
  } 
  
  if (!$del) {
    exit("0,0");
  } 

  if ($deadlineViolation) {
    exit("-1,".$price);
  } else {
    exit ("-1,-1");
  }

}

function accept($date,$start,$end,$room,$vkid,$price,$idb,$admin) {
  $notexist = bookingNotExist($idb,$start,$end,$date,$room);
  if ($notexist) {
    exit ("0");
  }

  $r = mysql_query("UPDATE {$idb}_booking SET `accept`='1' WHERE room='$room' and end='$end' and start='$start' and date='$date'");
  
  if (!$admin) {
    $r2 = mysql_query("UPDATE {$vkid}_history SET `status`='1' WHERE room='$room' and end='$end' and start='$start' and date='$date' and idb='$idb'");
    changeCurrent($idb,$vkid,1);
    userNotify(3,$idb,$room,$start,$end,$date,$vkid);
  }

  if ($r) {
    exit("-1");
  } else {
    exit("0");
  }
  
}

function notaccept($date,$start,$end,$room,$vkid,$price,$idb,$admin) {
  $notexist = bookingNotExist($idb,$start,$end,$date,$room);
  if ($notexist) {
    exit ("0");
  }

  $r = mysql_query("DELETE FROM {$idb}_booking WHERE room='$room' and end='$end' and start='$start' and date='$date' ");
  
  if (!$admin) {
    $r1 = mysql_query("DELETE FROM {$vkid}_history WHERE room='$room' and end='$end' and start='$start' and date='$date' and idb='$idb' ");
    userNotify(1,$idb,$room,$start,$end,$date,$vkid);
  }

  if ($r && $admin) {
    exit("-1");
  } else if ($r && !$admin) {
    exit("-2");
  } else {
    exit("0");
  }
  
}



$date  = check($_POST['date']);
$start = check($_POST['start']);
$end   = check($_POST['end']);
$room  = check($_POST['room']);
$vkid  = check($_POST['vkid']);
$price = check($_POST['price']);
$type  = check($_POST['type']);


/* type
  0 - cancel            output:   [1]:= [{},{}]
  1 - add BL            output:   
  2 - add debt          output: 
  3 - notcome           output: 
  4 - delete photo      output: 
  5 - accept            output: 
  6 - notaccept         output: 
*/

$booking = mysql_query("SELECT * FROM {$idb}_booking WHERE date='$date' and start='$start' and room='$room' ");
if (!$booking) {
  exit("0");
}

$booking = mysql_fetch_array($booking);
$admin = $booking['admin'];


if ($date === "" && $start === "" && $end === "" && $room === "" && $price === "" && $type === "") { 
  exit("0");
}

switch ($type) {  
case 0:
  delete($date,$start,$end,$room,$vkid,$price,$idb,$admin);
  break;
case 1:
  BL($vkid,$idb);
  break;
case 2:
  addDebt($vkid,$price,$idb);
  break;
case 3:
  notcome($date,$start,$end,$room,$vkid,$price,$idb,$admin);
  break;
case 5:
  accept($date,$start,$end,$room,$vkid,$price,$idb,$admin);
  break;
case 6:
  notaccept($date,$start,$end,$room,$vkid,$price,$idb,$admin);
  break;
}


?>