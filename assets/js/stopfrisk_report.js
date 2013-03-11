
$(document).ready(function() {

	
	$('#report').submit(function() {
	

	 	$.post( 'php/stopfrisk_record.php', $('#report').serialize(), function(data) {

			
			alert(data);
			
			window.location = 'list.html';

			
			//console.log(data);
			
	       }	    
	       
	     );


	  
	  return false;
	  
	});
	
	
	$('#other').click(function() {
	  
	  
	  alert('asdf');
	});


	$('#submitButton').click( function() {
	   	});

  
});
