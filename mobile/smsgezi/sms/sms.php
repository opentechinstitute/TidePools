<?php
 
 	require 'tropo.class.php';


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
	
	
	
	$type1 = 'landmarks';


	$collection = $db->$type1;
	
	
	$type2 = 'maps';
	
	$collection2 = $db->$type2;

	
	


	//INSERT MONGO QUERY INJECTION SECURITY HERE
	
	

	 
	$session = new Session(); 
	 
	$sms = $session->getInitialText();
	 
	$tropo = new Tropo(); 

	

	
	$where = substr( strrchr( $sms, '@' ), 1 );
	
	$where_r = preg_replace("/ /","+",$where);
	
	
	
	$need = strstr( $sms, '@', true );

	
	$geo = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$where_r."&sensor=false");
	
	
	
	
	$geo = json_decode($geo);
	

	
	$lat = $geo->results[0]->geometry->location->lat;
	
	$lon = $geo->results[0]->geometry->location->lng;

	$loc = array($lon,$lat);


	$when = strftime('%c');
	
	$newNeed = array(
	 
	    'need'=>$need,
	    'loc'=>$loc,
	    'where'=>$where,
	    'when'=>$when
	);


	
	insertNeed($newNeed, $coll, $need, $where, $tropo, $when, $loc, $collection, $collection2);

		
	function insertNeed($newNeed,$coll, $need, $where, $tropo, $when, $loc, $collection, $collection2){
			
		$safe_insert = true;
		
		$coll->insert($newNeed,$safe_insert);
		
		$coll->ensureIndex(array("loc" => "2d"));
		

		
		$tropo->say("Reported ".$need."@".$where." successfully");
		

		
		$tropo->RenderJson(); 
		
		
		
		recordLandmark($loc, $where, $need, $collection, $collection2, $when);

		

	}




function recordLandmark($loc, $where, $need, $collection, $collection2, $when){



	$marktype = "alert";

	$mapID = "509022b6a5d3972e03000000";
	
	

	/*
$marktype = $_POST['marktype'];
	

	$mapID = $_POST['maplist'];
*/
		
		
	$landmarkAdmin = "null";
	

		$timeType = "null";
		
		$timeStart = $when;
		$timeEnd = "null";
		
		
		$time = array(
		
			'type' => $timeType,
			'start' => $timeStart,
			'end' => $timeEnd,
			'arriving' => 0
	
		);



	//------ Landmark Stats -----------//
	
	$avatar = $marktype.'.png'; //avatar based on user selection
	$expires = 'never';
	$checkIn = array();
	$imGoing = array();

	
	$stats = array(	
		'time'=>$time,
		'avatar'=>$avatar,
		'level'=>1,
		'reputation'=>0,
		'likes'=>0,
		'buzz'=>0,
		'checkIn'=>$checkIn,
		'imGoing'=>$imGoing
	);
	
	//---------- Landmarks Inside Landmark --------//
	
	$insideStatus = 0;
	$landmarksInside = array();
	
	$insides = array(
		'insideAlready'=> $insideStatus,
		'landmarksInside'=> $landmarksInside
	);
	
	//---------- News & Annoucements --------//
	
	$post = array(
		'sticky'=>0,
		'global'=>0,
		'post'=>'...',
		'likes'=>0
	);
	
	$feed = array(
			
	);
	
	//---------- Permissions --------//
	
	$viewers = array();
	$admins = array();
	//hidden = not on global map aggregation
	
	$permissions = array(
		'hidden' => 0,
		'viewers' => $viewers,
		'openedit' => 0,
		'admins' => $admins	
	);
	
	//------------------------------//



	
	    
		
	    $description = $where;
	    
	    
	    //MAP ID TO POST TO
	    // ADMIN NAME

	    
	  		

			
	    //------ MONGO DB ESCAPE STRING -------//
	   /* 
		$pattern = '$';
		$replacement = '\$';
		echo preg_replace($pattern, $replacement, $description); 
		*/
		//------------------------------------//
		

	    
	   	//----Landmark JSON Object------//
						
		$landmark = array(
		 
		    'name'=>$need,
		    'description'=>$description,
		    'type'=>$marktype,
		    'loc'=>$loc,
		    'mapID'=>$mapID,
		    'stats'=>$stats,
		    'insides'=>$insides,
		    'feed'=>$feed,
		    'permissions'=>$permissions

		);
		
		//---------------------------//
		
	insertLandmark($landmark,$collection, $collection2, $mapID);
	
/*
	$marktype = "alert";

	$mapID = "509022b6a5d3972e03000000";
	

	//------ Landmark Stats -----------//
	
	$avatar = $marktype.'.png'; //avatar based on user selection


	$stats = array(	
		'avatar'=>$avatar
	);
	
		
	//---------- News & Annoucements --------//
	

		
		$feed = array(
				
		);

	    
	   	//----Landmark JSON Object------//
						
		$landmark = array(
		 
		    'name'=>$need,
		    'description'=>$where,
		    'loc'=>$loc,
		    'mapID'=>$mapID,
		    'stats'=>$stats,
		    'feed'=>$feed

		);
		
		//---------------------------//
		

		insertLandmark($landmark,$collection, $collection2, $mapID);	
*/
	    
	    
	}



	
	function insertLandmark($landmark,$collection, $collection2, $mapID){
			
		$safe_insert = true;
		
		$collection->insert($landmark,$safe_insert);
		
		$collection->ensureIndex(array("loc" => "2d"));
		
				
		updateMap($landmark['_id'], $collection2, $mapID);


	}
	
	
	
	function updateMap($landmarkID, $collection2, $mapID){ //to store landmarks on each map
	

		$landmarkID = new MongoID($landmarkID);
		
		$mapIDObj = new MongoID($mapID);
		
		
		$newdata = array('$push' => array("landmarks" => $landmarkID));

		
		$collection2->update(array("_id"=>$mapIDObj), $newdata);

	
	}
	


 
 
?> 