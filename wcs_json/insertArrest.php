<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$UWA_CASEID = isset($_POST['UWA_CASEID']) ? mysql_real_escape_string($_POST['UWA_CASEID']) : "";
		$PoliceCaseId = isset($_POST['PoliceCaseId']) ? mysql_real_escape_string($_POST['PoliceCaseId']) : "";
		$Participants = isset($_POST['Participants']) ? mysql_real_escape_string($_POST['Participants']) : "";
		$ArrestingOfficer = isset($_POST['ArrestingOfficer']) ? mysql_real_escape_string($_POST['ArrestingOfficer']) : "";
		$date = isset($_POST['date']) ? mysql_real_escape_string($_POST['date']) : "";
		$time = isset($_POST['time']) ? mysql_real_escape_string($_POST['time']) : "";		
		$Station = isset($_POST['Station']) ? mysql_real_escape_string($_POST['Station']) : "";
		$Outpost = isset($_POST['Outpost']) ? mysql_real_escape_string($_POST['Outpost']) : "";
		$LocationLat = isset($_POST['LocationLat']) ? mysql_real_escape_string($_POST['LocationLat']) : "";
		$LocationLon = isset($_POST['LocationLon']) ? mysql_real_escape_string($_POST['LocationLon']) : "";
		$UTMz = isset($_POST['UTMz']) ? mysql_real_escape_string($_POST['UTMz']) : "";

		$Datum = isset($_POST['Datum']) ? mysql_real_escape_string($_POST['Datum']) : "";		
		$LocationName = isset($_POST['LocationName']) ? mysql_real_escape_string($_POST['LocationName']) : "";
		$Detection = isset($_POST['Detection']) ? mysql_real_escape_string($_POST['Detection']) : "";
		$CrimeType = isset($_POST['CrimeType']) ? mysql_real_escape_string($_POST['CrimeType']) : "";
		$OffenceLocation = isset($_POST['OffenceLocation']) ? mysql_real_escape_string($_POST['OffenceLocation']) : "";
		$Motivation = isset($_POST['Motivation']) ? mysql_real_escape_string($_POST['Motivation']) : "";
		$ChangedBy=isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		
		
		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";
		$vCount=0;
		
		$sql1 = "SELECT * FROM `arrests` WHERE `UWA_CASEID` like '" . $UWA_CASEID . "%'";
		$qur1 = mysql_query($sql1);
		while($row = mysql_fetch_array($qur1)) 			
		{
			
			//$bln = true;
			$vCount=$vCount+1;
			//$sql = "HERE";
		}	
		
$vCount=$vCount+1;
				
		if ($bln == false) {
			$sql = "INSERT INTO `arrests`( `REGISTERED`, `DELETED`, `CHANGED`, `CHANGEDBY`, `NOTES`, `DOCS`, `UWA_CASEID`,`POLICE_CASEID`, `PARTICIPANTS`, `OFFICER_NAME`, `ARRESTDATE`, `ARRESTTIME`, `OUTPOST`, `OUTPOST_NAME`, `LOCATION_LAT`, `LOCATION_LON`, `UTM_Z`, `DATUM`, `LOCATION_NAME`, `DETECTION`, `OFFENCE`, `OFFENCE_LOCATION`, `EVIDENCETABLE`, `MOTIVATION`, `OFFENDERSTABLE`) VALUES(UNIX_TIMESTAMP( NOW())+3,0,UNIX_TIMESTAMP( NOW()) +3,'" . $ChangedBy . "',0,0,CONCAT('" . $UWA_CASEID . "','".$vCount."'),'" . $PoliceCaseId . "','" . $Participants . "','" . $ArrestingOfficer . "',UNIX_TIMESTAMP(STR_TO_DATE('" . $date . "',  '%Y-%m-%d %H:%i:%s' )),'" . $time . "','" . $Station . "','" . $Outpost . "','" . $LocationLat . "','" . $LocationLon . "','" . $UTMz . "','" . $Datum . "','" . $LocationName . "','" . $Detection . "','" . $CrimeType . "','" . $OffenceLocation . "',UNIX_TIMESTAMP( NOW())+3,'" . $Motivation . "',UNIX_TIMESTAMP( NOW())+3)";
			$bResult = mysql_query($sql) ;
			$insertId=mysql_insert_id();
			if ($bResult)
			{
				$txtResult = "Added Successfully!".$insertId.";".$UWA_CASEID.$vCount."#";
			}
		}
	}
	else
	{
		$txtResult = "Requested method not accepted!";
	}
	@mysql_close($conn);
 
	/* Output header */
	header('Content-type: application/json');
  	// Returns the JSON representation of fetched data
	print(json_encode($txtResult));

 ?>
 
