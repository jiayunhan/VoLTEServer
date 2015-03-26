<html>
  <head>

  </head>
  <body>
    <?php
    $conn = mysql_connect(':/cloudsql/psychic-rush-755:database',
      'root','');
    if(array_key_exists('result',$_POST)){
      $metadata = json_decode($_POST["result"],true);
      #$content = var_dump($metadata);
      $content = $_POST["result"];
      $callid = rand(1,999999);
      mysql_select_db("Echo");
      $type = "";
      $sql = "INSERT INTO JSON (content) VALUES ('$content') ";
      $result = mysql_query($sql);
      echo $metadata
            #foreach ($metadata as $key => $value){

       #  $sql = "INSERT INTO results (callID,eventName,Direction,softwareVersion,timeStamp,manufacturer,model,IMEI,MSISDN,networkType,cellID,lacID,latitude,longitude,UMTSPSC,rssnr,gsmSignalStrength,rsrp,feedback) VALUES ($callid,'$event','$direction','$version','$time','$manufacturer','$model','$imei','$msisdn','$networkType',$cell_id,$lac_id,$latitude,$longitude,$psc,$rssnr,$gsmSignalStrength,$rsrp,'$feedback')";
        # $result = mysql_query($sql);
         #echo "Last insert record has id ",mysql_insert_id();
         #}
  }
  ?>
  </body>
</html>