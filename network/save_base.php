<?php session_start();
function check($str) {
$str = addslashes($str);
$str = htmlspecialchars($str);
$str = trim($str);
$str = escapeshellcmd($str);
return $str;}

function rand_str($length = 10, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
    $chars_length = (strlen($chars) - 1);
    $string = $chars{mt_rand(0, $chars_length)};
     for ($i = 1; $i < $length; $i = strlen($string))
    {
        $r = $chars{rand(0, $chars_length)};
        if ($r != $string{$i - 1}) $string .=  $r;
    }
    return $string;
}
if (isset($_SESSION['login']) and isset($_POST['basename'])) {
$db = mysql_connect ("78.108.84.245","u108859","base256us");
mysql_set_charset('utf8',$db); 
mysql_select_db ("b108859_wordpress",$db);

$name=check($_POST['basename']);
$descript=check($_POST['descript']);
$adress=check($_POST['adress']);
$town=check($_POST['town']);
$station=check($_POST['station']);
$komn=check($_POST['komn']);


if ($_POST['type']=="1") {$type=1;}
if ($_POST['type']=="2") {$type=2;}
if ($_POST['type']=="3") {$type=3;}
if (!isset($name) or !isset($adress) or !isset($town) or !isset($type) or empty($name) or empty($adress) or empty($town) or empty($type)) 
{exit ("<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin/index.php?add=on\"></body></html>");}
                  
$result1 = mysql_query("SELECT id FROM bases WHERE name='$name'",$db);
$myrow = mysql_fetch_array($result1);
if (!empty($myrow['id'])) {exit("База с названием \"{$name}\" уже зарегистрирована, пожалуйста выберите другое название либо свяжитесь с администрацией, если вы считаете что кто-то неправомерно зарегистрировал базу с вашим названием.");}
if (empty($myrow['id'])) {
$pid=$_SESSION['pid'];
$website=check($_POST['website']);
$phone=check($_POST['phone']);
$vk=check($_POST['vk']);
if ($_SESSION['login']!="superadmin") {
$result = mysql_query("INSERT into bases (name,type,komn,descript,town,station,adress,pid,vk,phone,website) VALUES ('$name','$type','$komn','$descript','$town','$station','$adress','$pid','$vk','$phone','$website')",$db);}
else {$result = mysql_query("INSERT into bases (name,type,komn,descript,town,station,adress,vk,phone,website) VALUES ('$name','$type','$komn','$descript','$town','$station','$adress','$vk','$phone','$website')");}
}

if (!$result) {exit ("<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru\"></body></html>");}
$result1 = mysql_query("SELECT id FROM bases WHERE name='$name'",$db);
$myrow = mysql_fetch_array($result1);
                    
$result2 = mysql_query(
"CREATE TABLE `b108859_wordpress`.`{$myrow['id']}_cal` (
`date` INT NOT NULL ,
`time` INT NOT NULL ,
`name_vk` VARCHAR( 15 ) NOT NULL,
`id_vk` INT NOT NULL,
`tel` INT NOT NULL)");
$result3 = mysql_query("
CREATE TABLE `b108859_wordpress`.`{$myrow['id']}_bl` (
`name_vk` VARCHAR( 15 ) NOT NULL,
`id_vk` INT NOT NULL,
`tel` INT NOT NULL)"); 
$result4 = mysql_query("
CREATE TABLE `b108859_wordpress`.`{$myrow['id']}_photo` (
`id` INT NOT NULL AUTO_INCREMENT ,
PRIMARY KEY ( `id` ) ,
`name` VARCHAR( 12 ) NOT NULL)"); 
$result5 = mysql_query("
CREATE TABLE `b108859_wordpress`.`{$myrow['id']}_equip` (
`id` INT NOT NULL AUTO_INCREMENT ,
PRIMARY KEY ( `id` ) ,
`guitar` TEXT NOT NULL ,
`bass` TEXT NOT NULL ,
`drum` TEXT NOT NULL ,
`line` TEXT NOT NULL ,
`extra` TEXT NOT NULL
)"); 
$result6 = mysql_query("
CREATE TABLE `b108859_wordpress`.`{$myrow['id']}_booking` (
`id` INT NOT NULL AUTO_INCREMENT ,
PRIMARY KEY ( `id` ) ,
`guitar` TEXT NOT NULL ,
`bass` TEXT NOT NULL ,
`drum` TEXT NOT NULL ,
`line` TEXT NOT NULL ,
`extra` TEXT NOT NULL
)"); 
if ($result and $result2 and $result3) {
$mb=mysql_query("Select id from bases where name='$name'",$db);
$mb = mysql_fetch_array($mb);
$rrr=$mb['id'];
$idb=$mb['id'];
$base_id=$mb['id'].";";
$login=$_SESSION['login'];
$prev_bases= mysql_query("SELECT bases from users where login='$login'");
$prev_bases=mysql_fetch_array($prev_bases);
$base_id=$base_id.$prev_bases['bases'];
$r=mysql_query("UPDATE `b108859_wordpress`.`users` SET `bases` = '$base_id' WHERE `users`.`login`='$login';",$db);
if ($_SESSION['login']!="superadmin") {
$sa=mysql_query("SELECT bases from users where login='superadmin'");
$sa=mysql_fetch_array($sa);
$base_id=$mb['id'].";";
$base_id=$base_id.$sa['bases'];
$sar=mysql_query("UPDATE `b108859_wordpress`.`users` SET `bases` = '$base_id' WHERE `users`.`login`='superadmin';",$db);}
if ($_SESSION['login']=="superadmin") {
$secret=rand_str();
$link=rand_str();
$transfer=mysql_query("INSERT INTO transfer (id,link,secret) VALUES ('$idb','$link','$secret')");
exit("<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin/superadmin.php?change={$rrr}&v=2\"></body></html>");}
echo "<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin/index.php?change={$rrr}&v=2\"></body></html>";};} else {echo "<html><head></head><body></body></html>";} 
?>
