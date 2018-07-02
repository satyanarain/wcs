<?php
@session_start();

$XS1 = 1;
$YS1 = 1;

$XS2 = -1;
$YS2 = -1;

if ($_GET['s']) $settings = $_GET['s']; else $settings = 0;
$title = $_GET['title'];
include "ts$settings.php";

/* ---------------------------------------------------------------------- */

$fname = "s=$settings&title=$title";
$fname = str_replace (' ', '_', $fname);
$fname = str_replace ('=', '-', $fname);
$fname = str_replace ('&', '+', $fname);
$fname = str_replace ('.', '_', $fname);
$fname = str_replace (',', '_', $fname);
$fname = str_replace (array ('/', ':', ';'), '', $fname);

/* ---------------------------------------------------------------------- */

function _textWidth ($text, $fontsize, $_font)
{ $textbox = imagettfbbox (0+$fontsize, 0, $_font, $text);
   return $textbox[2];
}

//if ($_GET['font'] != "EN") $fontfile = "Nyala.ttf";

$titlewidth = _textWidth ($title, $fontsize, "fonts/$fontfile");

$width = $titlewidth + $leftmargin + $rightmargin;
$height = ($fontsize * 1.4) + $topmargin + $bottommargin;

$image = imagecreatetruecolor ($width, $height);
$alpha   = imagesavealpha( $image, true );
$trans   = imagecolorallocatealpha( $image, ($background & 0xFF0000) >> 16, ($background & 0xFF00) >> 8, $background & 0xFF, 127 );
$fill    = imagefill( $image, 0, 0, $trans );

if (count ($gradient) > 1)
   { $i = 0;
      $percentheight = $height / 100.0;
      $fromstep = 0;
      $fromRR = ($gradient [$fromstep] & 0xFF0000) >> 16;
      $fromGG = ($gradient [$fromstep] & 0xFF00) >> 8;
      $fromBB = $gradient [$fromstep] & 0xFF;
      foreach ($gradient as $step => $color)
                  { if ($step == 0) continue;
                     $toRR = ($gradient [$step] & 0xFF0000) >> 16;
                     $toGG = ($gradient [$step] & 0xFF00) >> 8;
                     $toBB = $gradient [$step] & 0xFF;
                     $firstline = intval (round ($fromstep * $percentheight));
                     $lastline = intval (round ($step * $percentheight));
                     $R = abs (($toRR - $fromRR) / ($lastline - $firstline));
                     $G = abs (($toGG - $fromGG) / ($lastline - $firstline));
                     $B = abs (($toBB - $fromBB) / ($lastline - $firstline));
                     for ($currentline = $firstline; $currentline <= $lastline; $currentline++)
                          { if ($toRR >= $fromRR) $fromRR += $R; else $fromRR -= $R; 
                             $fromRR = max (min ($fromRR, 255), 0);
                             if ($toGG >= $fromGG) $fromGG += $G; else $fromGG -= $G; 
                             $fromGG = max (min ($fromGG, 255), 0);
                             if ($toBB >= $fromBB) $fromBB += $B; else $fromBB -= $B; 
                             $fromBB = max (min ($fromBB, 255), 0);
                             $RGB = intval (round (($fromRR << 16))) + intval (round (($fromGG << 8))) + intval (round ($fromBB));
                             imageline ($image, 0, $height - $currentline, $width - 1, $height - $currentline, $RGB);
                          }
                     $fromstep = $step;
                  }
   } 

if (isset ($shadow)) 
   { do { $y = $YS1;
              do { imagettftext ($image, $fontsize, 0, $leftmargin + $XS1, $fontsize + $topmargin + $y, $shadow, "fonts/$fontfile", $title);
                      if ( $y > 0) $y--; else if ( $y < 0) $y++; 
                   } while ($y != 0);
              imagettftext ($image, $fontsize, 0, $leftmargin + $XS1, $fontsize + $topmargin + $y, $shadow, "fonts/$fontfile", $title);
              if ( $XS1 > 0) $XS1--; else if ( $XS1 < 0) $XS1++; 
           } while ($XS1 != 0);
      //imagettftext ($image, $fontsize, 0, $leftmargin + $XS1, $fontsize + $topmargin + $y, $shadow, "fonts/$fontfile", $title);
   }

if (isset ($shadow2)) 
   { do { $y = $YS2;
              do { imagettftext ($image, $fontsize, 0, $leftmargin + $XS2, $fontsize + $topmargin + $y, $shadow2, "fonts/$fontfile", $title);
                      if ( $y > 0) $y--; else if ( $y < 0) $y++; 
                   } while ($y != 0);
              imagettftext ($image, $fontsize, 0, $leftmargin + $XS2, $fontsize + $topmargin + $y, $shadow2, "fonts/$fontfile", $title);
              if ( $XS2 > 0) $XS2--; else if ( $XS2 < 0) $XS2++; 
           } while ($XS2 != 0);
       //imagettftext ($image, $fontsize, 0, $leftmargin + $XS2, $fontsize + $topmargin + $y, $shadow2, "fonts/$fontfile", $title);
   }

imagettftext ($image, $fontsize, 0, $leftmargin, $fontsize + $topmargin, 0+$fontcolor, "fonts/$fontfile", $title);

if (isset ($smooth)) imagefilter ($image, IMG_FILTER_SMOOTH, $smooth);

imagepng ($image, "timgs/$fname.png");
header ('Content-type: image/png');
imagepng ($image);
imagedestroy ($image);

?>
