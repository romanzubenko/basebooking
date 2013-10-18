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

function changeConfirmation(confirmation,inhtml,type) {
	var message  = $(confirmation).children()[0],
	prevType = $(confirmation).children()[1];

	$(message).html(inhtml);
	$(prevType).html(type);
}

function getPrice(parent) {
	var ind = $(parent).find(".ind").html();
	var arr = ind.split(",");
	var price = arr[5];


	return price;
}

function overlay(x,message) {
	/*
	x == 1 activate overlay
	x == 2 disable overlay
	*/
	
	if (x === 1 || x === 3) {
		var overlay = $("<div />", {class:"overlay"}),
		height =  getDocHeight(),
		top = $(window).scrollTop(),
	    left = $(window).scrollLeft(),
	    message = $("<div />", {class:"overlayMessage",text : message});
	    message.css("margin-top",(parseInt(top)+20)+ "px");
	    
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
		message.appendTo("#main");
	} else if (x === 2) {
		$("#yes").die("click");
		$("#no").die("click");

		$(".overlay, .overlayMessage").remove();
		$('body').css('overflow', 'auto');
      	$(window).unbind('scroll');
	}
	
}

function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
}

function initial(button, inhtml) {
  var parent = $(button).parent().parent().parent(), // booking_{odd/even}
  confirmation = $(parent).children()[1],
  main = $(parent).children()[2];

  $(confirmation).html(inhtml);
  $(confirmation).animate({"margin-top": "0px"},100);
  $(main).animate({"margin-top": "41px"},100);
}

$(".delete").live("click", function(event){
	console.log("delete");
	var a =  "<div>Отменить бронирование? <span class=\"yes9\">Да</span> &nbsp&nbsp<span class=\"cancel\">Нет</span></div><div class=\"qtype\">1</div> ";
	initial(this, a);
})

$(".cancel").live("click", function(event){
  var parent = $(this).parent().parent().parent(),
  main_booking = $(parent).children()[2],
  confirmation = $(parent).children()[1];
  $(confirmation).animate({"margin-top": "-41px"},100);
  $(main_booking).animate({"margin-top": "0px"},100);
  
})

$(".yes9").live("click", function(event){
  var ind = $(this).parent().parent().parent().children()[0].innerHTML,
  parent = $(this).parent().parent().parent(),
  confirmation = $(this).parent().parent().parent().children()[1],
  type = $(confirmation).children()[1].innerHTML;

   MakeURequest(ind,parent,confirmation,type);
})

/* type
  0 - cancel
  1 - delete
  2 - delete with debt acceptance
*/

function MakeURequest(ind, parent,confirmation,type) {
	var xmlHttp = getXMLHttp(),
	a = $(confirmation).children()[0],
	data = ind.split(",");

	

	xmlHttp.onreadystatechange = function() {
		if(xmlHttp.readyState == 4) {
			HandleUResponse(xmlHttp.responseText, parent, type,confirmation);
		}
	}

	
	$(a).html("Пожалуйста подождите...");	
	xmlHttp.open("POST", "http://www.basebooking.ru/musician/ajax.php?r=" + Math.random(), true);
	xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("date="+data[0]+"&start="+data[1]+"&end="+data[2]+"&room="+data[3]+"&id="+data[4]+"&type="+type);
	
}



function HandleUResponse(response,parent,type,confirmation) {
	var inhtml,a,
	price = getPrice(parent);
	console.log(response);
	
	response = response.split(".");

	confirmation = $(parent).children()[1],
  	main = $(parent).children()[2];

  	// delete
  	if (type == 1) {
  		if (response[0] == 1 && response[1] == 0) {
  			console.log("lol");
  			inhtml = "Бронирование успешно отменено.";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  			
  		} else if (response[0] == 0 && response[1] == 1) {
  			inhtml = "Если вы отмените бронирование сейчас то вам в долг добавится "+price+" руб. Вы уверены, что хотите продолжить? <span class=\"yes9\">Да</span> <span class=\"cancel\">Нет</span> ";
  			type = 2;
  			changeConfirmation(confirmation,inhtml,type);
  		}else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/musician\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
		}	
	}
	
	//delete with debt acceptance
	else if (type == 2) {
		if (response[0] == 1 && response[1] == 1) {
			inhtml = "Бронирование успешно отменено. "+price+" руб записано вам в долг.";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
  		} else {
  			inhtml = "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/musician\">перезагрузить страницу</a> ";
  			type = -1;
  			changeConfirmation(confirmation,inhtml,type);
		}	
	}

}







