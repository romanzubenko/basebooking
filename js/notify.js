
checkNotifications = function () {
	$.ajax({
		type: "POST",
		url: "http://www.basebooking.ru/notify.php",
	  
		success: function(data) {
			a = $("#top a")[0];
			if (data === undefined) {
				data = []; 
			}
		    if (data[0] == 1 && data[1] != 0) {
				a.href = "http://www.basebooking.ru/admin/index.php?notifications=on";
				a.innerHTML = "Мой кабинет <span style=\"color:#D11919\">("+data[1]+")</span>";
			} else if (data[0] == 2 && data[1] != 0) {
				a.href = "http://www.basebooking.ru/musician/index.php?notifications=on";
				a.innerHTML = "Мой кабинет <span style=\"color:#D11919\">("+data[1]+")</span>";
			}
		
  		}
	});

}
	

if ($("#enter").length === 0 ) {
	checkNotifications();
	setInterval(function () {
		checkNotifications();	
	},30000);
}




