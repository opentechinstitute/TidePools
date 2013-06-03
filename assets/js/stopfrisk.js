
var data;




$(document).ready(function() {

	

	
	$.getJSON("php/stopfrisk_call.php",
		{ 
			'null' : 'null'
		},
	
		function(json){
		
			//console.log(json);
			
			data = json;
			
			
			$.each(json,function(i,y){
			
			//	console.log(i);
				
			//	console.log(y.friskbasics);
				
				printData(i,y);
				
			
			});
			
		}
		
	);
	
	
	
  
});



function printData(i,y){

	//console.log(y);


	var frisked = y.friskbasics.frisked;
	var search = y.friskbasics.search;
	var reason = y.friskbasics.reason;
	
	var time = y.whenwhere.time;
	var month = y.whenwhere.month;
	var year = y.whenwhere.year;

	
	
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
	
	

		
		var IDString = '"'+i+'"';
		
		
		$("#stopfrisks_container").append("<div class='stopfrisk' onclick='popup("+IDString+");'><div style='background-color:"+timeColor+"; margin-right:13px; width: 16px; height:100%; float:left; border-top-left-radius:.3em; border-bottom-left-radius:.3em;'></div><span class='bold'><div style='margin-top:3px;'><span class='small'>Frisked? </span>"+frisked+"</span> "+"<br><span class='small'>Searched? </span> "+search+" <br><span class='small'>Reason given: </span>"+reason+"<br> <span class='small'>When: </span> "+time+" "+month+" "+year+"</div> <img src='img/mapicon.png' style='margin-right:15px; margin-top:-56px; float:right;'> </div>");
		
		

}


function popup(ID){

	//console.log(ID);
	//console.log(data);
	
	$.each(data, function(key, value) { 
	
		//console.log(key);
		
		if(key == ID){
		
			console.log(value);
		
		}
	
	
	
	});


}




function unhide(divID) { //http://webdesign.about.com/od/dhtml/a/aa101507.htm
	
	 var item = document.getElementById(divID);
	 
	 if (item) {
		 item.className=(item.className=='hidden')?'unhidden':'hidden';
	 }
 }
 
 
 
 		
