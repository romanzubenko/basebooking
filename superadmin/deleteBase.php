<?php
session_start();
include "../utils.php";


if ($_SESSION['login'] == "superadmin" and isset($_GET['base'])) {
    $idb = intval($_GET['base']);
    $r = deleteBase($idb);
     header('Location: http://www.basebooking.ru/superadmin');
} else {
    header('Location: http://www.basebooking.ru/');
}

?>