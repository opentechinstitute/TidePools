 /**
 *	TidePools Social WiFi
 *  Copyright (C) 2012 Jonathan Baldwin <jrbaldwin@gmail.com>
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
  
  
  //--------- Checking for mobile, not handling right now... ----------//
  
  	var mobile = false;
  
	if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 ){
	 
	 mobile = true;
	}  //http://stackoverflow.com/questions/3514784/best-way-to-detect-handheld-device-in-jquery
	
//---------------------------------------------------------//
		
  
 // setTimeout("location.href = 'http://tidepools.co';", 600000); //for the thesis show only
  
  
  // ------  Adjusting div height for scrolling vertical ------ //
  
  	var h = $(window).height();
   
    $("#nav").css('height',h - 350);


  
  $(window).resize(function(){
  
        var h = $(window).height();        
        $("#nav").css('height',h - 350);
    });
    
   //----------------------------------------------//
  
  

	//---- MAP Universal VARIABLES -----//
	
	var mapsArray = new Array(); //going to store all the map layers here
	
	var mapIDArray = new Array(); //storing all the map mongo ids
	
	var landmarkIDArray = new Array(); 
	
	var mapLayersArray = new Array();
	
	
	var landmarkLat;
	
	var landmarkLng;
	
	var landmarkType;
	
	var drop; //when a landmark is put on the map to drag around, this is the object, right here, yes this one.
	
	var clickState = 0; //whether or not we're "dropping" a landmark at the moment
	
	
	var userID = 4589912; //the current users database ID number
	
	var lastClickLandmark;


	var feedDiv = '#landmarkfeed';
	
	var clickedLandmark;
	
	var feedType = "landmarks";
	
	var currentMap = "combined";
	
	var currentFilter;

	var lastFilter;
	
	var lastMapFilter;
	
	
	//------------------------//
    	


function landmarkSubmitter(){

	//-------  SUBMIT LANDMARK ---------//
	
	$("#submit").click(function(e) {   //on submit, send info to DB
	
	
		//alert("landmark SUBMIT!!!");
	
	
	  $.ajax({
	      type: $('#newPost').attr('method')
	    , url: $('#newPost').attr('action')
	    , data: $('#newPost').serialize()
	    , success: function(html) {
	    
	    	
	    	drop.closePopup();

	        clickState = 0;

	        reBound();
	        
	        unhide('form'); 
	        
	        map.removeLayer(drop);


	      }
	  });
	  e.preventDefault();
	});
}
	

	//------------------------------//



		//-------  SUBMIT MAP ---------//
		
			$("#mapsubmit").click(function(e) {   //on submit, send info to DB
			
			
			  $.ajax({
			      type: $('#newMap').attr('method')
			    , url: $('#newMap').attr('action')
			    , data: $('#newMap').serialize()
			    , success: function(html) {
		
			        //clickState = 0;
			        
			       // console.log(html); //right now, this is printing out the "echo" from the record landmark page (the returned html)
			        
			        //alert('landmark created!'); //can this be the landmark saying something in the future?
			        
			        //window.location.reload();
			        //
			        reBound();
			        
			        unhide('mapform'); 
			        
			        getMaps(userID);

			      }
			  });
			  e.preventDefault();
			});
		
	
		//------------------------------//


	//-------  SUBMIT COMMENT ---------//

	
	
	function commentSubmitter(){
	

			$("#commentSubmit").click(function(e) {   //on submit, send info to DB
						
			
			  $.ajax({
			      type: $('#newComment').attr('method')
			    , url: $('#newComment').attr('action')
			    , data: $('#newComment').serialize()
			    , success: function(html) {
		
					reBound();
					
					landmarkWindow(lastClickLandmark);
					
					$('.clearme').focus(function() {
					    $(this).val("");
					  });
					
			      }
			  });
			  e.preventDefault();
			});
		}
	
		//---------------------------------//
		

		var map = new L.Map('map');
		
		
	//	b964066bd1de46c09867f94c658d4737
		

		var treekitUrl = 'http://{s}.tile.cloudmade.com/b964066bd1de46c09867f94c658d4737/26250/256/{z}/{x}/{y}.png',
		treekit = new L.TileLayer(treekitUrl, {minZoom: 10, maxZoom: 20});  //this is the layer!! the tile map
		
		
		map.setView(new L.LatLng(40.67645788914882,-74.00529831647873), 18).addLayer(treekit); //setting current view to map layer


		//map.setView(new L.LatLng(42.36463232550283,-83.05483818054199), 18).addLayer(treekit); //setting current view to map layer
		
		
		
		//-------- MAPS -------//
		
	
		getMaps(userID); //a list of all current public maps as well as private maps for this user
		
		//----------------------//
		



	//------ Events for changing landmarks in view (when map moves, re-query landmark locations) -----// 
	    
	map.on('dragend', reBound);
	map.on('zoomend', reBound);
	map.on('zoomend', landmarkResize);
	

 	//---------------------------//
 	
 	
	//--------- VARIABLES --------//
	
	var lastZoom = 18; //this is the zoom the map starts with right now
	
	var pyramidB = new L.LayerGroup();
	

	
	//function here to query for all map layers that exist ---> that are not private
	//show maps in nav bar
	//create array of layers objects
	// for loop _____ = new L.LayerGroup();
	//
	
	
	
	
	//------------------ MAPS POPULATING-----------------------//
	
	
	
	function getMaps(userID){ //getting maps
	
		
	
		
		 $.getJSON("php/maps_collect.php",
			{ 
				'userID' : userID
			},
			
			function(maps){

				$('#navtop').load('php/maps_feed.php',{'data':maps});


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
				

				//----- Populating form map options -------//
				
					
					$.each(mapsArray, function(x,y) {
					
						$.each(y, function(j,z) { 
											
							$('#maplist').append(
													       
						       $('<option></option>').val(z.mapID.$id).text(z.mapName)
						        
						    );
 
						 });
  
					});

				//----------------------------------------//
				

				reBound(); //loading landmarks in map window initially

			}

		);	

	}
	
	
	
	//-----------------------------------------------------//
	


 	//---------------------------//

	
function onMapClick(e, landmark) { //dropping in a landmark, what happens? // btw, this function applies to both click & drop and drag & drop actions
	

	var current;
	
	var currentForm;
   
 	if (clickState == 0){
	   
	   	clickState = 1;
	   	
	   	var lat = e.lat;
	  	var lng = e.lng; 
	  	
	    
    	//---- on drop, pan to drop ----//
    
     	var panCoord = new L.LatLng(lat,lng);
    
    	map.panTo(panCoord);
		
		//-----------------------------//
		
	  	
	    current = landmarkResize(landmark+'.png'); //get the current size of landmark, kk thx ^_^
	   
	    map.on('zoomend', updateClickLandmark);
	    
	     
	     function updateClickLandmark(){
	     
	     	if (clickState == 1){
	
		     	map.removeLayer(drop);
		     	
		     	current = landmarkResize(landmark+'.png');
		     		    
		     	drop = new L.Marker(new L.LatLng(lat, lng), {icon: current});
				
		     	map.addLayer(drop);
		     	
		     	drop.dragging.enable();
		     	
		     	var dropCoord = drop.getLatLng();
	  	
		     	drop.bindPopup(currentForm).openPopup();
		     	
		     	landmarkStats();
		     	
		     	drop.on('dragend', handlePopup);
	     	}
	     	
	     	else{
	     	
	     		return;
	     	}
	     	
		 }



	 	drop = new L.Marker(new L.LatLng(lat, lng), {icon: current});
	    
	   	map.addLayer(drop);
	   	
	   	drop.dragging.enable();
	   	
	   	drop.on('dragend',resendCoord);

			var eventpopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>New Event</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'Event Title</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'What"s happening?</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br><b>When is it?</b></br>'
				+'<input type="radio" name="timespec" value="none" checked> None <input type="radio" name="timespec" value="once"> Once <input type="radio" name="timespec" 				value="daily"> Daily '+'<input type="radio" name="timespec" value="weekly"> Weekly'
				+'</br>Choose Date/Time  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';


			var memorypopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>A Memory...</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'Title</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'What happened?</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br>When was it?  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';


			var friendpopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>Add a Friend</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'Their name/nickname?</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'Anything else?</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';
				
			var grouppopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>Add a Group</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'Group Name</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'What do you/they do?</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br><b>When do you/they meet?</b></br>'
				+'<input type="radio" name="timespec" value="none" checked> None <input type="radio" name="timespec" value="once"> Once <input type="radio" name="timespec" 				value="daily"> Daily '+'<input type="radio" name="timespec" value="weekly"> Weekly'
				+'</br>Choose Date/Time  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';
				
				
				var alertpopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>New Alert</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'What kind?</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'What happened/will happen?</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br><b>When?</b></br>'
				+'<input type="radio" name="timespec" value="none" checked> None <input type="radio" name="timespec" value="once"> Once <input type="radio" name="timespec" 				value="daily"> Daily '+'<input type="radio" name="timespec" value="weekly"> Weekly'
				+'</br>Choose Date/Time  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';
				
				var fixthispopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>Report a city problem</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'What"s wrong?</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'Description</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br><b>When did you notice it?</b></br>'
				+'</br>Choose Date/Time  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';
				
				var foodpopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>Food Place</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'What kind of food?</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'Recommendations/Deals?</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br><b>When is it open?</b></br>'
				+'<input type="radio" name="timespec" value="none" checked> None <input type="radio" name="timespec" value="once"> Once <input type="radio" name="timespec" 				value="daily"> Daily '+'<input type="radio" name="timespec" value="weekly"> Weekly'
				+'</br>Choose Date/Time  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';
				
				var otherpopup = 
				'<p style="color:#7f275b; font-size:14;  position:relative; display:inline-block; margin-top:-8;margin-bottom:9;"><b>Something Else</b></p>'
				+'<form id="newPost" action="php/record_landmark.php" method="post" onsubmit="this.submit(); return false;">'
				+'</select>'
				+'Name/Title</br><input type="text" id="name" name="name" maxlength="50" value=""/></br>'
				+'Description</br> <textarea name="description" id="description" maxlength="300"></textarea></br>'
				+'<input type="button" id="submit" value="Save" onsubmit="this.submit(); " />'
				+'<input type="button" id="submit" value="Cancel" onClick="window.location.reload()" />'	
				+'</br><hr>'
				+'</br><b>Is it time based?</b></br>'
				+'<input type="radio" name="timespec" value="none" checked> None <input type="radio" name="timespec" value="once"> Once <input type="radio" name="timespec" 				value="daily"> Daily '+'<input type="radio" name="timespec" value="weekly"> Weekly'
				+'</br>Choose Date/Time  <input type="text" name="datetimepicker" id="datetimepicker" value="Click Here" />'
				+'<br>Add to Map (optional): <select id="maplist" name="maplist">'
				+'<input type="hidden" id="lat" name="lat" value="0" />'
				+'<input type="hidden" id="lng" name="lng" value="0" />'
				+'<input type="hidden" id="marktype" name="marktype" value="0" />'
				+'<input type="hidden" id="landmarkAdmin" name="landmarkAdmin" value="0" />'
				+'<input type="hidden" id="mapID" name="mapID" value="0" />'
				+'</form>';


		if (landmark == "event"){
			
			drop.bindPopup(eventpopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else if (landmark == "memory"){
			
			drop.bindPopup(memorypopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else if (landmark == "friend"){
			
			drop.bindPopup(friendpopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else if (landmark == "group"){
			
			drop.bindPopup(grouppopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else if (landmark == "alert"){
			
			drop.bindPopup(alertpopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else if (landmark == "fixthis"){
			
			drop.bindPopup(fixthispopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else if (landmark == "food"){
			
			drop.bindPopup(foodpopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
		
		else {
			
			drop.bindPopup(otherpopup).openPopup(); 
			
			currentForm = eventpopup;
		
		}
	   	 

	   	$('#datetimepicker').datetimepicker(); //enable calender on click


	  	 //----------------------------//
	  	 //sending values to form
	  	 
	  	 landmarkLat = lat;
	  	 landmarkLng = lng;
	  	 landmarkType = landmark;

	 	 landmarkStats(); //sending values to form
		 landmarkSubmitter();
		 
	  	 	drop.on('dragend', handlePopup);
	  	 	
	  	 	drop.on('zoomend', handlePopup);
	  	 	
	  	 	map.on('click', removeDrop);
  	 	  	 
	  	 //---------------------------//
	  	 

	  	 $('#loadingDiv') //http://stackoverflow.com/questions/68485/how-to-show-loading-spinner-in-jquery
	  	 
		    .hide()  // hide it initially
		    .ajaxStart(function() {
		        $(this).show();
		    })
		    .ajaxStop(function() {
		        $(this).hide();
		    })
		;
			  	 
	  	 clickState = 1;
	  
	  }
	  
	  if (clickState == 1) {
	  
	  	console.log("moving pyramid");
	  	
	  	return;
	  
	  }
	    
}


	function clickLandmarkIcon(){
	
		var current2 = landmarkResize();
		
		return current2;
		
		
	
	}
	
	
	function unhide(divID) { //http://webdesign.about.com/od/dhtml/a/aa101507.htm
	
		 var item = document.getElementById(divID);
		 
		 if (item) {
			 item.className=(item.className=='hidden')?'unhidden':'hidden';
		 }
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
	 
	 
	 
	 
	 function landmarkStats(){ //reinput landmark values for form submission
	 	 
	  	 $("input[name='lat']").val(landmarkLat);
	  	 $("input[name='lng']").val(landmarkLng);
	  	 
	  	 $("input[name='marktype']").val(landmarkType);

	  	 
	  	 $("input[name='landmarkAdmin']").val(userID);
	  	 
	  	 
	  	 $.each(mapsArray, function(x,y) {
					
			$.each(y, function(j,z) { 
			
				$('#maplist').append(
										       
			       $('<option></option>').val(z.mapID.$id).text(z.mapName)
			        
			    );
			    
			 });
					    
		  });

	 }
	 
	 
	 function handlePopup(){
	 			 
		 	drop.openPopup();
		 	landmarkStats();
		 	landmarkSubmitter();
		 	
	 }
	 
	 function removeDrop(){
	 
		 drop.closePopup();
		
		 clickState = 0;

		 reBound();
			        
		 unhide('form'); 
			        
		 map.removeLayer(drop);
	 
	 }
	 
	 function secretLandmark(){ //needs to expand into a actual loader for all other landmark images, include URL image links
	 
	 	var secretName = prompt("You found a secret button! I use it to sneak special landmarks onto the map :)","defaultvalue");
	 	
	 	if (secretName!=null && secretName!=""){
	 			 	
		 	change(secretName);
		  
		  }
	 
	 }
	 
	 
	 function changeFeed(type){
	 
	 	feedType = type;
	 	
	 	reBound();
	 	

	 }
	 

$(document).ready(function(){



						    
});