<?php
@session_start();


$title = "<TITLE>Uganda Wildlife Authority • Offenders Data Portal</TITLE>";

/* -------------------------------------------------------------------------------------- */

$bordercolor = "#606060";

$style = <<<NXT
<STYLE>
BODY { color: #000000; 
           background-color: transparent; 
           margin: 0;  
           font-family: Trebuchet MS, Arial, Helvetica, sans-serif;  
           font-size: 9pt; 
           scrollbar-face-color: #E0DFD9; 
           scrollbar-shadow-color: #F7F6F0;
           scrollbar-highlight-color: #F7F6F0;
           scrollbar-darkshadow-color: #F7F6F0 ; 
           scrollbar-track-color: #E0E0E0; 
           scrollbar-arrow-color: #800000;
}
.title1 {min-height: 24px; height:auto !important; height: 24px; 
            padding: 4 16 4 6;
            background: URL(gradient.php?g=2) center repeat-x;
            border-style: solid; border-width: 0; border-color: #808080; 
            color: #000000; font-size: 12pt; font-style: italic; font-weight: bold; letter-spacing: 0.05em;
          }
.title2 {min-height: 20px; height:auto !important; height: 20px; 
            padding: 6 6 2 6;
            background: URL(gradient.php?g=2) center repeat-x;
            border-style: solid; border-width: 0; border-color: #808080; 
            color: #000000; font-size: 9pt; letter-spacing: 0.0em;
          }
.gap0 {min-height: 24px; height:auto !important; height: 24px; }
.gap1 {min-height: 32px; height:auto !important; height: 24px; }
.gap2 {min-height: 10px; height:auto !important; height: 24px; font-size: 2pt; }
.div244 {min-height: 244px; height:auto !important; height: 244px; }
.box {border-style: ridge;
          border-width: 1pt;
          padding: 0;
          box-shadow: 2px 2px 3px #606060;
          -webkit-box-shadow: 2px 2px 3px #606060;
          -moz-box-shadow: 2px 2px 3px #606060;
	}
.boxnb { padding: 0;
               box-shadow: 2px 2px 3px #606060;
               -webkit-box-shadow: 2px 2px 3px #606060;
               -moz-box-shadow: 2px 2px 3px #606060;
	    }
.formentry { border-style: ridge; border-width: 1; border-color: #0000A0; font-size: 9pt; }
.topcells { border-style: solid; border-width: 0 0 0 3; border-color: #002800; }
.logimg { border-style: solid; border-width: 1; border-color: #000000; }
TABLE { font-size: 9pt; }
.th { background: URL(gradient.php?g=2) top repeat-x; color: #FFFFFF; font-weight: bold; border-style: ridge; border-width: 1 0 0 1; border-color: $bordercolor; }
.thp { background: URL(gradient.php?g=3) top repeat-x; color: #FFFFFF; font-weight: bold; border-style: ridge; border-width: 1 0 0 1; border-color: $bordercolor; }
.thr { background: URL(gradient.php?g=2) top repeat-x; color: #FFFFFF; font-weight: bold; border-style: ridge; border-width: 1 0 0 0; border-color: $bordercolor; }
.thf { background: URL(gradient.php?g=22) top repeat-x; color: #FFFFFF; font-weight: bold; padding: 0 0 2 3; }
.the { background: #980000; color: #FFFFFF; font-weight: bold; border-style: ridge; border-width: 1 0 0 1; border-color: $bordercolor; }
.th2 { background: URL(gradient.php?g=2) top repeat-x; color: #FFFFFF; }
.th3 { color: #FFFFFF; }
.ta { background: #D7D6D0; border-style: ridge; border-width: 1 0 0 1; border-color: $bordercolor; padding: 0 4 0 4; }
.tb { background: #F7F6F2; border-style: ridge; border-width: 1 0 0 1; border-color: $bordercolor; padding: 0 4 0 4; }
.btn { border-style: ridge; border-width: 1; border-color: #0000A0; font-size: 9pt; }
.btn10 { border-style: ridge; border-width: 1; border-color: #0000A0; font-size: 9pt; max-width: 10px; width:auto !important; width: 10px; }
.ntsedit { min-height: 444px; height:auto !important; height: 444px; min-width: 566px; width:auto !important; width: 566px; }

div.fileinputs {	position: relative; }
div.fakefile { position: absolute; top: 0px; left: 0px; z-index: 0; }
input.file { position: relative; text-align: right; -moz-opacity:0 ; filter:alpha(opacity: 0); opacity: 0; z-index: 1; 
                  border-style: ridge; border-width: 1; border-color: #0000A0; font-size: 9pt; 
                  max-width: 310px; width:auto !important; width: 310px; 
                  max-height: 18px; height:auto !important; height: 18px; }

@font-face {
   font-family: 'Tfont';
   font-style: regular;
   font-weight: normal;
   src: url('fonts/Tfont-Reg3-tl.eot');
   src: url('fonts/Tfont-Reg3-tl.eot?#iefix') format('embedded-opentype'),
   url('fonts/Tfont-Reg3-tl.woff') format('woff'),
   url('fonts/Tfont-Reg3-tl.ttf')  format('truetype');
                  }
@font-face {font-family: 'Nyala';
   font-style: regular;
   font-weight: normal;
   src: url('fonts/Nyala.eot');
   src: url('fonts/Nyala.eot?#iefix') format('embedded-opentype'),
   url('fonts/Nyala.woff') format('woff'),
   url('fonts/Nyala.ttf')  format('truetype');
}				  
</STYLE>

NXT;
?>
