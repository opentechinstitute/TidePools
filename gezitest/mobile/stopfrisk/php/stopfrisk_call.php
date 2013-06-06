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
	
	
	$cursor = $coll->find();
	
	$cursor->sort(array('_id' => -1));
	
	$cursor = iterator_to_array($cursor);


	foreach($cursor as &$key) {  //kill the confidential info from the DB callback
	
		foreach($key as $value => &$k){
	
	   		if ($value == 'user'){
	   		
	   			unset($k['confinfo']);

	   		}
		}	
	}
		
	unset($key);	
	
	$cursor = json_encode($cursor);

	print_r($cursor);


?>