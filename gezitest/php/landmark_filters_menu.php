<?php

/**
 * landmark_filters_menu.php
 *
 * Dynamically generates landmark filters menu, based on array stored in
 * tidepools_variables.php
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

foreach ($landmarkTypesFilters as $v) {
    echo '
        <div id="'. $v . '" class="filter" style= "width: 49px; height: 49px;
            position:relative; display:inline-block; margin-bottom:6px;
            z-index:1; cursor:pointer;"
            onclick="reBoundFilter(\'' . $v . '\'); select(\'' . $v . '\');">
            <img src="assets/images/filter_' . $v . '.png"
            width="49" height="49" />
        </div>
    ';
};
