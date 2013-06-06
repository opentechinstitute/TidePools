<?php

/**
 * popup_content.php
 *
 * Generates HTML content of all landmark popup windows,
 * depending on variables passed to this file.
 *
 *
 *.---.      .                    .
 *  |  o     |                    |
 *  |  .  .-.| .-. .,-.  .-.  .-. | .--.
 *  |  | (   |(.-' |   )(   )(   )| `--.
 *  '-' `-`-'`-`--'|`-'  `-'  `-' `-`--' v0.2

 *  Copyright (C) 2012-2013 Open Technology Institute
 *  <tidepools@opentechinstitute.org>
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


    //Temporary handler for generating iframe content

    //generate IFRAME if it's an external link

    $class = $_POST['class'];
    $type = $_POST['type'];
    $mapID = (isset($_POST['mapID']) ? $_POST['mapID'] : null);
    $landmarkID = (isset($_POST['landmarkID'])
                   ? $_POST['landmarkID'] : null);
    $userID = (isset($_POST['userID']) ? $_POST['userID'] : null);
    $adminMap = (isset($_POST['adminMap']) ? $_POST['adminMap'] : null);
    $popWidth = (isset($_POST['popWidth']) ? $_POST['popWidth'] : null);
    $popHeight = (isset($_POST['popHeight'])
                  ? $_POST['popHeight'] : null);


if ($class == 'status') {

    if ($type == 'landmarkstatus') {

        echo '<div id="landmarkpopup" style="height:' . $popHeight . ';
            width:' . $popWidth . ';">';
        echo '<div id="dropzone" style="width:175px; height:42px;
            position:relative; float:left; margin-left:9; margin-bottom:5px;
            background-image:url(../assets/images/dropzone.png); z-index:1;">
            </div>';
        echo '<div id="landmarklike" style="width:32px; height:28px;
            position:relative; margin-left:10px; float:left;
            display:inline-block; cursor:pointer;
            background-image:url(../assets/images/like.png); z-index:1;"
            onclick="likeLandmark(' . $landmarkID . ');"></div>';
        echo '<div id="follow" style="width:66px; height:27px;
            position:relative; margin-right:4; float:left;
            display:inline-block; cursor:pointer;
            background-image:url(../assets/images/follow.png); z-index:1;
            margin-left:5px;"
            onclick="followLandmark(' . $landmarkID . ');"></div>';

        if ($mapID !== $adminMap) { //NOT a secure method for protected maps!

            echo '<div id="delete" style="width:66px; height:27px;
                position:relative; float:left; display:inline-block;
                cursor:pointer; background-image:url(../assets/images/delete.png);
                z-index:1; margin-left:5px;"
                onclick="deleteLandmark(' . $landmarkID . ');"></div><br>';

        }

        echo '<div id="formfont" style="padding-left:12; padding-right:12;">';
        echo '</br>';
        echo '<p style="color:#7f275b; font-size:14; margin-left:-30px;
            position:relative; display:inline-block; margin-top:24;
            margin-bottom:-35;"><b>Add Comment</b></p>';
        echo '</br><hr style="border:2px solid #7f275b;" />';
        echo '<form id="newComment" action="php/record_comment.php"
            method="post" onsubmit="this.commentSubmit(); return false;">';
        echo '</select></br>';
        echo '<p style="margin-bottom:2px;">Name / Nickname</p>
            <input type="text" id="name" value="Someone"
            name="name" maxlength="20"/>';
        echo '<p style="margin-bottom:1px;">Comment</p>
            <textarea name="description" class="clearme" id="description"
            maxlength="300"></textarea></br>';
        echo '<input type="hidden" id="userID" name="userID" value="'
            . $userID . '" />';
        echo '<input type="hidden" id="landmarkID" name="landmarkID" value="'
            . $landmarkID . '" />';
        echo '</br>';
        echo '<input type="button" id="commentSubmit" value="Post"
            onsubmit="this.commentSubmit(); " />';
        echo '<input type="button" id="commentSubmit" value="Cancel"
            onClick="window.location.reload()" />';
        echo '</form>';
        echo '<hr style="border:2px solid #7f275b;" />';
        echo '</div>';
    }
}

    if ($class == 'record') {

        $type = str_replace("assets/images/","",$type); //removing a dumb code thing

        echo '<p style="color:#7f275b; font-size:14;  position:relative;
            display:inline-block; margin-top:-8;margin-bottom:9;"><b>New '
            . $type . '</b></p>';
        echo '<form id="newPost" action="php/record_landmark.php" method="post"
            onsubmit="this.submit(); return false;">';

        switch ($type) {

            case "saglik":

                echo 'Yerin ya da kisinin ismi:</br><input type="text" id="name"
                    name="name" maxlength="50" value=""/></br>';
                echo 'Aciklama:</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                break;

            case "internet":

                echo 'Yerin ismi:</br><input type="text" id="name"
                    name="name" maxlength="50" value=""/></br>';
                echo 'Aciklama:</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                break;

            case "yarali":

                echo 'Kisinin ismi</br><input type="text" id="name"
                    name="name" maxlength="50" value=""/></br>';
                echo 'Yaralinin durumu</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                break;

            case "siginak":

                echo 'Kac kisiye yer var?</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo 'Aciklama:</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                break;

            case "dikkat":

                echo 'Tehlike nedir?</br><input type="text" id="name" name="name"
                    maxlength="50" value=""/></br>';
                echo 'Neler oldu?</br>
                    <textarea name="description" id="description"
                    maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                echo '</br><b>Ne zaman?</b></br>';
                echo '<input type="radio" name="timespec" value="none"
                    checked> None <input type="radio" name="timespec"
                    value="once"> Bir Kere <input type="radio" name="timespec"
                    value="daily"> Her Gun';
                echo '<input type="radio" name="timespec" value="weekly">
                    Her Hafta';
                echo '</br>Baslangic Tarihi/Saati  <input type="text"
                    name="startdatetimepicker" id="startdatetimepicker"
                    value="Click Here" />';
                echo '</br>Bitis Tarihi/Saati  <input type="text"
                    name="enddatetimepicker" id="enddatetimepicker"
                    value="Click Here" />';
                break;

            case "yangin":

                echo 'Ne yaniyor?</br><input type="text" id="name"
                    name="name" maxlength="50" value=""/></br>';
                echo 'Yanginin buyuklugu nedir:</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                echo '</br><b>Ne zaman basladi?</b></br>';
                echo '</br>Tarih/Zaman<input type="text"
                    name="datetimepicker" id="datetimepicker"
                    value="Click Here" />';
                break;

            case "yiyecek-icecek":

                echo 'Ne tarz yiyecek ve icecek bulunuyor?</br><input type="text" id="name"
                    name="name" maxlength="50" value=""/></br>';
                echo '<input type="button" id="submit" value="kaydet"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Iptal"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                echo '</br><b>Ne zaman acik?</b></br>';
                echo '<input type="radio" name="timespec" value="none"
                    checked> None <input type="radio" name="timespec"
                    value="once"> Bir Kere<input type="radio" name="timespec"
                    value="daily"> Her Gun';
                echo '<input type="radio" name="timespec" value="weekly">
                    Her Haft';
                echo '</br>Tarih/Zaman<input type="text"
                    name="datetimepicker" id="datetimepicker"
                    value="Click Here" />';
                break;

            default:

                echo 'Title/Name</br><input type="text" id="name" name="name"
                    maxlength="50" value=""/></br>';
                echo 'Anything else?</br> <textarea name="description"
                    id="description" maxlength="300"></textarea></br>';
                echo '<input type="button" id="submit" value="Save"
                    onsubmit="this.submit(); " />';
                echo '<input type="button" id="submit" value="Cancel"
                    onClick="window.location.reload()" />';
                echo '</br><hr>';
                echo '</br><b>When is it? (landmark will appear/disappear
                    during this time period) </b></br>';
                echo '<input type="radio" name="timespec" value="none" checked>
                    None <input type="radio" name="timespec" value="once"> Once
                    <input type="radio" name="timespec" value="daily"> Daily ';
                echo '<input type="radio" name="timespec" value="weekly">
                    Weekly';
                echo '</br>Start Date/Time  <input type="text"
                    name="startdatetimepicker" id="startdatetimepicker"
                    value="Click Here" />';
                echo '</br>End Date/Time  <input type="text"
                    name="enddatetimepicker" id="enddatetimepicker"
                    value="Click Here" />';

        }

            echo '<br>Add to Map (optional): <select id="maplist"
                name="maplist">';
            echo '<input type="hidden" id="lat" name="lat" value="0" />';
            echo '<input type="hidden" id="lng" name="lng" value="0" />';
            echo '<input type="hidden" id="marktype" name="marktype"
                value="0" />';
            echo '<input type="hidden" id="landmarkAdmin" name="landmarkAdmin"
                value="0" />';
            echo '<input type="hidden" id="mapID" name="mapID" value="0" />';
            echo '</form>';
    }
