/**
 *	TidePools Social WiFi
 *  Copyright (C) 2012 J.R.Baldwin <jrbaldwin@gmail.com>
 *
 *	This file is part of TidePools <http://www.tidepools.co>

 *  TidePools is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  TidePools is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with TidePools.  If not, see <http://www.gnu.org/licenses/>.
 */


    function onDrag(){
    	
		map.dragging.enable();
    	
    }


    function change(landmark) { //when drag/click, start here
    

		map.on('click', function(e){
		
			
			e = {
				 lat: e.latlng.lat,
				 lng: e.latlng.lng
			    };

		
			onMapClick(e,landmark);
				
		});

	}




//----query of map within bounding box------//

	function reBound(){
	
	
	/*
	if (needDots !== undefined){
		
			map.removeLayer(needDots);
		}
*/
	
	/*
	needDots = new L.LayerGroup();
	
		$.getJSON("https://rhiwifi.co/hurricane/sms/supply_call.php", 
		
			function(data){
			
				//console.log(data);
				
				$.each(data, function(x,y){
					
					//console.log(y.loc);
					
					var need = y.need;
					
					var where = y.where;
					
					var when = y.when;
					
					var needLon = y.loc[0];
					var needLat = y.loc[1];
					
					var dotInfo = "<b>Need:</b> "+need+"<br><b>Where:</b> "+where+"<br><b>When:</b> "+when;
					
					var needlatlng = new L.LatLng(needLat, needLon);
					
					var dot = new L.Circle(needlatlng,5);
					
					dot.bindPopup(dotInfo);

					
					needDots;
					
					needDots.addLayer(dot);
					

				
				});
				
				map.addLayer(needDots);
				  
				  //var dot = new L.Circle(buslatlng,3);
			}
			
		);
		
*/
		
		

		
/*
		//BUS DATA API
		
		if (busDots !== undefined){
		
			map.removeLayer(busDots);
		}
				
		busDots = new L.LayerGroup();
		
		
		
		
		$.getJSON("http://bustime.mta.info/api/siri/vehicle-monitoring.json?key=babec670-71fc0d6a-7352d870-d81a7a2b&OperatorRef=MTA%20NYCT&LineRef=B61&callback=?",
	
			function(data){
		
				
				console.log(data.Siri.ServiceDelivery.VehicleMonitoringDelivery[0].VehicleActivity[0]);
				
				
				//var dataa = data.Siri.ServiceDelivery.VehicleMonitoringDelivery;
				
				
				
				
				$.each(data.Siri.ServiceDelivery.VehicleMonitoringDelivery[0].VehicleActivity, function(x,y) { 
				
				 /*
 console.log(y.MonitoredVehicleJourney.VehicleLocation.Latitude);
				  console.log(y.MonitoredVehicleJourney.VehicleLocation.Longitude);
*/
				  
/*
				  var busLat = y.MonitoredVehicleJourney.VehicleLocation.Latitude;
				  var busLong = y.MonitoredVehicleJourney.VehicleLocation.Longitude;
				  
				  
				  var buslatlng = new L.LatLng(busLat, busLong);
				  
				  var dot = new L.Circle(buslatlng,3);
				  
				  busDots;
					
				 // map.removeLayer(dot);
				  
				  busDots.addLayer(dot);
				  
				  
				  
				});
				
				
				map.addLayer(busDots);
				
				
			});
		
*/


				
		var bounds = map.getBounds();
		
		//console.log(bounds);
		
		if (currentFilter !== undefined){
		
			reBoundFilter(currentFilter);
						
			return;
		}
		
		if (currentMap !== "combined"){
		
			reBoundFilter(currentFilter);
			
			return;
		
		}
		
		
		
		else {
		

			$.getJSON("php/rebound.php",
				{ 
					'nelat' : bounds._northEast.lat, 
					'nelng' : bounds._northEast.lng, 
					'swlat' : bounds._southWest.lat, 
					'swlng' : bounds._southWest.lng,
					'mapIDs' : mapIDArray
				},
				
				function(landmarks){
				
					
					drawLandmarks(landmarks);
					
					if (feedType == "landmarks"){
					
						$('#nav').load('php/landmark_feed.php',{'data':landmarks}); //also sending along the lat/long where landmark was dropped
						
					}
					
					if (feedType == "comments"){
					
						$('#nav').load('php/landmark_feed_shouts.php',{'data':landmarks}); //also sending along the lat/long where landmark was dropped
	
					
					}
					
	
				}
			);
		
		}

	}
	
