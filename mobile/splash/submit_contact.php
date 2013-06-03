<?php
try {

	$dbhandle = sqlite_open('db/shoutbox.db', 0666, $error);

	if($_POST['name']) {
	

			    
		$name    = $_POST['name'];
	    $email    = $_POST['email'];
	    $phone    = $_POST['phone'];

	    
	    $name_es = sqlite_escape_string($name); //no injections plz thx 
	    $email_es = sqlite_escape_string($email);
	    $phone_es = sqlite_escape_string($phone);
	    
	    if (!$dbhandle) die ($error);
	    
	
	
			$stm1 = "INSERT INTO contact(name, email, phone) VALUES('$name_es', '$email_es','$phone_es')";
			
			$ok1 = sqlite_exec($dbhandle, $stm1);
			if (!$ok1) die("Cannot execute statement.");
		

	
	    
	   //--------------------------//

	    

	
	}

}


catch(Exception $e) 
{
  die($error);
}

sqlite_close($dbhandle);


?>