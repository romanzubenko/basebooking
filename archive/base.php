<?php  session_start(); 
include "utils.php";
$today = 10000*date("Y")+100*date("n")+date("j");

if (!isset($_GET['name']) or empty($_GET['name'])) {
	header('Location: http://www.basebooking.ru/');
}

$bn = check($_GET['name']);
  
$r = mysql_query("SELECT * from bases WHERE name='$bn'");
if ($r == false ){ 
	header('Location: http://www.basebooking.ru/');
}  

$bn = clears($bn);
 
$r = mysql_fetch_array($r);
if (!isset($r['id']) or empty($r['id'])  ){ 
 header('Location: http://www.basebooking.ru/');
}  

// load conditions
	$conditions = array();
	$conditions['name'] = $r['name'];
	$conditions['deadline'] = $r['deadline']." ".rusHour($r['deadline'])." ";
	$conditions['maxPrime'] = $r['maxPrime'];
	$conditions['max'] = $r['max'];
	
  $conditions['booking'] = $r['booking'];
	$conditionsOut = "<div id=\"cond\" style=\"display:none\">";
	if (!$conditions['booking']) {
		$conditionsOut .= "Бронирование пока не доступно";
	} else {
		$conditionsOut .= "
		<div>
			<span>Все музыканты кто еще не пользовался Basebooking могут забронировать лишь одну репетицию, пока не сходят на первое забронированное время.</span></br></br>
			Максимальное количество репетиций доступное для бронирования пользователям, еще не репетировавших на {$conditions['name']} - {$conditions['maxPrime']} <br/><br/>
			Максимальное количество репетиций доступное для бронирования пользователям, уже репетировавших на {$conditions['name']} - {$conditions['max']} <br/><br/>
			Дедлайн после которого не возможна бесплатная отмена репетиции - {$conditions['deadline']}до начала репетиции
		</div>
		";
	}

	$conditionsOut .="</div>";
	unset($conditions);
// load conditions


$idb = $r['id'];
$ct = currentTime($idb,$today);
$ctM = $ct % 60;
$ctH = ($ct - $ctM) / 60;

// check if base uses booking
$activate = $r['booking'];
// check if base uses booking

 if ($r['type']==1) {$r['type']="Репетиционная база";}
 if ($r['type']==2) {$r['type']="Студия";}
 if ($r['type']==3) {$r['type']="Репетиционная база и студия";} 

 //load schedule
$temp=mysql_query("SELECT * from {$idb}_schedule");
$sch=array(0=>array('rooms'=>0));
$j=0;
$script="sch = [];";
if ($temp){
  While ($row=mysql_fetch_array($temp,MYSQL_ASSOC)) {
    $sch[$j] = $row;
    $i = 0;    
    $script .= "sch[{$sch[$j]['rooms']}]=[\"{$sch[$j]['d1']}\",\"{$sch[$j]['d2']}\",\"{$sch[$j]['d3']}\",\"{$sch[$j]['d4']}\",\"{$sch[$j]['d5']}\",\"{$sch[$j]['d6']}\",\"{$sch[$j]['d7']}\"];";
    $j++;
  }
}
//load schedule

   //clear 
 
if (isset($_SESSION['login'])) {
	$mybases=mysql_query("SELECT bases from users where login='$login'",$db); 
	$mybases=mysql_fetch_array($mybases);
	$mybase=$mybases['bases'];
} else {
  $mybase=0;
}

//start booking

  if (isset($_POST['hash']) && !empty($_POST['hash'])) {
    $name=check($_POST['name']);
    $lastname=check($_POST['lastname']);
    $hash=check($_POST['hash']);
    $vkid=check($_POST['vkid']);
    $start=check($_POST['timestart']);
    $end=check($_POST['timeend']);
    $date=check($_POST['date']);
    $phone=check($_POST['phone']);
    $room=check($_POST['room']);
    $band=check($_POST['band']);
    $price=check($_POST['price']);
    $bookcode = book($idb,$vkid,$date,$start,$end,$room,$admin,$band,$add,$phone,$name,$lastname,$hash,$price);
    $errorbooking = errorMessage($bookcode);
  } 
 
//end booking
 

//load bookings
$bookarr="bookings = [];";
$i=0;
For ($j=1;$j<=$r['komn'];$j++){
$temp="";
  $bookings=mysql_query("SELECT * FROM {$idb}_booking WHERE past='0' and room='$j'",$db);
  $ch=0; 
  $temp.="bookings[".$j."]=[";
  if ($bookings){
    While ($load=mysql_fetch_array($bookings,MYSQL_ASSOC)){
      if (!empty($load['date']) and !empty($load['start'])) {
        $ch=1;
        if ($_SESSION['vkid']==$load['vkid']){
          $temp.="['".$load['date']."','".$load['start']."-".$load['end']."']".",";
        } else {
          $temp.="['".$load['date']."','".$load['start']."-".$load['end']."']".",";	
        }
      }    
    }
  }
  if ($ch==1){
    $len=strlen($temp);
    $len--;
    $temp=substr($temp, 0,$len);
    $bookarr.=$temp;
    $bookarr.="];";
  }
}

