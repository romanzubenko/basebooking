<?php session_start();
include "../utils.php";
$login = $_SESSION['login'];

if (isset($_SESSION['login']) && $_SESSION['login']!= "") {

if ($_POST['type']==1) {
	$type = 1;
}
if ($_POST['type']==2) {
	$type = 2;
}
if ($_POST['type']==3) {
	$type = 3;
}




$descript = check($_POST['descript']);
$adress   = check($_POST['adress']);
$town     = check($_POST['town']);
$website  = check($_POST['website']);
$phone    = check($_POST['phone']);
$vk       = check($_POST['vk']);
$station  = check($_POST['station']);
$komn     = check($_POST['komn']);
$how      = check($_POST['how']);


/*	FORMAT WEBSITE FOR FUTURE PROPER USE IN HREF  */
$website = formatLink($website);
$vk = formatLink($vk);
/*	FORMAT WEBSITE FOR FUTURE PROPER USE IN HREF  */


$prevkomn = mysql_query("SELECT komn FROM bases WHERE id='$idb'");
$prevkomn = mysql_fetch_array($prevkomn);
$prevkomn = $prevkomn['komn'];

if ($komn > $prevkomn){
  for ($i = $prevkomn + 1; $i <= $komn; $i++){
		$sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_schedule` (`rooms`) VALUES ('$i')");
		$sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_equip` (`id`) VALUES ('$i')");
  }
}else if ($komn < $prevkomn) {
	for ($i = $prevkomn; $i > $komn; $i--) {
		$sc = mysql_query("DELETE FROM {$idb}_schedule WHERE rooms='$i'");
		$sc = mysql_query("DELETE FROM {$idb}_equip WHERE id='$i'");
	}
}


For ($i=1; $i <= $komn; $i++) {
	$b = "name".$i;
	$name = check($_POST[$b]);

	$b = "guitar".$i;
	$guitar=check($_POST[$b]);

	$b = "price".$i;
	$price = check($_POST[$b]);

	$b = "bass".$i;
	$bass = check($_POST[$b]);
	$b = "drum".$i;
	$drum = check($_POST[$b]);
	$b = "line".$i;
	$line = check($_POST[$b]);
	$b = "extra".$i;
	$extra = check($_POST[$b]);
	
	$table = $idb."_equip";

	$rd=mysql_query("SELECT * FROM $table WHERE id='$i'");
	$rd=mysql_fetch_array($rd);
	if (!isset($rd['guitar'])) {
		$rdd=mysql_query("INSERT INTO `b108859_wordpress`.`$table` (`name`,`guitar`,`bass`,`drum`,`line`,`extra`,`price`) VALUES ('$name','$guitar','$bass','$drum','$line','$extra','$price')"); 
	}
	if (isset($rd['guitar'])) { 
		$rdd=mysql_query("UPDATE `b108859_wordpress`.`$table` SET `price`='$price',`name`='$name', `guitar`='$guitar',`bass`='$bass',`drum`='$drum',`line`='$line',`extra`='$extra' WHERE $table.`id`=$i;");
	}
} 

$rs = mysql_query("UPDATE `b108859_wordpress`.`bases` SET `descript` = '$descript',`adress`='$adress',`town`='$town',`town`='$town',`station`='$station',`phone`='$phone',`type`='$type',`how`='$how',`website`='$website',`vk`='$vk', `komn`='$komn' WHERE `bases`.`id`='$idb'",$db);

	header('Location: http://www.basebooking.ru/admin/index.php?change=on&act=1');
} else {
	header('Location: http://www.basebooking.ru/admin/');
} 

?>
