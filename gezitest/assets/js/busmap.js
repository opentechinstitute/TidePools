var APIkey = "babec670-71fc0d6a-7352d870-d81a7a2b";

var busDots;

var map;

var busSelect;

var busStore;

var userLat;

var userLon;

var initial = true;

var busLat;

var busLong;

var busLat2;

var busLong2;

var stopLoc;

	 

$(document).ready(function(){

	
	busSelect = getQueryString()["bus"];
	
	//var test = getQueryString()["userlat"];
	
	//alert(test);
	
	

	if (getQueryString()["userlat"] !== "undefined"){
				
		userLat = getQueryString()["userlat"]; //insecure!!!!! 
		userLon = getQueryString()["userlon"]; //insecure!!!!!
	}
	
	else {

		userLat = 40.67645788914882;  //temp...
		userLon = -74.00529831647873;

	}
	


	//oText = oForm.elements["text_element_name"]

	map = new L.Map('map', {crs:L.CRS.EPSG3857});
	var treekitUrl = '../1.0.0/RedHook5/{z}/{x}/{y}.png',
	treekit = new L.TileLayer(treekitUrl, {minZoom: 16, maxZoom: 22, tms:true, unloadInvisibleTiles:true, reuseTiles:true});  //this is the layer!! the tile map
	
	addCloud();
	



	/*
//---------- ADDING CLOUD LAYER ----------//
	
	//var cloudmap = new L.Map('cloudmap');
		
	var cloudUrl = 'http://otile1.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
	cloud = new L.TileLayer(cloudUrl);  //this is the layer!! the tile map
	
	//var cloud = new L.TileLayer('http://{s}.tile.cloudmade.com/b964066bd1de46c09867f94c658d4737/26250/256/{z}/{x}/{y}.png').addTo(map);
		
	
	
	map.addLayer(cloud,true);
	
	//----------------------------------------//
*/


	map.setView(new L.LatLng(40.67645788914882,-74.00529831647873), 17).addLayer(treekit); //setting current view to map layer



	//------ Events for changing landmarks in view (when map moves, re-query landmark locations) -----// 
	    
	map.on('dragend', reBound);
	
	map.on('zoomend', reBound);
	
/*
	map.on('move', function(e){
	
		console.log(e);
		
	});	
*/
	
	
 	//---------------------------//
 	
 	
	//--------- VARIABLES --------//
	
	var lastZoom = 18; //this is the zoom the map starts with right now
	
	reBound();
	
	getStops();
	
				    
});





function getQueryString() {	//http://stackoverflow.com/questions/647259/javascript-query-string
  var result = {}, queryString = location.search.substring(1),
      re = /([^&=]+)=([^&]*)/g, m;

  while (m = re.exec(queryString)) {
    result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
  }

  return result;
}




function reBound(){
	
		
		//BUS DATA API
		
		if (busDots !== undefined){
		
			map.removeLayer(busDots);
		}
				
		busDots = new L.LayerGroup();
		

		$.getJSON("http://bustime.mta.info/api/siri/vehicle-monitoring.json?key="+APIkey+"&OperatorRef=MTA%20NYCT&LineRef=B61&callback=?",
	
			function(data){
			

				$.each(data.Siri.ServiceDelivery.VehicleMonitoringDelivery[0].VehicleActivity, function(x,y) { 
					

				  busLat = y.MonitoredVehicleJourney.VehicleLocation.Latitude;
				  busLong = y.MonitoredVehicleJourney.VehicleLocation.Longitude;
				  

					 var busIcon = L.icon({
					    iconUrl: 'img/bus.png',
					    iconSize: [103, 81],
					    iconAnchor: [22, 94],
					    popupAnchor: [-3, -76],
					    shadowUrl: null
					});
					
					var content = '<p>Next Stop: <b>'+y.MonitoredVehicleJourney.MonitoredCall.StopPointName+'</b><br />'+y.MonitoredVehicleJourney.MonitoredCall.Extensions.Distances.PresentableDistance+'<br />Destination: '+y.MonitoredVehicleJourney.DestinationName+'</p>';	
					
					
					
					if (busSelect == y.MonitoredVehicleJourney.VehicleRef){ //filtering out user selected bus 
				  
					  	if(initial == true){		  	
					  		

					  		gotoBus(y.MonitoredVehicleJourney);	
					  		
					  		
					  		
					  	}	 
					  	
					  	initial = false;
					  	
					  	busLat2 = y.MonitoredVehicleJourney.VehicleLocation.Latitude;
					  	
					  	busLong2 = y.MonitoredVehicleJourney.VehicleLocation.Longitude;

 	
					}
					
					
					L.marker([busLat, busLong], {icon: busIcon}).bindPopup(content).addTo(busDots);	
						
				  	
				  
				});
				
				
				map.addLayer(busDots);
				
				//gotoBus();
				
				
			});
			
			
			
			

}



