<?php 
@session_start();

if (isset ($_GET['id'])) $_POST['id'] = $_GET['id'];
if (isset ($_GET['role'])) $_POST['role'] = $_GET['role'];
if (isset ($_GET['picktable'])) $_POST['picktable'] = $_GET['picktable'];
if (isset ($_GET['SQLQUERY'])) $_POST['SQLQUERY'] = $_GET['SQLQUERY'];

include_once 'std.php';

/* ---------------------------------------------------------------- */

include 'logincheck.php';

/* ---------------------------------------------------------------- */

include 'fields.php';		// for the _addSlashes2 () & _getField () functions
include 'colorgrade.php';

/* ---------------------------------------------------------------- */

function _searchString ($table)
{ $search = "";
   $txt = array ();
   $chk = array ();
   $chkfields = array ();
   $chksearch = array ();

   foreach ($_POST as $key => &$value)
               { if (! get_magic_quotes_gpc()) $value = addslashes ($value);
                  if ((substr ($key, 0, 11) == 'SEARCH_TXT_') && ($value != ''))
                     { $fields = substr ($key, 12);
                        foreach ($GLOBALS['searchfields'][$table] as $txtfield => $type)
                                     { if ($type == '%') array_push ($txt, "$txtfield LIKE '%$value%'");
                                     }
                     }
                  if ((substr ($key, 0, 11) == 'SEARCH_CHK_') && ($value != ''))
                     { $fieldindex = strpos ($key, '_', 12);
                        $field = substr ($key, $fieldindex + 1);
                        if (! in_array ($field, $chkfields)) array_push ($chkfields, $field);
                        $field = "$field = '$value'";
                        array_push ($chk, $field);
                     }
               }

   if (count ($txt) > 0) 
     { $search = implode (" OR ", $txt);
        $search = "($search)";
     }

   if (count ($chkfields) > 0) 
      { foreach ($chkfields as $label)
                      { $tmp = array ();
                         foreach ($chk as $chkentry)
                                      { if (substr ($chkentry, 0, strlen ($label)) == $label) array_push ($tmp, $chkentry);
                                      }
                         array_push ($chksearch, "(" . implode (" OR ", $tmp) . ")");
                      }
         if (strlen ($search) == 0) $search = implode (" AND ", $chksearch);
            else $search = "$search AND " . implode (" AND ", $chksearch);
      }

   if (strlen ($search) > 0) $search = "$search AND ";
return $search;
}

/* ---------------------------------------------------------------- */

function _searchForm ($table)
{ $form = "";
   $searchstring = "";
   $textfields = array ();
   $checkfields = array ();
   $checkarrays = array ();
   $radiofields = array ();
   $radioarrays = array ();
   $checks = array ();
   $radios = array ();
   foreach ($GLOBALS['searchfields'][$table] as $field => $type)
                { if ($type == '%') array_push ($textfields, $field);
                   if (strpos ($type, '_incl_') !== false) 
                      { array_push ($checkfields, $field);
                         list ($dmmy, $arrayid) = explode (";", $type);
                         array_push ($checkarrays, $arrayid);
                      }
                   if (strpos ($type, '_excl_') !== false) 
                      { array_push ($radiofields, $field);
                         list ($dmmy, $arrayid) = explode (";", $type);
                         array_push ($radioarrays, $arrayid);
                      }
                }

   if (count ($textfields) > 0)
      { $fields = implode ("_", $textfields);
         $textlabels = array ();
         foreach ($textfields as $label) array_push ($textlabels, $GLOBALS['tablelabels'][$table][$label]);
         $label = implode (" , ", $textlabels);
         $help = <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" value="i" class="btn" 
onclick="alert('Keyword / phrase (or part of-) entered in the text field\\nwill be looked for in the following column(s):\\n\\n$label\\n\\nThe search is NOT case sensitive.');">

NXT;
         $fieldname = "SEARCH_TXT_$fields";
         if (get_magic_quotes_gpc()) $fieldvalue = htmlentities (stripslashes ($_POST[$fieldname]), ENT_QUOTES); else $fieldvalue = htmlentities ($_POST[$fieldname], ENT_QUOTES);

$KeyWord = 'Keyword';
$SearchTxt = 'Search';		 
switch ($_SESSION['selLang']) {
	case "FR":
		$KeyWord = 'Mot-clÃ©';
		$SearchTxt = 'Chercher';
		break;
	case "ES":
		$KeyWord = 'Palabra clave';
		$SearchTxt = 'Buscar';
		break;
}
		 
         $form .= <<<NXT
$KeyWord <INPUT type="TEXT" class="btn" size="24" name="$fieldname" value="$fieldvalue">

NXT;
      } else $help = "";

   if ($form != '') 
      $form .= <<<NXT
&nbsp;&nbsp;
<INPUT type="SUBMIT" value=$SearchTxt class="btn" onclick="document.showtable.submit();">$help

NXT;

   if (count ($checkarrays) > 0)
      { $i = 0;
         foreach ($checkarrays as $arrayname)
                      { $fieldname = $checkfields[$i];
                         $r = 0;
                         foreach ($GLOBALS[$arrayname] as $value)
                                      { $fname = "SEARCH_CHK_{$r}_$fieldname";
                                         if ($_POST[$fname]) $set = "checked"; else $set = "";
                                         $check = <<<NXT
<INPUT type="CHECKBOX" name="$fname" value="$value" $set>$value

NXT;
                                          array_push ($checks, $check);
                                          $r++;
                                      }
                         $check = implode ("&nbsp;OR&nbsp;", $checks);
                         $form .= "<BR>AND {$GLOBALS['tablelabels'][$table][$checkfields[$i]]} = $check";
                         $i++;
                      }
      }
return $form;
}

