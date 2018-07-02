<?php 
@session_start();

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

function _searchboxname ($table)
{
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
	{ 
		$fields = implode ("_", $textfields);
	}
	return "SEARCH_TXT_" . $fields;
}


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
		 $help = '';
		 if (($table == "arrests") || ($table == "cases")) {
			 $searchsuspectTxt = "Search Suspect Name";
			 switch ($_SESSION['selLang']) {
				case "FR":
					$searchsuspectTxt = "Rechercher Nom du suspect";
					break;
				case "ES":
					$searchsuspectTxt = "Buscar Nombre del sospechoso";
					break;
			}
			if (isset($_POST['cbox_suspect']) && ($_POST['cbox_suspect'] == 'search_suspect')) {

		 $help = <<<NXT
&nbsp;&nbsp;<label><input type="checkbox" name="cbox_suspect" value="search_suspect" checked> $searchsuspectTxt</label>
NXT;
}
else	
{
$help = <<<NXT
&nbsp;&nbsp;<label><input type="checkbox" name="cbox_suspect" value="search_suspect"> $searchsuspectTxt</label>
NXT;
}
}	 
         $help .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" value="i" class="btn" 
onclick="alert('Keyword / phrase (or part of-) entered in the text field\\nwill be looked for in the following column(s):\\n\\n$label\\n\\nThe search is NOT case sensitive.');">

NXT;
         $fieldname = "SEARCH_TXT_$fields";
         if (get_magic_quotes_gpc()) $fieldvalue = htmlentities (stripslashes ($_POST[$fieldname]), ENT_QUOTES); else $fieldvalue = htmlentities ($_POST[$fieldname], ENT_QUOTES);
		 
