<?php 
@session_start();

/* ------------------------------------------------------------------------ */

$__portal = "www.opiant.in";

/* ------------------------------------------------------------------------ */

$timeout = 1200;	// inactivity timeout (seconds)

date_default_timezone_set ('UTC');
$timestamp = time();
$UTCoffset = 60*60*3;	// 3hrs. = Uganda time
$timestamp += $UTCoffset;

if (getenv('SERVER_ADDR') == "127.0.0.1") $ssl = ""; else $ssl = "https://brute.acenet-inc.net/~rvsrw/public_html/uwa.jayksoft.com/login/";
$ssl = "";

/* ------------------------------------------------------------------------ */

$publicpage = "http://www.opiant.in";
$loginpage = "http://www.opiant.in/wcslogin";
$serverpath = "/home/opianfpe";

if (getenv('SERVER_ADDR') == "127.0.0.1") $notespath = "D:/clients/web/__UWA/_uwa_notes/"; else $notespath = "$serverpath/_uwa_notes/";
if (getenv('SERVER_ADDR') == "127.0.0.1") $docspath = "D:/clients/web/__UWA/_uwa_docs/"; else $docspath = "$serverpath/_uwa_docs/";
// Note: $docspath copied in getimage.php !

if (getenv('SERVER_ADDR') == "127.0.0.1") $rootpath = "D:/clients/web/__UWA/"; else $rootpath = "$serverpath/";
if (getenv('SERVER_ADDR') == "127.0.0.1") $httppath = "D:/clients/web/__UWA/www/"; else $httppath = "$serverpath/public_hrml/redd/";
if (getenv('SERVER_ADDR') == "127.0.0.1") $confpath = "D:/clients/web/__UWA/_uwa_conf/"; else $confpath = "$serverpath/_uwa_conf/";
if (getenv('SERVER_ADDR') == "127.0.0.1") $backuppath = "D:/clients/web/__UWA/_uwa_backups/"; else $backuppath = "$serverpath/_uwa_backups/";
if (getenv('SERVER_ADDR') == "127.0.0.1") $loginpage = "http://127.0.0.1/__uwa/www/login/";

$startyear = 2010;
$startmonth = 1;

/* ------------------------------------------------------------------------ */

?>

