<?php

require('tidepools_variables.php');

foreach ($landmarkTypes as $v) {
    echo '
        <div id="'. $v . '.png" class="landmark" style="width: 75px;
            height: 75px; position:relative; display:inline-block;
            margin-bottom:6px; z-index:1; cursor:move;"
            onclick="change(' . $v . '.png\');">
            <img src="images/icon_' . $v . '.png"
            width="70" height="70" />
        </div>
    ';
};
