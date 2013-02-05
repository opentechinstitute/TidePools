<?php

/**
 * landmark_feed_single.php
 *
 * Displays a single landmarks comment feed and general status.
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

    $landmarkID = (isset($_POST['data']) ? $_POST['data'] : null);

    $landmarkID = preg_replace("/[^a-zA-Z0-9\s]/", "", $landmarkID);

    $objID = new MongoId($landmarkID);

    $counter = 1;

    $cursor = $collection -> find(array("_id" => $objID)); //finding all public maps
    $cursor -> sort(array('_id' => -1));  //sort maps by creation, newest first
    $cursor = iterator_to_array($cursor);


    foreach($cursor as $v) {

        echo '<div id="frame" style="padding-left:13px; padding-right:13px;">';
        echo '<div style="width:353; height:5; background-color:#7f275b; top:54;"></div>';

        echo '<div id="backbutton" style="position: relative; width:73px; background-image:url(images/back.png); height:30px; top:7;" onclick="$(feedDiv).hide(); map.closePopup();" ></div>';
        echo '<img style="float:right; padding-left:10px; max-width:125px; margin-top: -19px; position:relative;"src="images/' . $v['stats']['avatar'].'"/></br>';
        echo '<div id="text box" style="margin-top:-11">';

        echo "<p><b>" . $v['name'] . "</b></p>";  //plug into CSS here...
        echo "<p>" . $v['description'] . "</p>";
        echo '</div>';

        echo '<div style="width:353; height:5; background-color:#7f275b; position: relative; top:83;"></div>';

        echo '<div id="singlefeed" style="margin-top:99px; ">';

        $result = array_reverse($v['feed']); //faking creation date reverse for now

        foreach($result as $val) {

            if ($val['words'] !== null) {

                    echo '<div style="width:100%; margin-left: -10px; margin-top: 42px; margin-bottom: 5px;">';
                    $num = rand(1, 3);
                    echo '<img src="images/people/person' . $num . '.png" style="float:left; max-width:42px; margin-left: 8; margin-right:11;"/>';
                    echo '<div id="innertext" style=" width:348px; height:39; margin-left: 12; margin-top:1; margin-bottom:8">';

                    $name = stripslashes($val['name']);

                    echo "<p5><span style='color:#7f275b'></span>" . $name . "</p5>";  //plug into CSS here...
                    $descrip = stripslashes($val['words']);
                    echo "<p style='margin-top:2px;'>" . $descrip . "</p>";
                    echo '</br><hr></div></div>';
            }
        }

        if (!isset($val['words'])) {

            echo '<p>No Comments yet... :(</p>';

        }

        echo '</div>';
    }

    unset ($v, $val); // remove lingering foreach() values from memory

    echo '</div>';

    // disconnect from database
    $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
        die('Error: ' . $e -> getMessage());
}
