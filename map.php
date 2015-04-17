<html>
  <head>
	<meta charset="utf-8"/>
	<title>EchoLocate Dashboard Prototype</title>
	
	<link rel="stylesheet" href="stylesheets/layout.css" type="text/css" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link href="js/jquery-1.5.2.min.js" type="text/javascript" rel="javascript"></link>
	<link href="js/hideshow.js" type="text/javascript" rel="javascript"></link>
	<link href="js/jquery.tablesorter.min.js" type="text/javascript" rel="javascript"></link>
	<link href="text/javascript" src="js/jquery.equalHeight.js" rel="javascript"></link>

<?php
    $conn = mysql_connect(':/cloudsql/psychic-rush-755:database',
    	'root','');
    if(!$conn){
    	die('Could not connect:'.mysql_error());
    }
    mysql_select_db('Echo');
    ////////////////////////////////////
	$rs = mysql_query("SELECT * FROM CallAggregatedData ORDER BY ID desc limit 20 " );
    $info = "Last 20 Calls";
if(isset($_GET['keyword'])){
    $msisdn = "+1".$_GET['keyword'];
    $rs = mysql_query("SELECT * FROM CallAggregatedData WHERE MSISDN = $msisdn ORDER BY ID desc limit 10 " );
    $info = "Recent 10 calls for " .    $_GET['keyword'];
}
    $callID_array= array();
    $startX_array= array();
    $startY_array= array();
    $endX_array= array();
    $endY_array= array();
    $event_array =array();
    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    		array_push($startX_array,$myrow["CallStartLocationX"]);
    		array_push($startY_array,$myrow["CallStartLocationY"]);
            array_push($endX_array,$myrow["CallEndLocationX"]);
            array_push($endY_array,$myrow["CallEndLocationY"]);
            array_push($callID_array, $myrow["CallID"]);
            if($myrow["CallDropReason"]=="NULL" and $myrow["MutingStartTime"]=="NULL"){
                array_push($event_array,0);
            }
            else{
                array_push($event_array, 1);
            };
    	}
    while ($myrow = mysql_fetch_array($rs));
}
?>



	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });

</script>

<style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChwzEfg-P1_nP2M8OKTeY-66ZQWyh4y7w">
    </script>
    <script type="text/javascript">
      function initialize() {

        var startX = <?php echo json_encode($startX_array)?>;
        var startY = <?php echo json_encode($startY_array)?>;
        var endX = <?php echo json_encode($endX_array)?>;
        var endY = <?php echo json_encode($endY_array)?>;
        var events = <?php echo json_encode($event_array)?>;
        var callID =  <?php echo json_encode($callID_array)?>;
        var bounds = new google.maps.LatLngBounds();

        var mapOptions = {
          center: { lat: 47.577, lng: -122.166},
          zoom: 10
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

        var pinColor = "00994C";

        var marker, i;
        //alert(endY.length);
        
        for (i=0;i<startX.length;i++){
            if(events[i] == 0){
                pinColor = "00994c";
            }
            else{
                pinColor = "ff0000";
            }

        var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 34),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 34));

        var x = parseFloat(endX[i]);
        var y = parseFloat(endY[i]);
        var id = callID[i].toString();
            marker = new google.maps.Marker({
                map: map,
                position : { lat: x, lng: y},
                animation : google.maps.Animation.DROP,
                icon : pinImage,
                url :"detail.php?callID="+id
            });

              google.maps.event.addListener(marker, 'click', function() {
                window.location.href = marker.url;
              });


            var loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
            bounds.extend(loc);           
        }
        //alert(x+"----"+y);

        map.fitBounds(bounds);
        map.panToBounds(bounds);

    /*
        var start_marker_1 = new google.maps.Marker({
            map: map,
           // draggable:true,
            position : { lat: startX, lng: startY},
            animation: google.maps.Animation.DROP,
            title: "Start"
        });
        var end_marker_1 = new google.maps.Marker({
            map: map,
           // draggable:true,
            position : { lat: endX, lng: endY},
            animation: google.maps.Animation.DROP,
            icon: pinImage,
            title: "End"
        });
                google.maps.event.addListener(start_marker_1, 'click', toggleBounce);
                google.maps.event.addListener(end_marker_1, 'click', toggleBounce);
        */
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>

<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
$user = UserService::getCurrentUser();
?>

  <body>
 	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.html">Welcome! <?=$user->getNickname()?></a></h1>
			<h2 class="section_title"><a href="index.html">EchoLocate Dashboard Prototype</a></h2>
		</hgroup>
	</header> <!-- end of header bar -->
	
	
	<aside id="sidebar" class="column">
		<form action="/map.php" method = "get" class="quick_search">
			<input name = "keyword" type="text" value="Phone Number Lookup(10-digit only)" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		<hr/>
		<ul class="toggle">
		<li class="icn_edit_article"><a href="volteServer.php">Statistics</a></li>
		<li class="icn_new_article"><a href="map.php">Call on the Map</a></li>
		
		</ul>		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2015 T-Mobile and UMich</strong></p>
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<article class="module width_full">
	<header><h3 class="tabs_involved">Call on the Map: <?php echo $info;?></h3>
	</header>
            <div id="map-canvas"></div>
		</article>
		

		
		<div class="clear"></div>
			</section>

  </body>
</html>