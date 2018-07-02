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

$chunk .= <<<NXT
&nbsp;&nbsp;<INPUT type="BUTTON" class="btn" value="Cancel"
onclick="
document.getElementById('newtablebgr').style.visibility = 'hidden';
parent.document.getElementById('newtablebgr').style.visibility = 'hidden'; 
parent.document.getElementById('pickrecordtable').style.visibility = 'hidden';
parent.document.getElementById('edittable').style.visibility='hidden';
">
NXT;

if ($total == 0) $showing = "No records found"; else $showing = "Showing $first to $last of $total entries";

$list = str_replace ("#SHOWING1#", $showing, $list);
$list = str_replace ("#SHOWING2#", "$chunk", $list);

$chunk = "<SPAN style=\"font-size: 3pt;\">&nbsp;</SPAN>";

?>

