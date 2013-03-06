<?php

/**
 * rebound.php
 *
 * When a user pans or zooms in the mapping interface, this
 * function is called to recalculate visible landmarks in the
 * current window view, for all map layers. It also determines
 * what time-based landmarks are visible based on the current
 * time and each landmarks start and end time.
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

$curr = "";

//remember to query lower right, then upper right
$nelat = floatval($_POST['nelat']);
$nelng = floatval($_POST['nelng']);
$swlat = floatval($_POST['swlat']);
$swlng = floatval($_POST['swlng']);


//-------- IS THERE A FILTER ? ---------//
$filter = (isset($_POST['filter']) ? $_POST['filter'] : null);

//---------------------------------------//

$maps = (isset($_POST['mapIDs']) ? $_POST['mapIDs'] : null);

//fixing boundary to compensate for a bit off screen
$nelat = $nelat + 0.00020;
$nelng = $nelng + 0.00020;
$swlat = $swlat - 0.00020;
$swlng = $swlng - 0.00020;

$box = array(
    array(
        $swlng, $swlat),
    array(
        $nelng, $nelat),
);


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


    // need to query for all map ids, sort which ones are public
    $final = array();

    if ($filter !== null) {

        foreach ($maps as $v) {

            $cursor = $collection -> find(array(
                'loc' => array(
                    '$within' => array(
                        '$box' => $box)
                ),
                'type' => $filter,
                'mapID' => $v)
            );

            $cursor -> sort(array('_id' => -1));  //sort landmarks by creation, newest first
            $cursor = iterator_to_array($cursor);
            array_push($final, $cursor);
        }
    } elseif ($maps != null) {

        foreach ($maps as $v) {

            $cursor = $collection -> find(array(
                'loc' => array(
                    '$within' => array(
                        '$box' => $box)
                ),
                'mapID' => $v)
            );

            $cursor -> sort(array('_id' => -1));
            $cursor = iterator_to_array($cursor);

            foreach ($cursor as $val) {

                $currentID = $val['_id'];
                $currentID = (string) $currentID;

                //---- TIME HANDLER STATIC RIGHT NOW, THESE ITEMS HANDLE TIME -----//
                if ($val['type'] == "event"
                    || $val['type'] == "A"
                    || $val['type'] == "B"
                    || $val['type'] == "C"
                    || $val['type'] == "AUD"
                    || $val['type'] == "154"
                    || $val['type'] == "156"
                    || $val['type'] == "157"
                    || $val['type'] == "1243"
                    || $val['type'] == "2242"
                    || $val['type'] == "BC"
                    || $val['type'] == "E"
                    || $val['type'] == "FG"
                    || $val['type'] == "H"
                    || $val['type'] == "I"
                    || $val['type'] == "J"
                    || $val['type'] == "L"
                    || $val['type'] == "M"
                    || $val['type'] == "north"
                ) {
                    $timeNow = timeExists($val);

                    if ($val['stats']['time']['start'] == "Click Here"
                        || $val['stats']['time']['end'] == "Click Here") {
                            $nestArray = array($currentID => $val);
                            array_push($final, $nestArray);
                    }

                    if ($timeNow == "1") {
                        $nestArray = array($currentID => $val);
                        array_push($final, $nestArray);
                    }
                    if ($timeNow == "0") {
                        //nothing
                    }
                } else {
                    $nestArray2 = array($currentID => $val);
                    array_push($final, $nestArray2);
                }
            }
        }
    }

    // disconnect from database
    $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
    die('Error: ' . $e -> getMessage());
}


$final = json_encode($final);
print_r($final);

unset($v, $val); // remove lingering foreach() values from memory


function checkTime($cursor)
{
    foreach ($cursor as $val) {

        $stringed = (string) $val['stats']['time']['start'];

        if ($stringed !== "0.00000000 0") {
            $curr = "test";
        }

        else {
            return "none";
        }
    }
}


function timeExists($val)
{
    $start = $val['stats']['time']['start'];
    $end = $val['stats']['time']['end'];

    //----- Start process -----//
    $start = (string) $start;

    $pattern = "/0.00000000 /";
    $replacement = "";

    $start = preg_replace($pattern, $replacement, $start);

    //echo $start;

    if ($start == null) {
        //return;
    }
    //------ End process -----//

    $end = (string) $end;

    $pattern = "/0.00000000 /";
    $replacement = "";

    $end = preg_replace($pattern, $replacement, $end);

    $end1 = intval($end);
    $start1 = intval($start);

    if ($end == null) {
        //return;
    }

    //----------------------//

    $now = strtotime("now");

    $start = intval($start);
    $end = intval($end);

    if (dateRange($start, $end, $now)) {
        return "1";
    } else {
        return "0";
    }
}


function dateRange($start, $end, $now)
{
    return (($now >= $start)
            && ($now <= $end));
}
