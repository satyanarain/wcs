<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';
include 'suspects.php';
include 'parishes.php';
include 'arrests.php';
include 'cases.php';
include 'offenderstable.php';
include 'defendantstable.php';
include 'fields.php';		// for the _getField () function

/* ---------------------------------------------------------------- */

if ($_POST['table'] == 'offenderstable') $_POST['record'] = _getField ('offenderstable', $_POST['record'], 'OFFENDER', '');
if ($_POST['table'] == 'defendantstable') $_POST['record'] = _getField ('defendantstable', $_POST['record'], 'DEFENDANT', '');

/* ---------------------------------------------------------------- */

$_docspath = "$docspath" . "suspects/{$_POST['record']}";

$notesfilename = "$notespath" . "suspects/{$_POST['record']}.txt";
$notes = file ($notesfilename);
if ($notes === false) $notes = "(none)"; else $notes = implode ("", $notes);

$notes = <<<NXT
<TR><TD colspan="2" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Notes</B>
</TD></TR>
<TR><TD colspan="2">
$notes
</TD></TR>
NXT;

/* ---------------------------------------------------------------- */

if ($_POST['role'] == 'GOD') $Dformat = 'd/m-Y'; else $Dformat = _getField ('users', $_POST['id'], 'DATEFORMAT', '');
if ($_POST['role'] == 'GOD') $TSsepr = '.,'; else $TSsepr = _getField ('users', $_POST['id'], 'NUMSEPARATORS', '');
$TSsepr = str_replace ('|', ' ', $TSsepr);
$thouSep = substr ($TSsepr, 0, 1);
$decSep = substr ($TSsepr, 1, 1);
if ($thouSep == '-') $thouSep = '';

/* ---------------------------------------------------------------- */


if (! is_dir("$_docspath/facial")) _makePath ($docspath, "suspects/{$_POST['record']}/facial");
if (! is_dir("$_docspath/fp")) _makePath ($docspath, "suspects/{$_POST['record']}/fp");


/* ---------------------------------------------------------------- */

$maxwidth = 320;

if ((isset ($_FILES['uploadedfile']['name'])) && ($_FILES['uploadedfile']['name'] != ''))
   { $filename = basename( $_FILES['uploadedfile']['name']);
      list ($upfile, $upext) = explode (".", $filename);
      $upext = strtolower ($upext);

      if ($_POST['img'] == "facial") 
         { move_uploaded_file ($_FILES['uploadedfile']['tmp_name'], "$_docspath/facial/facial.$upext");
            $imageinfo = getimagesize ("$_docspath/facial/facial.$upext");
            $width = $imageinfo[0];
            $height = $imageinfo[1];
            if ($width > $maxwidth)
               { $ratio = $width / $height;
                  $newheight = round ($maxwidth * (1 / $ratio), 0);
                  if ($upext == "png") $image = imagecreatefrompng ("$_docspath/facial/facial.$upext");
                  if ($upext == "jpg") $image = imagecreatefromjpeg ("$_docspath/facial/facial.$upext");
                  $image2 = imagecreatetruecolor ($maxwidth, $newheight);
                  imagecopyresampled ($image2, $image, 0, 0, 0, 0, $maxwidth, $newheight, $width, $height);
                  if ($upext == "png") imagepng ($image2, "$_docspath/facial/facial.$upext");
                  if ($upext == "jpg") imagejpeg ($image2, "$_docspath/facial/facial.$upext");
                  imagedestroy($image);
                  imagedestroy($image2);
               } 
         } 

      if ($_POST['img'] == "fp") 
         { move_uploaded_file ($_FILES['uploadedfile']['tmp_name'], "$_docspath/fingerprints.zip");
            $errorMsg = "Fingerprints succesfully uploaded.";
            $zip = new ZipArchive();
            $filename = "$_docspath/fingerprints.zip";
            if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
              $errorMsg = "Fingerprint upload FAILED. Please try again <BR>If the problem persists, contact the server / portal manager.";
              }
           $zip->extractTo("$_docspath/fp");
           $zip->close();
         }
   }


/* ---------------------------------------------------------------- */

$faceimgs = scandir ("$_docspath/facial");
$fprintimgs = scandir ("$_docspath/fp");

