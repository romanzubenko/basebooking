<?php session_start();

$db = mysql_connect ("78.108.84.245","u108859","YWJcH9CIBDpv");
mysql_set_charset('utf8',$db); 
mysql_select_db("b108859_wordpress",$db);

function check($str) {
  $str = htmlspecialchars($str);
  $str = trim($str);
  $str = escapeshellcmd($str);
  return $str;
}

function clears($str){
  $str=stripslashes($str);
  $str=stripslashes($str);
  return $str;
} 

if (isset($_SESSION['login']) && $_SESSION['login']!="superadmin") {
  $login = $_SESSION['login'];
  $mybases = mysql_query("SELECT bases from users where login='$login'",$db); 
  $mybases = mysql_fetch_array($mybases);
  $mybase = $mybases['bases'];
  $idb = $mybase;
}

function currentTime($idb, &$today){

  $currhour = date("G");
  //apply currtimezone
  $tz=mysql_query("SELECT `timezone` from bases WHERE id='$idb'");
  $tz=mysql_fetch_array($tz);

  $tz=$tz['timezone'];
  $currhour = $currhour - 4 + $tz ;
  
  if ($currhour < 0) { // timezone 0 London
    $currhour = 24 + $currhour;	
    $today = $today-1;
  } else if ($currhour > 24) {
  	$currhour = 24 - $currhour;
  	$today = $today+1;
  }
  
  //apply currtimezone end
  $currentTime=$currhour*60+(int)date("i");
  return $currentTime;	// minutes past midnight
}


function timeDiff($ctime,$cdate,$btime,$bdate){

	$date1Y=($cdate-$cdate%10000)/10000;
	$date1M=($cdate%10000 - $cdate%100)/100;
    $date1D=$cdate%100;
    $date1H  = floor($ctime/60);
    $date1Mi = $ctime - $date1H*60;
 
    
    $date2Y=($bdate-$bdate%10000)/10000;
	  $date2M=($bdate%10000 - $bdate%100)/100;
    $date2D=$bdate%100;
    $date2H  = floor($btime/60);
    $date2Mi = $btime - $date2H*60;
    
    $d1=mktime($date1H,$date1Mi,0,$date1M,$date1D,$date1Y);
    $d2=mktime($date2H,$date2Mi,0,$date2M,$date2D,$date2Y);
    $dateDiff = $d2 - $d1;
    
    $diff['days']    = floor($dateDiff/(60*60*24));
    $diff['hours']   = floor($dateDiff/(60*60));
    $diff['minutes'] = $dateDiff/60- $diff['hours']*60;
	return $diff;
}

function clearBooking($idb,$db){
  $today=10000*date("Y")+100*date("n")+date("j");
  $r=mysql_query("SELECT * FROM {$idb}_booking WHERE past='0' ");
  $i=-1;
While ($row=mysql_fetch_array($r,MYSQL_ASSOC)){
    $i++;
    $datet=explode(".",$row['date']);
    $date=$datet[0]+$datet[1]*100+$datet[2]*10000;
    if ($date<$today) {
      $d=$row['date'];
      $start=$row['start'];
      $end=$row['end'];
      $ro=$row['room'];
      $vkid=$row['vkid'];
      $r1=mysql_query("UPDATE {$idb}_booking SET `past`='1' , `done`='1' WHERE date='$d' AND start='$start' AND room='$ro'");
      $numsChange=mysql_query("SELECT * FROM mus_users WHERE vkid='$vkid'");
      $numsChange=mysql_fetch_array($numsChange);
      $past=$numsChange['past_book_num']+1;
      $curr=$numsChange['curr_book_num']-1;
      $r2=mysql_query("UPDATE mus_users SET `past_book_num`='$past',`curr_book_num`='$curr' WHERE vkid='$vkid'");
      $r3=mysql_query("UPDATE {$vkid}_history SET `past`='1' , `done`='1' WHERE date='$d' AND start='$start' AND room='$ro' AND idb='$idb'");
    }
  }
}

