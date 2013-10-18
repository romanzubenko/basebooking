<?php session_start();
include "../utils.php";
$login = $_SESSION['login'];

if ($login=="superadmin"){ 
  header('Location: http://www.basebooking.ru/superadmin/');
}

if (!isset($login) || $login == ""){ 
  header('Location: http://www.basebooking.ru/enter/');
}
function loadBookings($idb,$past) {
  $bookings=mysql_query("SELECT * FROM {$idb}_booking WHERE past='$past'");
  $bookings1 = array();
  $i = 0;
  While($row=mysql_fetch_array($bookings)) {   
    $dtemp=explode(".",$row['date']);
    $bookings1[$i][0] = $row['start']+10000*$dtemp[0]+1000000*$dtemp[1]+100000000*$dtemp[2];  //sort index
    $bookings1[$i]['date'] = $row['date'];
    $bookings1[$i]['time'] = formatTime($row['start'])." - ".formatTime($row['end']);
    $bookings1[$i]['room'] = $row['room'];
    $bookings1[$i]['vkid'] = $row['vkid'];
    $bookings1[$i]['name'] = $row['name'];
    $bookings1[$i]['lastname'] = $row['lastname'];
    $bookings1[$i]['phone'] = $row['phone'];
    $bookings1[$i]['band'] = $row['band'];
    $bookings1[$i]['ind'] = $row['date'].",".$row['start'].",".$row['end'].",".$row['room'].",".$row['vkid'].",".$row['price'].",".$row['name']." ".$row['lastname']; // ind for js
    $bookings1[$i]['done'] = $row['done'];
    $bookings1[$i]['accept'] = $row['accept'];
    $bookings1[$i]['admin'] = $row['admin'];
    $bookings1[$i]['price'] = $row['price']." руб";    
    $i++;
  }

  return $bookings1;
}

function bubble($bookings, $direct){
  $count=count($bookings);
  $per=false;
  if ($direct == 0) {
    While ($per == false){
      $per=true;
      for ($i = 0; $i < $count-1; $i++){
        if ($bookings[$i][0] > $bookings[$i+1][0]){
          $temp           = $bookings[$i];
		      $bookings[$i]   = $bookings[$i+1];
		      $bookings[$i+1] = $temp;
		      $per=false;
        }
      }
    }
  } else if ($direct == 1){
    While ($per==false){
      $per=true;
        for ($i = 0; $i < $count-1 ; $i++){
          if ($bookings[$i][0] < $bookings[$i+1][0]){
            $temp           =$bookings[$i];
		        $bookings[$i]   = $bookings[$i+1];
		        $bookings[$i+1] = $temp;
		        $per=false;
          }
        }
      }	
    }
  return $bookings;
}
function bOut($bookings,$past,$accept){
  $parity = 0;

  While (isset($bookings[$parity][0])) {
    $done  = $bookings[$parity]['done'];
    $ac    = $bookings[$parity]['accept'];
    $admin = $bookings[$parity]['admin'];
    $user  = "";

    $bookings[$parity]['band'] = clears($bookings[$parity]['band']);
    $bookings[$parity]['phone'] = clears($bookings[$parity]['phone']);
    $bookings[$parity]['name'] = clears($bookings[$parity]['name']);
    $bookings[$parity]['lastname'] = clears($bookings[$parity]['lastname']);



    if ($admin || $bookings[$parity]['vkid'] == 0) {
      $user = $bookings[$parity]['name'];
    } else {
      $user = "<a href=\"http://www.vkontakte.ru/id{$bookings[$parity]['vkid']}\">".$bookings[$parity]['name']." ".$bookings[$parity]['lastname']."</a>";
    }
    if ($parity %2 == 0)  {
      echo"<div class=\"booking_even\" id =\"".$bookings[$parity]['ind']."\">";
    } else {
      echo"<div class=\"booking_odd\"  id =\"".$bookings[$parity]['ind']."\">";
    }
    


      echo"

      <div class=\"ind\" style=\"display:none\">{$bookings[$parity]['ind']}</div>
      <div class=\"confirmation\"></div>
      <div class=\"main_booking\">
      <span class=\"binfo\">
      
        <span class=\"binfoc1\">{$bookings[$parity]['date']}</span>
        <span class=\"binfoc2\">{$bookings[$parity]['time']} </span>
        <span class=\"binfoc3\">{$bookings[$parity]['room']} комната</span>
        <span class=\"binfoc4\">$user</span>
      </span>
     
      <div class=\"buttons\">
      
      ";
      
      if ($accept && !$past && ($ac == 0)) {
        echo "
          <span class=\"accept\" style=\"color:#18A308\">Одобрить<br />заявку</span>
          <span class=\"notaccept\">Отменить<br />заявку</span>";
      } else if ($accept && !$past && ($ac == 1)) {
        echo "
          <span class=\"approved\">Заявка<br />одобрена</span>
          <span class=\"delete\">Отменить<br />бронирование</span>";
      } else if ($past && $done) {
      echo "
          <span class=\"done\">Группа<br /> не пришла?</span>";
      } else if (!$accept && !$past ) {
      echo "
        <span class=\"delete\">Отменить бронирование</span>";   
      } else if (!$done && $past) {
      echo "
         <span class=\"notcome\">Группа<br /> не пришла</span>";  
      }
      
      echo"
      <span class=\"contact\" style=\"color:#08c\">Контактная информация</span>
      </div> 
      </div>


      <div class=\"contacts\">
        <span class=\"binfo\">
          <span class=\"binfoc1\">{$bookings[$parity]['band']}</span>
          <span class=\"binfoc2\">{$bookings[$parity]['phone']} </span>
          
          <span class=\"binfoc4\">$user</span>
        </span>
        <div class=\"buttons\">
          <span class=\"back\">Обратно к<br />бронированию</span>
        </div>
      </div>


      </div>";
      $parity++;
  }  

  

  
  if ($parity == 0 && !$p){
  	echo"<div  class=\"booking_even\" style=\"height:136px;\"><div class=\"nothing\">Текущих бронирований пока еще нет</div></div>";
  } else if ($parity == 0 && $p){
  	echo"<div  class=\"booking_even\" style=\"height:136px;\"><div class=\"nothing\">Здесь будут отображены прошедшие бронирования</div></div>";
  }
  
}
  
 



  $mybases=mysql_query("SELECT bases from users where login='$login'",$db); 
  $mybases=mysql_fetch_array($mybases);
  $mybase=$mybases['bases'];
  $idb=$mybase;
  clearBooking($idb,$db);

  $rr = mysql_query("SELECT * from bases where id='$idb'");
  $rr = mysql_fetch_array($rr);
   if ($rr['type']==1) {$rr['type']="Репетиционная база";}
   if ($rr['type']==2) {$rr['type']="Студия";}
   if ($rr['type']==3) {$rr['type']="Репетиционная база и студия";}
   
