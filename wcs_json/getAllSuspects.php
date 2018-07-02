<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		
		
		
		
		// Select data from database
		$sql = "SELECT `id`,`SUSPECT_NAME` FROM `suspects` order by `SUSPECT_NAME`";
		
		
 
		// Set a resultset. 
		$qur = mysql_query($sql) or die(mysql_error());
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $id,"SUSPECT_NAME" => $SUSPECT_NAME);
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
 