//-----------------------------------------//





//----FILTER query of map within bounding box (for some reason, combining both rebound functions was throwing crazy errors------//

	function reBoundFilter(filter){
	
	
		currentFilter = filter;
		
	
		if (currentMap == "combined"){
		
			
			var mapSend = mapIDArray;
		
		}
		
		else {
		
			
			var mapSend = new Array(currentMap);
			
			
		}
		

						
		var bounds = map.getBounds();	
		
		
			
		$.getJSON("php/rebound.php",
			{ 
				'nelat' : bounds._northEast.lat, 
				'nelng' : bounds._northEast.lng, 
				'swlat' : bounds._southWest.lat, 
				'swlng' : bounds._southWest.lng,
				'filter' : filter,
				'mapIDs' : mapSend 
			},
			
			function(landmarks){
			
			
				drawLandmarks(landmarks);
				
				
				
				$('#nav').load('php/landmark_feed.php',{'data':landmarks}); //also sending along the lat/long where landmark was dropped
			}
		);

	}
	
//-----------------------------------------//


function reBoundFilterMap(mapID,divID){
	
	
		//console.log(mapID);
	
		/*
if (mapID == "unselect"){ //for routing from mapSelect function
		
			currentMap = "combined";
		}
*/
		
	/* 	else { */
		
			currentMap = mapID;
		
	//	}
		
		
		//console.log(currentMap);
		
		mapSelect(divID);
		
		
		//if (mapSelect(divID)){

		
			reBoundFilter(currentFilter);
		
		//}
		

	
	}


//need array outside to store 




//------- LANDMARK RENDERING --------//

