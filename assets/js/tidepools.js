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

    var minZ = 2; //min map zoom
    var maxZ = 18; //max map zoom

    var maxScale = 1.2; //landmark scaling range (1 = true size of image source)
    var minScale = 30;

    var plantMap = "4fec77c3c33694d70a000000"; //have to manually set these right now
    var adminMap = "4fec7800c336947909000000";

    //should query DB to find map base layer?



    //----- TREASURE MAP -------//

    L_PREFER_CANVAS = true;

    //https://github.com/Leaflet/Leaflet/issues/210
    L.Projection.NoWrap = {
        project: function (latlng) {
            return new L.Point(latlng.lng, latlng.lat);
        },

        unproject: function (point, unbounded) {
            return new L.LatLng(point.y, point.x, true);
        }
    };

    L.CRS.Direct = L.Util.extend({}, L.CRS, {
        code: 'Direct',

        projection: L.Projection.NoWrap,
        transformation: new L.Transformation(1, 0, 1, 0)
    });


    var tiles = new L.TileLayer.Canvas( {continuousWorld: 'true', tileSize: 500} );

    var img = new Image;
    img.src = "assets/images/svg/bg.png";

    tiles.drawTile = function(canvas, tile, zoom) {
        var ctx = canvas.getContext('2d');

        ctx.fillStyle = '#83A48B';
        ctx.fillRect(0, 0, 500, 500);

        ctx.drawImage(img,0,0);

    }

    var map = new L.Map('map',{worldCopyJump: false, crs: L.CRS.Direct, layers:[tiles]}).setView([0, 0], 17);


    // var imageUrl = 'assets/images/svg/logo.png',
    // imageBounds = [[1, 1]];

    // L.imageOverlay(imageUrl, imageBounds).addTo(map);


    var logo = L.icon({
        iconUrl: 'assets/images/svg/logo.png',

        iconSize:     [313, 304], // size of the icon
        shadowSize:   [50, 64], // size of the shadow
        iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
        shadowAnchor: [4, 62],  // the same for the shadow
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    L.marker([0, 0], {icon: logo}).addTo(map);

    map.setZoom(18); //because of leaflet glitch or something




    //------------------------//


    //---------- ADDING EXTERNAL WEB MAP LAYER ----------//

    var cloudUrl = 'http://otile1.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
    cloud = new L.TileLayer(cloudUrl);

    // map.addLayer(cloud,true); // <---- UNCOMMENT TO ACTIVATE

    //----------------------------------------//

   // map.addLayer(basemap,true);

    var APIload = false; //change to true to load external APIs (like bus time data)

    $(document).ready(function(){

        //map.panTo([1900, 30]);

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
    //  map.on('click', coordsPop); <--- UNCOMMENT FOR CLICK FOR MAP BOUNDS (TO BUILD NEW MAPS based in geo)

       // map.on('dragend', console.log());

        //----------------------//






    });
