<?php 
ob_start();
@session_start();

//header('Location: case_csv.php');
//echo "<script>window.location.href='case_csv.php'</script>";

?>
<!--<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script>
 $(document).ready(function() 
 {
     alert("rtretr");
//     
  var data1 = "test";
   $.ajax({
       url:'case_csv.php',
        data:data1,
       success: function(response)
    {
        alert("ere");
  
     //   $("#associateid").show();
      //$("#associateid").html(response);
    }
     
 });
 });
 </script>-->
<?php

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

$fromday = $_POST['fromday'];
$frommonth = $_POST['frommonth'];
$fromyear = $_POST['fromyear'];
$today = $_POST['today'];
$tomonth = $_POST['tomonth'];
$toyear = $_POST['toyear'];

$fromdate = mktime (0, 0, 0, $frommonth, $fromday, $fromyear);
$_todate = mktime (0, 0, 0, $tomonth, $today, $toyear);
$todate = mktime (23, 59, 59, $tomonth, $today, $toyear);

/* ---------------------------------------------------------------- */

$sql = new mySQLclass();
$sql2 = new mySQLclass();
$sql3 = new mySQLclass();
$searchstring = _searchString ($table);

/* ---------------------------------------------------------------- */

$liststyle = $_POST['cases__LISTSTYLE'];

if ($fromdate >= $_todate) 
   { if ($liststyle == 0) $additionalFilter = "";
      if ($liststyle == 1) $additionalFilter = "(ENDEDDATE > 0)";
      if ($liststyle == 2) $additionalFilter = "(ENDEDDATE = 0)";
   }
   else { if ($liststyle == 0) $additionalFilter = "(COURTDATE >= $fromdate) AND (COURTDATE <= $todate)";
             if ($liststyle == 1) $additionalFilter = "(COURTDATE >= $fromdate) AND (ENDEDDATE <= $todate) AND (ENDEDDATE > 0)";
             if ($liststyle == 2) $additionalFilter = "(COURTDATE >= $fromdate) AND (COURTDATE <= $todate) AND (ENDEDDATE = 0)";
           }

if ($additionalFilter) 
   { if ($searchstring) $searchstring .= " $additionalFilter AND";
         else $searchstring = "$additionalFilter AND";
   }

/* ---------case table------------------------------------------------------- */

$query = "SELECT * FROM $table WHERE $searchstring DELETED = 0";

$sql->_sqlLookup ($query);
$total = mysql_num_rows ($sql->sql_result); 
if ($sql->sql_result === false) $sql->_sqlerror ();


$cases__UWA_CASEID=$_POST['cases__UWA_CASEID'];
$cases__POLICE_CASEID=$_POST['cases__POLICE_CASEID'];
$cases__CRIME_TYPE=$_POST['cases__CRIME_TYPE'];
$cases__COURTDATE=$_POST['cases__COURTDATE'];
$cases__ENDEDDATE=$_POST['cases__ENDEDDATE'];
$cases__UWA_PROSECUTOR=$_POST['cases__UWA_PROSECUTOR'];
$cases__STATE_PROSECUTOR=$_POST['cases__STATE_PROSECUTOR'];
$cases__DEFENCE_LAWYER=$_POST['cases__DEFENCE_LAWYER'];
$cases__MAGISTRATE=$_POST['cases__MAGISTRATE'];

/*
$load .= <<<NXT
alert ('$total' + ' records found');

NXT;
*/

/* ---------------------------------------------------------------- */

$noOfFields = 0;

/* ---------------------------------------------------------------- */

$defendantsfields = array ('DEFENDANT', 'CRIME');

$verdictsfields = array ('UWA_CASEID', 'COURT_DATE', 'COURT_NAME', 'VERDICT', 'SPEC', 'QTY', 'UNIT', 'APPEALED');

/* ---------------------------------------------------------------- */

$tmp = 'rec';
$noOfFields++;
foreach ($extractfields [$table] as $field)
             { $extname = $table .  '__' . $field;
                if ($_POST[$extname])
                   { $noOfFields++;
                      $tmp .= "\t{$GLOBALS['tablelabels'][$table][$field]}";
                   }
             }