$fimgs = "";
foreach ($faceimgs as $fimg)
             { if (($fimg != ".") && ($fimg != "..")) $fimgs .= <<<NXT
<IMG SRC="getimage.php?in=fa&img=$fimg&rec={$_POST['record']}" style="cursor: $cursor;" TITLE="Facial photo">
NXT;

$profileimage = '';
if (file_exists ($_docspath . "/facial/facial.jpg"))
{
$profileimage = <<<NXT
<IMG SRC="$_docspath/facial/facial.jpg">
NXT;
}
if (file_exists ($_docspath . "/facial/facial.png"))
{
$profileimage = <<<NXT
<IMG SRC="$_docspath/facial/facial.png">
NXT;
}
			 }
if (! $fimgs) $fimgs = "<SPAN style=\"cursor: $cursor;\"><U>[Upload facial shot]</U></SPAN>";

/* ---------------------------------------------------------------- */

$fprints = array ('R1', 'R2', 'R3', 'R4', 'R5', 'L1', 'L2', 'L3', 'L4', 'L5');

/* ---------------------------------------------------------------- */

$ts = $timestamp;

$fprintimages = <<<NXT
<TABLE cellspacing="0" cellpadding="0" border="0">
<TR>
NXT;

$fingerimages = <<<NXT
<BR><BR><BR>
<TABLE cellspacing="0" cellpadding="0" border="0" >
<TR><TD colspan="5" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Fingerprints</B>
</TD></TR>
<TR>
NXT;


$fprinttables = '';

$i = 0;
foreach ($fprints as $finger)
           { $image = "$finger.png";
             $fprintimages .= <<<NXT
<TD style="border-style: solid; border-color: #606060; border-width: 1;">
<IMG  SRC="getimage.php?in=fp&img=$image&rec={$_POST['record']}&ts=$ts" width="100" TITLE="$finger"><BR><B>&nbsp;$finger 
</TD>
NXT;

if (file_exists ($_docspath . "/fp/" . $image))
{
$fingerimages .= <<<NXT
<TD style="border-style: solid; border-color: #606060; border-width: 1;width: 110px;">
<IMG  SRC="$_docspath/fp/$image" width="100"><BR><B>&nbsp;$finger 
</TD>
NXT;
}
else
{
$fingerimages .= <<<NXT
<TD style="border-style: solid; border-color: #606060; border-width: 1;width: 110px;">
<IMG  SRC="" width="100"><BR><B>&nbsp;$finger 
</TD>
NXT;
}

      $image = "";
      if ($i == 4) 
{
$fprintimages .= <<<NXT
</TR><TR>
NXT;

$fingerimages .= <<<NXT
</TR><TR>
NXT;
}
       $i++;
     }

$fprintimages .= <<<NXT
</TR>
</TABLE>
NXT;

$fingerimages .= <<<NXT
</TR>
</TABLE>
NXT;





$fprinttables .= <<<NXT
<TABLE ID="fprinttable" cellspacing="0" cellpadding="6" width="300" height="200" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #C0C0C0; 
border-style: solid; border-color: #806000; border-width: 1;" >
<FORM name="fprintform" method="POST" action="profile.php" enctype="multipart/form-data"
onsubmit="
document.getElementById('fprinttable').style.visibility = 'hidden';
">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="img" value="fp">
<TR><TD>
<B>Upload fingerprints
<BR>
Select file: C:/Culprints/fingerprints.zip
<BR>
<INPUT type="FILE" style="cursor: default;" name="uploadedfile" size="40" maxlength="80" />
<P>
<INPUT type="SUBMIT" value="Upload">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="BUTTON" value="Cancel" 
onclick="
document.getElementById('fprinttable').style.visibility = 'hidden';
">
</TD></TR>
</FORM>
</TABLE>
NXT;


/* ---------------------------------------------------------------- */

$rec = $_POST['record'];

