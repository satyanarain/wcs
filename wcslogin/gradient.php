<?php
@session_start();

$w0 = 4;
$h0 = 586;
$g0 = array (0 => 0xC0C0C0, 20 => 0xDDDDDD, 60 => 0xF7F6F2, 100 => 0xC0C0C0 );
//$smooth0 = 6;

$w0 = 1;
$h0 = 228;
$g0 = array (0 => 0xF8F6F0, 80 => 0x504D48, 100 => 0x403D38);
$smooth0 = 6;

$w1 = 1;
$h1 = 30;
$g1 = array (0 => 0x605000, 50 => 0xA07020, 70 => 0xC0A810, 100 => 0x605000 );
$g1 = array (0 => 0x000000, 60 => 0x506020, 100 => 0x000000 );	// green
$smooth1 = 8;

$w2 = 1;
$h2 = 32;
$g2 = array (0 => 0x000000, 60 => 0x506020, 100 => 0x000000 );	// green
$g2 = array (0 => 0x000000, 60 => 0x504800, 100 => 0x000000 );
$smooth2 = 8;

$w3 = 1;
$h3 = 32;
$g3 = array (0 => 0x000000, 60 => 0x806800, 100 => 0x000000 );
$smooth3 = 8;

$w22 = 1;
$h22 = 46;
$g22 = array (0 => 0x000000, 60 => 0x506020, 100 => 0x000000 );	// green
$g22 = array (0 => 0x000000, 60 => 0x504800, 100 => 0x000000 );
$smooth22 = 8;

/* -------------------------------------------------------- */

$index = $_GET['g'];
$w = "w" . $index;
$h = "h" . $index;
$g = "g" . $index;

if (! (isset (${$w}) && isset (${$h}) && isset (${$g})))
   { $index = 0;
      $w = "w0";
      $h = "h0";
      $g = "g0";
   }

$width = ${$w};
$height = ${$h};

if ($_GET['w']) $width = $_GET['w'];
if ($_GET['h']) $height = $_GET['h'];

$gradient = ${$g};
$background = 0xFFFFFF;

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


if (isset (${"smooth$index"})) imagefilter ($image, IMG_FILTER_SMOOTH, ${"smooth$index"});

header ('Content-type: image/png');
//imagepng ($image, "gradient.png");
imagepng ($image);
imagedestroy ($image);

?>
