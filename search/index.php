<?php  session_start(); 
include "../utils.php";


/* GET SEARCH INPUTS */
$s = mb_strtolower($_GET['search'],'UTF-8');
$town = mb_strtolower($_GET['town'],'UTF-8');
$station = mb_strtolower($_GET['station'],'UTF-8');

$s = check($s);
$town = check($town);
$station = check($station);
   

if (isset($_GET['t1']) || isset($_GET['t2'])){
  $typecheck = 1;
  $t1 = $_GET['t1'];
  $t2 = $_GET['t2'];
  if ( $t1 == 1 ) {
    $typecheck = 1;
  }
  if ( $t2 == 1 ) {
    $typecheck = 2;
  }
  if ($t1 == 1 && $t2 == 1) {
    $typecheck = 0;
  }
 }

/* GET SEARCH INPUTS END */ 
$bases = getBases();


/*  CACHE SEARCH PROTOTYPE */
$args = Array();
$args[0] = $s;
$args[1] = $town;
$args[2] = $station;
$args[3] = $typecheck;


if (time() > $_SESSION['searchExp']) {
 // $_SESSION['searchArgs'] = $args;
 // $_SESSION['search'] = $bases;
 // $_SESSION['searchExp'] = time() + 10;
  
  shuffle($bases);
  $htmlBases = allBasesOut($bases);
} else {
  
  if (($_GET['town'] == "" && $_GET['search'] == "" && $_GET['station'] == "") || empty($_GET)) {
    shuffle($bases);
    
    $htmlBases = allBasesOut($bases);
  } else {
    $bases = searchBases($bases,$town,$station,$s,$typecheck);
    $bases = sortBases($bases);
    $htmlBases = allBasesOut($bases);
  }
/*
  $_SESSION['searchArgs'] = $args;
  $_SESSION['search'] = $bases;
  $_SESSION['searchExp'] = time() + 10;
*/
}

/*  CACHE SEARCH PROTOTYPE */


?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Basebooking - Поиск Баз и Репетиционных Студий</title>
<link rel="stylesheet" type="text/css" href="http://basebooking.ru/styles/styles.css">
<link rel="shortcut icon"href="http://basebooking.ru/favicon.ico" />
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/ajax.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/paginator.js"></script>
<style>
#box1{width:665px;margin-left:0px;border-right:1px solid #d6d6d6;margin-top:0px; }
#box2{width:287px;margin-left:0px;min-height:142px;}
.base span{color:#666;width:665px;}
.base{border-bottom:1px solid #d1d1d1;height:132px;}

#main{border-radius:0;border-bottom-left-radius:8px;border-bottom-right-radius:8px;}
.g1 {float:left;margin-top:10px;margin-left:5px; width:657px;}
.g1 input {float:left;display:block;margin-left:5px; width:653px;border:1px solid #d1d1d1; height:30px;
font-family:"Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;font-size:11pt;}
.g1 input:focus {outline: none;}
#search2,#search3 {float:left; margin-top:10px;font-size:9pt;width:100%;}
#search2 input{font-family:"Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;font-size:11pt;float:left;display:block;margin-left:5px; width:250px;border:1px solid #d1d1d1; height:30px; }
#search2 table {margin-left:8px;}
.photo {height:80px; width:80px; float:left;}

#topbar { float:left;height:55px;background:-webkit-linear-gradient(top,#FBFBFB,#EFEFEF);border-bottom:1px solid #d1d1d1;width:100%;}
.sub {float:left;margin-left:14px;margin-top:15px;font-size:14pt;font-family: "HelveticaNeue-Light","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;cursor:pointer;}
.sub:hover {color:#08c;}
img {display:none;}
.photo,.ph {margin-left:11px;}
#exp {margin-left:12px; font-size:10pt;}
#exp {display:block;width:275px;}
#exp tr {width:265px;display:block;}
#exp td {display:block;width:128px;float:left;}
#exp .check {width:128px;}
#search3 {magin-top:0px;border-bottom:1px solid #d1d1d1;}



</style>
<script type="text/javascript">
function resizeBody() {

  var tempHeight = $("#box2").css("height"),
  tempHeight1 = $("#box1").css("height");

  tempHeight = Number(tempHeight.substring(0,tempHeight.length - 2));
  tempHeight1 = Number(tempHeight1.substring(0,tempHeight1.length-2));
  
  if (tempHeight > tempHeight1){
    $("#main").css("height",tempHeight+60+"px");
  } else {
    $("#main").css("height",tempHeight1+60+"px");
  }
}

$(window).load(function () {
 
	var max_size = 80;
  $("img").each(function(i) { 
    if ($(this).height() !== 20) {
      var w = max_size;
      var h = Math.ceil($(this).height() / $(this).width() * max_size);
      $(this).css({ height: h, width: w, display : "inline"  });
    }  else {
      $(this).css("display","block");
    }
  })

  resizeBody();
});



$(".sub").live("click" , function () {
  $("form")[1].submit();
})

$("#si0").live('keydown',function(e){ 
  if (e.which == 13) {
    $("form")[1].submit();
  }
})

$("#si1").live('keydown',function(e){ 
  if (e.which == 13) {
    $("form")[1].submit();
  }
})

$("#si2").live('keydown',function(e){ 
  if (e.which == 13) {
    $("form")[1].submit();
  }
})

</script>
</head>
<body>
<div id="centered">
<?php printHeader(); ?>
<br /><br />
<div class="space"></div> 
   <div id="main"><form action="http://www.basebooking.ru/search/index.php" method="get">

  <div id="topbar">
    <div class="g1">
        <input type="text" id ="si0" name="search" placeholder="Название базы, город, адрес, оборудование или другие ключевые слова" 
        <?php if (!empty($s)){
          $s = clears($s);
          echo"value=\"{$s}\"";}?>/>
    </div>
    <div class="sub">Найти</div>
  </div>
  <div id="box1">

   <?php print($htmlBases);?>

  </div><!box1>
  <div id="box2">
  <div id="search2">
    <table>
      <tr><td><input type="text" id="si1" name="town" placeholder="Город" <?php if (!empty($town)){
        $town = clears($town);
        echo"value=\"{$town}\"";}?>/></td></tr>
      <tr><td><input type="text" id="si2" name="station" placeholder="Станция Метро" <?php if (!empty($station)){
$station = clears($station);
        echo"value=\"{$station}\"";}?>/></td></tr>
    </table>
  </div>
  <div id="search3">
   <table id="exp">
    <tr>
      <td>Репетиционные базы</td><td><input type="checkbox" class="check" name="t1" value="1" <?php if ($t1==1) {echo "checked";} ?> /></td>
    </tr>
    
    <tr>
    <td>Студии</td><td><input type="checkbox" name="t2" class="check" value="1" <?php if ($t2==1) {echo "checked";} ?> /></td>
    </tr>
  
    </table>
  </div>
  
  </div><!box2></form>
 </div><!main>
 <?php printFooter(); ?>
 </div>
 </body>
 </html>