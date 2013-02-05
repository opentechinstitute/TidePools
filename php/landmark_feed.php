<?php 

/**
 * landmark_feed.php
 * 
 * Displays list view of landmarks currently on map. 
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


$landmarks = (isset($_POST['data']) ? $_POST['data'] : null);
$landmarks = stripslashesDeep($landmarks);

$counter = 1;

//THIS is used to display a static message in the landmark feed:
/* echo '<div style="width:351px; height:80px;"> <hr style="border:3px solid #df1c53;" /><img src="/images/announcement.png" style="float:left; margin-left: 8;" /> <div id="innertext" style="width:299px; height:75; float:left; margin-left: 7; margin-top:-5;"><p2>TidePools Social WiFi</p2>  </br> <p style="margin-top:2px; " > Social software for Community WiFi Mesh networks <a href="http://tidepools.co">http://tidepools.co</a></p></div> <hr></div>'; */
        
if ($landmarks != null) {

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


function stripslashesDeep($value)
{
    $value = is_array($value)
        ? array_map('stripslashesDeep', $value) 
        : stripslashes($value);

    return $value;
}
