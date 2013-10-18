<?php  session_start(); 
$timestamp = array();
$timestamp[0] = microtime();
include "utils.php";
include "utils/client.php";
include "utils/user.php";

$today = 10000*date("Y")+100*date("n")+date("j");

if (!isset($_GET['name']) || empty($_GET['name'])) {
  header('Location: http://www.basebooking.ru/');
}

$bn = check($_GET['name']);
$base = new Base(0,$bn,0);

if ($base->idb === 0) {
  header('Location: http://www.basebooking.ru/404.php');
  exit("");
}

if ($base->isOwner()) {
  $owner = true;
  $adminJS = "<script type=\"text/javascript\" src=\"http://basebooking.ru/js/baseAdmin.js\"></script>\n";
} else {
  $owner = false;
  $adminJS = "";
}

$JSinput = "";
$JSinput .= "var input = {};\n";

$JSinput .= "input.info = ";
$JSinput .= $base->outputInfo().";\n";

$JSinput .= "input.equipment = ";
$JSinput .= $base->outputEquipment().";\n";

$JSinput .= "input.schedules = ";
$JSinput .= $base->outputSchedules().";\n";

$JSinput .= "input.bookings = ";
$JSinput .= $base->outputBookings().";\n";

$JSinput .= "input.photos = ";
$JSinput .= $base->outputPhotos().";\n";

$JSinput .= "input.roomNames = ";
$JSinput .= $base->outputRoomNames().";\n";
/*
  FIGURE OUT NOTIFICATIONS
$JSinput .= "input.notifications = ";
$JSinput .= $base->outputPhotos().";\n";
*/
$JSinput .= "input.owner = ";
$JSinput .= json_encode($owner).";\n";

$JSinput .= "today = ".$today.";\n";
$JSinput .= "input.timestamp = ";
$JSinput .= $base->outputTime().";\n";


if (isset($_SESSION['vkid'])) {
  $user = new User($_SESSION['vkid']);

  $JSinput .= "user = {};\n";

  $JSinput .= "user.info = ";
  $JSinput .= $user->outputInfo().";\n";

}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - <?php $b =  clears($_GET['name']); echo $b; ?></title>
<link href="http://basebooking.ru/styles/fotorama.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/styles.css" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/baseStyles.css" />
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?32"></script>
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/fotorama.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/baseConstructor.js"></script>

<?php  print($adminJS);?>
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />

<style> 
  .roomInfo .equip_header{
    font-weight: 600;
    color: #123;
    width: auto;
    height:34px;
    height: 40px;
    font-size: 15px;
    border-bottom:none;
    cursor:pointer;
  }

  
.roomInfo span {
  display: block;
  width: 100%;
  text-align: center;
  color: #CCC;
  font-size: 15px;
  padding-top: 30px;
  height:52px;
}

.enlarge {
  width: 92px;
  float: right;
  display: inline-block;
  font-weight: 200;
  color: #08C;
}
  
  .roomInfo {
    min-height: 40px;
    float: left;
    width: 100%;

  }
  .slideDown {
    padding-left: 6px;
    border-top:1px dotted #ccc;
    display: none;
  }

#cond {
  display: none;
}

#basebooking_path {
  float: left;
  width: 100%;
  height: 40px;
  border-bottom: 1px solid #D1D1D1;
  background-image: -webkit-linear-gradient(top, #FAFAFA, #F4F4F4);
}

#basebooking_path div {
  float: left;
  height: 20px;
  padding-top: 11px;
  padding-left: 50px;
  font-size: 13px;
}

#basebooking_path span {
  display: block;
  width: 200px;
  padding-top: 11px;
  font-size: 13px;
  text-align: center;
  height:30px;
  float: left;
}

#basebooking_path div:first-child {
  float: left;
  height: 20px;
  padding-top: 11px;
  padding-left: 12px;
  font-size: 13px;
}

.booking_message {
  width: 636px;
  font-size:20px;
  height:110px;
  text-align:center;
  padding:20px;
  background-color:#fafafa;
  padding-top:150px;
  border-bottom:1px solid #d1d1d1;
}

.edit{
  display:inline-block;
  width:90%;
  text-align:right;
  color: #08C;
  cursor: pointer;
  font-size: 12px;
}

.editInfo {
  width: 95%;
  margin-top:7px;
}

.editConditions,.CanceleditConditions {
  width: 98%;
  margin-top: 8px;
}

.roomInfo span {
  display: block;
  width: 60px;
  text-align: left;
  font-size: 12px;
  padding-top: 0px;
  height: 20px;
  float: left;
}

