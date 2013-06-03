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
    <meta name="generator" content=
    "HTML Tidy for Linux/x86 (vers 25 March 2009), see www.w3.org" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />

    <title>Tidepools</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
    <link rel="icon" href="assets/images/pound.ico" />

    <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-32066814-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
    
  </head>

  <body>
    <div id="navframe" style="margin-top:-10px">
      <div id="mapcontrol" style="display:none; width:353; height:51;">
        <img src="assets/images/map_collection.png" style="margin-top:15" />
        <div id="newmap" style=
        "width: 67px; position:absolute; height: 31; top:18px; left:288px; cursor:pointer; background-image:url('assets/images/new_map.png'); z-index:1;"
        value='Hide Layer' onclick="unhide('mapform');"></div>
      </div>
      <div id="navtop" class="unhidden" style="display:none; top: 12px; left:-3"></div>
      <img src="assets/images/sms_note.png" ">
      <div id="filters" style=
      "width:353; height:134; background-color:#ebe1e3;position: relative;">
      <div style="padding: 6px 6px 6px 6px;">
        <div style=
        "position:relative; display:inline-block; margin-bottom:6px;margin-right: -9; margin-left: -1;">
        <img src="assets/images/filter_landmarks.png" /></div>
            <?php include "php/landmark_filters_menu.php"; ?>

            <!-- NOTE: NEED TO RENAME 2 ICONS!

                filter_wifi.png => filter_freewifi.png
                filter_other.png => filter_somethingelse.png

            // -->

      </div>
    </div>

    <div style=
    "width:353; height:5; background-color:#7f275b; position: relative;"></div>

    <!-- Buttons to select feed -->
    <div id="LandmarksFeed" style=
      "width:89px; height:19px; background-color:#8bc3c5; position: relative; display:inline-block; text-align: center; cursor:pointer;"
      onclick="changeFeed('landmarks');">
      <p3>Yerler</p3>
    </div>

    <div id="EventsFeed" style=
      "width:89px; height:19px; background-color:#ebe1e3; position: relative; display:inline-block; margin-left:-1; text-align: center; cursor:pointer;"
      onclick="changeFeed('comments');">
      <p3>Yorumlar</p3>
    </div>

    <div id="Search" style="width:89px; height:19px; background-color:#eb6699;
      position:relative; display:inline-block; margin-left:-1;
      text-align: center; cursor:pointer;" onclick="unhide('searchwindow')">
      <p3>Arama</p3>
    </div>


<div id="nav" style="top:266px;"></div>

<div id="searchwindow" style="z-index:1; position:absolute; background-color:#fff; top:170px;" class="hidden">
        <form id="searchform">

        <div style="padding-left: 2em; padding-top: 1em; width: 330px;">
            <input type="text" name="searchTerm" maxlength = "160" style="background-color: #dbd1d3;">
            <input type="button" id="searchsubmit" value="Find it!"
                style="background-color: #dbd1d3;" onsubmit=
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

<div id="mapform" class="hidden" style="background-color:#f2edea">
  <div id="formfont" style="padding-left:12; padding-right:12;">
    <form id="newMap" action="php/record_map.php" method="post" onsubmit=
    "this.mapsubmit();">
    <div id="backbutton" style=
    "position: relative; width:73px; background-image:url(assets/images/back.png); height:30px; top:7;"
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
"width: 170px; height: 325px; position:fixed; background-color:#e6e4ea; border-style:solid; border-color:#444d51; border:1px top:101px; left:398px; z-index:1; margin-top: 48px;"
class="unhidden">
<div style="padding: 6px 6px 6px 6px;">
    <?php include "php/landmark_menu.php"; ?>
</div>
</div>

<div id="map" style="z-index:0;"></div>

<div id="navbarr" class="unhidden" style=
"width: 223px; height: 73px; position:fixed; background-color:#7871a1; opacity: 0.85; border-style:solid; border-color:#2f5364; top:-29px; left:633px; z-index:1; margin-top: 48px; border-radius: 9;">
<div style="padding: 0px 4px 4px 22px;">
  <div id="clickgo" style=
  "position:relative; display:inline-block; margin-bottom:6px; opacity: 1; z-index:2; margin-top:-5px;"
  value='Hide Layer' class="target" ><img style="display:none;" src="assets/images/clickgo.png" width="34" height=
  "25" /></div>

  <div id="Istanbul" style=
  "background: #fff; font-family:Tahoma, Geneva, sans-serif; padding: 1px; position:relative; display:inline-block; margin-bottom:6px; cursor:pointer; opacity: 1; z-index:2;"
  value='Hide Layer' onclick=
  "gotoCoordinates(41.0128, 28.9744);" class="target"> Istanbul </div>

  <div id="Izmir" style=
  "background: #fff;  font-family:Tahoma, Geneva, sans-serif; padding: 1px; position:relative; display:inline-block; margin-bottom:6px; cursor:pointer; opacity: 1; z-index:2;"
  value='Hide Layer' onclick=
  "gotoCoordinates(38.4202, 27.1283);" class="target">  Izmir  </div>

  <div id="Ankara" style=
  "background: #fff; font-family:Tahoma, Geneva, sans-serif; padding: 1px; position:relative; display:inline-block; margin-bottom:6px; cursor:pointer; opacity: 1; z-index:2;"
  value='Hide Layer' onclick=
  "gotoCoordinates(39.9167, 32.8500);" class="target">  Ankara  </div>

  <div id="searcher" style=
  "background: #fff; font-family:Tahoma, Geneva, sans-serif; padding: 1px; position:relative; display:inline-block; margin-bottom:6px; cursor:pointer; opacity: 1; z-index:2;"
  value='Hide Layer' onclick=
  "addbyLocation();" class="target"> <img src="assets/images/search.png" width="112" height="43" /> </div>
</div>
</div>

<div id="addlandmark" style=
"width: 140px; background-repeat: no-repeat; position:absolute; height: 42px; top:18px; left:397px; background-image:url('assets/images/landmarkbutton.png'); z-index:1; cursor:pointer;"
value='Hide Layer' onclick="unhide('landmarkmenu');" class="target"></div>

<div id="trashlandmark" style=
"width: 171px; height: 337px; position:fixed; background-color:#2d3c4b; border-style:solid; top:58px; left:398px; z-index:1; opacity:0.7;"
class="hidden"><img src="assets/images/trash.png" /></div>

<div id="secretbutton" style=
"z-index:2; background-color:#fff; position:absolute; width: 8px; height:40px; top:0; margin-left:-8; cursor:pointer;"
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

</body>
</html>
