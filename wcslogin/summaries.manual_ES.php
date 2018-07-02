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
Uso de la configuración de Resúmenes y herramienta de generación de informes.
</B>
</DIV>

<P>

<TABLE width="90%" cellspacing="0" cellpadding="4" border="0">
<TR>
<TD align="justify" Valign="top">
<OL>
<Li>Seleccione la fecha de inicio incluida en su búsqueda haciendo clic en el calendario que se muestra a la izquierda en la configuración de la parte superior de esta página.
<Li>Seleccione la fecha de finalización incluida en su búsqueda haciendo clic en el calendario que se muestra a la derecha en la configuración de la parte superior de esta página.
<Li>Seleccione el informe de resumen deseado en el cuadro desplegable que muestra los resúmenes disponibles.
<Li>Si está seleccionada, la opción "Trace impounded item", en el menú desplegable de resúmenes, abre otro campo de entrada. Vea los detalles de uso a continuación.
<Li>El informe de resúmenes se mostrará en las tablas de esta pantalla mientras que también se ofrece para su descarga en forma de un archivo de texto con formato CSV (en formato DOS, como lo utiliza Microsoft Windows). Seleccione el separador de valores preferido utilizado en la salida del archivo de texto CSV en el cuadro desplegable denominado "Delimitador CSV".
<Li>Haga clic en el botón "Obtener resumen" para generar el informe de resumen deseado.
</OL>
<P>
La opción '<B>Trace impounded item</B>' en el cuadro desplegable 'Resúmenes' hace que una búsqueda de (elemento) en la totalidad de la tabla de Evidencia 'Serial / spec.' , Donde se introducen cosas como los números de serie de la placa o de los artículos, así como el número de prueba. , Y enumera cualquier referencia de detención donde cualquier cosa en este campo coincida con lo que se ingrese en el campo de búsqueda inicial para (elemento). La búsqueda no distingue entre mayúsculas y minúsculas y seleccionará cualquier registro donde (elemento) coincida con una parte del valor del campo, así como coincidencias exactas. Es decir, si dos registros de evidencia muestran números, digamos 'UA 143B' y 'UA 144B' respectivamente, una búsqueda de "UA" o "UA 14" incluirá referencias de arresto para ambos, mientras que una búsqueda de "143", "43B" O "3B" enumerará las referencias de arresto sólo a la primera. Y - obviamente - una búsqueda de "UA14" (sin el espacio entre "UA" y "14") no devolverá nada ...
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

