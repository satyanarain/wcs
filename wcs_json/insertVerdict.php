<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$CourtDate = isset($_POST['CourtDate']) ? mysql_real_escape_string($_POST['CourtDate']) : "";
		$Court = isset($_POST['Court']) ? mysql_real_escape_string($_POST['Court']) : "";
		$Verdict = isset($_POST['Verdict']) ? mysql_real_escape_string($_POST['Verdict']) : "";
		$Specify = isset($_POST['Specify']) ? mysql_real_escape_string($_POST['Specify']) : "";
		$Qty = isset($_POST['Qty']) ? mysql_real_escape_string($_POST['Qty']) : "";
		$Appealed = isset($_POST['Appealed']) ? mysql_real_escape_string($_POST['Appealed']) : "";
		$Moved = isset($_POST['Moved']) ? mysql_real_escape_string($_POST['Moved']) : "";
		$NewCourt = isset($_POST['NewCourt']) ? mysql_real_escape_string($_POST['NewCourt']) : "";
		$MovedDate = isset($_POST['MovedDate']) ? mysql_real_escape_string($_POST['MovedDate']) : "";
		$AccusedId = isset($_POST['AccusedId']) ? mysql_real_escape_string($_POST['AccusedId']) : "";
		$ChangedBy = isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		$caseID = isset($_POST['caseID']) ? mysql_real_escape_string($_POST['caseID']) : "";
		$UWA_CaseID = isset($_POST['UWA_CaseID']) ? mysql_real_escape_string($_POST['UWA_CaseID']) : "";
		
		
		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";
		$movedDt=0;
		if($MovedDate!=0){
			$movedDt="UNIX_TIMESTAMP(STR_TO_DATE('" . $MovedDate . "',  '%Y-%m-%d %H:%i:%s' ))";
		}
		$startDate = 0;
		if ($CourtDate != 0) {
			$startDate = "UNIX_TIMESTAMP(STR_TO_DATE('" . $CourtDate . "',  '%Y-%m-%d %H:%i:%s' ))";
		}
				
		if ($bln == false) {
			$sql = "INSERT INTO `verdictstable`( `DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `UWA_CASEID`, `COURT_DATE`, `COURT_NAME`, `VERDICT`, `SPEC`, `QTY`, `UNIT`, `APPEALED`, `MOVED`, `COURT_TO`, `MOVED_DATE`, `SENTENCETABLE`)  VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','defendantstable','" . $AccusedId . "','" . $UWA_CaseID . "', $startDate,'" . $Court . "','" . $Verdict . "','" . $Specify . "','" . $Qty . "', '', '" . $Appealed . "','" . $Moved . "','" . $NewCourt . "',$movedDt,UNIX_TIMESTAMP( NOW( ) ) +3)";
			$bResult = mysql_query($sql) ;
			$insertId=mysql_insert_id();
			if ($bResult)
			{
				$txtResult = "Added Successfully!".$insertId.";";
				$sql1 = "UPDATE `cases` set `COURTDATE` = " . $startDate . "  WHERE `UWA_CASEID` = '" . $UWA_CaseID . "'";
				$bResult1 = mysql_query($sql1) ;
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
 