//$tmp .= "\n";
/****************UWA Case ID  ****COURT case no.*****cases__POLICE_CASEID**cases__UWA_CASEID****cases__CRIME_TYPE*******************************************************************88
/* subtable labels */

$cases__UWA_CASEID=$_POST['cases__UWA_CASEID'];
$cases__POLICE_CASEID=$_POST['cases__POLICE_CASEID'];
$cases__CRIME_TYPE=$_POST['cases__CRIME_TYPE'];
$cases__COURTDATE=$_POST['cases__COURTDATE'];
$cases__ENDEDDATE=$_POST['cases__ENDEDDATE'];
$cases__UWA_PROSECUTOR=$_POST['cases__UWA_PROSECUTOR'];
$cases__STATE_PROSECUTOR=$_POST['cases__STATE_PROSECUTOR'];
$cases__DEFENCE_LAWYER=$_POST['cases__DEFENCE_LAWYER'];
$cases__MAGISTRATE=$_POST['cases__MAGISTRATE'];

$cases__UWA_CASEID=$_POST['cases__UWA_CASEID'];


$column.=" DISTINCT cases.id as rec, ";
if($_POST['cases__UWA_CASEID']!='')
{
 $column.="cases.UWA_CASEID as 'UWA Case ID', ";
 //$column.="cases.UWA_CASEID as UWA case ID, ";
}

$cases__POLICE_CASEID=$_POST['cases__POLICE_CASEID'];
if($cases__POLICE_CASEID)
{ 
    //$column.=" cases.POLICE_CASEID as COURT case no, " ; 
    $column.=" cases.POLICE_CASEID as 'COURT case no', " ; 
 }

$cases__CRIME_TYPE=$_POST['cases__CRIME_TYPE'];
if($cases__CRIME_TYPE)
{ 
    $column.=" cases.CRIME_TYPE as 'Crime type', " ; 
 }

$cases__COURTDATE=$_POST['cases__COURTDATE'];

if($cases__COURTDATE)
{ 

       $column.="IF(cases.COURTDATE='0','0',FROM_UNIXTIME(cases.COURTDATE)) as 'Start date', ";
 }

$cases__ENDEDDATE=$_POST['cases__ENDEDDATE'];
if($cases__ENDEDDATE)
{ 
    $column.="IF(cases.ENDEDDATE='0','',FROM_UNIXTIME(cases.ENDEDDATE)) as Finalized, ";
}
$cases__UWA_PROSECUTOR=$_POST['cases__UWA_PROSECUTOR'];
if($cases__UWA_PROSECUTOR)
{ 
    $column.=" cases.UWA_PROSECUTOR as 'UWA prosecutor', " ; 
 }
$cases__STATE_PROSECUTOR=$_POST['cases__STATE_PROSECUTOR'];
if($cases__STATE_PROSECUTOR)
{ 
    $column.=" cases.STATE_PROSECUTOR as 'State prosecutor', " ; 
 }
$cases__DEFENCE_LAWYER=$_POST['cases__DEFENCE_LAWYER'];
if($cases__DEFENCE_LAWYER)
{ 
    $column.=" cases.DEFENCE_LAWYER as 'Defence lawyer', " ; 
 }
$cases__MAGISTRATE=$_POST['cases__MAGISTRATE'];

if($cases__MAGISTRATE)
{ 
    $column.=" cases.MAGISTRATE 'Judicial Officer', " ; 
 }
