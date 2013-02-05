<?php

/**
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

try {
    // open connection to MongoDB server
    $m = new Mongo('localhost');

    // access database
    $db = $m -> selectDB($DBname);


    //------ MONGO DB ESCAPE STRING -------//
    /*
        $pattern = '$';
        $replacement = '\$';
        echo preg_replace($pattern, $replacement, $description);
        */
    //------------------------------------//


    // get 'people' collection
    //if $_POST['landmark']=='buildings', etc. to pass correct landmark value
    $type = 'people';
    $collection = $db -> $type;
    $marktype = $_POST['marktype'];


    //------ Landmark Stats -----------//

    $avatar = $marktype.'.png'; //avatar based on user selection
    $expires = 'never';
    $checkIn = array();
    $imGoing = array();

    $stats = array(
        'expires' => $expires,
        'avatar' => $avatar,
        'level' => 1,
        'reputation' => 0,
        'likes' => 0,
        'buzz' => 0,
        'checkIn' => $checkIn,
        'imGoing' => $imGoing,
    );

    //---------- Landmarks Inside Landmark --------//

    $insideStatus = 0;
    $landmarksInside = array();

    $insides = array(
        'insideAlready' => $insideStatus,
        'landmarksInside' => $landmarksInside,
    );

    //---------- News & Announcements --------//

    $post = array(
        'sticky'=>0,
        'global'=>0,
        'post'=>'...',
        'likes'=>0,
    );

    $feed = array();

    //---------- Permissions --------//

    $viewers = array();
    $admins = array();
    //hidden = not on global map aggregation

    $permissions = array(
        'hidden' => 0,
        'viewers' => $viewers,
        'openedit' => 0,
        'admins' => $admins,
    );

    //------------------------------//


    if($_POST['name']) {

        $name = $_POST['name'];
        $description = $_POST['description'];

        $lat = floatval($_POST['lat']); //converting from string to floats
        $lng = floatval($_POST['lng']); //...^

        $loc = array($lng, $lat);
        $map = 'Map_objectID'; //which map was this posted to? global map or personal map?


        //----Landmark JSON Object------//

        $landmark = array(
            'name'=>$name,
            'description'=>$description,
            'loc'=>$loc,
            'map'=>$map,
            'stats'=>$stats,
            'insides'=>$insides,
            'feed'=>$feed,
            'permissions'=>$permissions,
        );

        //---------------------------//

        insertLandmark($landmark, $collection);
    }

    // disconnect from database
    $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
    die('Error: ' . $e -> getMessage());
}


    function insertLandmark($landmark, $collection){
        //$safe_insert = true;
        $collection -> insert($landmark);
        $collection -> ensureIndex(array("loc" => "2d"));
    }
