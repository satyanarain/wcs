<?php
@session_start();


/* ---------------------------------------------------------- */

$tablejava = <<<NXT
<SCRIPT language="JavaScript">
function _goEdit (_table, _id, _field, _len)
{ document.editfield.table.value = _table;
   document.editfield.record.value = _id;
   document.editfield.field.value = _field;
   document.editfield.size.value = _len;
   _setEditFrame (360, 244);
  document.editfield.submit();
}
function _goDelete (_id, _name)
{ _setDelFrame (280, 140, _id, _name); 
}
function _goNotes (_table, _recid)
{ _setNotesFrame (600, 500, _table, _recid, 1); 
}
function _viewNotes (_table, _recid)
{ _setNotesFrame (600, 500, _table, _recid, 0); 
}
function _goDocs (_table, _recid)
{ _setDocsFrame (600, 500, _table, _recid, 1); 
}
function _viewDocs (_table, _recid)
{ _setDocsFrame (600, 500, _table, _recid, 0); 
}
function _goRecview (_table, _recid)
{ _setRecFrame (_table, _recid);
}
function _goTableview (_table, _subtable)
{ _setTblFrame (_table, _subtable);
}
function _goEvidenceview (_table, _recid)
{ _setEvidenceFrame (_table, _recid);
}
function _goOffendersview (_table, _recid)
{ _setOffendersFrame (_table, _recid);
}
function _goDefendantsview (_table, _recid)
{ _setDefendantsFrame (_table, _recid);
}
function _goSentenceview (_table, _recid)
{ _setSentenceFrame (_table, _recid);
}
function _goVerdictsview (_table, _recid)
{ _setVerdictsFrame (_table, _recid);
}
function _viewProfile (_table, _recid)
{ _setProfileFrame (_table, _recid);
}

function _centerElement (elmID, W, H)
{ document.getElementById(elmID).style.top=0+Math.round((document.body.clientHeight + document.body.scrollTop - H) / 2); 
   document.getElementById(elmID).style.left=0+Math.round((document.body.clientWidth + document.body.scrollLeft - W) / 2);
}

function _setNewFrame (W, H)
{ _centerElement ('newtable', W, H);
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('newtable').style.visibility = 'visible';
}  
function _setDelFrame (W, H, _id, _name)
{ document.showtable.deleteid.value = _id;
   document.getElementById('entrydel').innerHTML = _name;
   _centerElement ('deltable', W, H);
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('deltable').style.visibility = 'visible';
}  
function _setEditFrame (W, H)
{ _centerElement ('edittable', W, H);
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('edittable').style.visibility = 'visible';
}  
function _setNotesFrame (W, H, _table, _recid, _edit)
{ document.notesform.table.value = _table;
   document.notesform.record.value = _recid;
   document.notesform.edit.value = _edit;
   document.notesform.submit();
   _centerElement ('notestable', W, H);
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('notestable').style.visibility = 'visible';
}  
function _setDocsFrame (W, H, _table, _recid, _edit)
{ document.docsform.portal.value = '$portal';
   document.docsform.table.value = _table;
   document.docsform.record.value = _recid;
   document.docsform.edit.value = _edit;
   document.docsform.submit();
   _centerElement ('docstable', W, H);
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('docstable').style.visibility = 'visible';
}  
function _setRecFrame (_table, _recid)
{ document.recform.portal.value = '$portal';
   document.recform.table.value = _table;
   document.recform.record.value = _recid;
   document.recform.submit();
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('rectable').style.visibility = 'visible';
}  
function _setTblFrame (_table, _subtable)
{ document.tblform.maintable.value = _table;
   document.tblform.subtable.value = _subtable;
   document.tblform.submit();
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('pickrecordtable').style.visibility = 'visible';
}  
function _setEvidenceFrame (_table, _recid)
{ document.evidenceform.asstable.value = _table;
   document.evidenceform.assrec.value = _recid;
   document.evidenceform.submit();
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('pickrecordtable').style.visibility = 'visible';
}  
function _setOffendersFrame (_table, _recid)
{ document.offendersform.asstable.value = _table;
   document.offendersform.assrec.value = _recid;
   document.offendersform.submit();
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('pickrecordtable').style.visibility = 'visible';
}  
function _setDefendantsFrame (_table, _recid)
{ document.defendantsform.asstable.value = _table;
   document.defendantsform.assrec.value = _recid;
   document.defendantsform.submit();
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('pickrecordtable').style.visibility = 'visible';
}  
function _setSentenceFrame (_table, _recid)
{ document.sentenceform.asstable.value = _table;
   document.sentenceform.assrec.value = _recid;
   document.sentenceform.submit();
   document.getElementById('newtablebgr').style.visibility = 'visible';
   document.getElementById('pickrecordtable').style.visibility = 'visible';
}  
function _setVerdictsFrame (_table, _recid)
{ document.verdictsform.asstable.value = _table;
   document.verdictsform.assrec.value = _recid;
   document.verdictsform.submit();
   document.getElementById('newtablebgr2').style.visibility = 'visible';
   document.getElementById('pickrecordtable2').style.visibility = 'visible';
}  
function _setProfileFrame (_table, _recid)
{ document.profileform.table.value = _table;
   document.profileform.record.value = _recid;
   document.profileform.submit();
   document.getElementById('newtablebgr3').style.visibility = 'visible';
   document.getElementById('pickrecordtable3').style.visibility = 'visible';
}  

function _centerTables()
{ _centerElement ('newtable', 550, 400);
   _centerElement ('deltable', 280, 140);
   _centerElement ('edittable', 360, 244);
   _centerElement ('notestable', 600, 500);
   _centerElement ('docstable', 600, 500);
}

window.onresize=_centerTables;

</SCRIPT>
NXT;

?>