//$column.="cases.DELETED, ";
//$column.="cases.id, ";
if($_POST['cases__DEFENDANTSTABLE']!='' && empty($_POST['cases__VERDICTSTABLE']))
   {
   
   $string = "left join defendantstable on cases.id = defendantstable.ASSREC and defendantstable.DELETED = 0";
    echo  $query = "select " .$column." cases.UWA_PROSECUTOR as 'Name',defendantstable.CRIME as 'Crime' FROM cases "."$string"." where cases.DELETED=0 limit 0,10";
   
   }
 else if($_POST['cases__VERDICTSTABLE']!='' && empty($_POST['cases__DEFENDANTSTABLE']))
   {
  
    $string = "left join verdictstable on cases.id=verdictstable.ASSREC and verdictstable.DELETED=0";
    $query = "select " .$column." verdictstable.UWA_CASEID as 'UWA case ID', IF(verdictstable.COURT_DATE='0','',FROM_UNIXTIME(verdictstable.COURT_DATE)) as 'Court date',verdictstable.COURT_NAME as 'Court',verdictstable.VERDICT as 'Verdict',verdictstable.SPEC as 'Specify',verdictstable.QTY as 'qty',verdictstable.UNIT as 'Units',verdictstable.APPEALED as 'Appealed'  FROM cases "."$string"." where cases.DELETED=0";
   }

  else if($_POST['cases__DEFENDANTSTABLE']!='' && $_POST['cases__VERDICTSTABLE']!='')
   {
   $string = "left join defendantstable on cases.id = defendantstable.ASSREC and defendantstable.DELETED = 0 left join verdictstable on defendantstable.id=verdictstable.ASSREC and verdictstable.DELETED=0";
    $query = "select " .$column." cases.UWA_PROSECUTOR as 'Name',defendantstable.CRIME as 'Crime',verdictstable.UWA_CASEID as 'UWA case ID',IF(verdictstable.COURT_DATE='0','',FROM_UNIXTIME(verdictstable.COURT_DATE)) as 'Court date',verdictstable.COURT_NAME as 'Court',verdictstable.VERDICT as 'Verdict',verdictstable.SPEC as 'Specify',verdictstable.QTY as 'qty',verdictstable.UNIT as 'Units',verdictstable.APPEALED as 'Appealed' FROM cases "."$string"." where cases.DELETED=0";
   }
 
  else 
   {
    $string = "left join defendantstable on cases.id = defendantstable.ASSREC and defendantstable.DELETED = 0 left join verdictstable on defendantstable.id=verdictstable.ASSREC and verdictstable.DELETED=0";
    $query = "select " .$column." cases.UWA_PROSECUTOR as 'Name',defendantstable.CRIME as 'Crime',verdictstable.UWA_CASEID as 'UWA case ID', IF(verdictstable.COURT_DATE='0','',FROM_UNIXTIME(verdictstable.COURT_DATE)) as 'Court date',verdictstable.COURT_NAME as 'Court',verdictstable.VERDICT as 'Verdict',verdictstable.SPEC as 'Specify',verdictstable.QTY as 'qty',verdictstable.UNIT as 'Units',verdictstable.APPEALED as 'Appealed' FROM cases "."$string"." where cases.DELETED=0";
   // $query = "select " .$column." defendantstable.ASSREC,verdictstable.ASSREC,defendantstable.CRIME,verdictstable.COURT_DATE,verdictstable.COURT_NAME,verdictstable.VERDICT,verdictstable.SPEC,verdictstable.QTY FROM cases "."$string"." where cases.DELETED=0 limit 0,10";
   }
 $result = mysql_query($query) or die (mysql_error());
$num_column = mysql_num_fields($result);       
$csv_header = '';
for($i=0;$i<$num_column;$i++) {
    $csv_header .= '"' . mysql_field_name($result,$i) . '",';
}   
$csv_header .= "\n";

$csv_row ='';
while($row = mysql_fetch_row($result)) {
    for($i=0;$i<$num_column;$i++) {
            if($row[$i]=='1/1/1970')
            {
        $csv_row .= '"' .NULL.'",';
            }
            else {
               
                $csv_row .= '"' . $row[$i] . '",';
               
            }   
    }
    $csv_row .= "\n";
}

$data= $csv_header . $csv_row;

   
//$tmp .= "\n";

