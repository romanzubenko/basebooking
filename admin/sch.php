<?php session_start();
include "../utils.php";
if (isset($_SESSION['login'])) {$auth=1;}
if ($auth==1)
{ 

$pid=$_SESSION['pid'];
$idb=mysql_query("SELECT bases FROM users WHERE id='$pid'");
$idb=mysql_fetch_array($idb);
$idb=trim($idb['bases']);
$r=mysql_query("SELECT komn FROM bases where id='$idb'");
$r=mysql_fetch_array($r);
$komn=$r['komn'];
$r=mysql_query("SELECT rooms FROM {$idb}_schedule");
while($ra=mysql_fetch_array($r)){
$sch[]=$ra['rooms'];}
}
else {exit("<html><head></head><body><meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/\"></body></html>");}
 ?>
<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>

html {float:left;}
.elemento{height:auto; background-color: #FaFaFa;background-image:url(http://basebooking.ru/img/backel.png);background-repeat:repeat-x; box-shadow: 0 1px 3px rgba(0,0,0,0.5); -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);float:left;margin-top:1px; }
body {float:left;font-family:"Trebuchet MS", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif; font-size:13px;width:766px;}
.elemento, header1, table, form {float:left;}
form {width:100%;}
.elemento span {color:red;}
#add_sch form {float:left;min-height:260px;}
#add_sch {width:765px;background-color:#fafafa; background-image:url(http://basebooking.ru/img/lines.png);background-repeat:repeat-y;}
.komn {float:left;width:96px;min-height:270px;margin-top:10px;text-align:center}
.click {float:left;width:95px; height:50px; background-image:plus.png; margin: 0 auto; margin-top:20px; cursor:pointer;}
.newtime{float:left;width:95px;height:140px; margin: 0 auto; border-top:1px solid #bbb; margin-top:10px; margin-left:1px;}
.newtime select{display:inline;}
.newtime input{width:37px;}
.newtime input:last-child{width:84px;}
.save {float:left;margin:0 0 0 -1px; border:1px solid #ADBBCA; width:765px; height:40px; cursor:pointer;}
.save span{text-align:center; width:755px;display:block; height:30px;border:5px solid #DAE2E8;background-color:#fcfcfc; }
.save span:hover {background-color:#DAE2E8;}
.save span p {margin-top:5px;color:#235072}
.klast {width:90px; float:left;text-align:center; margin:0 auto; margin-top:10px;}
.klast span{color:#08c;}
.dialogue {width:765px;background-color:#222;opacity:0.4;display:none;position:absolute}
#s2 {width:300px; height:190px; background-color:#fafafa; position:absolute;display:none;top:52px;left:232px;border:1px solid #999;}
#s2 div {width:100%; margin-top:70px;text-align:center;}
#s2 div span:hover {color:#08c;cursor:pointer;}
.suc{width:741px; height:20px;background-color:#fafafa;  box-shadow: 0 1px 3px rgba(0,0,0,0.5); -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);float:left;text-align:center;padding:12px;}
</style>
<script type="text/javascript" src="http://basebooking.ru/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="http://basebooking.ru/js/jquery.ui.core.js"></script> 
<script type="text/javascript">
(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k="setTimeout",j="resize",d=j+"-special-event",b="delay",f="throttleWindow";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);
function is_numeric(mixed_var){ 
	return !isNaN(mixed_var) 
}
<?php 
if (count($sch)>0){echo"rooms=new Array(";
 for($i=0;$i<=count($sch);$i++){
  if (!empty($sch[$i])){ if (is_numeric($sch[$i])){
  echo"\"".$sch[$i]."\""; if ($i!=count($sch)-1) {echo",";}}
  
  }
 }
echo");"; }else {echo"rooms=new Array();";}?>
weekr = new Array("пн", "вт", "ср", "чт", "пт", "сб", "вс")
arr=new Array(0,0,0,0,0,0,0);
timeline=new Array (
new Array(new Array()),
new Array(new Array()),
new Array(new Array()),
new Array(new Array()),
new Array(new Array()),
new Array(new Array()),
new Array(new Array()));
count=0;
count1=0;
max=0; 

function countChecked() {
	  var n = $("input:checked").length;
return n;
	}
	
	$(":checkbox").click(countChecked);

$(".save").live("click", function(event) {

	serror=0;
	count1=0;
	for (var i=0;i<=6;i++){
		for (var j=1;j<=arr[i];j++){
			c=i+1;
			temp1="start_h_day"+c+"_"+j;
			temp2="end_h_day"+c+"_"+j;
			b1=Number($("input[name$="+temp1+"]").val());
			b3=Number( $("input[name$="+temp2+"]").val());
			temp1="start_m_day"+c+"_"+j;
			temp2="end_m_day"+c+"_"+j;
			b2=Number($("input[name$="+temp1+"]").val());
			b4=Number($("input[name$="+temp2+"]").val());
			a1=b1*100+b2;
			a2=b3*100+b4;
			name="#day"+c+"_"+j;
			timeline[i][j-1]=[a1,a2,name];

			if (b1<0 || b1>23 || b3<0 || b3>23 || b2<0 || b2>59 || b4<0 || b4>59 || !is_numeric(b1) || !is_numeric(b1) || !is_numeric(b1) || !is_numeric(b1) || a2<a1) { 
				$(name).css('background-color', '#fdd'); serror=1; 
			} else {
				$(name).css('background-color', '#fafafa');
			}
		}
	}


	for (var i = 0; i <= 6; i++){
 		if (arr[i] != 0){
  			for(var j = 0; j <= arr[i]-1; j++){
   				for(var e = 0; e <= arr[i]-1; e++){
	  				count = 0;
	  				if (j != e) {
						if (timeline[i][j][0]>timeline[i][e][0] && timeline[i][j][0]<timeline[i][e][1]) {
							count=2;
						}
						if (timeline[i][j][1]>timeline[i][e][0] && timeline[i][j][1]<timeline[i][e][1]) {
							count=2;
						}
						if (count!=0) {
							$(timeline[i][e][2]).css('background-color', '#fdd');
							count1=1;
						}
						if (count!=0) { 
							$(timeline[i][j][2]).css('background-color', '#fdd');
							count1=1;
						}
					}  
   				}     
  			}
 		}
	}
	
if (count1!=0) {
	a=$(".suc").html();a=a+"Репетиции выделенные красным не согласованы по времени!";
	$(".suc").html(a);
}


count2=0;
count2=countChecked();
checkedItems=$("input:checked");


smth=0;
for (var i=0;i<=6;i++){
	if (arr[i]>0){smth++;}
}
if (count2==0){x="!"; if (smth==0) {x=" и не добавили ни одного времени!";}$(".suc").html("Вы не выбрали ни одной комнаты"+x);$("#day_8").css('color', '#f33');}else {$("#day_8").css('color', '#444');$(".suc").html("");if (smth==0) {$(".suc").html("Вы не добавили ни одного времени!");}}

if (serror!=1 && count1==0 && count2!=0 && smth>0){
	if (count2>0){
		rooms1=new Array();
		for (var i=0;i<=count2-1;i++){
			a=Number(checkedItems[i].name.substring(4));
				for (var j=0;j<=rooms.length-1;j++){
					if (a==rooms[j]) {
						rooms1.push(a);
					}
				} 
		}
	}

	if (rooms1.length>0){
		
			b=$("body").css("height");
			$(".dialogue").css("height",b);
			$(".dialogue").show();
			$("#s2").show();

				b="";
				if (rooms1.length>1){
					
					b="Расписания для комнат";
	            	for (var i=0;i<=rooms1.length-1;i++){
						if (i<rooms1.length-2) {
							b=b+" "+rooms1[i]+",";
						}
						if (i==rooms1.length-2) {
							b=b+" "+rooms1[i];
						}
						if (i==rooms1.length-1) {
							b=b+" и "+rooms1[i]+" ";
						}
					}
					
					b=b+"уже существуют. Вы уверены, что хотите изменить их?";
				} else {
					
					b="Расписание для комнаты "+rooms1[0]+" уже существует. Вы уверены, что хотите изменить его?";
				}

		    b = "<div>"+b+"<br /> <span class=\"yes\">Да</span>&nbsp&nbsp&nbsp<span class=\"no\">Нет</span>  </div>"
			$("#s2").html(b);
		    	
			} else { 
				$("form").submit();
			}
	
	}
})

 
$(".yes").live("click", function(event){
	$("form").submit();
})	

$(".no").live("click", function(event){
	$(".dialogue").hide();
	$("#s2").hide();
})
 
  $(".click").live("click", function(event){
  a=this.id;
 clicky=0;
  b=a[1]
  b=b-1;
  arr[b]=arr[b]+1;
  if (max<arr[b]){max=arr[b];clicky=1;}
 
  c="day"+a[1]+"_"+arr[b];
   $("<div class=\"newtime\" id=\""+c+"\">Начало:<br>"+
   "<input type=\"text\" name=\"start_h_"+c+"\" />:"+
   "<input type=\"text\" name=\"start_m_"+c+"\" />"+
   "<br>Конец:<br>"+
   "<input type=\"text\" name=\"end_h_"+c+"\" />:"+
   "<input type=\"text\" name=\"end_m_"+c+"\" />"+
    "<br>Цена:<br><input type=\"text\" name=\"price_"+c+"\" class=\"large\" /></div>").appendTo("#day_"+a[1])
 if (max>=2 && clicky==1) {
	  tempHeight=$("body").css("height");
	  tempLength=tempHeight.length;
	  tempLength=tempLength-2;
	  temp1=tempHeight.substring(0,tempLength);
	  temp1=Number(temp1);
	  temp1=temp1+20;
	  temp1=temp1+"px";
      window.parent.$("iframe").css("height", temp1);
      window.parent.resizeBody();
  }


    })
  
  $(window).load(function() {
   $("<div/>", {"id": "add_sch", "class": "elemento"}).appendTo("body")
   $("<form>", {"id": "form", "action": "http://www.basebooking.ru/admin/addsch.php", "method": "POST"}).appendTo("#add_sch")
   for (var i=1; i<=7; i++) 
   { $("<div/>", {"class": "komn",text:weekr[i-1], "id": "day_"+i}).appendTo("#form");
  
   $("<div id=\"c"+i+"\" class=\"click\">Добавить<br>время</div>").appendTo("#day_"+i);
   }
   $("<div class=\"klast\" id=\"day_8\">Комнаты<br></div>").appendTo("#form");
    
	for (var i=1; i<=<?php echo $komn; ?>; i++) {
	$("<span>"+i+"</span><input type=\"checkbox\" name=\"komn"+i+"\" value=\"1\"><br>").appendTo("#day_8");
	}
   $("<div  class=\"save\"><span><p>Сохранить Расписание</p></span></div>").appendTo("body");
  });
</script></head><body id="<?php echo "c".$_GET['count']."\"><div class=\"suc\">";
if ($_GET['act']=="s") {echo"Расписание успешно добавлено!";}?></div><div class="dialogue"></div><div id="s2">
</div></body></html>