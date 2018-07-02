<?php

	// Database Connection
	include_once('databaseConnect.php');

	function _checkSerial ($table, $column, $serial) 
	{ 
		$sql1 = "SELECT * FROM $table WHERE $column = '$serial'";
		$qur1 = mysql_query($sql1);
		if (mysql_num_rows ($qur1) > 0) return true; else return false;
	}

	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$EntityID = isset($_POST['UWA_CASEID']) ? mysql_real_escape_string($_POST['UWA_CASEID']) : "";
		$CourtCaseNo = isset($_POST['CourtCaseNo']) ? mysql_real_escape_string($_POST['CourtCaseNo']) : "";
		$PoliceCaseId = isset($_POST['PoliceCaseId']) ? mysql_real_escape_string($_POST['PoliceCaseId']) : "";		
		$CrimeType = isset($_POST['CrimeType']) ? mysql_real_escape_string($_POST['CrimeType']) : "";
		$startdate = isset($_POST['startdate']) ? mysql_real_escape_string($_POST['startdate']) : "";
		$UWAProsecutor = isset($_POST['UWAProsecutor']) ? mysql_real_escape_string($_POST['UWAProsecutor']) : "";		
		$StateProsecutor = isset($_POST['StateProsecutor']) ? mysql_real_escape_string($_POST['StateProsecutor']) : "";
		$DefenseLawyer = isset($_POST['DefenseLawyer']) ? mysql_real_escape_string($_POST['DefenseLawyer']) : "";
		$JudicialLawyer = isset($_POST['JudicialLawyer']) ? mysql_real_escape_string($_POST['JudicialLawyer']) : "";
		$ChangedBy=isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		
		
		// Select data from database
		$bln = false;
		$vCount=0;
		$txtResult = "Not Successful! Try again!!";
		date_default_timezone_set ('UTC');
		$timestamp = time();
		$UTCoffset = 60*60*3;	// 3hrs. = Uganda time
		$timestamp += $UTCoffset;

		$date = date ('Y-m-d', $timestamp);
		$ser = 1;
		while (_checkSerial ('cases', 'UWA_CASEID', $EntityID . $date . "-" . $ser)) $ser++;
		$UWA_CASEID = $EntityID . $date . "-" . $ser;
		
		//$UWA_CASEID = $UWA_CASEID . $date . "-" . $ser;
		
		/*
		$sql1 = "SELECT * FROM `cases` WHERE `UWA_CASEID` like CONCAT('" . $UWA_CASEID . "','%')";
		$qur1 = mysql_query($sql1);
		while($row = mysql_fetch_array($qur1)) 			
		{
			
			//$bln = true;
			$vCount=$vCount+1;
			//$sql = "HERE";
		}	
		*/
		
		//$vCount=$vCount+1;
		
		if ($bln == false) {
			$sql = "INSERT INTO `cases`( `REGISTERED`, `DELETED`, `CHANGED`, `CHANGEDBY`, `NOTES`, `DOCS`, `UWA_CASEID`, `POLICE_CASEID`, `POLICE_CASE_ID`, `CRIME_TYPE`, `COURTDATE`, `ENDEDDATE`, `CASESTATUS`,  `UWA_PROSECUTOR`, `STATE_PROSECUTOR`, `DEFENCE_LAWYER`, `MAGISTRATE`, `DEFENDANTSTABLE`) VALUES(UNIX_TIMESTAMP( NOW( ) ) +3,0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "',0,0, '" . $UWA_CASEID . "','" . $CourtCaseNo . "','" . $PoliceCaseId . "','" . $CrimeType . "',0,0,'','" . $UWAProsecutor . "','" . $StateProsecutor . "','" . $DefenseLawyer . "','" . $JudicialLawyer . "',UNIX_TIMESTAMP( NOW( ) ) +3)";
			$bResult = mysql_query($sql) ;
			$insertId=mysql_insert_id();
			if ($bResult)
			{
				$txtResult = "Added Successfully!".$insertId.";".$UWA_CASEID.";";
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
 
