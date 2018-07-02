<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$OffenderId = isset($_POST['OffenderId']) ? mysql_real_escape_string($_POST['OffenderId']) : "";
		$Offender = isset($_POST['Offender']) ? mysql_real_escape_string($_POST['Offender']) : "";
		$Parish = isset($_POST['Parish']) ? mysql_real_escape_string($_POST['Parish']) : "";
		$Village = isset($_POST['Village']) ? mysql_real_escape_string($_POST['Village']) : "";
		$HomeLat = isset($_POST['HomeLat']) ? mysql_real_escape_string($_POST['HomeLat']) : "";
		$HomeLon = isset($_POST['HomeLon']) ? mysql_real_escape_string($_POST['HomeLon']) : "";
		$UTMz = isset($_POST['UTMz']) ? mysql_real_escape_string($_POST['UTMz']) : "";		
		$Datum = isset($_POST['Datum']) ? mysql_real_escape_string($_POST['Datum']) : "";
		$ActionTypes = isset($_POST['ActionTypes']) ? mysql_real_escape_string($_POST['ActionTypes']) : "";
		$Informant = isset($_POST['Informant']) ? mysql_real_escape_string($_POST['Informant']) : "";
		$ArrestId = isset($_POST['ArrestId']) ? mysql_real_escape_string($_POST['ArrestId']) : "";
		$ChangedBy = isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		$UWA_CASEID = isset($_POST['UWA_CASEID']) ? mysql_real_escape_string($_POST['UWA_CASEID']) : "";
		
		
		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";
		
				
		if ($bln == false) {
			if($OffenderId=="-1")
			{
				$sql = "INSERT INTO `offenderstable`( `DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `UWA_CASEID`, `OFFENDER`, `PROFILE`, `PARISH`, `HOME_LC1_NAME`, `HOME_LAT`, `HOME_LON`, `UTM_Z`, `DATUM`, `ACTION`, `INFORMANT`) VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','arrests','" . $ArrestId . "','". $UWA_CASEID ."',(SELECT `id` FROM `suspects` WHERE `SUSPECT_NAME`='" . $Offender . "' ORDER BY `id` DESC LIMIT 1),0,'" . $Parish . "','" . $Village . "','" . $HomeLat . "','" . $HomeLon . "','" . $UTMz . "','" . $Datum . "','" . $ActionTypes . "','" . $Informant . "')";
			}
			else
			{
				$sql = "INSERT INTO `offenderstable`( `DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `UWA_CASEID`, `OFFENDER`, `PROFILE`, `PARISH`, `HOME_LC1_NAME`, `HOME_LAT`, `HOME_LON`, `UTM_Z`, `DATUM`, `ACTION`, `INFORMANT`) VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','arrests','" . $ArrestId . "','". $UWA_CASEID ."','" . $OffenderId . "',0,'" . $Parish . "','" . $Village . "','" . $HomeLat . "','" . $HomeLon . "','" . $UTMz . "','" . $Datum . "','" . $ActionTypes . "','" . $Informant . "')";
			}
			$bResult = mysql_query($sql) ;
			if ($bResult)
			{
				$txtResult = "Added Successfully!";
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
 
