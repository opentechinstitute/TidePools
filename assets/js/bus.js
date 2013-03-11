var busStopList;

var destName;

var stopName;

var initial = 1;

var directionOne = "DOWNTOWN BKLYN FULTON MALL via RED HOOK";

var directionTwo = "PARK SLOPE 20 ST via RED HOOK";

var lat;

var lon;

var lastQuery = new Object();



function getBuses(direction, stopID, lon, lat){


	
	
	unhide('loadingbus');
	
	lastQuery.direction = direction;
	lastQuery.stopID = stopID;
	lastQuery.lon = lon;
	lastQuery.lat = lat;
	
	
	$.getJSON("php/bus_location.php",
		{ 
			'direction' : direction,
			'lon' : lon,
			'lat' : lat,
			'stopID' : stopID,
			'initial' : 0
		},
	
		function(json){
		
			//console.log(json);
			
			clearElements();
		
					
			$.each(json,function(i,y){
						   
			   if (y.destName !== undefined){
			   
			   	destName = y.destName;
			  	stopName = y.stopName;
			  	
			  	printBuses(y);
			  	
			   }
			   
			   else { //populating form list with nearest stops
			   
			  	var busString = '"busstops_popup"';
			   	
			   	$("#stop_container").append("<div class='stoplist' onclick='unhide("+busString+");' style='color:#2458a8; background-color:#9cafda;'</div>Choose Stop <div style='background-color:#2458a8; color:#9cafda; float:right; width:46px; height:100%; padding-right:2px; text-align:center; font-size:20px; font-family:GandhiSansBold; text-indent:3px;'>X</div>");
			   	
			   	$.each(y, function(a,c){
			   	
			   		//---- POPULATE FORM with Bus Stops------//
			   		
			   		directionString = '"'+direction+'"';	

					$("#stop_container").append("<div class='stoplist' onclick='changeStop("+directionString+","+this.stopID+");'>"+this.stopName+"</div>");
					    					
					//--------------------------------------//
					
					
					
			   	});
			   
			   }

			});
			
			$('#direction').append(destName);
			//$('#direction_box').append('<img src="img/uturn_y.png" style="float:right; padding-left:3%; padding-right:3%; padding-top:4px; width:35px; position:relative; display:inline-block;">');
			$('#busstop').append(stopName);
			$('#busstop').append('<img src="img/down.png" style="float:right; padding-top:12px; padding-left:3%; padding-right:12px;">');
			
			unhide('loadingbus');
	
						
	});

}


function printBuses(y){
	
	//---------- Colors based on time to arrival ------//
	var timeColor;
	
	if (y.stopsleft <= 4){
		timeColor = "#18f4ec"; //nearest
	}
	
	
	else if (y.stopsleft <= 10){
		timeColor = "#f2dd09"; //midway
	}
	
	else {
		timeColor = "#e36c2a"; //furthest
	}
	
	//-------------------------------------------------//
	
	
	
	if (y.stopsleft >= 1){ //not displaying "at bus stop" buses
	
		//ONCLICK SEND VALUE OF BUS ID
		
		var IDString = '"'+y.busID+'"';
		
		
		$("#bustimes_container").append("<div class='bustime' onclick='mapBus("+IDString+");'><div style='background-color:"+timeColor+"; margin-right:13px; width: 16px; height:100%; float:left; border-top-left-radius:.3em; border-bottom-left-radius:.3em;'></div><span class='bold'><div style='margin-top:3px;'>"+y.stopsleft+"</span> <span class='small'>Stops Left</span>"+"<br>"+y.distance+"</div> <img src='img/mapicon.png' style='margin-right:15px; margin-top:-30px; float:right;'> </div>");
		
		
	}
	
	
/*
	//----http://stackoverflow.com/questions/1191865/code-for-a-simple-javascript-countdown-timer---//
	var count=4;

	var counter = setInterval(timer, 1000); //1000 will  run it every 1 second
	
	//console.log(y);
	
	function timer(){
	
	  count=count-1;
	  if (count <= 1){
	     
	     
	     clearInterval(counter);
	     
	     refresh();


	     return;
	  }
	
	  document.getElementById("timer").innerHTML=count + " secs";
	}
	//------------------------------------------------------------------------------//
*/



}