$rr['name'] = clears($rr['name']);
   if (strlen($load['adress'])>=120) {
  $load['adress']=mb_strcut($load['adress'], 0, 117,'UTF-8')."...";
}

 //load schedule
 $temp=mysql_query("SELECT * from {$idb}_schedule");
 $sch=array(0=>array('rooms'=>0));
 $j=0;
 $script="sch=new Array();";
 if ($temp){
   While ($row=mysql_fetch_array($temp,MYSQL_ASSOC)){
	 $sch[$j]=$row;
	 $i=0;    
	 $script.="sch[{$sch[$j]['rooms']}]=[\"{$sch[$j]['d1']}\",\"{$sch[$j]['d2']}\",\"{$sch[$j]['d3']}\",\"{$sch[$j]['d4']}\",\"{$sch[$j]['d5']}\",\"{$sch[$j]['d6']}\",\"{$sch[$j]['d7']}\"];";
	 $j++;
   }
 }
 //load schedule
//load bookings
function bookarr($k,$idb){
$bookarr = "bookings = [];";
$i=0;
For ($j = 1; $j <= $k; $j++){
$temp="";
  $bookings=mysql_query("SELECT * FROM {$idb}_booking WHERE past='0' and room='$j'");
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

return $bookarr;
}






$today=10000*date("Y")+100*date("n")+date("j");
 // edit booking settings
 if ($_GET['change_settings'] == 1){
   $booking      =  check($_POST['booking']);	
   $deadline     = check($_POST['deadline']);	
   $timezone     = check($_POST['timezone']);	
   $max          = check($_POST['max']);	
   $maxPrime     = check($_POST['max_new']);	
   $emailNotify  = check($_POST['emailNotify']);
   $accept  = check($_POST['bookingtype']);

   $r = mysql_query("UPDATE bases SET `deadline`='$deadline',`timezone`='$timezone',`max`='$max',`maxPrime`='$maxPrime',`booking`='$booking',`accept`='$accept',`emailNotify`='$emailNotify'  WHERE id='$idb'");
   
   if ($r) {
    header('Location: http://www.basebooking.ru/admin/index.php?settings=on&win=true');
   } else {
    header('Location: http://www.basebooking.ru/admin/index.php?settings=on&fail=sadButTrue');
   	 
   }
 	
 	
 }
 // edit booking settings end

 $ncount = notesCountOut(notificationCount($idb));

 

echo "
<!DOCTYPE html>
<html lang=\"ru\">
<head> 

<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> 

<title>Basebooking - Моя страница</title> 
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/styles.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/adminStyles.css\">
<link rel=\"shortcut icon\"href=\"http://basebooking.ru/favicon.ico\" />
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery-1.6.1.min.js\"></script> 
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery.ui.core.js\"></script>
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery.ui.datepicker.js\"></script> 
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/schedules.js\"></script> 
 <script type=\"text/javascript\">
 function resizeBody(){
  var tempHeight = $(\"#box2\").css(\"height\"),
  tempHeight1 = $(\"#box1\").css(\"height\");

  tempHeight= Number(tempHeight.substring(0,tempHeight.length-2));
  tempHeight1= Number(tempHeight1.substring(0,tempHeight1.length-2));
  
  if (tempHeight>tempHeight1){
    $(\"#main\").css(\"height\",tempHeight+19+\"px\");
  } else {
    $(\"#main\").css(\"height\",tempHeight1+10+\"px\");
  }
}
 $(window).load(function () {
 resizeBody();
 $(\"#button_1\").addClass(\"active\");
 

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


function initial(button, inhtml) {
  var parent = $(button).parent().parent().parent(), // booking_{odd/even}
  confirmation = $(parent).children()[1],
  main = $(parent).children()[2];

  $(confirmation).html(inhtml);
  $(confirmation).animate({\"margin-top\": \"0px\"},100);
  $(main).animate({\"margin-top\": \"41px\"},100);
}



$(\".delete\").live(\"click\", function(event){
 var a =  \"<div>Отменить бронирование? <span class=\\\"yes9\\\">Да</span> &nbsp&nbsp<span class=\\\"cancel\\\">Нет</span></div><div class=\\\"qtype\\\">0</div> \";
  initial(this, a);
})

$(\".done\").live(\"click\", function(event){
 var a =  \"<div>Отметить что группа не пришла? <span class=\\\"yes9\\\">Да</span> &nbsp&nbsp<span class=\\\"cancel\\\">Нет</span></div><div class=\\\"qtype\\\">3</div> \";
  initial(this, a);
})

$(\".accept\").live(\"click\", function(event){
 var a =  \"<div>Одобрить заявку? <span class=\\\"yes9\\\">Да</span> &nbsp&nbsp<span class=\\\"cancel\\\">Нет</span></div><div class=\\\"qtype\\\">5</div> \";
  initial(this, a);
})

$(\".notaccept\").live(\"click\", function(event){
 var a =  \"<div>Отменить заявку? <span class=\\\"yes9\\\">Да</span> &nbsp&nbsp<span class=\\\"cancel\\\">Нет</span></div><div class=\\\"qtype\\\">6</div> \";
  initial(this, a);
})


$(\".cancel\").live(\"click\", function(event){
  var parent = $(this).parent().parent().parent(),
  main_booking = $(parent).children()[2],
  confirmation = $(parent).children()[1];
  $(confirmation).animate({\"margin-top\": \"-41px\"},100);
  $(main_booking).animate({\"margin-top\": \"0px\"},100);
  
})


$(\".no\").live(\"click\", function(event){
  $(this).parent().parent().parent().remove(); 
})

$(\".contact\").live(\"click\", function(){
  var confirmation = $(this).parent().parent().parent().children()[1],
  book = $(this).parent().parent().parent().children()[2],
  contacts = $(this).parent().parent().parent().children()[3];
  
  $(confirmation).animate({\"margin-top\": \"-82px\"},100);
  $(book).animate({\"margin-top\": \"-41px\"},100);
  $(contacts).animate({\"margin-top\": \"-11px\"},100);
  

});

$(\".back\").live(\"click\", function(){
  var confirmation = $(this).parent().parent().parent().children()[1],
  book = $(this).parent().parent().parent().children()[2],
  contacts = $(this).parent().parent().parent().children()[3];
  
  $(confirmation).animate({\"margin-top\": \"-41px\"},100);
  $(book).animate({\"margin-top\": \"0px\"},100);
  $(contacts).animate({\"margin-top\": \"41px\"},100);

});



$(\".yes9\").live(\"click\", function(event){
  var ind = $(this).parent().parent().parent().children()[0].innerHTML,
  parent = $(this).parent().parent().parent(),
  confirmation = $(this).parent().parent().parent().children()[1],
  type = $(this).parent().parent().children()[1].innerHTML;
  
/* type
  0 - cancel
  1 - add BL
  2 - add debt
  3 - notcome
  4 - delete photo
  5 - accept
  6 - notaccept
*/


   MakeURequest(ind,parent,type,confirmation);
})



// BL query
$(\".no1\").live(\"click\", function(event){
  ind = $(this).parent().parent().parent().children()[0].innerHTML;
  parent = $(this).parent().parent();
  name = ind.split(\",\")[3];
  vkid = ind.split(\",\")[4];
  \$(parent).html(\"<span>Добавить \"+name+\" в черный список? <span class=\\\"yes1\\\">Да</span> <span class=\\\"no\\\">Нет</span></span>\");
})

// delete photo
$(\".f8\").live(\"click\", function(event){
  parent = $(this).parent();
  $(parent).html(\"Вы уверены что хотите удалить фотографию? <span class=\\\"yes4\\\">Да</span> &nbsp&nbsp<span class=\\\"cancel1\\\">Нет</span> \");
  $(parent).css(\"width\",\"340px\");
  $(parent).css(\"margin-left\",\"190px\");
})

//delete photo no
$(\".cancel1\").live(\"click\", function(event){
  parent = $(this).parent();
  $(parent).html(\"<span class=\\\"f8\\\">Удалить фотографию</span>\");
  $(parent).css(\"width\",\"200px\");
  $(parent).css(\"margin-left\",\"280px\");

})

//delete photo yes
$(\".yes4\").live(\"click\", function(event){
  ind=$(this).parent().parent().children()[0].innerHTML;

  numTemp = ind.split(\"_\");
  num = numTemp[1].split(\".\")[0];
  parent = $(this).parent().parent(); // booking_(even/odd)
  MakeRequestDP(num, parent);
})

$(\".saveSettings1\").live(\"click\", function(){
  submit();
})

";
echo "today=".$today.";";  
           $k=$rr['komn'];
		   echo $script;
		   echo"k={$k};";
         echo  bookarr($k,$idb);

print("roomNames = ");
printRoomsNames($idb);
print(";");

echo"
resize=1;
</script> 

<script type=\"text/javascript\" src=\"http://basebooking.ru/js/adminbooking.js\"></script>
</head>
<body>
<div id=\"centered\"> ";
printHeader();
echo"
<div class=\"space\"></div> 
<div class=\"space\"></div> 
<div id=\"basename\"><a href=\"http://www.basebooking.ru/base/{$rr['name']}\">{$rr['name']}
<div class=\"des6\">перейти на страницу базы</div>
</a></div>
 <div id=\"main\">  
 <div id=\"box1\">         
	 <div id=\"admin_menu\">
	 <ul>
	 <li><a href=\"http://www.basebooking.ru/admin\">Мой кабинет</a></li>
	 <li><a href=\"http://www.basebooking.ru/admin/index.php?notifications=on\">Уведомления{$ncount}</a></li>
	 <li><a href=\"index.php?settings=on\">Бронирование</a></li>
	 <li><a href=\"http://www.basebooking.ru/admin/index.php?lists=on\">Списки музыкантов</a></li>
	 <li><a href=\"http://www.basebooking.ru/admin/index.php?add_sch=on\">Расписания</a></li>
	 <li><a href=\"http://www.basebooking.ru/admin/index.php?change=on\">Редактировать базу</a></li>
   <li><a href=\"http://www.basebooking.ru/admin/index.php?accSett=on\">Настройка аккаунта</a></li>
   <li><a href=\"http://www.basebooking.ru/admin/delete.php\">Удалить базу</a></li>
	 </ul>
	 </div><!admin_menu>       
     </div>
<!box1>            
 <div id=\"box2\">";
if (empty($_GET)) {

//loading bookings
  $bookings1 = loadBookings($idb,0);
  $bookings2 = loadBookings($idb,1);
  $jsbooking = makeJavaScriptArray($bookings1);


//loading bookings end

 echo"
 <script>var experiment = $jsbooking </script>

 <div id=\"head_buttons\" class=\"three\">";
 if ($bl_active==1){ echo"<div class=\"header1\">Добавить $bl_name в черный список?</div>";}
 echo"
 <div id=\"button_1\">Текушие бронирования</div><div id=\"button_2\">Забронировать</div><div id=\"button_3\">История бронирований</div></div>
 <div class=\"elemento\" >
   <div id=\"el_1\">";
 bOut($bookings1,0,$rr['accept']);
   echo"</div>
   <div id=\"el_2\">
 <div id=\"datepicker\"></div>
 <div id=\"sch\"> </div>
   </div>
   <div id=\"el_3\">
";
  
  $bookings2 = array_reverse($bookings2);
  bOut($bookings2,1,$rr['accept']);
 echo"
   
   </div></div>
   <form method=\"POST\" action=\"
   \" id=\"form\">
   <input name=\"room\" type=\"hidden\">
   <input name=\"time\" type=\"hidden\">
   <input name=\"date\" type=\"hidden\">
   </form>
   
";
 
}
     
//change		 
 if ($_GET['change'] == "on") {

 	$r = mysql_query("SELECT * FROM bases WHERE id = '$idb'");
 	$r = mysql_fetch_array($r);

  $r['town'] = clears($r['town']);
  $r['station'] = clears($r['station']);
  $r['adress'] = clears($r['adress']);
  $r['how'] = clears($r['how']);
  $r['descript'] = clears($r['descript']);
  $r['vk'] = clears($r['vk']);
  $r['website'] = clears($r['website']);
  $r['phone'] = clears($r['phone']);
  $r['vk'] = formatLinkForHumans($r['vk']);
  $r['website'] = formatLinkForHumans($r['website']);

  echo"
<script>
$(window).load(function() {
   $(\"img\").each(function(i) { 
    if ($(this).height() !== 20) {
      max_size = 80;

      if ($(this).height() < $(this).width()) {
        var w = max_size;
        var h = Math.ceil($(this).height() / $(this).width() * max_size);
        $(this).css({ height: h, width: w, display : \"inline\"  });
      } else {
        var h = max_size;
        var w = Math.ceil($(this).height() / $(this).width() * max_size);
        $(this).css({ height: h, width: w, display : \"inline\"  });
      }
      
    } 
  })
$(\"table\").css(\"float\",\"none\");
      
      $(\".namechange\").die(\"click\");
        $(\".namechange\").live(\"click\",function(){
            var name =  $('input[name*=\"namechange\"]').val();
            if (name.length < 2) {
              overlay(1,\"Имя базы должно быть минимум две буквы!\");
              setTimeout(function() {
                overlay(2,\"\");
              },1700);
              return false;
            } else{ 
              nameRequest(name);
            }
        })
     



})
</script>";



  if ($_GET['act']==1) {
    print("<div class=\"header1\">Изменения успешно сохранены</div><div class=\"space\" style=\"height:10px\"></div>");
   
  }

  echo"<div id=\"head_buttons\" class=\"three\">";
  


echo "
<div id=\"button_1\">Контактная информация</div><div id=\"button_2\">Оборудование комнат</div><div id=\"button_3\">Фотографии</div></div>

<div class=\"elemento\" >
   <form action=\"change_base.php\" method=\"POST\">
   <div id=\"el_1\">
<div class=\"settingsBlock\">  
   <div class=\"setheader\">Сменить название</div>
   <div class=\"descript\">
    <div style=\"padding-top: 5px\">Новое название:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</div>
   </div>

   <div class=\"inputs\">
    <div><input type=\"text\" name=\"namechange\" class=\"textinput\" value=\"".$rr['name']."\" style=\"width:141px\" />
    </div>
    </div>
</div>
<div class=\"settingsBlock\" style=\"height:66px\">  
  <div class=\"button saveSettings namechange\" style=\"border-bottom: 1px solid #D1D1D1\">
       <span>Изменить название</span>
  </div>  
</div>

<div class=\"settingsBlock\">
  <div class=\"setheader\">Контактная информация</div>
  <div class=\"space\"></div>
    <table>
    <tr>
      <td>Тип:</td>
      <td>
        <select name=\"type\">
        <option "; if ($r['type']==1) {echo "selected";} echo" value=\"1\">Репетиционная база</option>
        <option "; if ($r['type']==2) {echo "selected";} echo" value=\"2\">Студия</option>
        <option "; if ($r['type']==3) {echo "selected";} echo" value=\"3\">Репетиционная база и Студия</option>
       </select>

      </td>
    </tr>

    <tr><td>Количество комнат:</td>
    <td><select name=\"komn\" ><option disabled>0</option>
        <option "; if ($r['komn']==1) {echo "selected";} echo" value=\"1\">1</option>
        <option "; if ($r['komn']==2) {echo "selected";} echo" value=\"2\">2</option>
        <option "; if ($r['komn']==3) {echo "selected";} echo" value=\"3\">3</option>
        <option "; if ($r['komn']==4) {echo "selected";} echo" value=\"4\">4</option>
        <option "; if ($r['komn']==5) {echo "selected";} echo" value=\"5\">5</option>
        <option "; if ($r['komn']==6) {echo "selected";} echo" value=\"6\">6</option>
        <option "; if ($r['komn']==7) {echo "selected";} echo" value=\"7\">7</option>
        <option "; if ($r['komn']==8) {echo "selected";} echo" value=\"8\">8</option>
        <option "; if ($r['komn']==9) {echo "selected";} echo" value=\"9\">9</option>
        <option "; if ($r['komn']==10) {echo "selected";} echo" value=\"10\">10</option>
        <option "; if ($r['komn']==11) {echo "selected";} echo" value=\"11\">11</option>
        <option "; if ($r['komn']==12) {echo "selected";} echo" value=\"12\">12</option>
        <option "; if ($r['komn']==13) {echo "selected";} echo" value=\"13\">13</option>
        <option "; if ($r['komn']==14) {echo "selected";} echo" value=\"14\">14</option>
        <option "; if ($r['komn']==15) {echo "selected";} echo" value=\"15\">15</option>
        <option "; if ($r['komn']==16) {echo "selected";} echo" value=\"16\">16</option>
        <option "; if ($r['komn']==17) {echo "selected";} echo" value=\"17\">17</option>
        <option "; if ($r['komn']==18) {echo "selected";} echo" value=\"18\">18</option>
        <option "; if ($r['komn']==19) {echo "selected";} echo" value=\"19\">19</option>
        <option "; if ($r['komn']==20) {echo "selected";} echo" value=\"20\">20</option>
      </select></td></tr>

    <tr><td>Город:</td><td> <textarea name=\"town\" >{$r['town']}</textarea></td></tr>
    <tr><td>Ст. Метро:</td><td><textarea name=\"station\" >{$r['station']}</textarea></td></tr>
    <tr><td>Адрес:</td><td><textarea name=\"adress\">{$r['adress']}</textarea></td></tr>
    <tr><td>Как добраться:</td><td><textarea name=\"how\">{$r['how']}</textarea></td></tr>
    <tr><td>Описание:</td><td><textarea name=\"descript\">{$r['descript']}</textarea>
    </td></tr>
    <tr><td>Вконтакте</td><td> <textarea name=\"vk\">{$r['vk']}</textarea></td></tr>
    <tr><td>Вебсайт:</td><td><textarea name=\"website\" >{$r['website']}</textarea></td></tr>
    <tr><td>Телефон:</td><td><textarea name=\"phone\" >{$r['phone']}</textarea></td></tr>
    </table> 
    
  
  <div class=\"space\"></div>
</div>

   

<div class=\"settingsBlock\" style=\"height:66px\">  
  <div class=\"button saveSettings1\" style=\"border-bottom: 1px solid #D1D1D1\">
       <span>Сохранить изменения</span>
  </div>  
</div>
<br></div>";
$komn = $r['komn'];

echo"<div id=\"el_2\">";
for ($i = 1; $i <= $komn; $i++) {
	$table = $idb."_equip";
	$equip = mysql_query("SELECT * FROM $table WHERE id='$i'"); 
	$equip = mysql_fetch_array($equip);

  if ($equip['name'] == "") {
    $roomaName =  "№".$i;
  } else {
    $roomaName =  $equip['name'];
  }

	echo "
	<table><div class=\"header1\"> Оборудование в комнате ".$roomaName."</div>
	<div class=\"space\"></div>
  <tr><td>Название команты</td><td><td><textarea name=\"name{$i}\">{$equip['name']}</textarea></td></tr>
  <tr><td>Цены комнаты:</td><td><td><textarea name=\"price{$i}\">{$equip['price']}</textarea></td></tr>
	<tr><td>Гитара:</td><td><td><textarea name=\"guitar{$i}\">{$equip['guitar']}</textarea></td></tr>
	<tr><td>Бас Гитара:</td><td><td><textarea name=\"bass{$i}\">{$equip['bass']}</textarea></td></tr>
	<tr><td>Ударная Установка:</td><td><td><textarea name=\"drum{$i}\">{$equip['drum']}</textarea></td></tr>
	<tr><td>Вокальная Линия:</td><td><td><textarea name=\"line{$i}\">{$equip['line']}</textarea></td></tr>
	<tr><td>Дополнительно:</td><td><td><textarea name=\"extra{$i}\">{$equip['extra']}</textarea></td></tr>
	</table><div class=\"space\"></div><div class=\"space\" style=\"
    border-bottom: 1px solid #d1d1d1\"></div>";
}

$ph = mysql_query("SELECT * FROM {$idb}_photo");
$photos = "";
$parity = 0;
while ($row = mysql_fetch_array($ph)) {
	$parity++;
	$n = $row['name'];
	if ($parity % 2 != 0) {
	  $photos .= "<div class=\"booking_even\" style=\"height:120px\">
	    <div style=\"display:none\">$n</div>
	    <span class=\"delphoto\"><span class=\"f8\">Удалить фотографию</span> </span>
	  	<img class=\"photoEdit\"  src=\"http://www.basebooking.ru/upload/$n\"> 
	  </div>";
	} else {
      $photos .= "<div class=\"booking_odd\" style=\"height:120px\">
       <div style=\"display:none\">$n</div>
       <span class=\"delphoto\"><span class=\"f8\">Удалить фотографию</span> </span>
      	<img class=\"photoEdit\"  src=\"http://www.basebooking.ru/upload/$n\"> 
      </div>";
	}
} 

if ($photos == "") {
	$photos = "
	<div class=\"booking_even\" style=\"height:140px\">
      <div class=\"nothing\">Загруженных фотографий пока нет</div>
    </div>";
}

echo"
<div class=\"settingsBlock\" style=\"height:66px\">  
  <div class=\"button saveSettings1\" style=\"border-bottom: 1px solid #D1D1D1\">
       <span>Сохранить изменения</span>
  </div>  
</div>

</div>

<div id=\"el_3\">



  <div class=\"booking_odd\" style=\"height:136px\">
     <div class=\"header1\" style=\"color:#111;font-size:13pt;margin-top:8px;\"> Загрузка фотографий</div>

    <iframe src=\"http://www.basebooking.ru/admin/photo.php\" style=\"width:400px;height:160px;margin-left:300px;margin-top:20px;\"></iframe>
  </div>
  $photos
</div>
</div><!elemento>";
};



if ($_GET['add_sch']=="on") {
	
  $schedules = schedulesout($idb);
  $schJSARR = makeJavaScriptArray($schedules);
  $rooms = mysql_query("SELECT komn FROM bases WHERE id='$idb'");
  $rooms = mysql_fetch_array($rooms,MYSQL_ASSOC);
  $rooms = $rooms['komn'];

  print("
    <script>var sch1 = $schJSARR,
    rooms = {$rooms};
      
      var schedules = new scheduleFactory(sch1,$(\"#box2\"),rooms)
     </script>
  ");
}


// setings start
if ($_GET['settings'] == "on"){
 
 


	$settings = mysql_query("SELECT * from bases WHERE id='$idb'");
	$settings = mysql_fetch_array($settings);
  if ($settings['booking']) {
    $ch = "checked=\"checked\"";
  } else {
    $ch = "";
  }
  if ($settings['emailNotify']) {
    $ch4 = "checked=\"checked\""; 
  } else {
    $ch4 = "";
  }

  if ($settings['accept']) {
    $ch2 = "checked=\"checked\"";
    $ch3 = "";
  } else {
    $ch3 = "checked=\"checked\"";
    $ch2 = "";
  }
  echo"




<div id=\"head_buttons\" class=\"settings\">
<div class=\"header1\">Настройки бронирования</div></div>
  <form action=\"index.php?change_settings=1\" method=\"POST\">
  <div class=\"elemento\">
  <div id=\"el_1\">

<div class=\"settingsBlock\">
  <div class=\"setheader\">Бронирование</div>
  <div class=\"descript\">
    <div>
      Включить бронирование:
    </div>
    <div>
      Включить email уведомление:
    </div>
  </div>
  <div class=\"inputs\">
    <div>
      <input type=\"checkbox\" name=\"booking\" value=\"1\" $ch />
    </div>
  
    <div>
      <input type=\"checkbox\" name=\"emailNotify\" value=\"1\" $ch4 />
    </div>
  </div>
</div>

<div class=\"settingsBlock\">
  <div class=\"setheader\">Тип бронирования</div>
  <div class=\"descript\">
    <div>
      Заявки
    </div>
    <div style=\"padding-top: 4px\">
      Прямое бронирование
    </div>
  </div>
  <div class=\"inputs\">
    <div>
      <input type=\"radio\" name=\"bookingtype\" value=\"1\" $ch2/>
    </div>
    <div>
      <input type=\"radio\" name=\"bookingtype\" value=\"0\" $ch3/>
    </div>
  </div>
</div>

<div class=\"settingsBlock\">
  <div class=\"setheader\">Дедлайн</div>
  <div class=\"descript\">
    <div style=\"padding-top: 6px\">
      Последний срок отмены бронирования без потери денег:
    </div>
  </div>
  <div class=\"inputs\">
    <div>
      <input type=\"text\" name=\"deadline\" class=\"textinput\" style=\"width:200px\" "; if (isset($settings['deadline'])) {echo "value=\"".$settings['deadline']."\"   ";} echo"/>&nbsp&nbsp часов
    </div>
    
  </div>
</div>

<div class=\"settingsBlock\">
  <div class=\"setheader\">Ограничения</div>
  <div class=\"descript\">
    <div>
      Максимальное количество репетиций для музыкантов которые еще не были на вашей базе:
    </div>
    <div>
      Максимальное количество репетиций для музыкантов которые уже были на вашей базе:
    </div>
  </div>
  <div class=\"inputs\">
    <div>
      <input type=\"text\" class=\"textinput\"  name=\"max_new\" style=\"width:200px\" "; if (isset($settings['maxPrime'])) {echo "value=\"".$settings['maxPrime']."\"   ";} echo"/>&nbsp&nbsp репетиций
    </div>
    <div>
      <input type=\"text\" class=\"textinput\"  name=\"max\" style=\"width:200px\""; if (isset($settings['max'])) {echo "value=\"".$settings['max']."\"   ";} echo"/>&nbsp&nbsp репетиций    
    </div>
  </div>
</div>

<div class=\"settingsBlock\">
  <div class=\"setheader\">Время</div>
  <div class=\"descript\">
    <div>
      Часовой пояс:
    </div>
  </div>
  <div class=\"inputs\">
    <div>
      <select name=\"timezone\" >
        <option "; if ($settings['timezone']==4) {echo "selected";} echo" value=\"4\">+4 Москва</option>
        <option "; if ($settings['timezone']==0) {echo "selected";} echo" value=\"0\">0 Лондон</option>
        <option "; if ($settings['timezone']==1) {echo "selected";} echo" value=\"1\">+1 Париж</option>
        <option "; if ($settings['timezone']==2) {echo "selected";} echo" value=\"2\">+2 Рига, София</option>
        <option "; if ($settings['timezone']==3) {echo "selected";} echo" value=\"3\">+3 Минск</option>
        <option "; if ($settings['timezone']==5) {echo "selected";} echo" value=\"5\">+5 Западный Казахстан</option>
        <option "; if ($settings['timezone']==6) {echo "selected";} echo" value=\"6\">+6 Екатеринбург</option>
        <option "; if ($settings['timezone']==7) {echo "selected";} echo" value=\"7\">+7 Омск</option>
        <option "; if ($settings['timezone']==8) {echo "selected";} echo" value=\"8\">+8 Красноярск</option>
        <option "; if ($settings['timezone']==9) {echo "selected";} echo" value=\"9\">+9 Иркутск</option>
        <option "; if ($settings['timezone']==10) {echo "selected";} echo" value=\"10\">+10 Якутск</option>
        <option "; if ($settings['timezone']==11) {echo "selected";} echo" value=\"11\">+11 Владивосток </option>
        <option "; if ($settings['timezone']==12) {echo "selected";} echo" value=\"12\">+12 Магадан, Сахалин</option>
        <option "; if ($settings['timezone']==-1) {echo "selected";} echo" value=\"-1\">-1 Азорские острова</option>
        <option "; if ($settings['timezone']==-2) {echo "selected";} echo" value=\"-2\">-2 Среднеатлантическое время</option>
        <option "; if ($settings['timezone']==-3) {echo "selected";} echo" value=\"-3\">-3 Буэнос-Айрес</option>
        <option "; if ($settings['timezone']==-4) {echo "selected";} echo" value=\"-4\">-4 Канада</option>
        <option "; if ($settings['timezone']==-5) {echo "selected";} echo" value=\"-5\">-5 Североамериканское восточное время</option>
        <option "; if ($settings['timezone']==-6) {echo "selected";} echo" value=\"-6\">-6 Центральное время (США и Канада)</option>
        <option "; if ($settings['timezone']==-7) {echo "selected";} echo" value=\"-7\">-7 Горное время (США и Канада)</option>
        <option "; if ($settings['timezone']==-8) {echo "selected";} echo" value=\"-8\">-8 Североамериканское тихоокеанское время</option>
        <option "; if ($settings['timezone']==-9) {echo "selected";} echo" value=\"-9\">-9 Аляска</option>
        <option "; if ($settings['timezone']==-10) {echo "selected";} echo" value=\"-10\">-10 Гавайи</option>
        <option "; if ($settings['timezone']==-11) {echo "selected";} echo" value=\"-11\">-11 Самоа</option>
        <option "; if ($settings['timezone']==-12) {echo "selected";} echo" value=\"-12\">-12 К сожалению здесь нет обитаемых территорий</option>
      </select>
    </div>
  </div>
</div>
  
<div class=\"settingsBlock\" style=\"height:66px\">  
  <div class=\"button saveSettings1\" style=\"border-bottom: 1px solid #D1D1D1\">
       <span>Сохранить изменения</span>
  </div>  
</div>
 
  </div>
  </div>
  </form>

";
	
}

if ($_GET['lists']=="on"){
$clients=array();
	$allclients = mysql_query("SELECT * FROM {$idb}_list");
	 While($row=mysql_fetch_array($allclients)){
	 	$clients[$i][0] = $row['vkid'];
	 	$clients[$i][1] = $row['name'];
	 	$clients[$i][2] = $row['bookings'];
	 	$clients[$i][3] = $row['done'];
	 	$clients[$i][4] = $row['debt'];
	 	$clients[$i][5] = $row['bl']; 	
	 	$i++;
	 }

echo"
<script>var list = new ListManipulation();</script>
<div id=\"head_buttons\" class=\"two\">
   <div id=\"button_1\">Все музыканты</div>
   <div id=\"button_2\">Черный список</div>
</div>

<div class=\"elemento\" >
";
$list = listOut($idb);
$bLList = blOut($idb);
echo "$list $bLList
</div>
	";
	
}

if ($_GET['notifications'] == "on") {
	$notes = getBaseNotifications($idb);
  $notes = array_reverse($notes);

	$delete = mysql_query("UPDATE {$idb}_notification SET `seen`='1' WHERE seen='0'");
	$news = newOut($notes);
  $cancels = normalOut($notes,2,2);
  $books = normalOut($notes,1,1);
    
    if ($news == "") {
    	$news = "<div class=\"booking_even\" style=\"height:136px\"><div class=\"nothing\">Новых уведомлений нет</div></div>";
    }
	echo"

<div id=\"head_buttons\" class=\"three\">
   <div id=\"button_1\">Новые</div>
   <div id=\"button_2\">Бронирования</div>
   <div id=\"button_3\">Отмены</div>
</div>
  <div class=\"elemento notifications\">
    <div id=\"el_1\" >
	{$news}
    </div>
    <div id=\"el_2\">
	{$books}
    </div>
    <div id=\"el_3\">
    {$cancels} 
    </div>
  </div>
  
 ";

}

if ($_GET['accSett'] == "on") {
  print("
    <script>
      $(window).load(function() {
        $(\".saveSettings1\").die(\"click\");
        $(\".saveSettings1\").live(\"click\",function(){
          var p1 = $('input[name$=\"password1\"]').val(),
          p2 = $('input[name$=\"password2\"]').val(),
          p3 = $('input[name$=\"password3\"]').val();

          if (p2 !== p3) {
            overlay(1,\"Пароли не совпадают!\");
            setTimeout(function() {
              overlay(2,\"\");
            },1000);
            return false;
          }

          MakePassRequest(p1,p2);
          
        })
      })
    </script>
  ");

  print("
    <div id=\"head_buttons\" class=\"two\">
      <div id=\"button_1\">Изменить пароль</div>
      <div id=\"button_2\">Удалить аккаунт</div>
    </div>
  ");

  print("
    <div class=\"elemento notifications\">
      <div id=\"el_1\" > 
        <div class=\"settingsBlock\">
        <div class=\"setheader\">Смена пароля</div>
        <div class=\"descript\">
              <div style=\"padding-top:6px\">
                Настоящий пароль:
              </div>
              <div style=\"padding-top:13px\">
                Новый пароль:
              </div>
              <div style=\"padding-top:13px\">
                Повторите новый пароль:
              </div>
            </div>
        <div class=\"inputs\">
        <div>
          <input type=\"password\" name=\"password1\" class=\"textinput\" style=\"width:200px\" />
        </div>
        <div>
          <input type=\"password\" name=\"password2\" class=\"textinput\" style=\"width:200px\" />
        </div>
        <div>
          <input type=\"password\" name=\"password3\" class=\"textinput\" style=\"width:200px\"/>
        </div>
        
        </div>
        </div>

        <div class=\"settingsBlock\" style=\"height:66px\">  
          <div class=\"button saveSettings1\" style=\"border-bottom: 1px solid #D1D1D1\">
            <span>Сменить пароль</span>
          </div>  
        </div>
      </div>

      <div id=\"el_2\">
        <div class=\"settingsBlock\">
          <div class=\"setheader\"><a href=\"http://www.basebooking.ru/admin/delete.php\">Удалить базу</a></div>
        </div>
      </div>
    </div>
  ");


}

if ($_GET['pay'] == "on") {
  $d = mysql_query("SELECT exp FROM bases WHERE id='$idb'");
  $d = mysql_fetch_array($d);
  $d = $d['exp'] - time();
  $days = intval($d/86400);
  print("
    <script>
      $(window).load(function() {
        $(\".pay\").die(\"click\");
        $(\".pay\").live(\"click\",function(){
           overlay(1,\"Оплата будет доступна за 14 дней до окончания пробного периода!\");
            setTimeout(function() {
              overlay(2,\"\");
            },1700);
        })
      })
    </script>
  ");
  
  print("
  <div class=\"bp\">  
    <div class=\"p1\">
      У Вас осталось <strong>$days</strong> дней бесплатного пробного пользования  
    </div>
    <div class=\"p2\">
      Испытайте полнофункциональную версию нашей системы, предложите музыкантам бронировать онлайн, наслаждайтесь простотой работы!
    </div>
  </div>
  <div class=\"p3\">
    Тарифы и цены:
  </div>


  <div class=\"plan\" style=\"margin-top:16px\">
    <div class=\"des1\">1 месяц</div>
    <div class=\"des2\">500 рублей</div>
    <input style=\"width: 172px;height: 30px;\" class=\"button pay\" type=\"submit\" name=\"submit\" value=\"Выбрать\">
  </div>

  <div class=\"plan\">
    <div class=\"des1\">3 месяца</div>
    <div class=\"des2\">1200 рублей</div>
    <input style=\"width: 172px;height: 30px;\" class=\"button pay\" type=\"submit\" name=\"submit\" value=\"Выбрать\">
  </div>

  <div class=\"plan\">
    <div class=\"des1\">6 месяцев</div>
    <div class=\"des2\">2500 рублей</div>
    <input style=\"width: 172px;height: 30px;\" class=\"button pay\" type=\"submit\" name=\"submit\" value=\"Выбрать\">
  </div>

  <div class=\"plan\" style=\"padding-bottom: 23px\">
    <div class=\"des1\">12 месяцев</div>
    <div class=\"des2\">5000 рублей</div>
    <input style=\"width: 172px;height: 30px;\" class=\"button pay\" type=\"submit\" name=\"submit\" value=\"Выбрать\">
  </div>
  ");
}



 echo"</div>   
 </div><!main>   
 ";
 printFooter();
 echo"
 </div><! centered> 
 </body> 
 </html>"; ?>