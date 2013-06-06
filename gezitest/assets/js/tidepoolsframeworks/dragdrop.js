$(function() { //making all "items" tag divs draggable

       	var landmarkType; //which landmark was grabbed?
       	var cleanMarker;
       	var relativePosition;
       	var trash = 0;


		$( ".landmark" ).draggable({ delay: 210 }); //takes a bit longer to select and drag (for click mistakes)

        $(".landmark").draggable({   
        
                revert: true,     //---> items snap back on drag release. adding extra function options to draggable() ---> including conditions in function parameters
                helper: myHelper, //makes copy of item when dragging
                
                stop: function(event,ui) {
               		 ui.helper.remove();
                	$('#trashlandmark').hide();
                },
                start: function(event,ui) {
                	$('#trashlandmark').show();
                }
        });
        
         $("#trashlandmark").droppable({ //a div that item can drop to
 			greedy: true,
                
            over: function() {
                      $(this).removeClass('out').addClass('over'); //when "this" which = #trash!!! ---> has hover "over" then remove class out and an "over" 
                },
                
            out: function() {
                       $(this).removeClass('over').addClass('out'); //when "this" doesn't have item over it anymore, change back to other color 
            },
            
            drop: function(event,ui) { //when item drops in drop box, do this stuff plz
            
            	trash = 1;
            	$(this).removeClass('over').addClass('out');
            }
        });

       	$("#map").droppable({ //a div that item can drop to

            over: function() {
                      // $(this).removeClass('out').addClass('over'); //when "this" which = #trash!!! ---> has hover "over" then remove class out and an "over" 
                },
            out: function() {
                     //   $(this).removeClass('over').addClass('out'); //when "this" doesn't have item over it anymore, change back to other color 
            },
            drop: function(event,ui) { //when item drops in drop box, do this stuff plz
            
            	if (trash == 0){
            	
					var currentPos = ui.helper.position();
					var coords = map.layerPointToLatLng(map.containerPointToLayerPoint(new L.Point(currentPos.left, currentPos.top)));
		        	map.on('click', function(e){
					});
						
					ui.helper.remove();
					onMapClick(coords,landmarkType);
				}
				
				else {
					trash = 0;
					return;
				}
            }
        });
        
       function myHelper( event ) {
       
       		 landmarkType = event.currentTarget.id;
           var n=landmarkType.replace("assets/images/","");
       		 return '<div id="dragHelp"><img src="assets/images/icon_'+n+'"/></div>';
           //return '<div id="dragHelp"><img src="'+landmarkType+'"/></div>';
		}
		
		//removing HTML from div extraction
		function strip(html){
		   var tmp = document.createElement("DIV");
		   tmp.innerHTML = html;
		   return tmp.textContent||tmp.innerText;
		}

     	function sendtoMap(landmarkType){
     	}
});


function enableDropZone(){

 		$("#dropzone").droppable({ //a div that item can drop to
 			greedy: true,
            over: function() {
                      $(this).removeClass('out').addClass('over'); //when "this" which = #trash!!! ---> has hover "over" then remove class out and an "over" 
                },
            out: function() {
                       $(this).removeClass('over').addClass('out'); //when "this" doesn't have item over it anymore, change back to other color 
            },
            
            drop: function(event,ui) { //when item drops in drop box, do this stuff plz
            
            	$(this).removeClass('over').addClass('out');
            
            	alert("You can put landmarks inside landmarks, like a person inside a building, soon...");

            }

        });
        
        
        $("#landmarkpopup").droppable({ //a div that item can drop to
 		
 			greedy: true,
                
            over: function() {
                     //$(this).removeClass('out').addClass('over'); //when "this" which = #trash!!! ---> has hover "over" then remove class out and an "over" 
            },
                
            out: function() {
                     //  $(this).removeClass('over').addClass('out'); //when "this" doesn't have item over it anymore, change back to other color 
            },
            
            drop: function(event,ui) { //when item drops in drop box, do this stuff plz
             			
            }

        });

}



        
     
