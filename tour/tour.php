<?php session_start();
include "../utils.php";

if (!isset($idb) || empty($idb)) {
  exit("0,0");
}

function checkBaseName($name,$idb) {
  $r = mysql_query("SELECT * FROM bases WHERE name='$name' AND id<>'$idb'");
  if (!$r) {
    return false;
  }
  $r = mysql_fetch_array($r);

  if (!empty($r['name'])) {
    return false;
  } else {
    return true;
  }
}

function changeBaseName($name,$idb) {
  $r = mysql_query("UPDATE bases SET `name`='$name' WHERE id='$idb'");

  if ($r) {
    return true;
  } else {
    return false;
  }
}

function changePass($p) {
  $login = $_SESSION['login'];
  $p = md5($p);
  $r = mysql_query("UPDATE users SET `password`='$p' WHERE login = '$login'");
  if ($r) {
    return true;
  } else {
    return false;
  }
}

function updateBase($town,$phone,$timezone,$rooms,$mode,$idb) {
  $timezone = intVal($timezone);
  $rooms = intVal($rooms);
  if ($rooms < 1 || $rooms > 20) {
    $rooms = 1;
  }
  $accept = 1;
  if ($mode == 1) {
    $accept = 1;
  } else {
    $accept = 0;
  }


  /* OLD CODE FOR HANDLING ROOMS CHANGE*/
  $komn = $rooms; // only adjustment needed
  $prevkomn = mysql_query("SELECT komn FROM bases WHERE id='$idb'");
  $prevkomn = mysql_fetch_array($prevkomn);
  $prevkomn = $prevkomn['komn'];

  
  if ($komn > $prevkomn){
    for ($i = $prevkomn + 1; $i <= $komn; $i++){
      $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_schedule` (`rooms`) VALUES ('$i')");
      $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_equip` (`id`) VALUES ('$i')");
    }
  }

  if ($komn < $prevkomn) {
    for ($i=$prevkomn; $i>$komn; $i--) {
      $sc=mysql_query("DELETE FROM {$idb}_schedule WHERE rooms='$i'");
      $sc = mysql_query("DELETE FROM {$idb}_equip WHERE id='$i'");
    }
  }
   /* OLD CODE FOR HANDLING ROOMS CHANGE*/


  $r = mysql_query("UPDATE `b108859_wordpress`.`bases` SET `booking`='1',`town`='$town',`phone`='$phone',`timezone`='$timezone',`komn`='$rooms',`accept`='$accept' WHERE id='$idb'");

  if ($r) {
    return true;
  } else {
    return false;
  }
}

/*   MAIN PROGRAM   
OUTPUT:
1,1 -> all good page 1
1,2 -> all good page 2
0,1 -> base exists
0,2 -> error update
0,3 -> basename.length < 2
0,4 -> error pass update
0,5 -> password.length < 5
0,6 -> error update page 2

*/
$data = Array();

$data[0] = check($_POST['data0']);
$data[1] = check($_POST['data1']);
$data[2] = check($_POST['data2']);
$data[3] = check($_POST['data3']);
$data[4] = check($_POST['data4']);
$data[5] = check($_POST['data5']);

if ($data[0] == 1) {
  $r = checkBaseName($data[1],$idb);
  if (!$r) {
    exit("0,1");
  }
  
  if (strlen($data[1]) < 2) {
    exit("0,3");
  } 

  $r = changeBaseName($data[1],$idb);
  if (!$r) {
    exit("0,2");
  }
 
  if (strlen($data[2]) < 2) {
    exit("0,5");
  }
 
  $r = changePass($data[2]);
  if (!$r) {
    exit("0,4");
  }

  exit("1,1");
} else if ($data[0] == 2) {
  $r =  updateBase($data[1],$data[2],$data[3],$data[4],$data[5],$idb);
  if ($r) {
    exit("1,2");
  } else {
    exit("0,6");
  }
}


?>