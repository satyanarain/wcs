<?php 
@session_start();

/* ---------------------------------------------------------------- */

include_once 'std.php';
include 'logincheck.php';

include 'fields.php';		// for the _getField () function

include 'extractsstuff.php';

if ($_POST['role'] == 'GOD') $Dformat = 'd/m-Y'; else $Dformat = _getField ('users', $_POST['id'], 'DATEFORMAT', '');


/* ---------------------------------------------------------------- */

include 'suspects.php';
include 'arrests.php';
include 'cases.php';
include 'evidencetable.php';
include 'offenderstable.php';
include 'defendantstable.php';
include 'verdictstable.php';

/* ---------------------------------------------------------------- */

_makePath ($GLOBALS['docspath'], "_users/{$_POST['id']}");

/* ---------------------------------------------------------------- */

$config = "";
foreach ($extracttables as $extracttable)
            { $tmp = $extracttable;
               $selname = $extracttable . "__" . "LISTSTYLE";
               if ($_POST[$selname]) $tmp .= "\t{$_POST[$selname]}"; else $tmp .= "\t0";
               foreach ($extractfields[$extracttable] as $field)
                            { $name = $extracttable . "__" . $field;
                               if ($_POST[$name]) 
                                  { list ($t, $name) = explode ('__', $name);
                                     $tmp .= "\t$name";
                                  }
                            }
               foreach ($extractsubtables[$extracttable] as $field => $label)
                            { $name = $extracttable . "__" . $field;
                               if ($_POST[$name]) 
                                  { list ($t, $name) = explode ('__', $name);
                                     $tmp .= "\t$name";
                                  }
                            }
                $config .= "$tmp\n";
            }

if ($_POST['update'])
   { $extractsettings = fopen ("$_docspath/extractsettings.csv", "w");
      fwrite ($extractsettings, $config);
      fclose ($extractsettings);
   }

/* ---------------------------------------------------------------- */

if ($_POST['clearsettings']) unlink ("$_docspath/extractsettings.csv");

/* ---------------------------------------------------------------- */

