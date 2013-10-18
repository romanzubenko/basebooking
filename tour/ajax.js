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

function getXMLHttp(){
	var x = false;
    try {
	        x = new XMLHttpRequest();
	    }
	    catch(e) {
	        try {
	            x = new ActiveXObject("Microsoft.XMLHTTP");
	        }
	        catch(ex) {
	            try {
	                req = new ActiveXObject("Msxml2.XMLHTTP");
	            }
	            catch(e1) {
	                x = false;
	            }
	        }
	    }
	    return x;
	}


function overlay(x,message) {
	/*
	x == 1 activate overlay
	x == 2 disable overlay
	*/
	
	if (x === 1 || x === 3) {
		var overlay = $("<div />", {class:"overlay"}),
		height = getDocHeight(),
		top = $(window).scrollTop(),
	    left = $(window).scrollLeft(),
	    message = $("<div />", {class:"overlayMessage",text : message});
	    message.css("margin-top",parseInt(top)+ "px");
	    
	    if (x === 3) {
	    	span = $("<span />", {class: "answer"}).appendTo(message);
			$("<a />",{id: "yes", html: "Да"}).appendTo(span);
			$("<a />",{id: "no", html: "Нет"}).appendTo(span);
	    }
	    


		$('body').css('overflow', 'hidden');
		$(window).scroll(function(){
			$(this).scrollTop(top).scrollLeft(left);
		});

		overlay.css("height",height);
		overlay.appendTo("body");
		message.appendTo("#centered");
	} else if (x === 2) {
		$("#yes").die("click");
		$("#no").die("click");

		$(".overlay, .overlayMessage").remove();
		$('body').css('overflow', 'auto');
      	$(window).unbind('scroll');
	}
	
}

function diff(a,b) {
    return a.filter(function(i) {
    	return !(b.indexOf(i) > -1);
    })
}

function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
}

function getName(parent) {
	var ind = $(parent).find(".ind").html();
	var arr = ind.split(",");
	var name = arr[6];
	return name;
}

function is_numeric(mixed_var){ 
	return !isNaN(mixed_var) 
}

function getPrice(parent) {
	var ind = $(parent).find(".ind").html();
	var arr = ind.split(",");
	var price = arr[5];


	return price;
}

function MakeURequest(ind, parent,type,confirmation) {
	var xmlHttp = getXMLHttp(),
	a = $(confirmation).children()[0],
	data = ind.split(",");

	xmlHttp.onreadystatechange = function() {
		if(xmlHttp.readyState == 4) {
			HandleUResponse(xmlHttp.responseText, parent, type,confirmation);
		}
	}

	
	$(a).html("Пожалуйста подождите...");	
	
	xmlHttp.open("POST", "http://www.basebooking.ru/admin/ajax.php?r=" + Math.random(), true);
	xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("date="+data[0]+"&start="+data[1]+"&end="+data[2]+"&room="+data[3]+"&vkid="+data[4]+"&price="+data[5]+"&type="+type);

}

function MakeScheduleRequest(send,schedule,factory) {
	var xmlHttp = getXMLHttp();
	xmlHttp.onreadystatechange = function() {
		if(xmlHttp.readyState == 4) {
			//HandleUResponse(xmlHttp.responseText, parent, type,confirmation);
			console.log("response = "+xmlHttp.responseText);
			
			var response = xmlHttp.responseText,
			responseArr = [];

			responseArr = response.split('!');
			responseArr[1] = JSON.parse(responseArr[1]);
			if (responseArr[0] == 1) {

				overlay(2,"");
				factory.transformToViewSchedule(schedule,responseArr[1]);
				overlay(1,"Изменения успешно сохранены!");
				setTimeout(function () {
					overlay(2,"");
				},700);
			} else {
				overlay(2,"");
				overlay(1,"Что-то пошло не так. Попробуйте перезагрузить страницу.");
				setTimeout(function () {
					overlay(2,"");
				},700);
			}
			

		}
	}

	xmlHttp.open("POST", "http://www.basebooking.ru/admin/schedules.php?r=" + Math.random(), true);
	xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("data="+send);

}


function booking(booking) {

	var vkid,name,time,price,
		confirmation = $(booking).children()[1],
  		book = $(booking).children()[2],
		contacts = $(booking).children()[3],
		that = {};

	that.booking = booking;

	that.showConfirmation = function() {
  
		$(this.confirmation).animate({"margin-top": "-82px"},100);
		$(this.book).animate({"margin-top": "-41px"},100);
		$(this.contacts).animate({"margin-top": "-11px"},100);

	}

	that.showBooking = function() {
  
		$(this.confirmation).animate({"margin-top": "-82px"},100);
		$(this.book).animate({"margin-top": "-41px"},100);
		$(this.contacts).animate({"margin-top": "-11px"},100);

	}

	that.showContacts = function() {
  
		$(this.confirmation).animate({"margin-top": "-82px"},100);
		$(this.book).animate({"margin-top": "-41px"},100);
		$(this.contacts).animate({"margin-top": "-11px"},100);

	}

	that.getName = function () {
		if (this.name == "") {
			this.name = $(this.booking).find(".binfoc4").innerHTML;
		}
	    return this.name;	
	}
}

