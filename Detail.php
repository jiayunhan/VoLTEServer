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
    $pagesize=10;
    $conn = mysql_connect(':/cloudsql/psychic-rush-755:database',
    	'root','');
    if(!$conn){
    	die('Could no connect:'.mysql_error());
    }
    mysql_select_db('Echo');
    $callID = 0;
    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData");
    $rows = mysql_fetch_array($rs);
    $row_count = $rows[0];

    if(isset($_GET['callID'])){
    	$callID = $_GET['callID'];
    }
	$rs = mysql_query("SELECT * FROM CallAggregatedData WHERE CallID = $callID");
	$rows = mysql_fetch_array($rs);
	$row_count = $rows[0];
    		$CallStartTime= $rows["StartTime"];
    		$CallEndTime = $rows["EndTime"];
            $MutingStartTime= $rows["MutingStartTime"];
            $MutingEndTime = $rows["MutingEndTime"];	
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

  var CallEndTime = "<?php echo $CallEndTime; ?>";
  var CallEndTime_Date_str = CallEndTime.split(" ")[0];
  var CallEndTime_Time_str = CallEndTime.split(" ")[1];
  var CallEndTime_hour = parseInt(CallEndTime_Time_str.split(":")[0]);
  var CallEndTime_minute = parseInt(CallEndTime_Time_str.split(":")[1]);
  var CallEndTime_second = parseInt(CallEndTime_Time_str.split(":")[2]);

  var container = document.getElementById('timeline');
  var chart = new google.visualization.Timeline(container);
  var dataTable = new google.visualization.DataTable();
  dataTable.addColumn({ type: 'string', id: 'Room' });
  dataTable.addColumn({ type: 'date', id: 'Start' });
  dataTable.addColumn({ type: 'date', id: 'End' });
  var options = {
    timeline: { colorByRowLabel: true }
};

  var MutingStartTime = "<?php echo $MutingStartTime; ?>";
  if(MutingStartTime != "NULL"){
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
   dataTable.addRows([
    [ 'Normal',  new Date(0,0,0,CallStartTime_hour,CallStartTime_minute,CallStartTime_second),  new Date(0,0,0,MutingStartTime_hour,MutingStartTime_minute,MutingStartTime_second) ],
    [ 'Normal',  new Date(0,0,0,MutingEndTime_hour,MutingEndTime_minute,MutingEndTime_second),  new Date(0,0,0,CallEndTime_hour,CallEndTime_minute,CallEndTime_second) ],
    [ 'Muting', new Date(0,0,0,MutingStartTime_hour,MutingStartTime_minute,MutingStartTime_second),  new Date(0,0,0,MutingEndTime_hour,MutingEndTime_minute,MutingEndTime_second) ]]);
  }
  else{
dataTable.addRows([
    [ 'Normal',  new Date(0,0,0,CallStartTime_hour,CallStartTime_minute,CallStartTime_second),  new Date(0,0,0,CallEndTime_hour,CallEndTime_minute,CallEndTime_second) ]
	]);  	
  }
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
			<h2 class="section_title"><a href="index.html">EchoLocate Dashboard Prototype</a></h2>
		</hgroup>
	</header> <!-- end of header bar -->
	
	
	<aside id="sidebar" class="column">
		<form action="/Calls.php" method = "get" class="quick_search">
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
	<header><h3 class="tabs_involved">Customer Audio Experience</h3>
	</header>
            <div id="timeline" style="width: 600px; height: 200px;"></div>
	</article>


				
		<article class="module width_full">
		<header><h3 class="tabs_involved">Aggregated Call Data</h3>
		</header>


		<div class="tab_container">
			<div id = "tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody> 
			<tr>
				<td>CallID</td>
				<td><?=$rows["CallID"]?></td>
			</tr>
			<tr>
				<td>CallDirection</td>
				<td><?=$rows["CallDirection"]?></td>
			</tr>
			<tr>
				<td>CallType</td>
				<td><?=$rows["CallType"]?></td>
			</tr>
			<tr>
				<td>StartTime</td>
				<td><?=$rows["StartTime"]?></td>
			</tr>
			<tr>
				<td>EndTime</td>
				<td><?=$rows["EndTime"]?></td>
			</tr>
			<tr>
				<td>CallSetupFailureReason</td>
				<td><?=$rows["CallSetupFailureReason"]?></td>
			</tr>
			<tr>
				<td>CallDropReason</td>
				<td><?=$rows["CallDropReason"]?></td>
			</tr>
			<tr>
				<td>CallStartLocationX</td>
				<td><?=$rows["CallStartLocationX"]?></td>
			</tr>
			<tr>
				<td>CallStartLocationY</td>
				<td><?=$rows["CallStartLocationY"]?></td>
			</tr>
			<tr>
				<td>CallEndLocationX</td>
				<td><?=$rows["CallEndLocationX"]?></td>
			</tr>
			<tr>
				<td>CallEndLocationY</td>
				<td><?=$rows["CallEndLocationY"]?></td>
			</tr>
			<tr>
				<td>MutingStartTime</td>
				<td><?=$rows["MutingStartTime"]?></td>
			</tr>
			<tr>
				<td>MutingEndTime</td>
				<td><?=$rows["MutingEndTime"]?></td>
			</tr>
			<tr>
				<td>ApplicationPackageName</td>
				<td><?=$rows["ApplicationPackageName"]?></td>
			</tr>
			<tr>
				<td>Number</td>
				<td><?=$rows["MSISDN"]?></td>
			</tr>
			<tr>
				<td>Model</td>
				<td><?=$rows["Model"]?></td>
			</tr>
		</tbody>
		</table>
		</div>
		</div>
		</article>

		<article class="module width_full">
		<header><h3 class="tabs_involved">Call State Intents</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th>CallID</th> 
    				<th>Time</th>
    				<th>CallState</th>
    				<th>Network Type</th>
    				<th>Network Band</th>
    				<th>Signal Strength</th> 
				</tr> 
			</thead> 
			<tbody> 
	<?php
    $pagesize=10;
    $rs = mysql_query("SELECT COUNT(*) FROM CallState_Intent");
    $rows = mysql_fetch_array($rs);
    $row_count = $rows[0];
    #count page number needed
    $pages = intval($row_count/$pagesize)+1;
    if($row_count%$pagesize){
    	$row_count++;
    }
    if(isset($_GET['page'])){
    	$page = intval($_GET['page']);
    }
    else{
    	$page = 1;
    }
    $offset = $pagesize*($page-1);
    $rs = mysql_query("SELECT * FROM CallState_Intent WHERE CallId = $callID ORDER BY ID asc limit $offset,$pagesize");
    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    ?>

				<tr> 
   					<td><?=$myrow["CallId"]?></td> 
    				<td><?=$myrow["Timestamp"]?></td> 
    				<td><?=$myrow["CallState"]?></td>
    				<?php
    					$type = $myrow["VoiceAccessNetworkStateType"];
    					$signal = intval($myrow["VoiceAccessNetworkStateSignal"]);
    					if($type == "LTE"){
    						if($signal<=-120){
    							$signal = $signal." (1 bar)";
    						}
    						elseif ($signal>-120 and $signal<=-115) {
    							$signal = $signal." (2 bars)";
    						}
    						elseif ($signal>-115 and $signal<=-110) {
    							$signal = $signal." (3 bars)";
    						}
     						elseif ($signal>-110 and $signal<=-100) {
    							$signal = $signal." (4 bars)";
    						}
    						else{
    							$signal = $signal." (5 bars)";
    						}  						
    					}
    					elseif($type = "3G"){
    						$signal = -133+($signal*2);
    						if($signal<=-113){
    							$signal = $signal." (1 bar)";
    						}
    						elseif ($signal>-113 and $signal<=-103) {
    							$signal = $signal." (2 bars)";
    						}
    						elseif ($signal>-103 and $signal<=-97) {
    							$signal = $signal." (3 bars)";
    						}
     						elseif ($signal>-97 and $signal<=-89) {
    							$signal = $signal." (4 bars)";
    						}
    						else{
    							$signal = $signal." (5 bars)";
    						}  						
    					}
    				?>
    				<td><?=$type?></td> 
    				<td><?=$myrow["VoiceAccessNetworkStateBand"]?></td>
    				<td><?=$signal?></td>
				</tr>  
	<?php
		}
		while($myrow = mysql_fetch_array($rs));
	}
	?>
			</tbody> 
			</table>
