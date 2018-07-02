<?php 
@session_start();

/* ---------------------------------------------------------------- */

function _reportDeleteSend ($message, $to, $table)
{ $subject = "Record deleted from $table";
   $headers  = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
   $headers .= 'From: jan.kirstein@jayksoft.com' . "\r\n" .
    'Reply-To: jan.kirstein@jayksoft.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
   if (! (getenv('SERVER_ADDR') == "127.0.0.1")) mail ($to, $subject, $message, $headers);
}

function _reportDelete ($table, $record, $user)
{ include 'getrecord.php';
  $rip = getenv ('REMOTE_ADDR');

$msg = _getRawRecord ($table, $record);
$timestamparr = getdate ($GLOBALS['timestamp']);

$message = <<<NXT
Table: $table
Record ID: $record
<BR>
Deleted by: $user  On {$timestamparr['mday']}/{$timestamparr['mon']}-{$timestamparr['year']}
<BR>
From: {$GLOBALS['__portal']}
<BR>
Record content:
<BR>
$msg

NXT;

   $alerts = fopen ("{$GLOBALS['confpath']}/deleted.txt", "a");
   fwrite ($alerts, "$message --------------------------------------------\n\n");
   fclose ($alerts);

$recps = file ("{$GLOBALS['confpath']}/delrep.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($recps as $recipient)
           { _reportDeleteSend ($message, $recipient, $table);
           }
}

/* ---------------------------------------------------------------- */

function _addSlashes ()
{ foreach ($_POST as $key => &$value)
                { if ((substr ($key, 0, 4) == 'NEW_') && (! get_magic_quotes_gpc()))
                     { $value = str_replace ( "'" , "\\'" , $value);
                        $value = str_replace ( "\"" , "\\\"" , $value);
                     }
                   $value = nl2br ( $value, false);
                }
}

/* ---------------------------------------------------------------- */

function _UnHTMLize_addSlashes ()
{ foreach ($_POST as $key => &$value)
                { if (substr ($key, 0, 4) == 'NEW_') 
                     { $value = html_entity_decode ($value, ENT_QUOTES);
                        if (! get_magic_quotes_gpc())
                           { $value = str_replace ( "'" , "\\'" , $value);
                              $value = str_replace ( "\"" , "\\\"" , $value);
                           }
                     }
                }
}

/* ---------------------------------------------------------------- */

function _addSlashes2 ($value)
{ $res = $value;
   $res = str_replace ( "'" , "\\'" , $res);
   $res = str_replace ( "\"" , "\\\"" , $res);
return $res;
}

/* ---------------------------------------------------------------- */

function _setField ($table, $field, $id, $value, $user)
{ $sql = new mySQLclass();
   $query = "UPDATE $table SET $field = '$value' WHERE id = '$id'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result !== false) 
      { $query = "UPDATE tables SET LAST_ACTIVITY = '{$GLOBALS['timestamp']}', USER = '$user' WHERE TABLENAME = '$table'";
         $sql->_sqlLookup ($query);
         $query = "UPDATE $table SET CHANGED = '{$GLOBALS['timestamp']}', CHANGEDBY = '$user' WHERE id = '$id'";
         $sql->_sqlLookup ($query);

/* spec. case... */
   if (($table == 'suspects') && ($field == 'WANTED') && ($value != ''))
      { $query = "UPDATE $table SET WANTED_SINCE = '{$GLOBALS['timestamp']}' WHERE id = '$id'";
         $sql->_sqlLookup ($query);
         if ($sql->sql_result === false) $sql->_sqlerror ();
      }
   if (($table == 'suspects') && ($field == 'WANTED') && ($value == ''))
      { $query = "UPDATE $table SET WANTED_SINCE = '' WHERE id = '$id'";
         $sql->_sqlLookup ($query);
         if ($sql->sql_result === false) $sql->_sqlerror ();
      }
/* /spec. case... */

   if (($field == 'DELETED') && ($value != 0)) _reportDelete ($table, $id, $user);

         return true;
      } else { $sql->_sqlerror ();
                    return false;
                 }
}

/* ---------------------------------------------------------------- */

function _checkFields ($table, $rec)
{ $result = true;
   $sql = new mySQLclass();
   $query = "SELECT * FROM $table WHERE id = '$rec' LIMIT 0,1";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) 
      { $sql->_sqlerror ();
         return false;
      }
   $row = mysql_fetch_assoc ($sql->sql_result);
   foreach ($GLOBALS['requiredfields'][$table] as $field) 
               { if (($GLOBALS['tablehandlings'][$table][$field] == 'EDIT') && ($row[$field] == '')) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; }
                  if ($field == 'SUBTABLE') { if (! $row[$field]) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; } }
                  if ($field == 'GISTABLE') { if (! $row[$field]) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; } }
                  if ($field == 'EVIDENCETABLE') { if (! $row[$field]) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; } }
                  if ($field == 'OFFENDERSTABLE') { if (! $row[$field]) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; } }
                  if ($field == 'DEFENDANTSTABLE') { if (! $row[$field]) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; } }
                  if ($field == 'SENTENCETABLE') { if (! $row[$field]) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; } }
                  if ((substr ($GLOBALS['tablehandlings'][$table][$field], 0, 7) == '_select') && ($row[$field] == '')) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; }
                  if ((substr ($GLOBALS['tablehandlings'][$table][$field], 0, 5) == '_text') && ($row[$field] == '')) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => empty"; $result = false; }
                  if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickDate')) == '_pickDate') { if ($row[$field] == 0) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickDistrict')) == '_pickDistrict') { if ($row[$field] == '') { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickCounty')) == '_pickCounty') { if ($row[$field] == '') { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickSubCounty')) == '_pickSubCounty') { if ($row[$field] == '') { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickParish')) == '_pickParish') { if ($row[$field] == '') { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickRole')) == '_pickRole') { if ($row[$field] == '') { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_pickRecordId')) == '_pickRecordId') { if ((! is_numeric ($row[$field])) || ($row[$field] == 0)) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => not set"; $result = false; } }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_int')) == '_int') 
                          { $temp = $GLOBALS['tablehandlings'][$table][$field];
                             $temp = substr ($temp, strpos ($temp, "(")+1, -2);
                             $temp = str_replace ('""', '', $temp);
                             $temp = explode (',', $temp);
                             if ( (intval ($row[$field]) < $temp[0]) || (($temp1) && (intval ($row[$field]) > $temp[1])) ) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => out of bounds; allowed range: $temp[0] - $temp[1]";  $result = false; }
                          }
                       if (substr ($GLOBALS['tablehandlings'][$table][$field], 0, strlen ('_float')) == '_float') 
                          { $temp = $GLOBALS['tablehandlings'][$table][$field];
                             $temp = substr ($temp, strpos ($temp, "(")+1, -2);
                             $temp = explode (',', $temp);
                             if ( (floatval ($row[$field]) < $temp[2]) || (floatval ($row[$field]) > $temp[3]) ) { $GLOBALS['ERRORS'][$field] = "[{$GLOBALS['tablelabels'][$table][$field]}] => out of bounds; allowed range: $temp[2] - $temp[3]";  $result = false; }
                          }
               }
