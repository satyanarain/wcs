<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

include "subtables/{$_POST['maintable']}/{$_POST['subtable']}administration.php";

error_reporting ($errorlevel);

?>

