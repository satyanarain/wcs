<?php 
@session_start();

/* ---------------------------------------------------------------- */

function _rawDate ($timestamp)
{ if ($timestamp > 0)
     { $timestamparr = getdate ($timestamp);
        return "{$timestamparr['mday']}/{$timestamparr['mon']}-{$timestamparr['year']} {$timestamparr['hours']}:{$timestamparr['minutes']}";
     } else return "";
}


/* ---------------------------------------------------------------- */


function _getRawRecord ($table, $record)
{ $result = "";

   $sql2 = new mySQLclass();
   $query = "SELECT * FROM $table WHERE id = '$record'";
   $sql2->_sqlLookup ($query);
   $count = mysql_num_rows ($sql2->sql_result);
   if ($sql2->sql_result === false) $sql2->_sqlerror ();

   if ($row = mysql_fetch_assoc ($sql2->sql_result))
            { $r = 0;
               foreach ($row as $key => &$value)
                            { $fieldlen = mysql_field_len ($sql2->sql_result, $r);
                               $fieldtype = mysql_field_type ($sql2->sql_result, $r);
                               if (isset ($GLOBALS['prepends'][$table][$key])) $prep = $GLOBALS['prepends'][$table][$key]; else $prep = '';
                               if (isset ($GLOBALS['apppends'][$table][$key])) $app = $GLOBALS['apppends'][$table][$key]; else $app = '';
                               $r++;
                               if ( ($key == 'NOTES') || ($key == 'DOCS') || ($key == 'PROFILE') || ($key == 'SUBTABLE') || ($key == 'EVIDENCETABLE') || ($key == 'OFFENDERSTABLE') || ($key == 'DEFENDANTSTABLE') || ($key == 'SENTENCETABLE') || ($key == 'VERDICTSTABLE') || ($key == 'id') || ($key == 'DELETED') || ($key == 'CHANGED') || ($key == 'CHANGEDBY') || ($key == 'ASSTABLE') || ($key == 'ASSREC') ) continue;
                               if ( (strpos ($key, "DATE") !== false) || (strpos ($key, "REGISTERED") !== false) ) $value = _rawDate ($value);

                         
                               $value = stripslashes ($value);

                               $result .=  "[$key] {$GLOBALS['tablelabels'][$table][$key]} = $prep$value$app\r\n<br>";
                            }
               mysql_free_result ($sql2->sql_result);
            } 
return $result;
}

?>

