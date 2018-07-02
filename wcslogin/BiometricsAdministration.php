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

//echo("Language == " . $_SESSION['selLang']);
$Txt1 = "Upload fingerprint for matching";
$Txt2 = "(Remember to record and save the fingerprint from the scanner first...)";
$Txt3 = "No possible matches found.";
$Txt4 = "Fingerprint successfully uploaded.";
$Txt5 = "Fingerprint upload FAILED. Please try again <BR>If the problem persists, contact the server / portal manager.";
$Txt6 = "Input image for matching.<BR>Finger =";
$Txt7 = "Upload fingerprint for possible matching";
$Txt8 = "Cancel";
$Txt9 = "Select file:";
$Txt10 = "Upload";

switch ($_SESSION['selLang']) {
	case "FR":
		$Txt1 = "Télécharger une empreinte digitale pour l'appariement";
		$Txt2 = "(N'oubliez pas d'enregistrer et d'enregistrer d'abord l'empreinte du scanner...)";
		$Txt3 = "Aucun résultat trouvé.";
		$Txt4 = "L'empreinte digitale a été téléchargée avec succès.";
		$Txt5 = "Échec du téléchargement d'empreintes digitales. Veuillez réessayer <BR>Si le problème persiste, contactez le gestionnaire de serveur / portail.";
		$Txt6 = "Image d'entrée pour correspondance.<BR>Doigt =";
		$Txt7 = "Télécharger une empreinte digitale pour une correspondance possible";
		$Txt8 = "Annuler";
		$Txt9 = "Choisir le dossier:";
		$Txt10 = "Télécharger";
		break;
	case "ES":
		$Txt1 = "Carga de huellas digitales para hacer coincidir";
		$Txt2 = "(Recuerde grabar y guardar primero la huella digital del escáner...)";
		$Txt3 = "No se encontraron coincidencias.";
		$Txt4 = "Impresión digital cargada correctamente.";
		$Txt5 = "Error al cargar la huella digital. Por favor, inténtelo de nuevo <BR>Si el problema persiste, póngase en contacto con el administrador del servidor / portal.";
		$Txt6 = "Imagen de entrada para coincidir.<BR>Dedo =";
		$Txt7 = "Carga de huellas digitales para una posible coincidencia";
		$Txt8 = "Cancelar";
		$Txt9 = "Seleccione Archivo:";
		$Txt10 = "Subir";
		break;
}


function _avg ($a, $b)
{ return ($a + $b) / 2;
}

function _match ($a, $b, $tolerance)
{ if (abs ($a - $b) <= _avg ($a, $b) * $tolerance) return true; else return false;
}

/* -------------------------------------------------------------------- */


// working:
$lengthtolerance = 0.15;
$angletolerance = 0.05;
$ridgecounttolerance = 0;

// experimental (working better...):
$lengthtolerance = 0.2125;
$angletolerance = 0.0;
$ridgecounttolerance = 0;


$hits = array ();

/* ---------------------------------------------------------------- */

