<?php session_start();
include "../utils.php";
/*  FILE OUTPUT

0,0 - smth went wrong 
0,1 - not all fields were completed
1,1 - success
*/


if (!isset($idb) || empty($idb)) {
  exit("0");
}


function alterDebt($vkid,$idb,$debt) {
  $debt = intval($debt);
  if ($vkid == 0) {
    exit ("0");
  }
  if (!is_int($debt)) {
    exit("0");
  } 
  
  $r1 = mysql_query("UPDATE {$idb}_list SET `debt`='$debt' WHERE vkid='$vkid'");

  if ($r1) { 
    exit ("1");
  } else {
    exit ("0");
  }
}


function addToBL($vkid,$idb) {  
  $query = mysql_query("UPDATE {$idb}_list SET `bl`='1' WHERE vkid='$vkid'");
  if ($query) {
    $output = "1,1";
  } else {
    $output = "0,0";
  }
  exit ($output);
}

function removeFromBL($vkid,$idb) {  
  $query = mysql_query("UPDATE {$idb}_list SET `bl`='0' WHERE vkid='$vkid'");
  if ($query) {
    $output = "1,1";
  } else {
    $output = "0,0";
  }
  exit ($output);
}

$vkid = check($_POST['vkid']);
$debt = check($_POST['debt']);
$type = check($_POST['type']);


/* type
  1 - add BL             
  2 - add debt          
  3 - remove from BL   
*/


if (!empty($vkid) && !empty($type))  {
  switch ($type) {  
  case 1:
    alterDebt($vkid,$idb,$debt);
    break;
  case 2:
    addToBL($vkid,$idb);
    break;
  case 3:
    removeFromBL($vkid,$idb);
    break;
  default:
    exit("0,0");
    break;
  }
} else {
  exit("0,1");
}
?>