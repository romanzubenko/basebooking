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
<style>
.submit {float:left;width:402px;border:1px solid #d1d1d1;height:50px;background-color:#e5e5e5; color:#333; font-size:10pt;cursor:pointer;margin-top:10px;font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;font-size:11pt;}
.submit:hover{ background-color:#f1f1f1;}
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
line-height: 1.62em; margin-top:20px; } 

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
<div class="space"></div> 
  <div id="main">   

        <div class="text1"> 

<div class="header"><strong>Basebooking</strong></div> <br  />
Basebooking - молодой стартап предоставляющий сервис онлайн-бронирования репетиционным базам и студиям. 
<br /><br />
Все началось примерно год назад, когда мы были активно репетирующими музыкантами. Нам показалось, что процесс 
бронирования можно упростить сделав его онлайн, а также повысить надежность заменив блокноты на базу данных в 
облаке. После практически года разработки, basebooking работает более чем с 20 базами, продолжает расти и улучшаться.  
<br /><br />
Следить с разработкой, обновлениями и новостями проекта можно через наш паблик Вконтакте - 
<a href="http://vk.com/basebooking">vk.com/basebooking</a>. Там же можно задать любые вопросы и высказать предложения.


<br />

</div>

 <div class="text2">

<div class="header"><strong>Наша Команда</strong></div><br  />

Мы - это всего два человека. Оставаясь небольшими, мы можем быстро разрабатывать интересный 
продукт, иметь тесный личный контакт с нашими клиентами, а также в течении минимального времени улучшать уже 
уществующие функции сайта и добавлять новые по просьбам администраторов баз и музыкантов.
<br /><br />
<a href="http://vk.com/roman.zubenko" target="_blanc">Роман Зубенко</a>, основатель и разработчик<br />
Cтудент Бостонского Университета
<br />
<a href="http://vk.com/id69670136" target="_blanc">Максим Ващенко</a>, партнер ответственный за работу с клиентами<br />
Студент МАДИ
<br /><br />
Также связаться с нами можно через почту: contact@basebooking.ru <br />
или по телефону +79060442125
</div> 
  
      
  </div><! main>
<?php 
 printFooter();
?>
</div><! centered>
</body>
</html>