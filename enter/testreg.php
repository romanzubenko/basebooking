<?php session_start();
include "../utils.php";


if (isset($_SESSION['login'])) {
	header('Location: http://www.basebooking.ru/admin/');
}

if (isset($_SESSION['vkid'])) {
	header('Location: http://www.basebooking.ru/musician/');
}
	 

if (isset($_POST['hash'])) { 
  $name = check($_POST['name']);
  $lastname = check($_POST['lastname']);
  $hash = check($_POST['hash']);
  $vkid = check($_POST['vkid']);
  
  //hashcheck
  $app_id = "2388317";
  $secret_key = "4FX2MlBq5mr8vjKoEXxK";
  $bbhash = md5($app_id.$vkid.$secret_key);
  
  if ($bbhash == $hash){
    //hashcheck end
    
      if (!empty($name) and !empty($lastname) and !empty($hash) and !empty($vkid)){
        if (!isset($_SESSION['vkid'])) {
          $_SESSION['vkid'] = $vkid;
        }
        
        if (isset($_SESSION['login'])) { 
          unset($_SESSION['login']);
          unset($_SESSION['pid']);
          unset($_SESSION['type']);
        }
        
        createUser($vkid,$name,$lastname,10);
        
  	     
  	  }
      header('Location: http://www.basebooking.ru/musician');
      exit("");
	  
  }
}


if (isset($_POST['login'])) {$login = $_POST['login']; if ($login == '') {unset($login);}}
if (isset($_POST['password'])) {$password = $_POST['password']; if ($password == '') {unset($password);}}
if (empty($login) or empty($password)) {
  header('Location: http://www.basebooking.ru/partners');
}
$login = check($login);
$password = check($password);
$password=md5($password);
$result = mysql_query("SELECT * FROM users WHERE login='$login'",$db);
if ($result) {
  $myrow = mysql_fetch_array($result);
} else {
  header('Location: http://www.basebooking.ru/enter/index.php?act=2');
}

if (empty($myrow['password'])) {
  header('Location: http://www.basebooking.ru/partners/');
} else { 
  if ($password == $myrow['password']) {
    $_SESSION['login'] = $myrow['login'];
    $_SESSION['pid'] = $myrow['id'];
    $_SESSION['type'] = $myrow['type'];

    if ($myrow['login'] == "superadmin") { 
      header('Location: http://www.basebooking.ru/superadmin/');
    } 


   header('Location: http://www.basebooking.ru/admin/');
  }

  else {
    header('Location: http://www.basebooking.ru/enter/index.php?act=2');}
} 
?>