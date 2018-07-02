<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$SuspectId = isset($_POST['SuspectId']) ? mysql_real_escape_string($_POST['SuspectId']) : "";
		$SuspectName = isset($_POST['SuspectName']) ? mysql_real_escape_string($_POST['SuspectName']) : "";
		$Wanted = isset($_POST['Wanted']) ? mysql_real_escape_string($_POST['Wanted']) : "";
		$wanteddate = isset($_POST['wanteddate']) ? mysql_real_escape_string($_POST['wanteddate']) : "";
		$Offence = isset($_POST['Offence']) ? mysql_real_escape_string($_POST['Offence']) : "";		
		$Telephone = isset($_POST['Telephone']) ? mysql_real_escape_string($_POST['Telephone']) : "";
		$YOB = isset($_POST['YOB']) ? mysql_real_escape_string($_POST['YOB']) : "";
		$Gender = isset($_POST['Gender']) ? mysql_real_escape_string($_POST['Gender']) : "";
		$Passport = isset($_POST['Passport']) ? mysql_real_escape_string($_POST['Passport']) : "";
		$Driving = isset($_POST['Driving']) ? mysql_real_escape_string($_POST['Driving']) : "";

		$Voter = isset($_POST['Voter']) ? mysql_real_escape_string($_POST['Voter']) : "";		
		$Means = isset($_POST['Means']) ? mysql_real_escape_string($_POST['Means']) : "";
		$Nationality = isset($_POST['Nationality']) ? mysql_real_escape_string($_POST['Nationality']) : "";
		$Parish = isset($_POST['Parish']) ? mysql_real_escape_string($_POST['Parish']) : "";
		$Village = isset($_POST['Village']) ? mysql_real_escape_string($_POST['Village']) : "";
		$HomeLat = isset($_POST['HomeLat']) ? mysql_real_escape_string($_POST['HomeLat']) : "";
		$HomeLon = isset($_POST['HomeLon']) ? mysql_real_escape_string($_POST['HomeLon']) : "";
		$UTMz = isset($_POST['UTMz']) ? mysql_real_escape_string($_POST['UTMz']) : "";
		$Datum = isset($_POST['Datum']) ? mysql_real_escape_string($_POST['Datum']) : "";		
		$Married = isset($_POST['Married']) ? mysql_real_escape_string($_POST['Married']) : "";
		
		$husband = isset($_POST['husband']) ? mysql_real_escape_string($_POST['husband']) : "";
		$wives = isset($_POST['wives']) ? mysql_real_escape_string($_POST['wives']) : "";
		$children = isset($_POST['children']) ? mysql_real_escape_string($_POST['children']) : "";		
		$depend = isset($_POST['depend']) ? mysql_real_escape_string($_POST['depend']) : "";
		$Ethencity = isset($_POST['Ethencity']) ? mysql_real_escape_string($_POST['Ethencity']) : "";
		$Education = isset($_POST['Education']) ? mysql_real_escape_string($_POST['Education']) : "";
		$Occupation = isset($_POST['Occupation']) ? mysql_real_escape_string($_POST['Occupation']) : "";
		$Income = isset($_POST['Income']) ? mysql_real_escape_string($_POST['Income']) : "";
		$ChangedBy=isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		
$Dt=0;
if($wanteddate!=0){
$Dt="UNIX_TIMESTAMP(STR_TO_DATE('" . $wanteddate . "',  '%Y-%m-%d %H:%i:%s' ))";
}

		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";
				
		if ($bln == false) {
			$sql = "INSERT INTO `suspects`( `REGISTERED`, `DELETED`, `CHANGED`, `CHANGEDBY`, `NOTES`, `DOCS`, `PROFILE`, `SUSPECT_NAME`, `SUSPECT_ID`, `SUSPECT_PASSPORT_NO`, `SUSPECT_DR_LICENCE`, `SUSPECT_VOTERS_CARD`, `SUSPECT_NATIONALITY`, `WANTED`, `WANTED_SINCE`, `OFFENCE`, `TEL`, `YOB`, `GENDER`, `PARISH`, `LC1_NAME`, `HOME_LAT`, `HOME_LON`, `UTM_Z`, `DATUM`, `MARRIED`, `HUSBANDS`, `WIVES`, `CHILDREN`, `OTHER_DEPENDANTS`, `ETHNICITY`, `EDUCATION`, `OCCUPATION`, `OTHER_MEANS`, `MONTHLY_INCOME`) VALUES (UNIX_TIMESTAMP( NOW( ) ) +3,0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "',0,0,0,'" . $SuspectName . "','" . $SuspectId . "','" . $Passport . "','" . $Driving . "','" . $Voter . "','" . $Nationality . "','" . $Wanted . "',$Dt,'" . $Offence . "','" . $Telephone . "','" . $YOB . "','" . $Gender . "','" . $Parish . "','" . $Village . "','" . $HomeLat . "','" . $HomeLon . "','" . $UTMz . "','" . $Datum . "','" . $Married . "','" . $husband . "','" . $wives . "','" . $children . "','" . $depend . "','" . $Ethencity . "','" . $Education . "','" . $Occupation . "','" . $Means . "','" . $Income . "')";
			
			$bResult = mysql_query($sql) ;
			if ($bResult)
			{
				$txtResult = "1";
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
 
