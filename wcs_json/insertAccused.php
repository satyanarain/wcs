<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$AccusedId = isset($_POST['AccusedId']) ? mysql_real_escape_string($_POST['AccusedId']) : "";
		$Accused = isset($_POST['Accused']) ? mysql_real_escape_string($_POST['Accused']) : "";
		$Crime = isset($_POST['Crime']) ? mysql_real_escape_string($_POST['Crime']) : "";
		$CaseId = isset($_POST['CaseId']) ? mysql_real_escape_string($_POST['CaseId']) : "";
		$ChangedBy = isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";

		
		
		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";
		
				
		
				
		if ($bln == false) {
		
		if($AccusedId=="-1")
		{
		$sql = "INSERT INTO `defendantstable`( `DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `DEFENDANT`, `PROFILE`, `CRIME`, `VERDICTSTABLE`) VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','cases','" . $CaseId . "',(SELECT `id` FROM `suspects` WHERE `SUSPECT_NAME`='" . $Accused . "' ORDER BY `id` DESC LIMIT 1),0,'" . $Crime . "',UNIX_TIMESTAMP( NOW())+3)";
		}
		else
		{
			$sql = "INSERT INTO `defendantstable`( `DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `DEFENDANT`, `PROFILE`, `CRIME`, `VERDICTSTABLE`) VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','cases','" . $CaseId . "','" . $AccusedId . "',0,'" . $Crime . "',UNIX_TIMESTAMP( NOW())+3)";
			
			}
			$bResult = mysql_query($sql) ;
			$insertId=mysql_insert_id();
			if ($bResult)
			{
				$txtResult = "Added Successfully!".$insertId.";";
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
	print(json_encode($txtResult ));

 ?>
 
