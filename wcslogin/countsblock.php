<?php 
@session_start();

$last = $first + $count - 1;

$chunk = "&nbsp;";
if ($first > _first_) 
   { $_first = _first_;
      $chunk .= <<<NXT
&nbsp;<INPUT type="BUTTON" class="btn" value="<<"
onclick="document.showtable.first.value = $_first; 
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="< -"
onclick="document.showtable.first.value = $first - $max; 
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
NXT;
   }

if (($count < $total) && ($last < $total)) 
   $chunk .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="+ >"
onclick="document.showtable.first.value = $first + $max; 
document.getElementById('newtablebgr').style.visibility = 'visible';
document.showtable.submit();">
NXT;

$_maintable = $GLOBALS['maintable'];
$cols = $GLOBALS['datablockwidth'][$table];
$role = $_POST['role'];
if ($role == 'GOD') $_permissions = 0xFF; else $_permissions = $GLOBALS['permissions'][$role][$mainportal][$_maintable];

/*
$deb = fopen ("tmp.txt", "w");
fwrite ($deb, "\$GLOBALS['permissions'][$role][$mainportal][$_maintable] = $_permissions\n");
fclose ($deb);
*/

if (($_permissions & _edit_) && ($table != 'evidencetable') && ($table != 'offenderstable') || ($table == 'defendantstable') || ($table == 'sentencetable') || ($table == 'verdictstable')) $chunk .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="Upload data"
onclick="
document.getElementById('newtablebgr').style.visibility = 'visible';
document.getElementById('pickrecordtable').style.visibility = 'visible';
document.getElementById('pickrecordframe').src = 'uploadlist.php?id={$_POST['id']}&role=$role&table=$table&cols=$cols&maintable={$_POST['maintable']}';
">
NXT;

$chunk .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="Close"
onclick="
//document.getElementById('newtablebgr').style.visibility = 'hidden';
parent.document.getElementById('pickrecordframe').src = '';
//parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
parent.document.getElementById('pickrecordtable').style.visibility = 'hidden';
parent.document.getElementById('pickrecordframe').src = '';
parent.document.getElementById('edittable').style.visibility='hidden';
parent.document.showtable.submit();
">
NXT;

if ($total == 0) $showing = "No records found"; else $showing = "Showing $first to $last of $total entries";

$list = str_replace ("#SHOWING1#", $showing, $list);
$list = str_replace ("#SHOWING2#", "$chunk", $list);

$chunk = "<SPAN style=\"font-size: 3pt;\">&nbsp;</SPAN>";

?>

