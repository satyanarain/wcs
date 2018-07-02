<?php 
@session_start();

//print_r($_POST);

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

if ($fromdate >= $_todate) $additionalFilter = "";
   else $additionalFilter = "(ARRESTDATE >= $fromdate) AND (ARRESTDATE <= $todate)";

if ($additionalFilter) 
   { if ($searchstring) $searchstring .= " $additionalFilter AND";
         else $searchstring = "$additionalFilter AND";
   }

/* ---------------------------------------------------------------- */

$query = "SELECT * FROM $table WHERE $searchstring DELETED = 0";
$sql->_sqlLookup ($query);
$total = mysql_num_rows ($sql->sql_result); 
if ($sql->sql_result === false) $sql->_sqlerror ();
/*
$arrests__UWA_CASEID=$_POST['arrests__UWA_CASEID'];
$arrests__POLICE_CASEID=$_POST['arrests__POLICE_CASEID'];
$arrests__OFFICER_NAME=$_POST['arrests__OFFICER_NAME'];
$arrests__ARRESTDATE=$_POST['arrests__ARRESTDATE'];
$arrests__ARRESTTIME=$_POST['arrests__ARRESTTIME'];
$arrests__OUTPOST=$_POST['arrests__OUTPOST'];
$arrests__OUTPOST_NAME=$_POST['arrests__OUTPOST_NAME'];
$arrests__LOCATION_LAT=$_POST['arrests__LOCATION_LAT'];
$arrests__LOCATION_LON=$_POST['arrests__LOCATION_LON'];
$arrests__UTM_Z=$_POST['arrests__UTM_Z'];
$arrests__DATUM=$_POST['arrests__DATUM'];
$arrests__LOCATION_NAME=$_POST['arrests__LOCATION_NAME'];
$arrests__DETECTION=$_POST['arrests__DETECTION'];
$arrests__OFFENCE=$_POST['arrests__OFFENCE'];
$arrests__OFFENCE_LOCATION=$_POST['arrests__OFFENCE_LOCATION'];
$arrests__OFFENCE=$_POST['arrests__OFFENCE'];
$arrests__MOTIVATION=$_POST['arrests__MOTIVATION'];
*/

/*
`id`, `REGISTERED`, `DELETED`, `CHANGED`, `CHANGEDBY`, `NOTES`, `DOCS`, `UWA_CASEID`, `POLICE_CASEID`, 
        `PARTICIPANTS`, `OFFICER_NAME`, `ARRESTDATE`, `ARRESTTIME`, `OUTPOST`, `OUTPOST_NAME`, `LOCATION_LAT`, 
        `LOCATION_LON`, `UTM_Z`, `DATUM`, `LOCATION_NAME`, 
        `DETECTION`, `OFFENCE`, `OFFENCE_LOCATION`, `EVIDENCETABLE`, `MOTIVATION`, `OFFENDERSTABLE`
*/

if($_POST['arrests__UWA_CASEID']!='')
{
 $column.=", arrests.UWA_CASEID as 'UWA Case ID'";
}
$arrests__POLICE_CASEID=$_POST['arrests__POLICE_CASEID'];
if($arrests__POLICE_CASEID!='')
{   
    $column.=", arrests.POLICE_CASEID as 'POLICE Case ID'"; 
 }

$arrests__OFFICER_NAME=$_POST['arrests__OFFICER_NAME'];
if($arrests__OFFICER_NAME!='')
{ 
    $column.=", arrests.OFFICER_NAME as 'Arresting officer'"; 
 }

$arrests__ARRESTDATE=$_POST['arrests__ARRESTDATE'];

if($arrests__ARRESTDATE!='')
{ 

       $column.=", IF(arrests.ARRESTDATE='0','',FROM_UNIXTIME(arrests.ARRESTDATE)) as 'Date'";
 }

$arrests__ARRESTTIME=$_POST['arrests__ARRESTTIME'];
if($arrests__ARRESTTIME!='')
{ 
    $column.=" , IF(arrests.ARRESTTIME='0','',FROM_UNIXTIME(arrests.ARRESTTIME,'%h:%i:%s')) as 'Time'";
}
$arrests__OUTPOST=$_POST['arrests__OUTPOST'];
if($arrests__OUTPOST!='')
{ 
    $column.=" , arrests.OUTPOST as 'UWA Station'" ; 
 }
$arrests__OUTPOST_NAME=$_POST['arrests__OUTPOST_NAME'];
if($arrests__OUTPOST_NAME!='')
{ 
    $column.=" , arrests.OUTPOST_NAME as 'Outpost name'" ; 
 }
$arrests__LOCATION_LAT=$_POST['arrests__LOCATION_LAT'];
if($arrests__LOCATION_LAT!='')
{ 
    $column.=" , arrests.LOCATION_LAT as 'Location Lat.'"; 
 }
$arrests__LOCATION_LON=$_POST['arrests__LOCATION_LON'];

if($arrests__LOCATION_LON!='')
{ 
    $column.=" , arrests.LOCATION_LON as 'Location Lon.'"; 
 }
 
 $arrests__UTM_Z=$_POST['arrests__UTM_Z'];
 if($arrests__UTM_Z!='')
{
    $column.=" , arrests.UTM_Z as 'UTMz'"; 
}
 
$arrests__DATUM=$_POST['arrests__DATUM'];