function mapBus(IDString){

	
	window.location = "busmap.html?bus="+IDString+"&userlat="+lat+"&userlon="+lon+"";


}




function changeDirection(selection){	

	
	
	if (initial == 0){
	
		var current = $('#direction').text();
				
		if (current == directionOne){
			
			selection = directionTwo;
		}
		
		if (current == directionTwo){
		
			selection = directionOne;
		}
		
		var stopID=0;

		getBuses(selection,stopID, lon, lat);
		
	}
	
	if (initial == 1){
	
		unhide('direction_initial');
		
		$('#direction2').empty();
		$('#direction2').append(selection);
		
		getInitial(selection);
		
	}
	
}


function getInitial(direction){
	
	//var GPSwait = true;

	//while(GPSwait){
	
		//unhide('loader');
	//}
	
	unhide('loader');
	
	initialBusList(direction);
	
	if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
	} 
	
	
	//Get latitude and longitude;
	function successFunction(position) {
	
	   lat = position.coords.latitude;
	   lon = position.coords.longitude;
	   
	   //GPSwait = false;
	   
	   if (initial == 1){
	   
		   initial = 0;
		   
		   unhide('load_page');
	
		   var stopID=0;
		    
		   getBuses(direction,stopID,lon,lat);
	   }
 
	}
	
	function errorFunction(){
	
		unhide('loader');
		
		//console.log("no geo ;(");
		
		//unhide('busstops_popup');
		
		$('#status').empty();
		
		$('#status').append("No GPS :( Choose stop below V");
		
		
		

	}

}




function initialBusList(direction){

	clearElements();
	//$('#direction2').empty();
	//$('#direction2').append(direction);
	

	$.getJSON("php/bus_location.php",
		{ 
			'direction' : direction,
			'initial' : 1
		},
	
		function(json){
		
			var busString = '"busstops_popup"';
			   	
			$("#stop_container").append("<div class='stoplist' onclick='unhide("+busString+");' style='color:#2458a8; background-color:#9cafda;'</div>Choose Stop <div style='background-color:#2458a8; color:#9cafda; float:right; width:46px; height:100%; padding-right:2px; text-align:center; font-size:20px; font-family:GandhiSansBold; text-indent:3px;'>X</div>");

		
			$.each(json,function(i,y){

		   		//---- POPULATE FORM with Bus Stops------//
		   		
		   		directionString = '"'+direction+'"';	
		
				$("#stop_container").append("<div class='stoplist' onclick='changeStop("+directionString+","+this.stopID+");'>"+this.stopName+"</div>");
				    					
				//--------------------------------------//

			});

			//$('#direction2').append(direction);
	});
}

	






function changeStop(direction, stopID){

		
	if (initial == 1){
	
		initial = 0;
		
		unhide('load_page');

	}


	unhide('busstops_popup');
		
	getBuses(direction, stopID);

}


function unhide(divID) { //http://webdesign.about.com/od/dhtml/a/aa101507.htm
	
	 var item = document.getElementById(divID);
	 
	 if (item) {
		 item.className=(item.className=='hidden')?'unhidden':'hidden';
	 }
 }
 
 
function clearElements(){

	//clearInterval(counter);
	$(".bustime").remove();
	$('#direction').empty();
	$('#busstop').empty();
	$('#stop_container').empty();
	//$('#direction_box').empty();


}

function refresh(){

	getBuses(lastQuery.direction, lastQuery.stopID, lastQuery.lon, lastQuery.lat);
	
}

function back(){


	 unhide('load_page');
	 unhide('direction_initial');
	 unhide('loader');

	 initial = 1;

}



$(document).ready(function() {

  var z = $(window).height();
  
  //z = z - 370;
      
  $(".green").height(z - 300);

  
  $('#busstop_initial').append('Or, Choose a Bus Stop <img src="img/down.png" style="float:right; padding-top:12px; padding-left:3%; padding-right:12px;">');

  unhide('direction_initial');
  
  
/*
  $("div").click(function(){
  
  	$(this).css("background-color","red");
  	
  });
*/
  

  
});



$(window).resize(function(){
        var z = $(window).height();
  
  	//z = z - 370;
      
 		$(".green").height(z - 300);
    });



