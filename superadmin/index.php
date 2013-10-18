<?php session_start();
include "../utils.php";
if ($_SESSION['login']=="superadmin") { 
	$auth=1;
} else {
	header('Location: http://www.basebooking.ru/enter');
}

if ( $auth==1 ) {echo"

<!DOCTYPE html>
<html lang=\"ru\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <title>Basebooking - Моя страница</title> 
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/styles.css\">
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery-1.6.1.min.js\"></script>
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/sa.js\"></script>
<script type=\"text/javascript\">

function getXMLHttp(){var x = false;try {x = new XMLHttpRequest();}catch(e) {try {x = new ActiveXObject(\"Microsoft.XMLHTTP\");}
catch(ex) {try {req = new ActiveXObject(\"Msxml2.XMLHTTP\");}catch(e1) {x = false;}}}return x;}

</script>
<style>
#box1{width:145px;margin-left: 6px;height:auto;}
#box2{width:790px;float:left;}
.elemento, header1, table{float:left;}
.elemento {height:auto;margin-top:1px;margin-left:7px;width:776px;}
form {width:100%;}
table { margin:0 auto;}
.test { min-height:200px; max-width:500px;margin:0 auto;}
.elemento span {color:red;}
#button_1, #button_2, #button_3 {float:left; width:250px; cursor:pointer; text-align:center;}
#button_1 {margin-left:10px;}
#el_2, #el_3 {display:none;}
.element {height: 160px;}

#head_buttons {
	width: 756px;
	padding: 10px;
	min-height: 20px;
	margin-left: 7px;
	margin-top: 1px;
	float: left;
	background-color: #FAFAFA;
	border: 1px solid #D1D1D1;
}
.booking_odd{min-height:41px; width:775px;border-bottom:1px solid #d1d1d1;float:left;background-color:#fdfdfd;}
.booking_even{min-height:41px; width:775px;border-bottom:1px solid #d1d1d1;background-color:#FAFAFA;float:left;}

.booking_even table, .booking_odd table {width:755px;margin-left:20px;height:40px;}
.booking_even table td,.booking_odd table td {width:100px;}
.booking_even table td span,.booking_odd table td span {cursor:pointer;color:#A82A2A;display:block;}

.waitSuc {
	width: 100%;
	text-align: center;
	margin-top: 11px;
}

.booking_even .nothing, .booking_odd .nothing {
  font-size:16pt;
  width:100%; 
  text-align:center; 
  margin-top:50px;
  color:#555;
  font-family: \"HelveticaNeue-Light\",\"Helvetica Neue\",Helvetica,Arial,Verdana,sans-serif;
  float:left;
}

";

$r = mysql_query("SELECT * FROM waitlist WHERE done='0'");
$count = "";
$i = 0;
while ($row = mysql_fetch_array($r)) {
	$i++;
}
if ($i > 0) {
	$count = " (".$i.")";
}

if ($_GET['v']==2) {echo "
#el_3, #el_1 {display:none;}
#el_2 {display:block;}
";}

echo"
</style>
</head>
<body>
<div id=\"centered\"> ";

printHeader();
echo"<br><br>
<div class=\"space\"></div> 
<div class=\"space\"></div> 
<div id=\"main\">             
<div id=\"box1\">         
 <div id=\"admin_menu\">
 <ul>
 <li><a href=\"index.php\">Все базы и студии</a></li>
 <li><a href=\"index.php?notifications=on\">Заявки$count</a></li>
  <li><a href=\"index.php?users=on\">Аккаунты</a></li>
 </ul>
 </div><!admin_menu>       
 </div><!box1>
 
 <div id=\"box2\">";
if (empty($_GET)) {

echo"<div class=\"element\"><br><br><div class=\"header\"><a href=\"index.php?add=on\">Добавить базу</a></div></div> ";

$i=0;

$allbases = mysql_query("SELECT * from bases");
 
 while ($rr = mysql_fetch_array($allbases)) { 

 if ($rr['type']==1) {$rr['type']="Репетиционная база";}
 if ($rr['type']==2) {$rr['type']="Студия";}
 if ($rr['type']==3) {$rr['type']="Репетиционная база и студия";}

 echo "<div class=\"element\"><div class=\"header\"><a href=\"http://www.basebooking.ru/base/{$rr['name']}\">{$rr['name']}</a></div><p>{$rr['type']}<br>{$rr['town']}, ст. метро:{$rr['station']}</p><br><div class=\"delete\">
 <a href=\"http://www.basebooking.ru/superadmin/index.php?change={$rr['id']}\">Настроить</a>&nbsp&nbsp
 <a href=\"http://www.basebooking.ru/superadmin/index.php?compose={$rr['id']}\">Письмо</a>&nbsp&nbsp
 <a href=\"http://www.basebooking.ru/superadmin/deleteBase.php?base={$rr['id']}\">Удалить базу</a>
 <br/>
  <a style=\"margin-top:10px;display:block\">www.basebooking.ru/base.php/{$rr['name']}</a>
 
 </div></div>";
 
 $i++;
} 

echo "<div class=\"element\"><div class=\"header\">Всего баз: {$i} из 600 :)</div></div><div class=\"space\"></div>";}
   
   
   //add
   
 if ($_GET['add']=="on") {echo " <div class=\"elemento\" style=\"width:745px;\">   
<div class=\"space\"></div>   
 <div class=\"header1\">Добавить Репетиционную Базу</div>
 <div class=\"space\"></div>
 <div class=\"test\">
 <form action=\"save_base.php\" method=\"post\"> 
 <table>
 <tr><td><span>*</span>Название базы:</td><td><input type=\"text\" name=\"basename\"></td></tr>
 <tr><td><span>*</span>Город: </td><td><input type=\"text\"  name=\"town\"></td></tr>
 <tr><td> Станция Метро: </td><td><input type=\"text\"  name=\"station\"></td></tr>
 <tr><td>Тип:</td><td>
 <select name=\"type\">
 <option value=\"1\">Репетиционная база</option>
 <option value=\"2\">Студия</option>
 <option value=\"3\">Репетиционная база и Студия</option>
 </select>
 <td></td></tr>
 <tr><td><span>*</span>Количество комнат:</td><td>
 <select name=\"komn\">
 <option disabled>0</option>
 <option value=\"1\">1</option>
 <option value=\"2\">2</option>
 <option value=\"3\">3</option>
 <option value=\"4\">4</option>
 <option value=\"5\">5</option>
 <option value=\"6\">6</option>
 <option value=\"7\">7</option>
 <option value=\"8\">8</option>
 <option value=\"9\">9</option>
 <option value=\"10\">10</option>
 <option value=\"11\">11</option>
 <option value=\"12\">12</option>
 <option value=\"13\">13</option>
 <option value=\"14\">14</option>
 <option value=\"15\">15</option>
 <option value=\"16\">16</option>
 <option value=\"17\">17</option>
 <option value=\"18\">18</option>
 <option value=\"19\">19</option>
 <option value=\"20\">20</option>
 </select></td></tr>
 <tr><td>Вебсайт:</td><td><input type=\"text\" name=\"website\"/></td></tr>
 <tr><td>Телефон:</td><td><input type=\"text\" name=\"phone\"/></td></tr>
 <tr><td>Вконтакте http://</td><td><input type=\"text\" name=\"vk\"/></td></tr>
 <tr><td><span>*</span>Адрес:</td><td><textarea name=\"adress\" rows=\"3\"></textarea></td></tr>
 <tr><td>Описание:</td><td><textarea name=\"descript\"> </textarea></td></tr>
 

 </table>
 <div class=\"space\"></div><div class=\"space\"></div>
 <table><tr><td><input type=\"submit\" name=\"submit\" value=\"Сохранить изменения\" /></td></tr></table>

 </form>  
</div> 
  </div><!element><div class=\"space\"></div> ";}
 
         
		 //change
		 
		 
		 
 if (isset($_GET['change'])) {

if ($_GET['act']==1) {echo"<div class=\"header1\">Изменения успешно сохранены</div>";}

if (1) {
  $id=$_GET['change'];
  $r=mysql_query("SELECT * from bases WHERE id='$id'");
  $r=mysql_fetch_array($r);
  
	echo "
	<script>
	$(function(){
	$(\"#button_1\").click(function ( event ) {
	$(\"#el_2\").hide();
	$(\"#el_3\").hide();
	$(\"#el_1\").show();
	})
	$(\"#button_2\").click(function ( event ) {
	$(\"#el_1\").hide();
	$(\"#el_3\").hide();
	$(\"#el_2\").show();
	})
	$(\"#button_3\").click(function ( event ) {
	$(\"#el_2\").hide();
	$(\"#el_1\").hide();
	$(\"#el_3\").show();
	})
	})
	</script>


	<div id=\"button_1\">Контактная информация</div><div id=\"button_2\">Оборудование комнат</div><div id=\"button_3\">Загрузка фотографий</div>

	<div class=\"elemento\" style=\"width:745px\"><div class=\"space\"></div>
   <form action=\"change_base.php?base={$_GET['change']}\" method=\"POST\">
   <div class=\"test\">
   <div id=\"el_1\">
   
   <div class=\"header1\">Контактная информация</div>
   <table>
   <tr><td>Город:</td><td><input type=\"text\" name=\"town\" value=\"{$r['town']}\"/></td></tr>
   <tr><td>Ст. Метро:</td><td><input type=\"text\" name=\"station\" value=\"{$r['station']}\"/></td></tr>
   <tr><td>Адрес:</td><td><textarea name=\"adress\">{$r['adress']}</textarea></td></tr>
   <tr><td>Тип:</td><td>
   <select name=\"type\">
 <option "; if ($r['type']==1) {echo "selected";} echo" value=\"1\">Репетиционная база</option>
 <option "; if ($r['type']==2) {echo "selected";} echo" value=\"2\">Студия</option>
 <option "; if ($r['type']==3) {echo "selected";} echo" value=\"3\">Репетиционная база и Студия</option>
   </select>
   <td></td></tr> 
   <tr><td>Количество комнат:</td><td><select name=\"komn\" ><option disabled>0</option>
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
   <tr><td>Описание:</td><td><textarea name=\"descript\">{$r['descript']}</textarea></td></tr>
   <tr><td>Вконтакте http://</td><td><input type=\"text\" name=\"vk\" value=\"{$r['vk']}\"/></td></tr>
   <tr><td>Вебсайт:</td><td><input type=\"text\" name=\"website\" value=\"{$r['website']}\"/></td></tr>
   <tr><td>Телефон:</td><td><input type=\"text\" name=\"phone\" value=\"{$r['phone']}\"/></td></tr>
	</table>
	<br><br><table><tr><td><input type=\"submit\" name=\"submit\" value=\"Сохранить изменения\" /></td></tr></table>
	<br></div>";
	$komn=$r['komn'];
		echo"<div id=\"el_2\">";
	for ($i=1;$i<=$komn;$i++) {$table=$id."_equip";$equip=mysql_query("SELECT * FROM $table WHERE id='$i'"); $equip=mysql_fetch_array($equip);echo "
	<div class=\"space\"></div><table><div class=\"header1\"> Оборудование в комнате №{$i}</div>
	<tr><td>Гитара:</td><td><td><textarea name=\"guitar{$i}\">{$equip['guitar']}</textarea></td></tr>
	<tr><td>Бас Гитара:</td><td><td><textarea name=\"bass{$i}\">{$equip['bass']}</textarea></td></tr>
	<tr><td>Ударная Установка:</td><td><td><textarea name=\"drum{$i}\">{$equip['drum']}</textarea></td></tr>
	<tr><td>Вокальная Линия:</td><td><td><textarea name=\"line{$i}\">{$equip['line']}</textarea></td></tr>
	<tr><td>Дополнительно:</td><td><td><textarea name=\"extra{$i}\">{$equip['extra']}</textarea></td></tr>
		</table><div class=\"space\"></div><div class=\"space\"></div>";}
	echo"
	 <table><tr><td><input type=\"submit\" name=\"submit\" value=\"Сохранить изменения\" /></td></tr></table>
	</div></form><div class=\"space\"></div>
	<div id=\"el_3\">
	<div class=\"header1\"> Загрузка фотографий</div>
		<iframe src=\"http://www.basebooking.ru/admin/photo.php?b={$_GET['change']}\" width=\"350px\" height=\"200px\">
		</iframe></div>
	</div><!test>
	</div><!elemento>
	<div class=\"space\"></div>";
		}
	}
 
 //compose
 $idb=$_GET['compose'];
 $row=mysql_query("SELECT * FROM transfer WHERE id='$idb'");
 $row=mysql_fetch_array($row);
 $link=$row['link'];
 $secret=$row['secret'];
 $row=mysql_query("SELECT * FROM bases WHERE id='$idb'");
 $row=mysql_fetch_array($row);
 $name=$row['name'];
 if (isset($_GET['compose'])) {
 if ($row['type']==1) {$row['type']="репетиционной базы";}
 if ($row['type']==2) {$row['type']="студии";}
 if ($row['type']==3) {$row['type']="репетиционной базы и студии";}
 echo " <div class=\"elemento\" style=\"width:745px;\">

Уважаемые администраторы {$row['type']} {$row['name']},<br /><br />

Мы рады представить бесплатный сервис по онлайн бронированию репетиционных баз и студий Basebooking.ru. Наш сайт облегчает ведение расписания, а также автоматизирует процесс бронирования, предоставляя онлайн интерфейс для администраторов и музыкантов.
 <br /><br />
Мы предлагаем полностью автоматизированную систему онлайн бронирования и электронного расписания, где вы сможете просматривать статистику бронирований баз пользователем, что может помочь Вам принять решение по одобрению заявки на репетицию/запись.
 <br /><br />
Музыкантам не нужно будет платить ни лишние деньги, даже не требуется специальная регистрация – для бронирования реп базы требуется лишь профиль «В Контакте». Это значит, что вы всегда сможете проверить персональные данные музыканта.
<br /><br />
В целях безопасности, мы вручную приглашаем только те базы и студии, которые ведут рабочую деятельность. Чтобы попробовать наш сервис в действии мы сделали для вас уникальную ссылку обеспечивающую безопасность при регистрации - 
<br />
 www.basebooking.ru/transfer.php?link={$link}
 <br /> и укажите следующий секретный код:<br />
{$secret}
<br /><br />
Регистрация на нашем сайте, а также все услуги абсолютно бесплатны.
<br /><br />
Узнать больше о нашем сервисе вы можете на нашем сайте
www.basebooking.ru или в нашей группе В Контакте<br />
http://vk.com/basebooking
<br /><br />
С уважением,<br />
Создатели Basebooking.ru,<br />
Роман и Максим<br />
С нами вы можете связаться по этим ссылкам:<br />
http://vk.com/roman.zubenko<br />
http://vk.com/maxim.vashchenko


 </div>
 ";

}
if (isset($_GET['schedule'])) {echo " <div class=\"elemento\" style=\"width:745px;\">
  Дорогие администраторы базы {$name}.<br><br>
  Чтобы получить доступ к администрированию вашей базы на <a href=\"http://www.basebooking.ru/base/{$name}\">Basebooking.ru</a><br>
  Необходимо перейти по <a href=\"http://www.basebooking.ru/transfer.php?link={$link}\">Ссылке</a><br>
  И ввести следующий код: {$secret}
  <br><br>
  Приятного дня!
 </div>
 ";
} 

if ($_GET['notifications'] == "on") {
	echo"<div id=\"head_buttons\" style=\"height:40px\">
	  <div class=\"header1\">Заявки на добавление</div>
	</div>";
	
	
	$waitlist = "";
	$parity = 0;
	$r = mysql_query("SELECT * FROM waitlist");
	
	while ($row = mysql_fetch_array($r)) {
	  $name    = $row['name'];
	  $vk      = $row['vk']; 
	  $phone   = $row['phone'];
	  $email   = $row['email']; 
	  $website = $row['website'];
	  $ind = $row['id'];
	 
	  $parity++; 
	  if ($parity % 2 != 0) {
	    $waitlist .= "
	    <div class=\"booking_odd\">
	    	<div class=\"ind\" style=\"display:none\">$ind</div>

	        <table>
	   		  <tr>
	   		  	<td>$name</td>
	   		  	<td>$vk</td>
	   		  	<td>$phone</td>
	   		  	<td>$email</td>
	   		  	<td>$website</td>
	   		  	<td><span class=\"submit\">Одобрить<span/></td>
	   		  	<td><span class=\"denial\">Отклонить<span/></td>
	  		  </tr>
	  		</table>
	    </div>";
	  } else {
      $waitlist .= "
        <div class=\"booking_even\">
	    	<div class=\"ind\" style=\"display:none\">$n</div>
	        <table>
	   		  <tr>
	   		  	<td>$name</td>
	   		  	<td>$vk</td>
	   		  	<td>$phone</td>
	   		  	<td>$email</td>
	   		  	<td>$website</td>
	   		  	<td><span class=\"submit\">Одобрить<span/></td>
	   		  	<td><span class=\"denial\">Отклонить<span/></td>
	  		  </tr>
	  		</table>
	    </div>";
	  }	
	}
	
	if ($waitlist == "") {
		$waitlist = "<div class=\"booking_even\" style=\"height: 129px;\"><div class=\"nothing\">Заявок нет</div></div> ";
	}
	
	echo "<div class=\"elemento\" style=\"width:776px\">
	<div class=\"booking_even\">
	<table>
	   		  <tr>
	   		  	<td>Название</td>
	   		  	<td>Вконтакте</td>
	   		  	<td>Телефон</td>
	   		  	<td>Емэйл</td>
	   		  	<td>Вебсайт</td>
	   		  	<td>&nbsp</td>
	   		  	<td>&nbsp</td>
	  		  </tr>
	  		</table>
	</div>
	$waitlist</div>";
	
}
 
 if ($_GET['users'] == "on") {
	echo"<div id=\"head_buttons\" style=\"height:40px\">
	  <div class=\"header1\">Заявки на добавление</div>
	</div>";
 	echo "<div class=\"elemento\" style=\"width:776px\">";
 	print(usersOut());
 	echo"</div>";

 }
 
 echo"
 </div><!box2>   
 </div><!main>   
 <div id=\"bottom\">
 <table><tr><td>Basebooking 2011</td>
 <td>Социальное</td></tr></table> 
 </div> <!bottom>
 </div><! centered> 
 </body> 
 </html>";} ?>