/* ---------------------------------------------------------------- */

function _editSettings ($recId)
{ $result = <<<NXT
onclick="_pick ($recId);" style="cursor: default;"
NXT;

return $result;
}

/* ---------------------------------------------------------------- */

function _fieldLabel ($field, $label)
{ if ($GLOBALS['orderby'] == $field)
      { $ts = "sort1.png";
         if ($GLOBALS['order'] == 'ASC') $ordertitle = "- descending"; else $ordertitle = "- ascending";
      } else { $ts = "sort00.png";
                    if ($GLOBALS['order'] == 'ASC') $ordertitle = "- ascending"; else $ordertitle = "- descending";
                }
   return <<<NXT
<TD class="thp" Valign="top" style="cursor: default;" title="Order by $label $ordertitle"
onclick="
if (document.showtable.orderby.value == '$field') 
  { if (document.showtable.order.value == 'ASC') document.showtable.order.value = 'DESC'; else document.showtable.order.value = 'ASC';
  } else document.showtable.orderby.value = '$field';
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();
"><IMG src="timg.php?s=33&title=$label" border="0"><IMG src="images/$ts" border="0"></TD>

NXT;
}

function _fieldLabel2 ($label)
{ return <<<NXT
<TD class="thp" Valign="top"><IMG src="timg.php?s=33&title=$label" border="0"></TD>

NXT;
}

/* ---------------------------------------------------------------- */

$tablecolumns = 0;

if (isset ($_POST['order'])) $order = $_POST['order']; else $order = 'ASC';
if (isset ($_POST['orderby'])) $orderby = $_POST['orderby']; else $orderby = 'id';

