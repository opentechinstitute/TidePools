<?php 



if($_POST['refresh']) {    //upon any refresh, executes fucntion to fill shoutbox
    populate_announcements();
}


//here be populate_announcements()  and then add another jquery function to populate announcements...

function populate_announcements() {


	$dbhandle = sqlite_open('db/shoutbox.db', 0666, $error);


	$query = "SELECT * FROM announcements ORDER BY ROWID DESC";
	$result = sqlite_query($dbhandle, $query);	
	if (!$result) die("No Current Announcements");



	while ($row = sqlite_fetch_array($result, SQLITE_ASSOC)) {

		echo '<li>';
	    echo '<span class="annoucename">'.$row['name'].'</span>';
	    echo '<br>';

		echo '<span class="announcemessage">'.$row['post'].'</span>';
   
	    echo "</li>";
	    
	}
	
    
}


?>