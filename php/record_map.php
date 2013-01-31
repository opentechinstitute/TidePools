<?php
/**
 * record_map.php
 * 
 * Records map layers to Mongo database via form submission.  
 * 
 * 
 *.---.      .                    .     
 *  |  o     |                    |     
 *  |  .  .-.| .-. .,-.  .-.  .-. | .--.
 *  |  | (   |(.-' |   )(   )(   )| `--.
 *  '-' `-`-'`-`--'|`-'  `-'  `-' `-`--' v0.2
 
 *  Copyright (C) 2012-2013 Open Technology Institute <tidepools@opentechinstitute.org>
 *      Lead: Jonathan Baldwin
 *      Contributors: Lisa J. Lovchik
 *      This file is part of Tidepools <http://www.tidepools.co>

 *  Tidepools is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  Tidepools is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with Tidepools.  If not, see <http://www.gnu.org/licenses/>.
 */


require('tidepools_variables.php');

// if $_POST['landmark']=='buildings', etc. to pass correct landmark value
if ($_POST['mapname']) {
    
    //------ SORTING OUT FORM INPUTS ------//
    $mapName = $_POST['mapname'];
    $mapDescrip = $_POST['mapdescrip'];

    $admin = (isset($_POST['admin']) ? $_POST['admin'] : null);
    $openEdit = (isset($_POST['openedit']) ? $_POST['openedit'] : 0);
    $hidden = (isset($_POST['hidden']) ? $_POST['hidden'] : 0);
    $scavengerHunt = (isset($_POST['scavenger']) ? $_POST['scavenger'] : 0);

    //----------------------------------//
    
    $mapType = mapTypeProcess($openEdit, $hidden, $scavengerHunt);

    //------ Map Stats -----------//
    $avatar = $mapType . '.png'; //avatar based on user selection
    $expires = 'never';

    $stats = array( 
        'expires' => $expires,
        'avatar' => $avatar,
        'level' => 1,
        'reputation' => 0,
        'likes' => 0,
        'buzz'=> 0,
        'scavenger' => $scavengerHunt,
    );
    
    //---------- Landmarks on Map --------//
    $landmarks = array();

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
       
    //----Map JSON Object------//
    $map = array(
        'name' => $mapName,
        'description' => $mapDescrip,
        'landmarks' => $landmarks,
        'stats' => $stats,
        'permissions' => $permissions,
    );
    //---------------------------//


    // connect to database and access maps
    try {
        // open connection to MongoDB server
        $m = new Mongo('localhost');

        // access database
        $db = $m -> selectDB($DBname);
         
        // get 'maps' collection
        // if $_POST['landmark']=='buildings', etc. to pass correct landmark value
        $type = 'maps';
        $coll = $db -> $type;


        //------------------------------//
            
        //------ MONGO DB ESCAPE STRING -------//
        /* 
            $pattern = '$';
            $replacement = '\$';
            echo preg_replace($pattern, $replacement, $description); 
        */

        //------------------------------------//
     

        // create new map
        insertMap($map, $coll);    
        echo 'Map Type: ' . $mapType . '\n';

        // disconnect from database 
        $m -> close();
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server - is the "mongo" process running?');
    } catch (MongoException $e) {
        die('Error: ' . $e -> getMessage());
    }
}


function insertMap($map, $coll)
{
    //$safe_insert = true;
    $coll -> insert($map);
    echo 'Map Created!';
}


// set map type to display according to chosen options
function mapTypeProcess($openEdit, $hidden, $scavengerHunt)
{
    $mapType = 'nothing';

    if ($openEdit == 0) {
        $mapType = 'closed';
    } elseif ($openEdit == 1) {
        $mapType = 'open';
    }
    if ($hidden == 0) {
        $mapType .= 'public';
    } elseif ($hidden == 1) {
        $mapType .= 'hidden';
    }
    if ($scavengerHunt == 0) {
        $mapType .= 'map';
    } elseif ($scavengerHunt == 1) {
        $mapType .= 'hunt';
    }

    return $mapType;
}