function gotoBus(busStore){


	
	busLat2 = busStore.VehicleLocation.Latitude;
	
	busLong2 = busStore.VehicleLocation.Longitude;
  
	
	if (userLat !== 0 || userLat !== undefined){
	
	
		 var userIcon = L.icon({
					    iconUrl: 'img/friend.png',
					    iconSize: [182/2, 183/2],
					    iconAnchor: [22, 94],
					    popupAnchor: [-3, -76],
					    shadowUrl: null
					});
					
		
					
		L.marker([userLat, userLon], {icon: userIcon}).addTo(map,true);	


	}
	

	
	map.setView(new L.LatLng(busLat2,busLong2), 18);


}


function getStops(){


	$.getJSON("php/bus_location.php",
			{ 
				'busstops' : 1
			},
		
			function(json){
	
				
				var busStops = new L.LayerGroup();
				
				$.each(json, function(x,y) {
				
					//console.log(y.loc[0]);
					
					//var iconCurrent = landmarkResize('busstopsign.png');
					
					var userIcon = L.icon({
						    iconUrl: 'img/busstopsign.png',
						    iconSize: [108/2, 226/2],
						    iconAnchor: [22, 94],
						    popupAnchor: [-3, -76],
						    shadowUrl: null
						});
						
					stopLoc = new L.LatLng(y.loc[1],y.loc[0]);
					
					var content = '<p><b>'+y.name+'</b><br />Destination: '+y.destname+'</p>';
						
					L.marker([y.loc[1], y.loc[0]], {icon: userIcon}).bindPopup(content).addTo(busStops);
					
	
	
				});
				
				map.addLayer(busStops);
				
			});

}



function navMap(icon){


	if (icon == 'bus'){
	

		map.panTo([busLat2, busLong2]);
	
	}
	
	if (icon == 'sign'){
	
		map.panTo(stopLoc); //not real right now
	}

	if (icon == 'person'){
	
		map.panTo([userLat, userLon]);
	}





}


function landmarkResize(landmark){

	//console.log(landmark);
	
/*
	var iconSizeX;
	var iconSizeY;
*/
	var pyramid;
	
	var img = document.createElement('img'); //inefficient??!?

	img.onload = function () { 
			
		//console.log(img.width);
		
		iconSizeX = img.width;
		iconSizeY = img.height;
		
		process(img.width, img.height);
		
	};
	
	
	img.src='img/'+landmark;
	
	


	//******************************
	
	function process(iconSizeX, iconSizeY){
	
		var current = map.getZoom();
	
	
		if (current == 13){
		
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/10, iconSizeY/10],
			    iconAnchor: [30/6, 10/6],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
				
		}
		
		if (current == 14){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/8, iconSizeY/8],
			    iconAnchor: [30/5, 10/5],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
	
		}
		
		if (current == 15){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/6, iconSizeY/6],
			    iconAnchor: [30/4, 10/4],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
			
		}
		
		if (current == 16){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/5, iconSizeY/5],
			    iconAnchor: [30/3, 10/3],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
		
		}
		
		if (current == 17){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/3, iconSizeY/3],
			    iconAnchor: [30/2, 10/2],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
		
		}
		
		if (current == 18){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/2, iconSizeY/2],
			    iconAnchor: [30/1.5, 10/1.5],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
			
		}
		
		if (current == 19){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX/1.5, iconSizeY/1.5],
			    iconAnchor: [30, 10],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
		
		}
	
		
		if (current == 20){
		
			pyramid = L.icon({
			    iconUrl: img.src,
			    iconSize: [iconSizeX, iconSizeY],
			    iconAnchor: [30*2, 10*2],
			    popupAnchor: [-3, -10],
			    shadowUrl: null
			});
		
		}
		
	
		//pyramidIcon = new pyramid();
		
		return pyramid;
	}

}


function refresh(){

	reBound();

}


function addCloud(){

	//---------- ADDING CLOUD LAYER ----------//
	
	//var cloudmap = new L.Map('cloudmap');
		
	var cloudUrl = 'http://{s}.tile.cloudmade.com/b964066bd1de46c09867f94c658d4737/26250/256/{z}/{x}/{y}.png',
	cloud = new L.TileLayer(cloudUrl);  //this is the layer!! the tile map
	
	//var cloud = new L.TileLayer('http://{s}.tile.cloudmade.com/b964066bd1de46c09867f94c658d4737/26250/256/{z}/{x}/{y}.png').addTo(map);
		
	
	
	map.addLayer(cloud,true);
	
	//----------------------------------------//


}
