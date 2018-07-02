<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$EvidenceType = isset($_POST['EvidenceType']) ? mysql_real_escape_string($_POST['EvidenceType']) : "";
		$Species = isset($_POST['Species']) ? mysql_real_escape_string($_POST['Species']) : "";
		$Serial = isset($_POST['Serial']) ? mysql_real_escape_string($_POST['Serial']) : "";
		$Qty = isset($_POST['Qty']) ? mysql_real_escape_string($_POST['Qty']) : "";
		$Units = isset($_POST['Units']) ? mysql_real_escape_string($_POST['Units']) : "";
		$Value = isset($_POST['Value']) ? mysql_real_escape_string($_POST['Value']) : "";		
		$Status = isset($_POST['Status']) ? mysql_real_escape_string($_POST['Status']) : "";
		$Location = isset($_POST['Location']) ? mysql_real_escape_string($_POST['Location']) : "";
		$ArrestId = isset($_POST['ArrestId']) ? mysql_real_escape_string($_POST['ArrestId']) : "";
		$ChangedBy = isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		$UWA_CASEID = isset($_POST['UWA_CASEID']) ? mysql_real_escape_string($_POST['UWA_CASEID']) : "";
		$TransferDate = isset($_POST['TransferDate']) ? mysql_real_escape_string($_POST['TransferDate']) : "";
		$TransferTo = isset($_POST['TransferTo']) ? mysql_real_escape_string($_POST['TransferTo']) : "";
		
		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";
		
		$TDt=0;
		if($TransferDate!=0){
$TDt="UNIX_TIMESTAMP(STR_TO_DATE('" . $TransferDate . "',  '%Y-%m-%d %H:%i:%s' ))";
}

		if ($bln == false) {
			$sql = "INSERT INTO `evidencetable`(`DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `EVIDENCE`, `SPECIES`,  `QTY`, `EST_VALUE`, `UNIT`, `EVIDENCE_NO`, `STATUS`, `LOCATION`,`TRANS_DATE`,`TRANS_TO`) VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','arrests','" . $ArrestId . "','" . $EvidenceType . "','" . $Species . "','" . $Qty . "','" . $Value . "','" . $Units . "','" . $UWA_CASEID . "','" . $Status . "','" . $Location . "',$TDt,'" . $TransferTo . "')";
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
 