if($arrests__DATUM!='')
{
    $column.=" , arrests.DATUM as 'Datum'"; 
}
$arrests__LOCATION_NAME=$_POST['arrests__LOCATION_NAME'];
if($arrests__LOCATION_NAME!='')
{
    $column.=" , arrests.LOCATION_NAME as 'Location name'"; 
}
$arrests__DETECTION=$_POST['arrests__DETECTION'];
if($arrests__DETECTION!='')
{
    $column.=" , arrests.DETECTION as 'Detection'"; 
}
$arrests__OFFENCE=$_POST['arrests__OFFENCE'];
if($arrests__OFFENCE!='')
{
    $column.=" , arrests.OFFENCE as 'Crime Type'"; 
}
$arrests__OFFENCE_LOCATION=$_POST['arrests__OFFENCE_LOCATION'];
if($arrests__OFFENCE_LOCATION!='')
{
    $column.=" , arrests.OFFENCE_LOCATION as 'Offence Location'"; 
}

$arrests__MOTIVATION=$_POST['arrests__MOTIVATION'];
if($arrests__MOTIVATION!='')
{
    $column.=" , arrests.MOTIVATION as 'Motivation'"; 
} 
 
 //$_POST['arrests__EVIDENCETABLE']='';
 //$_POST['arrests__OFFENDERSTABLE']='';
 
 
//$column.="arrests.DELETED, ";
//$column.="arrests.id, ";
if($_POST['arrests__EVIDENCETABLE']!='' && empty($_POST['arrests__OFFENDERSTABLE']))
   {
   
   $string = "left join evidencetable on arrests.id = evidencetable.ASSREC and evidencetable.DELETED = 0";
     $query = "select DISTINCT arrests.id as 'rec' " .$column." ,evidencetable.EVIDENCE as 'Item / evidence',evidencetable.SPECIES as 'Species',evidencetable.LABEL as 'Serial / Spec.',evidencetable.QTY as 'Qty',evidencetable.UNIT as 'Units',evidencetable.EST_VALUE as 'Value',evidencetable.EVIDENCE_NO as 'Evidence No.',evidencetable.STATUS as 'Status',evidencetable.LOCATION as 'Location' FROM arrests "."$string"." where arrests.DELETED=0";
   
   }
 else if($_POST['arrests__OFFENDERSTABLE']!='' && empty($_POST['arrests__EVIDENCETABLE']))
   {
   $string = "left join offenderstable on arrests.id=offenderstable.ASSREC and offenderstable.DELETED=0 left join suspects on suspects.id=offenderstable.OFFENDER and offenderstable.DELETED=0 ";
    $query = " select DISTINCT arrests.id as 'rec' " .$column." ,suspects.SUSPECT_NAME as 'Offender',offenderstable.HOME_LC1_NAME as 'Village (LC1)',offenderstable.HOME_LAT as 'Home Lat.',offenderstable.HOME_LON as 'Home Lon.',offenderstable.UTM_Z as 'UTMz',offenderstable.DATUM as 'Datum',offenderstable.ACTION as 'Action taken',offenderstable.INFORMANT as 'Informant' FROM arrests "."$string"." where arrests.DELETED=0";
   }

  else if($_POST['arrests__OFFENDERSTABLE']!='' && $_POST['arrests__EVIDENCETABLE']!='')
   {
      
   $string = "left join evidencetable on arrests.id = evidencetable.ASSREC and evidencetable.DELETED = 0 left join offenderstable on evidencetable.id=offenderstable.ASSREC and offenderstable.DELETED=0 left join suspects on suspects.id=offenderstable.OFFENDER and offenderstable.DELETED=0";
   $query = "select DISTINCT arrests.id as 'rec' " .$column." ,evidencetable.EVIDENCE as 'Item / evidence',evidencetable.SPECIES as 'Species',evidencetable.LABEL as 'Serial / Spec.',evidencetable.QTY as 'Qty',evidencetable.UNIT as 'Units',evidencetable.EST_VALUE as 'Value',evidencetable.EVIDENCE_NO as 'Evidence No.',evidencetable.STATUS as 'Status',evidencetable.LOCATION as 'Location',suspects.SUSPECT_NAME as 'Offender',offenderstable.HOME_LC1_NAME as 'Village (LC1)',offenderstable.HOME_LAT as 'Home Lat.',offenderstable.HOME_LON as 'Home Lon.',offenderstable.UTM_Z as 'UTMz',offenderstable.DATUM as 'Datum',offenderstable.ACTION as 'Action taken',offenderstable.INFORMANT as 'Informant' FROM arrests "."$string"." where arrests.DELETED=0";
   }
 
  else 
   {
      
   // echo "hhhhh"  ;
      
   // $string = "left join evidencetable on arrests.id = evidencetable.";
      
     $query = "select DISTINCT arrests.id as rec ".$column." FROM arrests where arrests.DELETED=0";
     
   // $query = "select " .$column." evidencetable.ASSREC,offenderstable.ASSREC,evidencetable.CRIME,offenderstable.COURT_DATE,offenderstable.COURT_NAME,offenderstable.VERDICT,offenderstable.SPEC,offenderstable.QTY FROM arrests "."$string"." where arrests.DELETED=0 limit 0,10";
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
$of = fopen ("$_docspath/extract.$table.csv", "w");
fwrite ($of, $data);
fclose ($of);

$downsrc = "getfile3.php?file=$_docspath/extract.$table.csv";

/* ---------------------------------------------------------------- */




?>

