<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	$bResult = false;
	$txtResult = "Result awaited.";
	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get finger data
		$fingerdata = isset($_POST['fingerdata']) ? mysql_real_escape_string($_POST['fingerdata']) : "";
		// Get finger id
		$fingerid = isset($_POST['fingerid']) ? mysql_real_escape_string($_POST['fingerid']) : "-1";
		// Get suspect id
		$suspectid = isset($_POST['suspectid']) ? mysql_real_escape_string($_POST['suspectid']) : "0";
		if (($fingerdata != "") && ($fingerid != -1) && ($suspectid != 0))
		{
			// Check if already available
			$sql = "SELECT * FROM `fingerprintdata` WHERE `suspect_id`=" . $suspectid . " AND `finger_id`=" . $fingerid;
			$result = mysql_query($sql);
			//$num_rows = mysql_num_rows($result);
			$row = mysql_fetch_array($result);
			if ($row[0] > 0)
			{
				$sql = "UPDATE `fingerprintdata` SET `finger_data`='" . $fingerdata . "',`last_updated`=now() WHERE `suspect_id`=" . $suspectid . " AND `finger_id`=" . $fingerid;
				$bResult = mysql_query($sql);
				if ($bResult)
				{
					$txtResult = "Fingerprint data updated!";
				}
				else 
				{
					$txtResult = "Fingerprint data not updated! Try again!!";
				}
			}
			else
			{
				//Insert data
				$sql = "INSERT INTO `fingerprintdata`(`suspect_id`, `finger_id`, `finger_data`, `last_updated`) VALUES (" . $suspectid . "," . $fingerid . ",'" . $fingerdata . "',now())";
				$bResult = mysql_query($sql);
				if ($bResult)
				{
					$txtResult = "Fingerprint data inserted! ";
				}
				else 
				{
					$txtResult = "Fingerprint data not inserted! Try again!!";
				}
			}
		}
		else
		{
			$txtResult = "Data not valid for processing!";
		}
	}
	else
	{
		$txtResult = "Requested method not accepted!";
	}
	@mysql_close($conn);
 
	/* Output header */
	header('Content-type: application/json');
  	// Returns the JSON representation of result
	print(json_encode($txtResult));

 ?>
 