$KeyWord = 'Keyword';
$SearchTxt = 'Search';		 
switch ($_SESSION['selLang']) {
	case "FR":
		$KeyWord = 'Mot-clé';
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
                                      { if ($value == 'REFERENCE') continue;
                                         $fname = "SEARCH_CHK_{$r}_$fieldname";
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

include 'showtable.editsettings.php';

/* ---------------------------------------------------------------- */

function _fieldLabel ($field, $label)
{ global $_timg;
   if ($GLOBALS['orderby'] == $field)
      { $ts = "sort1.png";
         if ($GLOBALS['order'] == 'ASC') $ordertitle = "- descending"; else $ordertitle = "- ascending";
      } else { $ts = "sort00.png";
                    if ($GLOBALS['order'] == 'ASC') $ordertitle = "- ascending"; else $ordertitle = "- descending";
                }
   return <<<NXT
<TD class="th" Valign="top" style="cursor: default;" title="Order by $label $ordertitle"
onclick="
if (document.showtable.orderby.value == '$field') 
  { if (document.showtable.order.value == 'ASC') document.showtable.order.value = 'DESC'; else document.showtable.order.value = 'ASC';
  } else document.showtable.orderby.value = '$field';
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();
">{$_timg ("s=33&title=$label")}<IMG src="images/$ts" border="0"></TD>

NXT;
}

function _fieldLabel2 ($label)
{ global $_timg;
   return <<<NXT
<TD class="th" Valign="top">{$_timg ("s=33&title=$label")}</TD>

NXT;
}

/* ---------------------------------------------------------------- */

$tablecolumns = 0;

if (isset ($_POST['order'])) $order = $_POST['order']; else $order = 'DESC';
if (isset ($_POST['orderby'])) $orderby = $_POST['orderby']; else $orderby = 'id';

function _showtable ($table, $ROLE, $portal, &$first, &$count, &$total, $maxrecs, $deletelabel, $additionalFilter, $tablename)
{ global $tablecolumns, $_timg, $suspectname;

   if ($ROLE == 'GOD') $permissions = 0xFF; else $permissions = $GLOBALS['permissions'][$ROLE][$portal][$table];
   $result = $GLOBALS['tableheaders'][$table];
   $result = str_replace ("#NOTES#", "<TD class=\"th\">" . _notesimage0_ . "</TD>", $result);
   $result = str_replace ("#DOCS#", "<TD class=\"th\">" . _docsimage0_ . "</TD>", $result);
   if ($permissions & _view_) $result = str_replace ("#SHOWREC#", "<TD class=\"th\">" . _recimage0_ . "</TD>", $result);
      else $result = str_replace ("#SHOWREC#", "", $result);
   if ($permissions & _view_) $result = str_replace ("#SUBTABLE#", "<TD class=\"th\">" . _tblimage0_ . "</TD>", $result);
      else $result = str_replace ("#SUBTABLE#", "", $result);

   if ($permissions & _view_) $result = str_replace ("#EVIDENCETABLE#", "<TD class=\"th\">" . _evidenceimage0_ . "</TD>", $result);
      else $result = str_replace ("#EVIDENCETABLE#", "", $result);
   if ($permissions & _view_) $result = str_replace ("#OFFENDERSTABLE#", "<TD class=\"th\">" . _offendersimage0_ . "</TD>", $result);
      else $result = str_replace ("#OFFENDERSTABLE#", "", $result);
   if ($permissions & _view_) $result = str_replace ("#DEFENDANTSTABLE#", "<TD class=\"th\">" . _defendantsimage0_ . "</TD>", $result);
      else $result = str_replace ("#DEFENDANTSTABLE#", "", $result);
   if ($permissions & _view_) $result = str_replace ("#SENTENCETABLE#", "<TD class=\"th\">" . _sentenceimage0_ . "</TD>", $result);
      else $result = str_replace ("#SENTENCETABLE#", "", $result);
   if ($permissions & _view_) $result = str_replace ("#PROFILE#", "<TD class=\"th\">" . _profileimage_ . "</TD>", $result);
      else $result = str_replace ("#PROFILE#", "", $result);

   if ($permissions & _view_) $result = str_replace ("#VERDICTSTABLE#", "<TD class=\"th\">" . _verdictsimage_ . "</TD>", $result);
      else $result = str_replace ("#VERDICTSTABLE#", "", $result);

   foreach ($GLOBALS['tablelabels'][$table] as $key => &$value)
                { if (strpos ($result, "#SORT#$key#") !== false) $result = str_replace ("#SORT#$key#", _fieldLabel ($key, $value), $result);
                   if (strpos ($result, "#$key#") !== false) $result = str_replace ("#$key#", _fieldLabel2 ($value), $result);
                }

   $sql = new mySQLclass();
   $sqlnew = new mySQLclass();
   $query = "SHOW COLUMNS FROM $table LIKE 'CHANGEDBY'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror ();
   if ($row = mysql_fetch_assoc ($sql->sql_result)) $changeinfo = true; else $changeinfo = false;
   if (($ROLE != 'GOD') && ($ROLE != 'SYSADMIN') && ($ROLE != 'HQ')) $changeinfo = false;

// new: owner entity allowed editing only
   $query = "SHOW COLUMNS FROM $table LIKE 'OWNERENTITY'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror ();
   if ($row = mysql_fetch_assoc ($sql->sql_result)) $editif = _getField ('users', $_POST['id'], 'ENTITY', ''); else $editif = '';
   if (($ROLE == 'GOD') || ($ROLE == 'SYSADMIN') || ($ROLE == 'HQ')) $editif = '';

if ($editif) $filteredpermissions = $permissions & _view_; else $filteredpermissions = $permissions;

//

   $display = $GLOBALS['tabledisplays'][$table];

   if ($filteredpermissions & _delete_) 
      { $result = <<<NXT
<TD class="th"><IMG src="images/del3.png"></TD>
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
   if ($tablename) $_name = $tablename; else $_name = $GLOBALS['tablenames'][$table];
   if (! $tablename) $t_name = <<<NXT
<TD class="th3">{$_timg ("s=3&title=$_name")}</TD>
NXT;
      else $t_name = <<<NXT
<TD class="th3"><B>$_name •&nbsp;</B></TD>
NXT;
   $result = <<<NXT
<TR>
<TD colspan="$tablecolumns" class="th">
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0">
<TR>$t_name
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
   $searchboxName = _searchboxname ($table);
   
   if ($additionalFilter) 
      { if ($searchstring) $searchstring .= " $additionalFilter AND";
            else $searchstring = "$additionalFilter AND";
      }

   if (isset ($_POST['SQLQUERY']) && ($_POST['SQLQUERY'] != ''))
      { $SQLQUERY = str_replace ( "\\" , "'" , $_POST['SQLQUERY']);
         $SQLQUERY = str_replace ( "''" , "'" , $SQLQUERY);
         $query = "SELECT * FROM $table WHERE $SQLQUERY";
      }
      else 
	  {
		$query = "SELECT * FROM $table WHERE $searchstring DELETED = 0 $searchorder";
		//echo (" :: Table :: " . $table);
		//echo (" :: searchboxName :: " . $searchboxName);
		//echo(" :: Field Name :: " . $_POST[$searchboxName]);
		if (isset($_POST['cbox_suspect']) && ($_POST['cbox_suspect'] == 'search_suspect') && (isset($_POST[$searchboxName]))) 
		{
			if ($table == "arrests")
			{
				$query = "SELECT * FROM $table WHERE DELETED = 0 AND UWA_CASEID IN (SELECT UWA_CASEID FROM offenderstable WHERE OFFENDER IN (SELECT ID FROM suspects WHERE SUSPECT_NAME LIKE '%" . $_POST[$searchboxName] . "%'))";
				//echo("HERE1 ARRESTS : " . $query);
			}
			if ($table == "cases")
			{
				$query = "SELECT * FROM $table WHERE DELETED = 0 AND DEFENDANTSTABLE IN (SELECT CHANGED FROM defendantstable WHERE DEFENDANT IN (SELECT ID FROM suspects WHERE SUSPECT_NAME LIKE '%" . $_POST[$searchboxName] . "%'))";
				//echo("HERE1 CASES : " . $query);
			}
		}
	  }
   $sql->_sqlLookup ($query);
   $total = mysql_num_rows ($sql->sql_result); 
   if ($sql->sql_result === false) $sql->_sqlerror ();
   mysql_free_result ($sql->sql_result);

   $first--;
   if (isset ($_POST['SQLQUERY']) && ($_POST['SQLQUERY'] != ''))
      { $SQLQUERY = str_replace ( "\\" , "'" , $_POST['SQLQUERY']);
         $SQLQUERY = str_replace ( "''" , "'" , $SQLQUERY);
         $query = "SELECT * FROM $table WHERE $SQLQUERY LIMIT $first,$maxrecs";
      }
      else 
	  {	
		$query = "SELECT * FROM $table WHERE $searchstring DELETED = 0 $searchorder LIMIT $first,$maxrecs";
		if (isset($_POST['cbox_suspect']) && ($_POST['cbox_suspect'] == 'search_suspect') && (isset($_POST[$searchboxName]))) 
		{
			if ($table == "arrests")
			{
				$query = "SELECT * FROM $table WHERE DELETED = 0 AND UWA_CASEID IN (SELECT UWA_CASEID FROM offenderstable WHERE OFFENDER IN (SELECT ID FROM suspects WHERE SUSPECT_NAME LIKE '%" . $_POST[$searchboxName] . "%'))  $searchorder LIMIT $first,$maxrecs";
				//echo("HERE2 ARRESTS : " . $query);
			}
			if ($table == "cases")
			{
				$query = "SELECT * FROM $table WHERE DELETED = 0 AND DEFENDANTSTABLE IN (SELECT CHANGED FROM defendantstable WHERE DEFENDANT IN (SELECT ID FROM suspects WHERE SUSPECT_NAME LIKE '%" . $_POST[$searchboxName] . "%'))  $searchorder LIMIT $first,$maxrecs";
				//echo("HERE2 CASES : " . $query);
			}
		}
	  }
   $first++;
   $sql->_sqlLookup ($query);
   $count = mysql_num_rows ($sql->sql_result);
   if ($sql->sql_result === false) $sql->_sqlerror ();

   $i = 0;
   while ($row = mysql_fetch_assoc ($sql->sql_result))
            { if ($i & 1) { $class = " class=\"ta\""; $bgcolor = "D7D6D0"; } else { $class = " class=\"tb\""; $bgcolor = "F7F6F2"; }
               if (($ROLE != 'GOD') && (isset ($GLOBALS['conditions'][$ROLE][$portal][$table]))) $goahead = eval ($GLOBALS['conditions'][$ROLE][$portal][$table]); else $goahead = true;
               if (! $goahead) 
                  { $count--;
                     $total--;
                     continue;
                  }
               $i++;
               $_display = $display;
               $r = 0;

   if ($editif) if ($row['OWNERENTITY'] != $editif) $filteredpermissions = $permissions & _view_; else $filteredpermissions = $permissions;

               if (strpos ($_display, "#SHOWREC#") !== false)
                 {    $view = _editSettings ($filteredpermissions, $table, "SHOWREC", $row['id'], 0);
                       if ($filteredpermissions & _view_) $_display = str_replace ("#SHOWREC#", "<TD$class$view TITLE='View full record'>" . _recimage1_ . "</TD>", $_display);
                       else $_display = str_replace ("#SHOWREC#", "", $_display);
                 }
               foreach ($row as $key => &$value)
                            { $fieldlen = mysql_field_len ($sql->sql_result, $r);
                               $fieldtype = mysql_field_type ($sql->sql_result, $r);
                               if (isset ($GLOBALS['prepends'][$table][$key])) $prep = $GLOBALS['prepends'][$table][$key]; else $prep = '';
                               if (isset ($GLOBALS['apppends'][$table][$key])) $app = $GLOBALS['apppends'][$table][$key]; else $app = '';
                               if (substr ($GLOBALS['tablehandlings'][$table][$key], 0, 6) == '_float') list ($fieldlen, $decimals, $minval, $maxval) = explode (",", substr ($GLOBALS['tablehandlings'][$table][$key], 7, -1));
                               $r++;
                               if (isset($GLOBALS['tablealigns'][$table][$key])) $align = " align='{$GLOBALS['tablealigns'][$table][$key]}'"; else $align = "";
                               $mouseover = "";
                               $edit = _editSettings ($filteredpermissions, $table, $key, $row['id'], $fieldlen);
                               $kk = "#$key#";
                               if ($kk == '#NOTES#')
                                 { if ($value > 0) 
                                       { if ($value > $LL) $img = _notesimage4_; else $img = _notesimage2_;
                                          if ($logintime < $value) $img = _notesimage3_; 
                                          $showtime = " TITLE='Notes: Last updated: " . date ("$Dformat $Tformat", $value) . "'"; 
                                          $_display = str_replace ("#NOTES#", "<TD$align$class$edit$showtime>$img</TD>", $_display);
                                          continue;
                                       } else { $showtime = " TITLE='Add note'";
                                                    $_display = str_replace ("#NOTES#", "<TD$align$class$edit$showtime>" . _notesimage1_ . "</TD>", $_display);
                                                    continue;
                                                 }
                                 }
                                if ($kk == '#SUBTABLE#')
                                  { $view = _editSettings ($filteredpermissions, $table, "SUBTABLE", $row['id'], 0);
                                     if ($value) $img = _tblimage1_; else $img = _tblimage2_;
                                     if ($filteredpermissions & _view_) $_display = str_replace ("#SUBTABLE#", "<TD$class$view TITLE='View / edit subtable'>$img</TD>", $_display);
                                       else $_display = str_replace ("#SUBTABLE#", "", $_display);
                                  }
                                if ($kk == '#EVIDENCETABLE#')
                                  { $view = _editSettings ($filteredpermissions, $table, "EVIDENCETABLE", $row['id'], 0);
                                     if ($value) $img = _evidenceimage2_; else $img = _evidenceimage1_;
                                     if ($filteredpermissions & _view_) $_display = str_replace ("#EVIDENCETABLE#", "<TD$class$view TITLE='Open evidence table'>$img</TD>", $_display);
                                        else $_display = str_replace ("#EVIDENCETABLE#", "", $_display);
                                  }
                                if ($kk == '#OFFENDERSTABLE#')
                                  { $view = _editSettings ($filteredpermissions, $table, "OFFENDERSTABLE", $row['id'], 0);
                                     if ($value) $img = _offendersimage2_; else $img = _offendersimage1_;
                                     if ($filteredpermissions & _view_) $_display = str_replace ("#OFFENDERSTABLE#", "<TD$class$view TITLE='Open offenders table'>$img</TD>", $_display);
                                        else $_display = str_replace ("#OFFENDERSTABLE#", "", $_display);
                                  }
                                if ($kk == '#DEFENDANTSTABLE#')
                                  { $view = _editSettings ($filteredpermissions, $table, "DEFENDANTSTABLE", $row['id'], 0);
                                     if ($value) $img = _defendantsimage2_; else $img = _defendantsimage1_;
                                     if ($filteredpermissions & _view_) $_display = str_replace ("#DEFENDANTSTABLE#", "<TD$class$view TITLE='Open accuseds table'>$img</TD>", $_display);
                                        else $_display = str_replace ("#DEFENDANTSTABLE#", "", $_display);
                                  }
                                if ($kk == '#SENTENCETABLE#')
                                  { $view = _editSettings ($filteredpermissions, $table, "SENTENCETABLE", $row['id'], 0);
                                     if ($value) $img = _sentenceimage2_; else $img = _sentenceimage1_;
                                     if ($filteredpermissions & _view_) $_display = str_replace ("#SENTENCETABLE#", "<TD$class$view TITLE='Open sentence table'>$img</TD>", $_display);
                                        else $_display = str_replace ("#SENTENCETABLE#", "", $_display);
                                  }
                               if ($kk == '#DOCS#')
                                 { if ($value > 0) 
                                       { if ($value > $LL) $img = _docsimage4_; else $img = _docsimage2_;
                                          if ($logintime < $value) $img = _docsimage3_; 
                                          $showtime = " TITLE='Documents: Last updated: " . date ("$Dformat $Tformat", $value) . "'"; 
                                          $_display = str_replace ("#DOCS#", "<TD$align$class$edit$showtime>$img</TD>", $_display);
                                          continue;
                                       } else { $showtime = " TITLE='Open Documents'";
                                                    $_display = str_replace ("#DOCS#", "<TD$align$class$edit$showtime>" . _docsimage1_ . "</TD>", $_display);
                                                    continue;
                                                 }
                                 }
                               if ($kk == '#PROFILE#')
                                 { $img = _profileimage_;
                                    $showtime = " TITLE='Profile'"; 
                                    $_display = str_replace ("#PROFILE#", "<TD$align$class$edit$showtime>$img</TD>", $_display);
                                    continue;
                                 } 
                               if ($kk == '#VERDICTSTABLE#')
                                 { $img = _verdictsimage_;
                                    $showtime = " TITLE='Verdicts'"; 
                                    $_display = str_replace ("#VERDICTSTABLE#", "<TD$align$class$edit$showtime>$img</TD>", $_display);
                                    continue;
                                 } 
								if ($kk == '#CASESTATUS#')
                                 { 	$align1 = " align = 'center' ";
									$query_str = "SELECT 1 FROM `verdictstable` WHERE `VERDICT` = 'Pending' AND `UWA_CASEID` = '" . $row['UWA_CASEID'] . "'";
									$sqlnew->_sqlLookup ($query_str);
									$countPending = mysql_num_rows ($sqlnew->sql_result);
									if (($row['ENDEDDATE'] > 0) && ($countPending == 0)) {
										$img = _casestatus2_;
									}
									else {
										$img = _casestatus1_;
									}
									//$img = $query_str;
                                    $showtime = "&nbsp;";
                                    $_display = str_replace ("#CASESTATUS#", "<TD$align1$class$edit$showtime>$img</TD>", $_display);
                                    continue;
                                 } 

                               // check for #TIME# display
                               if (strpos ($_display, "#TIME$kk") !== false)
                                 { if ($value > 0) $showtime = date ("$Tformat", $value); else $showtime = "&nbsp;";
                                    $_display = str_replace ("#TIME$kk", "<TD$align$class$edit>$prep$showtime$app</TD>", $_display);
                                    continue;
                                 }
                               // check for #DATE# display
                               if (strpos ($_display, "#DATE$kk") !== false)
                                 { if ($value > 0) $showtime = date ($Dformat, $value); else $showtime = "&nbsp;";
                                    $_display = str_replace ("#DATE$kk", "<TD$align$class$edit>$prep$showtime$app</TD>", $_display);
                                    continue;
                                 }
                               // check for #DATETIME# display
                               if (strpos ($_display, "#DATETIME$kk") !== false)
                                 { if ($value > 0) $showtime = date ($Dformat, $value) . " " . date ("$Tformat", $value); else $showtime = "***&nbsp;";
                                    $_display = str_replace ("#DATETIME$kk", "<TD$align$class$edit>$prep$showtime$app</TD>", $_display);
                                    continue;
                                 }
                               if (strpos ($_display, "#TABLE$kk") !== false)
                                 { $ndx = strpos ($_display, "#TABLE$kk");
                                    $ndx2 = $ndx + strlen ("#TABLE$kk");
                                    while (substr ($_display, $ndx2, 1) != '#') $ndx2++;
                                    $ndx2++;
                                    $fullfield = substr ($_display, $ndx, $ndx2 - $ndx);
                                    list ($dummy, $fetchID, $chunks) = explode ("#", substr ($fullfield, 1, -1));
                                    list ($ftable, $ffield, $alternative, $ldepend, $rdepend, $format, $trunc) = explode (";", $chunks);
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
                                    if ($trunc) 
                                       { $ovrtitle = ' TITLE="' . str_replace ("<br>", "\n", $fvalue) . '"';
                                          if ($trunc == 'NL') 
                                             { if (strpos ($fvalue, "<br>") !== false) $fvalue = substr ($fvalue, 0, strpos ($fvalue, "<br>"));
                                             } else $fvalue = substr ($fvalue, 0, $trunc);
                                       } else $ovrtitle = "";
                                    $_display = str_replace ($fullfield, "<TD$align$class$edit$ovrtitle>$prep$fvalue$app</TD>", $_display);
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
                                    if ($trunc) 
                                       { $ovrtitle = ' TITLE="' . str_replace ("<br>", "\n", $fvalue) . '"';
                                          if ($trunc == 'NL') 
                                             { if (strpos ($fvalue, "<br>") !== false) $fvalue = substr ($fvalue, 0, strpos ($fvalue, "<br>"));
                                             } else $fvalue = substr ($fvalue, 0, $trunc);
                                       } else $ovrtitle = "";
                                    $_display = str_replace ($fullfield, "<TD$align$class$edit$ovrtitle>$prep$fvalue$app</TD>", $_display);
                                    continue;
                                 }
                               $value = stripslashes ($value);
                               if ($key == 'PASSWORD') for ($icnt=0;$icnt<strlen($value);$icnt++) $value[$icnt] = '*';
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
                               $_display = str_replace ($kk, "<TD$align$class$edit$ovrtitle>$prep$value$app</TD>", $_display);
                            }
               $result .= "<TR>\n";
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
               if ($filteredpermissions & _delete_) 
                 { $dellabel = _addSlashes2 ($row[$deletelabel]);
                    $result .= <<<NXT
<TD$class style="cursor: {$GLOBALS['cursor']}"
onclick="_goDelete ('{$row['id']}', '$dellabel');"><IMG src="images/del2.png" TITLE="{$row['id']}"></TD>
NXT;
                 }

               $result .= $_display;
               $result .= "</TR>\n";
            }
   mysql_free_result ($sql->sql_result);
return $result;
}


/* ---------------------------------------------------------------- */


/* ---------------------------------------------------------------- */

?>

