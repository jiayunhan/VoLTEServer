<html>
  <head>

  </head>
  <body>
    <?php
    $conn = mysql_connect(':/cloudsql/psychic-rush-755:database',
      'root','');
    if(array_key_exists('result',$_POST)){
      $metadata = json_decode($_POST["result"],true);
      mysql_select_db("Echo");
      $AggregatedDate_raw = json_decode($metadata["CallAggregatedData"],true);
      $UIState_Intents = $metadata["UIState_Intents"];
      $CallState_Intents = $metadata["CallState_Intents"];
      
      /* AggregatedCall*/
      $CallId = intval($AggregatedDate_raw["CallID"]);
      $CallDirection = $AggregatedDate_raw["CallDirection"];
      $CallType = $AggregatedDate_raw["CallType"];

      if($CallType != "VoLTE" or $CallId == 0){
        $CallId = rand(0,99999999);
      }

      $StartTime = $AggregatedDate_raw["StartTime"];

      if($StartTime == ""){
        exit(0);
      }


      $EndTime = $AggregatedDate_raw["EndTime"];
      $CallSetupFailureReason = $AggregatedDate_raw["CallSetupFailureReason"];
      $CallDropReason = $AggregatedDate_raw["CallDropReason"];
      $CallStartLocationX = floatval($AggregatedDate_raw["CallStartLocationX"]);
      $CallStartLocationY = floatval($AggregatedDate_raw["CallStartLocationY"]);
      $CallEndLocationX = floatval($AggregatedDate_raw["CallEndLocationX"]);
      $CallEndLocationY = floatval($AggregatedDate_raw["CallEndLocationY"]);
      $MutingStartTime = $AggregatedDate_raw["MutingStartTime"];
      $MutingEndTime = $AggregatedDate_raw["MutingEndTime"];
      $MSISDN = $AggregatedDate_raw["MSISDN"];
      $Model = $AggregatedDate_raw["Model"];
      $ApplicationPackageName = $AggregatedDate_raw["ApplicationPackageName"];
      $sql =  "INSERT INTO CallAggregatedData (CallID,CallDirection,CallType,StartTime,EndTime,CallSetupFailureReason,CallDropReason,CallStartLocationX,CallStartLocationY,CallEndLocationX,CallEndLocationY,MutingStartTime,MutingEndTime,ApplicationPackageName,MSISDN,Model) VALUES ($CallId,'$CallDirection','$CallType','$StartTime','$EndTime','$CallSetupFailureReason','$CallDropReason',$CallStartLocationX,$CallStartLocationY,$CallEndLocationX,$CallEndLocationY,'$MutingStartTime','$MutingEndTime','$ApplicationPackageName','$MSISDN','$Model')";
      $result = mysql_query($sql);

      /* CallState*/
      //echo var_dump($metadata["CallState_Intents"]);
      foreach ($CallState_Intents as $value) {
        # code...
        $CallState = json_decode($value,'true');
        $State = $CallState["CallState"];
        $Timestamp = $CallState["Timestamp"];
        $VoiceAccessNetworkStateType = $CallState["VoiceAccessNetworkStateType"];
        $VoiceAccessNetworkStateBand = $CallState["VoiceAccessNetworkStateBand"];
        $VoiceAccessNetworkStateSignal = $CallState["VoiceAccessNetworkStateSignal"];
       $sql =  "INSERT INTO CallState_Intent (CallId,Timestamp,CallState,VoiceAccessNetworkStateType,VoiceAccessNetworkStateBand,VoiceAccessNetworkStateSignal) VALUES ($CallId,'$Timestamp','$State','$VoiceAccessNetworkStateType','$VoiceAccessNetworkStateBand',$VoiceAccessNetworkStateSignal)";
       $result = mysql_query($sql);

      }
      /* CallState*/
      //echo var_dump($metadata["CallState_Intents"]);
      foreach ($UIState_Intents as $value) {
        # code...
        $UIState = json_decode($value,'true');
        $Timestamp = $UIState["Timestamp"];
        $State = $UIState["UICallState"];
        $VoiceAccessNetworkStateType = $UIState["VoiceAccessNetworkStateType"];
        $VoiceAccessNetworkStateBand = $UIState["VoiceAccessNetworkStateBand"];
        $VoiceAccessNetworkStateSignal = $UIState["VoiceAccessNetworkStateSignal"];
        $sql =  "INSERT INTO UIState_Intent (CallId,Timestamp,UIState,VoiceAccessNetworkStateType,VoiceAccessNetworkStateBand,VoiceAccessNetworkStateSignal) VALUES ($CallId,'$Timestamp','$State','$VoiceAccessNetworkStateType','$VoiceAccessNetworkStateBand',$VoiceAccessNetworkStateSignal)";
       $result = mysql_query($sql);
       //echo $State;
      }
      /*
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
         }*/
  }
  ?>
  </body>
</html>