// get rooms names
 ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - <?php $b =  clears($_GET['name']); echo $b; ?></title>
<link href="http://basebooking.ru/styles/fotorama.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/styles.css" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/baseStyles.css" />
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?32"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/fotorama.js"></script>
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<script type="text/javascript"  >
var activate = <?php 
if ($activate) {
  echo "1;";
} else {
  echo "0;";
}


print("roomNames = ");
printRoomsNames($idb);
print(";");

?>
$(function() {
  $('#fotorama').fotorama({
    width: 676,
    height: 224,
    navBackground:  '#fafafa',
    background: '#fafafa',
    thumbsBackgroundColor: '#fafafa'
  });
  
});

$(window).load(function() {
  
  mainHeight=$("#main").css("height");
  mainHeight=Number(mainHeight.substring(0, mainHeight.length-2));
  $("#box1").css("min-height", mainHeight+"px");
  <?php
    print("idb = ".$idb.";");
    echo "today=".$today.";t1=today;";  
    echo $script;
    $k=$r['komn'];
    echo"k={$k};";
    echo $bookarr;
    echo "minutes={$ctM};hours={$ctH};"
  ?> 
  
  d = parseInt(t1.toString().substring(6,8));
  startTime(d,hours,minutes,t1);  
})





function startTime(d,h,m,today) {
    m++;	
	m=checkTime(m);
	h=checkTime(h);
	d=checkTime(d)
	if (m == 60 ) {
		m="00";
		h++;
		
		if (h == 24){
          h="00";
          d++;
		}
	}
    newt = t1.toString();
    newt=newt.substring(0,4)+"."+newt.substring(4,6)+"."+d;
	$("#currTime").html(newt+" "+h+":"+m);
	t=setTimeout('startTime('+d+','+h+','+m+',t1)',1000*60);
}

function checkTime(i)  {
if (i<10) {
  i="0" + i;
}
return i;
}
</script>
<script type="text/javascript" src="http://basebooking.ru/js/bookingTest.js"></script>
<style>
.fotorama__frame {
  background-color:#fafafa;
}
</style>
</head>
<body>
<script type="text/javascript">
  VK.init({apiId: 2388317});
</script>
<div id="centered">

<?php printHeader(); ?>
<div class="space"></div>
<div class="space"></div>
  <div id="basename">
    <?php $b = clears($_GET['name']); echo $b;?></div><br/>

 
  <div class="topline">
    
    


    <div class="ribbon">
      <div id="gall">Галерея</div>
      <div id="cal">Забронировать</div>
      <div id="conditions">Условия бронирования</div>
      <div id="currTime"></div>
    </div>   
    <div class="triangle-l"></div> 
    <div class="triangle-r"></div>
  </div>   
  <div id="main"> 
  <div id="box1">
  
      <?php  


/*
add message that base is not administered

*/
if ($r['pid'] == 0 ) {
  print("<div class=\"errwrap\" id=\"noadmin\">Извините, но администраторы ".$r['name']." еще не подключились к Basebooking. Вся информация на данной странице носит только информационный характер.</div>");
}

