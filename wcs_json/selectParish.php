<?php

	// Database Connection
	include_once('databaseConnect.php');
 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		// Get data
		
		
		
		
		// Select data from database
		$sql = "SELECT `id`,`D_2010_NAME`,`C_2006_NAME`,`S_2010_NAME` ,`P_2010_NAME`,`SUBREGION` FROM `parishes`";
		
		
 
		// Set a resultset. 
		$qur = mysql_query($sql) or die(mysql_error());
		// Iterate the resultset to get all data
		while($row = mysql_fetch_array($qur)) 
		{
			extract($row);
			$result[] = array("id" => $id,"D_2010_NAME" => $D_2010_NAME,"C_2006_NAME" => $C_2006_NAME,"S_2010_NAME" => $S_2010_NAME,"P_2010_NAME" => $P_2010_NAME,"SUBREGION" => $SUBREGION);
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
 