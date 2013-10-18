
<?php session_start();
include "../utils.php";

if (!isset($idb) || empty($idb)) {
  exit("0,0");
}

function checkPassword($p) {
  $login = $_SESSION['login'];
  $old = mysql_query("SELECT * FROM users WHERE login='$login'");

  if (!$old) {
    return 1;
  }

  $old = mysql_fetch_array($old);

  if ($old['password'] == md5($p)) {
    return 2;
  } else {
    return 3;
  }

}

function refreshPass($p) {
  $login = $_SESSION['login'];
  $p = md5($p);

  $r = mysql_query("UPDATE users SET `password`='$p' WHERE login='$login'");

  if ($r) {
    return true;
  } else {
    return false;
  }
 
}

/*   MAIN PROGRAM   */

$pOld  = check($_POST['p1']);
$pNew  = check($_POST['p2']);

$r1 = checkPassword($pOld);

if ($r1 === 1) {
  exit("0,1");
} else if ( $r1 === 3) {
  exit("0,2");
}

$r2 = refreshPass($pNew);


if ($r2) {
  exit("1,1");
} else {
  exit("0,1");
}



?>