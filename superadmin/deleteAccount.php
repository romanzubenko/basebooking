<?php
session_start();
include "../utils.php";


if ($_SESSION['login'] == "superadmin" && $_SESSION['pid'] == 29 && isset($_GET['user'])) {
    $id = intval($_GET['user']);
    $r = deleteUser($id);
     header('Location: http://www.basebooking.ru/superadmin/index.php?users=on');
} else {
    header('Location: http://www.basebooking.ru/');
}

?>