if (file_exists ("$_docspath/extractsettings.csv")) 
   { $settings = file ("$_docspath/extractsettings.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   }

$checked = array ();
$listmodes = array ();

if ($settings)
   { foreach ($settings as $setting)
      { $setfields = explode ("\t", $setting);
         $table = array_shift ($setfields);
         $listmode = array_shift ($setfields);
         $listmodes[$table] = $listmode;
         foreach ($setfields as $field) 
                      { $name = $table . $field;
                         if (in_array ($field, $setfields)) 
                              $checked[$name] = '1'; else $checked[$name] = '';
                      }
      }
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
         $fieldname = "SEARCH_TXT_$table_$fields";
         if (get_magic_quotes_gpc()) $fieldvalue = htmlentities (stripslashes ($_POST[$fieldname]), ENT_QUOTES); else $fieldvalue = htmlentities ($_POST[$fieldname], ENT_QUOTES);
         $form .= <<<NXT
&nbsp;&nbsp;$help

NXT;
      } else $help = "";

return $form;
}

/* ---------------------------------------------------------------- */

$listall = '';
$listalltmp = '';
$globalform = '';
$load = "";
foreach ($extracttables as $extracttable)
            { $i = 0;
               

               $subs = '';
               foreach ($extractsubtables[$extracttable] as $subtable => $label)
                            { $subname = $extracttable . "__" . $subtable;
                               $chkctrl = '';
                               if ($subname == "cases__VERDICTSTABLE")
                                  { $chkctrl = <<<NXT
if (checked) 
  { document.settings.cases__DEFENDANTSTABLE.value = 1; 
     document.settings.cases__DEFENDANTSTABLE.checked = true;
  } else document.settings.cases__COURT_NAME.value = '';
NXT;
                                  } 
                               if ($subname == "cases__DEFENDANTSTABLE")
                                  { $chkctrl = <<<NXT
if (checked == false) 
  { document.settings.cases__VERDICTSTABLE.value = 0; 
     document.settings.cases__VERDICTSTABLE.checked = false;
     document.settings.cases__COURT_NAME.value = '';
  }
NXT;
                                  }

                               $chksubname = $extracttable . $subtable;
                               if ($checked[$chksubname]) 
                                  { $value = 1; 
                                     $chk = 'checked'; 
                                  } else { $value = 0;
                                                $chk = '';
                                             }
                               $subs .= <<<NXT
<INPUT type="CHECKBOX" name="$subname" value="$value" $chk 
onclick="
if (checked) value = 1; else value = 0;
$chkctrl
">{$GLOBALS['tablelabels'][$extracttable][$subtable]}&nbsp;&nbsp;&nbsp;

NXT;
                            }

               foreach ($extractfields[$extracttable] as $field)
                            { $name = $extracttable . "__" . $field;
                               $checkname = $extracttable . $field;
                               if ($checked[$checkname]) 
                                  { $value = 1; 
                                     $chk = 'checked'; 
                                  } else { $value = 0;
                                                $chk = '';
                                             }
                               $i++;
                               if ($i > 7)
                                  { $i = 0;
                                     $listalltmp .= "\n<BR>\n";
                                  }
                               $listalltmp .= <<<NXT
<INPUT type="CHECKBOX" name="$name" value="$value" $chk 
onclick="
if (checked) value = 1; else value = 0;
">{$GLOBALS['tablelabels'][$extracttable][$field]}&nbsp;&nbsp;&nbsp;

NXT;
                               $globalform .= <<<NXT
NXT;
                            }
               if ($subs) $subs = "<B>• Include subtable(s):</B> &nbsp;&nbsp;$subs";
               $selname = $extracttable . "__" . "LISTSTYLE";
               if ($extracttable == 'cases') $load .= <<<NXT
document.settings.$selname.value={$listmodes[$extracttable]};

NXT;
               $searchinfo = _searchForm ($extracttable);
               $listall .= <<<NXT
<DIV>
<INPUT type="BUTTON" class="btn" value="Extract data" onclick="document.settings.extractfrom.value = '$extracttable'; document.settings.submit();">
&nbsp;&nbsp;
<B style="font-size: 12pt;">{$tablenames[$extracttable]}</B>&nbsp;&nbsp;
NXT;
               if ($extracttable == 'cases') $listall .= <<<NXT
Extract:&nbsp;
<SELECT name="$selname" class="btn">
<OPTION value="0">All cases
<OPTION value="1">Finalized cases
<OPTION value="2">Ongoing cases
</SELECT>
&nbsp;&nbsp;
NXT;
               $searchfieldname = 'SEARCH_TXT_' . $extracttable . '__' . 'search';
               if ($_POST[$searchfieldname]) $searchfieldvalue = "{$_POST[$searchfieldname]}"; else $searchfieldvalue = "";
               if ($extracttable == 'cases') 
                  { $courtsel = _makeSelectFromTable ('courts', 'COURT_NAME', 'COURT_NAME', 'cases__COURT_NAME');
                     $courtseljava .= <<<NXT
name="cases__COURT_NAME" class="btn"
onchange = "if (value) 
{ document.settings.cases__DEFENDANTSTABLE.value = 1; 
   document.settings.cases__DEFENDANTSTABLE.checked = true;
   document.settings.cases__VERDICTSTABLE.value = 1; 
   document.settings.cases__VERDICTSTABLE.checked = true;
}"
NXT;
                     $courtsel = '&nbsp;&nbsp;&nbsp;Court (verdicts): ' . str_replace ('name="cases__COURT_NAME" class="btn"', $courtseljava, $courtsel);
                  } else $courtsel = '';
               $listall .= <<<NXT
$subs
</DIV>
<DIV style="padding: 8 0 0 0;">
<B>&nbsp;Fields:</B>
<BR>
$listalltmp
<P>
Search filter (keyword): <INPUT type="TEXT" class="btn" name="$searchfieldname" value="$searchfieldvalue">$searchinfo$courtsel
</DIV>
<HR>
<P>

NXT;
               $listalltmp = '';
            }

/* ---------------------------------------------------------------- */

if (isset ($_POST['separator'])) $load .= <<<NXT
document.settings.separator.value='{$_POST['separator']}';

NXT;

if (isset ($_POST['cases__COURT_NAME'])) $load .= <<<NXT
document.settings.cases__COURT_NAME.value='{$_POST['cases__COURT_NAME']}';

NXT;

/* ---------------------------------------------------------------- */

$downsrc = "";		// set by following include...
if ($_POST['extractfrom']) include "extract.{$_POST['extractfrom']}.php";

/* ---------------------------------------------------------------- */

$now = getdate ($GLOBALS['timestamp']);

if ($_POST['fromday']) $fday = $_POST['fromday']; else $fday = $now['mday'];
if ($_POST['frommonth']) $fmonth = $_POST['frommonth']; else $fmonth = $now['mon'];
if ($_POST['fromyear']) $fyear = $_POST['fromyear']; else $fyear = $now['year'];

if ($_POST['today']) $tday = $_POST['today']; else $tday = $now['mday'];
if ($_POST['tomonth']) $tmonth = $_POST['tomonth']; else $tmonth = $now['mon'];
if ($_POST['toyear']) $tyear = $_POST['toyear']; else $tyear = $now['year'];

/* ---------------------------------------------------------------- */

if ($_POST['XLATE_NATIONALITY']) $XLATE_NATIONALITY = "value=\"1\" checked"; else $XLATE_NATIONALITY = "value=\"0\"";
if ($_POST['XLATE_NAMES']) $XLATE_NAMES = "value=\"1\" checked"; else $XLATE_NAMES = "value=\"0\"";
if ($_POST['XLATE_DATES']) $XLATE_DATES = "value=\"1\" checked"; else $XLATE_DATES = "value=\"0\"";

if (! isset ($_POST['XLATE_NATIONALITY'])) $XLATE_NATIONALITY = "value=\"1\" checked";
if (! isset ($_POST['XLATE_NAMES'])) $XLATE_NAMES = "value=\"1\" checked";
if (! isset ($_POST['XLATE_DATES'])) $XLATE_DATES = "value=\"1\" checked";

/* ---------------------------------------------------------------- */

include 'style.php';

$page .= <<<NXT
$style

<BODY style="background: #F8F6F2; font-family: Trebuchet MS, Arial;" 
onLoad="$load;">

<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">

<FORM name="settings" method="POST" action="ExtractsAdministration.php">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="extractfrom" value="{$_POST['extractfrom']}">
<INPUT type="HIDDEN" name="clearsettings" value="">
<INPUT type="HIDDEN" name="update" value="">

<INPUT type="HIDDEN" name="fromday" value="">
<INPUT type="HIDDEN" name="frommonth" value="">
<INPUT type="HIDDEN" name="fromyear" value="">
<INPUT type="HIDDEN" name="today" value="">
<INPUT type="HIDDEN" name="tomonth" value="">
<INPUT type="HIDDEN" name="toyear" value="">

<!--  calendars                                                             -->

<TR>
<TD width="70" align="center" Valign="top">
&nbsp;
</TD>
<TD align="left" Valign="top" style="padding: 6 10 0 0;">
<HR>
<DIV style="background: #C8C8C8;">
<B style="font-size: 12pt;">Table extracts - Global settings</B>
</DIV>
<HR>
</TD>
</TR>

<TR>
<TD width="70" align="center" Valign="top">
&nbsp;
</TD>

<TD Valign="top">

<TABLE ID="datestable" width="100%" cellspacing="0" cellpadding="0" border="0">
<TR>
<TD width="220" height="220" align="center" Valign="top">
<Iframe name="fromdateframe" ID="fromdateframe" width="220" height="220" frameborder="0" SRC="calendar3.php?pday=fromday&pmon=frommonth&pyear=fromyear&y=$fyear&m=$fmonth&d=$fday" allowTransparency="true"></Iframe>
</TD>

<TD width="70" align="center" Valign="top">
&nbsp;<BR>
<B>
Select date-span for extract:
<DIV align="left">
&lt;- start date
</DIV>
<DIV align="right">
end date -&gt;
</DIV>
</B>
</TD>

<TD width="220" height="220" align="center" Valign="top">
<Iframe name="todateframe" ID="todateframe" width="220" height="220" frameborder="0" SRC="calendar3.php?pday=today&pmon=tomonth&pyear=toyear&y=$tyear&m=$tmonth&d=$tday" allowTransparency="true">allowTransparency="true"></Iframe>
</TD>

<TD align="left" Valign="top" style="padding: 16 10 0 8;">

<INPUT type="CHECKBOX" name="XLATE_NATIONALITY" $XLATE_NATIONALITY
onclick="
if (checked) value = 1; else value = 0;
">&nbsp;<B>Translate nationality</B> (from ICC2 format to Country name) - applies to Suspects table data.

<P>
<INPUT type="CHECKBOX" name="XLATE_NAMES" $XLATE_NAMES 
onclick="
if (checked) value = 1; else value = 0;
">&nbsp;<B>Expand names</B> (from table indexes) - applies to Suspects (Parish), Arrests/Offenders (Offender) and Court cases/Defendants (Accuseds - Name) table data.

<P>
<INPUT type="CHECKBOX" name="XLATE_DATES" $XLATE_DATES 
onclick="
if (checked) value = 1; else value = 0;
">&nbsp;<B>Translate dates</B> (from linux date stamps to humanly readable format) - applies to Arrests (Arrest date), Court cases (Starting dates) and Verdicts (Court dates) table data.

</TD>
</TR>
</TABLE>

</TD>
</TR>
<!--  /calendars                                                             -->


<TR>
<TD width="70" align="center" Valign="top">
&nbsp;
</TD>
<TD align="left" Valign="top" style="padding: 6 10 0 0;">
<DIV>
<B>CSV delimiter</B>
<SELECT name="separator" class="btn">
<OPTION value="<,>">&lt;,&gt;
<OPTION value="">&lt;Tab&gt;
<OPTION value="<;>">&lt;;&gt;
</SELECT>
<BR>
<B>Note: </B>Date span (applying to Arrests and Court cases extracts!) will be ignored if both dates are identical, or if start date is set to be later than end date. I.e.: Leave unchanged to ignore date filtering.

</DIV>
<HR>
</TD>
</TR>

<TR>
<TD width="70" align="center" Valign="top">
&nbsp;
</TD>


<TD align="left" Valign="top" style="padding: 6 10 0 0;">
<HR>

<DIV style="background: #C8C8C8;">
<B style="font-size: 12pt;">Table data extracts</B>&nbsp;&nbsp;

</DIV>
<HR>

$listall

<INPUT type="SUBMIT" class="btn" value="Save settings" onclick="document.settings.update.value = 1; document.settings.extractfrom.value = '';">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="BUTTON" class="btn" value="Clear all" onclick="document.settings.clearsettings.value = 1; document.settings.extractfrom.value = ''; document.settings.submit();">
<P>
<B>Note:</B> The save settings function saves the states of field- &amp; subtable selection checkboxes for future accessing of this portal. Global settings (dates, translates, CSV delimiter along with court selection in the Court cases section) are preserved within current access session and reset to defaults whenever this portal is accessed from the menu.
</TD>
</TR>


<TR>
<TD width="70" align="center" Valign="top">
&nbsp;
</TD>
<TD align="left" Valign="top" style="padding: 6 10 0 0;">
<HR>
<DIV style="background: #C8C8C8;">
<B style="font-size: 12pt;">&nbsp;</B>
</DIV>
<HR>
</TD>
</TR>


</FORM>
</TABLE>

<Iframe name="downframe" ID="downframe" width="0" height="0" frameborder="0" SRC="$downsrc" allowTransparency="true"></Iframe>

</BODY>
</HTML>

NXT;

echo $page;

?>

