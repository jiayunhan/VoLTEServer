<html>
  <head>

  </head>
  <body>
    <?php
    $conn = mysql_connect(':/cloudsql/psychic-rush-755:database',
      'root','');
    if(array_key_exists('result',$_POST)){
      $metadata = json_decode($_POST["result"],true);
      $feedback = $_POST["feedback"];
      echo $_POST["result"];
      $callid = rand(1,999999);
      mysql_select_db("report");
      $direction = "";
      foreach ($metadata as $key => $value){
        $event_words = explode(" ",substr($key, 6));
        if(count($event_words)==3){
          if($event_words[0]=="Incoming"){
            $direction = "Incoming";
          }
          else if($event_words[0]=="Outgoing"){
            $direction = "Outgoing";
          }
        }
        echo $key;
      }
      foreach ($metadata as $key => $value){
         $event=substr($key,6);  
         $rsrp = intval( $value["rsrp"]);
         $rssnr = intval($value["rssnr"]);
         $gsmSignalStrength = intval($value["gsmSignalStrength"]);
         $networkType = $value["NetworkType"];
         $manufacturer = $value["Manufacturer"];
         #$manufacturer = str_replace(" ", "_", $manufacturer);
         $model = $value["Model"]; 
         $version = $value["SoftwareVersion"];
         $imei = $value["IMEI"];
         $lac_id = intval($value["LAC ID"]);
         $cell_id = intval($value["Cell ID"]);
         $psc = intval($value["UMTS PSC"]);
         $latitude = floatval($value["Latitude"]);
         $longitude = floatval($value["Longitude"]);
         $msisdn = $value["MSISDN"];
         $time = $value["Timestamp"];
         #$time = str_replace(" ", "_", $time);
         $sql = "INSERT INTO results (callID,eventName,Direction,softwareVersion,timeStamp,manufacturer,model,IMEI,MSISDN,networkType,cellID,lacID,latitude,longitude,UMTSPSC,rssnr,gsmSignalStrength,rsrp,feedback) VALUES ($callid,'$event','$direction','$version','$time','$manufacturer','$model','$imei','$msisdn','$networkType',$cell_id,$lac_id,$latitude,$longitude,$psc,$rssnr,$gsmSignalStrength,$rsrp,'$feedback')";
         $result = mysql_query($sql);
         echo "Last insert record has id ",mysql_insert_id();
         }
  }
  ?>
  </body>
</html>