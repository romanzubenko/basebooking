<?
include "../utils.php";


$userSum = intval($_POST['sum']);

if ($userSum % 500 !== 0  || !isset($idb)) {
      exit("0");
}



// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
$mrh_login = "demo";
$mrh_pass1 = "Morbid11";

// номер заказа
// number of order
$inv_id = 0;

// описание заказа
// order description
$name = getBaseName($idb);
$inv_desc = "Payment for ".$name." Base id =".$idb;

// сумма заказа
// sum of order
$out_summ = $userSum;

// тип товара
// code of goods
$shp_item = "1";

// предлагаемая валюта платежа
// default payment e-currency
//                                        !!CHANGE TO RUBLES
$in_curr = "PCR";

// язык
// language
$culture = "ru";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// форма оплаты товара
// payment form
print "<html>".
      "<form action='https://merchant.roboxchange.com/Index.aspx' method=POST>".
      "<input type=hidden name=MrchLogin value=$mrh_login>".
      "<input type=hidden name=OutSum value=$out_summ>".
      "<input type=hidden name=InvId value=$inv_id>".
      "<input type=hidden name=Desc value='$inv_desc'>".
      "<input type=hidden name=SignatureValue value=$crc>".
      "<input type=hidden name=Shp_item value='$shp_item'>".
      "<input type=hidden name=IncCurrLabel value=$in_curr>".
      "<input type=hidden name=Culture value=$culture>".
      "<input type=submit value='Pay'>".
      "</form></html>";
?>