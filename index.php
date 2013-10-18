<?php session_start(); 
include "utils.php";

?> 

<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - онлайн сервис бронирования репетиционных баз и студий</title>
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/styles.css">
<link href="http://basebooking.ru/styles/fotorama.css" type="text/css" rel="stylesheet" />
<link href="http://basebooking.ru/styles/baseStyles.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?32"></script>
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<style>

#text,#buttons{color:#333;
font-size:12pt;
line-height: 1.7em;}
#fotorama,#top-part,#left,#right,#text,#buttons,#b1,#b2,#b3 {float:left;}
#centered1 {margin: 0 auto; width: 760px;}
#fotorama {float:left;margin: 5px 0px 0px 0px;z-index:1;}

#buttons  {height:50px;width:100%;border-top: 1px solid #d1d1d1;z-index:2;
  border-bottom: 1px solid #D1D1D1;
}

#tryit {
  -webkit-box-shadow: 0 5px 5px -3px #aaa;
   -moz-box-shadow: 0 5px 5px -3px #aaa;
        box-shadow: 0 5px 5px -3px #aaa;
  float:left;
}
#b1,#b2 {width:475px;height:50px;}
#b1 {border-right:1px solid #d1d1d1;} 
#b1 span,#b2 span{display:block;height:40px;margin-top:9px;width:100%;text-align:center;cursor:pointer;}

#b1:hover, #b2:hover{color:#08c;background-color:#fcfcfc;}

.maindes, .mainheader, #text, #buttons {
  font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
}

.ready {
  width: 666px;
  text-align: center;
  font-size: 13pt;
  padding-top: 114px;
  font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
  height: 157px;
  border: 5px solid #F6F6F6;
  cursor:pointer;
}

.maindes {
float: left;
width: 236px;
padding: 20px;
border-right: 1px dotted #D1D1D1;
min-height: 241px;
font-size: 10pt;

}

.mainheader {
  width: 100%;
  text-align: center;
  height: 55px;
  float: left;
  padding-top: 30px;
  border-top: 1px solid 
  #D1D1D1;
  border-bottom: 1px solid 
  #D1D1D1;
  font-size: 15pt;
}

.maindes span {
  width: 100%;
  display: block;
  text-align: center;
  height: 47px;
  font-size: 13pt;
}

.prince {
  width: 197px;
  min-height: 187px;
  line-height: 1.4em;
}
#sch {
  width:676px;
}

#datepicker {
  display: block;
  border-left: 1px solid #D1D1D1;
}

#sch {
  width: 676px;
  border-left: 1px solid #D1D1D1;
}

#fotorama {
  margin:0;
}

.komn div:last-child {
  border-bottom:none;
}

</style>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/fotorama.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.datepicker.js"></script>
<script>
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


$(window).load(function() {
  
  mainHeight = $("#main").css("height");
  mainHeight = Number(mainHeight.substring(0, mainHeight.length-2));
  $("#box1").css("min-height", mainHeight+"px");   
})




var url = window.location;
if (url.href.substring(0,8)=="http://b"){
	window.location="http://www.basebooking.ru";	
}

</script>

</head>
<body>

<div id="centered">
<?php printHeader(); ?>
<br><br>
  <div id="main" >
  <div id ="top-part">
  		<div id="right">
  			<div id="text">
  			<strong>Basebooking</strong> – бесплатный сервис онлайн бронирования репетиционных баз и студий. 
        Всего за несколько кликов вы сможете забронировать репетицию, а также найти текущую информацию 
        о расписании, ценах и наличии свободного времени.
        <br/><br/>
        <strong>If you are looking for english prototype please proceed <a href="http://www.basebooking.ru/prototype/DemoStudio">here.</a></strong>
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

</div> 

<div id="fotorama">
    <img src="http://www.basebooking.ru/img/mus.jpg" />
    <img src="http://www.basebooking.ru/img/adm.jpg" />
  </div>

  <div class="mainheader">4 Принципа Basebooking</div>  
  <div class="maindes prince">
    <span>Быстрота</span>
    Бронирования, одобрения и отклонения репетиций, блокировка музыкантов все происходит в доли секунд с автоматическим уведомлением всех сторон. 
    </div>
  <div class="maindes prince">
    <span>Надежность</span>
    Все данные хранятся в облаке. Также вы сможете блокировать недобросовестных музыкантов, вести статистику неприходов.
    </div>
  <div class="maindes prince">
    <span>Безопасность</span>
    На Basebooking используется авторизация музыкантов через Вконтакте, поэтому вы всегда сможете проверить персональные данные музыканта.
   </div>
  <div class="maindes prince" style="border:none;">
    <span>Никакой Рекламы</span>
    Когда музыканты заходят на страницу Вашей базы, можете быть уверены, что они не будут видеть какие либо баннеры других баз или студий.
    </div>

  </div><!--mainbox -->
<?php printFooter(); 
  mysql_close();
?>

</div><!-- centered -->
</body>
</html>