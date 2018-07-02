<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$fingerName = isset($_POST['fingerName']) ? mysql_real_escape_string($_POST['fingerName']) : "";
		$suspectID = isset($_POST['suspectID']) ? mysql_real_escape_string($_POST['suspectID']) : "";

		// Select data from database
		$sql = "SELECT `id`, `SUSPECT_NAME`, `YOB`, `GENDER`, '" . $fingerName . "' AS `Finger_Name` FROM `suspects` WHERE `id` = " . $suspectID;
		
		// Set a resultset.
		$qur = mysql_query($sql);
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $id, "SUSPECT_NAME" => is_string($SUSPECT_NAME) ? utf8_encode($SUSPECT_NAME) : $SUSPECT_NAME, 'YOB' => $YOB, 'GENDER' => $GENDER, 'Finger_Name' => $fingerName);
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
 