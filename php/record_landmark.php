<?php

/**
 * record_landmark.php
 *
 * Records landmarks to Mongo database via form submission.
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


    // get 'landmarks' collection
    $type = 'landmarks';
    $collection = $db -> $type;

    // get 'maps' collection
    $type2 = 'maps';
    $collection2 = $db -> $type2;

    $marktype = $_POST['marktype'];
    $mapID = $_POST['maplist'];
    $landmarkAdmin = $_POST['landmarkAdmin'];

    // $landmarks = (isset($_POST['data']) ? $_POST['data'] : null);

    $timeType = (isset($_POST['timespec']) ? $_POST['timespec'] : null);
    $timeStart = (isset($_POST['startdatetimepicker'])
        ? $_POST['startdatetimepicker'] : null);
    $timeEnd = (isset($_POST['enddatetimepicker'])
        ? $_POST['enddatetimepicker'] : null);

    //---TIME--//
    if ($timeStart == "Click Here" && $timeEnd == "Click Here") {
        $timeStart = 0;
        $timeEnd = 0;
    } else {
        if ($timeStart !== "Click Here") {
            $timeStart = new MongoDate(strtotime($timeStart));
        } else {
            $timeStart = 0;
        }

        if ($timeEnd !== "Click Here") {
            $timeEnd = new MongoDate(strtotime($timeEnd));
        } else {
            $timeEnd = 0;
        }
    }

    $time = array(
        'type' => $timeType,
        'start' => $timeStart,
        'end' => $timeEnd,
        'arriving' => 0,
    );

    //-----//

    //------ Landmark Stats -----------//

    $avatar = $marktype.'.png'; //avatar based on user selection
    $expires = 'never';
    $checkIn = array();
    $imGoing = array();

    $stats = array(
        'time' => $time,
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
        'sticky' => 0,
        'global' => 0,
        'post' => '...',
        'likes' => 0,
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


    if ($_POST['name']) {

        $description = $_POST['description'];

        $lat = floatval($_POST['lat']); //converting from string to floats
        $lng = floatval($_POST['lng']); //...^

        $loc = array($lng, $lat);


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
            'permissions'=>$permissions,
        );

        //---------------------------//

        insertLandmark($landmark, $collection, $collection2);
    }

    // disconnect from database
    $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
        die('Error: ' . $e -> getMessage());
}


    function insertLandmark($landmark, $collection, $collection2) {

        //$safe_insert = true;
        $collection -> insert($landmark);
        $collection -> ensureIndex(array("loc" => "2d"));

        updateMap($landmark['_id'], $landmark['mapID'], $collection2);

    }

    // store landmarks on each map
    function updateMap($landmarkID, $mapID, $collection2) {

        $landmarkID = new MongoID($landmarkID);
        $mapIDObj = new MongoID($mapID);

        $newdata = array('$push' => array("landmarks" => $landmarkID));
        $collection2 -> update(array("_id" => $mapIDObj), $newdata);

    }
