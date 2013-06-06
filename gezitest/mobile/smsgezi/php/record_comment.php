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
	

		
if($_POST['description']) {

    
	//------ SORTING OUT FORM INPUTS ------//
	
	$name = $_POST['name'];
	
	$words = $_POST['description'];
	
	$commenter = $_POST['userID']; //who said that thing?
	
	
	$mongoCommentID = new MongoID();
	
	
//print_r($landmarkID);	
	
 
   	//----Landmark JSON Object------//
					
	$comment = array(
	 
	    'name'=>$name,
	    'words'=>$words,
	    'likes'=>0,
	    'userID'=>$commenter,
	    'mongoCommentID'=>$mongoCommentID
	    

	);
	
	//---------------------------//
	

	
	updateLandmark($comment, $collection);

	
	
	
}


	function updateLandmark($comment, $collection){ 
	
	
	
		$landmarkID = $_POST['landmarkID'];
		
	
	
		$landmarkID = preg_replace("/[^a-zA-Z0-9\s]/", "", $landmarkID); 
		
		
		$objID = new MongoId($landmarkID);


		$newdata = array('$push' => array("feed" => $comment));
		

		if($collection->update(array("_id"=>$objID), $newdata)){
		
		
			echo "success";
		
		}
		
		

	
	}
	
	




?>