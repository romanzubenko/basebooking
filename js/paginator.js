;(function(window, undefined) {
	var paginator,cell;

	//~~~~~~~~~~~~~~~~~~~~~~~~~
	// The Paginator constructor
	function Paginator(pages, target, contentTarget, way) {
		this.pages = pages;
		this.currentPage = 1;
		this.target = target;
		this.scope = [];
		this.scope[0] = 1;
		this.cells = [];
		this.mode = 1;
		this.content = contentTarget;
		this.way = way;


		if (pages > 5) {
			this.mode = 2;
		}

		this.pagination = $("<div />",{class: "pagination"});
		this.ul = $("<ul />").appendTo(this.pagination);
		this.pagesArray = [];

		this.pagination.appendTo(target);
		this.createCells();

		paginator = this;
		$(".pagination li").live("click",paginator,function(){
			var innerContent = $(this).children()[0].innerHTML;

			if (innerContent === "«") {
				paginator.shift(-1);
			} else if (innerContent === "»") {
				paginator.shift(1);
			} else if (innerContent !== "...") {
				console.log("page number = "+innerContent);
				
				$(".pagination li").removeClass("active");
				$(this).addClass("active");
				paginator.changeCurrent(innerContent);
			}
			
		});


		if (this.mode == 1) {
			this.cells[0].activate();
			this.scope[1] = this.pages;
		} else {
			this.cells[2].activate();
			this.scope[1] = 4;
		}

		window.resizeBody();
	}

	//~~~~~~~~~~~~~~~~~~~~~~~~~
	// The Cell constructor
	Cell = function(c) {  // c for class
		if (c === undefined) {
			this.li = $("<li />");
		} else {
			this.li = $("<li />",{class: c});
		}
	
		this.li.a = $("<a />").appendTo(this.li);
		this.value = 0;

		this.show = function () {
			this.li.show();
		}

		this.hide = function () {
			this.li.hide();
		}

		this.setValue = function (n) {
			this.li.children()[0].innerHTML = n;
			this.value = n;
		}

		this.appendTo = function (t) {
			this.li.appendTo(t);
		}

		this.activate = function () {
			$(this.li).addClass("active");
		}


	}

	Paginator.prototype.createCell = function (n,c) {
		var cell = new Cell(c);
		cell.setValue(n);
		return cell;
	}

	
	Paginator.prototype.createCells = function () {
		if (this.pages > 5) {
			this.cells.push(this.createCell("«","pagePrev"));
			this.cells[0].appendTo(this.ul);

			this.cells.push(this.createCell("..."));
			this.cells[1].appendTo(this.ul);
			this.cells[1].hide();

			for (var i = 1; i < 5; i++) {
				this.cells.push(this.createCell(i));
				this.cells[1 + i].appendTo(this.ul);
			}
			
			this.cells.push(this.createCell("..."));
			this.cells[6].appendTo(this.ul);

			this.cells.push(this.createCell("»","pageNext"));
			this.cells[7].appendTo(this.ul);

		} else  if (this.pages <= 5 && this.pages > 0) {
			for (var i = 1; i <= this.pages; i++) {
				this.cells.push(this.createCell(i));
				this.cells[i - 1].appendTo(this.ul);
			}
		}
	}

	Paginator.prototype.changeCurrent = function (newPage) {
		this.currentPage = newPage;
		this.getPage();
	}

	Paginator.prototype.shift = function (direction) {
		var max = 0,
		min = 0;

		if (this.mode !== 2) {
			return false;
		}

		if (direction === 1) {
			max = this.scope[1] + 3;
			if (max > this.pages) {
				max = this.pages;
			}
			this.scope[1] = max;
			this.scope[0] = max - 3;
			

			for (var i = 2; i < 6; i++) {
				this.cells[i].setValue(this.scope[0] + i - 2);
			}

			$(".pagination li").removeClass("active");
			$(this.cells[2].li).addClass("active");
			this.changeCurrent(this.cells[2].value);

		}

		if (direction === -1) {
			min = this.scope[0] - 3;
			if (min < 1) {
				min = 1;
			}
			this.scope[0] = min;
			this.scope[1] = min + 3;

			for (var i = 2; i < 6; i++) {
				this.cells[i].setValue(this.scope[0] + i - 2);
			}
			$(".pagination li").removeClass("active");
			$(this.cells[5].li).addClass("active");
			this.changeCurrent(this.cells[5].value);
		}

		if (this.scope[0] != 1 && this.scope[1] != this.pages) {
			this.cells[1].show();
			this.cells[6].show();
		} else if (this.scope[0] != 1 && this.scope[1] == this.pages) {
			this.cells[1].show();
			this.cells[6].hide();
		} else if (this.scope[0] == 1 && this.scope[1] != this.pages) {
			this.cells[1].hide();
			this.cells[6].show();
		} else if (this.scope[0] == 1 && this.scope[1] == this.pages) {
			this.cells[1].hide();
			this.cells[6].hide();
		}

		
		
	}

	Paginator.prototype.getPage = function () {
		var xmlHttp = getXMLHttp(),
		paginator = this;
		xmlHttp.open("POST", this.way+"r = " + Math.random(), true);
		xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlHttp.send("page=" + this.currentPage);


		xmlHttp.onreadystatechange = function() {
			if(xmlHttp.readyState == 4) {
				paginator.content.html(xmlHttp.responseText);
			}
		}

		$.ajax({
			type: "POST",
			url: "http://www.basebooking.ru/userbook.php",
			data: { data: bookingData },
			//dataType: 'json',
			success: function(data) {
				console.log("message = ");
				console.log(data);
				paginator.content.html(data);
			}
		});

	}

	window.Paginator = Paginator;

}(window));


