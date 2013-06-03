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
	
	$type = 'landmarks';


	$collection = $db->$type;
	
	
	
	


	$landmarkID = $_POST['data'];
	
	
	
	$landmarkID = preg_replace("/[^a-zA-Z0-9\s]/", "", $landmarkID); 	
	

		
	$objID = new MongoId($landmarkID);
		


	$counter = 1;
	
	
	
	$cursor = $collection->find(array("_id"=>$objID)); //finding all public maps

	
	
	$cursor->sort(array('_id' => -1));  //sort maps by creation, newest first

	
	$cursor = iterator_to_array($cursor);
	


	foreach($cursor as $i){
	
			
		echo '<div id="frame" style="padding-left:13px; padding-right:13px;">';
		
		echo '<div style="width:353; height:5; background-color:#7f275b; top:54;"></div>';

		
		echo '<div id="backbutton" style="position: relative; width:73px; background-image:url(/images/back.png); height:30px; top:7;" onclick="$(feedDiv).hide(); map.closePopup();" ></div>';
		
		echo '<img style="float:right; padding-left:10px; max-width:125px; margin-top: -19px; position:relative;"src="/images/'.$i['stats']['avatar'].'"/></br>';
		
		echo '<div id="text box" style="margin-top:-11">';
		
		echo "<p><b>".$i['name']."</b></p>";  //plug into CSS here...
		echo "<p>".$i['description']."</p>";
		
		
		echo '</div>';
		
		echo '<div style="width:353; height:5; background-color:#7f275b; position: relative; top:83;"></div>';

		
		echo '<div id="singlefeed" style="margin-top:99px; ">';
	
		$result = array_reverse($i['feed']); //faking creation date reverse for now


		foreach($result as $c){
		

			if ($c['words'] !== null){

				
					echo '<div style="width:100%; margin-left: -10px; margin-top: 42px; margin-bottom: 5px;">';
				
					
					$num = rand(1, 3);
					
					echo '<img src="/images/people/person'.$num.'.png" style="float:left; max-width:42px; margin-left: 8; margin-right:11;"/>';//
					
					echo '<div id="innertext" style=" width:348px;  height:39;  margin-left: 12; margin-top:1; margin-bottom:8">';
					
					$name = stripslashes($c['name']);
					
					echo "<p5><span style='color:#7f275b'></span>".$name."</p5>";  //plug into CSS here...
					
					$descrip = stripslashes($c['words']);
					

					echo "<p style='margin-top:2px;'>".$descrip."</p>";
					
					
										
					echo '</br><hr></div></div>';

				
			}

		}
		
		if ($c['words'] == null){
		
			echo '<p>No Comments yet... :(</p>';
		
		}

		
		echo '</div>';

	}

	echo '</div>';



?>
	