function userClear($vkid){
	$bases = array();
	$b=mysql_query("SELECT idb FROM {$vkid}_history ");
	While ($row = mysql_fetch_array($b)) {
		$idb = $row['idb'];
		if (!array_search($idb,$bases)){
			$bases[] = $idb;
			clearBooking($idb,$db);
		} 
	}
}



function printFooter() {
	echo "
<div id=\"bottom\">
  <table>
    <tr>
      <td>Basebooking 2011</td>
      <td><a href=\"http://www.vk.com/basebooking\"><img src=\"http://www.basebooking.ru/img/vk.png\" / 
      style=\"width:20px;height:20px\"></a></td>
    </tr>
  </table>
</div>";
}

function formatTime($x) {
	$time = "";
	$h = floor($x/60);
	$m = $x - $h*60;
	$h = leadzero($h);
	$m = leadzero($m);
	$time = $h.":".$m;
	return $time;
}

function leadzero($x) {
	if ($x < 10) {
		$x = "0".$x;
	} 
	return $x;
}

function cancelNotify($idb,$room,$start,$end,$date,$vkid) {
	$today = 10000*date("Y")+100*date("n")+date("j");
	$otime = currentTime($idb, $today);
	
	$dateY = ($today-$today%10000)/10000;
	$dateM = ($today%10000 - $today%100)/100;
    $dateD = $today%100;
    
    $dateH  = floor($otime/60);
    $dateMin = $otime - $dateH*60;
    $format = $dateD.".".$dateM.".".$dateY;
    $formatTime = formatTime($dateH).":".$dateMin;
	$r = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_notification` (`seen`,`type`,`vkid`,`room`,`start`,`end`,`date`,`odate`,`otime`) VALUES ('0','2','$vkid','$room','$start','$end','$date','$format','$otime')");
}

function bookingNotify($idb,$room,$start,$end,$date,$vkid) {
	$today = 10000*date("Y")+100*date("n")+date("j");
	$otime = currentTime($idb, $today);
	
	$dateY = ($today-$today%10000)/10000;
	$dateM = ($today%10000 - $today%100)/100;
    $dateD = $today%100;
    
    $dateH  = floor($otime/60);
    $dateMin = $otime - $dateH*60;
    $format = $dateD.".".$dateM.".".$dateY;
    $formatTime = formatTime($dateH).":".$dateMin;
	$r = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_notification` (`seen`,`type`,`vkid`,`room`,`start`,`end`,`date`,`odate`,`otime`) VALUES ('0','1','$vkid','$room','$start','$end','$date','$format','$otime')");

}

 //notification count 
function notificationCount($idb){
	$r = mysql_query("SELECT * FROM {$idb}_notification WHERE `seen`='0'");
	$x = 0;
	while ($row = mysql_fetch_array($r)){
		$x++;
	}
	
	if ($x == 0){
		return "";
	} else {
		return " (".$x.")";
	}
}

function musNotificationCount($vkid){
  $r = mysql_query("SELECT * FROM {$vkid}_notification WHERE `seen`='0'");
  $x = 0;
  while ($row = mysql_fetch_array($r)){
    $x++;
  }
  
  if ($x == 0){
    return "";
  } else {
    return " (".$x.")";
  }
}
 
function getName($vkid) {
	$arr = array();
	$name = mysql_query("SELECT * from mus_users WHERE vkid='$vkid'");
	$name = mysql_fetch_array($name);
	$arr[0] = $name['name'];
	$arr[1] = $name['lastname'];
	return $arr;
}

