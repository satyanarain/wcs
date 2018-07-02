<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$suspectName = isset($_POST['suspectName']) ? mysql_real_escape_string($_POST['suspectName']) : "";
		$suspectID = isset($_POST['suspectID']) ? mysql_real_escape_string($_POST['suspectID']) : "";

		// Select data from database
		$sql = "SELECT id, SUSPECT_NAME, YOB, GENDER FROM suspects WHERE ";
		if (($suspectID == "--") && ($suspectName != "--")) {
		$sql = $sql . "suspect_name LIKE  '%" . $suspectName . "%'";
		}
		if (($suspectID != "--") && ($suspectName == "--")) {
		$sql = $sql . "id LIKE  '%" . $suspectID . "%'";
		}
		if (($suspectID != "--") && ($suspectName != "--")) {
		$sql = $sql . "suspect_name LIKE  '%" . $suspectName . "%'";
		$sql = $sql . " OR id LIKE  '%" . $suspectID . "%'";
		}
 
		// Set a resultset. For testing we are goint to return the full table (3 rows)
		$qur = mysql_query($sql);
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $id, "SUSPECT_NAME" => is_string($SUSPECT_NAME) ? utf8_encode($SUSPECT_NAME) : $SUSPECT_NAME, 'YOB' => $YOB, 'GENDER' => $GENDER);
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
 