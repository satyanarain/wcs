<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		$userName = isset($_POST['userName']) ? mysql_real_escape_string($_POST['userName']) : "";
		$userPass = isset($_POST['userPass']) ? mysql_real_escape_string($_POST['userPass']) : "";

		// Select data from database
		$sql = "SELECT `id`,`ENTITY` FROM `users` WHERE  `USERNAME` = '" . $userName . "' AND  `PASSWORD` = '" . $userPass . "'";
 
		// Set a resultset. For testing we are goint to return the full table (3 rows)
		$qur = mysql_query($sql);
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $id,"ENTITY"=>$ENTITY);
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
 