<?php session_start();
include "../utils.php";

/*  OUTPUT OF PROGRAM  :  

1,0  -> all good
0,1  -> mysql error
0,2  -> base with this name exists
0,3  -> mysql add error
0,4  -> transfer
0,5  -> update SA bases
0,6  -> email doesn't exit
0,0  -> not SA

*/

function getWaitlistQuery($id) {
	$r = mysql_query("SELECT * FROM waitlist WHERE id='$id'");
	if (!$r) {
		return false;
	}
	$row = mysql_fetch_array($r);
	return $row;
}

function baseNotExists($name){
	$r = mysql_query("SELECT id FROM bases WHERE name='$name'");
	$m = mysql_fetch_array($r);


	if (!empty($m['id'])) {
		return false;
	} else {
		return true;
	}
}

if ($_SESSION['login'] == 'superadmin') {
	$ind = $_POST['ind'];
	$ind = intval($ind);

	$data = getWaitlistQuery($ind);
	
	if ($data === false) {
		exit("0,1");
	}

	if ($_POST['denial'] == 1) {
		$to = $data['email'];
		$subject = 'Заявка на Basebooking!';
		$message = "Доброго времени суток,\n
		К сожалению Ваша заявка не была одобрена.\n\n С уважением, Максим и Роман.
		";
		$headers = 'From: info@basebooking.ru' . "\r\n";
		mail($to, $subject, $message, $headers);


		$r = mysql_query("DELETE FROM `waitlist` WHERE id='$ind'");
		exit("1,0");
	}

	if (!TrueEmail($data['email'])) {
		exit("0,6");
	}

	$notexist = baseNotExists($data['name']);
	if (!$notexist) {
		exit("0,2");
	}

	if ($data['type'] == 0) {
		$data['type'] = 1;
	}

	$addBase = createBase($data['name'],$data['type'],1,"","","","",0,$data['vk'],$data['phone'],$data['website']);
	
	if (!$addBase) {
		exit("0,3");
	}
$name = $data['name'];
	$idb = mysql_query("SELECT id FROM bases WHERE name='$name'");

    if ($addBase) {
        $idb = mysql_fetch_array($idb);
        $idb = $idb['id'];

        $secret   = rand_str();
        $link     = rand_str();
        $transfer = mysql_query("INSERT INTO `b108859_wordpress`.`transfer` (`id`,`link`,`secret`) VALUES ('$idb','$link','$secret')");
    }

    if (!$transfer) {
    	exit("0,4");
    }


    $to      = $data['email'];
	$subject = 'Ваша заявка одобрена на Basebooking!';
	$message = "Доброго времени суток,\n
	Для получения прав администрирования и доступа к сервису, пройдите по ссылке
	www.basebooking.ru/transfer.php?link=$link
	и укажите следующий секретный код:$secret\n\n С уважением, Максим и Роман.
	";
	$headers = 'From: contact@basebooking.ru' . "\r\n" .
    'Reply-To: contact@basebooking.ru' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);

	$r = mysql_query("DELETE FROM `waitlist` WHERE id='$ind'");
	
    if ($sar) {
    	exit("1,0");
    } else {
    	exit("0,5");
    }

} else {
	exit("0,0");
}

?>
