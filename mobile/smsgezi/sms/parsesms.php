<?php


	try 
	{
	    $m = new Mongo(); // connect
	    $db = $m->selectDB("Hurricane");
	    
	}
	catch ( MongoConnectionException $e ) 
	{
	    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
	    exit();
	}
	
	
	$type = 'supplies';
	
	$coll = $db->$type;
	
	
	//INSERT MONGO QUERY INJECTION SECURITY HERE
	

	
	$sms = 'need generator gas @ van brundt and summit st brooklyn';
	
	
	
	$where = substr( strrchr( $sms, '@' ), 1 );
	
	$where = preg_replace("/ /","+",$where);
	
	
	
	$need = strstr( $sms, '@', true );

	
	$geo = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$where."&sensor=false");
	
	
	
	
	$geo = json_decode($geo);
	

	
	$lat = $geo->results[0]->geometry->location->lat;
	
	$lon = $geo->results[0]->geometry->location->lng;

	$loc = array($lon,$lat);


	$newNeed = array(
	 
	    'need'=>$need,
	    'loc'=>$loc
	);

	
		
	
	//var_dump($stopFrisk);
	
	
	insertNeed($newNeed, $coll);

		
	function insertNeed($newNeed,$coll){
			
		$safe_insert = true;
		
		$coll->insert($newNeed,$safe_insert);
		
		$coll->ensureIndex(array("loc" => "2d"));
		
		//echo "Stop & Frisk Incident Reported, thank you.";
		
		//header( 'Location: ../list.html' ) ;
		
		echo "cool!";

	}





?>