function changeConfirmation(confirmation,inhtml,type) {
	var message  = $(confirmation).children()[0],
	prevType = $(confirmation).children()[1];

	$(message).html(inhtml);
	$(prevType).html(type);
}




/*

	TYPES!

  0 - cancel
  1 - add BL
  2 - add debt
  3 - notcome
  4 - delete photo
  5 - accept
  6 - notaccept


*/

function HandleUResponse(response,parent,type,confirmation) {
	var inhtml,a,
	name = getName(parent),
	price = getPrice(parent);
	window.testparent = parent;
	console.log(response);
	
	confirmation = $(parent).children()[1],
  	main = $(parent).children()[2];

	//cancel
  	if (type == 0) {
  		var responseArr = response.split(",");

  		if (responseArr[0] == -1 && responseArr[1] == -1) {
			inhtml = "Бронирование было успешно отменено.";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);	
  		} else if (responseArr[0] == -1 && responseArr[1] > 0){
  			inhtml = "Бронирование было успешно отменено после дедлайна. Добавить "+price+" в долг? <span class=\"yes9\">Да</span> <span class=\"no\">Нет</span> ";
  			type = 2;
  			changeConfirmation(confirmation,inhtml,type);
  		} else if (responseArr[0] == 0 && responseArr[1] == 0){
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  		}
  	
  	}

  	// BL
  	else if (type == 1) {
  		if (response == -1) {
  			inhtml = name+" добавлен(а) в черный список.";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  			
  		} else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
		}	
	}
	//add debt
	else if (type == 2) {
  		if (response == 1) {
  			inhtml = "Долг в "+price+" добавлен. Добавить "+name+" в черный список? <span class=\"yes9\">Да</span> <span class=\"no\">Нет</span>";
  			type = 1;
  			changeConfirmation(confirmation,inhtml,type);
  			
  		} else if (response == 2) {
  			inhtml = "Долг в "+price+" добавлен.";
  			type = 1;
  			changeConfirmation(confirmation,inhtml,type);
  		} else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
		}	
	}

	//notcome
  	else if (type == 3) {
  		if (response == -1) {
  			a = $(parent).children()[2]; //
  			button = $(a).children(".buttons").children(".done");
  			$(button).removeClass('done').html("Группа<br/>не пришла").addClass('notcome')
  			inhtml = "Группа не пришла. Добавить "+price+" в долг? <span class=\"yes9\">Да</span> <span class=\"no\">Нет</span> ";
  			type = 2;
  			changeConfirmation(confirmation,inhtml,type);

  		} else if (response == -2) {
  			a = $(parent).children()[2]; //
  			button = $(a).children(".buttons").children(".done");
  			$(button).removeClass('done').html("Группа<br/>не пришла").addClass('notcome')
  			inhtml = "Группа не пришла.";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);

  		} else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  		}
  	
  	}
	//accept
	else if (type == 5) {
  		if (response == -1) {
  			a = $(parent).children()[2]; //
  			$(a).children(".buttons").children(".accept").removeClass('accept').html("Заявка<br/> одобрена").addClass('approved');
  			

  			$(confirmation).animate({"margin-top": "-50px"},100);
  			$(main).animate({"margin-top": "0px"},100);

  		} else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  		};
  	
  	}
  	
  	//notaccept
  	else if (type == 6) {
  		
  		if (response == -1) {
  			inhtml = "Заявка отклонена. Добавить "+price+" в долг? <span class=\"yes9\">Да</span> <span class=\"no\">Нет</span>";
  			type = 2;
  			changeConfirmation(confirmation,inhtml,type);

  		} else if (response == -2) {
  			inhtml = "Заявка отклонена.";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  		} else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  		}
  	
  	}



}



function MakeRequestDP(num, parent) { // delete photo
	  var xmlHttp = getXMLHttp();
	  a = parent.children()[1];
	  xmlHttp.onreadystatechange = function() {
	    if(xmlHttp.readyState == 4) {
	      HandleResponseDP(xmlHttp.responseText, a);
	    }
	  }
	  a.innerHTML= "Пожалуйста подождите...";
	  var query = num;
	  xmlHttp.open("GET", "http://www.basebooking.ru/admin/dp.php?query=" + query, true);
	  xmlHttp.send(null);
}

