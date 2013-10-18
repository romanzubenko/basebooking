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


$(".submit").live("click", function() {
	var parent = $(this).parent().parent().parent().parent().parent(),
	ind = $(parent).children()[0].innerHTML;;
	makeRequest(parent,ind,0);
})
$(".denial").live("click", function() {
	var parent = $(this).parent().parent().parent().parent().parent(),
	ind = $(parent).children()[0].innerHTML;
	makeRequest(parent,ind,1);
})

function makeRequest(parent,ind,type) {
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText, parent,type);
    }
  }
  	 console.log("ind = "+ind);
	  parent.innerHTML = "<div class=\"waitSuc\">Пожалуйста подождите...<div />";

	  xmlHttp.open("POST", "http://www.basebooking.ru/superadmin/wait.php?r=" + Math.random(), true);
	  xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	  xmlHttp.send("ind="+ind+"&denial="+type);
}


function HandleResponse(response, parent,type) {
	console.log("response = "+response);
	parent.html("");

	response = response.split(",");
	if (response[0] == 1 && type == 0) {
		var a = $("<div />", {class: "waitSuc", text : "База была успешно добавлена!"});
		a.appendTo(parent);
	} else if (response[0] == 1 && type == 1) {
		var a = $("<div />", {class: "waitSuc", text : "Заявка успешно отклонена!"});
		a.appendTo(parent);
	} else {
		var a = $("<div />", {class: "waitSuc", text : "Что - то пошло не так!"});
		a.appendTo(parent);
	}
}
