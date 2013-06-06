<?php 

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

// TOTALLY not done...


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

	//if $_POST['landmark']=='buildings', etc. to pass correct landmark value
	
	$type = 'landmarks';
	$collection = $db->$type;





//CHECK HERE FOR JQUERY IDLE TIME TO DO A rebound(); or a refresh (if longer time) http://www.erichynds.com/jquery/a-new-and-improved-jquery-idle-timeout-plugin/
// INTEGRATE ONTO MAP WITHOUT CLICK! ---> flowers and plants AROUND landmark embedded on map, with more activity




	
	echo "</br>";
	
	$cursor = $collection->find();
	
	$cursor = iterator_to_array($cursor);

	
	foreach($cursor as $i){
	
			//echo $i;
			
			//echo[$i]['name'];
			
			
			foreach($i['stats'] as $w){

				if (in_array("time", $w)) {
				
		
					timeExists($w);

		
				}

								
			}
			
	}
					
			


	
	function timeExists($w){
	
			
						
			$start = $w['start'];
			$end = $w['end'];
			

			//----- Start process -----//
			$start = (string)$start;
			
			$pattern = "/0.00000000 /";
			$replacement = "";
			
			$start = preg_replace($pattern, $replacement, $start);
		
			echo $start;
			
			if ($start == null){
				return;
			}
			

			
			
			//------ End process -----//
			
			$end = (string)$end;
			
			$pattern = "/0.00000000 /";
			$replacement = "";
			
			
			$end = preg_replace($pattern, $replacement, $end);
			
			$end1 = intval($end);
			$start1 = intval($start);
			
			if ($end == null){
				return;
			}
			
			//----------------------//	
			
			
			$now = strtotime("now");

			$start = intval($start);
			$end = intval($end);
			
			//var_dump($start);

			
			
			if(dateRange($start, $end, $now)){
			
			  echo 'In range</br>';
			    
			} else {
			
			  echo 'Not in range</br>';
			    
			}
			
	}
	
	
	
		
	function dateRange($start, $end, $now){
	
	  return (($now >= $start) && ($now <= $end));
	
	}
		
	
	
	
	// find dates between 1/15/2010 and 1/30/2010
	//$collection->find(array("ts" => array('$gt' => $now, '$lte' => $end)));




	
	
	
	/*
	//loop of landmarks with time, timewhen =  
	
	
	$timeWhen = new MongoDate(strtotime($timeWhen)); 
	
	
	$start = new MongoDate(strtotime("2010-01-15 00:00:00"));
$end = new MongoDate(strtotime("2010-01-30 00:00:00"));

// find dates between 1/15/2010 and 1/30/2010
$collection->find(array("ts" => array('$gt' => $start, '$lte' => $end)));






//checking for upcoming times, adjusting status'

// checing for amount of comments, issuing levels


// if no activity, decrease visibility

	$js = "function() {
	  var date = new Date(this.datecolumn);
	  return date.getHours() >= 2 && date.getHours() <= 23;
	}";
	
	$cursor = $collection->find(array('$where' => $js));


	$cursor = iterator_to_array($cursor);
	
	var_dump($cursor);


*/






?>