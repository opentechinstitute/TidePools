<?php
	
	
	try 
	{
	    $m = new Mongo(); // connect
	    $db = $m->selectDB("RedHook");
	    
	}
	catch ( MongoConnectionException $e ) 
	{
	    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
	    exit();
	}
	
	
	$type = 'stopfrisks';
	
	$coll = $db->$type;
	
	
	//INSERT MONGO INJECTION SECURITY HERE
	
		
	//------- FRISK BASICS -------//
	
	$frisked = $_POST['frisked'];
	$consent = $_POST['consent'];
	
	$searched = $_POST['searched'];
	
	if (in_array("Other", $searched)) {
	
		array_push($searched, $_POST['othersearched']);
		
	}
	
	$search = $_POST['search'];
	$force = $_POST['force'];
	$injured = $_POST['injured'];
	
	if ($_POST['reason'] == "Other"){
		$reason = $_POST['otherreason'];
	}
	else {
		$reason = $_POST['reason'];
	}
	
	$outcome = $_POST['outcome'];
	
	$additional = $_POST['additional']; //this is out of order from HTML form, just fyi

	
	//----------------------------//
	
	$friskBasics = array(
	
	    'frisked'=>$frisked,
	    'consent'=>$consent,
	    'searched'=>$searched,
	    'search'=>$search,
	    'force'=>$force,
	    'injured'=>$injured,
	    'reason'=>$reason,
	    'outcome'=>$outcome,
	    'additional'=>$additional  
	);
	
	

	
	//-------- WHEN & WHERE --------//
	
	$time = $_POST['time'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	
	$location = $_POST['location'];
	//hidden LAT LONG values here
	
	//-----------------------------//
	
	$whenWhere = array(
	
	    'time'=>$time,
	    'month'=>$month,
	    'year'=>$year,
	    'location'=>$location
	);
	
	
	
	//--------- POLICE DATA-----------//
	
	if ($_POST['precinct'] == "Other"){
		$precinct = $_POST['otherprecinct'];
	}
	else {
		$precinct = $_POST['precinct'];
	}
	
	$report = $_POST['report'];
	
	$policeinfo = $_POST['policeinfo'];
	
	//-------------------------------//
	
	
	$police = array(
	
	    'precinct'=>$precinct,
	    'report'=>$report,
	    'policeinfo'=>$policeinfo	
	
	);
	
	

	
		
	//------ USER -------//
	
	$age = $_POST['age'];
	
	if ($_POST['gender'] == "Other"){
		$gender = $_POST['othergender'];
	}
	else {
		$gender = $_POST['gender'];
	}
	
	
	if ($_POST['race'] == "Other"){
		$race = $_POST['otherrace'];
	}
	else {
		$race = $_POST['race'];
	}
		
		//--------- /!\ CONFIDENTIAL INFO /!\ ----------//
		
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		
		//-----------------------------------//
		
		$confInfo = array(
		    'name'=>$name,
		    'address'=>$address,
		    'phone'=>$phone,
		    'email'=>$email
		);
		
	//-------------------//
	
	$user = array(
	
	    'age'=>$age,
	    'gender'=>$gender,
	    'race'=>$race,
	    'confinfo'=>$confInfo
	);

////////////--------- PACKAGING DATA ------/////////////////////////


	$stopFrisk = array(
	 
	    'friskbasics'=>$friskBasics,
	    'whenwhere'=>$whenWhere,
	    'police'=>$police,
	    'user'=>$user
	);
	
//////////////////////////////////////////////////////////////////

	
	//var_dump($stopFrisk);
	
	
	insertStopFrisk($stopFrisk, $coll);

		
	function insertStopFrisk($stopFrisk,$coll){
			
		$safe_insert = true;
		
		$coll->insert($stopFrisk,$safe_insert);
		
		//$collection->ensureIndex(array("loc" => "2d"));
		
		echo "Stop & Frisk Incident Reported, thank you.";
		
		//header( 'Location: ../list.html' ) ;

	}


?>