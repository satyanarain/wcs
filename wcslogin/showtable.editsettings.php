<?php 
@session_start();

function _editSettings ($permissions, $table, $field, $id, $fieldlen)
{ $handling = $GLOBALS['tablehandlings'][$table][$field];

   $result = "";
   $onclick = "";

   if (($permissions & _edit_) && ($handling != 'AUTO')) 
     { $result = <<<NXT
style="cursor: {$GLOBALS['cursor']}"

NXT;

        if ($field == 'NOTES') 
           { $result .= <<<NXT
onclick="_goNotes ('$table', '$id');"

NXT;
           }
        elseif ($field == 'DOCS') 
                 { $result .= <<<NXT
onclick="_goDocs ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'PROFILE') 
                 { $result .= <<<NXT
onclick="_viewProfile ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'SHOWREC') 
                 { $result .= <<<NXT
onclick="_goRecview ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'SUBTABLE') 
                 { $result .= <<<NXT
onclick="_goTableview ('$table', '$table$id');"

NXT;
                 }
        elseif ($field == 'EVIDENCETABLE') 
                 { $result .= <<<NXT
onclick="_goEvidenceview ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'OFFENDERSTABLE') 
                 { $result .= <<<NXT
onclick="_goOffendersview ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'DEFENDANTSTABLE') 
                 { $result .= <<<NXT
onclick="_goDefendantsview ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'SENTENCETABLE') 
                 { $result .= <<<NXT
onclick="_goSentenceview ('$table', '$id');"

NXT;
                 }
        elseif ($field == 'VERDICTSTABLE') 
                 { $result .= <<<NXT
onclick="_goVerdictsview ('$table', '$id');"

NXT;
                 }
        else { $result .= <<<NXT
onclick="_goEdit ('$table', '$id', '$field', '$fieldlen');"

NXT;
                }
return $result;
     }

   if (($permissions & _view_) && (($field == 'NOTES') || ($field == 'DOCS') || ($field == 'SHOWREC') || ($field == 'SUBTABLE') || ($field == 'EVIDENCETABLE') || ($field == 'OFFENDERSTABLE') || ($field == 'DEFENDANTSTABLE') || ($field == 'SENTENCETABLE') || ($field == 'VERDICTSTABLE'))) 
     { $result = <<<NXT
style="cursor: {$GLOBALS['cursor']}"

NXT;
        if ($field == 'NOTES') 
           $result .= <<<NXT
onclick="_viewNotes ('$table', '$id');"

NXT;
        if ($field == 'DOCS') 
           $result .= <<<NXT
onclick="_viewDocs ('$table', '$id');"

NXT;
        if ($field == 'PROFILE') 
           $result .= <<<NXT
onclick="_viewProfile ('$table', '$id');"

NXT;
        if ($field == 'SHOWREC') 
           $result .= <<<NXT
onclick="_goRecview ('$table', '$id');"

NXT;
        if ($field == 'SUBTABLE') 
           $result .= <<<NXT
onclick="_goTableview ('$table', '$table$id');"

NXT;
        if ($field == 'EVIDENCETABLE') 
           $result .= <<<NXT
onclick="_goEvidenceview ('$table', '$id');"

NXT;
        if ($field == 'OFFENDERSTABLE') 
           $result .= <<<NXT
onclick="_goOffendersview ('$table', '$id');"

NXT;
        if ($field == 'DEFENDANTSTABLE') 
           $result .= <<<NXT
onclick="_goDefendantsview ('$table', '$id');"

NXT;
        if ($field == 'SENTENCETABLE') 
           $result .= <<<NXT
onclick="_goSentencesview ('$table', '$id');"

NXT;
        if ($field == 'VERDICTSTABLE') 
           $result .= <<<NXT
onclick="_goVerdictsview ('$table', '$id');"

NXT;

return $result;
     }
return $result;
}

?>