function newOut($notes) {
	$k = count($notes);
	$str = "";
	$type = "";
	$parity = 0;
	for ($i = 0; $i < $k; $i++){
		if ($notes[$i]['seen'] == 0) {
			$parity++;
			if ($notes[$i]['type'] == 1) {
				$type = "Бронирование";
			} else {
				$type = "Отмена бронирования";
			}
			if ($parity%2 == 0){
				$str = $str."
				<div class=\"booking_even\">
				  <table>
      				<tr>
      				  <td>{$notes[$i]['date']}</td>
      				  <td>{$notes[$i]['start']} - {$notes[$i]['end']}</td>
     				  <td>{$notes[$i]['room']} комната</td>
      				  <td><a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\">{$notes[$i]['name']} {$notes[$i]['lastname']}</a></td>
      				  <td>{$type}</td>
      				<tr>
      		      </table>
				</div>";
			} else {
				$str = $str."
				<div class=\"booking_odd\">
				  <table>
      				<tr>
      				  <td>{$notes[$i]['date']}</td>
      				  <td>{$notes[$i]['start']} - {$notes[$i]['end']}</td>
     				  <td>{$notes[$i]['room']} комната</td>
      				  <td><a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\">{$notes[$i]['name']} {$notes[$i]['lastname']}</a></td>
      				  <td>{$type}</td>
      				<tr>
      		      </table>
				</div>";
				
			}
		}
	}
	return $str;
}

function normalOut($notes,$x,$h) {
    $months = array("января","февраля","марта","апреля","мая","июня","мюля","авгуса","сентября","октября","ноября","декабря");
	$k = count($notes);
    
    if ($h == 1) {
        $h = "забронировал(а)";
        
    } else {
        $h = "отменил(а) бронирование";
    }

        $str = "";
    
	$type = "";
	$parity = 0;
	for ($i = 0; $i < $k; $i++){
		if ($notes[$i]['seen'] == 1 && $notes[$i]['type'] == $x) {
			$parity++;
			if ($parity%2 == 0){
				$str = $str."
				<div class=\"booking_even\">
          <div class=\"note\">
            {$notes[$i]['odate']} в {$notes[$i]['otime']} <a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\"> {$notes[$i]['name']} 
            {$notes[$i]['lastname']}</a> $h
            {$notes[$i]['room']} комнату с {$notes[$i]['start']} до {$notes[$i]['end']} на {$notes[$i]['date']}
            </div>
				</div>";
			} else {
				$str = $str."
				<div class=\"booking_odd\">
				  <div class=\"note\">
                {$notes[$i]['odate']} в {$notes[$i]['otime']} <a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\"> {$notes[$i]['name']} 
                {$notes[$i]['lastname']}</a> $h
                {$notes[$i]['room']} комнату с {$notes[$i]['start']} до {$notes[$i]['end']} на {$notes[$i]['date']}
                </div>
				</div>";
				
			}
		}
	}
	return $str;
}

function bubbleNotifications($notes) {
	
	return $notes;
}

function rusHour ($x) {
	$str = "";
	if ($x % 5 == 0 || $x%10 == 6 || $x%10 == 7 || $x%10 == 8 || $x%10 == 9) {
		$str = "часов";
	} else if ($x%10 == 1) {
		$str = "час";
	} else if ($x % 10 == 2 || $x % 10 == 3 || $x % 10 == 4) {
		$str = "часа";
	}
	return $str;
}

function printHeader(){


  echo "<div id=\"top\">";
  if (isset($_SESSION['login']) && $_SESSION['login']!="superadmin") {
    
    echo "
      <div id=\"admin_panel\">
        <a href=\"http://www.basebooking.ru/admin\">Мой кабинет</a>&nbsp&nbsp&nbsp<a href=\"http://www.basebooking.ru/exit.php\">Выйти</a>
      </div>";
  } else if (isset($_SESSION['vkid'])) {
    echo "
      <div id=\"admin_panel\">
        <a href=\"http://www.basebooking.ru/musician\">Мой кабинет</a>&nbsp&nbsp&nbsp<a href=\"http://www.basebooking.ru/exit.php\">Выйти</a>
      </div>";
    
    
  } else if ($_SESSION['login']=="superadmin") {
     echo "
      <div id=\"admin_panel\">
        <a href=\"http://www.basebooking.ru/superadmin\">Мой кабинет</a>&nbsp&nbsp&nbsp<a href=\"http://www.basebooking.ru/exit.php\">Выйти</a>
      </div>";
    
  } else {
      echo "
        <div id=\"enter\">
          <a href=\"http://www.basebooking.ru/enter\"> Вход для администраторов и музыкантов</a>
        </div>";
  }
    
    echo"
    </div>
    <div id=\"i4\">
    <a href=\"http://www.basebooking.ru\" style=\"width:250px;height:55px;display:block;float:left;\"></a>
      <div id=\"topmenu\">
        <ul> 
          <li><a href=\"http://www.basebooking.ru/search.php\">Поиск</a></li>   
          <li><a href=\"http://www.basebooking.ru/bands\">Музыкантам</a></li> 
          <li><a href=\"http://www.basebooking.ru/partners\">Базам и Студиям</a></li>
          <li><a href=\"http://www.basebooking.ru/about\">О проекте</a></li> 
          <li ><form action=\"http://www.basebooking.ru/search.php\"></form></li>
        </ul>
      </div><!topmenu>
      
      </div><div class=\"topmenu\"></div>";
}


