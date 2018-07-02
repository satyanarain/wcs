<?php 
@session_start();

$page = <<<NXT
<HTML>
<HEAD>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv="expires" content="0">
</HEAD>

<!--
Source-code, Design & Lay-out of this web-site: 
Jan Kirstein � e-mail: jan.kirstein\@jayksoft.com
Copyright � 2012 - 2013 Jan Kirstein. All rights reserved!
-->

$style

<STYLE>
.tds { padding: 0 4 0 4; }
.tdsheader { padding: 0 4 0 4; background: #585858; color: #FFFFFF; font-weight: bold; }
.tdsheader2 { padding: 0 4 0 4; background: #C0C0C0; font-weight: bold; }
</STYLE>

<BODY style="margin: 8;">

<TABLE ID="maintable" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="background: #F8F6F2;">
<TR>
<TD align="center" Valign="middle">

<DIV align="left" style="font-size: 10pt;">
<B>
Utilisation des paramètres Résumés et outil de génération de rapports.
</B>
</DIV>

<P>

<TABLE width="90%" cellspacing="0" cellpadding="4" border="0">
<TR>
<TD align="justify" Valign="top">
<OL>
<Li>Sélectionnez la date de début incluse dans votre recherche en cliquant dessus dans le calendrier affiché à gauche dans les paramètres en haut de cette page.
<Li>Sélectionnez la date de fin incluse dans votre recherche en cliquant dessus dans le calendrier affiché à droite dans les paramètres en haut de cette page.
<Li>Sélectionnez le rapport de synthèse souhaité dans la liste déroulante répertoriant les résumés disponibles.
<Li>Si elle est sélectionnée, l'option 'Trace impounded item', dans la liste déroulante des résumés, ouvre un autre champ de saisie. Voir détails d'utilisation ci-dessous.
<Li>Le rapport de synthèse sera affiché dans les tableaux de cet écran tout en étant également offert pour téléchargement sous la forme d'un fichier texte formaté CSV (en format DOS, tel qu'utilisé par Microsoft «Windows»). Sélectionnez le séparateur de valeurs préféré utilisé dans la sortie du fichier texte CSV dans la zone déroulante intitulée «Détecteur CSV».
<Li>Cliquez sur le bouton "Obtenir un résumé" pour générer le rapport de synthèse souhaité.
</OL>
<P>
L'option '<B>Trace impounded item</B>' dans la liste déroulante 'Résumés' entraîne une recherche de (élément) dans l'ensemble de la table 'Evidence' Serial / spec. Colonne, où des numéros de série des plaques d'immatriculation ou des articles sont saisis ainsi que le numéro de preuve. , Et énumère toute référence d'arrêt où quelque chose dans ce champ correspond à ce qui est entré dans le champ de recherche d'ouverture pour (article). La recherche ne fait pas de distinction entre majuscules et minuscules et sélectionne tous les enregistrements dans lesquels (élément) correspond à une partie de la valeur du champ ainsi qu'à des correspondances exactes. C'est-à-dire que si deux enregistrements de preuves indiquent des numéros, par exemple «UA 143B» et «UA 144B» respectivement, une recherche «UA» ou «UA 14» Ou "3B" énumérera les références d'arrestation uniquement à la première. Et - évidemment - une recherche de "UA14" (sans l'espace entre "UA" et "14") ne renverra rien ...
</TD>
</TR>
</TABLE>


</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<Iframe name="downframe" ID="downframe" width="0" height="0" frameborder="0" SRC="getfile3.php?file=$_docspath/arrestsstats.csv" allowTransparency="true"></Iframe>

</BODY>
</HTML>

NXT;

echo $page;

?>

