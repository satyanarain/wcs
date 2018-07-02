<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$fingerdata = isset($_POST['fingerdata']) ? mysql_real_escape_string($_POST['fingerdata']) : "";
		$fingerid = isset($_POST['fingerid']) ? mysql_real_escape_string($_POST['fingerid']) : "";

		$sql = "SELECT `fingerprintdata`.`suspect_id`, `fingerprintdata`.`finger_id`, `suspects`.`id`, `suspects`.`SUSPECT_NAME`, `suspects`.`YOB`, `suspects`.`GENDER`";
		$sql = $sql . " FROM `fingerprintdata`, `suspects`";
		$sql = $sql . " WHERE `fingerprintdata`.`finger_data` = '" . $fingerdata . "'";
		$sql = $sql . " AND `fingerprintdata`.`suspect_id` = `suspects`.`id`";
		if ($fingerid != "99") {
			$sql = $sql . " AND `fingerprintdata`.`finger_id` = '" . $fingerid . "'";
		}
		
		// Set a resultset. For testing we are goint to return the full table (3 rows)
		$qur = mysql_query($sql);
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $id, "SUSPECT_NAME" => $SUSPECT_NAME, "YOB" => $YOB, "GENDER" => $GENDER, "finger_id" => $finger_id);
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
 