<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		
		
		$crimeType = isset($_POST['crimeType']) ? mysql_real_escape_string($_POST['crimeType']) : "";
		$Magistrate = isset($_POST['Magistrate']) ? mysql_real_escape_string($_POST['Magistrate']) : "";
		
		// Select data from database
		$sql = "SELECT `UWA_CASEID`,`POLICE_CASEID`,`CRIME_TYPE`,DATE_FORMAT( FROM_UNIXTIME(`COURTDATE`) ,  '%d/%m-%Y' ) as `COURTDATE`,DATE_FORMAT( FROM_UNIXTIME(`ENDEDDATE` ) ,  '%d/%m-%Y' ) as `ENDEDDATE`,`UWA_PROSECUTOR`,`STATE_PROSECUTOR`,`DEFENCE_LAWYER`,`MAGISTRATE`FROM `cases` WHERE `CRIME_TYPE`='".$crimeType ."' AND  `MAGISTRATE`= '".$Magistrate."' ";
		
		
 
		// Set a resultset. 
		$qur = mysql_query($sql) or die(mysql_error());
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("POLICE_CASEID" => $POLICE_CASEID,"CRIME_TYPE"=>$CRIME_TYPE, "COURTDATE" => $COURTDATE,'UWA_CASEID'=>$UWA_CASEID,'ENDEDDATE'=>$ENDEDDATE,'UWA_PROSECUTOR'=>$UWA_PROSECUTOR,'STATE_PROSECUTOR'=>$STATE_PROSECUTOR,'DEFENCE_LAWYER'=>$DEFENCE_LAWYER, 'MAGISTRATE' => $MAGISTRATE);
		} 
	}
	else
	{
		$json = array("status" => 0, "msg" => "Request method not accepted!");
	}
	@mysql_close($conn);
 
	/* Output header */
	header('Content-type: application/json');
  	// Returns the JSON representation of fetched data
	print(json_encode($result));

 ?>
 