function schCheck($idb,$room,$date,&$today,$start,$end) {
      
    $scheduleLoad=mysql_query("SELECT * FROM {$idb}_schedule WHERE`rooms`='$room'");
    $scheduleLoad=mysql_fetch_array($scheduleLoad);
    
    $datetemp=explode(".",$date);
    $number=$datetemp[2]*10000+$datetemp[1]*100+$datetemp[0];
    $dateY=($number-$number%10000)/10000;
    $dateM=($number%10000 - $number%100)/100;
    $dateD=$number%100;
    
    $cdate=10000*date("Y")+100*date("n")+date("j");
    
    
    $ctime = currentTime($idb,$today);
    $btime = $start;
    $diff=timeDiff($ctime,$cdate,$btime,$number);
      $h = mktime(0, 0, 0, $dateM,$dateD,$dateY);
      $weekday = date("w", $h);
      
      if ($weekday==0){
        $weekday=7;
      } 
      
      $newarr = explode (";",$scheduleLoad[$weekday]);
      $schResult=false;
      for ($i = 0; $i < count($newarr); $i++){
        $temptime = explode(",",$newarr[$i]);
        if ($temptime[0]==$start && $temptime[1] == $end){   
          $schResult=true;
        }
      }
      
      if ($schResult){
        $scheduleCheck=1;
      } else {
        $scheduleCheck=0;
      }      
     //check schedule
     
  return $scheduleCheck;   
}

function bookingNotExist($idb,$start,$end,$date,$room) {

  $r=mysql_query("SELECT * FROM {$idb}_booking WHERE start='$start' AND end='$end' AND date='$date'");
  $c = 1;
     
  while($row=mysql_fetch_array($r)){
    if ($row['start']==$start and $row['end']==$end and $row['date']==$date and $row['room']==$room){
      $c = 0;
    } 
  }

  return $c;
}

function pastDeadline ($date, $start) {
  $bookdate = explode(".",$date);
  $bookdate = $bookdate[2]*10000 + $bookdate[1]*100 + $bookdate[0];
  $today = 10000*date("Y")+100*date("n")+date("j");
  $ctime = currentTime($idb, &$today);
  $diff = array();
  $diff = timeDiff($ctime,$today,$start,$bookdate);
  unset($bookdate);
  $hours = $diff['hours'];

  if ($hours < 0) {
    return -1;
  }

  $r = mysql_query("SELECT deadline FROM bases WHERE id='$idb'");       //get deadline
  $r = mysql_fetch_array($r);
  $deadline = $r['deadline'];

  if ($hours <= $deadline) {
    $deadlinefail = 1;
  } else {
    $deadlinefail = 0;
  }

  return $deadlinefail;
}

