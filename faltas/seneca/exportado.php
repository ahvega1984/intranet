<?php
$falta_inicial0 = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
$falta_final0 = "$fecha10[2]-$fecha10[1]-$fecha10[0]";

$faltas0 = "select fecha, hora from FALTAS where date(fecha) >= '$falta_inicial0' and date(fecha) <= '$falta_final0' and claveal = '$claveal' and falta='F' order by fecha desc";
//echo "$faltas0<br>";
$faltas1 = mysql_query($faltas0) or die("No se ha podido abrir la Tabla de Faltas");	
while ($faltas = mysql_fetch_array($faltas1)) 
{	
$fecha20 = explode("-",$faltas[0]); 
$fecha = "$fecha20[2]/$fecha20[1]/$fecha20[0]";
$hora_al = $faltas[1];
if ($hora_al>3) {
	$hora_al+=1;
}

$tramos0 = "select tramo from tramos where hora = '$hora_al'";
//echo $tramos0;
$tramos1 = mysql_query($tramos0);
$tramos2 = mysql_fetch_array($tramos1) or die("No se ha podido abrir la tabla tramos");
$tramo = $tramos2[0];

$xml.= "	
	  <FALTA_ASISTENCIA>
            <F_FALASI>$fecha</F_FALASI>
            <X_TRAMO>$tramo</X_TRAMO>
            <C_TIPFAL>I</C_TIPFAL>
            <L_DIACOM>N</L_DIACOM>
          </FALTA_ASISTENCIA>";
}
?>

ero'];}elseif (isset($_POST['numero'])) {$numero = $_POST['numero'];}else{$numero="";}
// PDF
$fecha2 = date('Y-m-d');
$hoy = formatea_fecha($fecha);
include("../../pdf/fpdf.php");
define('FPDF_