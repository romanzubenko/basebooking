;(function(window, undefined) {
	var Base,Room;

	//~~~~~~~~~~~~~~~~~~~~~~~~~
	// The Base constructor
	function Base(base) {
		this.bookings = base.bookings;
		this.schedules = base.schedules;
		this.equipment = base.equipment;
		this.roomNames = base.roomNames;
		this.photos = base.photos;
		this.owner = base.owner;
		this.info = base.info;
		this.info.active = parseInt(this.info.active);
	}


	/*	CREATE METHODS	*/
	Base.prototype.createInfo = function() {
		
		var table = $("<table />"),
		type = "Rehearsal studio",
		header = $("<div />",{class: "header1", html: "About "+this.info['name']});
		this.infPointer = $("#inf");
		this.infPointer.children().remove();
		this.conditionsPointer = $("#cond");
		this.conditionsPointer.children().remove();
		this.conditionsInnerPointer = $("<div />",{class: "innerConditions"}).appendTo(this.conditionsPointer);

		
		if (this.info.active === 0) {
			$("<div />",{class: "attention", html: "Owners of "+this.info.name+ " haven't connected to Basebooking.ru yet. Please check back later."}).appendTo(this.infPointer);
		}
		
		
		if (this.info['type'] == 2) {
			type = "Recording studio";
		} else if (this.info['type'] == 3) {
			type = "Rehearsal and recording studio";
		}

		var header1 = $("<div />",{class: "header1", html: "Contact information"}).appendTo(this.infPointer);
		if (this.info['town'] !== "") {
			$("<tr />",{html: "<td>Town:</td><td><a href=\"http://www.basebooking.ru/search.php?town="+this.info['town']+"\">"+this.info['town']+"</a></td>"}).appendTo(table);
		}
		if (this.info['station'] !== "") {
			$("<tr />",{html: "<td>Subway:</td><td><a href=\"http://www.basebooking.ru/search.php?station="+this.info['station']+"\">"+this.info['station']+"</td>"}).appendTo(table);
		}
		if (this.info['address'] !== "") {
			$("<tr />",{html: "<td>Adress:</td><td>"+this.info['address']+"</td>"}).appendTo(table);
		}
		if (this.info['how'] !== "") {
			$("<tr />",{html: "<td>Directions:</td><td>"+this.info['how']+"</td>"}).appendTo(table);
		}
		if (this.info['type'] !== "") {
			$("<tr />",{html: "<td>Type:</td><td>"+type+"</td>"}).appendTo(table);
		}
		if (this.info['website'] !== "" && this.info['website'] !== "http://") {
			$("<tr />",{html: "<td>Website:</td><td><a href=\"http://"+this.info['website']+"\">"+this.info['website']+"</a></td>"}).appendTo(table);
		}
		if (this.info['vk'] !== "" && this.info['vk'] !== "http://") {
			$("<tr />",{html: "<td>Vk.com :</td><td><a href=\"http://"+this.info['vk']+"\">"+this.info['vk']+"</a></td>"}).appendTo(table);
		}
		if (this.info['phone'] !== "") {
			$("<tr />",{html: "<td>Phone:</td><td><a href=\"tel:"+this.info['phone']+"\">"+this.info['phone']+"</a></td>"}).appendTo(table);
		}
		$("<tr />",{html: "<td></td><td><div id=\"vk_like\"></div></td>"}).appendTo(table);
		window.VK.Widgets.Like("vk_like", {
    		type: "button", 
    		height: 24, 
    		pageTitle: "Online booking on Basebooking.ru",
    		text: "I like "+type.toLowerCase()+" "+window.page.info.name,
    		pageDescription: "Здесь можно забронировать репетицию или студийное время, а также посмотреть расписания комнат, оборудование и контактную информацию "+window.page.info.name
  		});

		
		table.appendTo(this.infPointer);
		header.appendTo(this.infPointer);
		
		if (this.info['description'] !== "") {
			table = $("<table />");
			$("<tr />",{html: "<td>"+this.info['description']+"</td>"}).appendTo(table);
			table.appendTo(this.infPointer);
		} else {
			table = $("<table />");
			$("<tr />",{html: "<td>Information is not added yet.</td>"}).appendTo(table);
			table.appendTo(this.infPointer);
		}

		

		this.conditionsInnerPointer.html(""+
			"Maximum number of booking avaliable to bands who hadnt booked yet - "+this.info['maxPrime']+" <br /><br />"+
			"Maximum number of booking avaliable to bands who'd already booked  - "+this.info['max']+" <br /><br />"+
			"Deadline of cancel - "+this.info['deadline']+" hours before booking<br /><br />"+
		"");
		$("<span />",{html: "All the musicians who hadn't use basebooking before can book only one time, until they go to their first booking."}).appendTo(this.conditionsInnerPointer);
		
	}

	Base.prototype.createEquipment = function() {
		var equipmentPointer = $("#equipment"),
		roomPointer;
		equipmentPointer.children().remove();

		for (var i = 0, limit = this.equipment.length; i < limit; i++) {
			roomPointer = $("<div />",{class: "roomInfo"});
			var room = this.createRoom(this.equipment[i],roomPointer);

			room.appendTo(equipmentPointer);
		}

		$(".equip_header .innerHeader").live("click",function() {
			var slideDown = $($(this).parent().parent().children()[1]),
			enlarge = $($(this).children()[0]);
			if (slideDown.css("display") == "none") {
				slideDown.slideDown();
				enlarge.html("Свернуть");
			} else {
				slideDown.slideUp();
				enlarge.html("Развернуть");
			}

		});
	}

	Base.prototype.createPhotos = function() {
		if (this.photos.length === 0) {
			return false;
			$("#fotorama").remove();
		}
		var photoRamaPointer = $("#fotorama"),
		photo;

		for (var i = 0, limit = this.photos.length; i < limit; i++) {
			photo = null;
			photo = $("<img />",{src: "http://www.basebooking.ru/upload/"+this.photos[i]})
			photo.appendTo(photoRamaPointer);
		}

		
	}

	Room = function(data,roomPointer) {
		roomPointer.children().remove();
		var slideDown = $("<div />",{class: "slideDown"}),
		table = $("<table/>"),
		roomName = data.name;

		if (roomName == "") {
			roomName = data.id;
		}
		var header = $("<div />",{class: "equip_header", html: "<div class=\"innerHeader\">Equipment of room "+roomName+" <div class=\"enlarge\">Show more</div></div>"}).appendTo(roomPointer);
		slideDown.appendTo(roomPointer);
		if (data.price !== "") {
			$("<tr />",{html: "<td>Price:</td><td>"+data.price+"</td>"}).appendTo(table);
		}
		if (data.guitar !== "") {
			$("<tr />",{html: "<td>Guitar:</td><td>"+data.guitar+"</td>"}).appendTo(table);
		}
		if (data.bass !== "") {
			$("<tr />",{html: "<td>Bass:</td><td>"+data.bass+"</td>"}).appendTo(table);
		}
		if (data.line !== "") {
			$("<tr />",{html: "<td>Vocal:</td><td>"+data.line+"</td>"}).appendTo(table);
		}
		if (data.drum !== "") {
			$("<tr />",{html: "<td>Drums:</td><td>"+data.drum+"</td>"}).appendTo(table);
		}
		if (data.extra !== "") {
			$("<tr />",{html: "<td>Extra:</td><td>"+data.extra+"</td>"}).appendTo(table);
		}
		

		if (table.children().length == 0) {
			$("<span />",{html: "Equipment is not added yet."}).appendTo(slideDown);
		} else {
			table.appendTo(slideDown);
		}	


		return roomPointer;
	}

	Base.prototype.createNavigation = function () {
		var cal = $("<div />",{id: "cal",text : "Book"}).appendTo(".ribbon"),
		gall = $("<div />",{id: "gall",text : "Gallery"}).appendTo(".ribbon"),	
		cond = $("<div />",{id: "conditions",text : "Booking conditions"}).appendTo(".ribbon"),
		clock = $("<div />",{class: "clock",text : ""}).appendTo(".ribbon");
		
		cal.on("click",function(){

			$("#cond").hide();
		 	$("#fotorama").hide();
		 	$("#exbutton").remove();
		 	$("#schedule").remove();
			$("#vk_auth").remove();
			$("#dial").remove();
				
			$("#booking_box").show();
		});
		gall.on("click",function(){
			$("#cond").hide();
		 	$("#booking_box").hide();
		 	$("#exbutton").remove();
		 	$("#schedule").remove();
			$("#vk_auth").remove();
			$("#dial").remove();

			$("#fotorama").show();
		});
		cond.on("click",function(){
		 	$("#booking_box").hide();
		 	$("#fotorama").hide();
		 	$("#exbutton").remove();
		 	$("#schedule").remove();
			$("#vk_auth").remove();
			$("#dial").remove();
			
			$("#cond").show();
		});
	}
 
	Base.prototype.createRoom = Room;
	window.Base = Base;

}(window));


