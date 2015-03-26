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
			<h2 class="section_title"><a href="index.html">Audio Issue Report Dashboard</a></h2>
		</hgroup>
	</header> <!-- end of header bar -->
	
	
	<aside id="sidebar" class="column">
		<form action="/search" method = "get" class="quick_search">
			<input name = "keyword" type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		<hr/>
		<h3>Content</h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="#">New Article</a></li>
			<li class="icn_edit_article"><a href="#">Edit Articles</a></li>
		</ul>
		<h3>Users</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="#">Add New User</a></li>
			<li class="icn_view_users"><a href="#">View Users</a></li>
			<li class="icn_profile"><a href="#">Your Profile</a></li>
		</ul>
		<h3>Admin</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="#">Options</a></li>
			<li class="icn_security"><a href="#">Security</a></li>
			<li class="icn_jump_back"><a href="#">Logout</a></li>
		</ul>
		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2014 Jack</strong></p>
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<h4 class="alert_info">Many functionalities are under development. Use the Quick Search Box to filter by MSISDN</h4>

		<!--<article class="module width_full">
			<header><h3>Stats</h3></header>
			<div class="module_content">
				<article class="stats_graph">
					<img src="http://chart.apis.google.com/chart?chxr=0,0,3000&chxt=y&chs=520x140&cht=lc&chco=76A4FB,80C65A&chd=s:Tdjpsvyvttmiihgmnrst,OTbdcfhhggcTUTTUadfk&chls=2|2&chma=40,20,20,30" width="520" height="140" alt="" />
				</article>
				
				<article class="stats_overview">
					<div class="overview_today">
						<p class="overview_day">Today</p>
						<p class="overview_count">1,876</p>
						<p class="overview_type">Hits</p>
						<p class="overview_count">2,103</p>
						<p class="overview_type">Views</p>
					</div>
					<div class="overview_previous">
						<p class="overview_day">Yesterday</p>
						<p class="overview_count">1,646</p>
						<p class="overview_type">Hits</p>
						<p class="overview_count">2,054</p>
						<p class="overview_type">Views</p>
					</div>
				</article>
				<div class="clear"></div>
			</div>
		</article><!-- end of stats article -->
		
		<article class="module width_full">
		<header><h3 class="tabs_involved">Content Table</h3>
		<!--<ul class="tabs">
   			<li><a href="#tab1">Posts</a></li>
    		<li><a href="#tab2">Comments</a></li>
		</ul> -->
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th>EntryID</th> 
    				<th>MSISDN</th> 
    				<th>Call ID</th>
    				<th>Event</th>
    				<th>Direction</th>
    				<th>Time</th> 
    				<th>Network</th>
    				<th>Cell ID</th>
    				<th>LAC ID</th>
    				<th>rsrp</th>
    				<th>rssnr</th>
    				<th>GsmSignalStrength</th>
    				<th>Manufacturer</th>
    				<th>Model</th>
    				<th>Version</th>
    				<th>IMEI</th>
    				<th>Latitude</th>
    				<th>Longitude</th>
    				<th>Feedback</th>
				</tr> 
			</thead> 

			<tbody> 

	<?php
    $pagesize=10;
    $conn = mysql_connect(':/cloudsql/psychic-rush-755:database',
    	'root','');
    if(!$conn){
    	die('Could no connect:'.mysql_error());
    }
    mysql_select_db('report');
    $rs = mysql_query("SELECT COUNT(*) FROM results");
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
    $rs = mysql_query("SELECT * FROM results ORDER BY entryID desc limit $offset,$pagesize");

    if(isset($_GET['keyword'])){
    	$msisdn = "1".$_GET['keyword'];
    	$rs = mysql_query("SELECT * FROM results WHERE MSISDN = $msisdn");
    	$rows = mysql_fetch_array($rs);
    	$row_count = $rows[0];
    	$pages = intval($row_count/$pagesize);
    }

    if($myrow = mysql_fetch_array($rs)){
    	$i=0;
    	do{
    		$i++;
    ?>

				<tr> 
   					<td><?=$myrow["entryID"]?></td> 
    				<td><?=$myrow["MSISDN"]?></td> 
    				<td><?=$myrow["callID"]?></td>
    				<td><?=$myrow["eventName"]?></td> 
    				<td><?=$myrow["Direction"]?></td>
    				<td><?=$myrow["timeStamp"]?></td>
    				<td><?=$myrow["networkType"]?></td>
    				<td><?=$myrow["cellID"]?></td>
    				<td><?=$myrow["lacID"]?></td>
    				<td><?=$myrow["rsrp"]?></td>
    				<td><?=$myrow["rssnr"]?></td>
    				<td><?=$myrow["gsmSignalStrength"]?></td>
    				<td><?=$myrow["manufacturer"]?></td>
    				<td><?=$myrow["model"]?></td>
    				<td><?=$myrow["softwareVersion"]?></td>
    				<td><?=$myrow["IMEI"]?></td>
    				<td><?=$myrow["latitude"]?></td>
    				<td><?=$myrow["longitude"]?></td>
    				<td><?=$myrow["feedback"]?></td>
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
		

		
		<div class="clear"></div>
		
		<article class="module width_full">
			<header><h3>Post New Article</h3></header>
				<div class="module_content">
						<fieldset>
							<label>Post Title</label>
							<input type="text">
						</fieldset>
						<fieldset>
							<label>Content</label>
							<textarea rows="12"></textarea>
						</fieldset>
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Category</label>
							<select style="width:92%;">
								<option>Articles</option>
								<option>Tutorials</option>
								<option>Freebies</option>
							</select>
						</fieldset>
						<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Tags</label>
							<input type="text" style="width:92%;">
						</fieldset><div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<select>
						<option>Draft</option>
						<option>Published</option>
					</select>
					<input type="submit" value="Publish" class="alt_btn">
					<input type="submit" value="Reset">
				</div>
			</footer>
		</article><!-- end of post new article -->
		
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