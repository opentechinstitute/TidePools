
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' id='viewport' name='viewport' />

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<title>Red Hook Radio</title>

<script src="js/libs/iscroll.js"></script>

<script src="js/libs/jquery-1.7.1.min.js"></script>

<script src="cjs/libs/jquery.mobile-1.0.min.js"></script>

<link rel="stylesheet" href="css/redtest.css" />

<link rel="stylesheet" href="css/jquery.mobile.structure-1.0.min.css" />

<link rel="stylesheet" href="css/scroll.css" />


<script type="text/javascript">



$(function() {
	
	//reloadPage();
    refresh_shoutbox();
    
    refresh_announcements();
  
	$("#submit").click(function(e) {
	
	  $.ajax({
	      type: $('#newPost').attr('method')
	    , url: $('#newPost').attr('action')
	    , data: $('#newPost').serialize()
	    , success: function(html) {
	    
	        refresh_shoutbox();
	        refresh_announcements();
	      }
	  });
	  e.preventDefault();
	});

});






function refresh_shoutbox() {
    var data = 'refresh=1';
    	
    $.ajax({
            type: "POST",
            url: "shout.php",
            data: data,
            success: function(html){ // this happen after we get result

                $("#shout").html(html);
                
                myScroll.refresh();
 
            }
        });
}


function refresh_announcements() {
    var data = 'refresh=1';
    	
    $.ajax({
            type: "POST",
            url: "announce.php",
            data: data,
            success: function(html){ // this happen after we get result

                $("#announce").html(html);
                
                myScroll.refresh();

 
            }
        });
}

</script>

</head>
<body>

	<nav id="header" style="position:fixed" >
	
		<a href="index.php">
			<figure>
			  <img src="images/home.png" alt="Home" />
			  <figcaption><p>Home</p></figcaption>
			</figure>
		</a>

		<a href="about.php">
			<figure>
			  <img src="images/about.png" alt="About" />
			  <figcaption><p>About</p></figcaption>
			</figure>
		</a>
		
		<a href="http://www.redhookinitiative.org">
			<figure>
			  <img src="images/internet.png" alt="Internet" />
			  <figcaption><p>Internet</p></figcaption>
			</figure>
		</a>

	</nav>
	
<div id="border">
	
	<img src="images/rhi.jpg" width="100%" style="position:static; margin-top:38px;"/>

<div id="formfont">

	<p>The Red Hook Initiative works to confront and affect the consequences of intergenerational poverty through an approach that offers support in education, employment, health and community development. We believe that social change comes from within individuals. The momentum to improve the quality of life for Red Hook's residents - as well as the community at large - must come from the people living in the community. Currently over 95% of our employees live in the Red Hook Houses. We are creating a unique model for social change.</p>
	
	<a href="http://www.rhicenter.org/">Click to vist www.rhicenter.org</a>
	
<hr>
	
	<li style="height:350px">
	Interested in the project? Learn more:
	
	<form id="newPost" action="submit_contact.php" method="post">
	
		Name: <input type="text" id="name" name="name" maxlength="50"/></br>
		
		Email: <input type="text" id="email" name="email" maxlength="50"/></br>
	
		Phone: <input type="text" id="phone" name="phone" maxlength="50"/></br>
	
	<input type="button" id="submit" value="Submit" />
	
	</form>

	
	
	</li>
	</ul>
	
</div>

</div>



</body>
</html>
