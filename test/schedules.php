<?php session_start();
include "../utils.php";


function checkArr($arr) {
  $error = false;
  $time1 = 0;
  $time2 = 0;
  for ($i = 0; $i < 9; $i++) {
    foreach ($arr[$i] as &$value) {
      if ( is_array($value) ) { // go thru time attributes
        $time1 = intval($value[0])*60 + intval($value[1]);
        $time2 = intval($value[2])*60 + intval($value[3]);

        if ( $time1 > $time2 ) {
          $error = true;
          break 2;
        }

        $value = (intval($value[0])*60 + intval($value[1])).",".(intval($value[2])*60 + intval($value[3])).",".intval($value[4]).";";
     
      } else {
        $value = intval($value);
      }
    }   
  }


  if ($error) {
    return null;
  }

  $newArray = Array();
  for ($i = 0; $i < 7; $i++) {
    $str = "";
    foreach ($arr[$i] as &$value) {
      $str .= $value;
    }
    $newArray[$i] = $str;
  }
  
  $newArray[7] = $arr[7]; //rooms
  $newArray[8] = $arr[8]; //rooms for delete
  return $newArray;
}

function updateRooms($schedule,$idb) {
  $good = true;

  $existingRooms = mysql_query("SELECT rooms FROM {$idb}_schedule"); 
  if (!$existingRooms) {
    return 0;
  }

  $i = 0;
  $prevRooms = Array();
  While ( $row = mysql_fetch_array($existingRooms) ){
    $prevRooms[$i] = $row['rooms'];
    $i++;
  }

  $newRooms = $schedule[7];

  $update = Array();
  $insert = Array();
  $delete = Array();

  $update = array_intersect($prevRooms,$newRooms);
  $insert = array_diff($newRooms,$prevRooms);
  $delete = $schedule[8];
  
  //UPDATE LOOP
  foreach ($update as &$room ) {
    $table = $idb."_schedule";
    $r = mysql_query("UPDATE `b108859_wordpress`.`$table` SET `d1`='$schedule[0]',`d2`='$schedule[1]',`d3`='$schedule[2]',`d4`='$schedule[3]',`d5`='$schedule[4]',`d6`='$schedule[5]',`d7`='$schedule[6]' WHERE $table.`rooms` = $room");
    if (!$r) {
      $good = false;
    }
  }

  //INSERT LOOP
  foreach ($insert as &$room ) {
    $table = $idb."_schedule";
    $r = mysql_query("INSERT into $table (rooms,d1,d2,d3,d4,d5,d6,d7) VALUES ('$room','$schedule[0]','$schedule[1]','$schedule[2]','$schedule[3]','$schedule[4]','$schedule[5]','$schedule[6]')");
    if (!$r) {
      $good = false;
    }
  }

  if (0) {
    //DELETE LOOP
    foreach ($delete as &$room ) {
      $table = $idb."_schedule";
      $r = mysql_query("DELETE FROM $table WHERE $table.`rooms` = $room");
      if (!$r) {
        $good = false;
      }
    }
  } else {
    //DELETE LOOP
    foreach ($delete as &$room ) {
      $table = $idb."_schedule";
      $r = mysql_query("UPDATE `b108859_wordpress`.`$table` SET `d1`='',`d2`='',`d3`='',`d4`='',`d5`='',`d6`='',`d7`='' WHERE $table.`rooms` = $room");
      if (!$r) {
        $good = false;
      }
    }
  }
  


  

  if ($good) {
    return 1;
  } else {
    return 0;
  }

}


/*   MAIN PROGRAM  */
$data = $_POST['data'];
$arr = json_decode($data);

$newArr = checkArr($arr);

if ($newArr === null) {
  exit("0!0");
}

$update = updateRooms($newArr,$idb);
$jsonObject = json_encode(array_slice($newArr,0,7));

if ($update) {
  exit("1!".$jsonObject);
} else {
  exit("0!0");
} 



?>