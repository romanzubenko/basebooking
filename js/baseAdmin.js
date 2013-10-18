Base.prototype.adminFeatures = function() {
	page = this;
	/*	EVENTS 	*/
	$(".editInfo").live("click",page,function() {
		page.inputInfo();
	});
	$(".editConditions").live("click",page,function() {
		page.inputConditions();
	});

	$(".editRoom").live("click",page,function() {
		var roomPointer = $(this).parent().parent(),
		index = roomPointer.index();
		page.inputRoom(index,roomPointer);
	});

	$(".cancelRoomEdit").live("click",page,function() {
		var roomPointer = $(this).parent().parent(),
		index = roomPointer.index(),
		slideDown = $(roomPointer.children()[1]);
		slideDown.slideUp(function() {
			page.createRoom(page.equipment[index],roomPointer);
			var header = roomPointer.find('.equip_header');
			$("<div/>",{class: "edit editRoom",html : "Редактировать"}).appendTo(header);
			header.find('.innerHeader').css('width','532px');
		});
	});
	/*	EVENTS 	*/

	/*	ADD EDITS + ADMIN PANEL	*/
	$("<span/>",{class: "edit editInfo",html : "Редактировать"}).prependTo(this.infPointer);
	$("<span/>",{class: "edit editConditions",html : "Редактировать"}).prependTo(this.conditionsPointer);
	$("<div/>",{class: "edit editRoom",html : "Редактировать"}).appendTo('.equip_header');
	$('.innerHeader').css('width','532px');

	//$("<div/>",{id: "float_menu",html : "Редактировать",html:"<span>Уведомления</span><span>Расписания</span><span>Музыканты</span><span>Настройки</span><span>Аккаунт</span>"}).prependTo('body');

	$("#float_menu").on("click", "span", function(event){
		$(this).parent().children().removeClass("active");
		$(this).addClass("active");
	});

}

Base.prototype.inputInfo = function() {

	var table = $("<table />"),
	page = this;
	header = $("<div />",{class: "header1", html: "О "+this.info['name']});
	this.infPointer.html("");
	$("<span/>",{class: "edit CancelEditInfo",html : "Отмена"}).prependTo(this.infPointer);
	var header1 = $("<div />",{class: "header1", html: "Контактная информация"}).appendTo(this.infPointer);

	$("<tr />",{html: "<td>Город:</td><td><textarea id=\"town\">"+this.info['town']+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Метро:</td><td><textarea id=\"metro\">"+this.info['station']+"</textarea></td>"}).appendTo(table)
	$("<tr />",{html: "<td>Адрес:</td><td><textarea id=\"address\">"+this.info['address']+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Как проехать:</td><td><textarea id=\"how\">"+this.info['how']+"</textarea></td>"}).appendTo(table);
	//$("<tr />",{html: "<td>Тип:</td><td><input name=\"\">"+type+"</input></td>"}).appendTo(table);		
	$("<tr />",{html: "<td>Вебсайт:</td><td><textarea id=\"website\">"+this.info['website']+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Вконтакте:</td><td><textarea id=\"vk\">"+this.info['vk']+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Телефон:</td><td><textarea id=\"phone\">"+this.info['phone']+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Кол-во комнат:</td><td><textarea id=\"komn\">"+this.info['komn']+"</textarea></td>"}).appendTo(table);
	
		
	table.appendTo(this.infPointer);
	header.appendTo(this.infPointer);
	table = $("<table />");
	$("<tr />",{html: "<td><textarea class=\"inputdes\"  name=\"description\">"+this.info['description']+"</textarea></td>"}).appendTo(table);
	table.appendTo(this.infPointer);
	$("<div/>",{class: "button middle", id :"saveSettings", html:"Сохранить изменения"}).appendTo(this.infPointer);
	$("<div/>",{class: "space"}).appendTo(this.infPointer);

	$(".CancelEditInfo").on('click',function(){
		page.createInfo();
		$("<span/>",{class: "edit editInfo",html : "Редактировать"}).prependTo(page.infPointer);
		$("<span/>",{class: "edit editConditions",html : "Редактировать"}).prependTo(page.conditionsPointer);
	});
	
	$("#saveSettings").live('click',function(){
		$("#saveSettings").die("click");
		$("#saveSettings").html("Подождите...");

		var  dataSend = {};
		dataSend.descript = $("textarea.inputdes").val();
		$("textarea.inputdes").attr("disabled", "disabled");
		dataSend.address = $("textarea#address").val();
		dataSend.town = $("textarea#town").val();
		dataSend.website = $("textarea#website").val();
		dataSend.phone = $("textarea#phone").val();
		dataSend.vk = $("textarea#vk").val();
		dataSend.station = $("textarea#metro").val();
		dataSend.komn = $("textarea#komn").val();
		dataSend.how = $("textarea#how").val();
		
		console.log(page);
		page.info.description = dataSend.descript;
		page.info.address = dataSend.address;
		page.info.town = dataSend.town;
		page.info.website = dataSend.website;
		page.info.phone = dataSend.phone;
		page.info.vk = dataSend.vk;
		page.info.station = dataSend.station;
		page.info.how = dataSend.how;
		page.info.komn = parseInt(page.info.komn);
		dataSend.komn = parseInt(dataSend.komn);

		
		if (page.info.komn < dataSend.komn) {
			for (var i = page.info.komn + 1; i <= dataSend.komn; i++) {
				page.equipment.push({bass:"",drum:"",extra:"",guitar:"",id: i,line:"",name:"",price:""});
			} 
			page.createEquipment();
		} else if (page.info.komn > dataSend.komn) {
			for (var i = page.info.komn; i > dataSend.komn; i--) {

				delete page.equipment[i - 1];
				page.equipment.length = page.equipment.length - 1;
			} 
			page.createEquipment();
		}
		
		page.info.komn = dataSend.komn;

		$.ajax({
			type: "POST",
			url: "http://www.basebooking.ru/admin/updateInfo.php",
			
			data: {data : dataSend},
			dataType: 'json',
			success: function(data) {
				if (data == true) {
					page.createInfo();
					$("<span/>",{class: "edit editInfo",html : "Редактировать"}).prependTo(page.infPointer);
					$("<span/>",{class: "edit editConditions",html : "Редактировать"}).prependTo(page.conditionsPointer);
				} else {
					console.log("fail");
				}
				
			}
		});
	});
}

