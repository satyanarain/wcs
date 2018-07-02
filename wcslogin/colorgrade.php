<?php
@session_start();


function _colorgrade ($index, $val, $loval, $hival)
{ 

/* -------------------------------------------------------- */

$g0 = array (0 => 0x403000, 18 => 0x60400, 35 => 0x806800, 55 => 0xA088000, 70 => 0xC0A000, 85 => 0xFF0000, 100 => 0x700000);

/* -------------------------------------------------------- */

   $g = "g" . $index;
   if (! isset (${$g})) $g = "g0";
   $gradient = ${$g};

   if (count ($gradient) > 1)
      { $fraction = 100 * (($val - $loval) / ($hival - $loval));
         $fraction = intval (round ($fraction));
         $tostep = -1;
         foreach ($gradient as $key => $color)
                      { if ($key <= $fraction) $fromstep = $key;
                         if (($key >= $fraction) && ($tostep == -1)) $tostep = $key;
                         if ($fromstep == $tostep) 
                           { $fromstep = $previous;
                              break;
                           }
                         $previous = $key;
                      }
         $fromRR = ($gradient [$fromstep] & 0xFF0000) >> 16;
         $fromGG = ($gradient [$fromstep] & 0xFF00) >> 8;
         $fromBB = $gradient [$fromstep] & 0xFF;
         $toRR = ($gradient [$tostep] & 0xFF0000) >> 16;
         $toGG = ($gradient [$tostep] & 0xFF00) >> 8;
         $toBB = $gradient [$tostep] & 0xFF;
         $Rspan = $toRR - $fromRR;
         $Gspan = $toGG - $fromGG;
         $Bspan = $toBB - $fromBB;
         $gap = $fraction - $fromstep;

         $RR = $fromRR + intval (round (($gap / 100) * $Rspan));
         $GG = $fromGG + intval (round (($gap / 100) * $Gspan));
         $BB = $fromBB + intval (round (($gap / 100) * $Bspan));

         $RGB = ($RR << 16) + ($GG << 8) + ($BB);
         $RGB = sprintf ("%02X", $RR) . sprintf ("%02X", $GG) . sprintf ("%02X", $BB);
//$RGB = "($fromstep, $tostep, $Rspan, $fraction, $gap, $RR, $GG, $BB)";
      }

return $RGB;

}

?>
