<html>
  <head>
	<meta charset="utf-8"/>
	<title>Audio Issue Report Dashboard</title>
	
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
    	die('Could no connect:'.mysql_error());
    }
    mysql_select_db('Echo');
    ////////////////////////////////////
	$rs = mysql_query("SELECT * FROM CallAggregatedData WHERE MutingStartTime != 'NULL' ORDER BY ID desc limit 1 " );
    $callID = 0;
    $CallStartTime = "";
    $CallEndTime = "";
    $MutingStartTime = "";
    $MutingEndTime = "";

    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    		$CallStartTime= $myrow["StartTime"];
    		$CallEndTime = $myrow["EndTime"];
            $MutingStartTime= $myrow["MutingStartTime"];
            $MutingEndTime = $myrow["MutingEndTime"];
            $callID = intval($myrow["CallID"]);
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

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization',
       'version':'1','packages':['timeline']}]}"></script>
<script type="text/javascript">

google.setOnLoadCallback(drawChart);
function drawChart() {
  var CallStartTime = "<?php echo $CallStartTime; ?>";
  var CallStartTime_Date_str = CallStartTime.split(" ")[0];
  var CallStartTime_Time_str = CallStartTime.split(" ")[1];
  var CallStartTime_hour = parseInt(CallStartTime_Time_str.split(":")[0]);
  var CallStartTime_minute = parseInt(CallStartTime_Time_str.split(":")[1]);
  var CallStartTime_second = parseInt(CallStartTime_Time_str.split(":")[2]);


  var MutingStartTime = "<?php echo $MutingStartTime; ?>";
  var MutingStartTime_Date_str = MutingStartTime.split(" ")[0];
  var MutingStartTime_Time_str = MutingStartTime.split(" ")[1];
  var MutingStartTime_hour = parseInt(MutingStartTime_Time_str.split(":")[0]);
  var MutingStartTime_minute = parseInt(MutingStartTime_Time_str.split(":")[1]);
  var MutingStartTime_second = parseInt(MutingStartTime_Time_str.split(":")[2]);

  var MutingEndTime = "<?php echo $MutingEndTime; ?>";
  var MutingEndTime_Date_str = MutingEndTime.split(" ")[0];
  var MutingEndTime_Time_str = MutingEndTime.split(" ")[1];
  var MutingEndTime_hour = parseInt(MutingEndTime_Time_str.split(":")[0]);
  var MutingEndTime_minute = parseInt(MutingEndTime_Time_str.split(":")[1]);
  var MutingEndTime_second = parseInt(MutingEndTime_Time_str.split(":")[2]);

  var CallEndTime = "<?php echo $CallEndTime; ?>";
  var CallEndTime_Date_str = CallEndTime.split(" ")[0];
  var CallEndTime_Time_str = CallEndTime.split(" ")[1];
  var CallEndTime_hour = parseInt(CallEndTime_Time_str.split(":")[0]);
  var CallEndTime_minute = parseInt(CallEndTime_Time_str.split(":")[1]);
  var CallEndTime_second = parseInt(CallEndTime_Time_str.split(":")[2]);
  
  //window.alert(MutingStartTime_hour);
  var container = document.getElementById('timeline');
  var chart = new google.visualization.Timeline(container);
  var dataTable = new google.visualization.DataTable();
  dataTable.addColumn({ type: 'string', id: 'Room' });
  dataTable.addColumn({ type: 'date', id: 'Start' });
  dataTable.addColumn({ type: 'date', id: 'End' });
  dataTable.addRows([
    [ 'Normal',  new Date(1991,1,8,CallStartTime_hour,CallStartTime_minute,CallStartTime_second),  new Date(1991,1,8,MutingStartTime_hour,MutingStartTime_minute,MutingStartTime_second) ],
    [ 'Normal',  new Date(1991,1,8,MutingEndTime_hour,MutingEndTime_minute,MutingEndTime_second),  new Date(1991,1,8,CallEndTime_hour,CallEndTime_minute,CallEndTime_second) ],
    [ 'Muting', new Date(1991,1,8,MutingStartTime_hour,MutingStartTime_minute,MutingStartTime_second),  new Date(1991,1,8,MutingEndTime_hour,MutingEndTime_minute,MutingEndTime_second) ]]);
  var options = {
    timeline: { colorByRowLabel: true }
  };

  chart.draw(dataTable, options);
}
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
			<h2 class="section_title"><a href="index.html">Audio Quality Monitor Dashboard</a></h2>
		</hgroup>
	</header> <!-- end of header bar -->
	
	
	<aside id="sidebar" class="column">
		<form action="/Calls.php" method = "get" class="quick_search">
			<input name = "keyword" type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		<hr/>
		<h3>Content</h3>
		<ul class="toggle">
		<li class="icn_edit_article"><a href="volteServer.php">Statistics</a></li>
		<li class="icn_new_article"><a href="map.php">Call on the Map</a></li>
		<li class="icn_settings"><a href="#">Muting Problem</a></li>
		
		</ul>		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2015 T-Mobile and UMich</strong></p>
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<article class="module width_full">
	<header><h3 class="tabs_involved">Call on the Map: Latest Muting Call, ID = <?php echo $callID;?></h3>
	</header>
            <div id="timeline" style="width: 600px; height: 400px;"></div>
	</article>
		

		
		<div class="clear"></div>
			</section>

  </body>
</html>