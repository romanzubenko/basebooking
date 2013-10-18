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
$(".submit").live("click", function(event){
	var parent = $("#col2");
	makeRequest(parent);
})


function makeRequest(parent) {
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText, parent);
    }
  }
  var name  = $('input[name*="name"]').val()
  var vk    = $('input[name*="vk"]').val()
  var phone = $('input[name*="phone"]').val()
  var email = $('input[name*="email"]').val()
  var website = $('input[name*="website"]').val()
  var type  = $('select[name*="type"]').val()
  
  r = true;
  
  if (name.length < 3) {
	  $('input[name*="name"]').css("border","1px solid #DE1D26");
	  r = false;
  } else {
	  $('input[name*="name"]').css("border","1px solid #000");
  }
  if (vk.length < 5) {
	  $('input[name*="vk"]').css("border","1px solid #DE1D26");
	  r = false;
  } else {
	  $('input[name*="vk"]').css("border","1px solid #000");
  }
  if (phone.length < 7) {
	  $('input[name*="phone"]').css("border","1px solid #DE1D26");
	  r = false;
  } else {
	  $('input[name*="phone"]').css("border","1px solid #000");
  }
  if (email.length < 5) {
	  $('input[name*="email"]').css("border","1px solid #DE1D26");
	  r = false;
  } else {
	  $('input[name*="email"]').css("border","1px solid #000");
  }
  if (website.length < 4) {
	  $('input[name*="website"]').css("border","1px solid #DE1D26");
	  r = false;
  } else {
	  $('input[name*="website"]').css("border","1px solid #000");
  }
  
  if (r) {
	 
	  parent.children()[0].innerHTML= "Пожалуйста подождите...";
	  xmlHttp.open("POST", "http://www.basebooking.ru/partners/query.php?r=" + Math.random(), true);
	  xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	  xmlHttp.send("name="+name+"&vk="+vk+"&phone="+phone+"&email="+email+"&website="+website+"&type="+type);
  }
}


function HandleResponse(response, parent) {
	console.log(response);
	var a = $("#col2"),
	b = $("#col1")
	responseArr = response.split(",");
	$(b).css("display","none")
	$(a,b).animate({"opacity": "0"},200);

	$(a).animate({"width":"0px"},300, function (){
		$(a).remove();
		$(b).css("width","100%").css("text-align","center").css("margin-left","0px").css("margin-top","36px").css("display","block").css("opacity", "0");
		
		$("#register").animate({"height": "100",},300);

		$(b).animate({"opacity": "1",},200);
	});

	
	

	if (responseArr[0] == 1) {
  		$("#col1").html("Ваша заявка была успешно добавлена. Если заявка будет одобрена, приглашение придет на ваш email.");
  		$("#col1").css("color","#3B9C4A");
  		return 0;
	} else {
		$("#col1").css("color","#BD3A3A");
	}

	/*ERROR  MESSAGES*/
	if (responseArr[1] == 1) {
		$("#col1").html("Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/partners\">перезагрузить страницу.</a>");
	} else if (responseArr[1] == 2) {
		$("#col1").html("База с таким именем уже зарегистрирована. <a href=\"http://www.basebooking.ru/\">Связаться с нами</a>.");
	} else if (responseArr[1] == 3) {
		$("#col1").html("База с таким именем уже есть в списке ожидания на одобрение. <a href=\"http://www.basebooking.ru/\">Связаться с нами.</a> ");
	} else if (responseArr[1] == 4) {
		$("#col1").html("Вы авторизованы на сайте как музыкант или владелец базы. Для продолжения небходимо <a href=\"http://www.basebooking.ru/exit.php\">выйти.</a>");
	} else if (responseArr[1] == 5) {
		$("#col1").html("Не все обязательный поля заполнены. <a href=\"http://www.basebooking.ru/partners/\">Попробуйте снова.</a>");
	}
}
