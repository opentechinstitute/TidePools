<?php


$dbhandle = sqlite_open('db/shoutbox.db', 0666, $error);
if (!$dbhandle) die ($error);




//--------- Shoutbox -----------//

$stm = "CREATE TABLE shouts(name VARCHAR(45)," . 
       "post TEXT)";
            
$ok = sqlite_exec($dbhandle, $stm, $error);

if (!$ok)
   die("Cannot execute query. $error");
   
//------------------------------//



//--------- Announcements -----------//
   
$stm2 = "CREATE TABLE announcements(name VARCHAR(45)," . 
       "post TEXT)";
         
$ok2 = sqlite_exec($dbhandle, $stm2, $error);

if (!$ok2)
   die("Cannot execute query. $error");
   
//------------------------------//


//--------- Contact stuff -----------//
   
$stm3 = "CREATE TABLE contact(name VARCHAR(45)," . 
       "email VARCHAR(45), phone VARCHAR(45))";
         
$ok3 = sqlite_exec($dbhandle, $stm3, $error);

if (!$ok3)
   die("Cannot execute query. $error");
   
//------------------------------//


echo "Database & tables created successfully";

sqlite_close($dbhandle);



?>