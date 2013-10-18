<?php session_start();
include "../utils.php";

function reptime($da,$rep){
$dayp['err']=0;
$dayp['sth']="start_h_day".$da."_".$rep;
if (isset($_POST[$dayp['sth']]) and is_numeric($_POST[$dayp['sth']])) {$dayp['sth']=$_POST[$dayp['sth']];} else {$dayp['err']=1;}
$dayp['stm']='start_m_day'.$da.'_'.$rep;
if (isset($_POST[$dayp['stm']]) and is_numeric($_POST[$dayp['stm']])) {$dayp['stm']=$_POST[$dayp['stm']];}else {$dayp['err']=1;}
$dayp['enh']="end_h_day".$da."_".$rep;
if (isset($_POST[$dayp['enh']]) and is_numeric($_POST[$dayp['enh']])) {$dayp['enh']=$_POST[$dayp['enh']];} else {$dayp['err']=1;}
$dayp['enm']='end_m_day'.$da.'_'.$rep;
if (isset($_POST[$dayp['enm']]) and is_numeric($_POST[$dayp['enm']])) {$dayp['enm']=$_POST[$dayp['enm']];}else {$dayp['err']=1;}
$dayp['pr']='price_day'.$da.'_'.$rep;
if (isset($_POST[$dayp['pr']]) and is_numeric($_POST[$dayp['pr']])) {$dayp['pr']=$_POST[$dayp['pr']];}else {$dayp['err']=1;}
return $dayp;}

$pid=$_SESSION['pid'];
$idb=mysql_query("SELECT bases FROM users WHERE id='$pid'");
$idb=mysql_fetch_array($idb);
$idb=trim($idb['bases']);
$r=mysql_query("SELECT komn FROM bases where id='$idb'");
$r=mysql_fetch_array($r);
$komn=$r['komn'];
For ($i=1;$i<=$komn;$i++){
$query="komn".$i;
if ($_POST[$query]==1) {$rooms[$i]=1;} else{$rooms[$i]=0;}
}

if (isset($_SESSION['login'])){$auth=1;} else {exit ("<html><head><script language=\"javascript\">window.location=\"http://www.basebooking.ru/enter/\"</script></head></html>");}
if ($auth==1) {
$prevsch=array();
$r=mysql_query("SELECT rooms FROM {$idb}_schedule"); 
$i=0;
While ($row=mysql_fetch_array($r)){
$prevsch[$i]=$row['rooms'];
$i++;
}

$da=1;
$rep=1;
$schedule=array(0=>"");
$day=reptime($da,$rep);
while ((isset($day['sth']) and isset($day['enh']) and $day['err']!=1) or $da<=7)
      {
      while (!empty($day['sth']) and !empty($day['enh']) and $day['err']!=1) {
      	$tempTime1=$day['sth']*60+$day['stm'];
      	$tempTime2= $day['enh']*60+$day['enm'];
		$schedule[$da]=$schedule[$da].$tempTime1.",";
		$schedule[$da]=$schedule[$da].$tempTime2.",";
		$schedule[$da]=$schedule[$da].$day['pr'].";";
		$rep++;$day=reptime($da,$rep);
      }
 $rep=1;$da++;$day=reptime($da,$rep);
}
//MySQLing
For ($i=1;$i<=$komn;$i++){
    if ($rooms[$i]==1) {
    $j=0;
    for ($k=0;isset($prevsch[$k]);$k++){
      if ($prevsch[$k]==$i){$j=1;}
    }
      if ($j==0){
         $rr=mysql_query("INSERT into {$idb}_schedule (rooms,d1,d2,d3,d4,d5,d6,d7) VALUES ('$i','$schedule[1]','$schedule[2]','$schedule[3]','$schedule[4]','$schedule[5]','$schedule[6]','$schedule[7]')",$db);
      }   else {
           $table=$idb."_schedule";
           $rr=mysql_query("UPDATE `b108859_wordpress`.`$table` SET `d1`='$schedule[1]',`d2`='$schedule[2]',`d3`='$schedule[3]',`d4`='$schedule[4]',`d5`='$schedule[5]',`d6`='$schedule[6]',`d7`='$schedule[7]' WHERE $table.`rooms`=$i");
          }

     }
}//MySQLing
echo "<html><head><script language=\"javascript\">window.location=\"http://www.basebooking.ru/admin/sch.php?act=s\"</script></head></html>";
} //end auth
?>