function MakeBookingRequest(name,band,add,phone,date,room,start,end,parent,price) {
	var xmlHttp = getXMLHttp();
	xmlHttp.onreadystatechange = function() {
    	if(xmlHttp.readyState == 4) {
    		HandleBookingResponse(xmlHttp.responseText,parent,name,band,add,phone);
  		}
	}
	

	$("#dial").innerHTML= "<div class=\"wait\">Пожалуйста подождите...<div>";
	xmlHttp.open("POST", "http://www.basebooking.ru/admin/book.php?r=" + Math.random(), true);
	xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("name="+name+"&band="+band+"&add="+add+"&phone="+phone+"&date="+date+"&room="+room+"&start="+start+"&end="+end+"&price="+price);
}

function HandleResponseDP(response, parent) {
	 if (response == -1) {
		 parent.innerHTML = "Фотография была успешно удалена";
	 } else {
	    parent.innerHTML = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/admin\">перезагрузить страницу</a>";
	 }
}


function HandleBookingResponse(response,parent,name,band,add,phone) {
	var responseArr = response.split("$&"),
	respArr = responseArr[3].split(",");
	

	if (responseArr[0] == 11) {
		$(parent).find("span")[0].innerHTML = responseArr[1];
		
		$(parent).find("input").css("border","0px solid #111").css("background-color","#fafafa");

		

		setTimeout(function () {
			$(".exdial").click();
			$(".exsch").click();
		},1);

		var message = "Репетиция для "+band+" была успешно забронирована.";
			overlay(1,message);
		setTimeout(function () {
			overlay(2,"");
		},1700);
		

		if (bookings[responseArr[2]] === undefined) {
			bookings[responseArr[2]] = [];
		}
		
		bookings[responseArr[2]].push(respArr);
		
	} 
}



// LIST MANIPULATION 
function ListManipulation() {
	var debtRequest = function (parent,debt,vkid) { // parent = l1
		
		debt = Number(debt);
		var xmlHttp = getXMLHttp();
		$(parent).children()[0].children[0].innerHTML = "Пожалуйста подождите...";

		xmlHttp.onreadystatechange = function() {
			if(xmlHttp.readyState == 4) {
				var message = debtResponse(xmlHttp.responseText);
				
				if (message === "1") {
					$(parent).children()[0].children[0].innerHTML = "Долг: "+debt+" руб"
				} else {
					$(parent).children()[0].children[0].innerHTML = message;
				}
			}
		}

	
		xmlHttp.open("POST", "http://www.basebooking.ru/admin/list.php?r=" + Math.random(), true);
		xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlHttp.send("type="+1+"&vkid="+vkid+"&debt="+debt);
	}

	var bLRequest = function (parent,vkid,type) { // parent = l1 
		// type 2 - add to BL
		// type 3 - delete from BL
	
		var xmlHttp = getXMLHttp();

		xmlHttp.onreadystatechange = function() {
			if(xmlHttp.readyState == 4) {
				var message = bLResponse(xmlHttp.responseText);
			
				if (message == "1" && type == "2") {
					$(parent).children()[0].children[0].innerHTML = "Музыкант добавлен в черный список.";
				} else if (message == "1" && type == "3") {
					$(parent).children()[0].children[0].innerHTML = "Музыкант удален из черного списка.";
				} else {
					$(parent).children()[0].children[0].innerHTML = message;
				}
			}
		}

	
		xmlHttp.open("POST", "http://www.basebooking.ru/admin/list.php?r=" + Math.random(), true);
		xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlHttp.send("type="+type+"&vkid="+vkid+"&debt=0");
	}

	var debtResponse = function (response) {
		

		if (response == 0) {
			return "Что-то пошло не так. <a href=\"http://www.basebooking.ru/admin\">Перезагрузить страницу</a>";
		} else if (response == 1)  {
			return "1";
		}

	}

	var bLResponse = function (response) {
	
		var responseArr = response.split(",");
		if (responseArr[0] == 0) {
			return "Что-то пошло не так. <a href=\"http://www.basebooking.ru/admin\">Перезагрузить страницу</a>";
		} else if (responseArr[0] == 1)  {
			return "1";
		}

	}



	$(".addToBL").live("click",function() {
		var parent = $(this).parent().parent(),	 // l2
		block1 = $(this).parent().parent().children()[0],
		block2 = $(this).parent().parent().children()[1],
		vkid = $(parent).parent().children()[3].innerHTML;

		$(block1).animate({"margin-top": "-80px"},200);
		$(block2).animate({"margin-top": "0px"},200);
		
	})	

	$(".debt").live("click",function() {
		var parent = $(this).parent().parent(),	 // l1
		block1 = $(this).parent().parent().children()[0],
		block2 = $(this).parent().parent().children()[1],
		vkid = $(parent).parent().children()[3].innerHTML;
		
		$(block1).animate({"margin-top": "-80px"},200);
		$(block2).animate({"margin-top": "0px"},200);
		
	})

	$(".confirmDebt").live("click",function() {
		var parent = $(this).parent().parent(),	 // l1
		block1 = $(this).parent().parent().children()[0],
		block2 = $(this).parent().parent().children()[1],
		vkid = $(parent).parent().children()[3].innerHTML;

		debt  = $(this).parent().children()[0].children[0],
		debtVal = $(debt).val();

		debtRequest(parent,debtVal,vkid);
		$(block1).animate({"margin-top": "0px"},200);
		$(block2).animate({"margin-top": "80px"},200);
		
		
	})
	
	$(".yesBL").live("click",function() {
		var type,parent = $(this).parent().parent().parent(),	 // l2
		block1 = $(this).parent().parent().parent().children()[0],
		block2 = $(this).parent().parent().parent().children()[1],
		vkid = $(parent).parent().children()[3].innerHTML;
		// decide weather BL in or out ->
		if ($("#button_1").hasClass("active")) {
			type = 2;
		} else {
			type = 3;
		}
		
		bLRequest(parent,vkid,type);
		$(block1).animate({"margin-top": "0px"},200);
		$(block2).animate({"margin-top": "80px"},200);
		
		
	})

	$(".cancelBL").live("click",function() {
		var parent = $(this).parent().parent().parent(),	 // l2
		block1 = $(this).parent().parent().parent().children()[0],
		block2 = $(this).parent().parent().parent().children()[1],
		vkid = $(parent).parent().children()[3].innerHTML;
		
		$(block1).animate({"margin-top": "0px"},200);
		$(block2).animate({"margin-top": "80px"},200);
		
		
	})
	


}

