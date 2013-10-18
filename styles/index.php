<?php session_start(); 
include "utils.php";
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - онлайн сервис бронирования репетиционных баз и студий</title>
<link rel="stylesheet" type="text/css" href="styles/styles.css">
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/fotorama.js"></script>
<link href="http://basebooking.ru/styles/fotorama.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<style>

#text,#buttons{color:#333;
font-size:12pt;
font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
line-height: 1.7em;}
#fotorama,#top-part,#left,#right,#text,#buttons,#b1,#b2,#b3 {float:left;}
#centered1 {margin: 0 auto; width: 760px;}
#fotorama {float:left;margin: 5px 0px 0px 0px;z-index:1;}
#top-part {float:left;width:100%;margin:3px 0 0 0;height:250px;}
#left {width:300px;height:250px;z-index:2}

#right {width:648px;height:250px;}
#right #text {margin:120px 0 0 20px;width:598px;height:110px; }
#buttons  {height:50px;width:100%;border-top: 1px solid #d1d1d1;z-index:2;
-webkit-box-shadow: 0 5px 5px -3px #aaa;
   -moz-box-shadow: 0 5px 5px -3px #aaa;
        box-shadow: 0 5px 5px -3px #aaa;
}
#b1,#b2,#b3 {width:317px;height:50px;}
#b1,#b2 {border-right:1px solid #d1d1d1;} 
#b1 span,#b2 span,#b3 span {display:block;height:40px;margin-top:9px;width:100%;text-align:center;cursor:pointer;}
#b1 span:hover,#b2 span:hover,#b3 span:hover{color:#08c;}

</style>
<script>
var url = window.location;
if (url.href.substring(0,8)=="http://b"){
	window.location="http://www.basebooking.ru";	
}

$(window).load(function() {
	$('#fotorama').fotorama({
		width: 953,
        height: 360,
        backgroundColor:  '#fafafa',
        thumbsBackgroundColor: '#e1e1e1',
	});
	$(".fotorama__thumbs").css("display","none");
});

$("#b1").live("click", function () {
	$(".fotorama__arr_prev").click();
})

$("#b2").live("click", function () {
	$(".fotorama__arr_next").click();
})

</script>

</head>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29427708-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<body>
<div id="centered">
<?php printHeader(); ?>
<br><br>
  <div id="main" >
  <div id ="top-part">
  		<div id="left">
  		</div>

  		<div id="right">
  			<div id="text">
  			<strong>Basebooking</strong> – новый сервис онлайн бронирования репетиционных баз и студий. Всего за несколько кликов вы сможете забронировать репетицию, а также найти текущую информацию о расписании, ценах и наличии свободного времени.
  			</div>
  		</div>
  </div>
  <div id ="buttons">
 
  	<div id = "b1">
  		<span>Группам</span>
  	</div>

  	<div id = "b2">
  		<span>Администраторам</span>
  	</div>

  	<div id = "b3">
  		<span> <a href="http://www.basebooking.ru/search.php">Найти базу!</a></span>
  	</div>

  </div>
  <div id="fotorama">
  <img src="http://www.basebooking.ru/img/mus.jpg" />
  <img src="http://www.basebooking.ru/img/adm.jpg" />
	

  </div>    
  </div><!mainbox>
<?php printFooter(); ?>

</div><! centered>
</body>
</html>