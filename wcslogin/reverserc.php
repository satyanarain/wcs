<?php 
@session_start();

/* ---------------------------------------------------------------- */

// used in 'stats... .php'

/* ---------------------------------------------------------------- */

function _reverseRC ($_toreverse)
{ $toreverse = trim ($_toreverse, "\n");
   $rows = explode ("\n", $toreverse);
   $outrows = array ();
   $outrows2 = array ();
   $outlines = array ();
   $cols = 0;
   $i = 0;
   foreach ($rows as $row)
                { $outrows[$i] = explode ("\t", $row);
                   if ($i == 0) $cols = count ($outrows[$i]);
                   $i++;
                }
   for ($i=0;$i<$cols;$i++)
         for ($r=0;$r<count($outrows);$r++)
              $outrows2[$i][$r] = $outrows[$r][$i];
   for ($s=0;$s<$cols;$s++)
         { $outlines[$s] = implode ("\t", $outrows2[$s]);
            $outlines[$s] = str_replace ("\r", "", $outlines[$s]);
         }
return  implode ("\n", $outlines);
}

/* ---------------------------------------------------------------- */

?>

