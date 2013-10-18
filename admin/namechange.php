<?php session_start();
include "../utils.php";

if (!isset($idb) || empty($idb)) {
  exit("0,0");
}

function checkBaseName($name) {
  $r = mysql_query("SELECT * FROM bases WHERE name='$name'");
  $r = mysql_fetch_array($r);

  if (!empty($r['name'])) {
    return false;
  } else {
    return true;
  }
}

function changeBaseName($name,$idb) {
  $r = mysql_query("UPDATE bases SET name='$name' WHERE id='$idb'");
 

  if (!$r) {
    return false;
  } else {
    return true;
  }
}

/*   MAIN PROGRAM   
OUTPUT:
1,0 -> all good
0,2 -> base exists
0,1 -> error update
0,3 -> strlen < 2


*/


$name  = check($_POST['name']);
if (strlen($name) < 2 ) {
  exit("0,3");
}

$check = checkBaseName($name);

if ($check) {
  if (changeBaseName($name,$idb)) {
    exit("1,0");
  } else {
     exit("0,1");
  }
} else {
  exit("0,2");
} 



?>