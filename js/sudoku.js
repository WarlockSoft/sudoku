start_timer();
var elems = document.sudoku.getElementsByTagName('input');
for (var i = 0; i < elems.length; i++) {
    if (elems[i].value>0){
		var quad = elems[i].id.substr(0,1);
		window["q"+quad].push(elems[i].value)
		var hor = elems[i].id.substr(1,1);		
		window["h"+hor].push(elems[i].value)
		var vert = elems[i].id.substr(2,1);
		window["v"+vert].push(elems[i].value)		
    }
  }
  
$("input").keyup(function(e){
	var elems = document.sudoku.getElementsByTagName('input');
	if (this.value > 0 && this.value < 10){
		quad = this.id.substr(0,1);
			if (window["q"+quad].indexOf(this.value) == -1){
				hor = this.id.substr(1,1);
				if (window["h"+hor].indexOf(this.value) == -1){	
					vert = this.id.substr(2,1);
					if (window["v"+vert].indexOf(this.value) == -1){										
						window["q"+quad].push(this.value);
						window["h"+hor].push(this.value);
						window["v"+vert].push(this.value);

						if (window["q"+quad].length == 9){
							for (var i = 0; i < elems.length; i++) {
								if (elems[i].value>0){
									var quad2 = elems[i].id.substr(0,1);
									if (quad == quad2) {
										$("#" + elems[i].id).css("backgroundColor", "#6fc07d");
									}
								}
							}
						}
						if (window["h"+hor].length == 9){	
							for (var i = 0; i < elems.length; i++) {
								if (elems[i].value>0){
									var quad2 = elems[i].id.substr(0,1);
									var hor2 = elems[i].id.substr(1,1);								
									if (hor == hor2) {
											
										$("#" + elems[i].id).css("backgroundColor", "#6fc07d");
									}
								}
							}
						}	
						if (window["v"+vert].length == 9){	
							for (var i = 0; i < elems.length; i++) {
								if (elems[i].value>0){
									var quad2 = elems[i].id.substr(0,1);
									var vert2 = elems[i].id.substr(2,1);								
									if (vert == vert2) {
											
										$("#" + elems[i].id).css("backgroundColor", "#6fc07d");
									}
								}
							}
						}
						if (q1.length == 9 && q2.length == 9 && q3.length == 9 && q4.length == 9 && q5.length == 9 && q6.length == 9 && q7.length == 9 && q8.length == 9 && q9.length == 9){
							if (uid == "")
								results = prompt("Победа! Ваше время составило: " + secs + " секунд! Введите Ваше имя для попадания в рейтинг!", "Anonymous");							
							else
								results = "";
							var datas='sudokuID='+JSON.stringify(gameId)+'&besttime='+JSON.stringify(secs)+'&name='+JSON.stringify(results)+'&uid='+JSON.stringify(uid);
							


							
							$.ajax({
								  type: 'POST',
								  url: 'https://www.freegamesplay.ru/sudoku_wins.php',
								  data: datas,
								  success: function(res) {
								  }
								});
							
							alert("Ваш результат сохранен!");
							
							
						}
					}
				}
			}
	}
	else
	{
		quad = this.id.substr(0,1);
		window["q"+quad] = new Array();
		hor = this.id.substr(1,1);
		window["h"+hor] = new Array();
		vert = this.id.substr(2,1);
		window["v"+vert] = new Array();
		for (var i = 0; i < elems.length; i++) {
			if (elems[i].value>0){
				var quad2 = elems[i].id.substr(0,1);
				if (quad == quad2) {
					window["q"+quad].push(elems[i].value)
				}
				var hor2 = elems[i].id.substr(1,1);
				if (hor == hor2){
					window["h"+hor].push(elems[i].value)
				}
				vert2 = elems[i].id.substr(2,1);
				if (vert == vert2){
					window["v"+vert].push(elems[i].value)
				}

				
				
			}
		}

		if (window["q"+quad].length == 8){

			for (var i = 0; i < elems.length; i++) {

				var quad2 = elems[i].id.substr(0,1);
				if (quad == quad2) {
					var hor2 = elems[i].id.substr(1,1);
					var vert2 = elems[i].id.substr(2,1);						
					if (window["h"+hor2].length < 9 && window["v"+vert2].length < 9){
						if($("#" + elems[i].id).is(':disabled'))
							$("#" + elems[i].id).css("backgroundColor", "#8bf79c");
						else{
							$("#" + elems[i].id).css("backgroundColor", "#ffffff");								
						}
					}

				}
			}
		}

		if (window["h"+hor].length == 8){
			for (var i = 0; i < elems.length; i++) {

				var quad2 = elems[i].id.substr(0,1);
				var hor2 = elems[i].id.substr(1,1);					
				if (hor == hor2) {
					
					var vert2 = elems[i].id.substr(2,1);	
					if (window["q"+quad2].length < 9 && window["v"+vert2].length < 9){
						if($("#" + elems[i].id).is(':disabled'))
							$("#" + elems[i].id).css("backgroundColor", "#8bf79c");
						else{
							$("#" + elems[i].id).css("backgroundColor", "#ffffff");								
						}
					}

				}
			}
		}

		if (window["v"+vert].length == 8){
			for (var i = 0; i < elems.length; i++) {

				var quad2 = elems[i].id.substr(0,1);
				var vert2 = elems[i].id.substr(2,1);
				if (vert == vert2) {
					
					var hor2 = elems[i].id.substr(1,1);
	
					if (window["q"+quad2].length < 9 && window["h"+hor2].length < 9){
						if($("#" + elems[i].id).is(':disabled'))
							$("#" + elems[i].id).css("backgroundColor", "#8bf79c");
						else{
							$("#" + elems[i].id).css("backgroundColor", "#ffffff");								
						}
					}

				}
			}
		}		

				
	}

	
})
var timer;
var secs;
function start_timer()
     {
     if (timer) clearInterval(timer); 
     secs = 0;
     document.getElementById('timer').innerHTML = 'Время: '+ secs + ' сек.'; 
     var timer = setInterval(
        function () {
         secs++;
         if (secs < 60)
        	 document.getElementById('timer').innerHTML = 'Время: '+ secs + ' сек.';
         else if (secs >= 60 && secs < 3600){
        	 document.getElementById('timer').innerHTML = 'Время: '+ Math.floor(secs/60) + 'мин. ' + (secs-Math.floor(secs/60)*60) + ' сек.';
         }
         else if (secs >= 3600){
        	 document.getElementById('timer').innerHTML = 'Время: '+ Math.floor(secs/60/60) + ' ч. ' + (Math.floor(secs/60)-60) + ' мин. ' + (secs-Math.floor(secs/60)*60) + ' сек.';
         }
         },
         1000
         );
         }
