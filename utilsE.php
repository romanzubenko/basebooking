<?php session_start();


/*        DOCUMENTATION: 

Function list: 

*********GENERAL UTILS:
printFooter()
printHeader()
check($str)
clears($str)
currentTime($idb, &$today)
formatTime($x)
leadzero($x)
getName($vkid)
getBaseName($idb)
rusHour($x)
dateFormat($date,$time)
makeJavaScriptArray( $phpArray )
rand_str($length = 10, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
TrueEmail($email)
getUsers()
formatLink($str)  - formats urls for future use in links
*********

*********BOOKING:
timeDiff($ctime,$cdate,$btime,$bdate)
checkBooking($idb)
bookingType($idb)
notIntersect($idb,$vkid,$start,$end,$date)
changeCurrent($idb,$vkid,$x)
createUser($vkid,$name,$lastname,$phone)
userExist($vkid)
checkBL($vkid,$idb)
checkBookingSettings($vkid,$idb)
addToList($idb,$vkid,$name,$lastname)
hashcheck($hash,$vkid)
book($idb,$vkid,$date,$start,$end,$room,$admin,$band,$add,$phone,$name,$lastname,$hash,$price)
errorMessage($x) 
schCheck($idb,$room,$date,&$today,$start,$end)
bookingNotExist($idb,$start,$end,$date,$room)
pastDeadline ($date, $start, $idb)
*********

*********USER:
userClear($vkid)
createUser($vkid,$name,$lastname,$phone)
*********

*********BASE(S):
clearBooking($idb,$db)
getStatus ($x) // zayavka
listOut($idb)
blOut($idb)
getBases()
baseOut($base) // search out
getCoverPhoto($idb)
allBasesOut($bases)
searchBases($search,$town,$station,$s,$typecheck)  // $search stands for bases
getBaseName($idb)

createBase($name,$type,$komn,$descript,$town,$station,$adress,$pid,$vk,$phone,$website)
deleteBase($idb)

getSchedules($idb)
outputSchedules($schedules)

printRoomsNames($idb)
*********

*********USERS && MUSUSERS:
createUser($vkid,$name,$lastname,$phone) // creates Mus user
deleteUser(id) // deletes admin user
*********

*********NOTIFICATIONS:
cancelNotify($idb,$room,$start,$end,$date,$vkid)
bookingNotify($idb,$room,$start,$end,$date,$vkid)
musNotificationCount($vkid)
notificationCount($idb)
newOut($notes)
normalOut($notes,$x,$h)
bubbleNotifications($notes)
getBaseNotifications($idb)
getUserNotifications($vkid) 
userNotificationsOut($vkid,$new,$notes)    // if $new then new out else old out
*********

*/


$db = mysql_connect ("78.108.84.245","u108859","YWJcH9CIBDpv",false);
mysql_set_charset('utf8',$db); 
mysql_select_db("b108859_wordpress",$db);

function check($str) {
  $str = htmlspecialchars($str);
  $str = trim($str);
  return $str;
}

function clears($str){
  $str = stripslashes($str);
  $str = stripslashes($str);
  return $str;
} 

if (isset($_SESSION['login']) && $_SESSION['login']!="superadmin") {
  $login = $_SESSION['login'];
  $mybases = mysql_query("SELECT bases from users where login='$login'",$db); 
  $mybases = mysql_fetch_array($mybases);
  $mybase = $mybases['bases'];
  $idb = $mybase;
}

function searchBases($search,$town,$station,$s,$typecheck) { // $search stands for bases
  if ( !empty($town) ) {
    $obj['town']=explode(" ", $town);} else {$obj['town'][0]="";
  }
  if ( !empty($station) ) { 
    $obj['station']=explode(" ", $station);} else {$obj['station'][0]="";
  }
  if ( !empty($s) ){ 
    $obj['search']=explode(" ", $s);} else {$obj['search'][0]="";
  }

  for ($j=0, $limit = count($search); $j< $limit; $j++) {
    
    $t = mb_strtolower($search[$j]['town'],'UTF-8');
    $d = mb_strtolower($search[$j]['descript'],'UTF-8');
    $n = mb_strtolower($search[$j]['name'],'UTF-8');
    $ad = mb_strtolower($search[$j]['adress'],'UTF-8');
    $st = mb_strtolower($search[$j]['station'],'UTF-8');  
     //1
    if (isset($obj['town'][0]) and !empty($obj['town'][0])) {
      $i1=0;
      while (isset($obj['town'][$i1]) and !empty($obj['town'][$i1])){
        if(isset($t) and !empty($t)){
          if (stristr($t,$obj['town'][$i1])){
            $search[$j]['prior']=$search[$j]['prior']+2;
          }
        }
        $i1++;
      }
    }
   //2
    if (isset($obj['station'][0]) and !empty($obj['station'][0])) {
      $i1=0;
      while (isset($obj['station'][$i1]) and !empty($obj['station'][$i1])){
        if(isset($st) and !empty($st)){
          if (stristr($st,$obj['station'][$i1])){
            $search[$j]['prior'] = $search[$j]['prior']+2;
          }
        }$i1++;
      }
    }
   //3
    if (isset($obj['search'][0]) and !empty($obj['search'][0])) {
      $i1=0;
      while (isset($obj['search'][$i1]) and !empty($obj['search'][$i1])){
        if(isset($n) and !empty($n)){ 
          if (stristr($n,$obj['search'][$i1])){
            $search[$j]['prior']=$search[$j]['prior']+3;
          }
        }
        $i1++;
      }
    }
   //4
    if (isset($obj['search'][0]) and !empty($obj['search'][0])) {
      $i1=0;
      while (isset($obj['search'][$i1]) and !empty($obj['search'][$i1])){
        if(isset($d) and !empty($d)){
          if (stristr($d,$obj['search'][$i1])){
            $search[$j]['prior']=$search[$j]['prior']+1;
          }
        }$i1++;
      }
    }
   //5
    if (isset($obj['search'][0]) and !empty($obj['search'][0])) {
      $i1=0;
      while (isset($obj['search'][$i1]) and !empty($obj['search'][$i1])){
        if(isset($ad) and !empty($ad)){
          if (stristr($ad,$obj['search'][$i1])){
            $search[$j]['prior']=$search[$j]['prior']+1;
          }
        }
        $i1++;
      }
    }
    //6
    if (isset($obj['search'][0]) and !empty($obj['search'][0])) {
      $i1=0;
      while (isset($obj['search'][$i1]) and !empty($obj['search'][$i1])){
        if(isset($ad) and !empty($ad)){
          if (stristr($t,$obj['search'][$i1])){
            $search[$j]['prior']=$search[$j]['prior']+2;
          }
        }
        $i1++;
      }
    }

    //7
    if (isset($obj['search'][0]) and !empty($obj['search'][0])) {
      $i1=0;
      while (isset($obj['search'][$i1]) and !empty($obj['search'][$i1])){
        if(isset($ad) and !empty($ad)){
          if (stristr($st,$obj['search'][$i1])){
            $search[$j]['prior']=$search[$j]['prior']+1;
          }
        }
        $i1++;
      }
    }

    //8
    if ($typecheck==1) {
      if ($typecheck1==1 and $search[$j]['type']==2) {
        $search[$j]['prior']=0;
      }
      if ($typecheck1==2 and $search[$j]['type']==1) { 
        $search[$j]['prior']=0;
      }
    }
  }

  return $search;
}