$suspectname = 'NAG';
/* ---------------------------------------------------------------- */

   $newrec = new mySQLclass();
   $query = "SELECT * FROM suspects WHERE id = $rec";
   $newrec->_sqlLookup ($query);
   if ($newrec->sql_result === false) 
      {$newrec->_sqlerror (); 
      }
   if ($row = mysql_fetch_assoc ($newrec->sql_result))
      { $newrec2 = new mySQLclass();
         $query2 = "SELECT * FROM parishes WHERE id = {$row['PARISH']}";
         $newrec2->_sqlLookup ($query2);
         if ($newrec2->sql_result === false) 
            {$newrec2->_sqlerror (); 
            }
         $row2 = mysql_fetch_assoc ($newrec2->sql_result);
         $name = $row['SUSPECT_NAME'];
		 $suspectname = $row['SUSPECT_NAME'];
         $nat = _getField2 ('countries', 'CC', $row['SUSPECT_NATIONALITY'], 'COUNTRY_NAME', '');
         if ($row['WANTED_SINCE']) $since = date ($Dformat, $row['WANTED_SINCE']); else $since = '';
         if ($row['WANTED']) $wanted  = <<<NXT
<P>
{$GLOBALS['tablelabels']['suspects']['WANTED']}: <B>{$row['WANTED']}</B><BR>
Wanted since: <B>$since</B>
<P>
NXT;
            else $wanted = '';
         $stemrec = <<<NXT
&nbsp;<BR>
Born: <B>{$row['YOB']}</B><BR>
Gender: <B>{$row['GENDER']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['SUSPECT_ID']}: <B>{$row['SUSPECT_ID']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['SUSPECT_NATIONALITY']}: <B>$nat ({$row['SUSPECT_NATIONALITY']})</B><BR>
$wanted
{$GLOBALS['tablelabels']['suspects']['SUSPECT_PASSPORT_NO']}: <B>{$row['SUSPECT_PASSPORT_NO']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['SUSPECT_DR_LICENCE']}: <B>{$row['SUSPECT_DR_LICENCE']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['SUSPECT_VOTERS_CARD']}: <B>{$row['SUSPECT_VOTERS_CARD']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['TEL']}: <B>{$row['TEL']}</B><BR>
{$GLOBALS['tablelabels']['parishes']['P_2010_NAME']}: <B>{$row2['P_2010_NAME']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['LC1_NAME']}: <B>{$row['LC1_NAME']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['HOME_LAT']}: <B>{$row['HOME_LAT']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['HOME_LON']}: <B>{$row['HOME_LON']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['UTM_Z']}: <B>{$row['UTM_Z']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['DATUM']}: <B>{$row['DATUM']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['WIVES']}: <B>{$row['WIVES']}</B><BR>
Children: <B>{$row['CHILDREN']}</B><BR>
Other dependants: <B>{$row['OTHER_DEPENDANTS']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['ETHNICITY']}: <B>{$row['ETHNICITY']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['EDUCATION']}: <B>{$row['EDUCATION']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['OCCUPATION']}: <B>{$row['OCCUPATION']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['OTHER_MEANS']}: <B>{$row['OTHER_MEANS']}</B><BR>
{$GLOBALS['tablelabels']['suspects']['MONTHLY_INCOME']}: <B>{$row['MONTHLY_INCOME']}</B><BR>
NXT;
      }

/* ---------------------------------------------------------------- */

$arrestassociates = array ();
$associatesA = new mySQLclass();

$arrests = "";

$homes = array ();

   $query = "SELECT * FROM offenderstable WHERE OFFENDER = $rec";
   $newrec->_sqlLookup ($query);
   if ($newrec->sql_result === false) 
      { $newrec->_sqlerror (); 
      }

   while ($row = mysql_fetch_assoc ($newrec->sql_result))
            {  $parish = _getField ('parishes', $row['PARISH'], 'P_2010_NAME', '');
                $temparr = array ($parish, $row['HOME_LC1_NAME'], $row['HOME_LAT'], $row['HOME_LON'], $row['UTM_Z'], $row['DATUM']);
                $anyhome = implode ('', $temparr);
                if ((! in_array ($temparr, $homes)) && ($anyhome)) array_push ($homes, $temparr);

               $query2 = "SELECT * FROM arrests WHERE id = {$row['ASSREC']}";
               $newrec2->_sqlLookup ($query2);
               if ($newrec2->sql_result === false) 
                 { $newrec2->_sqlerror (); 
                 }
               $row2 = mysql_fetch_assoc ($newrec2->sql_result);

               $date = date ($Dformat, $row2['ARRESTDATE']);
               if ($row['INFORMANT']) $informant = "<B>Informant</B><BR>"; else $informant = "";
               if (! $row2['UWA_CASEID']) $row2['UWA_CASEID'] = 'N.A.';
               $arrests .= <<<NXT
<B>$date &nbsp;{$row2['ARRESTTIME']} &nbsp;&nbsp;{$row2['LOCATION_NAME']}</B><BR>
{$GLOBALS['tablelabels']['arrests']['UWA_CASEID']}: <A HREF="JavaScript: _goRecview ('arrests', '{$row2['id']}');"><B>&nbsp;{$row2['UWA_CASEID']}&nbsp;</B></A><BR>
{$GLOBALS['tablelabels']['arrests']['DETECTION']}: <B>{$row2['DETECTION']}</B><BR>
{$GLOBALS['tablelabels']['offenderstable']['ACTION']}: <B>{$row['ACTION']}</B><BR>
$informant
<P>
NXT;

               $queryA = "SELECT * FROM offenderstable WHERE ASSREC = {$row['ASSREC']}";
               $associatesA->_sqlLookup ($queryA);
               if ($associatesA->sql_result === false) 
                 {$associatesA->_sqlerror (); 
                 } 
               while ($rowA = mysql_fetch_assoc ($associatesA->sql_result))
                         if ((! in_array ($rowA['OFFENDER'], $arrestassociates)) && ($rowA['OFFENDER'] != $rec)) $arrestassociates[] = $rowA['OFFENDER'];

            }

