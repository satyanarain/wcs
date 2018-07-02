<?php 
@session_start();

/* ---------------------------------------------------------------- */

// Note: Copied from settings.php !
$serverpath = "/home/opianfpe";
if (getenv('SERVER_ADDR') == "127.0.0.1") $docspath = "D:/clients/web/__UWA/_uwa_docs/"; else $docspath = "$serverpath/_uwa_docs/";

/* ---------------------------------------------------------------- */

$_docspath = "{$docspath}suspects/{$_GET['rec']}";


if ($_GET['in'] == 'fa') $in = 'facial'; 
if ($_GET['in'] == 'fp') $in = 'fp'; 

/* ---------------------------------------------------------------- */

$imagefile = "$_docspath/$in/{$_GET['img']}";
if ($_GET['in'] == 'ref') $imagefile = $_GET['img']; 

list ($img, $ext) = explode (".", $_GET['img']);
$ext = strtolower ($ext);

if ($ext == 'jpg') 
   { //$image = imagecreatefromjpeg ($imagefile);
  $f = fopen($imagefile, "rb");
  $img = fread($f, filesize($imagefile));
  fclose($f);
      header ('Content-Type: image/png');
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Some time in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
  header("Cache-Control: no-store, no-cache, must-revalidate"); 
  header("Cache-Control: post-check=0, pre-check=0", false); 
  header("Pragma: no-cache");
      //imagejpeg ($image);
      //imagedestroy ($image);
echo $img;
   }

if ($ext == 'png') 
   { //$image = imagecreatefrompng ($imagefile);
  $f = fopen($imagefile, "rb");
  $img = fread($f, filesize($imagefile));
  fclose($f);
      header ('Content-Type: image/png');
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Some time in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
  header("Cache-Control: no-store, no-cache, must-revalidate"); 
  header("Cache-Control: post-check=0, pre-check=0", false); 
  header("Pragma: no-cache");
      //imagepng ($image);
      //imagedestroy ($image);
echo $img;
   }

/* ---------------------------------------------------------------- */

?>