function scheduleFactory(primeschedules,target,rooms) {
	this.scheduleData = new Array();
	this.schedules = new Array();
	this.dayNamesRus = ['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'];
	this.mode = 1; // view mode, 2 -> input mode
	this.rooms = rooms;
	this.activeSchedule = -1;
	this.primeschedules = primeschedules;


	this.createAddButton = function(target) {
		var factory = this,
		a = $("<div />",{
			class : "addButton",
			text : "Добавить расписание" 
		}).appendTo(target);
		
		
	}

	this.refreshAllRooms = function (id) {
		var rooms = this.schedules[id].roomsData,
		index;
		for (var i = 0, limit = this.schedules.length; i < limit; i++) {
			if (i != id) {
				
				for (var j = 0; j < rooms.length; j++) {
					index = this.schedules[i].roomsData.indexOf(rooms[j]);
					if (index !== -1) {
						this.schedules[i].roomsData.splice(index,1);
					}
					this.createRooms(this.schedules[i],this.schedules[i].roomsData,this.schedules[i].mode);
					this.createButton(this.schedules[i],this.schedules[i].mode);
				}

			}
		}
		for (var i = 0, limit = this.schedules.length; i < limit; i++) {
			if (this.schedules[i].roomsData.length === 0) {
				this.schedules[i].remove();
				this.schedules[i] = null;
			}
		}
		resizeBody();
	}

	/* 	MESSAGE CODES:
		1 - times overlap
		2 - non valid time format
	*/
	
	this.createTime = function(timeData) {
		var time = {};
		if (timeData.length == 3) {
			time = $("<div />", {class: "time"});
			$("<div />", {class: "vr", text : formatTime(timeData[0])+" - "+formatTime(timeData[1])}).appendTo(time);
			$("<div />", {class: "price", text : timeData[2]+" руб"}).appendTo(time); 
		} else {
			time = null;
		}
		
		
		return time;
	}

	this.createInputTime = function(timeData) {
		var separate = function (x) { //returns array 0 -> hours , 1 -> minutes
			var str = formatTime(x),
			arr = str.split(":");
			return arr;
		}

		if (timeData === null || timeData === undefined) {
			timeData = [0,0,''];
		}
		
		if (timeData.length == 3) {
			var time = $("<div />", {class: "inputtime"}),
			deleteB = $("<div />", {class: "timeDeleteButton"});
			deleteB.appendTo(time); 
			
			$("<div />", {class: "inputheader", text : "Начало:"}).appendTo(time);

			$("<input />", {class: "h", value : separate(timeData[0])[0]}).appendTo(time);
			
			$(time).html(function() {
				return this.innerHTML+":";
			})
			$("<input />", {class: "m", value : separate(timeData[0])[1]}).appendTo(time);
			
			$("<div />", {class: "inputheader", text : "Конец:"}).appendTo(time);
			
			
			$("<input />", {class: "h", value : separate(timeData[1])[0]}).appendTo(time);
			
			$(time).html(function() {
				return $(this).html()+":";
			})
			
			$("<input />", {class: "m", value : separate(timeData[1])[1]}).appendTo(time);

			
			$("<div />", {class: "inputheader", text : "Цена:"}).appendTo(time);

			$("<input />", {class: "inputprice", value : timeData[2]}).appendTo(time);
			
			
		} else {
			var time = null;
		}
		
		
		return time;
	}

	this.createDay = function(dayData,type) {
		// type  1 = normal
		// type  2 = input


		var day = $("<div />", {class: "day"}),
		timeData = [];

		
		
		if (type == 1) {
			for (var k in dayData) {
				timeData = dayData[k].split(",");
				if (timeData.length != 0 ){
					var t = this.createTime(timeData);
					if (t !== null) {
						$(t).appendTo(day);
					}	
				}
			}
		} else if (type == 2){
			for (var k in dayData) {
				day.id = 9;
				timeData = dayData[k].split(",");
				if (timeData.length != 0 ){
					var t = this.createInputTime(timeData);
					if (t !== null) {
						$(t).appendTo(day);
					}	
				}
			}
			$("<div />", {class: "addTime", html : "Добавить<br />время"}).appendTo(day);
		}
		
		
		return day;
	}
	this.createWeek = function(weekData,type) {
		var week = $("<div />", {class: "week"}),
		dayData = new Array,
		day = {};
		week.days = new Array;
		
		for (var j in weekData) {
			if (weekData[j] instanceof Array) {
				dayData = weekData[j]
			} else {
				dayData = weekData[j].split(";");
			}
			
			
			day = this.createDay(dayData,type);
			
			$(day).appendTo(week);
			week.days.push(day);
			
		}
			

		return week;
	}

	this.createRooms = function(schedule,roomsData,type) {
		// type 1 -> vew mode
		//		2 -> input mode
		var c = false;
		
		$(schedule.rooms).remove();
		if (type == 1){
			schedule.rooms = $("<div />", {class: "rooms"}).appendTo(schedule);
			$("<div />", {class: "roomsheader", text : "Комнаты"}).appendTo(schedule.rooms);

			for (var i in roomsData) {
				$("<div />", {class: "schroom", text : roomsData[i]}).appendTo(schedule.rooms);
			}

		} else if (type == 2) {
			schedule.rooms = $("<div />", {class: "inputrooms"}).appendTo(schedule);
			$("<div />", {class: "roomsheader", text : "Комнаты"}).appendTo(schedule.rooms);
			
			

			for (var i = 1; i <= this.rooms;i++) {
				c = false;
				
				for (var j = 0; j < roomsData.length; j++) {
					if (i == roomsData[j]) {
						c = true;
						break;
					}
				}
				
				if (c === true){
					$("<div />", {class: "schroom", html : i+" <input type =\"checkbox\" checked=\"1\"/>"}).appendTo(schedule.rooms);
				} else {
					$("<div />", {class: "schroom", html : i+" <input type =\"checkbox\"/>"}).appendTo(schedule.rooms);	
				}
				
			}

		}


	}
	
	this.createButton = function(schedule,type) {
		$(schedule.button).remove();
		if (type == 1){
			schedule.button = $("<div />", {class: "schButton button inputButton",text : "Изменить расписание", "id" : schedule.id})
			$(schedule.button).appendTo(schedule);
		} else {
			schedule.button = $("<div />", {class: "schButton button saveSch",text : "Сохранить изменения", "id" : schedule.id})
			$(schedule.button).appendTo(schedule);
		}
		
	}

	this.createSchedule = function(scheduleData,id) {
		var schedule = $("<div />", {class: "schedule", id : "sch"+id});
		schedule.id = id;
		schedule.mode = 1;
		
		schedule.messenger = function (code) {
			var text = "";

			if (code === 1) {
				text = "Выделенные красным репетиции пересекаются!";
			} else if (code === 2) {
				text = "Выделенные красным репетиции имеют неправильный формат дат!";
			} else if (code === 3) {
				text = "Режим просмотра";
			} else if (code === 4) {
				text = "Режим редактирования";
			}

			$(this.message).html(text);
		}

		schedule.scan = function () {
			var sch = [[],[],[],[],[],[],[]],
			days = $(this.children()[3]).children(),
			tempArr = [],
			pointer = {};
			
			for (var i = 0; i < 7; i++) { // run thru days
				for (var j = 0,limit = $(days[i]).children().length - 1, day = $(days[i]).children(); j < limit; j++ ) { // run thru day
					pointer = day[j];
					tempArr = [];
					tempArr[0] = parseInt($($(pointer).children()[2]).val() * 1);
					tempArr[1] = parseInt($($(pointer).children()[3]).val() * 1);
					tempArr[2] = parseInt($($(pointer).children()[5]).val() * 1);
					tempArr[3] = parseInt($($(pointer).children()[6]).val() * 1);
					tempArr[4] = parseInt($($(pointer).children()[8]).val() * 1);
					tempArr[5] = pointer;

					sch[i].push(tempArr);
				}
			}
			return sch;
		}

		schedule.prepareToSend = function () {
			var sch = [[],[],[],[],[],[],[]],
			days = $(this.children()[3]).children(),
			tempArr = [],
			pointer = {};
			
			
			for (var i = 0; i < 7; i++) { // run thru days
				
				for (var j = 0, limit = $(days[i]).children().length - 1, day = $(days[i]).children(); j < limit; j++ ) { // run thru day
					pointer = day[j];
					console.log()
					tempArr = [];
					tempArr[0] = parseInt($($(pointer).children()[2]).val() * 1);
					tempArr[1] = parseInt($($(pointer).children()[3]).val() * 1);
					tempArr[2] = parseInt($($(pointer).children()[5]).val() * 1);
					tempArr[3] = parseInt($($(pointer).children()[6]).val() * 1);
					tempArr[4] = parseInt($($(pointer).children()[8]).val() * 1);

					sch[i].push(tempArr);
				}
			}

			sch.push(this.roomsData);
			return sch;
		}

		schedule.refreshRooms = function () {
			var inputs = $(this.rooms).children(),
			newRoomsData = [];

			for (var i = 1; i < inputs.length; i++) {
				if ($(inputs[i].children[0]).prop("checked")){
					newRoomsData.push(i);
				}
			}
			this.roomsData = newRoomsData;
		}

		var schDeleteButton = $("<div />", {class: "schDeleteButton"});
		schDeleteButton.appendTo(schedule);

		
		var weekdays = $("<div />", {class: "weekdays"});
		schedule.weekData = scheduleData.slice(0,7);

		if (typeof(scheduleData[7]) == 'number') {
			scheduleData[7] = scheduleData[7].toString();
		}
		
		schedule.roomsData = scheduleData[7].split(",");
		for (var i = 0; i < schedule.roomsData.length; i++) {
			schedule.roomsData[i] = parseInt(schedule.roomsData[i]);
		}
		

		
		schedule.message = $("<div />", {class: "message"}).appendTo(schedule);
		schedule.messenger(3);
		for (var i = 0; i < 7; i++) { 
			$("<div />",{class : "weekday", text: this.dayNamesRus[i]} ).appendTo(weekdays);
		}

		$(weekdays).appendTo(schedule);

		schedule.week = this.createWeek(schedule.weekData,1)
		$(schedule.week).appendTo(schedule);
		
		// append rooms
		this.createRooms(schedule,schedule.roomsData,1);
		

		// append button
		this.createButton(schedule,1);

		return schedule;
	}

	this.transformToInputSchedule = function(schedule) {
		schedule.mode = 2;
		schedule.messenger(4);
		$(schedule.week).remove();

		schedule.week = $(this.createWeek(schedule.weekData,2));
		$(schedule.week).appendTo(schedule);
		
		this.createRooms(schedule,schedule.roomsData,2);

		this.createButton(schedule,2);
		resizeBody();
		this.resizeDays();
	}

	this.transformToViewSchedule = function(schedule,newWeekData) {
		schedule.mode = 1;
		schedule.messenger(3);
		$(schedule.week).remove();

		schedule.week = $(this.createWeek(newWeekData,1));
		$(schedule.week).appendTo(schedule);
		schedule.weekData = newWeekData;
		
		this.createRooms(schedule,schedule.roomsData,1);

		this.createButton(schedule,1);
		resizeBody();
		this.resizeDays();
	}

	this.resizeDays = function() {
		$(".schedule .day").css("min-height",function () {
			var h1 = $(this).parent().css("height"),
			h2 = $($(this).parent().parent().children()[4]).css("height");

			if (parseInt(h1) > parseInt(h2)) {
				return h1;
			} else {
				return h2;
			}
			return $(this).parent().css("height");
		});
	}

	this.checkSchedule = function(schedule) {

		var sch = schedule.scan();
		var h0,m0,h1,m1,h2,m2,h3,m3,validError,index1,index2,index3,index4,
		good = true; // GOOOOOOD
		for (var i = 0; i < 7; i++) { // run thru days
			for (var j = 0,limit = sch[i].length; j < limit; j++ ) { // run thru day
				validError = 1;
				h0 = sch[i][j][0];
				m0 = sch[i][j][1];
				h1 = sch[i][j][2];
				m1 = sch[i][j][3];

				index1 = sch[i][j][0]*100 + sch[i][j][1];
				index2 = sch[i][j][2]*100 + sch[i][j][3];

				if (h0 < 0 || h0 > 23 || h1 < 0 || h1 > 23 || m0 < 0 || m0 > 59 || m1 < 0 || m1 > 59 || !is_numeric(h0) || !is_numeric(m0) || !is_numeric(h1) || !is_numeric(m1) || index1 >= index2 ) {
					$(sch[i][j][5]).css('background-color', '#fdd');
					schedule.messenger(2);
					validError = 0;
					good = false;
				} else {
					$(sch[i][j][5]).css('background-color', '#fafafa');
				}

					
					
				for (var k = 0; k < limit; k++) { // run thru other days
					index3 = sch[i][k][0]*100 + sch[i][k][1];
					index4 = sch[i][k][2]*100 + sch[i][k][3];
					
					if (k !== j) {
						if ((index1 > index3 && index1 < index4) || (index2 > index3 && index2 < index4) ) {
							$(sch[i][j][5]).css('background-color', '#fdd');
							$(sch[i][k][5]).css('background-color', '#fdd');
							good = false;
							schedule.messenger(1);
						} else if (!((index1 > index3 && index1 < index4) || (index2 > index3 && index2 < index4)) && validError) {
							$(sch[i][j][5]).css('background-color', '#fafafa');
						}
					}
				}
			}
		}	
		return good;	
	}

	this.checkRooms = function(schedule) { 
		var totalRooms = [],
		roomsOverlap = [];

		for (var i = 0; i < this.schedules.length; i++){
			if (schedule.id != i && this.schedules[i] != null) {
				totalRooms = totalRooms.concat(this.schedules[i].roomsData);
			}
		}
		

		for (var i = 0; i < schedule.roomsData.length; i++) {
			if (totalRooms.indexOf(schedule.roomsData[i]) != -1) {
				roomsOverlap.push(schedule.roomsData[i]);
			}
		}

		return roomsOverlap;
	}

	this.getDelete = function() {
		var rooms = [],
		i = 0;

		for(var i = 1; i <= this.rooms; i++) {
			rooms.push(i);
		}
		

		for (i in this.schedules) {
			if (this.schedules[i] !== null) {
				rooms = diff(rooms,this.schedules[i].roomsData);
			}
			
		}
		

		return rooms;
	}

	
	var factory = this;
	$(".inputButton").live("click",factory,function() {
		var parent  = $(this).parent(),
		id = parent.attr('id').substr(3),
		schedule = factory.schedules[id];

		factory.transformToInputSchedule(schedule);
	})

	$(".addTime").live("click",factory,function() {
		var parent  = $(this).parent(), // day
		time = factory.createInputTime(null);
		$(this).remove();
		
		$(time).appendTo(parent);
		$("<div />", {class: "addTime", html : "Добавить<br />время"}).appendTo(parent);
		
		factory.resizeDays();
		resizeBody();
	})

	$(".timeDeleteButton").live("click",factory,function(){
		var parent = $(this).parent();
		parent.remove();
		factory.resizeDays();
		resizeBody();
	})

	$(".schDeleteButton").live("click",factory,function(){
		var parent  = $(this).parent(),
		id = parent.attr('id').substr(3),
		schedule = factory.schedules[id],
		send = [];

		for (var i = 0; i < 8 ; i ++) {
			send[i] = [];
		}
		
		schedule.refreshRooms();
		send[8] = schedule.roomsData;
		console.log(send);
		var jsonstr = JSON.stringify(send);

		MakeScheduleRequest(jsonstr,schedule,factory);

		parent.remove();
		factory.resizeDays();
		resizeBody();
	})

	$(".saveSch").live("click",factory,function() {
		var parent  = $(this).parent(),
		id = parent.attr('id').substr(3),
		schedule = factory.schedules[id],
		sch = schedule.scan();
		send = [];
		

		check = factory.checkSchedule(schedule);
		schedule.refreshRooms();
		
		var roomsOverlap = factory.checkRooms(schedule);
		this.activeSchedule = id;
		

		send = schedule.prepareToSend(sch);
		send[8] = factory.getDelete(); // add rooms for delete

		console.log(send);

		var jsonstr = JSON.stringify(send);
	



		if (!check) {
			var message = "Репетиции в расписание пересекаются или неправильно отформатированы!";
			overlay(1,message);
			setTimeout(function() {
				overlay(2,"");
			},1500)
		}
		
		if (roomsOverlap.length !== 0) {
			var message = "";
			if (roomsOverlap.length > 1 ){
					
				message = "Расписания для комнат";
	            for (var i = 0; i < roomsOverlap.length; i++){
					if (i < roomsOverlap.length - 2) {
						message = message+" "+roomsOverlap[i]+",";
					}
					if (i == roomsOverlap.length-2) {
						message = message + " "+roomsOverlap[i];
					}
					if (i == roomsOverlap.length-1) {
						message = message + " и "+roomsOverlap[i]+" ";
					}
				}
				
				message = message +"уже существуют. Вы уверены, что хотите изменить их?";
			} else {
				message = "Расписание для комнаты " + roomsOverlap[0] + " уже существует. Вы уверены, что хотите изменить его?";
			}
			overlay(3,message);
		
		} else if (check){
			var message = "Пожалуйста подождите...";
			overlay(2,"");
			overlay(1,message);
			MakeScheduleRequest(jsonstr,schedule,factory);
		}

		$("#no").live("click", function(){
			overlay(2,"");
		})

		$("#yes").live("click",send, function(){

			var message = "Пожалуйста подождите..."
			overlay(2,"");
			overlay(1,message);
			
			
			MakeScheduleRequest(jsonstr,schedule,factory);
			factory.refreshAllRooms(id);
		})

		
		factory.resizeDays();
		resizeBody();
	})

	/*    MAIN PROGRAM */
		var factory = this;
		$(".addButton").live("click", factory, function() {
			factory.schedules[factory.schedules.length] = factory.createSchedule(["","","","","","","",""],factory.schedules.length);
			factory.transformToInputSchedule(factory.schedules[factory.schedules.length - 1]);
			$(factory.schedules[factory.schedules.length - 1]).insertAfter(this);
			resizeBody();
			factory.resizeDays();
		})
	
		this.createAddButton(target);
	
		for (var i in primeschedules) {
			console.log(primeschedules[i]);
			if (!(primeschedules[i][0] === "" && primeschedules[i][1] === "" && primeschedules[i][2] === "" && primeschedules[i][3] === "" && primeschedules[i][4] === "" && primeschedules[i][5] === "" && primeschedules[i][6] === "")) {
				scheduleData = primeschedules[i];
				this.schedules[i] = this.createSchedule(scheduleData,i);
				$(this.schedules[i]).appendTo(target); 
			}
			
		}
		resizeBody();
		this.resizeDays();
	
}

