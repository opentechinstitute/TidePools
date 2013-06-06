<?php


try {

	$dbhandle = sqlite_open('db/shoutbox.db', 0666, $error);

	if($_POST['name']) {
	

			    
		$name    = $_POST['name'];
	    $post    = $_POST['message'];
	    
	    $name_es = sqlite_escape_string($name); //no injections plz thx 
	    $post_es = sqlite_escape_string($post);
	    
	    if (!$dbhandle) die ($error);
	    
	    
	    //-------- announce FILTER ----------//
	    
	    $pattern = "/redhook1234/";

	    
	    
	    if (preg_match($pattern,$post_es))
	    {
	    
	    	if (preg_match("/deleteannounce/",$post_es))
	    	{
	    	
		    	$stm1 = "DELETE FROM announcements";
				
				$ok1 = sqlite_exec($dbhandle, $stm1);
				if (!$ok1) die("Cannot execute statement.");
	    	
	    	
	    	}
	    	
	    	else if (preg_match("/deleteshouts/",$post_es)) {
	    	
		    	$posting = preg_replace($pattern, '', $post_es);
	
				$stm1 = "DELETE FROM shouts";
				
				$ok1 = sqlite_exec($dbhandle, $stm1);
				if (!$ok1) die("Cannot execute statement.");
	
			}
			
			else {
			
				$posting = preg_replace($pattern, '', $post_es);
	
				$stm1 = "INSERT INTO announcements(name, post) VALUES('$name_es', '$posting')";
				
				$ok1 = sqlite_exec($dbhandle, $stm1);
				if (!$ok1) die("Cannot execute statement.");
			
			}
	    }
	    	    
	    else { 
	    
	
			$stm1 = "INSERT INTO shouts(name, post) VALUES('$name_es', '$post_es')";
			
			$ok1 = sqlite_exec($dbhandle, $stm1);
			if (!$ok1) die("Cannot execute statement.");
		

	    }
	    
	    
	   //--------------------------//

	    

	
	}

}


catch(Exception $e) 
{
  die($error);
}

sqlite_close($dbhandle);





if($_POST['refresh']) {    //upon any refresh, executes fucntion to fill shoutbox
    populate_shoutbox();
}


//here be populate_announcements()  and then add another jquery function to populate announcements...


function populate_shoutbox() {


	$dbhandle = sqlite_open('db/shoutbox.db', 0666, $error);


	$query = "SELECT * FROM shouts ORDER BY ROWID DESC";
	$result = sqlite_query($dbhandle, $query);	
	if (!$result) die("Cannot execute query.");



	while ($row = sqlite_fetch_array($result, SQLITE_ASSOC)) {

		echo '<li>';
		echo '<hr>';
	    echo '<span class="name">'.$row['name'].'</span>';
	    echo '<br>';

		echo '<span class="message">'.$row['post'].'</span>';

	    echo "</li>";
	    
	}
	    
}
?>