#inf textarea {
  width: 164px;
  min-height: 20px;
  border: 1px dotted #AAA;
  max-width: 164px;
}

.inputdes {
  min-width:235px;
  min-height:100px;
}

.middle {
  width: 224px;
  height: 30px;
  padding-top: 11px;
  text-align: center;
  font-size: 14px;
  margin: 0 auto;
}

#cond input {
  border: 1px solid #D1D1D1;
  border-radius: 3px;
  height: 16px;
  width: 24px;
  padding-left: 6px;
}

#cond div {
  float: left;
  margin-top: 0px;
  min-width: 10px;
  margin-left: 0px;
  color: #333;
}

#cond .innerConditions {
  float: left;
  margin-top: 30px;
  width: 630px;
  margin-left: 23px;
  color: #333;
}

.editRoom,.cancelRoomEdit {
  width: 128px;
  height: 32px;
  padding-top: 8px;
  float: left;
  font-weight: 200;
  font-size: 15px;
  border-left: 1px solid #D1D1D1;
  text-align: center;
}
.innerHeader {
  float: left;
  padding-left: 15px;
  padding-top: 7px;
  height: 33px;
  width: 662px;
}


.roomInfo .innerHeader:hover,.editRoom:hover,.cancelRoomEdit:hover {
  background-color:#eaeaea;
  color:#111;
}

.roomInfo textarea {
  width: 486px;
  border: 1px dotted #CCC;
}

.roomInfo .space {
  height: 11px;
}

#datepicker {
  display: block;
  border-bottom:1px solid #d1d1d1;
}

.slideDown span {
  width: 100%;
  text-align: center;
  padding-top: 35px;
  height: 59px;
  font-size: 15px;
  font-weight: 200;
  color: #123;
}

#float_menu {
  position: fixed;
  z-index: 10;
  top: 30%;
  left: 50%;
  margin-left: -593px;
  width: 114px;
  background-color: #FAFAFA;
  height: 138px;
  padding: 0;
  border: 1px dotted #CCC;
  font-size: 14px;
  color: #123;
  font-weight: 200;
  cursor: pointer;
  text-shadow: 1px 1px white;
  padding-top: 5px;
  padding-bottom: 5px;
}

#float_menu span {
  padding-top: 5px;
  padding-bottom: 5px;
  display: block;
  border-left: 6px solid #FAFAFA;
  -webkit-padding-start: 10px;
  -moz-padding-start: 10px;
  padding-start: 10px;
}

#float_menu span:hover {
  color: #08C;
  border-left: 6px solid #456;
}

#float_menu span.active {
  border-left: 6px solid #08c;
}

.fotorama__frame {
  background-color:#fafafa;
}

.exbutton {
  position: relative;
  margin-top: 5px;
  margin-left: 649px;
}
.exbutton {
  width: 11px;
  height: 11px;
  background-image: url(http://basebooking.ru/img/exbutton.png);
  display: none;
  position: absolute;
  margin-left: 660px;
  margin-top: 5px;
  cursor: pointer;
}

#exsch {
  margin-left: 649px;
  margin-top: -11px;
}

#exdial {
  margin-left: 654px;
  margin-top: 10px;
}


#sch_header {
  width: 666px;
  padding: 5px;
  padding-top: 21px;
  height: 38px;
  float: left;
  background-color: #FAFAFA;
  border-bottom: 1px solid #D6D6D6;
  text-align: center;
  font-size: 16px;
  color: #123;
}

#basebooking_schedule,#basebooking_confirmation {
  background-color: white;
  float: left;
  border-bottom:1px solid #d1d1d1;
}

.booked.owner a {
  cursor:pointer;
  text-shadow: 0px 1px white;
  z-index:10;
}

#dial1 {
  width: 428px;
  padding: 15px;
  min-height: 140px;
  border-right: 1px solid #D1D1D1;
  float: left;
}
#dial1 table {
  width: 100%;
}

#dial1 tr {
  height: 32px;
  width: 100%;
  color: #222;
  float: left;
  padding-top: 7px;
}

#dial1 td:first-child {
  font-weight: 600;
  width: 129px;
}

#dial1 input {
  border-radius: 3px;
  width: 281px;
}

#dial1 span {
  font-size: 15px;
}

.button.bookingButton {
  height: 41px;
  margin-top: 100px;
  padding-top: 19px;
  width: 200px;
  margin-left: 5px;
}

