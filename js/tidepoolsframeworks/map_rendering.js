/**
 *.---.      .                    .     
 *  |  o     |                    |     
 *  |  .  .-.| .-. .,-.  .-.  .-. | .--.
 *  |  | (   |(.-' |   )(   )(   )| `--.
 *  '-' `-`-'`-`--'|`-'  `-'  `-' `-`--' v0.2
 
 *  Copyright (C) 2012-2013 Open Technology Institute <tidepools@opentechinstitute.org>
 *	Lead: Jonathan Baldwin
 *	This file is part of Tidepools <http://www.tidepools.co>

 *  Tidepools is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  Tidepools is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with Tidepools.  If not, see <http://www.gnu.org/licenses/>.
 */

 
 //For adding map layers and landmarks onto map from the database
 
 
 	//------------------ ADD MAP LAYERS AND LANDMARKS ON MAP LAYERS-----------------------//
	
	function getMaps(userID){ //getting maps from DB
	
		 $.postJSON("php/maps_collect.php",
			{ 
				'userID' : userID //sending user ID to see which maps they can access
			},

			function(maps){

				$.each(maps, function(i,v) {

					mapIDArray.push(i); //adding map IDs to array to pass to reBound()

					i = new L.LayerGroup(); //new layer group for leaflet for each map ID

					var mapLayer = [{
					
						"mapID": v._id,
						"mapName": v.name,
						"mapDescrip": v.description,
						"mapLayer": i
					}];
				
					mapsArray.push(mapLayer);

				});
				
				reBound(); //loading landmarks in map window (this is the first time it is done)
				
				populateMaps(mapsArray,maps);

			}
		);	
	}
	
	//-----------------------------------------------------//
	
	
	function populateMaps(mapsArray,maps){
	
		$('#navtop').load('php/maps_feed.php',{'data':maps}); //display current maps in map feed DIV

	
		//----- Populating form map options -------//
				
		$.each(mapsArray, function(x,y) { 
		
			$.each(y, function(j,z) { 
								
				$('#maplist').append(
										       
			       $('<option></option>').val(z.mapID.$id).text(z.mapName)
			        
			    );
			 });
		});
		//----------------------------------------//
	}
	

	//----query of map within bounding box (what landmarks are in view for users window?)------//

	function reBound(){
	
		if (APIload == true){
			getAPIs(); //reloading APIs after every map change, but this SHOULD be storing values for a time duration, then re-requesting
		}
			
		var bounds = map.getBounds(); //geo coordinates of users current window
		
		//--- Should we filter out some landmarks?---//
		
		if (currentFilter !== undefined){
			reBoundFilter(currentFilter);
			return;
		}
		
		if (currentMap !== "combined"){
			reBoundFilter(currentFilter);
			return;
		}
		//-----------//
		
		else {
		
			$.postJSON("php/rebound.php", //what data is inside this geo window?
				{ 
					'nelat' : bounds._northEast.lat, 
					'nelng' : bounds._northEast.lng, 
					'swlat' : bounds._southWest.lat, 
					'swlng' : bounds._southWest.lng,
					'mapIDs' : mapIDArray
				},
				
				function(landmarks){
				
					drawLandmarks(landmarks); //render data on map
					
					//check feed type to specifiy what data to show in scroller from query
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
			
		$.postJSON("php/rebound.php",
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


	function reBoundFilterMap(mapID,divID){ //when map is clicked, filters only this map
		currentMap = mapID;
		mapSelect(divID);
		reBoundFilter(currentFilter);
	}
	


	//-----------------------------------//
	//------- LANDMARK RENDERING -------//
	//----------------------------------//


	function drawLandmarks(landmarks){
			
		var currentZ = map.getZoom(); //current map zoom
		
		var counter = 1;

	 	 // $('img.leaflet-marker-icon').fadeOut(1000, function() {  //working on fade 
		    //landmarksGroup.clearLayers();
		    
		  //});
		  
		landmarksGroup.clearLayers(); //clear landmarks before redrawing


		//gathering list of landmark IDs for the next loop
		landmarkIDArray = []; //clear array before re-population
		
		$.each(landmarks, function(i,v) {
			$.each(v, function(x,z) {
				var idString = z._id.$id;
				landmarkIDArray.push(idString);
			});
		});
		
		populateMapLayers();

		$.each(landmarks, function(i,v) {
		
				var landmarkCounter = 0;
				
				$.each(v, function(x,z) {
				
						counter++;

						var commentNum = z.feed.length;
						
						var landmarkID = "'"+x+"'";
						
						//resizing current icon to zoom ratio
						landmarkResize(z.stats.avatar, currentZ, function(result) { 
	
							//RE-enable for rendering number markers (need to update icon extension w/ latest ver.
/*
							if (currentZ <= 17){
	
								number = L.Icon.extend({
												options: {
	
									iconUrl: 'images/numbers/marker'+counter/2+'.png',
									shadowUrl: null,
									iconSize: new L.Point(20, 34),
									iconAnchor: new L.Point(20, -30),
									popupAnchor: new L.Point(-3, -10)
									}
								});
							}
								
							if (currentZ >= 18){
							
									number = L.Icon.extend({
													options: {
									iconUrl: 'images/numbers/marker'+counter/2+'.png',
									shadowUrl: null,
									iconSize: new L.Point(20, 34),
									iconAnchor: new L.Point(30, -60),
									popupAnchor: new L.Point(-3, -10)
									}
								});
							}
								
							var numberIcon = new number();
*/
							//////////////////////////////////
											
							var wHeight = $(window).height()/2;
							var wWidth = $(window).width()/2;
							
							var class2 = "status";
							var type2 = "landmarkstatus";
							
							var mapID = "'"+z.mapID+"'";
											
							var popWidth = 196;
							var popHeight = 325;
							
							///////////////////////////////////
							
							
							var buildingOptions = '<div id="popupdiv" style=" width:'+popWidth*1.2+'px; height:'+popHeight+'px;"></div>';
											

							if (z.mapID == plantMap){ //this stops indexing of landmarks (for cosmetic landmarks, like plants)
								var building = new L.Marker(new L.LatLng(z.loc[1], z.loc[0]), {icon: result, clickable:false}); 
								mapLayersArray[landmarkCounter].addLayer(building);
							}
	
							
							else {
							
								//adding landmark
								L.marker(new L.LatLng(z.loc[1], z.loc[0]), {icon: result}).bindPopup(buildingOptions).addTo(landmarksGroup)
									
									.on('click', function(e) { //when building clicked, do this
									
										$('#popupdiv').load('php/popup_content.php',
											{
											'class': class2,
											'type': type2,
											'mapID': mapID,
											'userID': userID,
											'landmarkID': landmarkID,
											'adminMap': adminMap,
											'width': popWidth,
											'height': popHeight
											},
											
											function(form){
											
												commentSubmitter();
												map.panTo(new L.LatLng(z.loc[1], z.loc[0]));
												landmarkWindow(landmarkID);
												enableDropZone();
												clickedLandmark = e;
											}
										); 	
	
  								})
									.on('dblclick', function(e) {
								
								    	alert("double click lets you reposition the landmark, but not yet :(");
								});
								
								landmarksGroup.addTo(map);
							}
	
							counter++;
							landmarkCounter++;

						});
				});
		});
	}
	

	function landmarkResize(landmark, currentZ, callback){
	
		var iconSizeX;
		var iconSizeY;
		
		$.postJSON("php/image_size.php", //PHP query for image size dimensions
			{ 
				'landmark' : landmark 
			},
	
			function(landmarkSize){
	
				iconSizeX=landmarkSize[0]; //image sizes
				iconSizeY=landmarkSize[1];
				
				//minZ + maxZ --- zoom range - called in tidepools.js
				//http://stackoverflow.com/questions/14224535/scaling-between-two-number-ranges
				var percent = (currentZ - minZ) / (maxZ - minZ);
				var outputScale = percent * (maxScale - minScale) + minScale;
				
				var sphinx = L.icon({
					iconUrl: 'images/'+landmark,
					iconSize: new L.Point(iconSizeX/outputScale, iconSizeY/outputScale),
					iconAnchor: new L.Point(0,0),
					popupAnchor: new L.Point(50/outputScale, 20/outputScale)
				});
				
				callback(sphinx);
			}
		);	
	}
		

	function populateMapLayers(){
	
		$.each(landmarkIDArray, function(x) {
			var x = new L.LayerGroup();
			mapLayersArray.push(x);
		});
	
	}
	

	 function select(divID) { 
	 
	 	if (lastFilter !== undefined && lastFilter !== divID){
	 		var lastItem = document.getElementById(lastFilter);
	 		lastItem.className = 'unselect';
	 	}
	 	
		var item = document.getElementById(divID);
		 
		if (item) {
			 item.className=(item.className=='select')?'unselect':'select';
		}

		if (lastFilter == divID && item.className == 'unselect'){ //unselect and kill filter
	 		currentFilter = undefined;
	 		reBound();
	 	}
		 
		lastFilter = divID;

	 }


 	function mapSelect(divID) { 
	 
	 	if (lastMapFilter !== undefined && lastMapFilter !== divID){
	
	 		var lastItem = document.getElementById(lastMapFilter);
	 		lastItem.className = 'unselect';
	 	}

	
		 var item = document.getElementById(divID);
		 
		 if (item) {
			 item.className=(item.className=='select')?'unselect':'select';
		 }
		 
		 
		 if (lastMapFilter == divID && item.className == 'unselect'){ //unselect and kill map filter
	 		reBoundFilterMap("combined");
	 	}

		lastMapFilter = divID;
	 }
	 
	 
	 function changeFeed(type){
	 	feedType = type;
	 	reBound();
	 }
