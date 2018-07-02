<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$fingerid = isset($_POST['fingerid']) ? mysql_real_escape_string($_POST['fingerid']) : "";

		$sql = "SELECT `suspect_id`, `finger_id`, `finger_data` FROM `fingerprintdata`";
		$sql = $sql . " WHERE `finger_id` = '" . $fingerid . "'";
		$sql = $sql . " ORDER BY `suspect_id`,`finger_id`";
		
		// Set a resultset. For testing we are goint to return the full table (3 rows)
		$qur = mysql_query($sql);
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("suspect_id" => $suspect_id, "finger_id" => $finger_id, "finger_data" => $finger_data);
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
 