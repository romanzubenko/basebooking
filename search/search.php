<?php  session_start(); 
include "utils.php";


/* GET SEARCH INPUTS */
$s = mb_strtolower($_GET['search'],'UTF-8');
$town = mb_strtolower($_GET['town'],'UTF-8');
$station = mb_strtolower($_GET['station'],'UTF-8');

$s = check($s);
$town = check($town);
$station = check($station);
   

if (isset($_GET['t1']) || isset($_GET['t2'])){
  $typecheck = 1;
  $t1 = $_GET['t1'];
  $t2 = $_GET['t2'];
  if ( $t1 == 1 ) {
    $typecheck = 1;
  }
  if ( $t2 == 1 ) {
    $typecheck = 2;
  }
  if ($t1 == 1 && $t2 == 1) {
    $typecheck = 0;
  }
 }

/* GET SEARCH INPUTS END */ 
$bases = getBases();


/*  CACHE SEARCH PROTOTYPE */

$args = new Array();
$args[0] = $s;
$args[1] = $town;
$args[2] = $station;
$args[3] = $typecheck;

if (time() > $_SESSION['searchExp']) {
  $_SESSION['searchArgs'] = $args;
  $_SESSION['search'] = $bases;
  $_SESSION['searchExp'] = time() + 180;
} else if ($_SESSION['searchArgs'] == $args) {
  $htmlBases = allBasesOut($_SESSION['search']);
} else {
  
  if (($_GET['town'] == "" && $_GET['search'] == "" && $_GET['station'] == "") || empty($_GET)) {
    $htmlBases = allBasesOut($bases);
  } else {
    $bases = searchBases($bases,$town,$station,$s,$typecheck);
    $bases = sortBases($bases);
    $htmlBases = allBasesOut($bases);
  }



  $_SESSION['searchArgs'] = $args;
  $_SESSION['search'] = $bases;
  $_SESSION['searchExp'] = time() + 180;
  $htmlBases = allBasesOut($bases);
}

/*  CACHE SEARCH PROTOTYPE */

if (($_GET['town'] == "" && $_GET['search'] == "" && $_GET['station'] == "") || empty($_GET)) {
  $htmlBases = allBasesOut($bases);
} else {
  $bases = searchBases($bases,$town,$station,$s,$typecheck);
  $bases = sortBases($bases);
  $htmlBases = allBasesOut($bases);
}

print($htmlBases);

?>