//while ($row = mysql_fetch_assoc ($sql->sql_result))
//         { if ($_POST['cases__COURT_NAME']) $addtotmp = false; else $addtotmp = true;
//            $tmpR = "{$row['id']}";
//
//            foreach ($extractfields [$table] as $field)
//                         { $extname = $table .  '__' . $field;
//                            if ($_POST[$extname]) 
//                               { $tmp2 = $row[$field];
//
//                                  if (array_key_exists ($field, $extractformats [$table])) $tmp2 = _xlateField ($tmp2, $table, $field);
//
//                                  _checkCSVformatting ($delimiter, $tmp2);
//                                  $tmpR .= "\t$tmp2";
//                               }
//                         }
//          $tmpR .= "\n";
//
///* subtables */
//            if ($_POST['cases__DEFENDANTSTABLE'])
//               { $query2 = "SELECT * FROM defendantstable WHERE ASSREC = {$row['id']} AND DELETED = 0";
//                  $sql2->_sqlLookup ($query2);
//                  if ($sql2->sql_result === false) $sql2->_sqlerror ();
//                  while ($row2 = mysql_fetch_assoc ($sql2->sql_result))
//                           { //for ($i=1;$i<$noOfFields;$i++) $tmpR .= "\t";
//						   
//				/*******************$defendantsfields*********'DEFENDANT', 'CRIME'*******************************************/		   
//                              foreach ($defendantsfields as $evfield)
//                                           { $tmp2 = $row2[$evfield];
//
//                                              if (array_key_exists ($evfield, $extractformats ['defendantstable'])) $tmp2 = _xlateField ($tmp2, 'defendantstable', $evfield);
//
//                                              _checkCSVformatting ($delimiter, $tmp2);
//                                              $tmpR .= "\t$tmp2";
//                                           }
//										   
//                              //$tmpR .= "\n";
//
///* sub-subtable... */
//                             if ($_POST['cases__VERDICTSTABLE'])
//                                { $query3 = "SELECT * FROM verdictstable WHERE ASSREC = {$row2['id']} AND DELETED = 0";
//                                   $sql3->_sqlLookup ($query3);
//                                   if ($sql3->sql_result === false) $sql3->_sqlerror ();
//                                   while ($row3 = mysql_fetch_assoc ($sql3->sql_result))
//                                            { if (($_POST['cases__COURT_NAME']) && ($_POST['cases__COURT_NAME']) == $row3['COURT_NAME']) $addtotmp = true;
//                                               //for ($i=1;$i<$offset;$i++) $tmpR .= "\t";
//											   
//						/*****************$verdictsfields post fields****'UWA_CASEID', 'COURT_DATE', 'COURT_NAME', 'VERDICT', 'SPEC', 'QTY', 'UNIT', 'APPEALED'***************************************************/					   
//                                               foreach ($verdictsfields as $offield)
//                                                            { $tmp2 = $row3[$offield];
//
//                                                               if (array_key_exists ($offield, $extractformats ['verdictstable'])) $tmp2 = _xlateField ($tmp2, 'verdictstable', $offield);
//                                                               
//                                                               _checkCSVformatting ($delimiter, $tmp2);
//                                                               $tmpR .= "\t$tmp2";
//                                                            }
//                                               //$tmpR .= "\n";
//                                            }
//                                }
///* /sub-subtable... */
//
//                           }
//
//
//
//               } 	// - /if ($_POST['cases__DEFENDANTSTABLE'])
//
//			//$tmpR .= "\n";
//            if ($addtotmp) $tmp .= $tmpR;
//         }

/* ---------------------------------------------------------------- */

//mysql_free_result ($sql->sql_result);
//mysql_free_result ($sql2->sql_result);
//mysql_free_result ($sql3->sql_result);

/* ---------------------------------------------------------------- */

//if ($_POST['separator'] == "<;>") $tmp = str_replace ("	", ";", $tmp);
//if ($_POST['separator'] == "<,>") $tmp = str_replace ("	", ",", $tmp);
//$tmp = str_replace ("\r", "", $tmp);
//$tmp = str_replace ("\n", "\r\n", $data);

$of = fopen ("$_docspath/extract.$table.csv", "w");
fwrite ($of, $data);
fclose ($of);

$downsrc = "getfile3.php?file=$_docspath/extract.$table.csv";

/* ---------------------------------------------------------------- */
?>

