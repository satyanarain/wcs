<?php
@session_start();


/* ---------------------------------------------------------- */

$showtable_tables = <<<NXT
<TABLE ID="newtablebgr" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

<TABLE ID="newtablebgr2" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

<TABLE ID="newtablebgr3" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden; background: #000000; opacity: 0.7; filter:alpha(opacity=70); ">
<TR>
<TD>
&nbsp;
</TD>
</TR>
</TABLE>

<TABLE ID="newtable" width="550" height="400" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="newframe" ID="newframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="deltable" width="280" height="140" cellspacing="0" celpadding="0" border="0" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden; border-style: ridge; border-width: 2; border-color: #807000; ">
<TR>
<TD colspan="2" align="center"><B>Delete: <SPAN ID="entrydel"></SPAN> ?
</TD>
</TR>
<TR>
<TD align="center">
<INPUT type="button" value="Delete" class="btn" onclick="document.showtable.submit();">
</TD>
<TD align="center">
<INPUT type="button" value="Cancel" class="btn" onclick="document.showtable.deleteid.value = ''; document.getElementById('newtablebgr').style.visibility = 'hidden'; document.getElementById('deltable').style.visibility = 'hidden';">
</TD>
</TR>
</TABLE>

<TABLE name="edittable" ID="edittable" width="360" height="244" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="editframe" ID="editframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"  scrolling="no"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="notestable" width="600" height="500" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="notesframe" ID="notesframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="docstable" width="600" height="500" cellspacing="0" celpadding="0" border="1" bgcolor="#FFFFFF"
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="docsframe" ID="docsframe" width="100%" height="100%" frameborder="0" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE ID="rectable" width="100%" height="100%" cellspacing="0" celpadding="0" border="1" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="recframe" ID="recframe" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE name="pickrecordtable" ID="pickrecordtable" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="pickrecordframe" ID="pickrecordframe" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE name="pickrecordtable2" ID="pickrecordtable2" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="pickrecordframe2" ID="pickrecordframe2" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>

<TABLE name="pickrecordtable3" ID="pickrecordtable3" width="100%" height="100%" cellspacing="0" celpadding="0" border="0" 
style="position: absolute; left: 0; top: 0; visibility: hidden;">
<TR>
<TD>
<Iframe name="pickrecordframe3" ID="pickrecordframe3" width="100%" height="100%" frameborder="0" scrolling="auto" SRC="" allowTransparency="true"></Iframe>
</TD>
</TR>
</TABLE>


<Iframe width="0" height="0" frameborder="0" SRC="editcheck.php?t={$GLOBALS['timestamp']}&n=$table&u={$_POST['id']}" allowTransparency="true" 
style="position: absolute; left: 0; top: 0; visibility: hidden;"></Iframe>

NXT;

?>