/*  PHOTOS FIGURE OUT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/  

$table = $idb."_photo";
$r2 = mysql_query("SELECT * FROM $table ORDER BY id");


if ($r2) {
  $row = mysql_fetch_array($r2,MYSQL_ASSOC);
} else {
  $row = "";
}

if (isset($errorbooking) && !empty($row)) {echo"<div class=\"errwrap\" id=\"err1\"><div class=\"err\"><span>{$errorbooking}{$errormax}<span></div><div class=\"space\" style=\"height:10px\"></div></div>";}
if (empty($row) && isset($errorbooking)) {echo"<div class=\"errwrap\"  id=\"err1\"><div class=\"err\" id=\"err1\"><span>{$errorbooking}{$errormax} Фотографии {$bn} еще не загружены<span></div><div class=\"space\" style=\"height:10px\"></div></div>";}
if (empty($row) && !isset($errorbooking)) {echo"<div class=\"errwrap\"  id=\"err1\"><div class=\"err\" id=\"err1\"><span>Фотографии {$bn} еще не загружены<span></div><div class=\"space\" style=\"height:10px\"></div></div>";}

if (!empty($row)) {
  echo"<div id=\"fotorama\">";
  $table = $idb."_photo";
  $r2 = mysql_query("SELECT * FROM $table ORDER BY id");

  while ($row = mysql_fetch_array($r2,MYSQL_ASSOC)) { 
    echo"<img src=\"http://www.basebooking.ru/upload/{$row['name']}\">";
  }
  echo"</div>";
} 
/*  PHOTOS FIGURE OUT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/  

?>	
  <div id="datepicker">
  </div>
  <div id="nobooking" class="errwrap" style="display:none">
    <div class="err">
      <span>Извините, но онлайн бронирование для данной пока не доступно.</span>
    </div>
  </div>
   <div id="sch"></div>
<?php print(clears($conditionsOut)); ?>
  
  <input type="hidden" id="selected_date"/>

<?php 
$eqt = $idb."_equip";

for ($i=1; $i <= $r['komn'];$i++) {
  $equip = mysql_query("SELECT * FROM $eqt WHERE id='$i'");   
  $equip = mysql_fetch_array($equip);

  $roomName = "";

  if ($equip['name'] != "") {
    $roomName = $equip['name'];
  } else {
    $roomName = $i;
  }

  echo "<div class=\"base_odd roomInfo\">
  ";
  if (!(empty($equip['guitar']) and empty($equip['bass']) and empty($equip['drum']) and empty($equip['line']) and empty($equip['extra']) and empty($equip['price']))) {
    echo"<div class=\"equip_header\">Оборудование комнаты ".$roomName."</div>
    <table>";
    if (!empty($equip['price'])) { 
      $equip['price'] = clears($equip['price']);
      echo"<tr><td>Цены:</td><td>{$equip['price']}</td></tr>";
    }

    if (!empty($equip['guitar'])) {
      $equip['guitar'] = clears($equip['guitar']);
      echo"<tr><td>Гитара:</td><td>{$equip['guitar']}</td></tr>";
    }
    if (!empty($equip['bass'])) { 
      $equip['bass'] = clears($equip['bass']);
      echo"<tr><td>Бас Гитара:</td><td>{$equip['bass']}</td></tr>";}
    if (!empty($equip['drum'])) { 
      $equip['drum'] = clears($equip['drum']);
      echo"<tr><td>Ударная Установка:</td><td>{$equip['drum']}</td></tr>";
    }
    if (!empty($equip['line'])) { 
      $equip['line'] = clears($equip['line']);
      echo"<tr><td>Вокальная Линия:</td><td>{$equip['line']}</td></tr>";
    }
    if (!empty($equip['extra'])) { 
      $equip['extra'] = clears($equip['extra']);
      echo"<tr><td>Дополнительно:</td><td>{$equip['extra']}</td></tr>";
    }

    

    echo"</table>";
  } else {
    echo"<span>Оборудование комнаты ".$roomName." еще не добавлено</span>";
  }
  echo"</div>";
} 

?>
  </div><!--box1-->

  <div id="box2">
   <div id="inf">
   <div class="header1">Контактная информация</div><br />
  <div class="space"></div>
  <?php  echo "<table cellspacing=\"0\" cellpdding=\"0\" border=\"0\">
  <tr><td>Город:</td><td><a href=\"http://www.basebooking.ru/search.php?town={$r['town']}\">{$r['town']}</a></td></tr>
  ";
  
  if (!empty($r['station'])) { 
    $r['station'] = clears($r['station']); 
    echo"<tr><td>Ст. Метро:</td><td><a href=\"http://www.basebooking.ru/search.php?station={$r['station']}\">{$r['station']}</a></td></tr>";
  }

  if (!empty($r['adress'])) {
    $r['adress'] = clears($r['adress']); 
    echo"<tr><td>Адрес:</td><td><a href=\"http://www.basebooking.ru/search.php?search={$r['adress']}\">{$r['adress']}</a></td></tr>";
  }

  if ($r['type']=="Репетиционная база"){

  	 echo"<tr><td>Тип:</td><td><a href=\"http://www.basebooking.ru/search.php?t1=1\">{$r['type']}</a></td></tr>";
  } else if ($r['type']=="Студия") {

  	 echo"<tr><td>Тип:</td><td><a href=\"http://www.basebooking.ru/search.php?t2=1\">{$r['type']}</a></td></tr>";
  } else {

  	echo"<tr><td>Тип:</td><td><a href=\"http://www.basebooking.ru/search.php\">{$r['type']}</a></td></tr>";	
  }
 
  if (!empty($r['website'])) {
  
    $href = clears($r['website']);
    $r['website'] = formatLinkForHumans($r['website']); 

    echo"<tr><td>Вебсайт:</td><td><a href=\"{$href}\">";
    
    if (strlen( $r['website'])>=30) {
      $r['website'] = mb_strcut($r['website'], 0, 27,'UTF-8')."...";
    } 
    echo $r['website']."</a></td></tr>";
  }

  if (!empty($r['vk'])) {
    $href = clears($r['vk']);
    $r['vk'] = formatLinkForHumans($r['vk']);  
    echo"<tr><td>В Контакте:</td><td><a href=\"http://{$href}\">{$r['vk']}</a></td></tr>";
  }
  if (!empty($r['phone'])) {
    $r['phone'] = clears($r['phone']);  
    echo"<tr><td>Контактный телефон:</td><td>{$r['phone']}</td></tr>";
  }

  echo"</table>";
  if (!empty($r['descript'])) {
    echo"<div class=\"header1\">О {$bn}</div><br>
     <table cellspacing=\"0\" cellpdding=\"0\" border=\"0\" style=\"font-size:9pt;\">";
    if (!empty($r['descript'])) {
      $r['descript'] = clears($r['descript']);  
      echo"<tr><td>{$r['descript']}</td></tr>";
    }echo"</table>";
  }

   ?>
  <div class="space1"></div>
 </div>
  </div><!--box2-->
 </div><!--main-->
  <?php 
  printFooter();
  ?>
 </div><!centered>
 </body>
 </html>