function _showtable ($table, $ROLE, &$first, &$count, &$total, $maxrecs, $additionalFilter)
{ global $tablecolumns;
   $permissions = 0x00; 
   if (isset ($GLOBALS['pickheaders'][$table])) $result = $GLOBALS['pickheaders'][$table]; else $result = $GLOBALS['tableheaders'][$table];
   $result = str_replace ("#NOTES#", "<TD class=\"th\">" . _notesimage0_ . "</TD>", $result);
   $result = str_replace ("#DOCS#", "<TD class=\"th\">" . _docsimage0_ . "</TD>", $result);
   if ($permissions & _view_) $result = str_replace ("#SHOWREC#", "<TD class=\"th\">" . _recimage0_ . "</TD>", $result);
      else $result = str_replace ("#SHOWREC#", "", $result);

   if ($permissions & _view_) $result = str_replace ("#PROFILE#", "<TD class=\"th\">" . _profileimage_ . "</TD>", $result);
      else $result = str_replace ("#PROFILE#", "", $result);

   foreach ($GLOBALS['tablelabels'][$table] as $key => &$value)
                { if (strpos ($result, "#SORT#$key#") !== false) $result = str_replace ("#SORT#$key#", _fieldLabel ($key, $value), $result);
                   if (strpos ($result, "#$key#") !== false) $result = str_replace ("#$key#", _fieldLabel2 ($value), $result);
                }

   if (isset ($GLOBALS['pickdisplays'][$table])) $display = $GLOBALS['pickdisplays'][$table]; else $display = $GLOBALS['tabledisplays'][$table];

   if ($permissions & _delete_) 
      { $result = <<<NXT
<TD class="thp"><IMG src="images/del3.png"></TD>
$result
NXT;
      }

 if ($changeinfo) $result = _fieldLabel ('CHANGED', 'Chng.') . _fieldLabel ('CHANGEDBY', 'By') . "\n$result\n";

   $result = <<<NXT
<TR>
$result
</TR>
NXT;
   $tablecolumns = substr_count ($result, '<TD');

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

   $searchform = _searchForm ($table);
   if ($GLOBALS['order'] == 'ASC')
      { if ($GLOBALS['orderby'] == 'id') $sorttitle = "Order by 'id' - descending"; else $sorttitle = "Order by 'id' - ascending";
         $orderimg = <<<NXT
<IMG src="images/asc.png" 
onclick="
if (document.showtable.orderby.value == 'id') document.showtable.order.value = 'DESC';
   else document.showtable.orderby.value = 'id';
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
NXT;
      } else { if ($GLOBALS['orderby'] == 'id') $sorttitle = "Order by 'id' - ascending"; else $sorttitle = "Order by 'id' - descending";
                       $orderimg = <<<NXT
<IMG src="images/desc.png" 
onclick="
if (document.showtable.orderby.value == 'id') document.showtable.order.value = 'ASC';
   else document.showtable.orderby.value = 'id';
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
NXT;
                }
   $searchorder = " ORDER BY {$GLOBALS['orderby']} {$GLOBALS['order']}";
   $result = <<<NXT
<TR>
<TD colspan="$tablecolumns" class="thp">
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD class="th3" Valign="middle">Pick from: </TD>
<TD class="th3"><IMG src="timg.php?s=3&title={$GLOBALS['tablenames'][$table]}" border="0"></TD>
<TD class="th3" Valign="middle" style="padding: 4 0 0 0;">#SHOWING1#</TD>
<TD class="th3" Valign="middle">#SHOWING2#</TD>
<TD class="th3" align="right" Valign="middle" TITLE="$sorttitle">$orderimg</TD>
<TD class="th3" align="right" Valign="middle"><IMG ID="exclm" src="images/refr.png"
onclick="
document.getElementById('newtablebgr').style.visibility = 'visible'; 
document.showtable.submit();
" 
TITLE="Reload table"></TD>
</TR>
</TABLE>
</TR>
<TR>
<TD colspan="$tablecolumns" class="tb">$searchform</TD>
</TR>
$result
NXT;

   $searchstring = _searchString ($table);
   if ($additionalFilter) 
      { if ($searchstring) $searchstring .= " $additionalFilter AND";
            else $searchstring = "$additionalFilter AND";
      }

   $sql = new mySQLclass();
   $query = "SELECT * FROM $table WHERE $searchstring DELETED = 0 $searchorder";
   $sql->_sqlLookup ($query);
   $total = mysql_num_rows ($sql->sql_result); 
   if ($sql->sql_result === false) $sql->_sqlerror ();
   mysql_free_result ($sql->sql_result);

   $first--;
   $query = "SELECT * FROM $table WHERE $searchstring DELETED = 0 $searchorder LIMIT $first,$maxrecs";
   $first++;
   $sql->_sqlLookup ($query);
   $count = mysql_num_rows ($sql->sql_result);
   if ($sql->sql_result === false) $sql->_sqlerror ();

   $i = 0;
   while ($row = mysql_fetch_assoc ($sql->sql_result))
            { if ($i & 1) { $class = " class=\"ta\""; $bgcolor = "D7D6D0"; } else { $class = " class=\"tb\""; $bgcolor = "F7F6F2"; }
               $i++;
               $_display = $display;
               $r = 0;
               if (strpos ($_display, "#SHOWREC#") !== false)
                 {    $view = _editSettings ($permissions, $table, "SHOWREC", $row['id'], 0);
                       if ($permissions & _view_) $_display = str_replace ("#SHOWREC#", "<TD$class$view TITLE='View full record'>" . _recimage1_ . "</TD>", $_display);
                       else $_display = str_replace ("#SHOWREC#", "", $_display);
                 }

               if (strpos ($_display, "#PROFILE#") !== false)
                 {    $view = _editSettings ($permissions, $table, "PROFILE", $row['id'], 0);
                       if ($permissions & _view_) $_display = str_replace ("#PROFILE#", "<TD$class$view TITLE='Profile'>" . _profileimage_ . "</TD>", $_display);
                       else $_display = str_replace ("#PROFILE#", "", $_display);
                 }
               foreach ($row as $key => &$value)
                            { $fieldtype = mysql_field_type ($sql->sql_result, $r);
                               if (isset ($GLOBALS['prepends'][$table][$key])) $prep = $GLOBALS['prepends'][$table][$key]; else $prep = '';
                               if (isset ($GLOBALS['apppends'][$table][$key])) $app = $GLOBALS['apppends'][$table][$key]; else $app = '';
                               $r++;
                               if (isset($GLOBALS['tablealigns'][$table][$key])) $align = " align='{$GLOBALS['tablealigns'][$table][$key]}'"; else $align = "";
                               $mouseover = "";
                               $edit = _editSettings ($row['id']);
                               $kk = "#$key#";
                               if ($kk == '#NOTES#')
                                 { if ($value > 0) 
                                       { if ($value > $LL) $img = _notesimage4_; else $img = _notesimage2_;
                                          if ($logintime < $value) $img = _notesimage3_; 
                                          $showtime = " TITLE='Notes: Last updated: " . date ("$Dformat $Tformat", $value) . "'"; 
                                          $_display = str_replace ("#NOTES#", "<TD$align $class $edit $showtime>$img</TD>", $_display);
                                          continue;
                                       } else { $showtime = " TITLE='Add note'";
                                                    $_display = str_replace ("#NOTES#", "<TD$align $class $edit $showtime>" . _notesimage1_ . "</TD>", $_display);
                                                    continue;
                                                 }
                                 }
                               if ($kk == '#DOCS#')
                                 { if ($value > 0) 
                                       { if ($value > $LL) $img = _docsimage4_; else $img = _docsimage2_;
                                          if ($logintime < $value) $img = _docsimage3_; 
                                          $showtime = " TITLE='Documents: Last updated: " . date ("$Dformat $Tformat", $value) . "'"; 
                                          $_display = str_replace ("#DOCS#", "<TD$align $class $edit $showtime>$img</TD>", $_display);
                                          continue;
                                       } else { $showtime = " TITLE='Open Documents'";
                                                    $_display = str_replace ("#DOCS#", "<TD$align $class $edit $showtime>" . _docsimage1_ . "</TD>", $_display);
                                                    continue;
                                                 }
                                 }

                               // check for #TIME# display
                               if (strpos ($_display, "#TIME$kk") !== false)
                                 { if ($value > 0) $showtime = date ("$Dformat $Tformat", $value); else $showtime = "&nbsp;";
                                    $_display = str_replace ("#TIME$kk", "<TD$align $class $edit>$prep$showtime$app</TD>", $_display);
                                    continue;
                                 }
                               // check for #DATE# display
                               if (strpos ($_display, "#DATE$kk") !== false)
                                 { if ($value > 0) $showtime = date ($Dformat, $value); else $showtime = "&nbsp;";
                                    $_display = str_replace ("#DATE$kk", "<TD$align $class $edit>$prep$showtime$app</TD>", $_display);
                                    continue;
                                 }
                               if (strpos ($_display, "#TABLE$kk") !== false)
                                 { $ndx = strpos ($_display, "#TABLE$kk");
                                    $ndx2 = $ndx + strlen ("#TABLE$kk");
                                    while (substr ($_display, $ndx2, 1) != '#') $ndx2++;
                                    $ndx2++;
                                    $fullfield = substr ($_display, $ndx, $ndx2 - $ndx);
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
                                    $_display = str_replace ($fullfield, "<TD$align $class $edit>$prep$fvalue$app</TD>", $_display);
                                    continue;
                                 }
                               if (strpos ($_display, "#FTTABLE$kk") !== false)
                                 { $ndx = strpos ($_display, "#FTTABLE$kk");
                                    $ndx2 = $ndx + strlen ("#FTTABLE$kk");
                                    while (substr ($_display, $ndx2, 1) != '#') $ndx2++;
                                    $ndx2++;
                                    $fullfield = substr ($_display, $ndx, $ndx2 - $ndx);
                                    list ($dummy, $fetchID, $chunks) = explode ("#", substr ($fullfield, 1, -1));
                                    list ($ftable, $reffield, $ffield, $alternative, $ldepend, $rdepend, $format, $trunc) = explode (";", $chunks);
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
                                    $_display = str_replace ($fullfield, "<TD$align $class $edit>$prep$fvalue$app</TD>", $_display);
                                    continue;
                                 }
                               $value = stripslashes ($value);

                               if ( ($key != 'CHANGED') && ($key != 'CHANGEDBY') && ($key != 'id') && (! isset ($GLOBALS['noformats'][$table][$key])) )
                                  { if ((isset ($GLOBALS['suppresszeros'][$table][$key])) && ($value == 0)) $value = "";
                                        else { if ($fieldtype == 'int') $value = number_format (intval ($value), 0, $decSep, $thouSep);
                                                   if ($fieldtype == 'real') $value = number_format ($value, $decimals, "$decSep", "$thouSep");
                                               }
                                  }
                               if ((isset ($GLOBALS['noformats'][$table][$key])) && (isset ($GLOBALS['suppresszeros'][$table][$key])) && ($value == 0)) $value = "";
                               if (isset ($GLOBALS['truncates'][$table][$key])) 
                                  { $ovrtitle = ' TITLE="' . str_replace ("<br>", "\n", $value) . '"';
                                     if ($GLOBALS['truncates'][$table][$key] == 'nl') 
                                        { if (strpos ($value, "<br>") !== false) $value = substr ($value, 0, strpos ($value, "<br>"));
                                        } else $value = substr ($value, 0, $GLOBALS['truncates'][$table][$key]);
                                  } else $ovrtitle = "";
                               if ("$prep$value$app" == "") $value = "&nbsp;";


                               $_display = str_replace ($kk, "<TD$align $class $edit>$prep$value$app</TD>", $_display);
                            }
               $result .= "<TR onmouseover=\"this.style.color='#0000C0';\" onmouseout=\"this.style.color='#000000';\">\n";
               if ($changeinfo) 
                 { $usr = _getField ('users', $row['CHANGEDBY'], 'REAL_NAME', '');
                    if (! $usr) $usr = 'Maestro';
                    $chngtime = date ("d/m", $row['CHANGED']);
                    $colorfrom = $GLOBALS['timestamp'] - (7 * 60*60*24);
                    if ($row['CHANGED'] >= $colorfrom)
                      { $color = _colorgrade (0, $row['CHANGED'], $colorfrom, $GLOBALS['timestamp']);
                         $bckgr = <<<NXT
style="color: #$color;"
NXT;
                      } else $bckgr = <<<NXT
style="color: #$bgcolor;"
NXT;
                    $result .= <<<NXT
<TD$class><SPAN style="font-family: Tfont; padding: 0 2 0 0;"><B $bckgr>ª</B></SPAN>$chngtime</TD>
<TD$class><SPAN style="font-size: 7pt;">$usr</SPAN></TD>

NXT;
                 }
               if ($permissions & _delete_) 
                 { $dellabel = _addSlashes2 ($row[$deletelabel]);
                    $result .= <<<NXT
<TD$class style="cursor: {$GLOBALS['cursor']}"
onclick="_goDelete ('{$row['id']}', '$dellabel');"><IMG src="images/del2.png"></TD>
NXT;
                 }

               $result .= $_display;
               $result .= "</TR>\n";
            }
   mysql_free_result ($sql->sql_result);
return $result;
}


