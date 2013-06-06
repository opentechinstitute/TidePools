<?php

try 
{
    $m = new Mongo(); // connect
    $db = $m->selectDB("RedHook");
    
}
catch ( MongoConnectionException $e ) 
{
    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
    exit();
}


$type = 'busstops';

$coll = $db->$type;

$busAPIkey = 'babec670-71fc0d6a-7352d870-d81a7a2b';


$destName = $_GET['direction'];
$stopIDSelect = $_GET['stopID'];
$initial = $_GET['initial'];
//$lonlat = $_GET['location'];
$lon = $_GET['lon'];
$lat = $_GET['lat'];

$busStopRequest = $_GET['busstops'];


if ($busStopRequest == 1){

	sendBusStops($coll);
}


if ($initial == 1){ //first load, getting bus list

	initialBusList($destName, $coll);

}

if ($initial == 0){ //not first load

	if ($stopIDSelect == 0){
		
		
		findNearBus($destName, $coll, $busAPIkey, $lon, $lat); //auto find by location first

	
	}
	
	else {
	
		findBusByID($destName, $coll, $busAPIkey, $stopIDSelect); //if user selected a bus stop goes here

	}
}



function initialBusList($destName, $coll){

	
	$cursor = $coll->find(Array('destname' => $destName));
	
	$busStopsList = packageBusList($cursor);
		
	jsonPackage($busStopsList);
	
}



function findBusByID($destName, $coll, $busAPIkey, $stopIDSelect){


	$cursor = $coll->find(Array('destname' => $destName));
	
	$busStopsList = packageBusList($cursor);
	
	whereBus($busAPIkey, $busStopsList, $stopIDSelect);
	
	
	
}


function findNearBus($destName, $coll, $busAPIkey, $lon, $lat){
	  	
	
	$lonlat = array(floatval($lon), floatval($lat));

	$cursor = $coll->find(Array('loc' => Array('$nearSphere' => $lonlat), 'destname' => $destName));

	$busStopsList = packageBusList($cursor);

	whereBus($busAPIkey, $busStopsList);
			
}

	
	
	

//route requests from drop list stop too
function whereBus($busAPIkey, $busStopsList, $stopIDSelect){

		
	if ($stopIDSelect == null){
	
		$stopIDSelect = $busStopsList[0]['stopID'];
	}

	

	$busStatusAll = array();
	
//	echo 'http://bustime.mta.info/api/siri/stop-monitoring.json?key='.$busAPIkey.'&MonitoringRef='.$stopID.'&LineRef=B61';
	
	$busReturn = file_get_contents('http://bustime.mta.info/api/siri/stop-monitoring.json?key='.$busAPIkey.'&MonitoringRef='.$stopIDSelect.'&LineRef=B61');
	
	if ($busReturn == null){
	
		echo "The MTA Bus Time website seems to be down...";
	
	}
	
	//var_dump($busReturn);
		
	$busReturn = json_decode($busReturn);
	
	
	//var_dump($busReturn);
			
	$z = $busReturn->Siri->ServiceDelivery->StopMonitoringDelivery[0]->MonitoredStopVisit;
	
	//var_dump($z);
	
	$breaker = 0;
	
	//$last_item = end($z);
	
	
	//$last_item = sizeof($z);
	
	//echo $last_item;
	
	$numItems = count($z);
	$i = 0;
		
	
	foreach($z as $y){
	
		
			$x = $y->MonitoredVehicleJourney;
						
			$destName = $x->DestinationName;
			
			$busLon = $x->VehicleLocation->Longitude;
			$busLat = $x->VehicleLocation->Latitude;
			
			$distance = $x->MonitoredCall->Extensions->Distances->PresentableDistance;
			$stopsLeft = $x->MonitoredCall->Extensions->Distances->StopsFromCall;
			$stopName = $x->MonitoredCall->StopPointName;
			
			$busID = $x->VehicleRef;
			
			//var_dump($x);
			

			
			$busLoc = array($busLon,$busLat);
			
			
			$busStatus = array(
			    'stopID'=>$stopIDSelect,
			    'stopName'=>$stopName,
			    'destName'=>$destName,
			    'loc'=>$busLoc,
			    'distance'=>$distance,
			    'stopsleft'=>$stopsLeft,
			    'busID'=>$busID
			);
			
							
			array_push($busStatusAll, $busStatus);
			
			
			if(++$i === $numItems) { //on last loop do this k thx
			
		    	array_push($busStatusAll, $busStopsList);

			
				jsonPackage($busStatusAll);
		    	
		 	}
					
		}
}
	
	
	
function jsonPackage($busStatusAll){ //broadcasting JSON
	
	$busStatusAll = json_encode($busStatusAll);

	print_r($busStatusAll);
	

}

	


	

function packageBusList($cursor){

	$stopID;
	
	$busStopsList = array();
					
	$cursor = iterator_to_array($cursor);

	//GATHER CURSOR, SEND ALONG NAME AND BUS STOP ID FORM DROP DOWN FORM
	
	$i = 0;
	$len = count($cursor);
		
	foreach($cursor as $x){ 
	
		$stopID = $x['stopID'];
		$stopName = $x['name'];
		$dest = $x['destname'];
		
		$busStopInfo = array(
				    'stopID'=>$stopID,
				    'stopName'=>$stopName,
				    'dest'=>$dest
		);
		
		array_push($busStopsList, $busStopInfo);
		
			
		$i++;

		if ($i == $len - 1) { //do this on last loop
							
			return $busStopsList;

		}
	}
}



function sendBusStops($coll){

	$cursor = $coll->find();
	
	$cursor = iterator_to_array($cursor);
	
	jsonPackage($cursor);



}




?>