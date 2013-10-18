<?php  session_start(); 
include "utils.php";?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - 404<?php $b =  clears($_GET['name']); echo $b; ?></title>
<link href="http://basebooking.ru/styles/fotorama.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/styles.css" />
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/baseStyles.css" />
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?32"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<style>
  #basename {
    margin-left: 101px;
  }
  #box1 {
    border-right:none;
    width:100%;
  }
  .mainheader {
    font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;
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

  img {
    width:955px;
    height:573px;
  }


</style>
<head>
<body>
<div id="centered">

<?php printHeader(); ?>
<div class="space"></div>
<div class="space"></div>
  <br/>
<div id="basename">Кажется вы попали не туда!</div>
 <div class="space"></div>
  <div class="topline">
    
    


    <div class="ribbon">
      
    </div>   
    <div class="triangle-l"></div> 
    <div class="triangle-r"></div>
  </div>   
  <div id="main"> 
  <div class="mainheader" style="font-size: 15px;">Наши лучшие силы (Бэтмэн) отправлены на поиски той страницы, которую вы ищете. Пока же вы можете перейти обратно на <a href="http://www.basebooking.ru">главную</a>.</div>
  <img src="http://www.basebooking.ru/img/batman.gif" />
   <div class="mainheader">Больше заурядных приключений Бэтмена вы можете посмотреть <a target="_blank" href="http://sarahj-art.tumblr.com/tagged/OrdinaryBatman/">тут</a>.</div>

 

  
 </div><!--main-->
  <?php 
  printFooter();
  ?>
 </div><!--centered-->
 </body>
 </html>