function drawLandmarks(landmarks){
	
	
		var boxx = map.getBounds();	
		
		//console.log(boxx);
		

		var current = map.getZoom();
		
		
		if (lastZoom !== current){ //did a zoom occur, should we clear the map? does this save resources??
		
		
			$.each(mapLayersArray, function(x,z) {
					
				z.clearLayers();
	
			});


		}
		
		lastZoom = current;

		var counter = 1;
		
		
		
		//gathering list of landmark IDs for the next loop
		
		landmarkIDArray = []; //clear array before re-populate
		
		$.each(landmarks, function(i,v) {
		

				$.each(v, function(x,z) {
				

						var idString = z._id.$id;


						landmarkIDArray.push(idString);

				});
			
		});
		

		
		$.each(mapLayersArray, function(x,z) {
		
			z.clearLayers();
			
		});

		populateMapLayers();
		

		$.each(landmarks, function(i,v) {
		

				var landmarkCounter = 0;
				
				$.each(v, function(x,z) {
				
	
					counter++;


						var commentNum = z.feed.length;
						
						var landmarkID = "'"+x+"'";
						
						var flowers = new L.LayerGroup();


						//--- drawing some flowers if there's comments...SUPER LAME CODE here, atm----//
						
						
						if (commentNum >= 1){
	

							var flowerResult1 = landmarkResize('flowers1.png');

							var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00040, z.loc[0]-0.00020), {icon: flowerResult1, clickable:false});

							flowers.addLayer(flower1);
							
							if (commentNum >= 15){
							
								var flowerResult1 = landmarkResize('time.gif');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00040, z.loc[0]-0.00020), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							
							}
							
							if (commentNum >= 2){
							
								var flowerResult1 = landmarkResize('flowers1.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00040, z.loc[0]-0.00040), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);

							
							
							}
							
							if (commentNum >= 2){
							
								var flowerResult1 = landmarkResize('flowers2.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00030, z.loc[0]-0.00010), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
								
							
							
							}
							
							if (commentNum >= 3){
							
								var flowerResult1 = landmarkResize('flowers2.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00060, z.loc[0]-0.00040), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
								
							
							
							}
							
							if (commentNum >= 4){
							
								var flowerResult1 = landmarkResize('flowers3.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00040, z.loc[0]-0.00060), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
		
							}
							
							if (commentNum >= 5){
							
								var flowerResult1 = landmarkResize('flowers3.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00030, z.loc[0]-0.00030), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
								
							
							}
							
							if (commentNum >= 6){

								var flowerResult1 = landmarkResize('flowers4.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00025, z.loc[0]-0.00045), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							}
							
							if (commentNum >= 7){
							
								var flowerResult1 = landmarkResize('flowers4.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00020, z.loc[0]-0.00030), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
								
							
							
							}
							
							if (commentNum >= 8){
							
								var flowerResult1 = landmarkResize('flowers1.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00020, z.loc[0]-0.00050), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							
							}
							
							if (commentNum >= 9){
							
								var flowerResult1 = landmarkResize('flowers2.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00035, z.loc[0]-0.00025), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
								
							
							
							}
							
							if (commentNum >= 10){
							
								var flowerResult1 = landmarkResize('flowers3.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00035, z.loc[0]-0.00015), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							
							}
							
							if (commentNum >= 11){
							
								var flowerResult1 = landmarkResize('flowers4.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00032, z.loc[0]-0.00010), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
								
							
							
							}
							
							
							
							if (commentNum >= 13){
							
								var flowerResult1 = landmarkResize('flowers3.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00050, z.loc[0]-0.00045), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							
							}
							
							if (commentNum >= 14){
							
								var flowerResult1 = landmarkResize('tree.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00055, z.loc[0]-0.00030), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							
							}
							
							if (commentNum >= 12){
							
								var flowerResult1 = landmarkResize('tree2.png');

								var flower1 = new L.Marker(new L.LatLng(z.loc[1]-0.00055, z.loc[0]-0.00015), {icon: flowerResult1, clickable:false});

								flowers.addLayer(flower1);
							
							
							}
							

						} 
						
			
								
						var result = landmarkResize(z.stats.avatar); //sending object's icon to function
						
						var currentZ = map.getZoom();
						
						if (currentZ <= 17){

							number = L.Icon.extend({
								iconUrl: 'images/numbers/marker'+counter/2+'.png',
								shadowUrl: null,
								iconSize: new L.Point(20, 34),
								iconAnchor: new L.Point(20, -30),
								popupAnchor: new L.Point(-3, -10)
							});
						}
							
						if (currentZ >= 18){
						
								number = L.Icon.extend({
								iconUrl: 'images/numbers/marker'+counter/2+'.png',
								shadowUrl: null,
								iconSize: new L.Point(20, 34),
								iconAnchor: new L.Point(30, -60),
								popupAnchor: new L.Point(-3, -10)
							});
						
						}
							
						var numberIcon = new number();
			
			
						var plantMap = "4fec77c3c33694d70a000000";
						
						//var adminMap = "509022b6a5d3972e03000000";
						
						var adminMap = "509022b6a5e03000000";

						
						
						//console.log('landmarkID '+landmarkID);
			
						
						if (z.mapID == adminMap){
						
							var buildingOptions = '<div id="landmarkpopup">'
							+'<div id="dropzone" style="width:175px; height:42px; position:relative; float:left; margin-left:9; margin-bottom:5px; background-image:url(images/dropzone.png); z-index:1;"></div>'
							+'<div id="landmarklike" style="width:32px; height:28px; position:relative; margin-left:10px; float:left; display:inline-block; cursor:pointer; background-image:url(images/like.png); z-index:1;" onclick="likeLandmark('+landmarkID+');"></div>'
							+'<div id="follow" style="width:66px; height:27px; position:relative; margin-right:4; float:left; display:inline-block; cursor:pointer; background-image:url(images/follow.png); z-index:1; margin-left:5px;" onclick="followLandmark('+landmarkID+');"></div>'
							+'<div id="formfont" style="padding-left:12; padding-right:12;">'
							+'</br>'
							+'<p style="color:#7f275b; font-size:14; margin-left:-30px; position:relative; display:inline-block; margin-top:24;margin-bottom:-35;"><b>Add Comment</b></p>'
							+'</br><hr style="border:2px solid #7f275b;" />'
							+'<form id="newComment" action="php/record_comment.php" method="post" onsubmit="this.commentSubmit(); return false;">'
							+'</select></br>'
							+'<p style="margin-bottom:2px;">Name / Nickname</p><input type="text" id="name" value="Responder" name="name" maxlength="20"/>'
							+'<p style="margin-bottom:1px;">Comment</p><textarea name="description" class="clearme" id="description" maxlength="300"></textarea></br>'
							+'<input type="hidden" id="userID" name="userID" value="'+userID+'" />'
							+'<input type="hidden" id="landmarkID" name="landmarkID" value="'+landmarkID+'" />'
							+'</br>'
							+'<input type="button" id="commentSubmit" value="Post" onsubmit="this.commentSubmit(); " />'
							+'<input type="button" id="commentSubmit" value="Cancel" onClick="window.location.reload()" />'	
							+'</form>'
							+'<hr style="border:2px solid #7f275b;" />'
							+'</div>';


						}
						
						else {
			
							var buildingOptions = '<div id="landmarkpopup">'
							+'<div id="dropzone" style="width:175px; height:42px; position:relative; float:left; margin-left:9; margin-bottom:5px; background-image:url(images/dropzone.png); z-index:1;"></div>'
							+'<div id="landmarklike" style="width:32px; height:28px; position:relative; margin-left:10px; float:left; display:inline-block; cursor:pointer; background-image:url(images/like.png); z-index:1;" onclick="likeLandmark('+landmarkID+');"></div>'
							+'<div id="follow" style="width:66px; height:27px; position:relative; float:left; display:inline-block; cursor:pointer; background-image:url(images/follow.png); z-index:1; margin-left:5px;" onclick="followLandmark('+landmarkID+');"></div>'
							+'<div id="delete" style="width:66px; height:27px; position:relative; float:left; display:inline-block; cursor:pointer; background-image:url(images/delete.png); z-index:1; margin-left:5px;" onclick="deleteLandmark('+landmarkID+');"></div>'
							+'<div id="formfont" style="padding-left:12; padding-right:12;">'
							+'</br>'
							+'<p style="color:#7f275b; font-size:14; margin-left:-30px; position:relative; display:inline-block; margin-top:25;margin-bottom:-35;"><b>Add Comment</b></p>'
							+'</br><hr style="border:2px solid #7f275b;" />'
							+'<form id="newComment" action="php/record_comment.php" method="post" onsubmit="this.commentSubmit(); return false;">'
							+'</select></br>'
							+'<p style="margin-bottom:2px;">Name / Nickname</p><input type="text" id="name" value="Responder" name="name" maxlength="20"/>'
							+'<p style="margin-bottom:1px;">Comment</p><textarea name="description" class="clearme" id="description" maxlength="300"></textarea></br>'
							+'<input type="hidden" id="userID" name="userID" value="'+userID+'" />'
							+'<input type="hidden" id="landmarkID" name="landmarkID" value="'+landmarkID+'" />'
							+'</br>'
							+'<input type="button" id="commentSubmit" value="Post" onsubmit="this.commentSubmit(); " />'
							+'<input type="button" id="commentSubmit" value="Cancel" onClick="window.location.reload()" />'	
							+'</form>'
							+'<hr style="border:2px solid #7f275b;" />'
							+'</div>';
						}
													
						

						var buildingCoords = new L.LatLng(z.loc[1], z.loc[0]);
						
					
						
						if (z.mapID == plantMap){
						
						
							var building = new L.Marker(new L.LatLng(z.loc[1], z.loc[0]), {icon: result, clickable:false}); //need to get the correct icon's here
							
							mapLayersArray[landmarkCounter].addLayer(building);
							
						
						}
						
						
						else {
						
							var building = new L.Marker(new L.LatLng(z.loc[1], z.loc[0]), {icon: result}); //need to get the correct icon's here
						
							var buildingNumber = new L.Marker(new L.LatLng(z.loc[1], z.loc[0]), {icon: numberIcon, clickable:false});

	
							building.bindPopup(buildingOptions); //delete landmark?

							building.on('click', function(e) {

								commentSubmitter();
							
								map.panTo(buildingCoords);
								
								landmarkWindow(landmarkID);
								
								enableDropZone();
								
								clickedLandmark = e;
								
							
							});
													
							
							building.on('dblclick', function(e) {
							
							    alert("double click lets you reposition the landmark, but not yet :(");
							    
							});
							
							
							

							
							if (commentNum >= 1){
							
								mapLayersArray[landmarkCounter].addLayer(flowers);	
								mapLayersArray[landmarkCounter].addLayer(building);
								
									
								if (currentZ >= 18){
								
									mapLayersArray[landmarkCounter].addLayer(buildingNumber);
								}

							}
							
							else {

								mapLayersArray[landmarkCounter].addLayer(building);
								
								if (currentZ >= 16){
								
									mapLayersArray[landmarkCounter].addLayer(buildingNumber);
								}

							
							}
						}
						
	
						counter++;
						
						landmarkCounter++;

				});

		});
		

		$.each(mapLayersArray, function(x,z) {
					
			map.addLayer(z);
				
		});


	}
	

	
	
function deleteLandmark(landmarkID){


	var r = confirm("Delete this landmark?");
	
	if (r == true){
			
			//console.log(landmarkID);

 			$.getJSON("php/remove_landmark.php",
				{ 
					'landmarkID' : landmarkID
				},
				
				function(success){

					reBound();
					
				}
			);

	  }
	  
	  
	else{
		 	return;
	  	}
	  	
	  	
	  	
	  	map.closePopup();
	
		reBound();
		
		$(feedDiv).hide();
	  
	}


function landmarkResize(landmark){

		
		//console.log(landmark);
	
	
		//******************************
		//if landmark == event, iconsizeX = 345, iconsizeY = 343, etc.
		
		var iconSizeX;
		var iconSizeY;
		
		
		if (landmark == "event.png"){
					
			iconSizeX = 216;
			iconSizeY = 258;

		}
		
		if (landmark == "group.png"){
		
			iconSizeX = 287;
			iconSizeY = 189;
		
		}
		
				
		if (landmark == "friend.png"){
		
			iconSizeX = 182;
			iconSizeY = 183;
		
		}
		
		if (landmark == "food.png"){
		
			iconSizeX = 155;
			iconSizeY = 215;
		
		}
		
		if (landmark == "fixthis.png"){
		
			iconSizeX = 155;
			iconSizeY = 215;
		
		}
		
		if (landmark == "alert.png"){
		
			iconSizeX = 228;
			iconSizeY = 281;
		
		}
		
		if (landmark == "garden.png"){
		
			iconSizeX = 960;
			iconSizeY = 422;
		
		}
		
				
		if (landmark == "memory.png"){
		
			iconSizeX = 136;
			iconSizeY = 147;
		
		}
		
				
		if (landmark == "somethingelse.png"){
		
			iconSizeX = 155;
			iconSizeY = 215;
		
		}
		
		if (landmark == "busstop.png"){
		
			iconSizeX = 346;
			iconSizeY = 306;
		
		}
		
		if (landmark == "medical.png"){
		
			iconSizeX = 301;
			iconSizeY = 372;
		
		}
		
		if (landmark == "postoffice.png"){
		
			iconSizeX = 301;
			iconSizeY = 397;
		
		}
		
		if (landmark == "library.png"){
		
			iconSizeX = 332;
			iconSizeY = 429;
		
		}
		
		if (landmark == "police.png"){
		
			iconSizeX = 320;
			iconSizeY = 324;
		
		}
		

		if (landmark == "billboard.png"){
		
			iconSizeX = 354;
			iconSizeY = 464;
		
		}
		
		if (landmark == "rhi.png"){
		
			iconSizeX = 360;
			iconSizeY = 408;
		
		}
				
		if (landmark == "school.png"){
		
			iconSizeX = 420;
			iconSizeY = 441;
		
		}
		
		if (landmark == "gen_wifi.png"){
		
			iconSizeX = 320;
			iconSizeY = 420;
		
		}
		
		if (landmark == "tree.png"){
		
			iconSizeX = 331;
			iconSizeY = 313;
		
		}
		
		
		if (landmark == "tree2.png"){
		
			iconSizeX = 331;
			iconSizeY = 415;
		
		}
		
		
		if (landmark == "gen.png" || landmark == "gen2.png" || landmark == "gen3.png"){
		
			iconSizeX = 320;
			iconSizeY = 278;
		
		}
		
		if (landmark == "flowers1.png" || landmark == "flowers2.png" || landmark == "flowers3.png" || landmark == "flowers4.png"){
		
			iconSizeX = 115;
			iconSizeY = 115;
		
		}
		
		if (landmark == "time.gif"){
		
			iconSizeX = 548;
			iconSizeY = 288;
		
		}
		
				
		if (landmark == "A.png" || 
			landmark == "B.png" || 
			landmark == "C.png" || 
			landmark == "AUD.png" || 
			landmark == "154.png" || 
			landmark == "156.png" || 
			landmark == "157.png" ||
			landmark == "2242.png" ||
			landmark == "BC.png" ||
			landmark == "E.png" ||
			landmark == "FG.png" || 
			landmark == "I.png" ||
			landmark == "J.png" ||
			landmark == "L.png" ||
			landmark == "M.png" ||
			landmark == "north.png" ||
			landmark == "I.png" ||
			landmark == "1243.png"){
		
			iconSizeX = 166;
			iconSizeY = 160;
		
		}
		
		if (landmark == "A_s.png" ||
			landmark == "B_s.png" ||
			landmark == "C_s.png" ||
			landmark == "D_s.png" ||
			landmark == "E_s.png" ||
			landmark == "F_s.png" ||
			landmark == "G_s.png" ||
			landmark == "H_s.png" ||
			landmark == "I_s.png" ||
			landmark == "J_s.png"){
		
			iconSizeX = 110;
			iconSizeY = 111;
		
		}


		var current = map.getZoom();
		
		
		//each loop here for processing all landmarks...

		//this should be an assoc. array of all icons types at some point, with a loop through to update??
		
		//clean this up: take current result, multiply/divide with icon size accordingly
		
		//-----> neeed to pass array of needed object types, process, return array of objects
		
		
		if (current == 13){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/10, iconSizeY/10),
				iconAnchor: new L.Point(30/6, 10/6),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
		if (current == 14){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/8, iconSizeY/8),
				iconAnchor: new L.Point(30/5, 10/5),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
		if (current == 15){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/6, iconSizeY/6),
				iconAnchor: new L.Point(30/4, 10/4),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
		if (current == 16){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/5, iconSizeY/5),
				iconAnchor: new L.Point(30/3, 10/3),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
		if (current == 17){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/3, iconSizeY/3),
				iconAnchor: new L.Point(30/2, 10/2),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
		if (current == 18){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/2, iconSizeY/2),
				iconAnchor: new L.Point(30/1.5, 10/1.5),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
		if (current == 19){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX/1.5, iconSizeY/1.5),
				iconAnchor: new L.Point(30, 10),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}

		
		if (current == 20){
		
			pyramid = L.Icon.extend({
				iconUrl: 'images/'+landmark,
				shadowUrl: null,
				iconSize: new L.Point(iconSizeX, iconSizeY),
				iconAnchor: new L.Point(30*2, 10*2),
				popupAnchor: new L.Point(-3, -10)
			});
		
		}
		
	
		pyramidIcon = new pyramid();
		
		return pyramidIcon;


	}
	


	//if someone drops in a landmark and then moves it around, this will resend the latest coords to the form
    function resendCoord(drop){
     
     	landmarkLat = drop.target._latlng.lat;

	    landmarkLng = drop.target._latlng.lng;

     	landmarkStats();
     	

     }
     
     

    function populateMapLayers(){

    	$.each(landmarkIDArray, function(x) {
    	
			var x = new L.LayerGroup();
						
			mapLayersArray.push(x);
			
		});

    }
    

    
    function landmarkWindow(landmarkID){

		lastClickLandmark = landmarkID;


		$('#landmarkfeed').show();

    	map.on('click', function(e){

			$('#landmarkfeed').hide();

		});

		
		$('#landmarkfeed').load('php/landmark_feed_single.php',{'data':landmarkID}); 

    
    }
     
     
    function likeLandmark(landmarkID){
    
    	alert("♥ You liked this landmark ♥");
  
    }
     
     
     
     
     

