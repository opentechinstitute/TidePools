<?php

/**
 * search.php
 *
 * Searches database for landmarks by keyword for name, description, or landmark
 * type and orders them by relevance
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

// sanitize entered search term(s)
// $sanTerm = 'memory';
$sanTerm = substr($_POST['searchTerm'], 0, 160);
$sanTerm = strip_tags($sanTerm);
$origTerm = $sanTerm;
$sanTerm = str_replace(":", " ", $sanTerm);
$sanTerm = preg_replace('/\s+/', ' ', $sanTerm); // remove extra spaces
$sanTerm = strtolower($sanTerm);

$termsArray = explode(' ', $sanTerm);

// REMEMBER TO ADD MULTIPLE TERM ROUTINES BACK IN (E.G., FROM TESTME5.PHP)


$counter = 1;

$maps = (isset($_POST['mapIDs']) ? $_POST['mapIDs'] : null);

// temporary to create array from string on future map layer iteration
$maps = explode( ',', $maps );

$searchResult = (isset($_POST['data']) ? $_POST['data'] : null);
$searchResult = stripslashesDeep($searchResult);

$searchKey = array('name', 'description');
$idArray = array();
$typeMatches = array();
$otherMatches = array();
$typeResults = array();
$otherResults = array();
$results = array();
$currentID = '';


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

    // group search types together and prepare to query database
    $query = array(
        '$or' => array(
            array('name' => new MongoRegex('/' . $sanTerm . '/i')),
            array('description' => new MongoRegex('/' . $sanTerm . '/i')),
        )
    );


    // prepend search by landmark type, if applicable
    $getSingular = array_keys($landmarkTypesPlural, $sanTerm);

    // prepend search by landmark type, if applicable
    if ($getSingular) {
        $sanTerm = $getSingular[0];
        $landmarkSearch = TRUE;
    } else {
        $landmarkSearch = in_array($sanTerm, $landmarkTypes);
    }

    if ($landmarkSearch) {
        array_unshift($query['$or'], array('type' => $sanTerm));
        array_unshift($searchKey, 'type');
    }

    $cursor = $collection -> find($query); // query database
    $cursor = iterator_to_array($cursor); // convert mongodb object to array

    // if applicable, separate landmark type matches from others
    if ($landmarkSearch) {
        foreach ($cursor as $v) {
            $currentID = (string) $v['_id'];

            if ($v['type'] == $sanTerm) {
                $countTypeMatches = countMatches($v, $sanTerm);
                $typeResults[0]["$currentID"] = $cursor["$currentID"];
                $typeMatches[] = $countTypeMatches;
            } else {
                $otherResults[0]["$currentID"] = $cursor["$currentID"];
                $otherMatches[] = countMatches($v, $sanTerm);
            }
        }

        unset ($v, $val); // remove lingering foreach() values from memory

        if ($typeMatches) {
            array_multisort($typeMatches, SORT_NUMERIC, SORT_DESC, $typeResults[0]);
        }
        if ($otherMatches) {
            array_multisort($otherMatches, SORT_NUMERIC, SORT_DESC, $otherResults[0]);
        }

        $results = array_merge($typeResults, $otherResults);

    } else {
        foreach ($cursor as $v) {
            $currentID = (string) $v['_id'];
            $results[0]["$currentID"] = $cursor["$currentID"];

            $otherMatches[0]["$currentID"] = countMatches($v, $sanTerm);
        }

        unset ($v, $val); // remove lingering foreach() values from memory
    }



// ---------------------------------------------------------------------- //

/*  TEMPORARY DISABLING GEOLOCATION SEARCH - THIS WILL HAVE ITS OWN BUTTON LATER

    $key = $m -> $_POST['searchKey'];

    // prepare to query DB
    switch ($key) {
        case "name":
        case "description":
        case "type":
            // set up name, description, and type searches by keyword
            $query = array(
                "$key" => new MongoRegex(
                    '/' . $sanTerm . '/i'
                )
            );
            // echo '<b>Locations with "' . $sanTerm . '" in their '
            //    . $key . '</span></b><br /><br />';

            break;
        case "loc":
            // get starting point and radius
            // starting point is hard-coded into index.html for now
            // later, this will be handled by the map interface
            $sanTerm = (float) $sanTerm;
            $distanceUnits = $_POST['distUnits'];

            if ($distanceUnits == 'mi') {
                // ~ 69 mi per 1 degree latitude or longitude
                $maxDistance = (float) $sanTerm / 69;
            }
            else {
                $distanceUnits = "km";
                // ~111 km per 1 degree latitude or longitude
                $maxDistance = (float) $sanTerm / 111;
            }

            $lon = (float) $_POST['lon'];
            $lat = (float) $_POST['lat'];
            $lonlat = array($lon, $lat);

            // echo '<b>Locations within ' . $sanTerm . ' '.
            //    $distanceUnits . ' of ' . $lon .  ' longitude, ' .
            //    $lat . ' latitude</b><br />';


            // set up location search as geospatial indexing search
            $query = array(
                "$key" => array(
                    '$near' => $lonlat,
                    '$maxDistance' => $maxDistance
                    )
                );
            break;
        case "time":
            // to be added in the future
            break;
    }


    var_dump($cursor);

    display search results
    echo '<b>' . $cursor -> count() . ' item(s) found.</b><br /><br />';

    convert search results to JSON format
    $final = array();

    $final = iterator_to_array($cursor);
    $final = array_push($final, $cursor);
    echo '<br /><br />';
*/


/* TAKE THIS DISPLAY OUT FOR NOW

    // iterate through the result set and print each document
    foreach ($cursor as $v) {
        echo 'Name: ' . $v['name'] . '<br />';
        echo 'Time: ' . $v['stats']['time']['start'] . ' - '
            . $v['stats']['time']['end'] . '<br />';
        echo 'Location: ' . $v['loc'][0] . ', ' . $v['loc'][1] . '<br />';
        echo 'Description: ' . $v['description'] . '<br />';
        echo 'Type: ' . $v['type'] . '<br />';
        echo '<br />';
    }
*/

// ---------------------------------------------------------------------- //


    // ---- ADD ROUTINE HERE TO TRUNCATE AFTER MAX RESULTS = 50 ---- //


    $results = json_encode($results);
    print_r($results);

    // disconnect from server
    $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
    die('Error: ' . $e -> getMessage());
}


function stripslashesDeep($value)
{
    $value = is_array($value)
        ? array_map('stripslashesDeep', $value)
        : stripslashes($value);

    return $value;
}

function countMatches($thisLandmark, $term)
{
    $count = 0;

    if (stripos($thisLandmark['name'], $term) !== FALSE) {
        $count++;
    }
    if (stripos($thisLandmark['description'], $term) !== FALSE) {
        $count++;
    }

    return $count;
}
