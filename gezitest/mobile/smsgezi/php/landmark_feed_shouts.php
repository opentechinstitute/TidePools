<?php 

/**
 *	TidePools Social WiFi
 *  Copyright (C) 2012 Jonathan Baldwin <jrbaldwin@gmail.com>
 *
 *	This file is part of TidePools <http://www.tidepools.co>

 *  TidePools is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  TidePools is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with TidePools.  If not, see <http://www.gnu.org/licenses/>.
 */

	$landmarks = $_POST['data'];
	
	
	stripslashes($landmarks); 

	
	$counter = 1;
	

	echo '<div style="width:351px; height:80px;"> <hr style="border:3px solid #df1c53;" /><img src="/images/announcement.png" style="float:left; margin-left: 8;" /> <div id="innertext" style="width:299px; height:75; float:left; margin-left: 7; margin-top:-5;"><p2>Announcement</p2> </br> <p style="margin-top:2px; " > This version of the Tidepools Community Internet Project in Red Hook, Brooklyn is hosted on the Internet, while the actual map is hosted locally, in the community. More info:<b> http://tidepools.co</b></p></div> <hr></div>';
		

	foreach($landmarks as $i){
	
	
		foreach($i as $c){
		

			if ($c['stats'] !== null){
			
				
				if ($c['name'] == "flora"){
				
				
					continue;
				
				}
				
				
				else {
				
				
					
							
					if ($c['feed'] !== null){
					
						//echo "<b>".$c['name']."</b></br>";
						
		
						foreach($c['feed'] as $x){
						
										
					
							$idVal = "'".$c['_id']['$id']."'";
		
							echo '<div style="width:100%; margin-left: -7px; margin-top: 42px; margin-bottom: 5px;" onclick="landmarkWindow('.$idVal.')">';
							
							//echo '<div style="cursor:pointer; " ">';

				
					
							$num = rand(1, 3);
							
							echo '<img src="/images/people/person'.$num.'.png" style="float:left; max-width:42px; margin-left: 8; margin-right:11;"/>';//
							
							echo '<div id="innertext" style=" width:348px;  height:39;  margin-left: 12; margin-top:1; margin-bottom:8">';
							
							$name = stripslashes($x['name']);
							
							echo "<p5><span style='color:#7f275b'></span>".$name."</p5>";  //plug into CSS here...
							
							$descrip = stripslashes($x['words']);
							
		
							echo "<p style='margin-top:2px;'>".$descrip."</p>";
							
							
												
							echo '</br><hr></div></div>';
							
							
							//$counter++;
					
					
						}
		
					}
					
					
				}

			
			}
			

			
						
		}
		
		
		
		
	}
	
	echo '</div>';
	


?>
	

	
			


