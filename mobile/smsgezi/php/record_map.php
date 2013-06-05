
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

	//if $_POST['landmark']=='buildings', etc. to pass correct landmark value
	
	$type = 'maps';


	$collection = $db->$type;
	

if($_POST['mapname']) {

    
	//------ SORTING OUT FORM INPUTS ------//
	
	$mapName = $_POST['mapname'];
	
	$mapDescrip = $_POST['mapdescrip'];
	
	$admin = $_POST['admin'];
		
	
	if($_POST['openedit'] == 'Yes'){
	    $openEdit = 1;
	}
	
	else{
	    $openEdit = 0;
	}

	if($_POST['hidden'] == 'Yes'){
	    $hidden = 1;
	}
	else{
	    $hidden = 0;
	}
	
	if($_POST['scavenger'] == 'Yes'){
	    $scavengerHunt = 1;
	}
	else
	{
	    $scavengerHunt = 0;
	}
	
	//----------------------------------//
	
	$mapType = mapTypeProcess($openEdit, $hidden, $scavengerHunt);
	


	//------ Map Stats -----------//
	
	$avatar = $mapType.'.png'; //avatar based on user selection
	$expires = 'never';


	
	$stats = array(	
		'expires'=>$expires,
		'avatar'=>$avatar,
		'level'=>1,
		'reputation'=>0,
		'likes'=>0,
		'buzz'=>0,
		'scavenger'=>$scavengerHunt
	);
	
	
	
	//---------- Landmarks on Map --------//
	
	$landmarks = array(

	);

	
	//---------- Permissions --------//
	
	$viewers = array();
	$admins = array();
	//hidden = not on global map aggregation
	
	$permissions = array(
		'hidden' => $hidden,
		'viewers' => $viewers,
		'openedit' => $openEdit,
		'admins' => $admins	
	);
	
	//------------------------------//


			
	    //------ MONGO DB ESCAPE STRING -------//
	   /* 
		$pattern = '$';
		$replacement = '\$';
		echo preg_replace($pattern, $replacement, $description); 
		*/
		//------------------------------------//



	   	//----Map JSON Object------//
						
		$map = array(
		 
		    'name'=>$mapName,
		    'description'=>$mapDescrip,
		    'landmarks'=>$landmarks,
		    'stats'=>$stats,
		    'permissions'=>$permissions

		);
		
		//---------------------------//
		

		insertMap($map,$collection);	
	    
	    
	}



	
	function insertMap($map,$collection){
			
		$safe_insert = true;
		
		$collection->insert($map,$safe_insert);
		
		echo "Map Created!";
		


	}

	


	function mapTypeProcess($openEdit, $hidden, $scavengerHunt){
	
		//yeah, this wasn't designed so well for scaling...
	
		if( ($openEdit == 1) && ($hidden == 1) && ($scavengerHunt == 1) ){
			return "openhiddenhunt";
		}
		
		
		else if( ($openEdit == 0) && ($hidden == 0) && ($scavengerHunt == 0) ){
	
			return "closedpublicmap";

		}
		
		else if( ($openEdit == 1) && ($hidden == 0) && ($scavengerHunt == 0) ){
		
			return "openpublicmap";
		}
		
		else if( ($openEdit == 0) && ($hidden == 1) && ($scavengerHunt == 0) ){
		
			return "closedhiddenhunt";		
		
		}
		
		else if( ($openEdit == 0) && ($hidden == 0) && ($scavengerHunt == 1) ){
		
			return "closedpublichunt";		
		
		}
		
		else if( ($openEdit == 1) && ($hidden == 1) && ($scavengerHunt == 0) ){
		
			return "openhiddenmap";		
		
		}
		
		else if( ($openEdit == 0) && ($hidden == 1) && ($scavengerHunt == 1) ){
		
			return "closedhiddenhunt";		
		
		}
		
		else if( ($openEdit == 1) && ($hidden == 0) && ($scavengerHunt == 1) ){
		
			return "openpublichunt";		
		
		}
		
		else {
		
			return "nothing";
		
		}

	
	
	}



?>