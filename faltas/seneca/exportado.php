<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$falta_inicial0 = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
$falta_final0 = "$fecha10[2]-$fecha10[1]-$fecha10[0]";

$faltas0 = "select fecha, hora from FALTAS where date(fecha) >= '$falta_inicial0' and date(fecha) <= '$falta_final0' and claveal = '$claveal' and falta='F' order by fecha desc";
//echo "$faltas0<br>";
$faltas1 = mysqli_query($db_con, $faltas0) or die("No se ha podido abrir la Tabla de Faltas");	
while ($faltas = mysqli_fetch_array($faltas1)) 
{	
$fecha20 = explode("-",$faltas[0]); 
$fecha = "$fecha20[2]/$fecha20[1]/$fecha20[0]";
$hora_al = $faltas[1];

$tramos0 = "select tramo from tramos where hora = '$hora_al'";
//echo $tramos0;
$tramos1 = mysqli_query($db_con, $tramos0);
$tramos2 = mysqli_fetch_array($tramos1) or die("No se ha podido abrir la tabla tramos");
$tramo = $tramos2[0];

$xml.= "
\t\t\t\t\t\t\t\t<FALTA_ASISTENCIA>
\t\t\t\t\t\t\t\t\t<F_FALASI>$fecha</F_FALASI>
\t\t\t\t\t\t\t\t\t<X_TRAMO>$tramo</X_TRAMO>
\t\t\t\t\t\t\t\t\t<C_TIPFAL>I</C_TIPFAL>
\t\t\t\t\t\t\t\t\t<L_DIACOM>N</L_DIACOM>
\t\t\t\t\t\t\t\t</FALTA_ASISTENCIA>";
}
?>