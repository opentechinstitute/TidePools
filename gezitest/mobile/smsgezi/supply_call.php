<?php


	try 
	{
	    $m = new Mongo(); // connect
	    $db = $m->selectDB("Hurricane");
	    
	}
	catch ( MongoConnectionException $e ) 
	{
	    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
	    exit();
	}
	
	
	$type = 'supplies';
	
	$coll = $db->$type;
	
	
	$cursor = $coll->find();
	
	$cursor->sort(array('_id' => -1));
	
	$cursor = iterator_to_array($cursor);

	
	$cursor = json_encode($cursor);

	print_r($cursor);


?>