$knownhomelocations = <<<NXT
<TABLE cellspacing="0" cellpadding="4" border="1">
<TR style="background: #C8C8C8;">
<TD>Parish</TD><TD>Village (LC1)</TD><TD>Home Lat.</TD><TD>Home Lon.</TD><TD>UTMz</TD><TD>Datum</TD>
</TR>
NXT;

foreach ($homes as $homearray)
             { $knownhomelocations .= <<<NXT
<TR>
<TD>{$homearray[0]}&nbsp;</TD><TD>{$homearray[1]}&nbsp;</TD><TD>{$homearray[2]}&nbsp;</TD><TD>{$homearray[3]}&nbsp;</TD><TD>{$homearray[4]}&nbsp;</TD><TD>{$homearray[5]}&nbsp;</TD>
</TR>
NXT;
             }
$knownhomelocations .= <<<NXT
</TABLE>
NXT;

$knownhomelocations = <<<NXT
<TR><TD colspan="2" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Known home locations</B>
</TD></TR>
<TR><TD colspan="2">
$knownhomelocations
</TD></TR>
NXT;

$assesA = "";
foreach ($arrestassociates as $ass)
             { $assesAtmp = _getField ('suspects', $ass, 'SUSPECT_NAME', '');
$assesA .= <<<NXT
<A HREF="JavaScript: _goRecview ('suspects', '$ass');">$assesAtmp</A><BR>
NXT;
             }
if ($assesA != "") 
$assesA = <<<NXT
<B>Arrests-</B>
<BR>
$assesA
NXT;

$arrests = <<<NXT
<TR><TD colspan="2" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Arrests</B>
</TD></TR>
<TR><TD colspan="2">
$arrests
</TD></TR>
NXT;


/* ---------------------------------------------------------------- */

$caseassociates = array ();
$cases = "";
$caseIds = array ();

