<?php session_start();
if (isset($_SESSION['login'])) {
	unset($_SESSION['login']);
} 
if (isset($_SESSION['vkid'])) {
	unset($_SESSION['vkid']);
} 

if (isset($_SESSION['pid'])) {
	unset($_SESSION['pid']);
} 
if (isset($_SESSION['type'])) {
	unset($_SESSION['type']);
} 

header('Location: http://www.basebooking.ru/');
?>
