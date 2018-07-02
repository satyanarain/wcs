<?php 
@session_start();

/* ---------------------------------------------------------------- */

if ($_POST['separator'] == "<;>") $delimiter = ";";
if ($_POST['separator'] == "<,>") $delimiter = ",";
if ($_POST['separator'] == "") $delimiter = "\t";

function _checkCSVformatting ($delimiter, &$value)
{ if (strpos($value, $delimiter) !== false)
      { $value = str_replace ( '"' , "'" , $value);
         $value = stripslashes ($value);
         $value = "\"$value\"";
      }
}

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
                     { //$fields = substr ($key, 12);
                        list ($flag, $fields) = explode ('__', $key);
                        foreach ($GLOBALS['searchfields'][$table] as $txtfield => $type)
                                     { if ($type == '%') array_push ($txt, "$txtfield LIKE '%$value%'");
                                     }
                     }
               }

   if (count ($txt) > 0) 
     { $search = implode (" OR ", $txt);
        $search = "($search)";
     }

   if (strlen ($search) > 0) $search = "$search AND ";
return $search;
}

/* ---------------------------------------------------------------- */

$table = $_POST['extractfrom'];

/* ---------------------------------------------------------------- */

$sql = new mySQLclass();
$searchstring = _searchString ($table);

/* ---------------------------------------------------------------- */
/* ---------------------------------------------------------------- */

$query = "SELECT * FROM $table WHERE $searchstring DELETED = 0";
$sql->_sqlLookup ($query);
$total = mysql_num_rows ($sql->sql_result); 
if ($sql->sql_result === false) $sql->_sqlerror ();

/*
$load .= <<<NXT
alert ('$total' + ' records found');

NXT;
*/

/* ---------------------------------------------------------------- */

$noOfFields = 0;

/* ---------------------------------------------------------------- */

$tmp = 'rec';
$noOfFields++;
foreach ($extractfields [$table] as $field)
             { $extname = $table .  '__' . $field;
                if ($_POST[$extname])
                $tmp .= "\t{$GLOBALS['tablelabels'][$table][$field]}";
             }
$tmp .= "\n";
while ($row = mysql_fetch_assoc ($sql->sql_result))
         { $tmp .= "{$row['id']}";
            foreach ($extractfields [$table] as $field)
                         { $noOfFields++;
                            $extname = $table .  '__' . $field;
                            if ($_POST[$extname]) 
                               { $tmp2 = $row[$field];

                                  if (array_key_exists ($field, $extractformats [$table])) $tmp2 = _xlateField ($tmp2, $table, $field);

                                  _checkCSVformatting ($delimiter, $tmp2);
                                  $tmp .= "\t$tmp2";
                               }
                         }
            $tmp .= "\n";
         }

/* ---------------------------------------------------------------- */

mysql_free_result ($sql->sql_result);

/* ---------------------------------------------------------------- */

if ($_POST['separator'] == "<;>") $tmp = str_replace ("	", ";", $tmp);
if ($_POST['separator'] == "<,>") $tmp = str_replace ("	", ",", $tmp);
$tmp = str_replace ("\r", "", $tmp);
$tmp = str_replace ("\n", "\r\n", $tmp);

$of = fopen ("$_docspath/extract.$table.csv", "w");
fwrite ($of, $tmp);
fclose ($of);

$downsrc = "getfile3.php?file=$_docspath/extract.$table.csv";

/* ---------------------------------------------------------------- */




?>

