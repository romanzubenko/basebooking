<?php session_start(); 
include "../utils.php";
if  (isset($_SESSION['login'])) {
  header('Location: http://www.basebooking.ru/admin/');
}

if  (isset($_SESSION['vkid'])) {
 header('Location: http://www.basebooking.ru/musician/');
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - Вход для партнеров</title>
<link rel="stylesheet" type="text/css" href="../styles/styles.css">
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?45"></script>
<style>
#cent{width:650px; margin:0 auto;}
#inf2 ul {margin-top:-5px;}
#vk_auth{float:left;}
.element span{color:#444;font-size:9pt;width:280px;margin-left:10px;display:block;float:left;}
.errorenter {
  float:left;
  width: 618px;
  height: 28px;
  background-color: #F57369;
  padding-top: 10px;
  text-align: center;
  margin-left: 12px;
  border: 1px solid #9E1111;
  border-radius: 2px;
}
</style>
</head>
<body>
<script type="text/javascript">
  VK.init({apiId: 2388317});
</script>
<div id="centered">
<?php printHeader(); ?>
<br /><br />
  <div id="cent">
    <div class="space"></div> 
    <?php 
      if ($_GET['act'] == "2") {
        print("<div class=\"errorenter\">Вы неправильно ввели логин или пароль.</div>");
      }


    ?>
    <div class="element" style="width:300px; height:220px">
       <div class="header">Вход для Музыкантов </div>
        <div class="container"> 
          <div id="vk_auth"></div>
          <script type="text/javascript">
          VK.Widgets.Auth("vk_auth", { 
          	width: "300px", 
          	onAuth: function(data) {
          	  $('input[name*="name"]').val(data['first_name']);
                $('input[name*="vkid"]').val(data['uid']);
                $('input[name*="lastname"]').val(data['last_name']);
                $('input[name*="hash"]').val(data['hash']);
                $("form")[1].submit();
          	
              }
          });
          </script>
          <form id="enter" action="testreg.php" method="POST" >
          <input type="hidden" name="name" ></input>
          <input type="hidden" name="vkid" ></input>
          <input type="hidden" name="lastname" ></input>
          <input type="hidden" name="hash" ></input>
          </form>
        </div>
<div class="enterNote">
Если вы еще не зарегистрированы на сайте, просто нажмите "Войти через Вконтакте" и регистрация произойдет автоматически.
</div>
</div>
    
<div class="element" style="width:300px; height:220px">
  <div class="header">Вход для Партнеров </div>
    <div class="container">
      <div id="adminenter"> 
        <div style="width:46px;padding-top: 7px;">
          <span style="margin-top: 4px;">Логин:</span>
          <span style="margin-top: 15px;">Пароль:</span>
        </div>

        <div style="width:176px;">
          <form action="testreg.php" method="post">
            <span><input name="login" type="text"/></span>
            <span><input type="password" name="password"/></span>
            <span><input style="width: 172px;height: 30px;" class="button" type="submit" name="submit" value="Войти" /></span>
          </form> 
        </div>
    </div>
  </div>  
  <div class="enterNote">
      Если вашей базы или студии еще нет на сайте, вы можете добавить свою базу <a href="http://www.basebooking.ru/partners">здесь</a>.
  </div>
</div>


</div><!centered>
</body>
</html>