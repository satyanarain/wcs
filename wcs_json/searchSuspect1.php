<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$suspectName = isset($_POST['suspectName']) ? mysql_real_escape_string($_POST['suspectName']) : "";
		$suspectID = isset($_POST['suspectID']) ? mysql_real_escape_string($_POST['suspectID']) : "";

		// Select data from database
		$sql = "SELECT SUSPECT_ID, SUSPECT_NAME, WANTED, WANTED_SINCE, OFFENCE, TEL, YOB, GENDER, SUSPECT_PASSPORT_NO, SUSPECT_DR_LICENCE, SUSPECT_VOTERS_CARD, OTHER_MEANS, SUSPECT_NATIONALITY, PARISH, LC1_NAME, HOME_LAT, HOME_LON, UTM_Z, DATUM, MARRIED, HUSBANDS, WIVES, CHILDREN, OTHER_DEPENDANTS, ETHNICITY, EDUCATION, OCCUPATION, MONTHLY_INCOME FROM suspects WHERE ";
		
		$sql = $sql . "SUSPECT_NAME LIKE  '%" . $suspectName . "%'";
	
		
 
		// Set a resultset. 
		$qur = mysql_query($sql);
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $SUSPECT_ID, "SUSPECT_NAME" => $SUSPECT_NAME,'WANTED'=>$WANTED,'WANTED_SINCE'=>$WANTED_SINCE,'OFFENCE'=>$OFFENCE,'TEL'=>$TEL, 'YOB' => $YOB, 'GENDER' => $GENDER,'SUSPECT_PASSPORT_NO'=>$SUSPECT_PASSPORT_NO,'SUSPECT_DR_LICENCE'=>$SUSPECT_DR_LICENCE,'SUSPECT_VOTERS_CARD'=>$SUSPECT_VOTERS_CARD,'OTHER_MEANS'=>$OTHER_MEANS,'SUSPECT_NATIONALITY'=>$SUSPECT_NATIONALITY,'PARISH'=>$PARISH,'LC1_NAME'=>$LC1_NAME,'HOME_LAT'=>$HOME_LAT,'HOME_LON'=>$HOME_LON,'UTM_Z'=>$UTM_Z,'DATUM'=>$DATUM,'MARRIED'=>$MARRIED,'HUSBANDS'=>$HUSBANDS,'WIVES'=>$WIVES,'children'=>$CHILDREN,'depend'=>OTHER_DEPENDANTS,'ETHNICITY'=>$ETHNICITY,'EDUCATION'=>$EDUCATION,'OCCUPATION'=>$OCCUPATION,'MONTHLY_INCOME'=>$MONTHLY_INCOME);
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
 