/* ---------------------------------------------------------------- */

$tablejava = <<<NXT
<SCRIPT language="JavaScript">
function _pick (recID)
{ parent.editframe.document.parform.NEW_VALUE.value = recID;
parent.editframe.document.parform.submit();
document.getElementById('newtablebgr').style.visibility = 'hidden';
//parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
parent.document.getElementById('pickrecordtable').style.visibility = 'hidden';
}
</SCRIPT>
NXT;

/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */


define("_first_", 1);

if (isset ($_POST['first'])) $first = $_POST['first']; else $first = _first_;
if ($first < _first_) $first = _first_;
$count = 0;
$total = 0;
$max = 20;

$table = $_POST['picktable'];
include "$table.php";

/* ---------------------------------------------------------------- */

$list = _showtable ($table, $_POST['role'], $first, $count, $total, $max, $_POST['SQLQUERY']);

include 'countspick.php';

$list = <<<NXT
<TABLE cellspacing="0" cellpadding="2" border="0" style="border-style: solid; border-width: 0 1 1 0; border-color: $bordercolor;">
<FORM name="showtable" method="POST" action="pickrecord.php" 
onsubmit="document.getElementById('newtablebgr').style.visibility = 'visible';">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="first" value="$first">
<INPUT type="HIDDEN" name="order" value="{$GLOBALS['order']}">
<INPUT type="HIDDEN" name="orderby" value="{$GLOBALS['orderby']}">
<INPUT type="HIDDEN" name="picktable" value="$table">
$list
<TR>
<TD class="thp" colspan="$tablecolumns" height="4">
$chunk
</TD>
</TR>
</FORM>
</TABLE>

NXT;



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
<TD align="center" Valign="middle">
$list 
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<TABLE ID="newtablebgr" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

</BODY>
</HTML>

NXT;


echo $page;

error_reporting ($errorlevel);

?>

