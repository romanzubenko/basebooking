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

function loadSch(i,dayOfWeek,datetemp){ // i for room 
	l=0;
  	while (sch[i][dayOfWeek][l]){
	   temp="";
	   idt=i+"d"+dayOfWeek+"l"+l;
	   time1M = sch[i][dayOfWeek][l][0] % 60;
	   time1H = (sch[i][dayOfWeek][l][0] - time1M) / 60 ;
	   time2M = sch[i][dayOfWeek][l][1] % 60;
	   time2H = (sch[i][dayOfWeek][l][1] - time1M) / 60 ;
	   
	   ttime=formatTime(sch[i][dayOfWeek][l][0])+"-"+formatTime(sch[i][dayOfWeek][l][1]);
	   
	   checktime = sch[i][dayOfWeek][l][0]+"-"+sch[i][dayOfWeek][l][1];
	   temp="<div class=\"timesch\">"+ttime+"</div>"+"<div class=\"price\">Цена<br>"+sch[i][dayOfWeek][l][2]+" руб</div>";
	   $("<div/>", {"class": "click",id : "rep_"+idt, html : temp}).appendTo("#komn"+i);
	   checkb = 0;
	   checkb = checkBooking(i,datetemp,checktime);
	  
	   if (checkb) {
         $("#rep_"+idt).removeClass('click');
         $("#rep_"+idt).addClass('booked');
	   }
	   tempHeight=$("#komn"+i).css("height");
	   tempHeight= Number(tempHeight.substring(0,tempHeight.length-2));

		if (tempHeight > tempHeightM){
			tempHeightM = tempHeight;
		}
	l++;
	}
	return tempHeightM;
}

$(window).load(function() {
for (var i=1;i<=k;i++){
  for (var j=0;j<=6;j++){
    sch[i][j]=sch[i][j].toString().split(';');
    l=0;
    while (sch[i][j][l]){
      sch[i][j][l]= sch[i][j][l].toString().split(',');  
      l++;
    }
  }  
}
});




function checkBooking(room,date,time){	
	i=0;
	result=0;
	if (bookings[room]!=undefined){
	    while (bookings[room][i]!=undefined){
	    	if (bookings[room][i][0]==date && bookings[room][i][1]==time) {
	    		result=1;
	    	}
	    	i++;
	    }
	}
	return result;
}
	 
function autoHeight(tempHeightM){
	$(".komn").css("min-height",tempHeightM+"px");
	$("#nextk").css("min-height",tempHeightM+"px");
	$("#prevk").css("min-height",tempHeightM+"px");	
	$(".marg1").css("min-height",tempHeightM+"px");	
}

function loadRooms(i,k,firstRoom,dayOfWeek,datetemp){
	s3=0;
	
	if (k <= 6){ 
		a = 6;
	} else {
		a = 5;
	}

	while (i <= k && i < firstRoom+a) {
	    s3++;
	    $("#k"+s3).html(i);
	    $("<div/>", {"class": "komn",id:"komn"+i}).appendTo("#schedule");

	 	if (window.roomNames !== undefined) {
			$("#k"+i).html(window.roomNames[i]);
		} else {
			$("#k"+i).html(i);
		}

		l=0;
 		tempHeightM = loadSch(i,dayOfWeek,datetemp);
        i++;
    }  
    return tempHeightM;
}

$(".exdial").live("click",function (event) {
	$("<div/>", {"id": "exbutton", "class": "exsch"}).appendTo("#schedule");
	$("#schedule").show();
	$(".exsch").show();
	$("#dial").remove();
	$(".submit").die("click");
	resizeBody();
})
	
$(".exsch").live("click",function (event) {
 	$(this).remove();
 	$("#schedule").remove();
 	$("#nextk").remove();
  	$("#nextk").die("click");
 	$("#prevk").die("click");
 	$(".click").die("click");
 	$(".submit").die("click");
 	$("#datepicker").show();
 	resizeBody();
})