#nextk,#prevk {
  height: 280px;
  width: 46px;
  padding-left: 9px;
}



#dial2 {
  width: 212px;
  padding: 15px;
  padding-left: 2px;
  padding-right: 2px;
  height: 140px;
  float: left;
  text-align: center;
  font-size: 13pt;
}

#vk_auth {
  margin-left: 6px;
  margin-top: 14px;
  float: left;
}

.attention {
  width: 232px;
  font-size: 13px;
  font-weight: 200;
  padding: 15px;
  border: 7px solid #F6F6F6;
}

.nobooking {
  width: 676px;
 height: 152px;
padding-top: 128px;
  font-weight: 200;
  font-size: 26px;
  float: left;
  text-align: center;
}

#box2 {
  margin-left: 0;
margin-top: 0;
}

.num {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  -o-text-overflow: ellipsis;
}

#sch_scale {
  float: left;
  width: 40px;
  min-height: 200px;
}

.scale_hour {
  width: 35px;
  display: block;
  padding-top: 0;
  float: right;
  margin-right: 6px;
  text-align: right;
  height: 55px;
  margin-top: 0;
}

.scale_hour:first-child {
  height: 80px;
}

#sch_scale {
  float: left;
  width: 50px;
}

#sch_schedule {
  width: 624px;
  min-height: 200px;
  float: left;
  position: absolute;
}

.sch_hour {
  display: block;
  width: 624px;
  border-top: 1px solid #D1D1D1;
  height: 18px;
}

.sch_half {
  display: block;
  width: 624px;
  height: 17px;
  border-top: 1px dotted #D1D1D1;
}
.sch_hour:first-child {
  margin-top: 50px;
}

#nonfixedcontainer {
  float: left;
  overflow: hidden;
  width: 624px;
  border-left: 1px dotted #d1d1d1;
}

#bookings_container {
  float: left;
  min-height: 150px;
  position: relative;
}

.NFroom {
  position: relative;
  width: 155px;
  float: left;
  border-right: 1px dotted #DDD;
  margin-top: 32px;
  border-top: 1px solid #D1D1D1;
}

.roomHeader:active {
  cursor: -webkit-grabbing;
  cursor: -moz-grabbing;
  cursor: grabbing;
}

.roomHeader {
  width: 155px;
  padding-top: 15px;
  text-align: center;
  height: 39px;
  background-color: #FCFCFC;
  cursor: -webkit-grab;
  cursor: -moz-grab;
  cursor: grab;
}

