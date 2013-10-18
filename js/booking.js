function formatTime(x) {
	var time = "",
	h = Math.floor(x/60),
	m = x - h*60;
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

function loadSch(i,dayOfWeek){
	var l = 0,t1,t2,ttime,checktime,checkb,
	tempHeight = 0,l,
	h = [],
	additionOccur = 0,
 	temp = "";

 	while (sch[i][dayOfWeek][l]){ 
		additionOccur = 1;
		temp = "";
		idt=i+"d"+dayOfWeek+"l"+l;
		t1 = formatTime(sch[i][dayOfWeek][l][0]);  
		t2 = formatTime(sch[i][dayOfWeek][l][1]);
		ttime = t1+" - "+t2;
		checktime = sch[i][dayOfWeek][l][0]+"-"+sch[i][dayOfWeek][l][1];
		temp = "<div class=\"timesch\">"+ttime+"</div>"+"<div class=\"price\">Цена<br>"+sch[i][dayOfWeek][l][2]+" руб</div>";
		$("<div/>", {"class": "click",id : "rep_"+idt, html : temp}).appendTo("#komn"+i);
		checkb = 0;
		checkb = checkBooking(i,datetemp,checktime);
		if (checkb) {
        	$("#rep_"+idt).removeClass('click');
        	$("#rep_"+idt).addClass('booked');
		}
		
		if (parseInt($("#komn"+i).css("height")) > tempHeight) {
			tempHeight = parseInt($("#komn"+i).css("height"));
		}
 
   		l++;
  	}

	h = [additionOccur,tempHeight];
	return h;
}

$(window).load(function() {
	var l = 0;

	for (var i = 1; i <= k; i++){
	  for (var j = 0; j <= 6; j++){
	    sch[i][j]=sch[i][j].toString().split(';');
	    l = 0;
	    while (sch[i][j][l]){
	    	sch[i][j][l]= sch[i][j][l].toString().split(',');  
	    	l++;
	    }
	  }  
	}
});




function checkBooking(room,date,time){	
	var i = 0,
	result = 0;

	if (bookings[room] != undefined){
		while (bookings[room][i]!=undefined){
			if (bookings[room][i][0]==date && bookings[room][i][1] == time) {
				result = 1;
			}
			i++;
		}
	}
	return result;
}
	 
function autoHeight(tempHeightM){
	var margin = parseInt(tempHeightM/2 - 10) + "px";
	$(".komn").css("min-height",tempHeightM+"px");
	$("#nextk").css("min-height",tempHeightM+"px");
	$("#prevk").css("min-height",tempHeightM+"px");	
	$(".marg1").css("min-height",tempHeightM+"px");
	$(".arrtext").css("margin-top",margin);
}

function loadRooms(i,k,firstRoom,dayOfWeek){
	var occur = 0,h,l,
	s3 = 0,
	tempHeightM = "";

	if (k <= 6) {
		a = 6;
	} else {
		a = 5;
	}

	 	 
	while (i <= k && i < firstRoom + a) {
		s3++;
		$("#k"+s3).html(i);
		$("<div/>", {"class": "komn",id:"komn"+i}).appendTo("#schedule");
		$("#k"+i).html(i);
		l = 0;
		h = loadSch(i,dayOfWeek);
		tempHeightM = h[1];
		if (h[0] == 1) {
			occur = 1;
	 	} 
	 	i++;
	        
	}
	      
	if (!occur) {	  
		$(".marg1").remove();
		$(".komn").hide();
		$("#sch_header").hide();
		$("<div/>", {"class": "nosch",text: "Извините, на данный день расписание отсутствует", style : "display:none"}).appendTo("#schedule");
		$(".nosch").show();
		$("#prevk").hide();
		$("#nextk").hide();
	}
	
	return tempHeightM;
}

$(function(){
	$("#gall").click(function ( event ) {
	 	$("#cond").hide;
	 	$("#datepicker").hide();
	 	$("#exbutton").hide();
	 	$("#schedule").hide();
	 	$("#schedule").remove();
		$("#vk_auth").remove();
		$("#dial").remove();
		$("#fotorama").show();
		$("#nobooking").hide();
		$("#err1").show();
		$("#cond").hide();	 
	 })
	 	 
	$("#cal").click(function ( event ) {
	    if (activate) {
	    	$("#cond").hide;
	        $("#fotorama").hide();
	 	    $("#schedule").remove();
	 	    $("#dial").remove();
	 	    $("#vk_auth").remove();
	 	    $("#datepicker").show();
	 	    $(".errwrap").hide();
	 	    $("#cond").hide();
	 	} else {
	 		$("#fotorama").hide();
	 		$("#cond").hide();
	 		$(".errwrap").hide();
	 		$("#nobooking").show();     
	 	}
	})
	     
	$("#conditions").click(function ( event ) {
	    if (activate) {
	        $("#fotorama").hide();
	 	    $("#schedule").remove();
	 	    $("#dial").remove();
	 	    $("#vk_auth").remove();
	 	    $("#datepicker").hide();
	 	    $(".errwrap").hide();
	 	    $("#cond").show();
	 	} else {
	 		$("#fotorama").hide();
	 		$(".errwrap").hide();
	 		$("#nobooking").show();     
	 	}
	})
})
	 	
	 	
	 
$(function(){
	$("#datepicker").datepicker({
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'], firstDay: 1, prevText: '', nextText: '',
		onSelect: function(dateText, inst) {
			var date = $("#datepicker").datepicker('getDate');
			var dayOfWeek = date.getDay();
			datetemp = date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear();
			datenum = date.getDate()+(date.getMonth()+1)*100+date.getFullYear()*10000;
			
			if (today <= datenum) {
				dayNamesRus =['понедельник', 'вторник', 'среду', 'четверг', 'пятницу', 'субботу', 'воскресенье']
	     	    if (dayOfWeek==0){ 
	     	    	dayOfWeek = 6;
	     	    } else {
	     	    	dayOfWeek=dayOfWeek-1;
	     	    }

				tempHeightM = 0;
				$("#datepicker").hide();
				$("<div/>", {"id": "schedule"}).appendTo("#sch");
	 			$("<div/>", {"id": "exbutton"}).appendTo("#schedule");

				$('#schedule').show();
				$('#exbutton').show();
				$("#vk_auth").show();
				$("#cond").hide();
				$("<div/>", {id:"sch_header", html: "Комнаты<br>"}).appendTo("#schedule");
				
				if (k < 6) {
					$("<div/>", {"class": "marg"}).appendTo("#sch_header");
				}
	 			
	 			if (k > 6) { 
	 				$("<div/>", {id:"prevk"}).appendTo("#schedule");
					$("<div />",{class: "arrtext",html: "Пред."}).appendTo("#prevk");
					$("<div/>", {"class": "marg"}).appendTo("#sch_header");
				}

				if (k <= 6){ 
					for (var i = 1; i <= 6; i++) {
						$("<div/>", {id:"k"+i, "class": "num"}).appendTo("#sch_header");
	 				}
	 			} else {
	 				for (var i = 1; i <= 5; i++) {
						$("<div/>", {id:"k"+i, "class": "num"}).appendTo("#sch_header");
	 				}
	 			}

	 			if (k < 6) {
					$("<div/>", {"class": "marg"}).appendTo("#sch_header");
					a = (676-k*113)/2;
					b = a+"px";
					$("<div/>", {"class": "marg1"}).appendTo("#schedule");
					$(".marg1").css("width",b);
					$(".marg").css("width",b);
	 	  		}
	 			
				firstRoom=1;
				i = firstRoom;
				tempHeightM = loadRooms(i,k,firstRoom,dayOfWeek);
				
				if (k > 6) { 
					$("<div/>", {id:"nextk"}).appendTo("#schedule");
					$("<div />",{class: "arrtext",html: "След."}).appendTo("#nextk");
				}
	 			autoHeight(tempHeightM);
	 			
	 if (k>6) {
	   $("#nextk").live("click", function(event){
	   	$("#sch_header").show();
	     $(".nosch").remove();
	     if (firstRoom+4<k){
	         
	       for (i=firstRoom;i<=firstRoom+4;i++){
	         $("#komn"+i).remove();
	         }
	       
	       $("#nextk").remove();
	       
	       for (var i=1;i<=5;i++){
	 	        $("#k"+i).html("");
	 		  }
	       firstRoom=firstRoom+5;
	       i=firstRoom;
	       tempHeightM = loadRooms(i,k,firstRoom,dayOfWeek);  
	       $("<div/>", {id:"nextk"}).appendTo("#schedule");
	       $("<div />",{class: "arrtext",html: "След."}).appendTo("#nextk");
	       autoHeight(tempHeightM);
	       if (firstRoom+4>=k){
	           a=(676-(k-firstRoom+1)*113)/2;
	           b=a+"px";
	 		$("#prevk").css("width",b);
	 		$(".marg").css("width",b);
	 		c=a-1;
	 		c=c+"px";
	 		$("#nextk").css("width",c);
	       }
	     }


	     if ($(".nosch").length == 1) {
	     	$("#prevk").css("width","50px");
	     	$("#nextk").css("width","50px");
	     	$(".nosch").css("width","574px");
	     } 
	   })
	   
	   $("#prevk").live("click", function(event){
	     $(".nosch").remove();
	     $("#sch_header").show();
	     if (firstRoom!=1){
	         for (i=firstRoom;i<=firstRoom+4;i++){
	         $("#komn"+i).remove();
	         }
	       $("#nextk").remove();
	       for (var i=1;i<=5;i++){
	 	        $("#k"+i).html("");
	 		  }
	       i=firstRoom-5;
	       firstRoom=firstRoom-5;
	        tempHeightM=loadRooms(i,k,firstRoom,dayOfWeek);
	       $("<div/>", {id:"nextk"}).appendTo("#schedule");
	       $("<div />",{class: "arrtext",html: "След."}).appendTo("#nextk");
	       autoHeight(tempHeightM);
	       $(".marg").css("width","55px");
	       $("#prevk").css("width","55px");
	       $("#nextk").css("width","54px");

	     }
	     if ($(".nosch").length == 1) {
	     	$("#prevk").css("width","50px");
	     	$("#nextk").css("width","50px");
	     	$(".nosch").css("width","574px");
	     } 

	   })
	   
	 }

	 if (k==6){$("#komn6").addClass("lastroom");}
	 			  $("#exbutton").click(function (event) {
	 		      $(this).remove();
	 			  $("#schedule").remove();
	 			  $("#nextk").remove();
	 			  $("#nextk").die("click");
	 			  $("#prevk").die("click");
	 			  $("#schedule").remove();
	 			  $("#datepicker").show();
	 		       })
	      $(".click").live("click", function(){
	 		a=$(this).get(0).id;
	 		ind1=a.indexOf("_");
	 		ind2=a.indexOf("d");
	 		ind3=a.indexOf("l");
	 		ind4=a.length;
	 		getrep =new Array();
	 		
	 		getrep[0]=Number(a.substring(ind1+1,ind2));
	 		getrep[1]=Number(a.substring(ind2+1,ind3));
	 		getrep[2]=Number(a.substring(ind3+1,ind4));
	 		dayname=dayNamesRus[getrep[1]];
	 		price=Number(sch[getrep[0]][getrep[1]][getrep[2]][2]);
	 		if (price%10==1 || price%100!=11){rubl="рубль"}
	 		if (price%10==0){rubl="рублей"}
	 		if (price%10<10 && price%10>1 && price%100-price%10==0){rubl="рубля"}
	 		if (price%10<10 && price%10>1 && price%100-price%10!=0){rubl="рублей"}
	 		time=formatTime(sch[getrep[0]][getrep[1]][getrep[2]][0])+" - "+formatTime(sch[getrep[0]][getrep[1]][getrep[2]][1]);
	 		$("#schedule").hide();
	 		$("#sch").show();
	 		$("#dial").remove();
	 		$("#dial1").remove();
	 		$("#dial2").remove();
	 		$("<div/>", {id:"dial"}).appendTo("#sch");
	 		$("<div/>", {id:"dial1"}).appendTo("#dial");
	 		$("<div/>", {id:"dial2"}).appendTo("#dial");
	 		$("#dial1").html("<span>Подтверждение бронирования на "+dayname+", "+datetemp+"</span><br>Для подтверждения бронирования репетиции нажмите на кнопку авторизоваться, тем самым предоставляя контактные данные для администраторов баз"+
	 		"<form id=\"book\" action=\"\" method=\"POST\"><br>Контактный телефон<input type=\"text\" name=\"phone\" value=\"+7\" />"+
	 		"<br><br>Название группы&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type=\"text\" name=\"band\"/>"+
	 		"<input type=\"hidden\" name=\"name\"/>"+
	 		"<input type=\"hidden\" name=\"lastname\"/>"+
	 		"<input type=\"hidden\" name=\"vkid\"/>"+
	 		"<input type=\"hidden\" name=\"hash\"/>"+
	 		"<input type=\"hidden\" name=\"timestart\"/>"+
	 		"<input type=\"hidden\" name=\"timeend\"/>"+
	 		"<input type=\"hidden\" name=\"date\"/>"+
	 		"<input type=\"hidden\" name=\"room\"/>"+
	 		"<input type=\"hidden\" name=\"price\"/>"+
	 		"</form>");
	 	
	 		$("#dial2").html("<span>"+time+"<br /></span><br/>"+price+" "+rubl);
	 		$("#vk_auth").remove();
	 		$("<div/>", { "id": "vk_auth"}).appendTo("#sch");
	 		$("#vk_auth").show();
	 		$("#vk_auth").css("border", "no");
	 		
	 		VK.Widgets.Auth("vk_auth", {width: "676px",height: "40px", onAuth: function(data) {
	 				c1=1;
	 				$('input[name*="name"]').val(data['first_name']);
	 				$('input[name*="vkid"]').val(data['uid']);
	 				$('input[name*="lastname"]').val(data['last_name']);
	 				$('input[name*="hash"]').val(data['hash']);
	 				$('input[name*="date"]').val(datetemp);
	 				$('input[name*="timestart"]').val(sch[getrep[0]][getrep[1]][getrep[2]][0]);
	 				$('input[name*="timeend"]').val(sch[getrep[0]][getrep[1]][getrep[2]][1]);
	 				$('input[name*="room"]').val(getrep[0]);
	 				//price
	 				$('input[name*="price"]').val(sch[getrep[0]][getrep[1]][getrep[2]][2]);
	 				if ($('input[name*="name"]').val()==""){c1=0;}
	 				if ($('input[name*="vkid"]').val()==""){c1=0;}
	 				if ($('input[name*="lastname"]').val()==""){c1=0;}
	 				if ($('input[name*="hash"]').val()==""){c1=0;}
	 				if ($('input[name*="phone"]').val()==""){c1=0;$('input[name*="phone"]').css("border","1px solid #f44");$('input[name*="phone"]').css("background-color","#fff3f3");}
	 				

	 				if (c1 == 1){
	 					$("#book").submit();
	 				}
	 		  }
	 		})	    
	 	  })
	     }}
	  })
	 })



