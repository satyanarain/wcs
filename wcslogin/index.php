<?php 
@session_start();

$errorlevel = error_reporting ();
error_reporting (E_ERROR | E_PARSE);

function __autoload($class_name) {
    require_once $class_name . '.php';
}

if (getenv('SERVER_ADDR') == "127.0.0.1") $ssl = ""; else $ssl = "https://brute.acenet-inc.net/~rvsrw/uwa.jayksoft.com/login";
$call = $ssl . "login.php";
$call = "login.php";

include 'style.php';

$browser = getenv('HTTP_USER_AGENT');
if (strpos ($browser, "Firefox") > -1)
   { $cursor = "pointer";
   } else { $cursor = "hand";
             }

$dip = getenv ('REMOTE_ADDR');

$iplookup = new ip2Countryclass();
$iplookup->_lookup($dip);

$publicpage = "http://www.ugandawildlife.org";

//if (($iplookup->cc2 == 'UG') || (getenv('SERVER_ADDR') == "127.0.0.1") || ($iplookup->cc2 == 'IN') || ($iplookup->cc2 == 'NL') || ($iplookup->cc2 == 'IT'))
if ( 1 || ($iplookup->cc2 == 'UG') || ($dip == "216.104.202.230") || (getenv('SERVER_ADDR') == "127.0.0.1") || ($dip == "41.72.175.130") )
   { echo <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
{$GLOBALS['title']}
<LINK rel="shortcut icon" href="images/icon_uwa.ico">
</HEAD>

$style

<BODY style="overflow: auto; overflow-x: hidden; overflow-y: hidden;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
<Iframe width="100%" height="100%" frameborder="0" SRC="$call" scrolling="auto" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

</BODY>
</HTML>
NXT;
   } else { echo <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
{$GLOBALS['title']}
<LINK rel="shortcut icon" href="images/icon_uwa.ico">
</HEAD>

$style

<BODY onload="document.location='$publicpage';">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
Redirecting to <A HREF="$publicpage">$publicpage</A>
</TD>
</TR>
</TABLE>
</BODY>
</HTML>
NXT;
             }

error_reporting ($errorlevel);
?>

