<?php
 
	
 	require('tropo.class.php');
 	
 	include_once('encrypt.php');

 	//require 'encrypt.php';
 	


	try 
	{
	    $m = new Mongo(); // connect
	    $db = $m->selectDB("gezi");
	    
	}
	catch ( MongoConnectionException $e ) 
	{
	    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
	    exit();
	}
	
	
	
	//look through HASH of all phone numbers --> have we seen this before?
	
	//Queue up "open chat rooms" ---> which ones are open? Texts back --> send to which room?
	

	
	$type = 'supplies';
	
	$coll = $db->$type;
	
	
	
	$type1 = 'landmarks';


	$collection = $db->$type1;
	
	
	$type2 = 'maps';
	
	$collection2 = $db->$type2;

	
	


	//INSERT MONGO QUERY INJECTION SECURITY HERE
	
	

	
	$session = new Session(); 
	 
	$sms = $session->getInitialText();
	
	$sKey = generateRandomString();
	
	$converter = new Encryption;

	
	$whoA = $session->getFrom();
	
	
	if (!$whoA['id']){  //is this an outbound SMS ?
	
		$to = "+".$session->getParameters("numberToDial"); 
		$msg = $session->getParameters("msg"); 
		     
		$tropo = new Tropo(); 
		     
		$tropo->call($to, array('network'=>'SMS')); 
		$tropo->say($msg); 
		 
		$tropo->RenderJson(); 
	
	
	}
	
	
	
	else {  //it is an incoming SMS, actually
	
	
		$whoA = $whoA['id'];
		
		$who = $converter->encode($whoA,$sKey);
	
		$tropo = new Tropo(); 
	
		
		
		if (!preg_match('/@/', $sms)) {
	   			 
	   		errorSMS($tropo);
		}
		
		else {
		
	
			
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
			 	
			 	'who'=>$who,
			    'need'=>$need,
			    'loc'=>$loc,
			    'where'=>$where,
			    'when'=>$when,
			    'sKey'=>$sKey
			);
		
		
			
			insertNeed($newNeed, $coll, $need, $where, $tropo, $when, $loc, $collection, $collection2, $who, $sKey);
	
		}
		
		
	}
		
	
	
	function insertNeed($newNeed,$coll, $need, $where, $tropo, $when, $loc, $collection, $collection2, $who, $sKey){
			
		$safe_insert = true;
		
		$coll->insert($newNeed,$safe_insert);
		
		$coll->ensureIndex(array("loc" => "2d"));
		

		
		$tropo->say("Reported ".$need."@".$where." successfully");
		

		
		$tropo->RenderJson(); 
		
		
		
		recordLandmark($loc, $where, $need, $collection, $collection2, $when, $who, $sKey);

		

	}



function errorSMS($tropo){


	$tropo->say("Please format text as Need @ Your Location, i.e. -> Gas, Water Pump @ Van Brundt and Pioneer Brooklyn");
		

	$tropo->RenderJson(); 




}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}




function recordLandmark($loc, $where, $need, $collection, $collection2, $when, $who, $sKey){

	switch (true) {
	    case strstr($need,"siginak"):
	        $marktype = "siginak";
	    case strstr($need,"yarali"):
	        $marktype = "yarali";
      case strstr($need,"dikkat"):
        $marktype = "dikkat";
      case strstr($need,"yiyecek-icecek"):
        $marktype = "gida";
      case strstr($need,"yangin"):
        $marktype = "yangin";
      case strstr($need,"internet"):
        $marktype = "internet";
      case strstr($need,"saglik"):
        $marktype = "saglik";
	    default:
	    	$marktype = "dikkat";
	}


 //    $marktype = "dikkat";

	// $marktype = "alert";

	$mapID = "51abac0aa5d3976226000000"; //adding SMS texts to this map layer
	
		
		
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
		
		
	//------- SMS Stats ---------//
	
	$smsInfo = array(	
	
		'who'=>$who,
		'sKey'=>$sKey
	);



	//------ Landmark Stats -----------//
	
	$avatar = 'assets/images/'.$marktype.'.png'; //avatar based on user selection
	$expires = 'never';
	$checkIn = array();
	$imGoing = array();

	
	$stats = array(	
		//'time'=>$time,
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
		    'permissions'=>$permissions,
		    'smsInfo'=>$smsInfo

		);
		
		//---------------------------//
		
	insertLandmark($landmark,$collection, $collection2, $mapID);
	
	    
	    
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
