<?php session_start();
include "../utils.php";

$query = check($_GET['query']);

$query = $idb."_".$query.".jpg";


// check if photo exist
  $r = mysql_query("SELECT * FROM {$idb}_photo WHERE name='$query'");
  $r = mysql_fetch_array($r);
  $name  = $r['name'];
  if (isset($r['name'])){
	$c1 = 1;
  } else {
	$c1 = 0;
  }
  unset($r);
// check if photo exist

// delete photo
  if ($c1){
  	$del  = mysql_query("DELETE FROM {$idb}_photo WHERE name='$query'");
  	$del2 = unlink("../upload/".$query);
  }
// delete photo

// output code
if ($c1 && $del && $del2) {
	$output = -1; 
} else {
	$output = 0;
}
// output code


// if 0 --> smth went wrong
// if -1 --> everything is cool
echo $output;
?>