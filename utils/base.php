<?php  session_start(); 
include "../utils/client.php";

$today = 10000*date("Y")+100*date("n")+date("j");

if (!isset($_GET['name']) or empty($_GET['name'])) {
	header('Location: http://www.basebooking.ru/');
}

$name = $_GET['name'];
$base = new Base(0,$name);


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
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/fotorama.js"></script>
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<script type="text/javascript"  >
<?php 

$a = $base->outputInfo();
print("var base =");
print($a);
print(";\n");


?>
$(function() {
  $('#fotorama').fotorama({
    width: 676,
    height: 224,
    backgroundColor:  '#fafafa',
    thumbsBackgroundColor: '#e1e1e1',
  });
  
});

$(window).load(function() {
  
  mainHeight=$("#main").css("height");
  mainHeight=Number(mainHeight.substring(0, mainHeight.length-2));
  $("#box1").css("min-height", mainHeight+"px");
  
  
  d = parseInt(t1.toString().substring(6,8));
  startTime(d,hours,minutes,t1);  
})





function startTime(d,h,m,today) {
    m++;	
	m=checkTime(m);
	h=checkTime(h);
	d=checkTime(d)
	if (m == 60 ) {
		m="00";
		h++;
		
		if (h == 24){
          h="00";
          d++;
		}
	}
    newt = t1.toString();
    newt=newt.substring(0,4)+"."+newt.substring(4,6)+"."+d;
	$("#currTime").html(newt+" "+h+":"+m);
	t=setTimeout('startTime('+d+','+h+','+m+',t1)',1000*60);
}

function checkTime(i)  {
if (i<10) {
  i="0" + i;
}
return i;
}

</script>
<script type="text/javascript" src="http://basebooking.ru/js/bookingTest.js"></script>
</head>
<body>
<script type="text/javascript">
  VK.init({apiId: 2388317});
</script>
<div id="centered">

<?php printHeader(); ?>
<div class="space"></div>
<div class="space"></div>
  <div id="basename">
    <?php print($base->name);?>
  </div><br/>

 
  <div class="topline">
    
    


    <div class="ribbon">
      <div id="gall">Галерея</div>
      <div id="cal">Забронировать</div>
      <div id="conditions">Условия бронирования</div>
      <div id="currTime"></div>
    </div>   
    <div class="triangle-l"></div> 
    <div class="triangle-r"></div>
  </div>   
  <div id="main"> 
  <div id="box1">
  
      <?php  


/*
add message that base is not administered

*/


?>	
  <div id="datepicker">
  </div>
  <div id="nobooking" class="errwrap" style="display:none">
    <div class="err">
      <span>Извините, но онлайн бронирование для данной пока не доступно.</span>
    </div>
  </div>
   <div id="sch"></div>

<?php 

?>
  </div><!box1>

  <div id="box2">
   <div id="inf">
   <div class="header1">Контактная информация</div><br />
  <div class="space"></div>
  <?php  

   ?>
  <div class="space1"></div>
 </div>
  </div><!box2>
 </div><!main>
  <?php 
  printFooter();
  ?>
 </div><!centered>
 </body>
 </html>