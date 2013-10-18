function scheduleFactory(primeschedules,target,rooms) {
	this.scheduleData = [];
	this.schedules = [];
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
		var c = false,
		roomName = "";
		
		$(schedule.rooms).remove();
		if (type == 1){
			schedule.rooms = $("<div />", {class: "rooms"}).appendTo(schedule);
			$("<div />", {class: "roomsheader", text : "Комнаты"}).appendTo(schedule.rooms);

			for (var i in roomsData) {
				if (window.roomNames != undefined) {
					roomName = "<span>"+window.roomNames[roomsData[i]]+"</span>";
				} else {
					roomName = "<span>"+roomsData[i]+"</span>";
				}
				
				$("<div />", {class: "schroom", html : roomName}).appendTo(schedule.rooms);
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
					if (window.roomNames != undefined) {
						roomName = "<span>"+window.roomNames[i]+"</span>";
					} else {
						roomName = "<span>"+i+"</span>";
					}

					$("<div />", {class: "schroom", html : roomName+" <input type =\"checkbox\" checked=\"1\"/>"}).appendTo(schedule.rooms);
				} else {
					if (window.roomNames != undefined) {
						roomName = "<span>"+window.roomNames[i]+"</span>";
					} else {
						roomName = "<span>"+i+"</span>";
					}
					$("<div />", {class: "schroom", html : roomName+" <input type =\"checkbox\"/>"}).appendTo(schedule.rooms);	
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
				if ($(inputs[i].children[1]).prop("checked")){
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
		var weekdays = $(".weekdays");
		weekdays.children().remove();
		for (var i = 0; i < 7; i++) { 
			
			$("<div />",{class : "weekday", html: this.dayNamesRus[i]+"<input type=\"checkbox\" />"} ).appendTo(weekdays);
		}

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
		
		send[7] = schedule.roomsData;
		send[8] = [];

		console.log("send = ");
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
		sch = schedule.scan(),
		send = [];
		

		check = factory.checkSchedule(schedule);
		schedule.refreshRooms();
		
		var roomsOverlap = factory.checkRooms(schedule);
		this.activeSchedule = id;
		
		
		send = schedule.prepareToSend(sch);
		send[8] = factory.getDelete(); // add rooms for delete

		console.log("send =1 ");
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
			
			if (!(primeschedules[i][0] === "" && primeschedules[i][1] === "" && primeschedules[i][2] === "" && primeschedules[i][3] === "" && primeschedules[i][4] === "" && primeschedules[i][5] === "" && primeschedules[i][6] === "")) {
				scheduleData = primeschedules[i];
				this.schedules[i] = this.createSchedule(scheduleData,i);
				$(this.schedules[i]).appendTo(target); 
			}
			
		}

		if (this.schedules.length == 0) {
			$(".addButton").click();
			$(".schroom input").click();
		}

		resizeBody();
		this.resizeDays();
	
}