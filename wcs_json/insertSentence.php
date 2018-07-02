<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data		
		$Opt1 = isset($_POST['Opt1']) ? mysql_real_escape_string($_POST['Opt1']) : "";
		$Qty1 = isset($_POST['Qty1']) ? mysql_real_escape_string($_POST['Qty1']) : "";
		$Units1 = isset($_POST['Units1']) ? mysql_real_escape_string($_POST['Units1']) : "";
		$Rem1 = isset($_POST['Rem1']) ? mysql_real_escape_string($_POST['Rem1']) : "";
		$Opt2 = isset($_POST['Opt2']) ? mysql_real_escape_string($_POST['Opt2']) : "";
		$Qty2 = isset($_POST['Qty2']) ? mysql_real_escape_string($_POST['Qty2']) : "";
		$Units2 = isset($_POST['Units2']) ? mysql_real_escape_string($_POST['Units2']) : "";
		$Rem2 = isset($_POST['Rem2']) ? mysql_real_escape_string($_POST['Rem2']) : "";
		$Opted = isset($_POST['Opted']) ? mysql_real_escape_string($_POST['Opted']) : "";
		$Specify = isset($_POST['Specify']) ? mysql_real_escape_string($_POST['Specify']) : "";
		$PrisonName = isset($_POST['PrisonName']) ? mysql_real_escape_string($_POST['PrisonName']) : "";
		$StartDate = isset($_POST['StartDate']) ? mysql_real_escape_string($_POST['StartDate']) : "";
		$ReleaseDate = isset($_POST['ReleaseDate']) ? mysql_real_escape_string($_POST['ReleaseDate']) : "";
		$VerdictId = isset($_POST['VerdictId']) ? mysql_real_escape_string($_POST['VerdictId']) : "";
		$ChangedBy = isset($_POST['ChangedBy']) ? mysql_real_escape_string($_POST['ChangedBy']) : "";
		
		
		// Select data from database
		$bln = false;
		$txtResult = "Not Successful! Try again!!";

		$startDt=0;
		if($StartDate!=0){
$startDt="UNIX_TIMESTAMP(STR_TO_DATE('" . $StartDate . "',  '%Y-%m-%d %H:%i:%s' ))";
}
		$releaseDt=0;
		if($ReleaseDate!=0){
$releaseDt="UNIX_TIMESTAMP(STR_TO_DATE('" . $ReleaseDate . "',  '%Y-%m-%d %H:%i:%s' ))";
}		
		if ($bln == false) {
			$sql = "INSERT INTO `sentencetable`( `DELETED`, `CHANGED`, `CHANGEDBY`, `ASSTABLE`, `ASSREC`, `UWA_CASEID`, `OPT1`, `QTY1`, `UNIT1`, `REM1`, `OPT2`, `QTY2`, `UNIT2`, `REM2`, `OPTED`, `OPTEDREM`, `PRISONNAME`, `PRISONSTART`, `PRISONEND`) VALUES(0,UNIX_TIMESTAMP( NOW( ) ) +3,'" . $ChangedBy . "','verdictstable','" . $VerdictId . "','0','" . $Opt1 . "','" . $Qty1 . "','" . $Units1 . "','" . $Rem1 . "','" . $Opt2 . "','" . $Qty2 . "','" . $Units2 . "','" . $Rem2 . "','" . $Opted . "','" . $Specify . "','" . $PrisonName . "',$startDt,$releaseDt)";
			$bResult = mysql_query($sql) ;
			$insertId=mysql_insert_id();
			if ($bResult)
			{
				$txtResult = "Added Successfully!".$insertId;
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
 
