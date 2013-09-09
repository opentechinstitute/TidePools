/**
 *.---.      .                    .
 *  |  o     |                    |
 *  |  .  .-.| .-. .,-.  .-.  .-. | .--.
 *  |  | (   |(.-' |   )(   )(   )| `--.
 *  '-' `-`-'`-`--'|`-'  `-'  `-' `-`--' v0.2

 *  Copyright (C) 2012-2013 Open Technology Institute <tidepools@opentechinstitute.org>
 *  Lead: Jonathan Baldwin
 *  This file is part of Tidepools <http://www.tidepools.co>

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

/*
  Add the postJSON function to jQuery, which is like getJSON but uses
  HTTP POST instead of HTTP GET.
  All instances of getJSON have been changed to postJSON,
  except for the API_collect function that talks to bustime.mta.info
    -- Juul
*/
$.extend({
    postJSON: function(url, data, callback) {
        return $.post(url, data, callback, 'json');
    }
});


    //------ BASE MAP SETTINGS------//

    var minZ = 6; //min map zoom
    var maxZ = 19; //max map zoom

    var maxScale = 2.5; //landmark scaling range (1 = true size of image source)
    var minScale = 30;

    var plantMap = "4fec77c3c33694d70a000000"; //have to manually set these right now
    var adminMap = "4fec7800c336947909000000";

    //should query DB to find map base layer?


    //-------- USING EXTERNAL CLOUD MAP SERVICE--------///

    // var map = L.map('map').setView([41.02899590297445,28.984766006469727], 14);
    // L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {minZoom:minZ, maxZoom:maxZ}).addTo(map);

    //--------------------------------------------------//

    //--------- USING LOCAL MAP AND OPTIONAL CLOUD MAP LAYER--------//

    var map = new L.Map('map', {crs:L.CRS.EPSG3857}).setView([42.36219069106654,-83.06988000869751], 16);
    var base = L.tileLayer('1.0.0/amc2013/{z}/{x}/{y}.png', {minZoom:minZ, maxZoom:maxZ, tms:'true'});

    //---------- ADDING OPTIONAL EXTERNAL WEB MAP LAYER (UNCOMMENT TO ACTIVATE) ----------//

    // var cloudUrl = 'http://otile1.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
    // cloud = new L.TileLayer(cloudUrl);
    // map.addLayer(cloud,true); 

    map.addLayer(base,true);

    //----------------------------------------//


    var APIload = false; //change to true to load external APIs (like bus time data)

    $(document).ready(function(){

        resourceLoader(); //UX framework
        //-------- MAPS -------//

        getMaps(userID); //generate map layers for this user

        //----------------------//

        if (APIload == true){
            getAPIs(); //gets APIs
        }

        //------ Events for changing landmarks in view (when map moves, re-query landmark locations) -----//

        map.on('dragend', reBound);
        map.on('zoomend', reBound);
        //map.on('click', function(e){alert("Lat, Lon : " + e.latlng.lat + ", " + e.latlng.lng);}); //<----- UNCOMMENT TO CLICK FOR COORDINATES OF CLICKER
        //map.on('click', coordsPop); //<--- UNCOMMENT FOR CLICK FOR MAP BOUNDS (TO BUILD NEW MAPS based in geo)

        //----------------------//

    });
