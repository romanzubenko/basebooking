<?php session_start();
if ($_SESSION['login']=="superadmin") {exit("<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin/superadmin.php\"></body></html>");}
if (isset($_SESSION['login'])) {$auth=1;} else {echo "<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/enter\"></body></html>";}

if ($auth==1) {echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"> <html xmlns=\"http://www.w3.org/1999/xhtml\"> <head> <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <title>Basebooking - Моя страница</title> 
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/styles.css\">
<style>
#box1{width:160px;margin-left: 6px;height:auto;}
#box2{width:775px;float:left;}
.elemento, header1, table, form {float:left;}
.elemento {height:auto;}
iframe {float:left;width:775px;height:370px;border:1px solid #111;}
</style>
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery-1.6.1.min.js\"></script> 
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery.ui.core.js\"></script>
<script type=\"text/javascript\" src=\"http://basebooking.ru/js/jquery.ui.datepicker.js\"></script> 
 <script type=\"text/javascript\">
(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k=\"setTimeout\",j=\"resize\",d=j+\"-special-event\",b=\"delay\",f=\"throttleWindow\";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);
 $(function(){
    $(\"#datepicker\").datepicker({
      monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
      dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
      dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'], firstDay: 1, prevText: '', nextText: ''
    });

iff=0;
sec=0;
  $(\"#add_sch\").click(function (event) {
  iff++;
  
   $(\"<iframe>\", { id: \"f_\"+iff, \"src\": \"http://www.basebooking.ru/admin/sch.php?count=\"+iff, \"scrolling\": \"no\"}).appendTo(\"#box2\")
 $(\"iframe\").load(function(){ 
 sec++;
 if (sec<2) {alert(\"ahh\");\$(\"<script>\", {\"type\": \"text/javascript\", \"text\": \"$(\\\"iframe\\\").contents().find(\\\"body\\\").resize(function (event){aid=\\\"f_\\\"\+$(this).get(0).id;alert(aid);he=\$(this).css(\\\"height\\\");alert(he);he+=20; alert(he); \$(\\\"#\\\"+aid).css(\\\"height\\\",\$(this).css(\\\"height\\\")+=20\\\"})\"}).appendTo(\"#box2\");
   
  }})});
 
  });
  
</script> 

</head>
<body>
<div id=\"centered\"> 
<div id=\"top\">";
if (isset($_SESSION['login'])) {echo "<div id=\"admin_panel\"><ul title=\"none\"><a href=\"http://www.basebooking.ru/admin\">Моя страница</a><li><a href=\"http://www.basebooking.ru/exit.php\">Выйти</a></div>";} else {echo "<div id=\"enter\"><a href=\"http://www.basebooking.ru/enter\"> Вход для партнеров</a></div>";}
echo"</div>
<div id=\"i4\"><div id=\"topmenu\"><ul title=\"none\"> <li><a href=\"http://www.basebooking.ru/rating\">Рейтинг</a></li> <li><a href=\"http://www.basebooking.ru/search.php\">Поиск</a></li> <li><a href=\"http://www.basebooking.ru/special\">Скидки</a></li> <li><a href=\"http://www.basebooking.ru/about\">О проекте</a></li> <li><a href=\"http://www.basebooking.ru/partners\">Партнерам</a></li> <li ><form action=\"\"><input type=\"text\" name=\"search\"/></form></li></ul></div><!topmenu></div><div class=\"topmenu\"></div>
 <br><br>
<div id=\"main\">             
<div id=\"box1\">         
 <div id=\"admin_menu\">
 <ul>
 <li><a href=\"index.php\">Мои базы</a></li>
 <li><a href=\"index.php?settings=on\">Мои Настройки</a></li>
 <li><a href=\"index.php?actions=on\">Мои акции</a></li>
 <li><a href=\"index.php?pay=on\">Оплата услуг</a></li>
  <li><a href=\"index.php?add=on\">Добавить базу</a></li>
  <li><a href=\"index.php?add_sch=on\">Добавить расписание</a></li>
 </ul>
 </div><!admin_menu>       
 </div><!box1>
 
 <div id=\"box2\">";
if (empty($_GET)) {
$db = mysql_connect ("78.108.84.245","u108859","base256us");
mysql_set_charset('utf8',$db); 
mysql_select_db ("b108859_wordpress",$db);
$login=$_SESSION['login'];

echo"<div id=\"datepicker\" style=\"width:755px; margin-left:10px;\"> </div>";

$mybases=mysql_query("SELECT bases from users where login='$login'",$db); 
$mybases=mysql_fetch_array($mybases);
$mybases=explode(";",$mybases['bases']);
$i=0;
 while (!empty($mybases[$i])) {
 $rr=mysql_query("SELECT * from bases where id='{$mybases[$i]}'");
 $rr=mysql_fetch_array($rr);
 if ($rr['type']==1) {$rr['type']="Репетиционная база";}
 if ($rr['type']==2) {$rr['type']="Студия";}
 if ($rr['type']==3) {$rr['type']="Репетиционная база и студия";}
 echo "<div class=\"element\"><div class=\"header\"><a href=\"http://www.basebooking.ru/base.php?name={$rr['name']}\">{$rr['name']}</a></div><p>{$rr['type']}<br>{$rr['town']}, ст. метро:{$rr['station']}</p><br><div class=\"delete\"><a href=\"http://www.basebooking.ru/admin/index.php?change={$rr['id']}\">Настроить</a><a href=\"http://www.basebooking.ru/admin/delete.php?base={$rr['id']}\">Удалить базу</a></div></div>";
 ++$i;} echo "<div class=\"space\"></div>";}
   
   
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
 <tr><td><span>*</span>Количество комнат:</td><td><select name=\"komn\"><option disabled>0</option><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option>
 <option value=\"4\">4</option><option value=\"5\">5</option><option value=\"6\">6</option><option value=\"7\">7</option><option value=\"8\">8</option><option value=\"9\">9</option><option value=\"10\">10</option></select></td></tr>
 <tr><td><span>*</span>Адрес:</td><td><textarea name=\"adress\" rows=\"3\"></textarea></td></tr>
 <tr><td>Описание:</td><td><textarea name=\"descript\"> </textarea></td></tr>
 </table>
 <div class=\"space\"></div><div class=\"space\"></div>
 <table><tr><td><INPUT TYPE=\"image\" SRC=\"../img/submit1.png\" HEIGHT=\"30\" WIDTH=\"120\" BORDER=\"0\" ALT=\"Submit Form\"></td></tr> </table>

 </form>  
</div> 
  </div><!element><div class=\"space\"></div> ";}
 
         
		 //change
		 
		 
		 
 if (isset($_GET['change'])) {
$db = mysql_connect ("78.108.84.245","u108859","base256us");
mysql_set_charset('utf8',$db); 
mysql_select_db("b108859_wordpress",$db);
$mybases=mysql_query("SELECT bases from users where login='$login'",$db); 

$mybases=mysql_fetch_array($mybases);
$mybases=explode(";",$mybases['bases']);
if ($_GET['act']==1) {echo"<div class=\"header1\">Изменения успешно сохранены</div>";}

if (array_search($_GET['base'],$mybases)===0) {$ss=true;}
if (array_search($_GET['base'],$mybases) or $ss ) {
$id=$_GET['change'];
  $r=mysql_query("SELECT * from bases WHERE id='$id'");
  $r=mysql_fetch_array($r);
 
echo "<div class=\"elemento\" style=\"width:745px\"><div class=\"space\"></div>
   <div class=\"header1\">Контактная информация</div>
   <div class=\"test\">
   <form action=\"change_base.php?base={$_GET['change']}\" method=\"POST\">
   <table>
   <tr><td>Город:</td><td><input type=\"text\" name=\"town\" value=\"{$r['town']}\"/></td></tr>
   <tr><td>Ст. Метро:</td><td><input type=\"text\" name=\"station\" value=\"{$r['station']}\"/></td></tr>
   <tr><td>Адрес:</td><td><textarea name=\"adress\">{$r['adress']}</textarea></td></tr>
   <tr><td>Тип:</td><td>
   <select name=\"type\">
 <option"; if ($r['type']==1) {echo "selected";} echo" value=\"1\">Репетиционная база</option>
 <option"; if ($r['type']==2) {echo "selected";} echo" value=\"2\">Студия</option>
 <option"; if ($r['type']==3) {echo "selected";} echo" value=\"3\">Репетиционная база и Студия</option>
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
   </select></td></tr>
   <tr><td>Описание:</td><td><textarea name=\"descript\">{$r['descript']}</textarea></td></tr>
   <tr><td>Вконтакте http://</td><td><input type=\"text\" name=\"vk\" value=\"{$r['vk']}\"/></td></tr>
   <tr><td>Вебсайт:</td><td><input type=\"text\" name=\"website\" value=\"{$r['website']}\"/></td></tr>
   <tr><td>Телефон:</td><td><input type=\"text\" name=\"phone\" value=\"{$r['phone']}\"/></td></tr>
</table><br><br>";
$komn=$r['komn'];
for ($i=1;$i<=$komn;$i++) {$table=$id."_equip";$equip=mysql_query("SELECT * FROM $table WHERE id='$i'"); $equip=mysql_fetch_array($equip);echo "<div class=\"space\"></div><table><div class=\"header1\"> Оборудование в комнате №{$i}</div>

<tr><td>Гитара:</td><td><td><textarea name=\"guitar{$i}\">{$equip['guitar']}</textarea></td></tr>
<tr><td>Бас Гитара:</td><td><td><textarea name=\"bass{$i}\">{$equip['bass']}</textarea></td></tr>
<tr><td>Ударная Установка:</td><td><td><textarea name=\"drum{$i}\">{$equip['drum']}</textarea></td></tr>
<tr><td>Вокальная Линия:</td><td><td><textarea name=\"line{$i}\">{$equip['line']}</textarea></td></tr>
<tr><td>Дополнительно:</td><td><td><textarea name=\"extra{$i}\">{$equip['extra']}</textarea></td></tr>
</table><div class=\"space\"></div><div class=\"space\"></div>";}
echo"
 <table><tr><td><input type=\"submit\" name=\"submit\" value=\"Сохранить изменения\" /></td></tr></table>
</form><div class=\"space\"></div>
<div class=\"header1\"> Загрузка фотографий</div>
<iframe src=\"http://www.basebooking.ru/admin/photo.php?b={$_GET['change']}\" width=\"350px\" height=\"200px\">
</iframe>
</div></div><div class=\"space\"></div>";
};}



if ($_GET['add_sch']=="on") {echo "<div id=\"add_sch\" class=\"elemento\" style=\"width:745px\">
<div class=\"space\"></div>   
 <div class=\"header1\">Добавить Расписание</div>
<div class=\"space\"></div>   
</div>



";


}
 
 echo"</div>   
 </div><!main>   
 <div id=\"bottom\">
 <table><tr><td>Basebooking 2011</td>
 <td>Социальное</td></tr></table> 
 </div> 
 </div><! centered> 
 </body> 
 </html>";} ?>