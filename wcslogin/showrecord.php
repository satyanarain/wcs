<?php 
@session_start();

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';
include 'fields.php';		// for the _addSlashes2 () & _getField () functions

/* ---------------------------------------------------------------- */

$portal = $_POST['portal'];
$table = $_POST['table'];
$record = $_POST['record'];

define("_first_", 1);


include "$table.php";


/* ---------------------------------------------------------------- */

include 'showtable.editsettings.php';

/* ---------------------------------------------------------------- */

function _fieldLabel2 ($field, $label, $permissions)
{ global $table, $_timg;
   if (($permissions & _edit_) && (in_array ($field, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
   return <<<NXT
<TR><TD class="th" Valign="top">{$_timg ("s=33&title=$label")}</TD><TD class="thr" align="right" Valign="top">$req</TD>

NXT;
}

/* ---------------------------------------------------------------- */

function _closebutton ($table, $record, $permissions)
{ 

		 $closeTxt = "Close";
		 switch ($_SESSION['selLang']) {
			case "FR":
				$closeTxt = "Fermer";
				break;
			case "ES":
				$closeTxt = "Cerca";
				break;
		}

if (($permissions & _edit_) && (! _checkFields ($table, $record))) 
      { foreach ($GLOBALS['ERRORS'] as &$error) $error = addslashes ($error);
         $msg = implode ("\\n", $GLOBALS['ERRORS']);

		 
         $closebutton = <<<NXT
<TD class="th3" align="center"><INPUT class="btn" type="BUTTON" value="$closeTxt" 
onclick="
alert ('The following required fields are\\nnot filled / set or out of range:\\n\\n$msg');
"></TD>

NXT;
      } else { $closebutton = <<<NXT
<TD class="th3" align="center"><INPUT class="btn" type="BUTTON" value="$closeTxt" 
onclick="
if (((document.showtable.edited.value == '1') || 1) && parent.document.getElementById('showtable')) parent.document.showtable.submit();
   else { parent.document.getElementById('rectable').style.visibility = 'hidden'; 
              parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
              document.getElementById('maincell').innerHTML = '';
           }
"></TD>

NXT;
                 }
return $closebutton;
}

function _showrecord ($table, $ROLE, $portal, $record)
{ global $_timg;
   $result = "";
   $display = $GLOBALS['recorddisplays'][$table];

   if ($ROLE == 'GOD') $permissions = 0xFF; else $permissions = $GLOBALS['permissions'][$ROLE][$portal][$table];
   if ($ROLE == 'GOD') $Dformat = 'd/m-Y'; else $Dformat = _getField ('users', $_POST['id'], 'DATEFORMAT', '');
   if ($ROLE == 'GOD') $Tformat = 'H:i:s'; else $Tformat = _getField ('users', $_POST['id'], 'TIMEFORMAT', '');
   if ($ROLE == 'GOD') $TSsepr = '.,'; else $TSsepr = _getField ('users', $_POST['id'], 'NUMSEPARATORS', '');
   $TSsepr = str_replace ('|', ' ', $TSsepr);
   $thouSep = substr ($TSsepr, 0, 1);
   $decSep = substr ($TSsepr, 1, 1);
   if ($thouSep == '-') $thouSep = '';
   $LL = $_POST['LL'];
   if ($ROLE == 'GOD') $logintime = intval (implode ("", file ($GLOBALS['rootpath'] . '_conf/Glin.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
      else $logintime = _getField ('users', $_POST['id'], 'LAST_LOGIN', '');
                                    
   $closebutton = _closebutton ($table, $record, $permissions);
   $result = <<<NXT
<TR>
<TD colspan="3" class="th">
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0">
<TR><TD class="th3">{$_timg ("s=3&title={$GLOBALS['tablenames'][$table]}")}</TD>
<TD class="th3">[$record]&nbsp;</TD>
$closebutton
<TD class="th3" align="right" Valign="middle" TITLE="reload record"><IMG ID="exclm" src="images/refr.png"
onclick="
parent.document.recform.submit();
" 
TITLE="Reload record"></TD>
</TR>
</TABLE>
</TR>
NXT;


   $sql2 = new mySQLclass();
   $query = "SELECT * FROM $table WHERE id = '$record'";
   $sql2->_sqlLookup ($query);
   $count = mysql_num_rows ($sql2->sql_result);
   if ($sql2->sql_result === false) $sql2->_sqlerror ();

   $class = " class=\"ta\"";
   if ($row = mysql_fetch_assoc ($sql2->sql_result))
            { $r = 0;
               foreach ($row as $key => &$value)
                            { $fieldlen = mysql_field_len ($sql2->sql_result, $r);
                               $fieldtype = mysql_field_type ($sql2->sql_result, $r);
                               if (isset ($GLOBALS['prepends'][$table][$key])) $prep = $GLOBALS['prepends'][$table][$key]; else $prep = '';
                               if (isset ($GLOBALS['apppends'][$table][$key])) $app = $GLOBALS['apppends'][$table][$key]; else $app = '';
                               if (substr ($GLOBALS['tablehandlings'][$table][$key], 0, 6) == '_float') list ($fieldlen, $decimals, $minval, $maxval) = explode (",", substr ($GLOBALS['tablehandlings'][$table][$key], 7, -1));
                               $r++;
                               $mouseover = "";
                               $edit = _editSettings ($permissions, $table, $key, $row['id'], $fieldlen);
                               $kk = "#$key#";
                               if ($kk == '#NOTES#') 
                                  { if ($value > 0) 
                                        { if ($value > $LL) $img = _notesimage4_; else $img = _notesimage2_;
                                           if ($logintime < $value) $img = _notesimage3_; 
                                           $showtime = "Last updated: " . date ("$Dformat $Tformat", $value); 
                                        } else { $showtime = "Add note";
                                                      $img = _notesimage1_;
                                                  }
                                     if (($value == 0) && (! ($permissions & _edit_))) 
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>&nbsp;</TD><TD$class$edit>$showtime</TD></TR>", $display);
                                     continue;
                                  } 
                               if ($kk == '#DOCS#') 
                                  { if ($value > 0) 
                                        { if ($value > $LL) $img = _docsimage4_; else $img = _docsimage2_;
                                           if ($logintime < $value) $img = _docsimage3_;
                                           $showtime = "Last updated: " . date ("$Dformat $Tformat", $value); 
                                        } else { $showtime = "Upload Document(s)";
                                                      $img = _docsimage1_;
                                                  }
                                     if (($value == 0) && (! ($permissions & _edit_))) 
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>&nbsp;</TD><TD$class$edit>$showtime</TD></TR>", $display);
                                     continue;
                                  }
                               if ($kk == '#PROFILE#') 
                                  { $img = _profileimage_; 
                                     $showtime = "Profile sheet"; 
                                     if (! ($permissions & _edit_)) 
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>&nbsp;</TD><TD$class$edit>$showtime</TD></TR>", $display);
                                     continue;
                                  }
                         
                               if ($kk == '#SUBTABLE#') 
                                  { if ($value) $img = _tblimage1_; else $img = _tblimage2_;
                                     if (($permissions & _edit_) && (in_array ($key, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
                                     if (! (($permissions & _edit_) || ($permissions & _view_)))
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>$req</TD><TD$class$edit>Open table</TD></TR>", $display);
                                     continue;
                                  }
                         
                               if ($kk == '#EVIDENCETABLE#') 
                                  { if ($value) $img = _evidenceimage2_; else $img = _evidenceimage1_; 
                                     if (($permissions & _edit_) && (in_array ($key, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
                                     if (! (($permissions & _edit_) || ($permissions & _view_)))
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>$req</TD><TD$class$edit>Evidence</TD></TR>", $display);
                                     continue;
                                  }
                         
                               if ($kk == '#OFFENDERSTABLE#') 
                                  { if ($value) $img = _offendersimage2_; else $img = _offendersimage1_; 
                                     if (($permissions & _edit_) && (in_array ($key, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
                                     if (! (($permissions & _edit_) || ($permissions & _view_)))
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>$req</TD><TD$class$edit>Offenders</TD></TR>", $display);
                                     continue;
                                  }
                               if ($kk == '#DEFENDANTSTABLE#') 
                                  { if ($value) $img = _defendantsimage2_; else $img = _defendantsimage1_; 
                                     if (($permissions & _edit_) && (in_array ($key, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
                                     if (! (($permissions & _edit_) || ($permissions & _view_)))
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>$req</TD><TD$class$edit>Accuseds</TD></TR>", $display);
                                     continue;
                                  }
                               if ($kk == '#SENTENCETABLE#') 
                                  { if ($value) $img = _sentenceimage2_; else $img = _sentenceimage1_; 
                                     if (($permissions & _edit_) && (in_array ($key, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
                                     if (! (($permissions & _edit_) || ($permissions & _view_)))
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>$req</TD><TD$class$edit>Sentence</TD></TR>", $display);
                                     continue;
                                  }
                               if ($kk == '#VERDICTSTABLE#') 
                                  { $img = _verdictsimage_; 
                                     if (($permissions & _edit_) && (in_array ($key, $GLOBALS['requiredfields'][$table]))) $req = '<IMG src="images/dot33b.png" border="0">'; else $req = '&nbsp;';
                                     if (! (($permissions & _edit_) || ($permissions & _view_)))
                                        $display = str_replace ($kk, "", $display);
                                        else $display = str_replace ($kk, "<TR><TD class='th' Valign='top' $edit>$img{$_timg ("s=33&title={$GLOBALS['tablelabels'][$table][$key]}")}</TD><TD class='thr'>$req</TD><TD$class$edit>Verdicts</TD></TR>", $display);
                                     continue;
                                  }
                         
                               // check for #TIME# display
                               if (strpos ($display, "#TIME$kk") !== false)
                                 { if ($value > 0) $showtime = date ("$Dformat $Tformat", $value); else $showtime = "&nbsp;";
                                    $display = str_replace ("#TIME$kk", "<TR>" . _fieldLabel2 ($key, $GLOBALS['tablelabels'][$table][$key], $permissions) . "</TD><TD$class$edit>$prep$showtime$app</TD></TR>", $display);
                                    continue;
                                 }
                               // check for #DATE# display
                               if (strpos ($display, "#DATE$kk") !== false)
                                 { if ($value > 0) $showtime = date ($Dformat, $value); else $showtime = "&nbsp;";
                                    $display = str_replace ("#DATE$kk", "<TR>" . _fieldLabel2 ($key, $GLOBALS['tablelabels'][$table][$key], $permissions) . "</TD><TD$class$edit>$prep$showtime$app</TD></TR>", $display);
                                    continue;
                                 }
                               if (strpos ($display, "#TABLE$kk") !== false)
                                 { $ndx = strpos ($display, "#TABLE$kk");
                                    $ndx2 = $ndx + strlen ("#TABLE$kk");
                                    while (substr ($display, $ndx2, 1) != '#') $ndx2++;
                                    $ndx2++;
                                    $fullfield = substr ($display, $ndx, $ndx2 - $ndx);
                                    list ($dummy, $fetchID, $chunks) = explode ("#", substr ($fullfield, 1, -1));
                                    list ($ftable, $ffield, $alternative, $ldepend, $rdepend, $format) = explode (";", $chunks);
                                    $fvalue = _getField ($ftable, $value, $ffield, $rdepend);
                                    if ($fvalue !== false)
                                       { if (! $fvalue) $fvalue = $alternative;
                                          if (($ldepend) && (! $row[$ldepend])) $fvalue = '&nbsp;';
                                       } else $fvalue = '&nbsp;';
                                    if ($fvalue != '&nbsp;')
                                       { if ($format == 'INT') $fvalue = number_format (intval ($fvalue), 0, $decSep, $thouSep);
                                          if ($format == 'FLOAT') $fvalue = number_format (intval ($fvalue), $decimals, $decSep, $thouSep);
                                          if ($format == 'DATE') $fvalue = date ($Dformat, $fvalue);
                                          if ($format == 'TIME') $fvalue = date ("$Dformat $Tformat", $fvalue);
                                       }
                                    $display = str_replace ($fullfield, "<TR>" . _fieldLabel2 ($key, $GLOBALS['tablelabels'][$table][$key], $permissions) . "</TD><TD$class$edit>$prep$fvalue$app</TD></TR>", $display);
                                    continue;
                                 }
                               if (strpos ($display, "#FTTABLE$kk") !== false)
                                 { $ndx = strpos ($display, "#FTTABLE$kk");
                                    $ndx2 = $ndx + strlen ("#FTTABLE$kk");
                                    while (substr ($display, $ndx2, 1) != '#') $ndx2++;
                                    $ndx2++;
                                    $fullfield = substr ($display, $ndx, $ndx2 - $ndx);
                                    list ($dummy, $fetchID, $chunks) = explode ("#", substr ($fullfield, 1, -1));
                                    list ($ftable, $reffield, $ffield, $alternative, $ldepend, $rdepend, $format) = explode (";", $chunks);
                                    $fvalue = _getField2 ($ftable, $reffield, $value, $ffield, $rdepend);
                                    if ($fvalue !== false)
                                       { if (! $fvalue) $fvalue = $alternative;
                                          if (($ldepend) && (! $row[$ldepend])) $fvalue = '&nbsp;';
                                       } else $fvalue = '&nbsp;';
                                    if ($fvalue != '&nbsp;')
                                       { if ($format == 'INT') $fvalue = number_format (intval ($fvalue), 0, $decSep, $thouSep);
                                          if ($format == 'FLOAT') $fvalue = number_format (intval ($fvalue), $decimals, $decSep, $thouSep);
                                          if ($format == 'DATE') $fvalue = date ($Dformat, $fvalue);
                                          if ($format == 'TIME') $fvalue = date ("$Dformat $Tformat", $fvalue);
                                       }
                                    $display = str_replace ($fullfield, "<TR>" . _fieldLabel2 ($key, $GLOBALS['tablelabels'][$table][$key], $permissions) . "</TD><TD$class$edit>$prep$fvalue$app</TD></TR>", $display);
                                    continue;
                                 }
                               $value = stripslashes ($value);
                               if ($key == 'PASSWORD') for ($icnt=0;$icnt<strlen($value);$icnt++) $value[$icnt] = '•';
                               if ( ($key != 'CHANGED') && ($key != 'CHANGEDBY') && ($key != 'id') && (! isset ($GLOBALS['noformats'][$table][$key])) )
                                  { if ((isset ($GLOBALS['suppresszeros'][$table][$key])) && ($value == 0)) $value = "";
                                        else { if ($fieldtype == 'int') $value = number_format (intval ($value), 0, $decSep, $thouSep);
                                                   if ($fieldtype == 'real') $value = number_format ($value, $decimals, "$decSep", "$thouSep");
                                               }
                                  }
                               if ((isset ($GLOBALS['noformats'][$table][$key])) && (isset ($GLOBALS['suppresszeros'][$table][$key])) && ($value == 0)) $value = "";
                               if ("$prep$value$app" == "") $value = "&nbsp;";
                               $display = str_replace ($kk, "<TR>" . _fieldLabel2 ($key, $GLOBALS['tablelabels'][$table][$key], $permissions) . "</TD><TD$class$edit>$prep$value$app</TD></TR>", $display);
                            }
                               $result .= $display;
            }
   mysql_free_result ($sql2->sql_result);
return $result;
}


if (isset ($_POST['edited'])) $edited = $_POST['edited']; else $edited = "";

/* ---------------------------------------------------------------- */


include 'showtable.tableforms1.php';
include 'showtable.tables.php';
include 'showtable.tablejava.php';


/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */

$list = _showrecord ($table, $_POST['role'], $portal, $record);

/* ---------------------------------------------------------------- */

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein • e-mail: jan.kirstein\@jayksoft.com
Copyright © 2012 Jan Kirstein. All rights reserved!
-->

$style

$tablejava

<BODY style="margin: 8;">
<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD align="center" Valign="middle" ID="maincell">
<TABLE cellspacing="0" cellpadding="4" border="0" BGcolor="#E0A000">
<TR><TD>

<TABLE cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
$tableforms
$list
</TABLE>

</TD></TR></TABLE>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

$showtable_tables

</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

