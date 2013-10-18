<?php session_start();
include "utils.php";
function checktMDK($id,$idb,$email) {
  $r = mysql_query("SELECT * FROM users WHERE id='$id'");
  $r = mysql_fetch_array($r);
  $basename = getBaseName($idb);

  if ($r['login'] == "") {
    $user = mysql_query("INSERT INTO `b108859_wordpress`.`mudaki` (`idb`, `email`, `basename`) VALUES ('$idb', '$email', '$basename')");
    return false;
  } else  {
    return true;
  }
}

if (isset($_SESSION['login']) && $_SESSION['login'] != "superadmin") {
  header('Location: http://www.basebooking.ru/admin/');
} else {

//receiver
if  ($_GET['right'] == 1 && isset($_POST['link']) && isset($_POST['code']) && isset($_POST['submit'])) {




  $link = check($_POST['link']);
  $secret = $_POST['code'];
  $email = check($_POST['email']);
  if (!TrueEmail($email) || $email == "") { 
  	header('Location: http://www.basebooking.ru/transfer.php?link='.$link);
    exit("");
  }

  $prov = mysql_query("SELECT id FROM users WHERE email='$email' or login='$email'");
  $prov = mysql_fetch_array($prov);

  if (!empty($myrow['id'])) {
    exit ("Данный почтовый ящик уже зарегистрирован");
  }

  $r = mysql_query("SELECT * FROM transfer WHERE link='$link'");
  $r = mysql_fetch_array($r);
  $idb = $r['id'];

  if ($secret == $r['secret']) {
    $base = $idb;
    $st = 1;

    $secret = md5($secret);

    $user = mysql_query("INSERT INTO `b108859_wordpress`.`users` (`login`, `password`, `email`,`bases`,`type`) VALUES ('$email', '$secret', '$email','$base','$st')");
    $back = mysql_query("SELECT * FROM users WHERE login='$email'");
    $back = mysql_fetch_array($back);
    checktMDK($id,$idb,$email);

    $idMDK = mysql_insert_id();
    
    if (isset($back['id']) and isset($back['login']) and isset($back['password'])){
      $_SESSION['login'] = $back['login'];
      $_SESSION['pid'] = $back['id'];
      $pid = $back['id'];

      $exp = time() + 3888000*1000;
      $rss = mysql_query("UPDATE `bases` SET `exp`='$exp' WHERE `id`='$idb'");
      

      $del = mysql_query("DELETE FROM `transfer` WHERE `link`='$link'",$db);
      $up = mysql_query("UPDATE `bases` SET `pid`='$pid' , `booking`='1' WHERE `id`='$idb'",$db);



      /*HERE GOES REDIRECT TO TOUR */
      if ($del && $up) {
      	header('Location: http://www.basebooking.ru/tour');
      }
      /*HERE GOES REDIRECT TO TOUR */

    }
  }
}


$link = $_GET['link'];
$r = mysql_query("SELECT * FROM transfer WHERE link='$link'");
$r = mysql_fetch_array($r);
$idb = $r['id'];
$name = getBaseName($idb);

echo"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"> <html xmlns=\"http://www.w3.org/1999/xhtml\"> <head> <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <title>Basebooking - {$name}</title> 
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://basebooking.ru/styles/styles.css\">
<style>
#cent{width:400px; margin:0 auto;}
.elemento {
  width: 398px;
  height: 249px;
  border-radius: 6px;
}
.test {margin:0 auto; width:370px;}
.test1 {margin:0 auto; width:318px;}
.test2 {
  margin: 0 auto;
  width: 197px;
  margin-top: 23px;
}
.test2 input {
  width: 143px;
  height: 39px;
  font-size: 10pt;
}

.test1 input{
  min-height: 22px;
  border: 1px solid 
  #AAA;
  border-radius: 3px;
  min-width: 197px;
}

</style>"; ?>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29427708-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 


</script>

<?php echo"
<body>
 <div id=\"centered\"> 
";
printHeader();
echo"<br><br>
 <div id=\"cent\"> 
<div class=\"elemento\"><br />
<div class=\"header1\">Если вы явлеетесь владельцем базы {$name}</div><div class=\"space\"></div>
<div class=\"test1\">
<form action=\"http://www.basebooking.ru/transfer.php?right=1&link=".$link."\" method=\"post\"> 
 <table>
  <tr><td>Ваш email:</td><td><input type=\"text\" name=\"email\"></td></tr>
 <tr><td>Секретный код:</td><td><input type=\"text\" name=\"code\"></td></tr>

  </table>
 <input type=\"hidden\" name=\"link\" value=\"".$link."\">
 <div class=\"test2\">
<input type=\"submit\" name=\"submit\" value=\"Зарегистрироваться\" class=\"button\"/>
 </div>
 </form></div><br><div class=\"test\">
 Ваш email будет вашим логином для входа на Basebooking.
 </div>
</div>

";
printFooter();
echo"
</div>
</body>
</html>

";
}
?>