.Fbooking,.NFbooking {
  position:absolute;
  min-height:50px;
  border-color: rgba(0, 0, 0, .35);
  border: 1px solid #A5A5A5;
  -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
  -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
  box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
  -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
  -moz-border-radius: 3px;
  border-radius: 3px;
  background: -webkit-linear-gradient(top,#fcfcfc,#F9f9f9);
  background-image: -moz-linear-gradient(top, #fcfcfc, #f9f9f9);
  background-image: -ms-linear-gradient(top, #fcfcfc, #f9f9f9);
  background-image: -webkit-linear-gradient(top, #fcfcfc, #f9f9f9);
  background-image: -o-linear-gradient(top, #fcfcfc, #f9f9f9);
  background-image: linear-gradient(top, #fcfcfc, #f9f9f9);
  min-width: 147px;
  left: 2px;
  border-left:3px solid #34DA34;
  cursor:pointer;
  z-index:9;
}

.Fbooking:hover,.NFbooking:hover {
  background: -webkit-linear-gradient(top,#fff,#fcfcfc);
  background-image: -moz-linear-gradient(top, #fff, #fcfcfc);
  background-image: -ms-linear-gradient(top, #fff, #fcfcfc);
  background-image: -webkit-linear-gradient(top, #fff, #fcfcfc);
  background-image: -o-linear-gradient(top, #fff, #fcfcfc);
  background-image: linear-gradient(top, #fff, #fcfcfc);
}

.Fbooking:active,.NFbooking:active {
  background: -webkit-linear-gradient(top,#fafafa,#f7f7f7);
  background-image: -moz-linear-gradient(top, #fafafa, #f7f7f7);
  background-image: -ms-linear-gradient(top, #fafafa, #f7f7f7);
  background-image: -webkit-linear-gradient(top, #fafafa, #f7f7f7);
  background-image: -o-linear-gradient(top, #fafafa, #f7f7f7);
  background-image: linear-gradient(top, #fafafa, #f7f7f7);
}

.Fbooking.booked,.NFbooking.booked {
  border-left:3px solid #E61F1F;
}

.ext1 {
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;
}

.ext2 {
  border-bottom: none;
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
}

#bookings_wrapper {
  float: left;
  margin-left:-623px;
}

body {
  background: url(http://basebooking.ru/img/bgNew.png) repeat;
}
#conditions, #gall, #cal {
  color: white;
  font-weight: 200;
  height: 22px;
  padding-top: 7px;
  padding-left: 15px;
  padding-right: 15px;
  margin-left: 0px;
}

#conditions:hover, #gall:hover, #cal:hover {
  background-color: rgba(38, 70, 138, 0.25);
}

.topline .ribbon {
  height: 34px;
  padding-top: 0px;
}
#top {
  height: 46px;
  background-color: white;
  border-bottom: 1px dashed #123;
  border-top: 3px solid #123;
}

.timesch,.price {
  margin: 10px;
}
.booked .price  {
  margin-top: -3px;
}

.timesch input[type="text"] {
  border: 1px solid #D1D1D1;
  height: 19px;
  margin: 3px;
  border-radius: 3px;
  width: 42px;
}

#prevArrow, #nextArrow {
  position: absolute;
  width: 156px;
  text-align: center;
  padding-top: 7px;
  background-color: #FDFDFD;
  height: 23px;
  cursor: pointer;
  z-index: 100;
}

#prevArrow:hover, #nextArrow:hover {
  color: #08c;
}
#nextArrow {
  left: 521px;
}

#nextday, #prevday {
  width: 20px;
  height: 17px;
  position: absolute;
}
#nextday {
  background-image:url(http://basebooking.ru/img/monthForward_normal.gif);
  left:507px;
}

#prevday {
  background-image:url(http://basebooking.ru/img/monthBackward_normal.gif);
  left:149px;
}
.priceInfo {
  margin:0 auto;
  margin-top: 10px;
  width: 90%;
  font-size: 12px;
  padding-bottom: 10px;
  max-height: 67px;
}

.clock {
  float: right;
  margin-right: 16px;
  margin-top: 7px;
  color: white;
  font-weight: 200;
}

</style>
<script type="text/javascript">
<?php
  print($JSinput);
?>

var k = input.info['rooms'];
function setBoxHeight () {
  var main = (parseInt($("#main").css("height")) + 10 ) + "px";
  $("#box1").css("min-height",main);
}

$(window).load(function() {
  VK.init({apiId: 2388317, onlyWidgets: true});
  window.page = new Base(input);
  window.booking = new BookingEngine($("#booking_box"),input.schedules,input.bookings,input.info['idb'],input.owner,page.info['komn'],input.roomNames,page.info.NF,page.info.firstHour,page.info.lastHour,input.timestamp[2]);
  page.createInfo();
  page.createPhotos();
  page.createEquipment();
  page.createNavigation();
  if (page.owner) {
      page.adminFeatures();
  }

  if (page.photos.length !== 0) {
   
    $('#fotorama').fotorama({
      width: 676,
      height: 224,
      backgroundColor:  '#fafafa',
      thumbsBackgroundColor: '#e1e1e1',
    }).css("display","block").css("display","none");

  } 
  setBoxHeight();
  VK.init({apiId: 2388317});
  window.booking.clock();
});




</script>
<script type="text/javascript" src="http://basebooking.ru/js/bookingEngine.js"></script>
</head>
<body>


<div id="centered">

<?php printHeader(); ?>
<div class="space"></div>
<div class="space"></div>
  <div id="basename">
    <?php $b = clears($_GET['name']); echo $b;?></div><br/>
  <div class="topline">
    <div class="ribbon">
    </div>   
    <div class="triangle-l"></div> 
    <div class="triangle-r"></div>
  </div>   

  <div id="main"> 
  <div id="box1">
  
  <div id="nobooking" class="errwrap" style="display:none">
    <div class="err">
      <span>Извините, но онлайн бронирование для данной пока не доступно.</span>
    </div>
  </div>
   <div id="booking_box"></div>
   <div id="fotorama"></div>
   <div id="cond"></div>
   <div id="equipment"></div>
   
  </div><!--box1 -->

  <div id="box2">
   <div id="inf">
   
  <div class="space"></div>
 </div>
  </div><!--box2 -->
 </div><!--main-->
<?php 

  printFooter();
  $timestamp[1] = microtime();
  $a = $timestamp[1] - $timestamp[0];
  echo "time = ".$a." h";

 ?>
 </div><!--centered-->
 </body>
 </html>