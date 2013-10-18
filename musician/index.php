<?php session_start();
include "../utils.php";

$vkid = $_SESSION['vkid'];
$name = mysql_query("SELECT * from mus_users WHERE vkid='$vkid'");
$name = mysql_fetch_array($name);
$name = $name['name']." ".$name['lastname'];

if (isset($_SESSION['login']) && $_SESSION['login'] != "") {
  header('Location: http://www.basebooking.ru/admin/');
} 

if ($_SESSION['login'] == "superadmin"){
  header('Location: http://www.basebooking.ru/superadmin/');
}


function bubble($bookings, $direct){
  $count=count($bookings);
  $per=false;
if ($direct==1) {
  While ($per==false){
    $per=true;
    for ($i=0;$i<$count-1;$i++){
      if ($bookings[$i][0]>$bookings[$i+1][0]){
      	$temp=$bookings[$i];
		$bookings[$i]=$bookings[$i+1];
		$bookings[$i+1]=$temp;
		$per=false;
      }
	}

  }
} else {
  While ($per==false){
    $per=true;
    for ($i=0;$i<$count-1;$i++){
      if ($bookings[$i][0]<$bookings[$i+1][0]){
      	$temp=$bookings[$i];
		$bookings[$i]=$bookings[$i+1];
		$bookings[$i+1]=$temp;
		$per=false;
      }
	}
  }
	
}
  return $bookings;
}

function bOut($bookings,$p){
  $parity=0;
  While (isset($bookings[$parity][0])) {
    $status = getStatus($bookings[$parity][6]);


    $bookings[$parity][1] = clears($bookings[$parity][1]);
    $bookings[$parity][2] = clears($bookings[$parity][2]);
    $bookings[$parity][3] = clears($bookings[$parity][3]);
    $bookings[$parity][4] = clears($bookings[$parity][4]);
    $bookings[$parity][5] = clears($bookings[$parity][5]);
    $bookings[$parity][6] = clears($bookings[$parity][6]);


    if ($parity%2 == 0)  {
      echo"<div class=\"booking_even\" id =\"{$bookings[$parity][7]}\">";
    } else {
      echo"<div class=\"booking_odd\"  id =\"{$bookings[$parity][7]}\">";
    }
      echo"
      <div class=\"ind\" style=\"display:none\">{$bookings[$parity][5]}</div>
      <div class=\"confirmation\"></div>
      <div class=\"main_booking\">
      <span class=\"binfo\">
          <span class=\"binfoc1\">{$bookings[$parity][1]}</span>
          <span class=\"binfoc2\">{$bookings[$parity][2]}</span>
          <span class=\"binfoc3\">{$bookings[$parity][3]} комната</span>
          <span class=\"binfoc4\">{$bookings[$parity][7]} руб&nbsp&nbsp<a href=\"http://www.basebooking.ru/base.php?name={$bookings[$parity][4]}\">{$bookings[$parity][4]}</a></span></span>";
      if ($p) {
         
         echo"<div class=\"buttons\"> 
         $status
         <span class=\"delete\">Отменить бронирование</span></div>";
      } 
      echo"</div></div>";
      $parity++;
  }  
  if ($parity==0 && $p){
  	echo"<div  class=\"booking_even\" style=\"height:136px;\"><div class=\"nothing\">Текущих бронирований пока еще нет</div></div>";
  } else if ($parity == 0 && !$p){
  	echo"<div  class=\"booking_even\" style=\"height:136px;\"><div class=\"nothing\">Здесь будут отображены прошедшие бронирования</div></div>";
  }
}
  

