<?php session_start();
include "../utils.php";

if (!isset($idb) || empty($idb)) {
  header('Location: http://www.basebooking.ru/');
}

  function getDefaults($idb) {
    $defaults = Array();
    $defaults[0] = getBaseName($idb);
    $r = mysql_query("SELECT * from bases where id='$idb'");
    if (!$r) {
      header('Location: http://www.basebooking.ru/');
    }

    $r = mysql_fetch_array($r);
    $defaults[1] = $r['town'];
    $defaults[2] = $r['phone'];
    $defaults[3] = $r['timezone'];
    $defaults[4] = $r['komn'];

    return $defaults;
  } 

$defaults = json_encode(getDefaults($idb));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - Мастер Настройки</title>
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/styles.css" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/adminStyles.css" />
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script> 
<script type="text/javascript" src="http://basebooking.ru/js/ajax.js"></script> 
<style>



#main {
  width:785px;
  min-height:50px;

 margin-left: 0;
  overflow: hidden;
  border-radius: 4px;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;

}

#steps {
  width: 785px;
  height: 67px;
  font-family: "Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
  font-size: 42pt;
  color: #AAA;
  padding-bottom: 0px;
  border-bottom: 1px solid #D1D1D1;
}

#steps span {
  border-left: 1px solid #D1D1D1;
  text-align: center;
  display: inline-block;
  width: 195px;

}
#steps span:first-child {
  color: #123;
  border-left: none;
}

#col1 {
 margin-top:163px; 
  margin-left:35px;
  width:450px;
  font-size:14pt;
  font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
  line-height: 1.7em;
}

#navigation {
  width: 100%;
  border-top: 1px solid #D1D1D1;
  height: 70px;

}

#navigation .button {
  width: 89px;
  height: 22px;
  margin-top: 20px;
  margin-right: 30px;
  text-align: center;
  margin-left: 30px;
  padding-top: 6px;
  box-shadow:none;
}

.button.next {
  float: right;
}

#barframe {
  height:4px;
  width:100%;
  background-color:#f3f3f3;
}

#tourHeader {
  width: 100%;
  height: 100px;
  text-align: center;
  color: #123;
  font: 14px/20px helvetica,arial,sans-serif;
  font-size: 63px;
  line-height: 1.2em;
}

#bar{
  height:4px;
  width:785px;
  background-color:#26DE26;
  margin-left:-785px;
}

#page {
  width: 100%;
  min-height: 100px;
  padding-bottom: 30px;
  background-color: #FCFCFC;
  font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
}

#stepHeader {
  width: 100%;
  padding-top: 17px;
  height: 39px;
  margin: 0 auto;
  font-size: 24px;
  text-align: center;
  border-bottom: 1px solid #E1E1E1;
}

.innerpage {
  padding-top: 30px;
}

.inputSection {
 width: 80%;
  margin: 0 auto;
  height: 58px;
  padding-bottom: 5px;
}

.inputSection .subsection {
  height: 20px;
  padding-top: 5px;
}

.inputSection .subsection input {
  height: 26px;
  border: 1px solid #D1D1D1;
  width: 100%;
  border-radius: 3px;
}

.m1, .m2 {
  margin-left:10px;
  width: 278px;
  display: inline-block;
  text-align: center;
  padding: 10px;
  height: 140px;
  background-color:#f6f6f6;
  -webkit-transition:background-color 0.1s linear; 
  transition:background-color 0.1s linear; 
  cursor:pointer;
  font-size: 11pt;
  margin-top:5px;
}

.m1:hover, .m2:hover {
  background-color:#ccc;
}

.schedule, .addButton {
  margin-left: 4px;
}

.m1 span, .m2 span {
  display: block;
  margin-top: 18px;
  text-align: left;
  font-size: 10pt;
}

.finallink {
  text-align: center;
  margin-top: 30px;
  color: #08C;
  font-size: 14pt;
}

.final {
  width: 80%;
  margin: 0 auto;
  padding: 10px;
  border: 4px solid #F1F1F1;
}

.tourdes span {
  text-align: center;
  width: 100%;
  display: block;
  height: 30px;
  padding-top: 14px;
  font-size: 11pt;
}

.tourdes {
  width: 144px;
  background-color: 
  white;
  min-height: 328px;
  float: left;
  margin-top: 21px;
  border: 1px solid 
  #D1D1D1;
  margin-left: 2px;
  padding: 10px;
  font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
}

</style>
<script>

function resizeBody(){
  var height = parseInt($("#main").css("height")) - 21;
  $(".tourdes").css("height",height+"px");
}
  

function formatTime(x) {
  var time = "";
  var h = Math.floor(x/60);
  var m = x - h*60;
  h = leadzero(h);
  m = leadzero(m);
  time = h+":"+m;
  return time;
}