/*
errors: response[1] -> old pass != old pass

*/
function MakePassRequest(p1,p2) {
	var xmlHttp = getXMLHttp();
	
	

	xmlHttp.onreadystatechange = function() {
		if(xmlHttp.readyState == 4) {
			overlay(2,"");
			var response = xmlHttp.responseText.split(",");
			console.log(response);
			if (response[0] == 1) {
				overlay(1,"Пароль успешно обновлен!");
            	setTimeout(function() {
             		overlay(2,"");
            	},1500);
			} else if (response[0] == 0 && response[1] == 2) {
				
				overlay(1,"Вы не правильно ввели настоящий пароль! Попробуйте снова.");
            	setTimeout(function() {
             		overlay(2,"");
            	},1700);
			} else if (response[0] == 0 && response[1] == 1) {
				
				overlay(1,"Что-то пошло не так! Попробуйте снова.");
            	setTimeout(function() {
             		overlay(2,"");
            	},1700);
			}
		}
	}

	
	overlay(1,"Пожалуйста подождите...");
	
	xmlHttp.open("POST", "http://www.basebooking.ru/admin/password.php?r=" + Math.random(), true);
	xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("p1="+p1+"&p2="+p2);
}

function nameRequest(name) {
	if (name.length < 2) {
		return false;
	}
	var xmlHttp = getXMLHttp();
	
	xmlHttp.onreadystatechange = function() {
		if(xmlHttp.readyState == 4) {
			console.log(xmlHttp.responseText);
			var response = xmlHttp.responseText.split(",");

			if (response[0] == 1) {
				overlay(2,"");
				overlay(1,"Имя было успешно изменено.");
            	setTimeout(function() {
             		overlay(2,"");
            	},1700);
            	$("#basename").children()[0].href = "http://www.basebooking.ru/base.php?name="+name;
            	$("#basename").children()[0].innerHTML = name+"<div class=\"des6\">перейти на страницу базы</div>";

			} else {
				if (response[1] == 1) {
					overlay(2,"");
					overlay(1,"Что-то пошло не так! Попробуйте снова.");
	            	setTimeout(function() {
	             		overlay(2,"");
	            	},1700);
				} else if (response[1] == 2) {
					overlay(2,"");
					overlay(1,"База с таким именем уже существует! Попробуйте снова.");
	            	setTimeout(function() {
	             		overlay(2,"");
	            	},1700);
				} else if (response[1] == 3) {
					overlay(2,"");
					overlay(1,"Имя слишко короткое Попробуйте снова.");
	            	setTimeout(function() {
	             		overlay(2,"");
	            	},1700);
				}
			}
		}
	}

	
	overlay(1,"Пожалуйста подождите");
	
	xmlHttp.open("POST", "http://www.basebooking.ru/admin/namechange.php?r=" + Math.random(), true);
	xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("name="+name);


}