$(window).load(function() {
	$("#datepicker").datepicker({
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'], firstDay: 1, prevText: '', nextText: '',
		onSelect: function () {
			createBookingEngine(this.dateText, this.inst)
		}
	})
})



function createBookingEngine(dateText, inst) {

   	var date = $("#datepicker").datepicker('getDate'),
		dayOfWeek = date.getDay();
		datetemp = date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear();
		datenum = date.getDate()+(date.getMonth()+1)*100+date.getFullYear()*10000;
	
	if (today <= datenum) {

	    dayNamesRus = ['понедельник', 'вторник', 'среду', 'четверг', 'пятницу', 'субботу', 'воскресенье']
	    if (dayOfWeek == 0) {
	     	dayOfWeek = 6;
	    } else {
	   		dayOfWeek=dayOfWeek-1;
	   	}
	   			
		tempHeightM = 0;
 		$("#datepicker").hide();
 		$("<div/>", {"id": "schedule"}).appendTo("#sch");
	 	$("<div/>", {"id": "exbutton", "class": "exsch"}).appendTo("#schedule");

	 	   
		$('#schedule').show();	
		$('#exbutton').show();

		//admin adjust fucking everything
		$("#schedule").css("width","775px");

		$("<div/>", {id:"sch_header", html: "Комнаты<br>"}).appendTo("#schedule");
		
		if (k < 6) {
			$("<div/>", {"class": "marg"}).appendTo("#sch_header");
		}
		
		if (k > 6) { 
			$("<div/>", {id:"prevk"}).appendTo("#schedule");
			$("<div/>", {"class": "marg"}).appendTo("#sch_header");
		}

		if (k <= 6) { 
			for (var i=1;i<=6;i++) {
				$("<div/>", {id:"k"+i, "class": "num"}).appendTo("#sch_header");
			}
		} else { 
			for (var i=1;i<=5;i++){
	 			$("<div/>", {id:"k"+i, "class": "num"}).appendTo("#sch_header")
	 		}
	 	}

	 	if (k < 6) {
			$("<div/>", {"class": "marg"}).appendTo("#sch_header");
			a=(775-k*131)/2;
			b=a+"px";
			$("<div/>", {"class": "marg1"}).appendTo("#schedule");
			$(".marg1").css("width",b);
			$(".marg").css("width",b);
			$(".num").css("width","130px");
		}
	 		
		firstRoom=1;
		i = firstRoom;
		tempHeightM = loadRooms(i,k,firstRoom,dayOfWeek,datetemp);
		if (k > 6) { 
			$("<div/>", {id:"nextk"}).appendTo("#schedule");
		}

		autoHeight(tempHeightM);
	 			
	 	if (k > 6) {
			
			$("#nextk").live("click", function(event) {
			
				if (firstRoom+4 < k) { 
				for (i=firstRoom;i<=firstRoom+4;i++) {
					$("#komn"+i).remove();
				}
	       
				$("#nextk").remove();
	       
				for (var i=1;i<=5;i++){
	 	        	$("#k"+i).html("");
				}
	       
	       firstRoom=firstRoom+5;
	       i=firstRoom;
	       tempHeightM=loadRooms(i,k,firstRoom,dayOfWeek);  
	       $("<div/>", {id:"nextk"}).appendTo("#schedule");
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
	   })
	   
	
	$("#prevk").live("click", function(event){
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
	    	autoHeight(tempHeightM);
	    	$(".marg").css("width","55px");
	    	$("#prevk").css("width","55px");
	    	$("#nextk").css("width","54px");
		}
	})
	   
} // if k == 6


	if (k==6){ 
		$("#komn6").addClass("lastroom");
	}

	resizeBody();
	 		        
	    $(".click").live("click", function(){

	 		var a=$(this).get(0).id,
	 		ind1=a.indexOf("_"),
	 		ind2=a.indexOf("d"),
	 		ind3=a.indexOf("l"),
	 		ind4=a.length,
	 		getrep = [];
	 		
	 		getrep[0]=Number(a.substring(ind1+1,ind2));
	 		getrep[1]=Number(a.substring(ind2+1,ind3));
	 		getrep[2]=Number(a.substring(ind3+1,ind4));
	 		
	 		window.booking = [];
	 		window.booking['komn'] = getrep[0];
	 		window.booking['date'] = datetemp;
	 		window.booking['start'] = sch[getrep[0]][getrep[1]][getrep[2]][0];
	 		window.booking['end'] = sch[getrep[0]][getrep[1]][getrep[2]][1];
	 		window.booking['price'] = sch[getrep[0]][getrep[1]][getrep[2]][2];


	 		dayname=dayNamesRus[getrep[1]];
	 		price=Number(sch[getrep[0]][getrep[1]][getrep[2]][2]);
	 		if (price%10==1 || price%100!=11){rubl="рубль"}
	 		if (price%10==0){rubl="рублей"}
	 		if (price%10<10 && price%10>1 && price%100-price%10==0){rubl="рубля"}
	 		if (price%10<10 && price%10>1 && price%100-price%10!=0){rubl="рублей"}
	 		time=formatTime(sch[getrep[0]][getrep[1]][getrep[2]][0])+" - "+formatTime(sch[getrep[0]][getrep[1]][getrep[2]][1]);
	 		$("#schedule").hide();
	 		$("#sch").show();
	 		$("<div/>", {id:"dial"}).appendTo("#sch");
	 		$("#dial").css("height","283px");
	 		$("#dial").css("width","775px");
	 		$("<div/>", {id:"dial1"}).appendTo("#dial");
	 		$("<div/>", {id:"dial2"}).appendTo("#dial");
			$("#dial1").css("width","560px");
			
			$("#exbutton").remove();
	 		$("<div/>", {"id": "exbutton", "class": "exdial"}).appendTo("#dial");
	 		$("#exbutton").show();
	 		
	 		

	 		$("#dial1").html("<span>Подтверждение бронирования на "+dayname+", "+datetemp+"</span><br />"+
	 		"Для подтверждения бронирования добавьте контактную информацию"+
	 		"<form id=\"book\"><br />"+
	 		"<div>Контактный телефон:<input type=\"text\" name=\"phone\" value=\"+7\" /></div>"+
	 		"<div>Название группы:<input type=\"text\" name=\"band\"/></div>"+
	 		"<div>Контактное лицо:<input type=\"text\" name=\"name\"/></div>"+
	 		"<div>Дополнительная информация:<input type=\"text\" name=\"add\"/></div>"+
	 		"<div class=\"submit button\" style=\"width:560px;height:50px\"><span style=\"margin-top:15px;width:100%\">Забронировать</span></div>"+
	 		"</form>"
	 		);
	 	
	 		$("#dial2").html("<span >"+time+"<br></span><br>"+price+" "+rubl);
	 		resizeBody();
	 	
	 				//c1=1;
	 				
	 				$(".submit").live("click", function() {
	 					var name  = $('input[name*="name"]').val();
	 					var band  = $('input[name*="band"]').val();
	 					var add   = $('input[name*="add"]').val();
	 					var phone = $('input[name*="phone"]').val();
	 					var date  = window.booking['date'];
	 					var room  = window.booking['komn'];
	 					var start = window.booking['start'];
	 					var end   =	window.booking['end'];
	 					var price =	window.booking['price'];
	 					var parent = $(this).parent().parent().parent();
	 					//var c1 = 0;
	 					//c1 = checkFull();

	 					$(this).children(0).html("Пожалуйста подождите...");
						MakeBookingRequest(name,band,add,phone,date,room,start,end,parent,price);		
	 				})

	 		 
	 		    
	 		})
	}
}
	




