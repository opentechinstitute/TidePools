<?php

/**
 * maps_collect.php
 *
 * Finding all map layers visible to the current user.
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


    // get 'maps' collection
    $type = 'maps';

    $collection = $db -> $type;

    //-- NOTE: CHANGE METHOD TO 'POST' IN THE JS, FOR SECURITY REASONS! --//
    $userID = $_POST['userID'];
    //-- CHANGE METHOD ABOVE TO 'POST' IN JS -----------------------------//

    $cursor = $collection -> find(); //finding all public maps

    //this would usually only show public maps and hidden maps owned by user, but there is only one user for now
    //$cursor = $collection -> find(array('permissions' => array('hidden' => 0))); //finding all public maps

    $cursor -> sort(array('_id' => -1));  //sort maps by creation, newest first

    $cursor = iterator_to_array($cursor);
    $cursor = json_encode($cursor);

    print_r($cursor);

    // disconnect from database
    $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
        die('Error: ' . $e -> getMessage());
}
