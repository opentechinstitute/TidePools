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
	
		
	$landmarkID = $_GET['landmarkID'];
	
	$landmarkID = new MongoID($landmarkID);


	$collection->remove(array('_id'=>$landmarkID));
	
	echo 'success';
	


?>