$verdictsql = new mySQLclass();

   $query = "SELECT * FROM defendantstable WHERE DEFENDANT = $rec AND DELETED = 0";
   $newrec->_sqlLookup ($query);
   if ($newrec->sql_result === false) $newrec->_sqlerror (); 

   while ($row = mysql_fetch_assoc ($newrec->sql_result))
            { $cId = _getField ('cases', $row['ASSREC'], 'UWA_CASEID', '');
               $rId = $row['ASSREC'];
               if (! in_array ($cId, $caseIds)) $caseIds[$rId] = $cId;
            }
   foreach ($caseIds as $rId => $cId)
            {

               $query2 = "SELECT * FROM cases WHERE id = $rId AND DELETED = 0";
               $newrec2->_sqlLookup ($query2);
               if ($newrec2->sql_result === false) $newrec2->_sqlerror (); 
               $row2 = mysql_fetch_assoc ($newrec2->sql_result);

               $date = date ($Dformat, $row2['COURTDATE']);
               $UWAid = $row2['UWA_CASEID'];
               if (! $row2['UWA_CASEID']) $row2['UWA_CASEID'] = 'N.A.';

               $qty = number_format ($row['QTY'], 0, "$decSep", "$thouSep");
               if ($qty == '0') $qty = '';

               $cases .= <<<NXT
<B>$date</B> &nbsp;&nbsp;<B>{$row2['CRIME_TYPE']}</B><BR>
{$GLOBALS['tablelabels']['cases']['UWA_CASEID']}: <A HREF="JavaScript: _goRecview ('cases', '{$row2['id']}');"><B>&nbsp;{$row2['UWA_CASEID']}&nbsp;</B></A><BR>
#VERDICT#

<P>
NXT;

               $verdict = '';
               $queryA = "SELECT * FROM defendantstable WHERE ASSREC = $rId";
               $associatesA->_sqlLookup ($queryA);
               if ($associatesA->sql_result === false) $associatesA->_sqlerror (); 
               while ($rowA = mysql_fetch_assoc ($associatesA->sql_result))
                         { if ((! in_array ($rowA['DEFENDANT'], $caseassociates)) && ($rowA['DEFENDANT'] != $rec)) $caseassociates[] = $rowA['DEFENDANT'];
                            if ($rowA['DEFENDANT'] == $rec) 
                               { $VId = $rowA['id'];
                                  $query = "SELECT * FROM verdictstable WHERE ASSREC = $VId AND DELETED = 0";
                                  $verdictsql->_sqlLookup ($query);
                                  if ($verdictsql->sql_result === false) $verdictsql->_sqlerror (); 
                                  while ($Vrow = mysql_fetch_assoc ($verdictsql->sql_result))
                                            { if ($Vrow['VERDICT'] == 'Pending') continue;
                                               if ($Vrow['APPEALED']) $appealed = '• <B>Appealed</B>'; else $appealed = '';
                                               if ($Vrow['SPEC']) $spec = "[{$Vrow['SPEC']}]"; else $spec = '';
                                               $Cdate = date ($Dformat, $Vrow['COURT_DATE']);
                                               if ($Vrow['QTY']) $qty = $Vrow['QTY'] . ' ' . $Vrow['UNIT']; else $qty = '';
                                               $verdict .= <<<NXT
$Cdate {$Vrow['COURT_NAME']} • {$Vrow['VERDICT']} $qty $spec $appealed<BR>
NXT;
                                            }
                               }
                         }
               $cases = str_replace ('#VERDICT#', $verdict, $cases);

            }

$assesC = "";
foreach ($caseassociates as $ass)
             { $assesCtmp = _getField ('suspects', $ass, 'SUSPECT_NAME', '');
$assesC .= <<<NXT
<A HREF="JavaScript: _goRecview ('suspects', '$ass');">$assesCtmp</A><BR>
NXT;
             }
if ($assesC != "") 
$assesC = <<<NXT
<P>
<B>Court cases-</B>
<BR>
$assesC
NXT;

$cases = <<<NXT
<TR><TD colspan="2" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Prosecutions</B>
</TD></TR>
<TR><TD colspan="2">
$cases
</TD></TR>
NXT;


/* ---------------------------------------------------------------- */


$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

$style

<SCRIPT language="JavaScript">
function _centerElement (elmID, W, H)
{ document.getElementById(elmID).style.top = document.body.scrollTop + Math.round((document.body.clientHeight - H) / 2); 
   document.getElementById(elmID).style.left= document.body.scrollLeft + Math.round((document.body.clientWidth - W) / 2);
}

function _centerElement2 (elmID)
{ document.getElementById(elmID).style.top = document.body.scrollTop; 
   document.getElementById(elmID).style.left= document.body.scrollLeft;
}

function _goRecview (_table, _recid)
{ _setRecFrame (_table, _recid);
}

function _setRecFrame (_table, _recid)
{ document.recform.portal.value = 'SuspectsAdministration';
   document.recform.table.value = _table;
   document.recform.record.value = _recid;
   document.recform.submit();
   _centerElement2 ('newtablebgr');
   _centerElement2 ('rectable');
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('rectable').style.visibility = 'visible';
}  

</SCRIPT>

<BODY style="margin: 8; background: #FFFFFF;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<FORM name="recform" method="POST" action="showrecord.php" target="recframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="portal" value="">
<INPUT type="HIDDEN" name="table" value="">
<INPUT type="HIDDEN" name="record" value="">
</FORM>
<TR>
<TD align="center" Valign="middle">

<TABLE cellspacing="0" cellpadding="2" style="border-style: solid; border-width: 1 1 1 1; border-color: $bordercolor;">
<FORM name="profileform" method="POST" action="profile.php" target="pickrecordframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="edit" value="">
</FORM>

