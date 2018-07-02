<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$policeCaseId = isset($_POST['policeCaseId']) ? mysql_real_escape_string($_POST['policeCaseId']) : "";
		$arrestingOfficer = isset($_POST['arrestingOfficer']) ? mysql_real_escape_string($_POST['arrestingOfficer']) : "";
		$station = isset($_POST['station']) ? mysql_real_escape_string($_POST['station']) : "";
		$locationName = isset($_POST['locationName']) ? mysql_real_escape_string($_POST['locationName']) : "";
		$crimeType = isset($_POST['crimeType']) ? mysql_real_escape_string($_POST['crimeType']) : "";
		
		// Select data from database
		$sql = "SELECT `UWA_CASEID`,`POLICE_CASEID`,`PARTICIPANTS` ,`OFFICER_NAME`, DATE_FORMAT( FROM_UNIXTIME( `ARRESTDATE`) ,  '%d/%m-%Y' ) as ARRESTDATE,`ARRESTTIME` ,`OUTPOST`,`OUTPOST_NAME`,`LOCATION_LAT`,`LOCATION_LON`,`UTM_Z`,`DATUM` ,`LOCATION_NAME`,`DETECTION`, `OFFENCE`,`OFFENCE_LOCATION`,`MOTIVATION` FROM `arrests`  WHERE `POLICE_CASEID` = '" . $policeCaseId . "' AND  `OFFICER_NAME` = '" . $arrestingOfficer . "'  AND `OUTPOST`='".$station."' AND `LOCATION_NAME`= '" . $locationName . "'  AND `OFFENCE`= '" . $crimeType . "'";
		
		
 
		// Set a resultset. 
		$qur = mysql_query($sql) or die(mysql_error());
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("POLICE_CASEID" => $POLICE_CASEID, "PARTICIPANTS" => $PARTICIPANTS,'UWA_CASEID'=>$UWA_CASEID,'OFFICER_NAME'=>$OFFICER_NAME,'ARRESTDATE'=>$ARRESTDATE,'ARRESTTIME'=>$ARRESTTIME,'OUTPOST'=>$OUTPOST, 'OUTPOST_NAME' => $OUTPOST_NAME, 'LOCATION_LAT' => $LOCATION_LAT,'LOCATION_LON'=>$LOCATION_LON,'UTM_Z'=>$UTM_Z,'DATUM'=>$DATUM,'LOCATION_NAME'=>$LOCATION_NAME,'DETECTION'=>$DETECTION,'OFFENCE'=>$OFFENCE,'OFFENCE_LOCATION'=>$OFFENCE_LOCATION,'MOTIVATION'=>$MOTIVATION);
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
 