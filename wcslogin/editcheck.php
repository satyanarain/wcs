<?php
@session_start();

//	Copyright © 2012 JayKSoft / Jan Kirstein Møller. All rights reserved

include 'std.php';



/* ---------------------------------------------------------------------------*/

$loadtime = $_GET['t'];
$tablename = $_GET['n'];
$user = $_GET['u'];

$sql = new mySQLclass();
$query = "SELECT * FROM tables WHERE TABLENAME = '$tablename'";
$sql->_sqlLookup ($query);

$refresh = <<<NXT
<meta http-equiv="refresh" content="10">

NXT;

if ($sql->sql_result === false) $load = "";
   else { if ($row = mysql_fetch_assoc ($sql->sql_result))
                { if (($row['LAST_ACTIVITY'] > $loadtime) && ($row['USER'] != $user))
                      { $load = <<<NXT
onload="parent.document.getElementById('exclm').src = 'images/changed.png'; alert ('This table\\'s content has been changed by another user\\nConsider re-loading the table!');"
NXT;
                         $refresh = "";
                      } 
                } 
           }


/* ---------------------------------------------------------------------------*/


$page = <<<NXT
<HTML>
<HEAD>
$refresh
</HEAD>
<BODY $load>
</BODY>
</HTML>

NXT;

echo $page;

?>