<TR> <TD class="th">
Profile: $name
</TD>
<TD class="th" align="right">
<INPUT type="BUTTON" value="Close" 
onclick="
document.body.innerHTML = '';
parent.document.getElementById('newtablebgr3').style.visibility = 'hidden';
parent.document.getElementById('pickrecordtable3').style.visibility = 'hidden';
">
</TD></TR>

<TR>
<TD Valign="top" 
onclick="
document.getElementById('facialtable').style.visibility = 'visible';
_centerElement ('facialtable', 300, 200);
">
$fimgs
</TD>
<TD Valign="top" style="font-size: 10pt;">
$stemrec
</TD></TR>

<TR><TD colspan="2" style="cursor: $cursor; border-style: solid; border-color: #0000A0; border-width: 0 0 1 0; padding: 8 0 0 4;" 
onclick=
"document.getElementById('fprinttable').style.visibility = 'visible';
_centerElement ('fprinttable', 300, 200);
">
<B style="font-size: 10pt;">Fingerprints - <U>Upload</U></B>
</TD></TR>
<TR><TD colspan="2" style="padding: 0;">
$fprintimages
</TD>
</TR>

$arrests
$cases

$knownhomelocations

<TR><TD colspan="2" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Associates</B>
</TD></TR>
<TR><TD colspan="2">
$assesA
$assesC
</TD></TR>

$notes

</TABLE>

</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<TABLE ID="facialtable" cellspacing="0" cellpadding="6" width="300" height="200" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #C0C0C0; 
border-style: solid; border-color: #806000; border-width: 1;">
<FORM name="facialform" method="POST" action="profile.php" target="pickrecordframe" enctype="multipart/form-data">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="record" value="{$_POST['record']}">
<INPUT type="HIDDEN" name="img" value="facial">
<TR><TD>
<B>Upload facial image:
<BR>
<INPUT type="FILE" style="cursor: default;" name="uploadedfile" size="40" maxlength="80" />
<P>
<INPUT type="SUBMIT" value="Upload">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="BUTTON" value="Cancel" 
onclick="
document.getElementById('facialtable').style.visibility = 'hidden';
">
</TD></TR>


</FORM>
</TABLE>

$fprinttables

<TABLE ID="newtablebgr" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

<TABLE ID="rectable" width="100%" height="100%" cellspacing="0" celpadding="0" border="1" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="recframe" ID="recframe" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>


</BODY>
</HTML>

NXT;


$page_gen = <<<NXT
<HTML>
<HEAD>
</HEAD>
$style
<BODY style="margin: 8; background: #FFFFFF;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle">
<TABLE cellspacing="0" cellpadding="2" >
<TR> <TD style="font-size: 14pt;">
<B>Profile: $name</B><BR>
</TD>
<TD align="right">
</TD></TR>

<TR>
<TD Valign="top">
$profileimage
</TD>
<TD Valign="top" style="font-size: 10pt;">
$stemrec
</TD></TR>

<TR><TD colspan="2" style="cursor: $cursor; border-style: solid; border-color: #0000A0; border-width: 0 0 1 0; padding: 8 0 0 4;">

</TD></TR>
<TR><TD colspan="2" style="padding: 0;">

</TD>
</TR>

$arrests
$cases

$knownhomelocations

<TR><TD colspan="2" style="border-style: solid; border-color: #0000A0; border-width: 0 0 2 0; padding: 8 0 0 4;">
<B style="font-size: 10pt;">Associates</B>
</TD></TR>
<TR><TD colspan="2">
$assesA
$assesC
</TD></TR>

$notes

</TABLE>

</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

$fingerimages

</BODY>
</HTML>

NXT;

$newpage = htmlentities($page_gen,ENT_QUOTES);

$pdfcode = <<<NXT
<div align="center">
<FORM name="pdfgen" action="SuspectProfile.php" method="post" target="_blank">
<INPUT type="SUBMIT" class="btn" name="submit" value="Generate PDF">
<INPUT type="HIDDEN" name="suspectprofile" value="$newpage">
<INPUT type="HIDDEN" name="suspectname" value="$suspectname">
</FORM>
</div>
NXT;

echo $pdfcode;
echo $page;
 
error_reporting ($errorlevel);

?>