if (isset($_SESSION['vkid'])) {

//delete booking
if (isset($_POST["room"]) and isset($_POST["time"]) and isset($_POST["date"])){

	$room=$_POST["room"];
  $time=$_POST["time"];
  $date=$_POST["date"];
  $r=mysql_query("SELECT vkid FROM {$idb}_booking  WHERE date='$date' and room='$room' and time='$time' ");
  $r=mysql_fetch_array($r);
  $vkid= $r['vkid'];
  $del = mysql_query("DELETE FROM {$idb}_booking   WHERE date='$date' and room='$room' and time='$time' ");
  $del2= mysql_query("DELETE FROM {$vkid}_history  WHERE date='$date' and room='$room' and time='$time' ");
  
  $numsChange=mysql_query("SELECT * FROM mus_users WHERE vkid='$vkid'",$db);
  $numsChange=mysql_fetch_array($numsChange);
  $curr=$numsChange['curr_book_num']-1;
  $r2=mysql_query("UPDATE mus_users SET `curr_book_num`='$curr' WHERE vkid='$vkid'");
  
  $bl_active=1;
  $bl_vkid=$vkid;
  $bl_name=$numsChange['name']." ".$numsChange['lastname'];
}

//delete booking


echo "
<!DOCTYPE html>
<html lang=\"ru\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <title>Basebooking - $name</title> 
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/styles.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/musstyles.css\">
<link rel=\"shortcut icon\"href=\"http://basebooking.ru/favicon.ico\" />

<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery-1.6.1.min.js\"></script> 
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/musajax.js\"></script> 
 <script type=\"text/javascript\">
 
function resizeBody(){
  tempHeight=$(\"#box2\").css(\"height\");
  tempHeight1=$(\"#box1\").css(\"height\");
  tempHeight= Number(tempHeight.substring(0,tempHeight.length-2));
  tempHeight1= Number(tempHeight1.substring(0,tempHeight1.length-2));
  
  if (tempHeight>tempHeight1){
    $(\"#main\").css(\"height\",tempHeight+19+\"px\");
  }
}
$(window).load(function () {
  resizeBody();
});
  $(function(){
$(\"#button_1\").click(function ( event ) {
  $(\"#button_1\").addClass(\"active\");
  $(\"#button_2\").removeClass(\"active\");
  $(\"#button_3\").removeClass(\"active\");
  $(\"#el_2\").hide();
  $(\"#el_3\").hide();
  $(\"#el_1\").show();
  resizeBody();
})

$(\"#button_2\").click(function ( event ) {
  $(\"#button_2\").addClass(\"active\");
  $(\"#button_1\").removeClass(\"active\");
  $(\"#button_3\").removeClass(\"active\");

  $(\"#el_1\").hide();
  $(\"#el_3\").hide();
  $(\"#el_2\").show();
  resizeBody();
})

$(\"#button_3\").click(function ( event ) {
  $(\"#button_3\").addClass(\"active\");
  $(\"#button_2\").removeClass(\"active\");
  $(\"#button_1\").removeClass(\"active\");

  $(\"#el_2\").hide();
  $(\"#el_1\").hide();
  $(\"#el_3\").show();
  resizeBody();
})
})
function submit(){
$(\"form\")[1].submit();
}


</script> 

</head>
<body>
<div id=\"centered\"> ";
printHeader();
$notecount = notesCountOut(musNotificationCount($vkid));
echo"
<div class=\"space\"></div> 
<div class=\"space\"></div> 
<div id=\"basename\">$name</div>
 <div id=\"main\">  
 <div id=\"box1\">         
	 <div id=\"admin_menu\">
	 <ul>
	 <li><a href=\"http://www.basebooking.ru/musician\">Мои Бронирования</a></li>
   <li><a href=\"index.php?notifications=on\">Мои Уведомления $notecount </a></li>";
	 //<li><a href=\"index.php?settings=on\">Мои Настройки</a></li>
	 //<li><a href=\"index.php?help=on\">Помощь</a></li>
	 echo"</ul>
	 </div><!admin_menu>       
     </div>
<!box1>            
 <div id=\"box2\">";
if (empty($_GET)) {
userClear($vkid);
//loading bookings
$bookout1="";
$bookout2="";
$i=0;
$bookings1=array();
$bookings2=array();
$bookings=mysql_query("SELECT * FROM {$vkid}_history WHERE past='0'");
  While($row=mysql_fetch_array($bookings)){
   $dtemp=explode(".",$row['date']);
   $bookings1[$i][0] = $dtemp[0]+100*$dtemp[1]+10000*$dtemp[2];
   $bookings1[$i][1] = $row['date'];
   $bookings1[$i][2] = formatTime($row['start'])." - ".formatTime($row['end']);
   $bookings1[$i][3] = $row['room'];
   $tIdb=$row['idb'];
   $bookings1[$i][4] = getBaseName($tIdb);
   $bookings1[$i][5] = $row['date'].",".$row['start'].",".$row['end'].",".$row['room'].",".$tIdb.",".$row['price'];   //ind
   $bookings1[$i][6] = $row['status'];
   $bookings1[$i][7] = $row['price'];
    $i++;
 }
$bookings1=bubble($bookings1,1);

$parity=0;
$i=0;
$bookings=mysql_query("SELECT * FROM {$vkid}_history WHERE past='1'");
 While($row=mysql_fetch_array($bookings)){
   $dtemp=explode(".",$row['date']);
   $bookings2[$i][0]=$dtemp[0]+100*$dtemp[1]+10000*$dtemp[2];
   $bookings2[$i][1]=$row['date'];
   $bookings2[$i][2]=formatTime($row['start'])." - ".formatTime($row['end']);
   $bookings2[$i][3]=$row['room'];
   $tIdb=$row['idb'];
   $bookings2[$i][4]=getBaseName($tIdb);
   $bookings2[$i][6]=$row['status'];
    $i++;
 }
$bookings2 = bubble($bookings2,2);

 echo"<div id=\"head_buttons\" class=\"two\">";
 if ($bl_active==1){ echo"<div class=\"header1\">Добавить $bl_name в черный список?</div>";}
 echo"
 <div id=\"button_1\" class=\"active\">Текушие бронирования</div><div id=\"button_3\">История бронирований</div></div>
 <div class=\"elemento\" >
   <div id=\"el_1\" style=\"width:776px\">";
 bOut($bookings1,1);
   echo"</div>
   <div id=\"el_3\" style=\"width:776px\">
";
   bOut($bookings2,0);
 echo"
   
   </div></div>
";
}

if ($_GET['notifications'] == "on") {
  $notes = getUserNotifications($vkid);
  
  $delete = mysql_query("UPDATE {$vkid}_notification SET `seen`='1' WHERE seen='0'");
  
  $notesNew = userNotificationsOut($vkid,true,$notes);
  $notes = array_reverse($notes);
  $notesOld = userNotificationsOut($vkid,false,$notes);

  if ($notesNew == "") {
    $notesNew = "<div class=\"booking_even\" style=\"height:136px\"><div class=\"nothing\">Новых уведомлений нет</div></div>";
  }

  if ($notesOld == "") {
    $notesOld = "<div class=\"booking_even\" style=\"height:136px\"><div class=\"nothing\">Новых уведомлений нет</div></div>";
  }

  echo"
    <div id=\"head_buttons\" class=\"two\">
      <div id=\"button_1\" class=\"active\">Новые</div>
      <div id=\"button_2\">Старые</div>
    </div>
    <div class=\"elemento notifications\" style=\"width:776px\">
      <div id=\"el_1\" style=\"width:776px\" >
        {$notesNew}
      </div>
      <div id=\"el_2\">
        {$notesOld}
      </div>
  </div>";
}


 
 echo"
 </div>   
 </div><!main>
"; printFooter(); 
echo"
 </div><! centered> 
 </body> 
 </html>";} ?>