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

function MakeRequest(ind, parent) {
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText, parent);
    }
  }
  parent.children()[2].children[0].innerHTML= "Пожалуйста подождите...";
  var query = ind+",0";
  xmlHttp.open("GET", "http://www.basebooking.ru/musician/delete_booking.php?query=" + query, true);
  xmlHttp.send(null);
}



function HandleResponse(response, parent) {
	responseArr = new Array(4);
	responseArr = response.split(",");
  if (responseArr[0] == -1) {
	  parent.children()[2].children[0].innerHTML= "Бронирование было успешно отменено.";
  } else if (responseArr[0] == 0){
	  parent.children()[2].children[0].innerHTML= "Что-то пошло не так. Попробуйте <a href=\"http://www.basebooking.ru/musician\">перезагрузить страницу</a>";
  } else if (responseArr[0] > 0){
	  parent.children()[2].children[0].innerHTML= "Вы хотите отменить бронирование после дедлайна. Цена отмены - "+responseArr[1]+" руб. Продолжить? <span class=\"yes1\">Да</span> <span class=\"cancel\">Нет</span>";
  }
}