function book($idb,$date,$start,$end,$room,$price,$name,$lastname,$vkid,$phone,$band,$admin) {
  if ($admin) {
  $r1 = mysql_query("INSERT into {$idb}_booking (vkid,start,end,band,date,name,lastname,past,room,phone,price,admin)VALUES ('0','$start','$end','$band','$date','$name','0','0','$room','$phone','$price','1') ");
  } else {
    $r1 = mysql_query("INSERT into {$idb}_booking (vkid,start,end,band,date,name,lastname,past,room,phone,price,admin)VALUES ('$vkid','$start','$end','$band','$date','$name','$lastname','0','$room','$phone','$price','0') ");
    bookingNotify($idb,$room,$start,$end,$date,$vkid);
   
    $user = mysql_query("SELECT * FROM mus_users WHERE vkid='$vkid'");
    $user = mysql_fetch_array($user);
    $current = $user['curr_book_num'];
    $current++;
    
    $r2 = mysql_query("UPDATE `b108859_wordpress`.`mus_users` SET `curr_book_num`='$current' WHERE vkid='$vkid'");
    $r3 = mysql_query("INSERT into {$vkid}_history (idb,start,end,date,tel,done,room) VALUES ('$idb','$start','$end','$date','$phone','0','$room')");

    $lists=mysql_query("SELECT * FROM {$idb}_list WHERE `vkid`='$vkid'",$db);
    $lists=mysql_fetch_array($lists);
       
    if (empty($lists['vkid'])) {

       $fullname = $name." ".$lastname; 
       $addtolist = mysql_query("INSERT into {$idb}_list (vkid, name, bookings) VALUES ('$vkid','$fullname',1)");
       } else {
        $listtable =$idb."_list";
        $updatelist=mysql_query("UPDATE `b108859_wordpress`.`$listtable` SET `bookings`=`bookings`+1 WHERE vkid='$vkid'",$db);   
    }

    unset($_SESSION['login']);
    unset($_SESSION['pid']);
    unset($_SESSION['type']);
    $_SESSION['vkid'] = $vkid;

  }     
       $formatted = formatTime($timestart)." - ".formatTime($timeend);
       $errorbooking="Время $formatted для комнаты $room успешно забронировано на $date! <br /> <a href=\"http://www.basebooking.ru/musician\">Перейти в мой кабинет</a><br />";
       

}

function getBaseNotifications($idb) {
  $notifications=mysql_query("SELECT * FROM {$idb}_notification");
  $notes = array();
  $i = 0;
  while ($row = mysql_fetch_array($notifications)){
    $notes[$i]['type'] = $row ['type'];
    $notes[$i]['vkid'] = $row['vkid'];
    $notes[$i]['seen'] = $row['seen'];
    $notes[$i]['room'] = $row['room'];
    $notes[$i]['start'] = formatTime($row['start']);
    $notes[$i]['end'] = formatTime($row['end']);
    $notes[$i]['date'] = $row['date'];
    $notes[$i]['odate'] = $row['odate'];
    $notes[$i]['otime'] = formatTime($row['otime']);
    $arr = getName($notes[$i]['vkid']);
    $notes[$i]['name'] = $arr[0];
    $notes[$i]['lastname'] = $arr[1];
    unset($arr);
    $i++;
    
  }
  return $notes;
}

function getUserNotifications($vkid) {
  $notifications=mysql_query("SELECT * FROM {$vkid}_notification");
  $notes = array();
  $i = 0;
  while ($row = mysql_fetch_array($notifications)){
    $notes[$i]['type'] = $row ['type'];
    $notes[$i]['idb'] = $row['vkid'];
    $notes[$i]['seen'] = $row['seen'];
    $notes[$i]['room'] = $row['room'];
    $notes[$i]['start'] = formatTime($row['start']);
    $notes[$i]['end'] = formatTime($row['end']);
    $notes[$i]['date'] = $row['date'];
    $i++;
  }
  return $notes;
}

function printUserNotifications($notes) {
  echo "<div class=\"elemento\" style=\"width:776px\"><div id=\"el_1\" style=\"width:776px\">";

  for ($i = 0; $i < count($notes); $i++) {
    echo "
      <div class=\"booking_even\">
        <table>
        <tr>
          <td>{$notes[$i]['idb']}</td>
          <td>{$notes[$i]['type']}</td>
          <td>{$notes[$i]['date']}</td>
          <td>{$notes[$i]['start']} - {$notes[$i]['end']}</td>
        </tr>
        </table>
      </div>      
    ";
  }


  echo"<div/></div>";
}
?>