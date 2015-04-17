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
    	die('Could no connect:'.mysql_error());
    }
    mysql_select_db('Echo');
if(!isset($_GET['keyword'])){
    $info = "";
    $tmp = "";
    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = 'VoLTE' " );
    $rows = mysql_fetch_array($rs);
    $volte_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '3G' " );
    $rows = mysql_fetch_array($rs);
    $threeg_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '2G' " );
    $rows = mysql_fetch_array($rs);
    $twog_count = intval($rows[0]);

//////////////////////////////////
    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallDropReason != 'NULL' AND CallType = 'VoLTE' " );
    $rows = mysql_fetch_array($rs);
    $volte_drop_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason != 'NULL' AND CallType = 'VoLTE' " );
    $rows = mysql_fetch_array($rs);
    $volte_setupfailure_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason = 'NULL' AND CallDropReason = 'NULL' AND CallType = 'VoLTE' " );
    $rows = mysql_fetch_array($rs);
    $volte_normal_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallDropReason != 'NULL' AND CallType = '3G' " );
    $rows = mysql_fetch_array($rs);
    $g3_drop_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason != 'NULL' AND CallType = '3G' " );
    $rows = mysql_fetch_array($rs);
    $g3_setupfailure_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason = 'NULL' AND CallDropReason = 'NULL' AND CallType = '3G' " );
    $rows = mysql_fetch_array($rs);
    $g3_normal_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallDropReason != 'NULL' AND CallType = '2G' " );
    $rows = mysql_fetch_array($rs);
    $g2_drop_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason != 'NULL' AND CallType = '2G' " );
    $rows = mysql_fetch_array($rs);
    $g2_setupfailure_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason = 'NULL' AND CallDropReason = 'NULL' AND CallType = '2G' " );
    $rows = mysql_fetch_array($rs);
    $g2_normal_count = intval($rows[0]);
    ////////////////////////////////////
	$rs = mysql_query("SELECT * FROM CallAggregatedData WHERE MutingStartTime != 'NULL' AND CallType = 'VoLTE' " );
	$mute_short_cnt = 0;
	$mute_long_cnt = 0;
    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    		$Time = $myrow["MutingStartTime"];
    		$endTime = $myrow["MutingEndTime"];
    		$Duration = strtotime($endTime) - strtotime($Time);
    		if($Duration>10){
    			$mute_long_cnt = $mute_long_cnt+1;
    		}
    		else{
    			$mute_short_cnt = $mute_short_cnt+1;
    		}
    	}
    while ($myrow = mysql_fetch_array($rs));
}

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE MutingStartTime = 'NULL' AND CallType = 'VoLTE' " );
    $rows = mysql_fetch_array($rs);
    $nomute_count = intval($rows[0]);
}
?>

