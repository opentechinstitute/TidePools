	 //-----------------------------------------------//
	 //****** DRAG DROP LANDMARK FUNCTIONS ***********//
	 //-----------------------------------------------//
	 
	 
    function change(landmark) { //click to place landmark function (initial after drag drop fire)

    	alert("Noktayi eklemek icin haritaya tiklayin");
        
		map.on('click', function(e){
			
			e = {
				 lat: e.latlng.lat,
				 lng: e.latlng.lng
			    };
		
			onMapClick(e,landmark);
		});
	}
		 

	function onMapClick(e, landmark) { //when dropping in a landmark, do this // btw, this function applies to both click & drop and drag & drop actions
		
		var currentZ = map.getZoom(); //current map zoom
		var current;

	   
	 	if (clickState == 0){ //if not currently in a "adding landmark state" then do this:
		   
		   	clickState = 1;
		   	
		   	landmarkLat = e.lat;
		  	landmarkLng = e.lng; 
		  	

		  	// if ( $('#navbarr').hasClass('unhidden') ) {
    	// 	$('#navbarr').removeClass('unhidden').addClass('hidden');

    		
		  	
		  	
	    	//---- on drop, pan to drop ----//
	    
	     	var panCoord = new L.LatLng(landmarkLat,landmarkLng);
	    
	    	map.panTo(panCoord);
			
			//-----------------------------//
			
			landmarkResize(landmark, currentZ, function(result) { 
					
			 	drop = new L.Marker(new L.LatLng(landmarkLat, landmarkLng), {icon: result});
			   	map.addLayer(drop);
			   	drop.dragging.enable();
			   	
			   	//////////////////////////////////
								
				var wHeight = $(window).height()/2;
				var wWidth = $(window).width()/2;
				
				var class2 = "record";

				//alert(landmarkType);

				landmarkType = landmark;

				var landmarkTypeExt = landmark.substr(0, landmark.lastIndexOf('.')) || landmark; //removing file extension
								
				var popWidth = 196;
				var popHeight = 325;
	
				///////////////////////////////////
				
				
				var popupDiv = '<div id="popupdiv" style=" width:'+popWidth*1.2+'px; height:'+popHeight+'px;"></div>';
								
				drop.bindPopup(popupDiv).openPopup();
				
		  	 	drop.on('dragend', function(e){
		  	 	
		  	 		handlePopup(e);
		  	 	});
		  	 
		  	 	drop.on('zoomend', handlePopup);
			
				$('#popupdiv').load('php/popup_content.php',
					{
					'class': class2,
					'type': landmarkTypeExt,
					'width': popWidth,
					'height': popHeight
					},
					
					function(form){
						
						currentForm = form;
						$('#datetimepicker').datetimepicker(); //enable calender on click
						$('#startdatetimepicker').datetimepicker(); //enable calender on click
	   					$('#enddatetimepicker').datetimepicker(); //enable calender on click
	   					
	   					landmarkStats();
	   					landmarkSubmitter();
					}
				); 	    
			  	 
			  	 map.on('click', removeDrop);
	
			  });
		  }
		  
		  if (clickState == 1) {
		  	unhide('navbarr');
		  	console.log("moving pyramid");
		  	return;
		  }
	}

	 function handlePopup(e){
	 
	 	landmarkLat = e.target._latlng.lat;
	    landmarkLng = e.target._latlng.lng;
	 	
	 	drop.bindPopup(currentForm).openPopup();
	 	
	 	$('#datetimepicker').datetimepicker(); //enable calender on click
		$('#startdatetimepicker').datetimepicker(); //enable calender on click
   		$('#enddatetimepicker').datetimepicker(); //enable calender on click

	 	landmarkStats();
	 	landmarkSubmitter();
	 }
	 
	 function removeDrop(){
	 
		 drop.closePopup();
		 clickState = 0;
		 unhide('form'); 
		 map.removeLayer(drop);

		 unhide('navbarr');
		 

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
	 
	 //if someone drops in a landmark and then moves it around, this will resend the latest coords to the form
	  function resendCoord(drop){
     
     	landmarkLat = drop.target._latlng.lat;
	    landmarkLng = drop.target._latlng.lng;
     	landmarkStats();
     }



	 //-----------------------------------------------//
	 //********** EXTRA LANDMARK FUNCTIONS ***********//
	 //-----------------------------------------------//



	function clickLandmarkIcon(){
		var current2 = landmarkResize();
		return current2;
	}


	// function addbyLocation(){

	// 	var secretName = prompt("huh","defaultvalue");
	//  	if (secretName!=null && secretName!=""){
	// 	 	//change('assets/images/'+secretName);
	// 	  }

	// }

function addbyLocation(){
	console.log("success");
  $("#search-address").click(function(e) {   //on submit, send info to DB
   console.log ($ ("#query").val());
   var address = $("#query").val();
            
        $.postJSON("php/locationsearch.php",
            {
                'query' : address
            },

            function(success){

                gotoCoordinates(success[1],success[0]);
                L.marker([success[1],success[0]]).addTo(map);
            }
        );
		  
		  e.preventDefault();
		});
	}


	 function secretLandmark(){ //needs to expand into a actual loader for all other landmark images, include URL image links
	 
	 	var secretName = prompt("You found a secret button! Load landmarks from the images folder here (include image name and extension, like: tree.png) click map to place landmark","defaultvalue");
	 	if (secretName!=null && secretName!=""){
		 	change('assets/images/'+secretName);
		  }
	 }
	 
	 function gotoCoordinates(lat,lng){
	 
	 	var goTo =  new L.LatLng(lat, lng);
	 	map.panTo(goTo);	 
	 	reBound();	
	 	
	 }
	 
	 function deleteLandmark(landmarkID){

		var r = confirm("Delete this landmark?");

		if (r == true){
	
	 			$.postJSON("php/remove_landmark.php",
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
	
	
	 function likeLandmark(landmarkID){
    
    	alert("♥ You liked this landmark ♥"); //this doesn't do anything atm
  
    }

