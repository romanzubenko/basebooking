<?php 
  session_start();
  include "../utils.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
  <div id="main">   
    <script type="text/javascript" src="http://basebooking.tumblr.com/js"></script>
  </div><! main>
<?php 
 printFooter();
?>
</div><! centered>
</body>
</html>