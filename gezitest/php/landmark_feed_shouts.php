<?php

/**
 * landmark_feed_shouts.php
 *
 * Displays list view of most recent comments from landmarks currently on map.
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


if (isset($_POST['data'])) {

    $landmarks = $_POST['data'];
    $landmarks = stripslashesDeep($landmarks);

    $counter = 1;


    foreach ($landmarks as $v) {

        foreach ($v as $val) {

            if ($val['stats'] !== null) {

                if ($val['name'] == "flora") {

                    continue;

                } elseif (isset($val['feed'])) {

                    foreach ($val['feed'] as $value) {

                        $idVal = "'" . $val['_id']['$id'] . "'";

                        echo '<div style="width:100%; margin-left: -7px; margin-top: 42px; margin-bottom: 5px;" onclick="landmarkWindow(' . $idVal . ')">';

                        $num = rand(1, 3);

                        echo '<img src="assets/images/people/person' . $num . '.png" style="float:left; max-width:42px; margin-left: 8; margin-right:11;"/>';

                        echo '<div id="innertext" style=" width:348px;  height:39;  margin-left: 12; margin-top:1; margin-bottom:8">';

                        $name = stripslashes($value['name']);

                        echo "<p5><span style='color:#7f275b'></span>" . $name . "</p5>";  //plug into CSS here...

                        $descrip = stripslashes($value['words']);

                        echo "<p style='margin-top:2px;'>" . $descrip . "</p>";

                        echo '</br><hr></div></div>';
                    }
                }
            }
        }
    }

    unset ($v, $val, $value); // remove lingering foreach() values from memory

}


function stripslashesDeep($value)
{
    $value = is_array($value)
        ? array_map('stripslashesDeep', $value)
        : stripslashes($value);

    return $value;
}