function leadzero(x) {
  if (x < 10) {
    x = "0"+x;
  } 
  return x;
}

/*
Data0 -> switch
1 -> page1
2 -> page2

*/

var send = function (data,tour) {
  
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) { 
      var response = xmlHttp.responseText,
      responseArr = [];
      responseArr = response.split(',');
      if (responseArr[0] == 1) {
        tour.nextPageConfirm();
      } else {

        if (responseArr[1] == 1) {
          tour.message(9);
        }
        if (responseArr[1] == 2) {
          tour.message(10);
        }
        if (responseArr[1] == 3) {
          tour.message(1);
        }
        if (responseArr[1] == 4) {
          tour.message(11);
        }
        if (responseArr[1] == 5) { 
          tour.message(3);
        }
        if (responseArr[1] == 6) {
          tour.message(12);
        }

        overlay(1,"Error");
        
      }
    }
  }

  xmlHttp.open("POST", "http://www.basebooking.ru/tour/tour.php?r=" + Math.random(), true);
  xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlHttp.send("data0="+data[0]+"&data1="+data[1]+"&data2="+data[2]+"&data3="+data[3]+"&data4="+data[4]+"&data5="+data[5]);
}


$(function(){
  var tour, step;

  tour = function (defaults) {
    this.steps = [];
    this.currentStep = 1;
    this.nextP = $(".next");
    this.prevP = $(".prev");
    this.bar = $("#bar");
    this.page = $("#page");
    this.header = $("#stepHeader");
    this.pages = [];
    this.pages[0] = $("<div />",{class: "innerpage"});
    this.pages[1] = $("<div />",{class: "innerpage"});
    this.pages[2] = $("<div />",{class: "innerpage"});
    this.pages[3] = $("<div />",{class: "innerpage"});
    this.descriptions = [];


    this.name = defaults[0];
    this.password = "";
    this.city = defaults[1];
    this.phone = defaults[2];
    this.timezone = defaults[3];
    this.rooms = defaults[4];
    this.mode = "";
    this.schSend = false;



    var p1 = ""+
      "<div class=\"inputSection\">"+
       "<div class=\"subsection\">"+
          "Название базы:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"text\" name=\"name\" value=\""+this.name+"\"/>"+
        "</div>"+
      "</div>"+
      "<div class=\"inputSection\">"+
        "<div class=\"subsection\">"+
          "Введите новый пароль:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"password\" name=\"p1\" value=\""+this.password+"\"/>"+
        "</div>"+
      "</div>"+
      "<div class=\"inputSection\">"+
        "<div class=\"subsection\">"+
          "Повторите пароль:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"password\" name=\"p2\" value=\""+this.password+"\"/>"+
        "</div>"+
      "</div>"+
    "",
    p2 = ""+
      "<div class=\"inputSection\">"+
       "<div class=\"subsection\">"+
          "Город:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"text\" name=\"city\" value=\""+this.city+"\"/>"+
        "</div>"+
      "</div>"+
      
      "<div class=\"inputSection\">"+
        "<div class=\"subsection\">"+
          "Контактный телефон:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"text\" name=\"phone\" value=\""+this.phone+"\"/>"+
        "</div>"+
      "</div>"+

      "<div class=\"inputSection\">"+
        "<div class=\"subsection\">"+
          "Часовой пояс:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"text\" name=\"timezone\" value=\""+this.timezone+"\"/>"+
        "</div>"+
      "</div>"+

      "<div class=\"inputSection\">"+
        "<div class=\"subsection\">"+
          "Количество комнат на базе или студии:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<input type=\"text\" name=\"rooms\" value=\""+this.rooms+"\"/>"+
        "</div>"+
      "</div>"+

      "<div class=\"inputSection\" style=\"height: 200px;\">"+
        "<div class=\"subsection\" style=\"text-align: center;padding-top: 20px;font-size:12pt\">"+
          "Тип бронирования:"+
        "</div>"+
        "<div class=\"subsection\">"+
          "<div class=\"m1\">"+
          "Бронирование по заявкам"+
          "<span>Данный вид бронирования подходит Вам, если вы хотите одобрять/отклонять  заявки на репетиции. Заявка на репетицию не будет активна до её одобрения Вами.</span>"+
          "</div>"+
          "<div class=\"m2\">"+
          "Прямое бронирование"+
          "<span>Музыканты смогут напрямую бронировать репетиции без дополнительных действий с Вашей стороны. Вы всегда можете отменить репетицию по каким либо причинам.</span>"+
          "</div>"+
        "</div>"+
      "</div>"+
    "",
    p3 = "",
    p4 = ""+
    "<div class=\"final\">"+
      "<div class=\"d\">"+
      "Вы успешно настроили бронирование на Basebooking.ru. Чтобы начать пользоваться онлайн бронированием, Вам достаточно лишь дать ссылку на страницу вашей базы. Напоминаем, что наш сервис абсолютно бесплатен!"+
      "</div>"+
      "<div class=\"finallink\">"+
      "www.basebooking.ru/base/"+this.name+
      "</div>"+
    "</div>",
    d1 = "<span>Знаете ли вы?</span>Чтобы забронировать репетицию на Basebooking, музыкант должен авторизоваться В контакте. Администраторам базы приходит уведомление о том, что кто-то забронировал время, в котором содержится вся контактная информация и ссылка на его профиль Вконтакте.",
    d2 = "<span>Знаете ли вы?</span>Информация о заявке содержит статистику бронирований музыканта, подавшего заявку. В ней отображается общее количество забронированных репетиций и количество их посещений. Если у вас есть сомнения по поводу добросовестности музыканта, вы всегда сможете позвонить и ему и уточнить все вопросы. Если вы решили аннулировать заявку и, таким образом, отменить репетицию, музыканту придет соответствующее оповещение",
    d3 = "<span>Знаете ли вы?</span>В том случае, если группа не пришла, вы можете нажать соответствующую кнопку. Не пришедших на репетиции музыкантов можно добавить в список должников или в черный список. Добавление в первый список приведет к тому, что при последующих бронированиях этим музыкантом вы получите напоминание о его задолженности. Добавление в черный список закроет перед музыкантом возможность подавать заявки на репетиции на вашей базе впредь.",
    d4 = "<span>Знаете ли вы?</span>";
    this.descriptions[0] = d1;
    this.descriptions[1] = d2;
    this.descriptions[2] = d3;
    this.descriptions[3] = d4;
   
    this.pages[0].html(p1);
    this.pages[1].html(p2);
    this.pages[2].html(p3);
    this.pages[3].html(p4);
    this.pages[0].appendTo(this.page);
    $(".tourdes").html(this.descriptions[this.currentStep-1]);
    resizeBody();

    this.steps = ["Имя и Пароль","Настройки Бронирования","Расписания","Начните онлайн бронирование прямо сейчас!"];


    var tour = this;
    this.bar.tour = tour;

    $(".m1").live("click", tour, function() {
      tour.mode = 1;
      $(this).css("background-color","#333").css("color","#fff");
      $(".m2").css("background-color","#f6f6f6").css("color","#000");
    })

    $(".m2").live("click", tour, function() {
      tour.mode = 2;
      $(this).css("background-color","#333").css("color","#fff");
      $(".m1").css("background-color","#f6f6f6").css("color","#000");
    })

    $(".saveSch").live("click", tour, function() {
      tour.schSend = true;
    })

    this.nextP.live("click", tour, function() {
      tour.nextStep();
    });

    this.prevP.live("click", tour, function() {
      tour.prevStep();
    });
    this.header.html(this.steps[this.currentStep - 1]);
  }

  tour.prototype.nextStep = function () {
    if (this.currentStep > 4) {
      return false;
    }

    if (this.currentStep === 4) {
      window.location = "http://www.basebooking.ru/admin";
    }

    if (!this.checkPage()) {
      return false;
    }


    this.sendInfo();

  }
  tour.prototype.nextPageConfirm = function() {
    this.currentStep = this.currentStep + 1;
    this.header.html(this.steps[this.currentStep - 1]);
    var margin = this.bar.css("margin-left"),
    tour = this;
    margin = parseInt(margin);
    margin = (margin + 196) + "px";
    
    this.bar.animate({"margin-left": margin},900,function(){
      var selector = "#s"+tour.currentStep;
      $(selector).css("color", "#123");
    });

    $(".innerpage").remove();
    if (this.currentStep == 1 || this.currentStep == 2 || this.currentStep == 4) {
      this.pages[this.currentStep - 1].appendTo(this.page);
    } 

    if (this.currentStep == 3) {
      $("<div />",{class: "innerpage"}).appendTo(this.page);
      scheduleFactory("",$(".innerpage"),this.rooms);
      $(".innerpage, #page, #navigation").css("float","left");
      $(".addButton").click();
    }
    

    if (this.currentStep === 4 || this.currentStep === 5) {
      this.nextP.html("Готово");
    } else {
      this.nextP.html("След");
    }

    $(".tourdes").html(this.descriptions[this.currentStep-1]);
    resizeBody();
  }

  tour.prototype.prevStep = function () {
    if (this.currentStep < 2) {
      return false;
    }
    
    this.currentStep = this.currentStep - 1;

    this.header.html(this.steps[this.currentStep - 1]);
    var margin = this.bar.css("margin-left"),
    tour = this;
    margin = parseInt(margin);
    margin = (margin - 196) + "px";
    
    this.bar.animate({"margin-left": margin},900,function(){
      var selector = "#s"+ (tour.currentStep + 1);
      $(selector).css("color", "#aaa");
    });

    if (this.currentStep === 4 || this.currentStep === 5) {
      this.nextP.html("Готово");
    } else {
      this.nextP.html("След");
    }

  }

   tour.prototype.message = function (x) {
    var str = "";
    switch(x) {
    case 1 : 
      str = "Название базы слишком короткое!";
      break;
    case 2 : 
      str = "Пароли не совпадают";
      break;
    case 3 : 
      str = "Пароль должен быть минимум 5 символов";
      break;
    case 4 : 
      str = "Количество комнат не может быть больше 20";
      break;
    case 5 : 
      str = "Вы не выбрали тип бронирования";
      break;
    case 6 : 
      str = "";
      break;
    case 7 : 
      str = "Часовой пояс должен быть от - 12 до +12";
      break;
    case 8 : 
      str = "Создайте хотя бы одно расписание!";
      break;
    case 9 : 
      str = "База с таким названием уже существует!";
      break;
    case 10 : 
      str = "Ошибка обновления базы данный попробуйте снова.";
      break;
    case 11 : 
      str = "Ошибка обновления пароля.";
      break;
    case 12 : 
      str = "Ошибка обновления страницы 2, попробуйте снова.";
      break;
    }
    overlay(2,"");
    overlay(1,str);
    setTimeout(function () {
      overlay(2,"");
    },1700);

   }

  tour.prototype.sendInfo = function () {
    var data =[],
    tour = this;

    switch(this.currentStep) {
    case 1:
      data[0] = 1;
      data[1] = this.name;
      data[2] = this.password;
      data[3] = 0;
      data[4] = 0;
      data[5] = 0;
      return send(data,tour); 
      break;
    case 2:
      data[0] = 2;
      data[1] = this.city;
      data[2] = this.phone;
      data[3] = this.timezone;
      data[4] = this.rooms;
      data[5] = this.mode;
      return send(data,tour);  
      break;
    case 3:
    this.nextPageConfirm();
    return true;
      break;
    case 4:
    return true;
      break;
    }
  }

  tour.prototype.checkPage = function () {
    switch(this.currentStep) {
    case 1:
      var name = $('input[name*="name"]').val(),
      p1 = $('input[name*="p1"]').val(),
      p2 = $('input[name*="p2"]').val();


      if (name === undefined || name.length < 2 ) {
        this.message(1);
        
        return false;
      }

      if (p1.length < 5 || p1 === undefined) {
        this.message(3);
        return false;
      }

      if (p1 !== p2) {
        this.message(2);
        return false;
      }

      this.name = name;
      this.password = p1;

      return true;
      break;
    
    case 2:
      var city = $('input[name*="city"]').val(),
      phone = $('input[name*="phone"]').val(),
      timezone = parseInt($('input[name*="timezone"]').val()),
      rooms = parseInt($('input[name*="rooms"]').val());
    
    if (rooms < 1 || rooms > 20) {
      this.message(4);
      return false;
    }

    if (timezone < -12 || timezone > 12) {
      this.message(7);
      return false;
    }

    if (this.mode === "") {
      this.message(5);
      return false;
    }

      this.city = city;
      this.phone = phone;
      this.timezone = timezone;
      this.rooms = rooms;

      return true;
      break;
    case 3:
      if (!this.schSend) {
        this.message(8);
        return false;
      }
      return true;
      break;
    case 4:
    return true;
      break;
    }
  }



  window.tour = tour;
}(window));

$(window).load(function(){
  <?php 
    print("var defaults =");
    print($defaults.";");
  ?>
  tour = new tour(defaults);
})


</script>
</head>
<body>
<div id="centered">
<?php 
 printHeader();
?>
<br /><br />
  
  <br />
  <div id="tourHeader">Настройте базу за 4 шага</div>
  <div id="main">
    <div id="steps">  
    <span id="s1">1</span><span id="s2">2</span><span id="s3">3</span><span id="s4">4</span>
  </div>
  <div id="barframe"><div id="bar"></div></div> 

  <div id="page">
    <div id="stepHeader"></div>

  </div>

  <div id="navigation">
    <div class="button next">След</div>
    <div class="button prev" style="display:none">Пред</div>
  </div>
  </div><!--main -->
  <div class="tourdes"><span>Знаете ли вы?</span></div>
<?php 
 printFooter();
?>
</div><!--centered-->
</body>
</html>