function sortBases($bases) {
  $per=false;
  while ($per === false){
    $per = true;
    for ($i=0, $basecount = count($bases); $i < $basecount; $i++) {
      if ($bases[$i]['prior'] < $bases[$i+1]['prior']) {
        $temp = $bases[$i];
        $bases[$i] = $bases[$i+1];
        $bases[$i+1] = $temp;
        $per=false;
      }
    }
  }

  return $bases;
}

function getUsers() {
  $users = Array();
  $r =  mysql_query("SELECT * FROM users");
  if (!$r) {
    return false;
  }

  $i = 0;
  while ($row = mysql_fetch_array($r)) {
    $users[$i] = $row;
    $i++; 
  }
  return $users;
}
function usersOut() {
  $users = getUsers();
  $str = "";
  $limit = count($users);

  for ($i = 2; $i < $limit; $i++) {
    $name = getBaseName($users[$i]['bases']);
    $userId = $users[$i]['id'];
    $l = $users[$i]['login'];
    $n = $i - 1;
    if ($users[$i]['id'] != 29 || $users[$i]['id'] != 30) {
       $str .= "<div class=\"booking_odd\">$n id Базы - ".$users[$i]['bases']."&nbsp&nbsp&nbspБаза <a href=\"http://www.basebooking.ru/base.php?name=".$name."\">".$name."</a>, аккаунт - $l &nbsp&nbsp&nbsp <a href=\"http://www.basebooking.ru/superadmin/deleteAccount.php?user=".$userId."\">Удалить аккаунт</a></div>";
  
    }
   }
  return $str;
}

function getCoverPhoto($idb) { //returns photo filename
  $photo ="";
  $p = mysql_query("SELECT * FROM {$idb}_photo ");
  if (!$p) {
    return false;
  }
  $photo = mysql_fetch_array($p);
  $photo = $photo['name'];

  return $photo;
}

function getBases() {
  $bases = Array();
  $r = mysql_query("SELECT * from bases ORDER BY id");
  $i = 0;

  while ( $row = mysql_fetch_array($r,MYSQL_ASSOC)) {
    $bases[$i] = $row;
    $bases[$i]['prior'] = 0;
    $i++;
  }

  return $bases;
}


function baseOut($base) {
  $html = "";
  $photo = getCoverPhoto($base['id']);

  if ($photo == "") { 
    $photoOut = "<div class=\"ph\"></div>";
  } else {
    $photoOut = "<div class=\"photo\"><img src=\"http://www.basebooking.ru/upload/$photo\"></div>";
  }

  $base['town'] = stripslashes($base['town']);
  $base['town'] = stripslashes($base['town']);
  $base['station'] = stripslashes($base['station']);
  $base['adress'] = stripslashes($base['adress']);
  $base['adress'] = stripslashes($base['adress']);

  if (strlen($base['adress']) >= 120) {
    $base['adress'] = mb_strcut($base['adress'], 0, 117,'UTF-8')."...";
  }

  if ($base['type']==1) {
    $base['type']="Репетиционная база";
  }
  if ($base['type']==2) {
   $base['type']="Студия";
  }
  if ($base['type']==3) {
    $base['type']="Репетиционная база и Студия";
  }

 $html = $html."
    <div class=\"base\">$photoOut 
    <table>
      <a href=\"http://www.basebooking.ru/base/{$base['name']}\"> {$base['name']}</a>&nbsp&nbsp&nbsp<span>{$base['type']}</span>
      <div class=\"space\"></div>
      <tr><td>Город:</td><td>{$base['town']}<td><tr>
      <tr><td>Метро:</td><td>{$base['station']}<td><tr>
      <tr><td>Адрес:</td><td>{$base['adress']}<td><tr>
    </table>
    </div>
  ";
  
  return $html;
}

function allBasesOut($bases) {
  $html = "";
  for ($i = 0, $limit = count($bases); $i < $limit; $i++) {
    $html = $html.baseOut($bases[$i]);
  }


  if ( $html === "" ){
    $html = "<div class=\"errwrap\"><div class=\"err\"><span>По вашему запросу ничего не найдено<span></div></div>";
  }
  return $html;
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

	$date1Y = ($cdate-$cdate%10000)/10000;
	$date1M = ($cdate%10000 - $cdate%100)/100;
  $date1D = $cdate%100;
  $date1H = floor($ctime/60);
  $date1Mi = $ctime - $date1H*60;
    
  $date2Y = ($bdate-$bdate%10000)/10000;
	$date2M = ($bdate%10000 - $bdate%100)/100;
  $date2D = $bdate%100;
  $date2H  = floor($btime/60);
  $date2Mi = $btime - $date2H*60;
    
  $d1 = mktime($date1H,$date1Mi,0,$date1M,$date1D,$date1Y);
  $d2 = mktime($date2H,$date2Mi,0,$date2M,$date2D,$date2Y);
  $dateDiff = $d2 - $d1;
    
  $diff['days']    = floor($dateDiff/(60*60*24));
  $diff['hours']   = floor($dateDiff/(60*60));
  $diff['minutes'] = $dateDiff/60- $diff['hours']*60;
	return $diff;
}

function clearBooking($idb){
  $today = 10000*date("Y")+100*date("n")+date("j");
  $r = mysql_query("SELECT * FROM {$idb}_booking WHERE past='0' ");
  $i = -1;
  if (!$r) {
    return false;
  }

While ($row = mysql_fetch_array($r,MYSQL_ASSOC)){
    $i++;
    $datet = explode(".",$row['date']);
    $date = $datet[0]+$datet[1]*100+$datet[2]*10000;
    if ($date < $today) {
      $d = $row['date'];
      $start = $row['start'];
      $end = $row['end'];
      $ro = $row['room'];
      $vkid = $row['vkid'];
      
      $r1 = mysql_query("UPDATE {$idb}_booking SET `past`='1' , `done`='1' WHERE date='$d' AND start='$start' AND room='$ro'");
      changeCurrent($idb,$vkid,-1);
      changePast($idb,$vkid,1);

    }
  }
}

function userClear($vkid){
	$bases = array();
	$b = mysql_query("SELECT idb FROM {$vkid}_history ");
	While ($row = mysql_fetch_array($b)) {
		$idb = $row['idb'];
		if (!array_search($idb,$bases)){
			$bases[] = $idb;
			clearBooking($idb);
		} 
	}
}



function printFooter() {
	echo "
<div id=\"bottom\">
  <table>
    <tr>
      <td>Basebooking 2011 - 2012</td>
      <td><a href=\"http://www.vk.com/basebooking\"><img src=\"http://www.basebooking.ru/img/vk.png\" / 
      style=\"width:18px;height:18px\"></a></td>
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

  $r1 = mysql_query("SELECT name FROM {idb}_equip WHERE id='$room'");
  
  emailNotify($vkid,$start,$end,$room,$date,$idb,2);




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


  emailNotify($vkid,$start,$end,$room,$date,$idb,1);
}

