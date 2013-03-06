<?php

/**
 * search_results_feed.php
 *
 * Displays list view of landmarks from search results.
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


$counter = 1;

if (isset($_POST['data'])) {
    $searchResult = $_POST['data'];
    $searchResult = stripslashesDeep($searchResult);
    // var_dump($searchResult);
    // echo '<br /><br />';


    print_r($searchResult);

    echo '<br /><br />';






}

/*
if (isset($_POST['data'])) {

    $landmarks = $_POST['data'];
    // $landmarks = stripslashesDeep($landmarks);

    foreach ($landmarks as $v) {

        foreach ($v as $val) {

            if ($val['stats'] !== null) {

                if ($val['name'] == "flora") {

                    continue;

                } else {

                    $idVal = "'" . $val['_id']['$id'] . "'";

                    echo '<div style="width:97%; margin-left: -13px; margin-top: 15px; margin-bottom: 15px;">';
                    echo '<div style="cursor:pointer; margin-left:7px;" onclick="landmarkWindow(' . $idVal . ')">';
                    echo '<img src="images/' . $val['stats']['avatar'].'"style="float:left; max-width:42px; margin-left: 8;"/>';

                    if (isset($val['feed'])) {

                        echo '<div id="innertext" style=" width:286px;  height:65; float:left; margin-left: 7; margin-top:0; margin-bottom:42">';

                    } else {

                        echo '<div id="innertext" style=" width:286px;  height:65; float:left; margin-left: 7; margin-top:0; margin-bottom:10">';

                    }

                    $name = stripslashes($val['name']);
                    echo "<p5><span style='color:#7f275b'> " . $counter . "</span> . " . $name . "</p5>";

                    $descrip = stripslashes($val['description']);
                    echo "<p style='margin-top:2px;'>" . $descrip . "</p>";

                    if (isset($val['feed'])) {

                        if (!isset($val['stats']['time']['start']['sec'])
                            || ($val['stats']['time']['start']['sec'] == "0")
                        ) {
                            $result = array_reverse($val['feed']);

                            echo "<img src='images/comment.png'/><p4 style='margin-bottom:7px;'>" . $result[0]['words'] . "</p4>";
                        }
                    }

                    // display time info if it exists
                    if (isset($val['stats']['time']['start']['sec'])
                        && ($val['stats']['time']['start']['sec'] !== "0")
                    ) {

                        echo "<p><b>Start:</b> " . date("H:i m-d", $val['stats']['time']['start']['sec']);
                        echo "     <b>End:</b> " . date("H:i m-d", $val['stats']['time']['end']['sec']) . "</p>";

                    }

                    echo '</div></br><hr></div></div>';

                    $counter++;
                }
            }
        }
    }

    unset ($v, $val); // remove lingering foreach() values from memory

    echo '</div>';
}
*/


function stripslashesDeep($value)
{
    $value = is_array($value)
        ? array_map('stripslashesDeep', $value)
        : stripslashes($value);

    return $value;
}
