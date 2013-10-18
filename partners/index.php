<?php 
  session_start();
  include "../utils.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - Партнерам</title>
<link rel="stylesheet" type="text/css" href="http://www.basebooking.ru/styles/styles.css" />
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script> 
<script type="text/javascript" src="http://basebooking.ru/js/query.js"></script>
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<style>



.submit {float:left;width:402px;height:50px;margin-top:10px;font-size:15px;
}

.submit span {float:left;margin-top:15px;text-align:center;width:100%;}
#register {width:100%;float:left;height:320px;border-bottom:1px solid #d1d1d1;background-color:#fafafa;
    border-top-left-radius : 8px; border-top-right-radius: 8px;


}
#col1,#col2{margin-top:25px;height:370px;float:left;margin-left:20px;}
#col1 {
 margin-top:163px; 
margin-left:35px;
  width:450px;
  font-size:14pt;
  font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
  line-height: 1.7em;
}
#col2 {margin-left:20px;width:428px;font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;font-size:11pt;}
#form input, #form select{display:block;width:200px;height:25px;border:1px solid #d1d1d1;float:left;font-size:10pt;}
#form select {width:204px;}
.cell {width:100%;margin-top:5px;height:27px;float:left;}
.cell span {display:block;float:left;margin-top:5px;width:200px;height:24;}
.text1, .text2 {float:left;font-size:10pt; width:450px;margin-left:20px;
font-family: "Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
line-height: 1.62em; margin-top:35px; } 

.text1 .header, .text2 .header {width:100%;float:left;height:30px;font-size:13pt;
  color:#333;
letter-spacing: -.05em;
text-shadow: 0 1px 1px #f1f1f1;} 

</style>

</head>

<body>

<div id="centered">
<?php 
 printHeader();
?>
<br /><br />
  <div id="main">   
     <div id="register">
     <div id="col2">
         <div id ="response"></div>
         <div id="form">
          <div class="cell">  <span>Название базы или студии:</span> <input name="name" type="text"/></div>
          <div class="cell">  <span>Ссылка Вконтакте:</span>  <input name="vk" type="text"/></div>
          <div class="cell">  <span>Телефон:</span>  <input name="phone" type="text"/></div>
          <div class="cell">  <span>Контактный email:</span>  <input name="email" type="text"/></div>
          <div class="cell">  <span>Вебсайт базы:</span>  <input name="website" type="text"/></div>
          <div class="cell">  <span>Тип:</span>  
                 <select name="type"> 
                <option value="1">Репетиционная база</option>
                  <option value="2">Студия</option>
                  <option value="3">Репетиционная база и студия</option>
                </select><br />       
            </div>
            <div class="submit button" class="cell"><span><strong>Отправить заявку</strong></span></div>
         </div>  
       </div>
         <div id="col1">
             Если вашей базы или студии еще нет на сайте и вы хотите присоединиться к проекту, просто заполните заявку и в течении дня мы добавим вашу базу на сайт.
         </div>    
       
    </div>        

        <div class="text1"> 

<div class="header"><strong>Basebooking - это просто!</strong></div> <br  />
Бесплатное размещение страницы вашей базы/студии с легко загружаемыми фотографиями, списком оборудования, 
контактными данными и размещением дополнительной информации.<br  /><br  />

Создание удобного автоматически обновляемого онлайн-расписания, доступного для каждого пользователя.
<br  /><br  />

<div class="header"><strong>Онлайн бронирование</strong></div><br  />
Онлайн-бронирование репетиций и звукозаписи: всего за несколько кликов музыкант сможет найти вашу базу и
 забронировать свободное время. Музыкантам не требуется регистрация на сайте, достаточно лишь иметь аккаунт 
 Вконтакте<br  /><br  />

Моментальное бронирование репетиционного или студийного времени с сохранением данных учетной записи 
«Вконтакте» музыкантов.<br  /><br  />




</div>

 <div class="text2">

<div class="header"><strong>История бронирований и черный список</strong></div><br  />
Доступ к истории бронирований пользователя: теперь базы могут отслеживать «добросовестность» музыканта – 
количество юронированных репетиций и их прогулов, что позволяет обезопасить Вас от недобросовестных 
пользователей.<br  /><br  />

При неявке музыканта на репетицию, вы можете добавить его как и в черный список, так и в список должников 
за потраченное впустую время.<br  /><br  />

Basebooking - это экономия времени. Все что вы делали раньше вручную теперь происходит автоматически! 
Наш сайт абслютно бесплатен.
 </div> 
  

      
  </div><! main>
<?php 
 printFooter();
?>
</div><! centered>
</body>
</html>