/*     TYPES :
  1  -> not accept
  2  -> cancel booking
  3  -> accept booking
  4  -> not come
  5  -> BL
*/
function userNotify($type,$idb,$room,$start,$end,$date,$vkid) {
  $today = 10000*date("Y")+100*date("n")+date("j");
  $otime = currentTime($idb, $today);
  
  $dateY = ($today-$today%10000)/10000;
  $dateM = ($today%10000 - $today%100)/100;
  $dateD = $today%100;
    
  $dateH  = floor($otime/60);
  $dateMin = $otime - $dateH*60;
  $format = $dateD.".".$dateM.".".$dateY;
  
  $r = mysql_query("INSERT INTO `b108859_wordpress`.`{$vkid}_notification` (`seen`,`type`,`idb`,`room`,`start`,`end`,`date`,`odate`,`otime`) VALUES ('0','$type','$idb','$room','$start','$end','$date','$format','$otime')");

}

 //notification count 
function notificationCount($idb){
	$r = mysql_query("SELECT * FROM {$idb}_notification WHERE `seen`='0'");
	$x = 0;
	while ($row = mysql_fetch_array($r)){
		$x++;
	}
	

	return $x;
}

function musNotificationCount($vkid){
  $r = mysql_query("SELECT * FROM {$vkid}_notification WHERE `seen`='0'");
  $x = 0;

  while ($row = mysql_fetch_array($r)){
    $x++;
  }
  
 return $x;
}