</article>



<article class="module width_full">
		<header><h3 class="tabs_involved">UI State Intents</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th>CallID</th> 
    				<th>Time</th>
    				<th>UIState</th>
    				<th>Network Type</th>
    				<th>Network Band</th>
    				<th>Signal Strength</th> 
				</tr> 
			</thead> 
			<tbody> 
	<?php
    $pagesize=10;
    $rs = mysql_query("SELECT COUNT(*) FROM UIState_Intent");
    $rows = mysql_fetch_array($rs);
    $row_count = $rows[0];
    #count page number needed
    $pages = intval($row_count/$pagesize)+1;
    if($row_count%$pagesize){
    	$row_count++;
    }
    if(isset($_GET['page'])){
    	$page = intval($_GET['page']);
    }
    else{
    	$page = 1;
    }
    $offset = $pagesize*($page-1);
    $rs = mysql_query("SELECT * FROM UIState_Intent WHERE CallId = $callID ORDER BY ID asc limit $offset,$pagesize");
    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    ?>

				<tr> 
   					<td><?=$myrow["CallId"]?></td> 
    				<td><?=$myrow["Timestamp"]?></td> 
    				<td><?=$myrow["UIState"]?></td>
    				<td><?=$myrow["VoiceAccessNetworkStateType"]?></td> 
    				<td><?=$myrow["VoiceAccessNetworkStateBand"]?></td>
    				<td><?=$myrow["VoiceAccessNetworkStateSignal"]?></td>
				</tr>  
	<?php
		}
		while($myrow = mysql_fetch_array($rs));
	}
	?>
			</tbody> 
			</table>
			</article>
	</section>

  </body>
</html>