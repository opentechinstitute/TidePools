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
 
	//---- MAP Universal VARIABLES -----//
	
	//var busAPIkey = mtaBusKey;  //add an API key from http://bustime.mta.info/ to pull in bus locations
	var busSelect;
	
	var mapsArray = new Array(); //going to store all the map layers here
	var mapIDArray = new Array(); //storing all the map mongo ids
	var landmarkIDArray = new Array(); 
	var mapLayersArray = new Array();
	var landmarksGroup = new L.LayerGroup();

	var flowers = new L.LayerGroup();

	var landmarkLat;
	var landmarkLng;
	var landmarkType;
	var lastClickLandmark;
	var clickedLandmark;
	var clickState = 0; //whether or not we're "dropping" a landmark at the moment
	var drop; //when a landmark is put on the map to drag around, this is the object, right here, yes this one.
	var currentForm;

	var userID = 4589912; //the current users database ID number (replace with session id?)

	var feedDiv = '#landmarkfeed';
	var feedType = "landmarks"; //default start interface by showing list of landmarks in area, not latest comment
	var currentMap = "combined"; //right now, it's overlaying all map layers by default	
	
	var currentFilter;
	var lastFilter;
	var lastMapFilter;
	
	var lastZoom; //used to determine if map zoom was changed, should we refresh
	
	var imageSizes = new Array(); //storing image sizes in folder
	
	var busDots;


	//------------------------//



	//-------  SUBMIT LANDMARK ---------//

	function landmarkSubmitter(){ //POST object to map
	
			
		$("#submit").click(function(e) {   //on submit, send info to DB

		  $.ajax({
		      type: $('#newPost').attr('method')
		    , url: $('#newPost').attr('action')
		    , data: $('#newPost').serialize()
		    , success: function(html) {
		    	 
		 		drop.closePopup(); 
		        clickState = 0;
		        reBound(); 
		        unhide('form'); 
		        map.removeLayer(drop); //
		        
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
		
		
		
	//------ RESOURCE SCRIPTS-----//
	
	function resourceLoader(){
	  
	  // Adjusting div height for scrolling vertical
	  
	  var h = $(window).height();
	  $("#nav").css('height',h - 315);
	
	  $(window).resize(function(){
	  
	        var h = $(window).height();        
	        $("#nav").css('height',h - 315);
	        
	    });
	 }
	 
	function unhide(divID) { //http://webdesign.about.com/od/dhtml/a/aa101507.htm
		 var item = document.getElementById(divID);
		 if (item) {
			 item.className=(item.className=='hidden')?'unhidden':'hidden';
		 }
	 }


    function coordsPop(){ //bounds for current map view
		var bounds = map.getBounds();
		alert(bounds._northEast.lat + " " +  bounds._southWest.lat + " " + bounds._northEast.lng + " " + bounds._southWest.lng)
	}


	function landmarkWindow(landmarkID){
	
		lastClickLandmark = landmarkID;
	
		$('#landmarkfeed').show();
	
		map.on('click', function(e){
	
			$('#landmarkfeed').hide();
		});
	
		$('#landmarkfeed').load('php/landmark_feed_single.php',{'data':landmarkID}); 
	}


  function mobileCheck(){
	if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 ){
	 
	 window.location = "http://localhost/mobile.html";
	 
	}  //http://stackoverflow.com/questions/3514784/best-way-to-detect-handheld-device-in-jquery
 }
     

	
	
	
 
	