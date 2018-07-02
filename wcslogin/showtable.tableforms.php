<?php
@session_start();


/* ---------------------------------------------------------- */

if (isset ($_POST['asstable'])) $asstable = $_POST['asstable']; else $asstable = "";
if (isset ($_POST['assrec'])) $assrec = $_POST['assrec']; else $assrec = "";

$tableforms = <<<NXT
<FORM name="newrecord" method="POST" action="new.record.php" target="newframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="asstable" value="$asstable">
<INPUT type="HIDDEN" name="assrec" value="$assrec">
</FORM>
<FORM name="editfield" method="POST" action="edit.field.php" target="editframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="">
<INPUT type="HIDDEN" name="record" value="">
<INPUT type="HIDDEN" name="field" value="">
<INPUT type="HIDDEN" name="size" value="">
<INPUT type="HIDDEN" name="asstable" value="$asstable">
<INPUT type="HIDDEN" name="assrec" value="$assrec">
</FORM>
<FORM name="notesform" method="POST" action="notes.php" target="notesframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="table" value="">
<INPUT type="HIDDEN" name="record" value="">
<INPUT type="HIDDEN" name="edit" value="">
</FORM>
<FORM name="docsform" method="POST" action="docs.php" target="docsframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="portal" value="">
<INPUT type="HIDDEN" name="table" value="">
<INPUT type="HIDDEN" name="record" value="">
<INPUT type="HIDDEN" name="edit" value="">
</FORM>
<FORM name="profileform" method="POST" action="profile.php" target="pickrecordframe3">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="portal" value="$portal">
<INPUT type="HIDDEN" name="table" value="$table">
<INPUT type="HIDDEN" name="record" value="$record">
<INPUT type="HIDDEN" name="edit" value="">
</FORM>
<FORM name="recform" method="POST" action="showrecord.php" target="recframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="portal" value="">
<INPUT type="HIDDEN" name="table" value="">
<INPUT type="HIDDEN" name="record" value="">
</FORM>
<FORM name="tblform" method="POST" action="runtableview.php" target="pickrecordframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="maintable" value="">
<INPUT type="HIDDEN" name="subtable" value="">
</FORM>
<FORM name="evidenceform" method="POST" action="evidenceview.php" target="pickrecordframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="asstable" value="">
<INPUT type="HIDDEN" name="assrec" value="">
</FORM>
<FORM name="offendersform" method="POST" action="offendersview.php" target="pickrecordframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="asstable" value="">
<INPUT type="HIDDEN" name="assrec" value="">
</FORM>
<FORM name="defendantsform" method="POST" action="defendantsview.php" target="pickrecordframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="asstable" value="">
<INPUT type="HIDDEN" name="assrec" value="">
</FORM>
<FORM name="verdictsform" method="POST" action="verdictsview.php" target="pickrecordframe2">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="asstable" value="">
<INPUT type="HIDDEN" name="assrec" value="">
</FORM>
<FORM name="sentenceform" method="POST" action="sentenceview.php" target="pickrecordframe">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="asstable" value="">
<INPUT type="HIDDEN" name="assrec" value="">
</FORM>
<FORM name="showtable" id="showtable" method="POST" action="$portal.php" 
onsubmit="document.getElementById('newtablebgr').style.visibility = 'visible';">
<INPUT type="HIDDEN" name="id" value="{$_POST['id']}">
<INPUT type="HIDDEN" name="role" value="{$_POST['role']}">
<INPUT type="HIDDEN" name="LL" value="{$_POST['LL']}">
<INPUT type="HIDDEN" name="first" value="$first">
<INPUT type="HIDDEN" name="deleteid" value="">
<INPUT type="HIDDEN" name="order" value="{$GLOBALS['order']}">
<INPUT type="HIDDEN" name="orderby" value="{$GLOBALS['orderby']}">
<INPUT type="HIDDEN" name="showrec" value="{$_POST['showrec']}">
<INPUT type="HIDDEN" name="asstable" value="$asstable">
<INPUT type="HIDDEN" name="assrec" value="$assrec">

NXT;

/* ---------------------------------------------------------------- */

?>
