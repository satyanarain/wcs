<?php
@session_start();

// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$htmlcontent = stripslashes(html_entity_decode($_POST['suspectprofile']));

$dompdf->loadHtml($htmlcontent);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($_POST['suspectname'],array("Attachment"=>0));

//echo $htmlcontent;

?>