Base.prototype.inputConditions = function() {
	$(this.conditionsPointer.children()[0]).remove();
	$("<span/>",{class: "edit CanceleditConditions",html : "Отмена"}).prependTo(page.conditionsPointer);
	this.conditionsInnerPointer.html(""+
			"Максимальное количество репетиций доступное для бронирования пользователям, еще не репетировавших на Demo Studio - <input id=\"maxPrime\" value=\""+this.info['maxPrime']+"\"></input> <br /><br />"+
			"Максимальное количество репетиций доступное для бронирования пользователям, уже репетировавших на Demo Studio - <input id=\"max\" value=\""+this.info['max']+"\"></input> <br /><br />"+
			"Дедлайн после которого не возможна бесплатная отмена репетиции - <input id=\"deadline\" value=\""+this.info['deadline']+"\"></input> часа до начала репетиции<br /><br />"+
	"");
	$("<div/>",{class: "button middle", id :"saveConditions", html:"Сохранить изменения"}).appendTo(this.conditionsInnerPointer);
	$("<div/>",{class: "space"}).appendTo(this.conditionsInnerPointer);

	$(".CanceleditConditions").on('click',function() {
		page.createInfo();
		$("<span/>",{class: "edit editInfo",html : "Редактировать"}).prependTo(page.infPointer);
		$("<span/>",{class: "edit editConditions",html : "Редактировать"}).prependTo(page.conditionsPointer);
	});

	$("#saveConditions").on('click',function() {
		$("#saveConditions").die("click");
		$("#saveConditions").html("Подождите...");
		var dataSend = {
			maxPrime : $("input#maxPrime").val(),
			max : $("input#max").val(),
			deadline : $("input#deadline").val()
		};

		page.info.maxPrime = dataSend.maxPrime;
		page.info.max = dataSend.max;
		page.info.deadline = dataSend.deadline;

		$.ajax({
			type: "POST",
			url: "http://www.basebooking.ru/admin/updateConditions.php",
			data: {data : dataSend},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (data == true) {
					page.createInfo();
					$("<span/>",{class: "edit editInfo",html : "Редактировать"}).prependTo(page.infPointer);
					$("<span/>",{class: "edit editConditions",html : "Редактировать"}).prependTo(page.conditionsPointer);
				} else {
					console.log("fail");
					overlay(1,"fail");
				}
				
			}
		});

	});
}

Base.prototype.inputRoom = function(index,roomPointer) {
	var table = $("<table/>"),
	slideDown = $(roomPointer.children()[1]);
	slideDown.children().remove();
	roomPointer.find('.enlarge').html('Свернуть');
	roomPointer.find('.editRoom').html('Отмена').addClass('cancelRoomEdit').removeClass('editRoom');


	$("<tr />",{html: "<td>Название Комнаты:</td><td><textarea class=\"room_name\">"+this.equipment[index].name+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Цена:</td><td><textarea class=\"room_price\">"+this.equipment[index].price+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Гитара:</td><td><textarea class=\"room_guitar\">"+this.equipment[index].guitar+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Бас гитара:</td><td><textarea class=\"room_bass\">"+this.equipment[index].bass+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Вокальная линия:</td><td><textarea class=\"room_line\">"+this.equipment[index].line+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Ударная установка:</td><td><textarea class=\"room_drum\">"+this.equipment[index].drum+"</textarea></td>"}).appendTo(table);
	$("<tr />",{html: "<td>Дополнительно:</td><td><textarea class=\"room_extra\">"+this.equipment[index].extra+"</textarea></td>"}).appendTo(table);
	table.appendTo(slideDown);
	$("<div/>",{class: "button middle saveEquipment", html:"Сохранить изменения"}).appendTo(slideDown);
	$("<div/>",{class: "space"}).appendTo(slideDown);
	slideDown.slideDown();

	$(".saveEquipment").on("click",function() {
		$(this).off("click");
		$(this).html("Подождите...");
		
		var rawData = roomPointer.find('textarea'),
		dataSend = {
			id:index + 1 
		},
		propertyName = "";
		for (var i = 0,limit = rawData.length;i < limit;i++) {
			propertyName = $(rawData[i]).attr('class').substr(5);
			dataSend[propertyName] = $(rawData[i]).val();
		}
		

		$.ajax({
			type: "POST",
			url: "http://www.basebooking.ru/admin/updateEquipment.php",
			data: {data : dataSend},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (data == true) {
					index = roomPointer.index(),
					slideDown = $(roomPointer.children()[1]);
					page.equipment[index] = dataSend;
					slideDown.slideUp(function() {
						page.createRoom(page.equipment[index],roomPointer);
						var header = roomPointer.find('.equip_header');
						$("<div/>",{class: "edit editRoom",html : "Редактировать"}).appendTo(header);
						header.find('.innerHeader').css('width','532px');
					});
				} else {
					console.log("fail");
					
				}
				
			}
		});

	});



}


