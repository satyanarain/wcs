<?php 
@session_start();

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein • e-mail: jan.kirstein\@jayksoft.com
Copyright © 2012 - 2013 Jan Kirstein. All rights reserved!
-->

$style

<STYLE>
.tds { padding: 0 4 0 4; }
.tdsheader { padding: 0 4 0 4; background: #585858; color: #FFFFFF; font-weight: bold; }
.tdsheader2 { padding: 0 4 0 4; background: #C0C0C0; font-weight: bold; }
</STYLE>

<BODY style="margin: 8;">

<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="background: #F8F6F2;">
<TR>
<TD align="center" Valign="middle">

<DIV align="left" style="font-size: 10pt;">
<B>
Use of the Summaries settings and report generation tool.
</B>
</DIV>

<P>

<TABLE width="90%" cellspacing="0" cellpadding="4" border="0">
<TR>
<TD align="justify" Valign="top">
<OL>
<Li>Select the start date included in your search by clicking on this in the calendar displayed on the left in settings at the top of this page.
<Li>Select the end date included in your search by clicking on this in the calendar displayed on the right in settings at the top of this page.
<Li>Select the desired summary report in the drop-down box listing the available summaries. 
<Li>If selected, the 'Trace impounded item' option, in the summaries drop-down, opens a further input field. See details of usage below.
<Li>The summaries report will be displayed in tables on this screen while also being offered for download in the form of a CSV formatted text file (in DOS format, as used by Microsoft 'Windows'). Select your preferred value separator used in the CSV text file output in the drop-down box labelled 'CSV delimiter'.
<Li>Click the 'Get summary' button to generate the desired summary report.
</OL>
<P>
The '<B>Trace impounded item</B>' option in the 'Summaries' drop-down box causes a search for (item) in the whole of the 'Evidence' table's 'Serial / spec.' column, where things like license plate- or items' serial numbers is entered as well as the 'Evidence no.' column, and lists any arrest reference where anything in this field matches whatever is entered in the opening search field for (item). The search is not case sensitive, and will select any record where (item) matches either a part of the field's value as well as exact matches. I.e.: if two evidence records list numbers, say 'UA 143B' and 'UA 144B' respectively, a search for "UA" or "UA 14" will list arrest references for both, whereas a search for "143", "43B" or "3B" will list arrest references to only the first. And - obviously - a search for "UA14" (without the space between "UA" and "14") will return nothing...
</TD>
</TR>
</TABLE>


</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<Iframe name="downframe" ID="downframe" width="0" height="0" frameborder="0" SRC="getfile3.php?file=$_docspath/arrestsstats.csv" allowTransparency="true"></Iframe>

</BODY>
</HTML>

NXT;

echo $page;

?>