function notesCountOut($x) {
  if ($x == 0) {
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

function getBaseName($idb) {
  $temp = mysql_query("SELECT name FROM bases where id='$idb'");
  if (!$temp) {
    return "";
  }
  $temp = mysql_fetch_array($temp);
  $name = $temp['name'];
  return $name;
}


function userNotificationsOut($vkid,$new,$notes) {
  $k = count($notes);
  if ($new) {
    $seen = 0;
  } else {
    $seen = 1;
  }
  $str = "";
  $time = "";
  $type = "";
  $parity = 0;

  for ($i = 0; $i < $k; $i++){
    if ($notes[$i]['seen'] == $seen) {
      $parity++;
      
      if ($notes[$i]['type'] == 1) {
        $h = "не одобрила заявку на комнату ".$notes[$i]['room']." с ".$notes[$i]['start']." до ".$notes[$i]['end']." на ".$notes[$i]['date'];
      } else if ($notes[$i]['type'] == 2) {
        $h = "отменил(а) бронирование ".$notes[$i]['room']." комнаты с ".$notes[$i]['start']." до ".$notes[$i]['end']." на ".$notes[$i]['date'];
      } else if ($notes[$i]['type'] == 3) {
        $h = "одобрила заявку на комнату  ".$notes[$i]['room']." с ".$notes[$i]['start']." до ".$notes[$i]['end']." на ".$notes[$i]['date'];
      } else if ($notes[$i]['type'] == 4) {
        $h = "отметила, что вы не пришли на репетицию в ".$notes[$i]['room']." комнате с ".$notes[$i]['start']." до ".$notes[$i]['end']." на ".$notes[$i]['date'];
      } else if ($notes[$i]['type'] == 5) {
        $h = "добавила Вас в черный список";
      } 


      $timestamp = dateFormat($notes[$i]['odate'],$notes[$i]['otime']);
      $notes[$i]['name'] = clears($notes[$i]['name']);
      if ($parity %2 == 0){
        $str = $str."
        <div class=\"booking_even\">
          <div class=\"timestamp\"> $timestamp</div>
          <div class=\"note\">
            <a href=\"http://http://www.basebooking.ru/base.php?name={$notes[$i]['name']}\"> {$notes[$i]['name']} 
            </a> $h  
          </div>
        </div>";
      } else {
        $str = $str."
        <div class=\"booking_odd\">
        <div class=\"timestamp\"> $timestamp</div>
          <div class=\"note\">
            <a href=\"http://http://www.basebooking.ru/base.php?name={$notes[$i]['name']}\"> {$notes[$i]['name']} 
            </a> $h
          </div>
        </div>";
        
      }
    }
  }
  return $str;
}


function newOut($notes) {
  $k = count($notes);

  $str = "";
  $time = "";
  $type = "";
  $parity = 0;

  for ($i = 0; $i < $k; $i++) {
    if ($notes[$i]['seen'] == 0) {
      $parity++;
      
      if ($notes[$i]['type'] == 1) {
        $h = "забронировал(а) ".$notes[$i]['room']." комнату";
        
      } else {
        $h = "отменил(а) бронирование ".$notes[$i]['room']." комнаты" ;
      }

      $notes[$i]['name'] = clears($notes[$i]['name']);
      $notes[$i]['lastname'] = clears($notes[$i]['lastname']);
      $timestamp = dateFormat($notes[$i]['odate'],$notes[$i]['otime']);
      if ($parity %2 == 0){
        $str = $str."
        <div class=\"booking_even\">
          <div class=\"timestamp\"> $timestamp</div>
          <div class=\"note\">
           <a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\"> {$notes[$i]['name']} 
            {$notes[$i]['lastname']}</a> $h
            с {$notes[$i]['start']} до {$notes[$i]['end']} на {$notes[$i]['date']}
            </div>
        </div>";
      } else {
        $str = $str."
        <div class=\"booking_odd\">
        <div class=\"timestamp\"> $timestamp</div>
          <div class=\"note\">
           <a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\"> {$notes[$i]['name']} 
                {$notes[$i]['lastname']}</a> $h
                с {$notes[$i]['start']} до {$notes[$i]['end']} на {$notes[$i]['date']}
                </div>
        </div>";
        
      }
    }
  }
  return $str;
}

function normalOut($notes,$x,$h) {
   
	$k = count($notes);
    
    if ($h == 1) {
        $h = "забронировал(а)";
        
    } else {
        $h = "отменил(а) бронирование";
    }

  $str = "";
  $time = "";
	$type = "";
	$parity = 0;

	for ($i = 0; $i < $k; $i++){
		if ($notes[$i]['seen'] == 1 && $notes[$i]['type'] == $x) {
			$parity++;
      
      $timestamp = dateFormat($notes[$i]['odate'],$notes[$i]['otime']);
			if ($parity%2 == 0){
				$str = $str."
				<div class=\"booking_even\">
          <div class=\"timestamp\"> $timestamp</div>
          <div class=\"note\">
           <a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\"> {$notes[$i]['name']} 
            {$notes[$i]['lastname']}</a> $h
            {$notes[$i]['room']} комнату с {$notes[$i]['start']} до {$notes[$i]['end']} на {$notes[$i]['date']}
            </div>
				</div>";
			} else {
				$str = $str."
				<div class=\"booking_odd\">
        <div class=\"timestamp\"> $timestamp</div>
				  <div class=\"note\">
           <a href=\"http://www.vkontakte.ru/id{$notes[$i]['vkid']}\"> {$notes[$i]['name']} 
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
$adminPanel = "";

  echo "
  <script src=\"http://www.basebooking.ru/js/notify.js\"></script>
  "; 
   if (isset($_SESSION['login']) && $_SESSION['login']!="superadmin") {
    $adminPanel .="
      <div id=\"admin_panel\">
        <a href=\"http://www.basebooking.ru/admin\">My Page </a>
        <a href=\"http://www.basebooking.ru/exit.php\">Exit</a>
      </div>";
      //
  } else if (isset($_SESSION['vkid'])) {
    $adminPanel .="
      <div id=\"admin_panel\">
        <a href=\"http://www.basebooking.ru/musician\">My Page </a>
        <a href=\"http://www.basebooking.ru/exit.php\">Exit</a>
      </div>";
    
  } else if ($_SESSION['login']=="superadmin") {
     $adminPanel .="
      <div id=\"admin_panel\">
        <a href=\"http://www.basebooking.ru/superadmin\">My Page </a>
        <a href=\"http://www.basebooking.ru/exit.php\">Exit</a>
      </div>";
  } else {
      $adminPanel .="
        <div id=\"admin_panel\" style=\"width:53px;\">
          <a href=\"http://www.basebooking.ru/enter\" style=\"width:53px;border-right: none \"> Login</a>
        </div>";
  }
    
    echo "
    <div id=\"top\">
      <div id=\"topmenu\">
      ".$adminPanel."
      <a href=\"http://www.basebooking.ru\"><div id=\"logo\"></div></a>
        <ul> 
          <li><a href=\"http://www.basebooking.ru/search.php\">Search</a></li>   
          <li><a href=\"http://www.basebooking.ru/bands\">Musicians</a></li> 
          <li><a href=\"http://www.basebooking.ru/partners\">Studios</a></li>
          <li><a href=\"http://www.basebooking.ru/about\">About</a></li> 
        </ul>
      </div><!topmenu>
    
  </div>";
}


function schCheck($idb,$room,$date,&$today,$start,$end) {

    $scheduleLoad = mysql_query("SELECT * FROM {$idb}_schedule WHERE rooms='$room'");

    if (!$scheduleLoad) {
      return 0;
    }

    $scheduleLoad = mysql_fetch_array($scheduleLoad);
    
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

  $r = mysql_query("SELECT * FROM {$idb}_booking WHERE start='$start' AND end='$end' AND date='$date'");
  $c = 1;
     
  while($row = mysql_fetch_array($r)) {
    if ($row['start'] == $start && $row['end']==$end && $row['date']==$date && $row['room']==$room){
      $c = 0;
    } 
  }

  return $c;
}

function pastDeadline($date, $start,$idb) {
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
    $deadlinefail = true;
  } else {
    $deadlinefail = false;
  }

  return $deadlinefail;
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
    $notes[$i]['idb'] = $row['idb'];
    $notes[$i]['seen'] = $row['seen'];
    $notes[$i]['room'] = $row['room'];
    $notes[$i]['start'] = formatTime($row['start']);
    $notes[$i]['end'] = formatTime($row['end']);
    $notes[$i]['date'] = $row['date'];
    $notes[$i]['odate'] = $row['odate'];
    $notes[$i]['otime'] = $row['otime'];
    $notes[$i]['name'] = getBaseName($notes[$i]['idb']);

    $i++;
  }
  return $notes;
}



function getStatus ($x) {
  if ($x == 1) {
    return "<span class=\"status1\">Заявка<br />одобрена</span>";
  } else if ($x == 0) {
    return "<span class=\"status\">Заявка на<br />рассмотрении...</span>";
  }

}

function printLinks() {
  print("");
}


/*  BOOKING FUNCTIONS */




// returns  1 if booking is turned on
// returns -1 if booking is not turned on
function checkBooking($idb) {
  $checkbooking=mysql_query("SELECT booking FROM bases WHERE id='$idb'");
  $checkbooking=mysql_fetch_array($checkbooking);
  $checkbooking=$checkbooking['booking']; 
  if ($checkbooking != 1) {
    return 0; 
  } else {
    return 1;
  }
}

// returns 1  if acceptance is not needed
// returns 2  if acceptance is     needed
// returns -1 if smth went wrong 
function bookingType($idb) {
  $r = mysql_query("SELECT accept FROM bases WHERE id='$idb'");
  $accept = mysql_fetch_array($r);
  $accept = $accept['accept'];

  if (!$r) {
    return 0; 
  }

  if ($accept == 0) {
    return 1;
  } else if ($accept == 1) {
    return 2;
  }
}

function notIntersect($idb,$vkid,$start,$end,$date) {
  $interceptionInter = mysql_query("SELECT * FROM {$vkid}_history WHERE (('$start' >= `start` AND '$start' < `end`) OR ('$end' > `start` AND '$end' <= `end`)) AND `date`='$date' AND idb='$idb'");  
  $interceptionExter = mysql_query("SELECT * FROM {$vkid}_history WHERE (('$start' >= `start` AND '$start' <= `end`) OR ('$end' >= `start` AND '$end' <= `end`)) AND `date`='$date' AND idb !='$idb'"); 
       
  $interceptionInter = mysql_fetch_array($interceptionInter);
  $interceptionExter = mysql_fetch_array($interceptionExter);
       
  if (!empty($interceptionInter['start']) || !empty($interceptionExter['start'])) {
    $interception = 0;
    $intererr = "Извините, данная репетиция пересекается с репетицией которую вы уже забронировали.";
    return 0;
  } else {
    $interception = 1;  
    return  1;
  }
     
}

function changeCurrent($idb,$vkid,$x) {
  $table = $idb."_list";
  if ($x > 0) {
    $r  = mysql_query("UPDATE `b108859_wordpress`.`mus_users` SET `curr_book_num`=`curr_book_num`+1 WHERE vkid='$vkid'");
    $r1 = mysql_query("UPDATE `b108859_wordpress`.`$table` SET `current`=`current`+1 WHERE vkid='$vkid'");
  } else if ($x < 0) {
    $r  = mysql_query("UPDATE `b108859_wordpress`.`mus_users` SET `curr_book_num`=`curr_book_num`-1 WHERE vkid='$vkid'");
    $r1 = mysql_query("UPDATE `b108859_wordpress`.`$table` SET `current`=`current`-1 WHERE vkid='$vkid'");
  }
}

function changePast($idb,$vkid,$x) {
  $table = $idb."_list";
  if ($x > 0) {
    $r  = mysql_query("UPDATE `b108859_wordpress`.`mus_users` SET `past_book_num`=`past_book_num`+1 WHERE vkid='$vkid'");
    $r1 = mysql_query("UPDATE `b108859_wordpress`.`$table` SET `done`=`current`+1 WHERE vkid='$vkid'");
  } else if ($x < 0) {
    $r  = mysql_query("UPDATE `b108859_wordpress`.`mus_users` SET `past_book_num`=`past_book_num`-1 WHERE vkid='$vkid'");
    $r1 = mysql_query("UPDATE `b108859_wordpress`.`$table` SET `done`=`current`-1 WHERE vkid='$vkid'");
  }
}

function createUser($vkid,$name,$lastname,$phone) {
  $c = userExist($vkid);
  if (!$c) {
    $r = mysql_query("INSERT INTO `b108859_wordpress`.`mus_users` (`vkid`,`name`,`lastname`,`phone`,`curr_book_num`,`past_book_num`) VALUES ('$vkid','$name','$lastname','$phone','0','0')");
    
    $r1 = mysql_query(
      "CREATE TABLE `b108859_wordpress`.`{$vkid}_history` (
        `idb`   INT NOT NULL,
        `date`  TEXT NOT NULL,
        `start` INT( 4 ) NOT NULL ,
        `end`   INT( 4 ) NOT NULL ,
        `done`  INT NOT NULL,
        `past`  INT NOT NULL,
        `tel`   VARCHAR(25) NOT NULL,
        `band`  TEXT NOT NULL,
        `room`  INT(3)  NOT NULL,
        `price`  INT(5)  NOT NULL,
        `status`  INT(3)  NOT NULL
      )"
    );

    $r2 = mysql_query(
      "CREATE TABLE `b108859_wordpress`.`{$vkid}_notification` (
        `date`  VARCHAR(12) NOT NULL,
        `type`  INT(1) NOT NULL,
        `idb`   INT(9) NOT NULL,
        `start` VARCHAR(5) NOT NULL,
        `end`   INT(5) NOT NULL,
        `room`   INT(3) NOT NULL,
        `odate` TEXT NOT NULL,
        `otime` INT(5) NOT NULL,
        `seen`  INT(1) NOT NULL
      )"
    );
  }
}

function userExist($vkid) {
  $r = mysql_query("SELECT * FROM mus_users WHERE vkid='$vkid'");
  $r1 = mysql_fetch_array($r);
    if (!empty($r1['vkid'])) {
      return 1;
    } else {
      return 0;
    }
}

function checkBL($vkid,$idb) {
  $bl = mysql_query("SELECT * FROM {$idb}_list WHERE vkid='$vkid'");
  if ($bl) {
    $bl = mysql_fetch_array($bl);

    if (!empty($bl['bl'])) {
      if ($bl['bl'] != 1) {
        return 1;
      } else {
        return 0;
      }
      
    } else {
      return 1;
    } 

  } else {
     return 1;
  }
}

// ATTENTION! returns array [code, error message];
function checkBookingSettings($vkid,$idb) {
  $num = mysql_query("SELECT * FROM mus_users WHERE `vkid`='$vkid'");
  $num = mysql_fetch_array($num);
  $gpast = $num['past_book_num'];
  $gcurr = $num['curr_book_num'];
  $c2 = 1;
       
  //determine if user is Prime for base
  $userstats = mysql_query("SELECT * FROM {$idb}_list WHERE `vkid`='$vkid'");
  $userstats = mysql_fetch_array($userstats);
  $lpast  = $userstats['done'];
  $lcurr  = $userstats['current'];  
      
  $basesettings =  mysql_query("SELECT * FROM bases WHERE `id`='$idb'");
  $basesettings = mysql_fetch_array($basesettings);
  $maxPrime = $basesettings['maxPrime'];
  $max = $basesettings['max'];
       
  $errormax = "";
       
  if (($gpast == 0 && $gcurr == 1)) {
    $return = array(0,0);
    return $return; 
  } 
       

  if ($lpast > 0) { // not prime
    if ($lcurr >= $max) {
      $return = array(0,1);
      return $return;
    }
  } else { //prime
    if ($lcurr >= $maxPrime) {
      $return = array(0,2); 
      return $return;
    }
  }

  $return = array(1,"0");
  return  $return;
}

function addToList($idb,$vkid,$name,$lastname) {
  $lists=mysql_query("SELECT * FROM {$idb}_list WHERE `vkid`='$vkid'");
  $lists=mysql_fetch_array($lists);
       
  if (empty($lists['vkid'])) {
    $fullname = $name." ".$lastname; 
    $addtolist = mysql_query("INSERT into {$idb}_list (vkid, name,current) VALUES ('$vkid','$fullname','1')");
  } 
}

function hashcheck($hash,$vkid) {
  $app_id="2388317";
  $secret_key="4FX2MlBq5mr8vjKoEXxK";
  $bbhash=md5($app_id.$vkid.$secret_key);
  if ($bbhash == $hash){
    return 1;
  } else {
    return 0;
  }
}

function openHoursCheck($start,$end,$idb) {
  $settings = mysql_query("SELECT firstHour, lastHour, NF FROM bases WHERE `id`='$idb'");
  $settings = mysql_fetch_array($settings);

  $firstHour = $settings['firstHour'] * 60;
  $lastHour = $settings['lastHour'] * 60;

  if ($settings['NF'] == "0") {
    return true;
  }

  if ($start < $firstHour || $end > $lastHour) {
    return false;
  }
   return true;
}



// admin = 1 then no vkid

/*     error and output codes:

1 - booking is not switched on
2 - booking exists
3 - booking is not in schedule
4 - intercection violation
5 - BL
6 - Violation of booking settings
7 - hash violation
8 - open hours violation

10 - everything worked out well, congrats Roman
11 - success admin booking
*/

// if admin - adminbooking, no vkid
function book($idb,$vkid,$date,$start,$end,$room,$admin,$band,$add,$phone,$name,$lastname,$hash,$price,$nf) {

  $output = array();
  $checkBooking    = checkBooking($idb);
  $bookingType     = bookingType($idb); // 1 - acc not needed; 2 - needed
  $scheduleCheck   = schCheck($idb,$room,$date,$today,$start,$end);
  $bookingNotExist = bookingNotExist($idb,$start,$end,$date,$room); 
  $hashcheck       = hashcheck($hash,$vkid);
  $openHoursCheck  = openHoursCheck($start,$end,$idb);

  if ($hash == "reserve") {
    $hashcheck = true;
  }

  if (!$checkBooking) {
    $output[0] = 1;
    return $output;
  }

  if (!$bookingNotExist) {
    $output[0] = 2;
    return $output;
  }

  if ($nf == "0") {
    if (!$scheduleCheck) {
      $output[0] = 3;
      return $output;
    }
  } else {
    if (!$openHoursCheck) {
      $output[0] = 8;
      return $output;
    }
  }
  
  if (!$hashcheck && !$admin) {
    $output[0] = 7;
    return $output;
  }

  switch ($admin) { 
    // admin booking
    case 1: 
      $r = mysql_query("INSERT into {$idb}_booking (vkid,start,end,band,date,name,lastname,past,room,phone,price,accept,admin) VALUES ('0','$start','$end','$band','$date','$name','','0','$room','$phone','$price','1','1')");
      
      $output[0] = 11;
      return $output;
      
      break;

    // user booking
    case 0:


    createUser($vkid,$name,$lastname,$phone);
    $c1 = checkBL($vkid,$idb);
    $c2 = notIntersect($idb,$vkid,$start,$end,$date);
    $ct = checkBookingSettings($vkid,$idb);
    $c3 = $ct[0];
    unset ($ct);

      if ($c1 && $c2 && $c3) {
      // direct booking
        if ($bookingType == 1) {

          $r  = mysql_query("INSERT into {$idb}_booking (vkid,start,end,band,date,name,lastname,past,room,phone,price,accept) VALUES ('$vkid','$start','$end','$band','$date','$name','$lastname','0','$room','$phone','$price','1')");
          $r1 = mysql_query("INSERT into {$vkid}_history (idb,date,start,end,band,room,status,price) VALUES ('$idb','$date','$start','$end','$band','$room','1','$price')");
          changeCurrent($idb,$vkid,1);
          bookingNotify($idb,$room,$start,$end,$date,$vkid);
        // acceptance based booking
        } else {
          $r  = mysql_query("INSERT into {$idb}_booking (vkid,start,end,band,date,name,lastname,past,room,phone,price,accept) VALUES ('$vkid','$start','$end','$band','$date','$name','$lastname','0','$room','$phone','$price','0')");
          $r1 = mysql_query("INSERT into {$vkid}_history (idb,date,start,end,band,room,status,price) VALUES ('$idb','$date','$start','$end','$band','$room','0','$price')");
          bookingNotify($idb,$room,$start,$end,$date,$vkid);
        }

        addToList($idb,$vkid,$name,$lastname);
        unset($_SESSION['login']);
        unset($_SESSION['pid']);
        unset($_SESSION['type']);
        $_SESSION['vkid'] = $vkid;
        
        $output[0] = 10;
        return $output;

      } else {
        if (!$c1) {
          $output[0] = 5;
          return $output;
        }
        if (!$c2) {
          $output[0] = 4;
          return $output;
        }
        if (!$c3) {
          $output[0] = 6;
          return $output;
        }
      }
    
    break;
  }
}

function errorMessage($x) {
  $x1 = $x[0];
  $x2 = $x[1];
  
  $error = "";
  switch($x1) {
    case 1 : 
      $error = "Бронирование не включено!";
      break;
    case 2 : 
      $error = "Данное время уже забронировано. Выберете пожалуйста другую репетицию.";
      break;
    case 3 : 
      $error = "Репетиция которую вы хотите забронировать отстутствует в расписании. Попробуйте забронировать снова.";
      break;
    case 4 :
      $error = "Репетиция которую вы пытаетесь забронировать пересекается с репетицией заброниванной вами ранее. Пожайлуйста, выберете другое время."; 
      break;
    case 5 : 
      $error = "К сожалению вы добавлены в черный список этой базы. Если вы считаете, что Вас добавили по ошибке - позвоните по контактному телефону базы.";
      break;
    case 6 : 
      switch($x2) {
        case 0:
          $error = "Извините, но пока вы еще не сходили ни на одну репетицию забронированную через Basebooking, поэтому пока мы не можем забронировать вам еще одно время.";
          break;
        case 1:
        $error = "Извините, но вы исчерпали лимит на бронирования для этой базы.";
          break;
        case 2:
          $error = "Извините, но вы исчерпали лимит на бронирования для музыкантов которые еще не были на этой базе.";
          break;
      }
      break;
    case 7 : 
      $error = "Хм. Вконтакте говорит, что вы пытаетесь сделать что-то противозаконное.";
      break;
    case 8 : 
      $error = "Репетицию которую Вы пытаетесь забронировать начинается либо раньше открытия студии либо заканчивается после закрытия.";
      break;

    case 10 : 
      $error = "Репетиция успешно забронирована! Перейти в <a href=\"http://www.basebooking.ru/musician/\">личный кабинет</a>. ";

      break;
    case 11 : 
      $error = "Репетиция успешно забронирована!";

      break;
  }

  return $error;
}

/* END OF BOOKING FUNCTIONS*/

 function makeJavaScriptArray( $phpArray ) { 
    $arrayConstant = 'new Array('; 
    $delimiter = ''; 
     
    foreach ($phpArray as $fieldName => $fieldValue) { 
        if (is_bool( $fieldValue ))                                    // Boolean data type 
            if ($fieldValue) $fieldConstant = 'true'; 
            else $fieldConstant = 'false'; 
         
        elseif (is_numeric( $fieldValue ))                            // Numeric data type 
            $fieldConstant = $fieldValue; 
         
        elseif (is_string( $fieldValue ))                            // String data type 
            $fieldConstant = "\"" . addSlashes( $fieldValue ) . "\""; 
             
        elseif (is_array( $fieldValue ))                            // Array data type 
            $fieldConstant = makeJavaScriptArray( $fieldValue ); 
             
        else                                                        // Unknown data type 
            $fieldConstant = ''; 
         
        if ($fieldConstant > '') { 
            $arrayConstant .= $delimiter . "$fieldConstant"; 
            $delimiter = ','; 
        } 
    }
    $arrayConstant .= ')'; 
    return $arrayConstant;
}  

function dateFormat($date,$time) {
  $months = array("Янв","Фев","Мар","Апр","Мая","Июн","Июл","Авг","Сен","Окт","Ноя","Дек");
  $dtemp=explode(".",$date);
  $date = $dtemp[0]." ".$months[$dtemp[1]-1].", ".$dtemp[2]." в ".formatTime($time);

  return $date;
}

function listOut($idb) {
  $parity = 0;
  $output = "<div id=\"el_1\" class=\"list\">";
  $r = mysql_query("SELECT * FROM {$idb}_list WHERE bl='0' ");

  while ($list = mysql_fetch_array($r,MYSQL_ASSOC)){
    
    if ($parity % 2 == 0) {
      $output .= "<div class=\"booking_odd\">";
    } else {
      $output .= "<div class=\"booking_even\">";
    }
    
    $output .= "<div class=\"name\"><span><a href =\"http://www.vk.com/id{$list['vkid']}\">{$list['name']}</a></span></div>";
    
    $output .= "
      <div class=\"l1\">
        <div>
          <span>Долг: {$list['debt']} руб</span>
          <span class=\"debt\">Изменить долг</span>
        </div>
        
        <div >
          <span><input type=\"text\" value=\"{$list['debt']}\" /></span>
          <span class=\"confirmDebt\">Сохранить изменения</span>
        </div>
      
      </div>
    
      <div class=\"l2\">
        <div>
          <span class=\"metrics\">
            <span>Текущие: {$list['current']}</span>
            <span>Прошлые: {$list['done']}</span>
          </span>
          <span class=\"addToBL\">Добавить в черный список</span>
        </div>
        
        <div>
          <span>Вы уверены что хотите добавить пользователя в черный список?<br /> <a class=\"yesBL\">Да</a> &nbsp&nbsp&nbsp <a class=\"cancelBL\">Нет</a> </span>
        </div>

      </div>
      <div class =\"vkid\">{$list['vkid']}</div>
    
    ";

    $output .= "</div>";
    $parity++;
  }

  if ($parity != 0) {
   return $output."</div>";
  } else {
    return $output."<div class=\"note\">Здесь будут показаны репетирующие музыканты</div></div>";
  }
}

function blOut($idb) {
  $parity = 0;
  $output = "<div id=\"el_2\" class=\"list\">";
  $r = mysql_query("SELECT * FROM {$idb}_list WHERE bl='1' ");

  while ($list = mysql_fetch_array($r,MYSQL_ASSOC)){
    
    if ($parity % 2 == 0) {
      $output .= "<div class=\"booking_odd\">";
    } else {
      $output .= "<div class=\"booking_even\">";
    }
    
    $output .= "<div class=\"name\"><span><a href =\"http://www.vk.com/id{$list['vkid']}\">{$list['name']}</a></span></div>";
    
    $output .= "
      <div class=\"l1\">
        <div>
          <span>Долг: {$list['debt']} руб</span>
          <span class=\"debt\">Изменить долг</span>
        </div>
        
        <div >
          <span><input type=\"text\" value=\"{$list['debt']}\" /></span>
          <span class=\"confirmDebt\">Сохранить изменения</span>
        </div>
      
      </div>
    
      <div class=\"l2\">
        <div>
          <span class=\"metrics\">
            <span>Текущие: {$list['current']}</span>
            <span>Прошлые: {$list['done']}</span>
          </span>
          <span class=\"addToBL\">Удалить из черного списка</span>
        </div>
        
        <div>
          <span> Удалить пользователя из черного списка<br /> <a class=\"yesBL\">Да</a> &nbsp&nbsp&nbsp <a class=\"cancelBL\">Нет</a> </span>
        </div>

      </div>
      <div class =\"vkid\">{$list['vkid']}</div>
    
    ";

    $output .= "</div>";
    $parity++;
  }


  if ($parity != 0) {
   return $output."</div>";
  } else {
    return $output."<div class=\"note\">В черном списке нет музыкантов</div></div>";
  }


}

function schedulesout($idb) {

  $r = mysql_query("SELECT * FROM {$idb}_schedule");
  

  $schedules = Array();
  $i = 0;
  
  While ($row = mysql_fetch_array($r,MYSQL_ASSOC)) {
    $schedules[$i] = $row;
    $schedules[$i]['mark'] = 0;
    $schedules[$i]['newrooms'] = "".$schedules[$i]['rooms'];
    $i++;
  }
  
  $newSchedules = Array();
  $lastindex = 0;
  for ($j = 0; $j < $i; $j++ ) {
    if ($schedules[$j]['mark'] != 1) {
      $newSchedules[] = $schedules[$j];

      $lastindex = count($newSchedules);
      for ($k = $j+1; $k < $i; $k++) {
        if ($schedules[$k]['d1'] == $schedules[$j]['d1'] &&
            $schedules[$k]['d2'] == $schedules[$j]['d2'] &&
            $schedules[$k]['d3'] == $schedules[$j]['d3'] &&
            $schedules[$k]['d4'] == $schedules[$j]['d4'] &&
            $schedules[$k]['d5'] == $schedules[$j]['d5'] &&
            $schedules[$k]['d6'] == $schedules[$j]['d6'] &&
            $schedules[$k]['d7'] == $schedules[$j]['d7']  ) {
          $newSchedules[$lastindex-1]['newrooms'] .= ",".$schedules[$k]['rooms'];
          $schedules[$k]['mark'] = 1;
        }
      }
    }
  }

  $lastindex = count($newSchedules);
  for ($j = 0; $j < $lastindex; $j++ ) {
    unset($newSchedules[$j]['rooms']);
    unset($newSchedules[$j]['mark']);
  }
  
  //$newSchedules[$lastindex+1] = $rooms;
  return $newSchedules;
  
}

function createBase($name,$type,$komn,$descript,$town,$station,$adress,$pid,$vk,$phone,$website) {
  $exp = time() + 3888000 * 100;

  $result = mysql_query("INSERT into bases (name,type,komn,descript,town,station,adress,pid,vk,phone,website,exp) VALUES ('$name','$type','$komn','$descript','$town','$station','$adress','$pid','$vk','$phone','$website',$exp)");
  $idb = mysql_insert_id();
    if (!$result) { 
      return false;
    }

    $r = mysql_query("SELECT id FROM bases WHERE name='$name'");
    if (!$r) { 
      return false;
    }

    $row = mysql_fetch_array($r);
    $id = $row['id'];
    unset($row);
    unset($r);
    
    $r2 = mysql_query("
    CREATE TABLE `b108859_wordpress`.`{$id}_photo` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    PRIMARY KEY ( `id` ) ,
    `name` VARCHAR( 12 ) NOT NULL
    )");
    
    $r3 = mysql_query("
    CREATE TABLE `b108859_wordpress`.`{$id}_equip` (
    `id` INT NOT NULL ,
    PRIMARY KEY ( `id` ) ,
    `guitar` TEXT NOT NULL ,
    `bass` TEXT NOT NULL ,
    `drum` TEXT NOT NULL ,
    `line` TEXT NOT NULL ,
    `extra` TEXT NOT NULL,
    `price` TEXT NOT NULL ,
    `name` TEXT NOT NULL
    )");
    
    $r4 = mysql_query("
    CREATE TABLE `b108859_wordpress`.`{$id}_booking` (
    `vkid` BIGINT(20) NOT NULL,
    `date` TEXT NOT NULL ,
    `room` TEXT NOT NULL ,
    `name` TEXT NOT NULL ,
    `lastname` TEXT NOT NULL,
    `phone` TEXT NOT NULL,
    `price` INT(11) NOT NULL,
    `start` INT NOT NULL ,
    `end` INT NOT NULL ,
    `band` TEXT NOT NULL,
    `past` INT(1) NOT NULL,
    `done` INT(1) NOT NULL,
    `admin` INT(1) NOT NULL,
    `accept` INT(1) NOT NULL
    )");
    
    $r5 = mysql_query("
    CREATE TABLE `b108859_wordpress`.`{$id}_schedule` (
    `rooms` TEXT NOT NULL ,
    `d1` TEXT NOT NULL ,
    `d2` TEXT NOT NULL ,
    `d3` TEXT NOT NULL ,
    `d4` TEXT NOT NULL ,
    `d5` TEXT NOT NULL ,
    `d6` TEXT NOT NULL ,
    `d7` TEXT NOT NULL 
    )");
    
    $r6 = mysql_query("
    CREATE TABLE `b108859_wordpress`.`{$id}_list` (
    `vkid` bigint(20)  NOT  NULL ,
  `name` text NOT  NULL ,
  `done` int(5)  NOT  NULL ,
  `debt` int(6)  NOT  NULL ,
  `bl` int(1)  NOT  NULL ,
  `current` int(5)  NOT  NULL ,
  `cancels` int(5)  NOT  NULL
    )");
    
    $r7 = mysql_query("
    CREATE TABLE `b108859_wordpress`.`{$id}_notification` (
    `seen` INT(2) NOT NULL,
    `type` INT(2) NOT NULL,
    `vkid` INT(12) NOT NULL, 
    `room` INT(3) NOT NULL,
    `date` TEXT NOT NULL,
    `start` INT(5) NOT NULL,
    `end` INT(5) NOT NULL,
    `odate` TEXT NOT NULL,
    `otime` INT(5) NOT NULL,
    `timestamp` INT(15) NOT NULL
    )");


    for ($i = 1; $i <= $komn; $i++) {
      $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_schedule` (`rooms`) VALUES ('$i')");
      $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_equip` (`id`) VALUES ('$i')");
    }

    if ($r2 && $r3 && $r4 && $r5 && $r6 && $r7) {
      return true;
    } else {
      return false;
    }
}

function rand_str($length = 10, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
    $chars_length = (strlen($chars) - 1);
    $string       = $chars{mt_rand(0, $chars_length)};
    for ($i = 1; $i < $length; $i = strlen($string)) {
        $r = $chars{rand(0, $chars_length)};
        if ($r != $string{$i - 1})
            $string .= $r;
    }
    return $string;
}

function TrueEmail($email){
  $email_arr=explode('@',$email);
  $email_arr2=@explode('.',$email_arr[1]);
  switch (true){
          case count($email_arr)!=2:
              return false;
          case strlen($email_arr[1])<4:
              return false;
          case preg_replace("/[0-9A-zА-я._-]/",null,$email_arr[0])!=false:
              return false;
          case preg_replace("/[0-9A-zА-я._-]/",null,$email_arr[1])!=false:
              return false;
          case count($email_arr2)<2:
              return false;
          case strlen($email_arr2[0])<2:
              return false;
          case strlen($email_arr2[1])<2:
              return false;
          default:
              return true;
  }
}

function deleteBase($idb) {
  $pid = mysql_query("SELECT pid FROM bases WHERE id='$idb'");
  if (!$pid) {
    return false;
  }
  $pid = mysql_fetch_array($pid);
  $pid = $pid['pid'];

  $login = $_SESSION['login'];

  //delete tables
  $notification = $idb."_notification";
  $list = $idb."_list";
  $schedule = $idb."_schedule";
  $booking = $idb."_booking";
  $eqip = $idb."_equip";
  $photo = $idb."_photo";
  

  $r1 = mysql_query("DROP TABLE `$notification`");
  $r2 = mysql_query("DROP TABLE `$list`");
  $r3 = mysql_query("DROP TABLE `$schedule`");
  $r4 = mysql_query("DROP TABLE `$booking`");
  $r5 = mysql_query("DROP TABLE `$eqip`");
  $r6 = mysql_query("DROP TABLE `$photo`");

  unset($notification);
  unset($list);
  unset($schedule);
  unset($booking);
  unset($eqip);
  unset($photo);
  //delete tables

  //delete photos
  $r7 = mysql_query("SELECT * from $table ORDER BY id"); 
  if ($r7) {
    while ($row=mysql_fetch_array($r6,MYSQL_ASSOC)) {
      $file="../upload/".$row['name']; 
      unlink($file);
    } 
  }
  //delete photos


  $r8 = mysql_query("DELETE FROM bases WHERE id='$idb'");  //delete from bases
  $r9 = mysql_query("DELETE FROM transfer WHERE id='$idb'");  //delete from transfer

  //delete from user
  if ($login === 'superadmin') {
    $sab = mysql_query("SELECT bases FROM users WHERE `login`='superadmin'");
    $sab = mysql_fetch_array($sab);
    $sab = explode(";",$sab['bases']);
    $d = array_search($idb,$sab);
    unset($sab[$d]);
    $sab = implode(";",$sab);
    $sar = mysql_query("UPDATE `b108859_wordpress`.`users` SET `bases` = '$sab' WHERE `users`.`login`='superadmin' ");
    $r10 = $sar;

    unset($sab);
    unset($sar);
    unset($d);
  } else if ($login !== "" && $pid != "") {
    $r10 = mysql_query("UPDATE `b108859_wordpress`.`users` SET `bases` = '' WHERE `users`.`id`='$pid'");
  }
  //delete from user

  if ($r1 && $r2 && $r3 && $r4 && $r5 && $r6 && $r7 && $r8 && $r9) {
    return true;
  } else {
    return false;
  }
}

function deleteUser($id) {
  $id = intval($id);
  if ($id == 30) {
    return false; // superadmin protection, so nobody can delete superadmin
  }
  
  $r = mysql_query("SELECT bases FROM users WHERE id = '$id'");
  if (!$r) {
    return false;
  }
  $r = mysql_fetch_array($r);
  $idb = $r['bases'];
  deleteBase($idb);

  $r2 = mysql_query("DELETE FROM users WHERE id = '$id'");
  if ($r2) {
    return true;
  } else {
    return false;
  }
}

function formatLink($str) {
  $escapes = array("http://","https://","http//:","https//:","http:/","https:/","http:","https:");
  $str = str_replace($escapes, "", $str);
  $str = "http://".$str;
  return $str;
}

function formatLinkForHumans($str) {
  $escapes = array("http://","https://","http//:","https//:","http:/","https:/","http:","https:");
  $str = str_replace($escapes, "", $str);
  return $str;
}


function getSchedules($idb) {
  $r = mysql_query("SELECT * from {$idb}_schedule");
  
  $schedules = array();
  $i = 0;
  while ($row = mysql_fetch_array($r)) {
    $schedules[$i] = $row;
    $i++;
  }

  return $schedules;
}

function outputSchedules($schedules) {
  $schedules = json_encode($schedules);
  print($schedules);
}

function printRoomsNames($idb) {
  $r = mysql_query("SELECT * FROM {$idb}_equip");
  if (!$r) {
    print("[];");
  }

  $roomNames = array();
  while ($row = mysql_fetch_array($r)) {
    if ($row['name'] == "") {
      $row['name'] = $row['id'];
    }
    $roomNames[$row['id']] = $row['name'];
  }

  $roomNames = json_encode($roomNames);
  print($roomNames);
}

function getRoomName($idb,$room) {
  $r = mysql_query("SELECT name FROM {$idb}_equip WHERE id='$room'");
  if (!$r) {
    return "";
  }

  $name = mysql_fetch_array($r);
  $name = $name['name'];
  if ($name == "") {
    $name = $room;
  }
  return $name;
}

function getBaseEmail($idb) {
  $r = mysql_query("SELECT pid FROM bases WHERE id='$idb'");
  if (!$r) {
    return "";
  }

  $r = mysql_fetch_array($r);
  $pid = $r['pid'];

  $r = mysql_query("SELECT email FROM users WHERE id='$pid'");
  if (!$r) {
    return "";
  }
  $r = mysql_fetch_array($r);
  $email = $r['email'];

  return $email;

}

function emailNotify($vkid,$start,$end,$room,$date,$idb,$type) {
  $r = mysql_query("SELECT emailNotify FROM bases WHERE id='$idb'");
  if (!$r) {
    return false;
  } 

  $r = mysql_fetch_array($r);
  if ($r['emailNotify'] != 1) {
    return false;
  }
 
  $name = getName($vkid);
  $roomName = getRoomName($idb,$room);
  $start = formatTime($start);
  $end = formatTime($end);
  $email = getBaseEmail($idb);

  $to = $email;
  if ($type == 1) {
    $str = "Бронирование";
    $str1 = "<a href=\"http://www.vkontakte.ru/id".$vkid."\">".$name[0]." ".$name[1]."</a> забронировал(а) в комнате ".$roomName." репетицию с ".$start." до ".$end." на ".$date;
  } else {
    $str = "Отмена бронирования";
    $str1 = "<a href=\"http://www.vkontakte.ru/id".$vkid."\">".$name[0]." ".$name[1]."</a> отменил(а) бронирование в комнате ".$roomName." с ".$start." до ".$end." на ".$date;
  }

  $subject = $str.' на Basebooking!';
  $message = "<html><body>Доброго времени суток,\n<br/><br/>
  $str1,\n<br/>
  Basebooking.ru
  </body></html>
  ";
  $headers = "From: notification@basebooking.ru\r\n";
  $headers .= "Reply-To: contact@basebooking.ru \r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
  
  return mail($to, $subject, $message, $headers);
}

?>