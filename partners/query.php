<?php session_start();
include "../utils.php";

/*  FILE OUTPUT

1,1 - success
0,1 - unknown - try again
0,2 - base exists - contact us
0,3 - base exists in waitlist - hold on || contact us
0,4 - you are logged in 
0,5 - not all required fields are completed
*/


function queryExistance ($name,$email) {
	$query = "SELECT name from bases where name='$name'";
	$test1 = mysql_query($query);
	$test1 = mysql_fetch_array($test1);

	$query = "SELECT name from waitlist where name='$name'";
	$test2 = mysql_query($query);
	$test2 = mysql_fetch_array($test2);

	if (!empty($test1['name'])) {
		return "0,2";
	} else if (!empty($test2['name'])) {
		return "0,3";
	} else {
		return "1";
	}
}

if (isset($_SESSION['login']) || isset($_SESSION['vkid'])) {
  exit ("0,4");
}


$name     = check($_POST['name']);
$vk       = check($_POST['vk']);
$phone    = check($_POST['phone']);
$email    = check($_POST['email']);
$type     = check($_POST['type']);
$website  = check($_POST['website']);

if ($name == "" || $phone == "" || $email == "") {
  exit ("0,5");
}

// check if base exists, check if it exists in waitlist
$existTest = queryExistance($name,$email);

if ($existTest == "0,2") {
	exit("0,2");
} else if ($existTest == "0,3") {
	exit("0,3");
}

// existance check done 



$insert = mysql_query("INSERT INTO `b108859_wordpress`.`waitlist` (`name`,`vk`,`phone`,`email`,`website`,`done`) VALUES ('$name','$vk','$phone','$email','$website','0')");
if ($insert) {
  exit ("1,1");
} else {
  exit ("0,1");
}

?>