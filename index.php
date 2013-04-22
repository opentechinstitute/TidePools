<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html>
<html class="no-js" lang="en">
<!--

.===.      .                    .
  |  o     |                    |
  |  .  .-.| .-. .,-.  .-.  .-. | .==.
  |  | (   |(.-' |   )(   )(   )| `==.
  '-' `-`-'`-`=='|`-'  `-'  `-' `-`==' v0.2
                 |
                 '
Open Technology Institute & jrbaldwin
Contributors: Lisa J. Lovchik, Nicholas Frota


//-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
  <title>Tidepools</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
  <!-- TODO: icons: favicon, iphone, MS surface. How to make it themable?  -->
  <link rel="shortcut icon" href="assets/ico/favicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/ico/apple-touch-icon-57.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144.png">
  <meta name="viewport" content="user-scalable=0, initial-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes" /> 
  <meta name="application-name" content="TidePools"/> 
  <meta name="msapplication-TileColor" content="#cccccc"/> 
  <meta name="msapplication-TileImage" content="assets/ico/ms-TileImage.png"/>
</head>
<body>
  <div id="navframe">
    <div class="navframe1"></div>

    <div id="mapcontrol">
      <img src="assets/images/map_collection.png" class="mapcontrol1" />

      <div id="newmap"
      value='Hide Layer' onclick="unhide('mapform');"></div>
    </div>

    <div id="navtop" class="unhidden"></div>

    <div id="filters">
      <div class="filter1">
        <div class="filter2">
          <img src="assets/images/filter_landmarks.png" /></div>
          <?php include "php/landmark_filters_menu.php"; ?>

            <!-- NOTE: NEED TO RENAME 2 ICONS!

                filter_wifi.png => filter_freewifi.png
                filter_other.png => filter_somethingelse.png

                // -->

              </div>
            </div>

            <div class="filter3"></div>

            <!-- Buttons to select feed -->
            <div id="LandmarksFeed"
            onclick="changeFeed('landmarks');">
            <p3>Landmarks</p3>
          </div>
          <div id="EventsFeed"
          onclick="changeFeed('comments');">
          <p3>Chat Feed</p3>
        </div>

        <div id="Search" onclick="unhide('searchwindow')">
        <p3>Search</p3>
      </div>


      <div id="nav"></div>

      <div id="searchwindow" class="hidden">
        <form id="searchform">

          <div class="searchform1">
            <input type="text" name="searchTerm" maxlength = "160">
            <input type="button" id="searchsubmit" placeholder="Find it!" onsubmit=
            "this.searchsubmit(); return false;" /><br />

            <br /><br />

            <!-- TO BE RE-ADDED LATER FOR ADVANCED GEOINDEXING SEARCH
            <strong>(for location) Distance units:</strong><br />
            <input type="radio" name="distUnits" value="mi" checked>Miles
            <input type="radio" name="distUnits" value="km">Kilometers
            <br /><br /> -->

            <!--starting point for location search is hard-coded for now;
            later, this will be handled by the map interface -->

                <!-- ClickGo 157 coordinates
                <input type="hidden" name="lon" value="42.35404858146037" />
                <input type="hidden" name="lat" value="-83.07889223098755" /> -->

                <!-- Red Hook post office coordinates
                <input type="hidden" name="lon" value="-74.001862406731" />
                <input type="hidden" name="lat" value="40.674576233841" /> -->

              </div>

            <!-- SEARCH HELP
            <div style="margin: 3ex; padding: 10px; background-color: #ffff99;
                width: 18em;">
                <strong>Example searches:</strong><br />
                Name: school, health, library, initiative, Brooklyn<br />
                Type: busstop, library, police, postoffice, event, garden, flowers1, tree<br />
                Description: dwight, van brunt, barbeque<br />
                Distance: .3 miles, .4 kilometers
              </div> -->

            </form>
          </div>
        </div>

        <div id="landmarkfeed" class="hidden"></div>

        <div id="mapform" class="hidden">
          <div id="formfont">
            <form id="newMap" action="php/record_map.php" method="post" onsubmit=
            "this.mapsubmit();">
            <div id="backbutton" style=
            ""
            onclick="unhide('mapform')"></div><br />
            <p5>Create a New Map</p5><br />

            <p><i>Note: All maps aggregate unless specified</i></p><img src=
            "assets/images/icon_map.png" /><br />

            <p>Map Name<br />
              <input type="text" id="mapname" name="mapname" maxlength="50" /><br />
              Theme/Description<br />
              <br />
              <textarea name="mapdescrip" id="mapdescrip" maxlength="300">
              </textarea><br />
              <input type="button" id="mapsubmit" value="Save" onsubmit=
              "this.mapsubmit(); return false;" /><br />
              <br />
              <p5>Map Options</p5><br />
              <br />
              Free for All <input type="checkbox" name="openedit" value="1" /><br />
              Private/Hidden <input type="checkbox" name="hidden" value="1" /><br />
              Scavenger Hunt <input type="checkbox" name="scavenger" value=
              "1" /><br /></p><input type="hidden" id="admin" name="admin" value="0" />
            </form>
          </div>
        </div>

        <div id="landmarkmenu" style=
        ""
        class="unhidden">
        <div class="landmark1">
          <?php include "php/landmark_menu.php"; ?>
        </div>
      </div>

      <div id="map"></div>

      <div id="navbarr" style=
      "">
      <div class="navbarr1">
        <div id="clickgo" style=
        ""
        value='Hide Layer' class="target"><img src="assets/images/clickgo.png" width="34" height=
        "25" /></div>

        <div id="A" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.349481561625495, -83.05789589881897);" class="target"><img src=
        "assets/images/A.png" width="39" height="37" /></div>

        <div id="B" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.349703577207784, -83.05676937103271);" class="target"><img src=
        "assets/images/B.png" width="39" height="37" /></div>

        <div id="C" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.34944191590328, -83.05510640144348);" class="target"><img src=
        "assets/images/C.png" width="39" height="37" /></div>

        <div id="AUD" class="ABClayer"
        value='Hide Layer' onclick="gotoCoordinates(42.36235320539709, -83.054478764534);"
        class="target"><img src="assets/images/AUD.png" width="39" height="37" /></div>

        <div id="154" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.352086230785794, -83.07842016220091);" class="target"><img src=
        "assets/images/154.png" class="targetimg" /></div>

        <div id="156" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.352177412073424, -83.07647824287415);" class="target"><img src=
        "assets/images/156.png" width="39" height="37" /></div>

        <div id="157" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.35404858146037, -83.07889223098755);" class="target"><img src=
        "assets/images/157.png" width="39" height="37" /></div>

        <div id="1243" class="ABClayer"
        value='Hide Layer' onclick="gotoCoordinates(42.35659359955532, -83.0454021692276);"
        class="target"><img src="assets/images/1243.png" width="39" height="37" /></div>

        <div id="campus" class="ABClayer"
        value='Hide Layer' onclick=
        "gotoCoordinates(42.35079382091884, -83.04302036762238);" class="target"><img src=
        "assets/images/campus.png" width="39" height="37" /></div>
      </div>
    </div>

    <div id="addlandmark" style=
    ""
    value='Hide Layer' onclick="unhide('landmarkmenu');" class="target"></div>

    <div id="trashlandmark" style=
    ""
    class="hidden"><img src="assets/images/trash.png" /></div>

    <div id="secretbutton" style=
    ""
    onclick="secretLandmark()">
    <div id="loadingDiv" style=
    "background-image:url('assets/images/loading.gif'); position:absolute;"></div>
  </div>

  <script src="assets/js/resources/leaflet.js" type="text/javascript"></script>
  <script src="assets/js/resources/jquery.js" type="text/javascript"></script>
  <script src="assets/js/resources/jquery_ui.js" type="text/javascript"></script>
  <script src="assets/js/resources/jquery_ui_timepicker.js" type="text/javascript"></script>
  <!-- <script src="js/tidepoolsframeworks/APIkeys.js"></script> <!-- UNCOMMENT TO LOAD YOUR API Keys-->
  <script src="assets/js/tidepoolsframeworks/global_functions.js" type="text/javascript"></script>
  <script src="assets/js/tidepoolsframeworks/dragdrop.js" type="text/javascript"></script>
  <script src="assets/js/tidepoolsframeworks/map_rendering.js" type="text/javascript"></script>
  <script src="assets/js/tidepoolsframeworks/API_collect.js" type="text/javascript"></script>
  <script src="assets/js/tidepoolsframeworks/landmark_functions.js" type="text/javascript"></script>
  <script src="assets/js/tidepools.js" type="text/javascript"></script>
<!-- TODO: add and test bootstrap, add 2 home
<script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/add2home.js"></script>  -->

</body>
</html>