return $result;
}

/* ---------------------------------------------------------------- */

function _checkFieldsNewRec ($table)
{ $result = true;
   foreach ($GLOBALS['newrecfields'][$table] as $field => $len)
               { if ((! isset ($_POST["NEW_$field"])) || ($_POST["NEW_$field"] == '')) $result = false;
               }
return $result;
}

/* ---------------------------------------------------------------- */

function _getField ($table, $id, $fieldname, $dependency)
{ $sql = new mySQLclass();
   $query = "SELECT * FROM $table WHERE id = '$id'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror ();
   if ($row = mysql_fetch_assoc ($sql->sql_result))
      { if ($dependency)
           { if ($row[$dependency]) return $row[$fieldname];
                 else return false;
           } 
         return stripslashes ($row[$fieldname]);        
      } else return '';
}

/* ---------------------------------------------------------------- */

function _getField2 ($table, $reffield, $value, $fieldname, $dependency)
{ $sql = new mySQLclass();
   $query = "SELECT * FROM $table WHERE $reffield = '$value'";
   $sql->_sqlLookup ($query);
   if ($sql->sql_result === false) $sql->_sqlerror ();
   if ($row = mysql_fetch_assoc ($sql->sql_result))
      { if ($dependency)
           { if ($row[$dependency]) return $row[$fieldname];
                 else return false;
           } 
         return stripslashes ($row[$fieldname]);
      } else return '';
}

/* ---------------------------------------------------------------- */

?>

