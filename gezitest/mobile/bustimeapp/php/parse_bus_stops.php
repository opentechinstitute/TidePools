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


	$collection = $db->$type;
	
	
	//http://bustime.mta.info/api/siri/stop-monitoring.json?key=babec670-71fc0d6a-7352d870-d81a7a2b&OperatorRef=MTA%20NYCT&MonitoringRef=901055&LineRef=B61
	
	
	$json = file_get_contents('./busstops.json', true);
	
	
	$json = json_decode( $json );	
	
	//var_dump($json);
	
	$breaker = 0;
	
	foreach($json as $x){
	
		//var_dump($x);
		
		$stopID = $x->stop_id;
		$stopName = $x->stop_name;
		$stopLat = $x->stop_lat;
		$stopLon = $x->stop_lon;
		
		
		//echo $stopID;
		
		//echo "<br><br>";

		
		
		$busReturn = file_get_contents('http://bustime.mta.info/api/siri/stop-monitoring.json?key=babec670-71fc0d6a-7352d870-d81a7a2b&OperatorRef=MTA%20NYCT&MonitoringRef='.$stopID.'&LineRef=B61');
		
		$busReturn = json_decode($busReturn);
				
		$a = $busReturn->Siri->ServiceDelivery->StopMonitoringDelivery;
		
		$a = serialize($a);
		
		if (preg_match("/No such stop/", $a)){
			//echo "NONE";
		}
		
		else {
		
			//echo "TRUE********";
			
			$z = $busReturn->Siri->ServiceDelivery->StopMonitoringDelivery[0]->MonitoredStopVisit;
		
			//var_dump($z);
			
			echo "<br><br>";	
			
			
			foreach($z as $y){
			
				$x = $y->MonitoredVehicleJourney;
			
				//var_dump($y);
				
				//print_r($y->MonitoredVehicleJourney->PublishedLineName);
				
				//echo $y->PublishedLineName;
				
				$destName = $x->DestinationName;
			//	echo "<br>";
	
				/*

				if (preg_match("/B61/", $y->MonitoredVehicleJourney->PublishedLineName)){
			
				}
*/
				
				//echo "<br><br>";	
				
				
				dataOutput($destName, $stopID, $stopName, $stopLat, $stopLon, $collection);
				
				break;
				
				
				
			}
		
			
			/*
$breaker++;
			

			if ($breaker == 10){
				
				break;
	
			}
*/
			
			sleep(2);
	
			

		}
		
		
	/*
	$stopID = 306064;
		
		$busReturn = file_get_contents('http://bustime.mta.info/api/siri/stop-monitoring.json?key=babec670-71fc0d6a-7352d870-d81a7a2b&OperatorRef=MTA%20NYCT&MonitoringRef='.$stopID.'&LineRef=B61');
		
		$busReturn = json_decode($busReturn);
*/
		
		
	//	var_dump($busReturn->Siri->ServiceDelivery->StopMonitoringDelivery);
	

	}
	
	
	function dataOutput($destName, $stopID, $stopName, $stopLat, $stopLon, $collection){
	
		
		$loc = array($stopLon,$stopLat);

		
		$busStop = array(
		 
		    'name'=>$stopName,
		    'destname'=>$destName,
		    'stopID'=>$stopID,
		    'loc'=>$loc
		);
		
		
		insertBus($busStop, $collection);
	
	}
	
	
	
	
	
	
	
		/*		
	
	$time = array(
	
		'type' => $timeType,
		'start' => $timeStart,
		'end' => $timeEnd,
		'arriving' => 0

	);


	    
	   	//----Landmark JSON Object------//
						
		$landmark = array(
		 
		    'name'=>$_POST['name'],
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
		

		insertLandmark($landmark,$collection, $collection2);	
	    
	    
	*/
	
	
	//STORE
	
	//bus ID, lat/long, destination 



	
	function insertBus($busStop,$collection){
			
		$safe_insert = true;
		
		$collection->insert($busStop,$safe_insert);
		
		$collection->ensureIndex(array("loc" => "2d"));
		
				


	}
	
	
	



?>