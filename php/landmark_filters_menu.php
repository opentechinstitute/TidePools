<?php

require('tidepools_variables.php');

foreach ($landmarkTypesFilters as $v) {
    echo '
        <div id="'. $v . '" class="filter" style= "width: 49px; height: 49px;
            position:relative; display:inline-block; margin-bottom:6px;
            z-index:1; cursor:pointer;"
            onclick="reBoundFilter(\'' . $v . '\'); select(\'' . $v . '\');">
            <img src="images/filter_' . $v . '.png"
            width="49" height="49" />
        </div>
    ';
};
