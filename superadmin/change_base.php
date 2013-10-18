<?php session_start();
include "../utils.php";

if ($_SESSION['login'] != 'superadmin') {
	header('Location: http://www.basebooking.ru/');
}


	


if (isset($_GET['base'])) {

	$idb = $_GET['base'];

	if ($_POST['type']==1) {$type=1;}
	if ($_POST['type']==2) {$type=2;}
	if ($_POST['type']==3) {$type=3;}

	$descript=check($_POST['descript']);
	$adress=check($_POST['adress']);
	$town=check($_POST['town']);
	$website=check($_POST['website']);
	$phone=check($_POST['phone']);
	$vk=check($_POST['vk']);
	$station=check($_POST['station']);
	$komn=check($_POST['komn']);
	
	for ($i = 1; $i <= $komn; $i++) {
		$b="guitar".$i;
		$guitar=check($_POST[$b]);
		$b="bass".$i;
		$bass=check($_POST[$b]);
		$b="drum".$i;
		$drum=check($_POST[$b]);
		$b="line".$i;
		$line=check($_POST[$b]);
		$b="extra".$i;
		$extra=check($_POST[$b]);
		$table=$idb."_equip";
		$rd=mysql_query("SELECT * FROM $table WHERE id='$i'");
		$rd=mysql_fetch_array($rd);
		if (!isset($rd['guitar'])) {$rdd=mysql_query("INSERT into $table (guitar,bass,drum,line,extra) VALUES ('$guitar','$bass','$drum','$line','$extra')"); }
		if (isset($rd['guitar'])) { $rdd=mysql_query("UPDATE `b108859_wordpress`.`$table` SET `guitar`='$guitar',`bass`='$bass',`drum`='$drum',`line`='$line',`extra`='$extra' WHERE $table.`id`=$i;");}
	} 

	$rs = mysql_query("UPDATE `b108859_wordpress`.`bases` SET `descript` = '$descript',`adress`='$adress',`town`='$town',`town`='$town',`station`='$station',`phone`='$phone',`type`='$type',`website`='$website',`vk`='$vk', `komn`='$komn' WHERE `bases`.`id`='$idb'",$db);
} 


	header('Location: http://www.basebooking.ru/superadmin/index.php?change='.$idb.'&act=1');

?>
