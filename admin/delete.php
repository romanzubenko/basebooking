<?php session_start();
include "../utils.php";
if ($login=="superadmin"){ 
  header('Location: http://www.basebooking.ru/superadmin/');
}

if (!isset($login) || $login == ""){ 
  header('Location: http://www.basebooking.ru/enter/');
}
$nu = getBaseName($idb);

if ($_GET['y']!=1){
echo"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"> <html xmlns=\"http://www.w3.org/1999/xhtml\"> <head> <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <title>Basebooking</title> 
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/styles.css\">
<style>
#cent{width:352px; margin:0 auto;}
.header {width:330px; margin-left:15px;}
.header a:hover{color:#08c;}
.element{text-align:center;}
</style>
<body>
 <div id=\"centered\"> 
<div id=\"top\">";
printHeader();
echo"
 <div id=\"cent\"> 
<div class=\"element\" style=\"width:350px;height:120px;\">

<div class=\"header\" >Удаление базы</div>
<br />
Вы уверены что хотите удалить базу: <br>{$nu}?
<div class=\"header\" >
  <a href=\"http://www.basebooking.ru/admin/delete.php?base={$idb}&y=1\">Да</a>
  &nbsp&nbsp
  <a href=\"http://www.basebooking.ru/admin\">Нет</a>
</div>
</div></div>

</div>
</body>
</html>

";}

if (isset($_SESSION['login']) && ($_GET['y']==1))
{
$login=$_SESSION['login'];
$userid=$_SESSION['pid'];

$nov=mysql_query("SELECT pid FROM bases WHERE `id`='$idb'");
$nov=mysql_fetch_array($nov);
$pid2=$nov['pid'];

//delete tables
$cal=$idb."_cal";
$bl=$idb."_bl";
$ph=$idb."_photo";
$eq=$idb."_equip";
$book=$idb."_booking";
$table=$idb."_photo";

$rez=mysql_query("SELECT * from $table ORDER BY id"); 
while ($row=mysql_fetch_array($rez,MYSQL_ASSOC)) {
	$file="../upload/".$row['name']; unlink($file);
}

$r2=mysql_query("DROP TABLE `$cal`");
$r3=mysql_query("DROP TABLE `$bl`");
$r5=mysql_query("DROP TABLE `$ph`");
$r6=mysql_query("DROP TABLE `$eq`");
$r7=mysql_query("DROP TABLE `$book`");
//delete tables

//delete from bases
$r=mysql_query("DELETE FROM bases WHERE id='$idb'");
//delete from transfer
$roh=mysql_query("DELETE FROM transfer WHERE id='$idb'");

//delete from user
if ($_SESSION['login']!='superadmin' and $pid2==$userid){ 
$mybases=mysql_query("SELECT bases FROM users WHERE `login`='$login'",$db); 
$mybases=mysql_fetch_array($mybases);
$mybases=explode(";",$mybases['bases']);
$d=array_search($idb,$mybases);
unset($mybases[$d]);
$mb=implode(";",$mybases);
$r4=mysql_query("UPDATE `b108859_wordpress`.`users` SET `bases` = '$mb' WHERE `users`.`id`='$userid';",$db);
}
//delete from user
 
//super delete
$sab=mysql_query("SELECT bases FROM users WHERE `login`='superadmin'",$db);
$sab=mysql_fetch_array($sab);
$sab=explode(";",$sab['bases']);
$d=array_search($idb,$sab);
unset($sab[$d]);
$sab=implode(";",$sab);
$sar=mysql_query("UPDATE `b108859_wordpress`.`users` SET `bases` = '$sab' WHERE `users`.`login`='superadmin' ");
//super delete

if ($r and $r2 and $r3) {echo"<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin\"></body></html>";}
else {echo"<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/\"></body></html>";}
;}
?>