<?php
    if(isset($_GET['keyword'])) {
    $msisdn = "+1".$_GET['keyword'];
    $info = ":" . $_GET['keyword'];
    $tmp = $_GET['keyword'];
    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = 'VoLTE' AND MSISDN = $msisdn " );
    $rows = mysql_fetch_array($rs);
    $volte_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '3G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $threeg_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '2G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $twog_count = intval($rows[0]);

//////////////////////////////////
    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallDropReason != 'NULL' AND CallType = 'VoLTE' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $volte_drop_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason != 'NULL' AND CallType = 'VoLTE' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $volte_setupfailure_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason = 'NULL' AND CallDropReason = 'NULL' AND CallType = 'VoLTE' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $volte_normal_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallDropReason != 'NULL' AND CallType = '3G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $g3_drop_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason != 'NULL' AND CallType = '3G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $g3_setupfailure_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason = 'NULL' AND CallDropReason = 'NULL' AND CallType = '3G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $g3_normal_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallDropReason != 'NULL' AND CallType = '2G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $g2_drop_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason != 'NULL' AND CallType = '2G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $g2_setupfailure_count = intval($rows[0]);

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallSetupFailureReason = 'NULL' AND CallDropReason = 'NULL' AND CallType = '2G' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $g2_normal_count = intval($rows[0]);
    ////////////////////////////////////
    $rs = mysql_query("SELECT * FROM CallAggregatedData WHERE MutingStartTime != 'NULL' AND CallType = 'VoLTE' AND MSISDN = $msisdn" );
    $mute_short_cnt = 0;
    $mute_long_cnt = 0;
    if($myrow = mysql_fetch_array($rs)){
        $i=0;
        do{
            $i++;
            $Time = $myrow["MutingStartTime"];
            $endTime = $myrow["MutingEndTime"];
            $Duration = strtotime($endTime) - strtotime($Time);
            if($Duration>10){
                $mute_long_cnt = $mute_long_cnt+1;
            }
            else{
                $mute_short_cnt = $mute_short_cnt+1;
            }
        }
    while ($myrow = mysql_fetch_array($rs));
}

    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE MutingStartTime = 'NULL' AND CallType = 'VoLTE' AND MSISDN = $msisdn" );
    $rows = mysql_fetch_array($rs);
    $nomute_count = intval($rows[0]);
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

  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1.0', {'packages':['corechart']});
      google.load('visualization', '1.1', {
    'packages': ['bar']
});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

      	var volte =  eval("<?php echo $volte_count; ?>");
      	var twog =  eval("<?php echo $twog_count; ?>");
      	var threeg = eval("<?php echo $threeg_count; ?>");
                // Create the data table.
        
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Type');
        data.addColumn('number', 'Number');
        data.addRows([
          ['VoLTE', volte],
          ['3G Call', threeg],
          ['2G Call', twog]
        ]);
        // Set chart options
        var options = {'title':'Call Type Statistics',
                       'width':400,
                       'height':300};
                               // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

  <script type="text/javascript">
google.setOnLoadCallback(drawStuff);

function drawStuff() {

	var volte_drop_count = eval("<?php echo $volte_drop_count;?>");
	var volte_setupfailure_count = eval("<?php echo $volte_setupfailure_count;?>");
	var volte_normal_count = eval("<?php echo $volte_normal_count;?>");

	var g3_drop_count = eval("<?php echo $g3_drop_count;?>");
	var g3_setupfailure_count = eval("<?php echo $g3_setupfailure_count;?>");
	var g3_normal_count = eval("<?php echo $g3_normal_count;?>");

	var g2_drop_count = eval("<?php echo $g2_drop_count;?>");
	var g2_setupfailure_count = eval("<?php echo $g2_setupfailure_count;?>");
	var g2_normal_count = eval("<?php echo $g2_normal_count;?>");

    function selectHandler() {
        var selectedItem = chart.getSelection()[0];
        if (selectedItem) {
            var row = selectedItem.row;
            var column = selectedItem.column;
            var tmp = "<?php echo $tmp; ?>";
            //alert('The user selected ' + tmp);
            if(column>1){
                if(tmp != ""){
                    window.location.href = "Calls.php?row="+row+"&column="+column+"&number="+tmp;
                }
                else{
                    window.location.href = "Calls.php?row="+row+"&column="+column;
                }
            }
        }
    }

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Type');
    data.addColumn('number', 'Normal');
    data.addColumn('number', 'Setup Failure');
    data.addColumn('number', 'Drop');
    data.addRows([
        ['VoLTE',volte_normal_count , volte_setupfailure_count, volte_drop_count],
        ['WFC', 0, 0, 0],
        ['3G', g3_normal_count, g3_setupfailure_count, g3_drop_count],
        ['2G', g2_normal_count, g2_setupfailure_count, g2_drop_count],
        ['Out-of-Coverage', 0, 0, 0]
    ]);
    // Set chart options
    var options = {
        title: 'Call Drop Analysis',
        isStacked: true,
        width: 400,
        height: 300,
        colors: ['green','orange','red']
        }
    //window.alert(volte_drop_count);
    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.ColumnChart(document.getElementById('barchart_values'));

  google.visualization.events.addListener(chart, 'select', selectHandler);


    chart.draw(data, options);
};
  </script>

  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      //google.load('visualization', '1.0', {'packages':['corechart']});
      google.setOnLoadCallback(drawPieChart);
      function drawPieChart() {

      	var mute_short_cnt =  eval("<?php echo $mute_short_cnt; ?>");
      	var mute_long_cnt =  eval("<?php echo $mute_long_cnt; ?>");
      	var nomute_count = eval("<?php echo $nomute_count; ?>");
        // Create the data table.
        
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Type');
        data.addColumn('number', 'Number');
        data.addRows([
          ['10+ seconds', mute_long_cnt],
          ['5~10 seconds', mute_short_cnt],
          ['Normal', nomute_count]
        ]);
        // Set chart options
        var options = {'title':'# of calls with missing audio problem.',
                       'width':400,
                       'height':300,
                       'slices':{
                            0:{ color: 'red'},
                            1:{ color: 'orange'},
                            2:{ color: 'green'}
                       }
                   };
                               // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('pie1_div'));
		function selectHandler() {
	    	var selectedItem = chart.getSelection()[0];
	    	if (selectedItem) {
	    		var row = selectedItem.row;
                var tmp = "<?php echo $tmp; ?>";
	            if(row < 2){
                    if(tmp!=''){
                        window.location.href = "Calls.php?rowMute="+row+"&number="+tmp;    
                    }
                    else{
	            	window.location.href = "Calls.php?rowMute="+row;	
                    }            
            }
	    	}
	  	}
	  	google.visualization.events.addListener(chart, 'select', selectHandler);        
        chart.draw(data, options);
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
		<form action="/volteServer.php" method = "get" class="quick_search">
			<input name = "keyword" type="text" value="Phone Number Lookup(10-digit only)" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		<hr/>
		<ul class="toggle">
		<li class="icn_edit_article"><a href="#">Statistics</a></li>
		<li class="icn_new_article"><a href="map.php">Call on the Map</a></li>		
		</ul>		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2015 T-Mobile and UMich</strong></p>
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id="main" class="column">
	<article class="module width_full">
	<header><h3 class="tabs_involved">Call Statistics<?php echo $info;?></h3>
	</header>
		<div id ="whole" style="position: relative; overflow: hidden;">
		<div id = "chart_div" style="position: relative; width:40%; float:left;" ></div>
		<div id="barchart_values" style="position: relative; width:60%; float:left;"></div>
		</div>
		</article>

		<article class="module width_full">
	<header><h3 class="tabs_involved">Missing Audio Problem<?php echo $info;?></h3>
	</header>
		<div id ="whole" style="position: relative; overflow: hidden;">
		<div id = "pie1_div" style="position: relative; width:50%; float:left;" ></div>
		<div id="pie2_div" style="position: relative; width:50%; float:left;"></div>
		</div>
		</article>



		<article class="module width_full">
		<header><h3 class="tabs_involved">Recent Calls<?php echo $info;?></h3>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>Time</th>
    				<th>Direction</th>
    				<th>Type</th>
    				<th>Duration</th>
    				<th>Problem</th> 
    				<th>Number</th>
    				<th>Detail </th>
				</tr> 
			</thead> 
			<tbody> 

	<?php
    $pagesize=5;
    if(isset($_GET['number'])){
        $msisdn = "+1".$_GET['number'];
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE MSISDN = $msisdn");
    }
    else{
    $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData");
}
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
    $rs = mysql_query("SELECT * FROM CallAggregatedData ORDER BY ID desc limit $offset,$pagesize");

    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    		$CallID = $myrow["CallID"];
    		$Time = $myrow["StartTime"];
    		$Direction = $myrow["CallDirection"];
    		$Type = $myrow["CallType"];

    		$endTime = $myrow["EndTime"];
    		$Duration = strtotime($endTime) - strtotime($Time);
    		#echo "Duration",$Duration;
    		$CallSetupFailureReason = $myrow["CallSetupFailureReason"];
    		$CallDropReason = $myrow["CallDropReason"];
    		$MutingStartTime = $myrow["MutingStartTime"];
    		$Problem = "";
    		if($CallSetupFailureReason == "NULL" && $CallDropReason =="NULL" && $MutingStartTime=="NULL"){
    			$Problem = "NULL";
    		}
    		elseif ($CallDropReason!="NULL"){
    			$Problem = "Unintended Call Drop";
    		}
    		elseif ($MutingStartTime!="NULL"){
    			$Problem = "Audio Muting";
    		}
    		elseif ($CallSetupFailureReason!="NULL"){
    			$Problem = "Call Setup Failure";
    		}
    		$Package_name = $myrow["ApplicationPackageName"];
    		$MSISDN = $myrow["MSISDN"];

    ?>

				<tr> 
    				<td><?=$Time?></td> 
    				<td><?=$Direction?></td>
    				<td><?=$Type?></td> 
    				<td><?=$Duration?> seconds</td>
    				<td><?=$Problem?></td>
    				<td><?=$MSISDN?></td>
    				<td><a href=detail.php?callID=<?php echo $CallID; ?>>Detail</a></td>
				</tr>  
	<?php
		}
		while($myrow = mysql_fetch_array($rs));
	}
	$first = 1;
	$prev = $page-1;
	$next = $page+1;
	$last = $pages;
	?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			<div align ='center'>Total <?=$pages?> Pages (<?=$page?>/<?=$pages?>)</div>
			<div align = 'center'>
	<?php
		if($page>1){
	?>
			<a href = "volteServer.php?page=<?=$first?>">First Page     </a><a href = "volteServer.php?page=<?=$prev?>">Prev    </a>
	<?php
		}
		if($page<$pages){
	?>
			<a href = "volteServer.php?page=<?=$next?>">Next    </a><a href = "volteServer.php?page=<?=$last?>">Last Page    </a>
	<?php
		}
	?>
			</div>		
		</div><!-- end of .tab_container -->
		
	</article><!-- end of content manager article -->
			
		<!--

		<h4 class="alert_warning">A Warning Alert</h4>
		
		<h4 class="alert_error">An Error Message</h4>
		
		<h4 class="alert_success">A Success Message</h4>
		
		<article class="module width_full">
			<header><h3>Basic Styles</h3></header>
				<div class="module_content">
					<h1>Header 1</h1>
					<h2>Header 2</h2>
					<h3>Header 3</h3>
					<h4>Header 4</h4>
					<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>

<p>Donec id elit non mi porta <a href="#">link text</a> gravida at eget metus. Donec ullamcorper nulla non metus auctor fringilla. Cras mattis consectetur purus sit amet fermentum. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>

					<ul>
						<li>Donec ullamcorper nulla non metus auctor fringilla. </li>
						<li>Cras mattis consectetur purus sit amet fermentum.</li>
						<li>Donec ullamcorper nulla non metus auctor fringilla. </li>
						<li>Cras mattis consectetur purus sit amet fermentum.</li>
					</ul>
				</div>
		</article>
		<div class="spacer"></div>   -->
	</section>

  </body>
</html>