function _matchprint (&$suspectToMatch, $finger)
{ global $lengthtolerance, $angletolerance, $ridgecounttolerance, $hits, $docspath;
$hits = array ();

/*
$deb = fopen ("tmp2.txt", "w");
fclose ($deb);
$deb = fopen ("tmp2.txt", "a");
*/

$datafiles = scandir ("$docspath" . "suspects");
foreach ($datafiles as $suspect)
           { 
//fwrite ($deb, "$suspect\n");
              if ((is_dir("$suspect")) || ($suspect == '..') || ($suspect == '.')) continue;
              if (! file_exists ("$docspath" . "suspects/$suspect/fp/$finger.dat")) continue;
              $candidate = file ("$docspath" . "suspects/$suspect/fp/$finger.dat", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//fwrite ($deb, "Found: $docspath" . "suspects/$suspect/fp/$finger.dat - fsize: ".filesize ("$docspath" . "suspects/$suspect/fp/$finger.dat"). "\n");


$totalpossibles = 0;
$matches = 0;

              foreach ($candidate as $anotherpoint)
                          { $candidatepoint = explode ("\t", $anotherpoint);
                             $candpoints = count ($candidatepoint) >> 2;
                             foreach ($suspectToMatch as $apoint)
                                         { 
                                            $suspectpoint = explode ("\t", $apoint);
                                            $susppoints = count ($suspectpoint) >> 2;

$possibles = min ($candpoints, $susppoints);

//$totalpossibles += $possibles;
$totalpossibles += $candpoints + $susppoints;

//if ($candpoints == $susppoints)
for ($s=0;$s<$possibles;$s++)
{ if ( (abs ($candidatepoint[$s*4] - $suspectpoint[$s*4]) <= (($candidatepoint[$s*4] + $suspectpoint[$s*4]) / 2) * $lengthtolerance)
        //_match ($candidatepoint[$s*4], $suspectpoint[$s*4], $lengthtolerance) 
        &&
        //_match ($candidatepoint[($s*4)+1], $suspectpoint[($s*4)+1], $angletolerance) 
        ($candidatepoint[($s*4)+1] == $suspectpoint[($s*4)+1]) 
        &&
        ($candidatepoint[($s*4)+2] == $suspectpoint[($s*4)+2]) 	// type
        &&
        //(abs ($candidatepoint[($s*4)+3] - $suspectpoint[($s*4)+3]) <= $ridgecounttolerance) 
        ($candidatepoint[($s*4)+3] == $suspectpoint[($s*4)+3]) 
      ) $matches++;
} 

                                        }
                          }
if ($matches) $hits["$suspect"] += ($matches / $totalpossibles) * 10000;


            }
//fclose ($deb);
}	// END _matchprint ()

/* ---------------------------------------------------------------- */

$_docspath = "$docspath" . "_users/{$_POST['id']}";

if ($_POST['upload'])
   { if (! is_dir("$_docspath/fptemp")) _makePath ($docspath, "_users/{$_POST['id']}/fptemp");

   }

/* ---------------------------------------------------------------- */

$fprints = array ('R1', 'R2', 'R3', 'R4', 'R5', 'L1', 'L2', 'L3', 'L4', 'L5');

/* ---------------------------------------------------------------- */

function _matchQualityEvaluation ($score)
{ if ($score < 1.0) $evaluation = "Match highly unlikely<BR>($score)";
   if ($score >= 1.0) $evaluation = "Match possible, but unlikely<BR>($score)";
   if ($score >= 3.0) $evaluation = "Match possible<BR>($score)";
   if ($score >= 10.0) $evaluation = "Match very possible<BR>($score)";
   if ($score >= 100.0) $evaluation = "<B>Highly possible match!</B><BR>($score)";
return $evaluation;
}

/* ---------------------------------------------------------------- */

function _bar ($value)
{ $value = round ($value, 0);
   if ($value > 146) $width = $value; else $width = 146;
return <<<NXT
<TABLE width="$width" height="8" cellspacing="0" cellspacing="0" border="0" 
style="border-width: 1; border-style: solid; border-color: #E0E0E0; font-size: 2pt;">
<TR>
<TD width="$value" style="background: #000080;">&nbsp;</TD>
<TD>&nbsp;</TD>
</TR>
</TABLE>
NXT;
}

/* ---------------------------------------------------------------- */

$maxRersults = 10;

function _addResultCandidate ($key, $value, $finger)
{ $name = _getField ('suspects', $key, 'SUSPECT_NAME', '');
   $probability = _matchQualityEvaluation ($value);
   $image = "$finger.png";
   $bar = _bar ($value);
   $return = <<<NXT
<TR> 
<TD>
<IMG SRC="getimage.php?in=fp&img=$image&rec=$key" width="120" TITLE="img=$image&rec=$key">
</TD>
<TD>
$probability
<BR>
$bar
</TD>
<TD>
<A HREF="JavaScript: _goRecview ('suspects', '$key');">$name</A>
</TD>
</TR>
NXT;
return $return;
}

/* ---------------------------------------------------------------- */

function _addNoResults ()
{ $return = <<<NXT
<TR> 
<TD colspan="3" align="center">
<B>$Txt3
</TD>
</TR>
NXT;
return $return;
}

/* ---------------------------------------------------------------- */

$matched = false;

if ((isset ($_FILES['uploadedfile']['name'])) && ($_FILES['uploadedfile']['name'] != ''))
   { $filename = basename( $_FILES['uploadedfile']['name']);
      if (($filename == 'lastprint.zip') ||  1)
         { // clear temp dir before unpacking!
           $datafiles = scandir ("$_docspath/fptemp");
           foreach ($datafiles as $somefile)
                     if (! is_dir("$somefile")) unlink ("$_docspath/fptemp/$somefile");
 
            move_uploaded_file ($_FILES['uploadedfile']['tmp_name'], "$_docspath/fptemp/lastprint.zip");
            $errorMsg = $Txt4;
            $zip = new ZipArchive();

            $filename = "$_docspath/fptemp/lastprint.zip";
            if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
              $errorMsg = $Txt5;
              }
           $zip->extractTo("$_docspath/fptemp");
           $zip->close();
           $datafiles = scandir ("$_docspath/fptemp");
           foreach ($fprints as $finger)
                      { $dataFile = "$finger.dat";
                        if ((in_array ($dataFile, $datafiles)) && (! $matched))
                          { $dataToMatch = file ("$_docspath/fptemp/$dataFile", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            _matchprint ($dataToMatch, $finger);
                            $matched = true;

foreach ($hits as &$hit) $hit = round ($hit, 3);
arsort ($hits);
if (count ($hits) > 0)
   {$i = 0;
     $resultslist = "";
     if (count ($hits) == 0) $resultslist .= _addNoResults ();
     foreach ($hits as $key => $value)
            { $i++;
               if ($i >= $maxRersults) break;
               $resultslist .= _addResultCandidate ($key, $value, $finger);
            }
    $resultslist = <<<NXT
</TR>
<TR> 
<TD colspan="3">
<HR>
</TD>
</TR>
<TR> 
<TD>
<IMG SRC="getimage.php?in=ref&img=$_docspath/fptemp/$finger.png" width="120" TITLE="">
</TD>
<TD colspan="2">
<B>$Txt6 $finger
</TD>
</TR>
<TR> 
<TD colspan="3">
<HR>
</TD>
</TR>
$resultslist
NXT;
   }

                          }
                      }
        }
   }





/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */


$fprinttable = <<<NXT
<TABLE ID="fprinttable" cellspacing="0" cellpadding="6" width="300" height="200" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #C0C0C0; 
border-style: solid; border-color: #806000; border-width: 1;" >
<FORM name="fprintform" method="POST" action="BiometricsAdministration.php" enctype="multipart/form-data"
onsubmit="
document.getElementById('fprinttable').style.visibility = 'hidden';
">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="img" value="fp">
<INPUT type="HIDDEN" name="upload" value="fp">
<TR><TD>
<B>$Txt7
<BR>
$Txt9 C:/Culprints/lastprint.zip
<BR>
<INPUT type="FILE" style="cursor: default;" name="uploadedfile" size="40" maxlength="80" />
<P>
<INPUT type="SUBMIT" value="$Txt10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="BUTTON" value="$Txt8" 
onclick="
document.getElementById('fprinttable').style.visibility = 'hidden';
">
</TD></TR>
</FORM>
</TABLE>
NXT;


/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */



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
<TD align="center" Valign="middle" style="background: #C8C2C0;">
<B style="cursor: $cursor; font-size: 10pt;" 
onclick=
"document.getElementById('fprinttable').style.visibility = 'visible';
_centerElement ('fprinttable', 300, 200);
"><P><U>$Txt1</U></B>
<BR>
$Txt2
<BR>&nbsp;
</TD>
</TR>

<TR>
<TD align="center" Valign="middle">
<TABLE ID="restable" width="60%" cellspacing="0" cellpadding="0" border="0">
$resultslist
</TABLE>
</TD>
</TR>

</TABLE>



$fprinttable

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


echo $page;

error_reporting ($errorlevel);

?>

