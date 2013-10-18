<?php
session_start();
include "../utils.php";


if ($_SESSION['login'] == "superadmin" and isset($_POST['basename'])) {
    $name     = check($_POST['basename']);
    $descript = check($_POST['descript']);
    $adress   = check($_POST['adress']);
    $town     = check($_POST['town']);
    $station  = check($_POST['station']);
    $komn     = check($_POST['komn']);
    $website = check($_POST['website']);
    $phone   = check($_POST['phone']);
    $vk      = check($_POST['vk']);
    $type   = check($_POST['type']);

    if (!isset($name) or !isset($adress) or !isset($town) or !isset($type) or empty($name) or empty($adress) or empty($town) or empty($type)) {
        header('Location: http://www.basebooking.ru/superadmin/index.php?add=on&fail=fail');
        exit("");
    }
    
    $result1 = mysql_query("SELECT id FROM bases WHERE name='$name'");
    $myrow   = mysql_fetch_array($result1);
    if (!empty($myrow['id'])) {
        exit("База с названием \"{$name}\" уже зарегистрирована, пожалуйста выберите другое название либо свяжитесь с администрацией, если вы считаете что кто-то неправомерно зарегистрировал базу с вашим названием.");
    }
     
       /// HERE WE GOO ADDDIIING
       $addBase = createBase($name,$type,$komn,$descript,$town,$station,$adress,0,$vk,$phone,$website);
       $idb = mysql_query("SELECT id FROM bases WHERE name = '$name'");

        if ($addBase) {
            $idb = mysql_fetch_array($idb);
            $idb = $idb['id'];

            $secret   = rand_str();
            $link     = rand_str();
            $transfer = mysql_query("INSERT INTO `b108859_wordpress`.`transfer` (`id`,`link`,`secret`) VALUES ('$idb','$link','$secret')");

          
            header('Location: http://www.basebooking.ru/superadmin/');
            exit("");
            
            
    } else {
        header('Location: http://www.basebooking.ru/superadmin/index.php?change=hui&v=2');
        exit("");
    }

}
?>