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
?>
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

<?php
        $problem = "";
        $type = "";
        if(isset($_GET['row'])){
            $row = intval($_GET['row']);
            $column = intval($_GET['column']);
            if($row == 0){
                $type = "VoLTE";
            }
            elseif ($row ==1) {
                $type = "WFC";
            }
            elseif ($row ==2) {
                $type = "3G";
            }
            elseif ($row ==3) {
                $type = "2G";
            }
            else {
                $type = "Out-Range";
            }

            if($column == 1){
                $problem = "Normal";
            }
            else if($column ==2){
                $problem = "SetupFailure";
            }
            else{
                $problem = "CallDrop";
            }
        }
?>
		<article class="module width_full">
		<header><h3 class="tabs_involved">Problematic Calls : <?php echo $problem ?></h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
                    <th>CallID</th>
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
    $rs = "";
    $rs1 = "";
    $page = 1;
    $pages = 1;
    if($problem == "SetupFailure"){
        if(isset($_GET['number'])){
            $msisdn = "+1".$_GET['number'];
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '$type' AND CallSetupFailureReason != 'NULL' AND MSISDN = $msisdn ");
                $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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

        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE CallType = '$type' AND CallSetupFailureReason != 'NULL' AND MSISDN = $msisdn ORDER BY ID desc limit $offset,$pagesize");       
        }
        else{
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '$type' AND CallSetupFailureReason != 'NULL' ");
                $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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

        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE CallType = '$type' AND CallSetupFailureReason != 'NULL' ORDER BY ID desc limit $offset,$pagesize");

        }
        }

    else{
        if(isset($_GET['number'])){
            $msisdn = "+1".$_GET['number'];
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '$type' AND CallDropReason != 'NULL' AND MSISDN = $msisdn ");
                $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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

        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE CallType = '$type' AND CallDropReason != 'NULL' AND MSISDN = $msisdn ORDER BY ID desc limit $offset,$pagesize");
        }

        else{
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE CallType = '$type' AND CallDropReason != 'NULL' ");
                $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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

        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE CallType = '$type' AND CallDropReason != 'NULL' ORDER BY ID desc limit $offset,$pagesize");
        }        
    }
    
    if (isset($_GET['rowMute'])){
        if(isset($_GET['number'])){
            $msisdn = "+1".$_GET['number'];
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE MutingStartTime != 'NULL' AND MSISDN = $msisdn ");
                $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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

        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE MutingStartTime != 'NULL' AND MSISDN = $msisdn ORDER BY ID desc limit $offset,$pagesize");
    }
        else{
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE MutingStartTime != 'NULL' ");
                $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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

        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE MutingStartTime != 'NULL' ORDER BY ID desc limit $offset,$pagesize");
    }               
    }

    if(isset($_GET['keyword'])){
        $msisdn = "+1".$_GET['keyword'];
        $rs = mysql_query("SELECT COUNT(*) FROM CallAggregatedData WHERE MSISDN = $msisdn");
        $rows = mysql_fetch_array($rs);
        $row_count = $rows[0];
        $pages = intval($row_count/$pagesize);
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
        $rs1 = mysql_query("SELECT * FROM CallAggregatedData WHERE MSISDN = $msisdn");
    }

    if($myrow = mysql_fetch_array($rs1)){
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
    		elseif ($CallDropReason!="NULL" && $MutingStartTime =="NULL"){
    			$Problem = "Unintended Call Drop";
    		}
    		elseif ($MutingStartTime!="NULL" && $CallDropReason =="NULL"){
    			$Problem = "Audio Muting";
    		}
            elseif ($MutingStartTime!="NULL" && $CallDropReason !="NULL"){
                $Problem = "Audio Muting & Call Drop";
            }
    		elseif ($CallSetupFailureReason!="NULL"){
    			$Problem = "Call Setup Failure";
    		}
    		$Package_name = $myrow["ApplicationPackageName"];
    		$MSISDN = $myrow["MSISDN"];

    ?>

				<tr> 
                    <td><?=$CallID?></td> 
    				<td><?=$Time?></td> 
    				<td><?=$Direction?></td>
    				<td><?=$Type?></td> 
    				<td><?=$Duration?></td>
    				<td><?=$Problem?></td>
    				<td><?=$MSISDN?></td>
    				<td><a href=detail.php?callID=<?php echo $CallID; ?>>Detail</a></td>
				</tr>  
	<?php
		}
		while($myrow = mysql_fetch_array($rs1));
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
			<a href = "Calls.php?page=<?=$first?>">First Page     </a><a href = "Calls.php?page=<?=$prev?>">Prev    </a>
	<?php
		}
		if($page<$pages){
	?>
			<a href = "Calls.php?page=<?=$next?>">Next    </a><a href = "Calls.php?page=<?=$last?>">Last Page    </a>
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