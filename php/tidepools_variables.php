<?php

/**
 * tidepools_variables.php
 *
 * Universal variables for tidepools php functions.
 * Holds name of Mongo collection right now, and arrays used for search and
 * dynamic menu generation.
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


    $DBname = "defaultMap";


    // note: landmark types must be all lower-case letters
    $landmarkTypes = array(
        'event',
        'memory',
        'friend',
        'group',
        'alert',
        'fixthis',
        'food',
        'somethingelse',
        // Note, extra items in 'Filter Landmarks' section:
        //   'freewifi',
        //   'coupons',
        //   'jobs',
    );

    // note: landmark type filters must be all lower-case letters
    $landmarkTypesFilters = array(
        'event',
        'memory',
        'alert',
        'fixthis',
        'freewifi',
        'friend',
        'group',
        'food',
        'coupons',
        'jobs',
        'somethingelse',
    );

    $landmarkTypesPlural = array(
        'event' => 'events',
        'memory' => 'memories',
        'alert' => 'alerts',
        'friend' => 'friends',
        'group' => 'groups',
    );
