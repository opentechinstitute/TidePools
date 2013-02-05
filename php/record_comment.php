<?php

/**
 * record_comment.php
 *
 * Records comments to Mongo database via form submission.
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


    if($_POST['description']) {

        //------ SORTING OUT FORM INPUTS ------//

        $name = $_POST['name'];
        $words = $_POST['description'];
        $commenter = $_POST['userID']; //who said that thing?

        $mongoCommentID = new MongoID();


        //----Landmark JSON Object------//

        $comment = array(

            'name'=>$name,
            'words'=>$words,
            'likes'=>0,
            'userID'=>$commenter,
            'mongoCommentID'=>$mongoCommentID,
        );

        //---------------------------//

        updateLandmark($comment, $collection);

    }

        // disconnect from database
        $m -> close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server - is the "mongo" process running?');
} catch (MongoException $e) {
    die('Error: ' . $e -> getMessage());
}


    function updateLandmark($comment, $collection){

        $landmarkID = $_POST['landmarkID'];

        $landmarkID = preg_replace("/[^a-zA-Z0-9\s]/", "", $landmarkID);
        $objID = new MongoId($landmarkID);
        $newdata = array('$push' => array("feed" => $comment));

        if($collection -> update(array("_id" => $objID), $newdata)) {

            echo "success";
        }
    }
