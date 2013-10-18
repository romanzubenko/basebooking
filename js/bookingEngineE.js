/*	DOCUMENTATION:

METHODS:
	checkBooking	
	formatTime
	leadzero
	showConfirmation
	showBooking
	showSchedules
	message
	createFTime
	createNFTime
	createRoom
	appendFBookings
	appendNFBookings
	appendScheduleHeader
	showSchedule
	prepareSchedules
	prepareBookings
	book
	deleteBooking
*/

;(function(window) {

	var BookingEngine = function (target,schedules,bookings,idb,owner,rooms,roomNames,nf,firsthour,lasthour,time) {
		firsthour = parseInt(firsthour);
		lasthour = parseInt(lasthour);


		this.target = target;
		this.schedules = schedules;
		this.bookings = bookings;
		this.idb = idb;
		this.nf = nf; // 0 -> fixed / 1 -> not fixed
		this.owner = owner; // if owner == true then make admin booking with acces to all musician details in calendar
		this.rooms = rooms;
		this.roomNames = roomNames;
		this.firstRoom = 1;
		this.dayOfWeek = 0;
		if (firsthour == 0) {
			firsthour = 8;
		}
		this.firsthour = firsthour;
		if (lasthour == 0) {
			lasthour = 24;
		}
		this.lasthour = lasthour;
		this.bookingPointers = [];
		this.date = "";
		this.div = {};
		this.timestamp = time;

		this.bookingData = {
			name : "",
			vkid : 0,
			lastname : "",
			hash : "",
			date : "",
			start : 0,
			end : 0,
			room : 0,
			price : 0,
			phone : "",
			idb : this.idb
		};

		this.dayNamesRus = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

		/* 	USER INTERFACE	*/
		var triangle = $("<div />",{class : "tr"});
		$("<div />",{class : "tr1"}).appendTo(triangle);
		$("<div />",{class : "tr2"}).appendTo(triangle);

		
		this.datepicker = $("<div />",{id : "datepicker"}).appendTo(this.target);
		this.schedule = $("<div />",{id : "basebooking_schedule"}).appendTo(this.target);
		this.confirmation = $("<div />",{id : "basebooking_confirmation"}).appendTo(this.target);
		

		/* 	USER INTERFACE	*/

		/* 	EVENTS	*/
		var	BookingEngine = this; // this pointer for event handlers

		// exit booking page
		$(document).on("click","#exdial",function () {
			$("#dial").remove();
			BookingEngine.showSchedules();
			BookingEngine.scroll();
			BookingEngine.div.condition = true;
			$(document).on('mouseenter','.NFroom', e4);
			$(document).on('mouseleave','.NFroom', e5);
			$(document).on('click','.NFroom',e6);
		});
	
		// exit schedule page
		$(document).on("click","#exsch",function (event) {
		 	$(this).remove();
		 	BookingEngine.schedule.hide();
		 	BookingEngine.schedule.children().remove();
		 	$("#nextk").remove();
		  	$("#nextk").off("click");
		 	$("#prevk").off("click");
		 	$("#datepicker").fadeIn(200);
		 	BookingEngine.scroll();
		});

		// click time
		$(document).on("click",".Fbooking.free",function () {
			var scheduleIndex = $(this).index() - 1,
			roomNumber = 0;
			roomNumber = $(this).parent().index() + 1;

			BookingEngine.bookingData.room = roomNumber;
			BookingEngine.bookingData.start = BookingEngine.schedules[roomNumber][BookingEngine.dayOfWeek][scheduleIndex][0];
			BookingEngine.bookingData.end = BookingEngine.schedules[roomNumber][BookingEngine.dayOfWeek][scheduleIndex][1];
			BookingEngine.bookingData.price = BookingEngine.schedules[roomNumber][BookingEngine.dayOfWeek][scheduleIndex][2];
			BookingEngine.showConfirmation();
		});

		// fixed click for booking review
		$(document).on("click",".Fbooking.booked.owner",function () {
			var scheduleIndex = $(this).index() - 1,
			roomNumber = 0;
			roomNumber = $(this).parent().index() + 1;	
			
			start = BookingEngine.schedules[roomNumber][BookingEngine.dayOfWeek][scheduleIndex][0],
			end = BookingEngine.schedules[roomNumber][BookingEngine.dayOfWeek][scheduleIndex][1],
			index = BookingEngine.checkBooking(start,end,roomNumber),
			dataPointer = BookingEngine.bookings[roomNumber][index];

			BookingEngine.bookingData.bookingIndex = index;
			BookingEngine.showBooking(dataPointer);
		});

		// not fixed click for booking review
		$(document).on("click",".NFbooking.booked.owner",function () {
			var bookingIndex = $(this).index() - 1,
			roomNumber = $(this).parent().index() + 1,		
			dataPointer = BookingEngine.bookingPointers[roomNumber][bookingIndex];

			BookingEngine.bookingData.bookingIndex = bookingIndex;
			BookingEngine.showBooking(dataPointer);
		});

		if (rooms > 4) {
			$(document).on("click","#nextArrow",function () {
				var container = $("#bookings_container"),
				left = parseInt(container.css("left"),10),
				move = 155;
				
				if (left - 155 < 0) {
					move = left;
				}
				container.animate({
					left: '-='+move
				}, 300);
			});

			$(document).on("click","#prevArrow",function () {
				var container = $("#bookings_container"),
				left = parseInt(container.css("left"),10),
				move = 155;
				
				if (left + 155 > BookingEngine.bookingsLeft) {
					
					move = BookingEngine.bookingsLeft - left;
				}
				container.animate({
					left: '+='+move
				}, 300);
			});
		}
		$(document).on("click","#bookingButton",function () {
			BookingEngine.book({});
		});

		

		$(document).on("click","#nextday",function () {
			var date = BookingEngine.pickedDate,
			datetemp = "";
			date.setTime(BookingEngine.pickedDate.getTime() + 86400000);
			datetemp = date.getDate()+"."+(date.getMonth()+1) + "." + date.getFullYear();
			BookingEngine.bookingData.date = datetemp;
			BookingEngine.pickedDate = date;
			BookingEngine.schedule.hide();
		 	BookingEngine.schedule.children().remove();
			window.booking.showSchedules();
		});

		$(document).on("click","#prevday",function () {
			var date = BookingEngine.pickedDate,
			datetemp = "";
			date.setTime(BookingEngine.pickedDate.getTime() - 86400000);
			datetemp = date.getDate()+"."+(date.getMonth()+1) + "." + date.getFullYear();
			BookingEngine.bookingData.date = datetemp;
			BookingEngine.pickedDate = date;
			BookingEngine.schedule.hide();
		 	BookingEngine.schedule.children().remove();
			window.booking.showSchedules();
		});
		
	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!	

		$(this.datepicker).datepicker({
			monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'],
			dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
			dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sat'], 
			firstDay: 1, 
			prevText: '', 
			nextText: '',
			onSelect: function(dateText, inst) {
				var date = $("#datepicker").datepicker('getDate'),
				datetemp = date.getDate()+"."+(date.getMonth()+1) + "." + date.getFullYear();
				window.booking.bookingData.date = datetemp;
				window.booking.pickedDate = date;
				window.booking.showSchedules();
			}
		});

		$(document).on("click","#delete_booking",function () {
			BookingEngine.deleteBooking();
		});

		if (this.nf == "1") {

			/*

				first declare all functions then attach them accordingly
				all this needed to be done because event handlers are attached and detached multiple times
			*/

			var e1 = function() {
				BookingEngine.div.condition = false;
				BookingEngine.div.hide();
				$(document).off('mousemove');
				$(document).off('click','.NFroom');
			},

			e2 = function() {
				BookingEngine.div.condition = true;
				
				$(document).on('mousemove', e3);
				$(document).on('click','.NFroom',e6);
			},

			e3 = function(e){
				pointer = $(".futureB");

				var parentOffset = BookingEngine.div.parent().offset(),
				posY = e.pageY - parentOffset.top - 20,
				roomNumber = pointer.parent().index() + 1;
				start = parseInt(60 * ((posY - 56) / 55 + BookingEngine.firsthour),10),
				end = BookingEngine.div.start + 120;
				BookingEngine.div.start = BookingEngine.formatTime(start);
				BookingEngine.div.end = BookingEngine.formatTime(end);
				BookingEngine.bookingData.room = roomNumber;

				if (posY > 53 && BookingEngine.div.condition ) {
					BookingEngine.div.show();
					BookingEngine.div.css({ top: posY + 30 });
					BookingEngine.div.start = BookingEngine.div.start.split(":");
					BookingEngine.div.end = BookingEngine.div.end.split(":");
					
				} else {
					BookingEngine.div.hide();
				}
			},

			e4 = function() {
				BookingEngine.div = $("<div/>",{class:"futureB",html:"<div class=\"price\">Click to book</div>"}).css("height","70px").css("position","absolute").appendTo(this).hide();
				BookingEngine.div.time = $("<div/>",{class:"timesch"}).prependTo(BookingEngine.div);
				BookingEngine.div.condition = true;
				$(document).on('mousemove', e3);
			},

			e5 = function(){
				BookingEngine.div.remove();
				$(document).off('mousemove');
			},

			e6 = function(e) {
		        BookingEngine.div.condition = false;
		        BookingEngine.div.remove();
		        $(document).off('mousemove');
		        $(document).off('mouseenter','.NFroom');
		        $(document).off('mouseleave','.NFroom');
		        $(document).off('click','.NFroom');
		        $(document).off('mouseenter','.booked');
				$(document).off('mouseleave','.booked');




		     	BookingEngine.div = $("<div/>",{class:"NFbooking",html:"<div class=\"price\"><a>More</a></div>"}).css("height","70px").css("position","absolute").css("z-index","7").appendTo(this).show();
		     	BookingEngine.div.time = $("<div/>",{class:"timesch"}).prependTo(BookingEngine.div);
		     	
		     	var parentOffset = BookingEngine.div.parent().offset(),
		     	posY = e.pageY - parentOffset.top,
		     	hSelect = "<select>",
		     	mSelect = "<select>";

		     	for (var i = 0; i < 24; i++) {
		     		hSelect +=" <option value\""+i+"\">"+BookingEngine.leadzero(i)+"</option>";
		     	}

		     	for (var i = 0; i < 60; i++) {
		     		mSelect +=" <option value\""+i+"\">"+BookingEngine.leadzero(i)+"</option>";
		     	}

		     	hSelect += "</select>";
		     	mSelect += "</select><br/>";

		     	var startH = hSelect,
		     	startM = mSelect,
		     	endH = hSelect,
		     	endM = mSelect;
		     	
				BookingEngine.div.start = parseInt(60 * ((posY - 56) / 55 + BookingEngine.firsthour),10);
				BookingEngine.div.end = BookingEngine.div.start + 120;

				if (BookingEngine.div.start < BookingEngine.firsthour * 60 || BookingEngine.div.end> BookingEngine.lasthour * 60 ) {
					e8();

					console.log("start = " + BookingEngine.div.start+"; limitst = " + BookingEngine.firsthour * 60 +"; \nend = "+BookingEngine.div.end + " limit end = "+BookingEngine.lasthour * 60);
					return 0;
				}
				BookingEngine.div.start = BookingEngine.formatTime(BookingEngine.div.start).split(":");
				BookingEngine.div.end = BookingEngine.formatTime(BookingEngine.div.end).split(":");


				startH = $(startH).appendTo(BookingEngine.div.time);
				BookingEngine.div.time.append(document.createTextNode(":"))

		     	startM = $(startM).appendTo(BookingEngine.div.time);
		     	endH = $(endH).appendTo(BookingEngine.div.time);
		     	BookingEngine.div.time.append(document.createTextNode(":"))
		     	endM = $(endM).appendTo(BookingEngine.div.time);
			
				

				$($(".timesch select")[0]).val(BookingEngine.div.start[0]);
				$($(".timesch select")[1]).val(BookingEngine.div.start[1]);
				$($(".timesch select")[2]).val(BookingEngine.div.end[0]);
				$($(".timesch select")[3]).val(BookingEngine.div.end[1]);
				
				
				$("<div/>",{class:"exbutton"}).prependTo(BookingEngine.div).show().css("margin-left","135px");
				BookingEngine.div.css({ top: posY,height: 104 });
		    },

		    e7 = function(e) { 
		    	var pointer = $(this).parent().parent(),
				roomNumber = $(this).parent().parent().parent().index() + 1;
				BookingEngine.bookingData.room = roomNumber;
				if (BookingEngine.validateNFBooking(pointer)) {
					BookingEngine.showConfirmation();
				}
			},

			e8 = function(e) { 
				$(this).parent().remove();
				$(document).on('mousemove', e3);
		        $(document).on('mouseenter','.NFroom',e4);
		        $(document).on('mouseleave','.NFroom',e5);
		        $(document).on('click','.NFroom',e6);
		        $(document).on('mouseenter','.booked', e1);
				$(document).on('mouseleave','.booked', e2);
			}


			$(document).on('mouseenter','.booked', e1);
			$(document).on('mouseleave','.booked', e2);
			$(document).on('mouseenter','.NFroom', e4);
			$(document).on('mouseleave','.NFroom', e5);
			$(document).on('click','.NFroom',e6);
			$(document).on('click','.price a',e7);
			$(document).on('click','.NFbooking .exbutton',e8);

		}

		
		/* 	EVENTS 	END  */

		/* 	INITIALIZE	*/
		this.prepareSchedules();
		this.prepareBookings();
	}

	BookingEngine.prototype.clock = function () {
		var date = new Date(),
		offset =  date.getTimezoneOffset() * 60 * 1000;
		date = new Date(this.timestamp * 1000 + offset),
		h  = ( date.getHours() < 10 ? "0" : "" ) + date.getHours(),
		m  = ( date.getMinutes() < 10 ? "0" : "" ) + date.getMinutes();

		$(".clock").html(h + ":" + m);

		this.datepicker.datepicker("setDate" , date );

		setInterval(function(){
			var h  = ( date.getHours() < 10 ? "0" : "" ) + date.getHours(),
			m  = ( date.getMinutes() < 10 ? "0" : "" ) + date.getMinutes();

			date.setMinutes(date.getMinutes() + 1);
			$(".clock").html(h + ":" + m);
		},60000);

	}


	BookingEngine.prototype.validateNFBooking = function (time) {
		var start = [],
		end = [],
		intersectTest = true,
		diff = 0;
		if ($(".timesch select").length !== 0) {
			start[0] = $($(".timesch select")[0]).val();
			start[1] = $($(".timesch select")[1]).val();
			end[0] = $($(".timesch select")[2]).val();
			end[1] = $($(".timesch select")[3]).val();
		} else {
			start = this.div.start;
			end = this.div.end;
		}
		

		this.bookingData.start = parseInt(start[0],10) * 60 + parseInt(start[1],10);
		this.bookingData.end = parseInt(end[0],10) * 60 + parseInt(end[1],10);
		diff = this.bookingData.end - this.bookingData.start;

		if (this.bookingData.start >= this.bookingData.end) {			
			$("input[name=\"start\"]").css("border", "1px solid #f44").css("background-color", "#fff3f3");
			$("input[name=\"end\"]").css("border", "1px solid #f44").css("background-color", "#fff3f3");
			return false;
		} else {
			$("input[name=\"start\"]").css("border", "1px solid #d1d1d1").css("background-color", "#fff");
			$("input[name=\"end\"]").css("border", "1px solid #d1d1d1").css("background-color", "#fff");
		}

		for (var i = 0, limit = this.bookingPointers[this.bookingData.room].length; i < limit; i++) {
			if (this.bookingData.start >= this.bookingPointers[this.bookingData.room][i].start && this.bookingData.start < this.bookingPointers[this.bookingData.room][i].end) {
				intersectTest = false;
				this.bookingData.start = this.bookingPointers[this.bookingData.room][i].end;
				this.bookingData.end = this.bookingData.start + end;
			}

			if (this.bookingData.end > this.bookingPointers[this.bookingData.room][i].start && this.bookingData.end <= this.bookingPointers[this.bookingData.room][i].end) {
				intersectTest = false;
				this.bookingData.end = this.bookingPointers[this.bookingData.room][i].start;
			}
		}

		if (this.bookingData.end < this.bookingData.start) {
			this.bookingData.end = parseInt(this.bookingData.start,10) + 180;
		}

		start = this.formatTime(this.bookingData.start).split(":");
		end = this.formatTime(this.bookingData.end).split(":");


		var yStart = ((this.bookingData.start / 60) - this.firsthour) * 37 + 56,
		height = ((this.bookingData.end - this.bookingData.start) / 60) * 37 - 6;
		$($(".timesch select")[0]).val(start[0]);
		$($(".timesch select")[1]).val(start[1]);
		$($(".timesch select")[2]).val(end[0]);
		$($(".timesch select")[3]).val(end[1]);

		height += "px";
		yStart += "px";
		time.animate({"top":yStart},"slow").animate({"height":height},"slow");
		

		if (intersectTest) {
			return true;
		} else {
			return false;
		}
		
	}

	/*
	Works only in fixed mode.
	Function checks if for given time booking exists and
	returns for success search index in this.bookings OR -1 if booking was not found
	*/
	BookingEngine.prototype.checkBooking = function (start,end,room) {
		var date = this.date,
		searchIndex = -1;
		start = ""+start;
		end = ""+end;
		if (this.bookings[room] === undefined) {
			this.bookings[room] = [];
		}
		for (var i = 0, limit = this.bookings[room].length; i < limit; i++) {
			if (""+this.bookings[room][i]['start'] === start && ""+this.bookings[room][i]['end'] === end && ""+this.bookings[room][i]['date'] === date) {
				searchIndex = i;
				return searchIndex;
			}
		}
		return searchIndex;
	}

	/*
	Little function that formats time from minutes past midnight to human readable 24 european hour form
	*/
	BookingEngine.prototype.formatTime = function (x) {
		x = parseInt(x,10);
		var time = "",
		h = Math.floor(x / 60),
		m = x - h * 60;

		h = this.leadzero(h);
		m = this.leadzero(m);
		time = h + ":" + m;
		return time;
	}

	/*
	prepends zero for minutes or hours for time formatting purposes
	*/

	BookingEngine.prototype.leadzero = function(x) {
		if (x < 10) {
			x = "0"+x;
		} 
		return x;
	}

	BookingEngine.prototype.scroll = function(x) {
		if ($('body').scrollTop() > 183) {
			$('body').animate({scrollTop:"110px"}, 'slow');
		} 
		return this;
	}

	/*
	Works both in fixed and non-fixed mode exactly the same.
	Also it works differently if user is business owner/musician/non registered person
	Only requirement for good work is all filled this.bookingData
	Function hides schedule and creates booking confirmation
	*/
	BookingEngine.prototype.showConfirmation = function () {
		this.scroll();
		basebooking = this;

		this.confirmation.children().remove();
		this.bookingData.vkid = 0;
		this.confirmation.hide();
		this.schedule.hide();
		this.confirmation.fadeIn(200);
	
		var table = $("<table />"),
		dial = $("<div />",{id: "dial"}).appendTo(this.confirmation),
		dial1 = $("<div />",{id: "dial1"}).appendTo(dial),
		dial2 = $("<div />",{id: "dial2"}).appendTo(dial);
		$("<tr />",{html: "<td>Date:</td><td>"+this.dayNamesRus[this.dayOfWeek]+", "+this.bookingData['date']+"</td>"}).appendTo(table);
		$("<tr />",{html: "<td>Time:</td><td>"+this.formatTime(this.bookingData['start'])+" - "+this.formatTime(this.bookingData['end'])+"</td>"}).appendTo(table);
		$("<tr />",{html: "<td>Room:</td><td>"+this.roomNames[this.bookingData['room']]+"</td>"}).appendTo(table);
			
		if (window.user !== undefined) {
			$("<tr />",{html: "<td>Name:</td><td>"+window.user.info.name+" "+window.user.info.lastname+"</td>"}).appendTo(table);
		} else if (this.owner) {
			$("<tr />",{html: "<td>Name:</td><td><input type=\"text\" name=\"basebooking_name\"></input></td>"}).appendTo(table);
		}

		$("<tr />",{html: "<td>Band:</td><td><input type=\"text\" name=\"basebooking_band\">"+""+"</input></td>"}).appendTo(table);
		$("<tr />",{html: "<td>Phone:</td><td><input type=\"text\" name=\"basebooking_phone\">"+""+"</input></td>"}).appendTo(table);



		table.appendTo(dial1);
		
		if (this.nf == "1") {
			if (window.page.equipment[this.bookingData.room - 1].price !== "") {
				$("<div />",{class:"priceInfo",html:"<b>Цена</b><br />"+window.page.equipment[this.bookingData.room - 1].price}).appendTo(dial2);
			} else {
				$("<div />",{class:"priceInfo",html:"<b>Цена</b><br />"+"Information on prices on this room is not availiable"}).appendTo(dial2);
			}
		} else {
			dial2.html("<br />$"+this.bookingData['price']+"");
		}
		$("<div />",{id: "exdial", class : "exbutton"}).appendTo(dial).show();

		if (window.user !== undefined || this.owner) {
			$("<div />",{class: "button bookingButton", id:"bookingButton", text:"Book"}).appendTo(dial2);
		} else {
			$("<div/>", { "id": "vk_auth"}).appendTo(dial2);
			$("#vk_auth").show();
			$("#vk_auth").css("border", "no");
			 	VK.Widgets.Auth("vk_auth", {
					width: "166px",
					height: "40px",
					onAuth: function(data) {
						basebooking.book(data);
					}
				});
		}
	
	}

	/*
	Works both in fixed and non-fixed mode exactly the same.
	It works ONLY if user is business owner
	Another requirement for good work is all filled this.bookingData
	Function hides schedule and creates booking review
	*/
	BookingEngine.prototype.showBooking  = function (data) {
		this.scroll();
		this.schedule.fadeOut(200);
		this.confirmation.children().remove();
		this.bookingData.vkid = data.vkid;
		this.bookingData.start = data.start;
		this.bookingData.end = data.end;
		this.bookingData.room = data.room;
	

		var table = $("<table />"),
		dial = $("<div />",{id: "dial"}).appendTo(this.confirmation),
		dial1 = $("<div />",{id: "dial1"}).appendTo(dial),
		dial2 = $("<div />",{id: "dial2"}).appendTo(dial);

		
		dial1.html("<span>Review booking</span>");
		$("<div />",{class: "space"}).appendTo(dial1);
		
		$("<tr />",{html: "<td>Date:</td><td>"+this.dayNamesRus[this.dayOfWeek]+", "+data['date']+"</td>"}).appendTo(table);
		$("<tr />",{html: "<td>Time:</td><td>"+this.formatTime(data['start'])+" - "+this.formatTime(data['end'])+"</td>"}).appendTo(table);
		
		$("<tr />",{html: "<td>Room:</td><td>"+this.roomNames[data['room']]+"</td>"}).appendTo(table);
		if (data['vkid'] != 0) {
			$("<tr />",{html: "<td>Musician:</td><td><a href=\"http://www.vk.com/id"+data['vkid']+"\">"+data['name']+" "+data['lastname']+"</a></td>"}).appendTo(table);
		} else {
			$("<tr />",{html: "<td>Musician:</td><td>"+data['name']+"</td>"}).appendTo(table);
		}
		
		$("<tr />",{html: "<td>Band:</td><td>"+data['band']+"</td>"}).appendTo(table);
		$("<tr />",{html: "<td>Phone:</td><td>"+data['phone']+"</td>"}).appendTo(table);

		table.appendTo(dial1);
		dial2.html("<br />$"+data['price']+"");
		$("<div />",{id: "exdial", class : "exbutton"}).appendTo(dial).show();
		$("<div />",{id: "delete_booking", class : "button bookingButton", html:"Cancel"}).appendTo(dial2);
		
	}

	/*
	Function serves some internal puprposes only,
	gets date from datepicker,
	determines if it was past today
	translates weird day of week (my best guess is it was american form)
	and finally launches .showSchedule()
	*/

	BookingEngine.prototype.showSchedules = function () {
		var date = this.pickedDate,
		datetemp = date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear(),
		datenum = date.getDate()+(date.getMonth()+1)*100+date.getFullYear()*10000;

		this.dayOfWeek = date.getDay();
		this.date = date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear();
		$("#datepicker").hide();

		if (window.today > datenum) {
			this.message(1,"You picked day before today!");
			setTimeout(function () {
				window.booking.message(2,"");
			},1000);
			return false;
		}

		/* DATEPICKER EDIT DAY OF WEEK;  AMERICAN SYSTEM ??*/
		if (this.dayOfWeek == 0) {
			this.dayOfWeek = 6; 
		} else {
			this.dayOfWeek--;
		}
		/* DATEPICKER EDIT DAY OF WEEK;  AMERICAN SYSTEM ??*/
		
		this.showSchedule();
	
	}	

	/* 
		shows full target box size message
	*/
	BookingEngine.prototype.message = function (type,text) {
		if (type === 1) {
			this.datepicker.hide();
			$("<div />",{class:"booking_message",text : text}).appendTo(this.target);
		} else if (type === 2) {
			this.datepicker.show();
			$(".booking_message").remove();
		}
	}
	
	
	/* 
	Works only with fixed time and creates div with time, if booked marks it as booked
	*/
	BookingEngine.prototype.createFTime = function (data,pointer,roomNumber,style) {
		var time = $("<div />",{class: "Fbooking"}),
		start = this.formatTime(data[0]),
		end = this.formatTime(data[1]),
		price = parseInt(data[2],10),
		booked = this.checkBooking(data[0],data[1],roomNumber),
		bookingClass = "",
		html = "",
		yStart = ((data[0] / 60) - this.firsthour) * 55 + 56,
		height = ((data[1] - data[0]) / 60) * 55 - 6,

		bookingPointer = this.bookings[roomNumber][booked];

		if (style[0] === 1) {
			yStart -= 2;
			height += 2;
			bookingClass = "ext1 ";
		}

		if (style[1] === 1) {
			height += 3;
			bookingClass += "ext2 ";
		}
		
		height += "px";
		yStart += "px";



		if (this.owner && booked > -1) {
			bookingClass += "booked owner";
			html = "<a>Band: "+bookingPointer.band+"<a/><br/><a>Show more</a>";
		} else if (this.owner && booked == -1) {
			bookingClass += "free";
			html = "Price: &nbsp;&nbsp;$"+price+"";
		} else if (!this.owner && booked == -1) {
			bookingClass += "free";
			html = "Price: &nbsp;&nbsp;$"+price+"";
		} else if (!this.owner && booked > -1) {
			bookingClass += "booked";
			html = "Booked";
		}

		time.addClass(bookingClass);
		$("<div />",{class: "timesch", html:start + " - " + end}).appendTo(time);
		$("<div />",{class: "price", html:html}).appendTo(time);		

		time.appendTo(pointer); 
		time.css("top",yStart).css("height",height);
	}

	/* 
	Works only with non-fixed time and creates div with BOOKED time
	*/
	BookingEngine.prototype.createNFTime = function (data,pointer,roomNumber,style) {
		var time = $("<div />",{class: "NFbooking"}),
		start = this.formatTime(data.start),
		end = this.formatTime(data.end),
		price = parseInt(data.price,10),
		booked = this.checkBooking(data.start,data.end,roomNumber),
		bookingClass = "",
		html = "",
		yStart = ((data.start / 60) - this.firsthour) * 55 + 56,
		height = ((data.end - data.start) / 60) * 55 - 6;

		if (style[0] === 1) {
			yStart -= 1;
			height += 1;
			bookingClass = "ext1 ";
		}

		if (style[1] === 1) {
			height += 5;
			bookingClass += "ext2 ";
		}

	
		if (this.owner && booked > -1) {
			bookingClass += "booked owner";
			html = "<a>Show more</a>";
		} else if (!this.owner && booked > -1) {
			bookingClass += "booked";
			html = "Booked";
		}
		
		height += "px";
		yStart += "px";

		time.addClass(bookingClass);
		$("<div />",{class: "timesch", html:start + " - " + end}).appendTo(time);
		$("<div />",{class: "price", html:html}).appendTo(time);	

		time.appendTo(pointer); 
		time.css("top",yStart).css("height",height);
	}

	/* 
	Works with both fixed and non-fixed booking, simply creates room div with header
	*/
	BookingEngine.prototype.createRoom = function (roomNumber,pointer) {
		roomPointer = $("<div />",{class: "NFroom"}).appendTo(pointer);
		
		$("<div />",{class: "roomHeader", text: this.roomNames[roomNumber]}).appendTo(roomPointer);
		if (this.nf === "0") {
			if( this.schedules[roomNumber] === undefined ) {
				this.schedules[roomNumber] = {};
				this.schedules[roomNumber][this.dayOfWeek] = [];
			}
			

			this.appendFBookings(roomNumber,roomPointer);
		} else {
			
			this.appendNFBookings(roomNumber,roomPointer);
		}

	}

	
	/* 
	Appends 'times' to room
	Works only with fixed
	*/
	BookingEngine.prototype.appendFBookings = function (roomNumber,pointer) {
		var  style = [0,0];
		
		
		for (var i = 0, limit = this.schedules[roomNumber][this.dayOfWeek].length; i < limit; i++) {
			style = [0,0];

			if (this.schedules[roomNumber][this.dayOfWeek][i-1] !== undefined) {
				 if (this.schedules[roomNumber][this.dayOfWeek][i-1][1] === this.schedules[roomNumber][this.dayOfWeek][i][0]) {
				 	style[0] = 1;
				 }
			}

			if (this.schedules[roomNumber][this.dayOfWeek][i+1] !== undefined) {
				 if (this.schedules[roomNumber][this.dayOfWeek][i+1][0] === this.schedules[roomNumber][this.dayOfWeek][i][1]) {
				 	style[1] = 1;
				 }
			}
	
			this.createFTime(this.schedules[roomNumber][this.dayOfWeek][i],pointer,roomNumber,style);
		}
	}

	/* 
	Appends 'times' to room
	Works only with non-fixed
	*/
	BookingEngine.prototype.appendNFBookings = function (roomNumber,pointer) {
		var  style = [0,0],
		data = null;
		if (this.bookings[roomNumber] === undefined) {
			this.bookings[roomNumber] = [];
		}
		if (this.bookingPointers[roomNumber] === undefined) {
			this.bookingPointers[roomNumber] = [];
		}

		for (var i = 0, limit = this.bookings[roomNumber].length; i < limit; i++) {
			if (this.bookings[roomNumber][i].date === this.bookingData.date) {
				data = this.bookings[roomNumber][i];
				this.createNFTime(data,pointer,roomNumber,style);
				this.bookingPointers[roomNumber].push(data);
			}
		}
	}


	BookingEngine.prototype.appendScheduleHeader = function () {
		var header = "<div id=\"prevday\"></div><div id=\"nextday\"></div>Schedule on "+this.dayNamesRus[this.dayOfWeek]+", "+this.bookingData.date;
		
		$("<div />",{id: "sch_header", html: header}).appendTo(this.schedule);
		$("<div/>", {"class": "exbutton", "id": "exsch"}).prependTo("#sch_header").show();
	}

	/*
	just builds and shows schedule
	there's a bit complicated DOM inside with all this margins and lefts but i guess it was the only way to do it
	*/
	BookingEngine.prototype.showSchedule = function () {
		this.schedule.children().remove();
		this.schedule.fadeIn(200);
		this.appendScheduleHeader();
	
		var scale = $("<div />",{id: "sch_scale"}).appendTo(this.schedule),
		container = $("<div />",{id: "nonfixedcontainer"}).appendTo(this.schedule),
		background = $("<div />",{id: "sch_schedule"}).appendTo(container),
		bookingsWrapper = $("<div />",{id: "bookings_wrapper"}).appendTo(container),
		bookings = $("<div />",{id: "bookings_container"}).appendTo(bookingsWrapper),
		roomPoiner = null;

		if (this.rooms > 4) {
			roomWidth = 156;
		} else {
			roomWidth = 624 / this.rooms + 1;
		}
		
		

		var wrapperMargin = (this.rooms * roomWidth - 625 ) * -1,
		containerWidth = this.rooms * roomWidth + 1,
		wrapperWidth = containerWidth - wrapperMargin,
		bookingsLeft =  wrapperMargin * -1;

		wrapperMargin += "px";
		containerWidth += "px";
		wrapperWidth += "px";
		bookingsLeft += "px";
		
		if (this.rooms > 4) {
			$("<div />",{id: "nextArrow",text:"Next rooms"}).prependTo(container);
			$("<div />",{id: "prevArrow",text:"Prev rooms"}).prependTo(container);
		} 
		
		$("<span />",{class: "scale_hour"}).appendTo(scale);
		$("<span />",{class: "sch_hour"}).appendTo(background);
		$("<span />",{class: "sch_half"}).appendTo(background);
		
		if (this.nf === "1") {
			this.bookingPointers = [];
		} else {
			this.updateFirstHour();
		}

		for (var i = this.firsthour; i < this.lasthour; i++) {
			$("<span />",{class: "scale_hour",text : i+":00"}).appendTo(scale);
			$("<span />",{class: "sch_hour"}).appendTo(background);
			$("<span />",{class: "sch_half"}).appendTo(background);
			$("<span />",{class: "sch_half"}).appendTo(background);
		}

		
		for (var j = 1; j <= this.rooms; j++) {
			this.createRoom(j,bookings);
		}

		bookingsWrapper.css("width",wrapperWidth).css("margin-left",wrapperMargin);
		if (this.rooms > 4) {
			bookings.draggable({axis:"x",containment: "#bookings_wrapper", scroll: false, handle: "div.roomHeader"});
		}
		
		bookings.css("width",containerWidth).css("left",bookingsLeft);
		$(".NFroom").css("height",((this.lasthour - this.firsthour) * 55 + 55)+"px");
		if (this.rooms < 4)  {
			$(".NFroom,.roomHeader").css("width",(roomWidth - 1)+"px");
		}
		this.bookingsLeft = parseInt(bookingsLeft,10);
	}


	BookingEngine.prototype.updateFirstHour = function () {
		this.firsthour = 8;
		for (var i = 1; i <= this.rooms; i++) {
			if (this.schedules[i][this.dayOfWeek][0] !== undefined) {
				if (this.schedules[i][this.dayOfWeek][0][0] < 8 * 60 ) {
					this.firsthour = parseInt(this.schedules[i][this.dayOfWeek][0][0] / 60);
				}
			}
			
 		}
	}

	/*	GET RID OF THIS SHIT IN NEXT RELEASE	
	rewrites schedules in better form
	*/
	BookingEngine.prototype.prepareSchedules = function () {
		var x = "",
		newSchedules = [],
		room = 0,
		length = 0; 

		for (var i = 0, limit = this.schedules.length; i < limit; i++ ) {
			room = this.schedules[i]['room'];
			for (x in this.schedules[i]) {
				if (x !== "room") {
					this.schedules[i][x] =  this.schedules[i][x].split(";");
					for (var j = 0, limit2 = this.schedules[i][x].length; j < limit2; j++ ) {
						if (this.schedules[i][x][j] !== "") {
							this.schedules[i][x][j] = this.schedules[i][x][j].split(",");
						} else {
							length = this.schedules[i][x].length;
							delete this.schedules[i][x][j];
							this.schedules[i][x].length = length - 1;
						}
					}
				}
			}
			delete this.schedules[i]['room'];
			newSchedules[room] =  this.schedules[i];
		}
		this.schedules = newSchedules;
	}

	/*	
	rewrites bookings in better form
	*/
	BookingEngine.prototype.prepareBookings = function () {
		var x = "",
		newBookings = [],
		length = 0; // wtf why should i do that?

		for (var i = 0, limit = this.bookings.length; i < limit; i++ ) {
			room = parseInt(this.bookings[i]['room'],10);
			if (newBookings[room] === undefined) {
				newBookings[room] = [];
			} 
			newBookings[room].push(this.bookings[i]);

		}
		this.bookings = newBookings;
	}

	BookingEngine.prototype.book = function (data) {
		var condition = true,
		basebooking = this;

		if (!this.owner && window.user === undefined) {
			this.bookingData.name = data['first_name'];
			this.bookingData.vkid = data['uid'];
			this.bookingData.lastname = data['last_name'];
			this.bookingData.hash = data['hash'];
			this.bookingData.phone = $('input[name*="basebooking_phone"]').val();
		}

		if (this.owner) {
			this.bookingData.phone = $('input[name*="basebooking_phone"]').val();
			this.bookingData.band = $('input[name*="basebooking_band"]').val();
			this.bookingData.name = $('input[name*="basebooking_name"]').val();
		}

		if (window.user !== undefined) {
			this.bookingData.phone = $('input[name*="basebooking_phone"]').val();
			this.bookingData.band = $('input[name*="basebooking_band"]').val();
			this.bookingData.name = window.user.info.name;
			this.bookingData.lastname = window.user.info.lastname;
			this.bookingData.vkid = window.user.info.vkid;
		}


		if (this.bookingData.phone == "") {
			condition = false;
			$('input[name*="basebooking_phone"]').css("border", "1px solid #f44").css("background-color", "#fff3f3");
			condition = false;
		} else {
			$('input[name*="basebooking_phone"]').css("border", "1px solid #d1d1d1").css("background-color", "#fff");
		}
		if (this.bookingData.band == "") {
			condition = false;
			$('input[name*="basebooking_band"]').css("border", "1px solid #f44").css("background-color", "#fff3f3");
			condition = false;
		} else {
			$('input[name*="basebooking_band"]').css("border", "1px solid #d1d1d1").css("background-color", "#fff");
		}

		if (this.bookingData.name == "") {
			condition = false;
			$('input[name*="basebooking_name"]').css("border", "1px solid #f44").css("background-color", "#fff3f3");
			condition = false;
		} else {
			$('input[name*="basebooking_name"]').css("border", "1px solid #d1d1d1").css("background-color", "#fff");
		}

		if (!condition) {
			return false;
		}
		
		$("#bookingButton").html("Wait...");
		if (condition) {

					
				$.ajax({
					type: "POST",
					url: "http://www.basebooking.ru/userbook.php",
					data: { data: basebooking.bookingData },
					dataType: 'json',
					success: function(data) {
						$("#dial").children().remove();
						$("<div />",{id: "exdial", class: "exbutton"}).appendTo(dial).show();
						$("<div/>",{class : "ready",html: data[1]+"<br/>"}).appendTo("#dial");
						if (data[0] === 10 || data[0] === 11) {
							var insert = jQuery.extend(true, {}, basebooking.bookingData);
							basebooking.bookings[basebooking.bookingData.room].push(insert);

							setTimeout(function () {
								basebooking.showSchedules();
								$(".ready").remove();
							},1500);
							
						}
					}
				});
		}
	}

	BookingEngine.prototype.deleteBooking = function () {
		var basebooking = this;
		$(".bookingButton").html("Wait...");
		$.ajax({
			type: "POST",
			url: "http://www.basebooking.ru/admin/ajaxNew.php",
			data: { data: basebooking.bookingData },
			dataType: 'json',
			success: function(data) {
	
				$("#dial").children().remove();
				if (data[1] === "debt" && basebooking.bookingData.vkid !== 0) {
					data[1] = "Debt $"+basebooking.bookingData.price+ "added to musician.";

				} else if (data[1] === "debt" && basebooking.bookingData.vkid === 0 ) {
					data[1] = "Successfully booked";
				}
				
				$("<div />",{id: "exdial", class: "exbutton"}).appendTo(dial).show();
				$("<div/>",{class : "ready",html: data[1]+"<br/>"}).appendTo("#dial");
				if (data[0] == true) {
					basebooking.bookings[basebooking.bookingData.room].splice(basebooking.bookingData.bookingIndex,1);
				}
				setTimeout(function () {
					basebooking.showSchedules();
					$(".ready").remove();
				},1500);
			}
		});
	}


	window.BookingEngine = BookingEngine;
}(window));





