<?php
session_start();
include("../../config.php");
include_once('../../config/version.php');

// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/salir.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/salir.php');
		exit();
	}
}

if($_SESSION['cambiar_clave']) {
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/clave.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/clave.php');
		exit();
	}
}


if ((stristr($_SESSION['cargo'],'1') == false) && (stristr($_SESSION['cargo'],'2') == false)) {
	die ("<h1>FORBIDDEN</h1>");
}

if (isset($_POST['curso'])) $unidad = $_POST['curso'];
if (isset($_POST['evaluacion'])) $evaluacion = $_POST['evaluacion'];

if ($_SERVER['SERVER_NAME'] == 'iesantoniomachado.es') {
	$evaluaciones = array(
		'IN1' => 'Intermedia 1 (Febrero)',
		'IN2' => 'Intermedia 2 (Mayo)'
	);
}
else {
	$evaluaciones = array(
		'1EV' => '1� Evaluaci�n',
		'2EV' => '2� Evaluaci�n',
		'3EV' => '3� Evaluaci�n',
		'Ord' => 'Ordinaria',
		'FFP' => 'Final FP',
		'Ext' => 'Extraordinaria',
		'FE1' => 'Final Excepcional 1� Convocatoria',
		'5CV' => '5� Convocatoria Extraordinaria de Evaluaci�n',
		'OT1' => 'Obtenci�n t�tulo ESO (Primer a�o)',
		'FE2' => 'Final Excepcional 2� Convocatoria',
		'OT2' => 'Obtenci�n t�tulo ESO (Segundo a�o)',
		'EP1' => 'Evaluaci�n de pendientes 1� Convovatoria',
		'EVI' => 'Evaluaci�n inicial',
		'EP2' => 'Evaluaci�n de pendientes 2� Convovatoria',
	);
}

if (!$unidad) {
	die ("<h1>FORBIDDEN</h1>");
}


require("../../pdf/fpdf.php");

// Variables globales para el encabezado y pie de pagina
$GLOBALS['CENTRO_NOMBRE'] = $nombre_del_centro;
$GLOBALS['CENTRO_DIRECCION'] = $direccion_del_centro;
$GLOBALS['CENTRO_CODPOSTAL'] = $codigo_postal_del_centro;
$GLOBALS['CENTRO_LOCALIDAD'] = $localidad_del_centro;
$GLOBALS['CENTRO_TELEFONO'] = $telefono_del_centro;
$GLOBALS['CENTRO_FAX'] = $fax_del_centro;
$GLOBALS['CENTRO_CORREO'] = $email_del_centro;
$GLOBALS['CURSO_ACTUAL'] = $curso_actual;


if(substr($codigo_postal_del_centro,0,2)=="04") $GLOBALS['CENTRO_PROVINCIA'] = 'Almer�a';
if(substr($codigo_postal_del_centro,0,2)=="11") $GLOBALS['CENTRO_PROVINCIA'] = 'C�diz';
if(substr($codigo_postal_del_centro,0,2)=="14") $GLOBALS['CENTRO_PROVINCIA'] = 'C�rdoba';
if(substr($codigo_postal_del_centro,0,2)=="18") $GLOBALS['CENTRO_PROVINCIA'] = 'Granada';
if(substr($codigo_postal_del_centro,0,2)=="21") $GLOBALS['CENTRO_PROVINCIA'] = 'Huelva';
if(substr($codigo_postal_del_centro,0,2)=="23") $GLOBALS['CENTRO_PROVINCIA'] = 'Ja�n';
if(substr($codigo_postal_del_centro,0,2)=="29") $GLOBALS['CENTRO_PROVINCIA'] = 'M�laga';
if(substr($codigo_postal_del_centro,0,2)=="41") $GLOBALS['CENTRO_PROVINCIA'] = 'Sevilla';

# creamos la clase extendida de fpdf.php 
class GranPDF extends FPDF {
	function Header() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJER�A DE EDUCACI�N, CULTURA Y DEPORTE',0,1);
		$this->SetFont('ErasMDBT','I',10);
		$this->Cell(75);
		$this->Cell(80,5,$GLOBALS['CENTRO_NOMBRE'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
	function Footer() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/pie.jpg', 0, 245, 25, '', 'jpg' );
		$this->SetY(275);
		$this->SetFont('ErasMDBT','',8);
		$this->Cell(75);
		$this->Cell(80,4,$GLOBALS['CENTRO_DIRECCION'].'. '.$GLOBALS['CENTRO_CODPOSTAL'].', '.$GLOBALS['CENTRO_LOCALIDAD'].' ('.$GLOBALS['CENTRO_PROVINCIA'] .')',0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Telf: '.$GLOBALS['CENTRO_TELEFONO'].'   Fax: '.$GLOBALS['CENTRO_FAX'],0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Correo-e: '.$GLOBALS['CENTRO_CORREO'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
}


# creamos el nuevo objeto partiendo de la clase ampliada
$MiPDF = new GranPDF ( 'P', 'mm', 'A4' );

$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');

$MiPDF->SetMargins(25, 20, 20);

$titulo = "Bolet�n de calificaciones";


$result = mysqli_query($db_con, "SELECT * FROM alma WHERE unidad='$unidad'");
if (!mysqli_num_rows($result)) {
$result = mysqli_query($db_con, "SELECT * FROM alma WHERE unidad='".substr($unidad, 0, -1)."'");
}

$i = 0;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	
	// VARIABLES
	$alumno = $row['APELLIDOS'].', '.$row['NOMBRE'];
	$numexp = $row['NUMEROEXPEDIENTE'];
	$curso  = $row['CURSO'];
	$unidad = $row['UNIDAD'];
	
	$padre = $row['NOMBRETUTOR'].' '.$row['PRIMERAPELLIDOTUTOR'].' '.$row['SEGUNDOAPELLIDOTUTOR'];
	$direccion = $row['DOMICILIO'];
	$codpostal = $row['CODPOSTAL'];
	$localidad = $row['LOCALIDAD'];
	$provincia = $row['PROVINCIARESIDENCIA'];
	
	// Consultamos el tutor del grupo
	$result1 = mysqli_query($db_con, "SELECT TUTOR FROM FTUTORES WHERE unidad='$unidad'");
	$tutor = mysqli_fetch_array($result1);
	mysqli_free_result($result1);
	
	
	// CREAMOS UNA NUEVA PAGINA
	$MiPDF->Addpage();
	
	// INFORMACION DE LA CARTA
	$MiPDF->SetY(35);
	$MiPDF->SetFont('NewsGotT', 'BU', 10);
	$MiPDF->Multicell(0, 5, mb_strtoupper($titulo, 'iso-8859-1'), 0, 'C', 0 );
	$MiPDF->Ln(10);
	
	$MiPDF->SetFont ( 'NewsGotT', 'B', 10 );
	$MiPDF->Cell(25, 5, 'Alumno/a:', 0, 0, 'R', 0 );
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(50, 5, $alumno, 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $padre, 0, 1, 'L', 0 );
	$MiPDF->SetFont ( 'NewsGotT', 'B', 10 );
	$MiPDF->Cell(25, 5, 'N�mero exp.:', 0, 0, 'R', 0 );
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(50, 5, $numexp, 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $direccion, 0, 1, 'L', 0 );
	$MiPDF->SetFont ( 'NewsGotT', 'B', 10 );
	$MiPDF->Cell(25, 5, 'Curso:', 0, 0, 'R', 0 );
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(50, 5, $curso, 0, 0, 'L', 0 );
	$MiPDF->Cell(0, 5, $localidad.' - '.$codpostal.' '.mb_strtoupper($provincia, 'iso-8859-1'), 0, 1, 'L', 0 );
	$MiPDF->SetFont ( 'NewsGotT', 'B', 10 );
	$MiPDF->Cell(25, 5, 'Unidad:', 0, 0, 'R', 0 );
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(50, 5, $unidad, 0, 1, 'L', 0 );
	$MiPDF->SetFont ( 'NewsGotT', 'B', 10 );
	$MiPDF->Cell(25, 5, 'Convocatoria:', 0, 0, 'R', 0 );
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(75, 5, $evaluacion.' ('.$evaluaciones[$evaluacion].')', 0, 1, 'L', 0 );
	$MiPDF->SetFont ( 'NewsGotT', 'B', 10 );
	$MiPDF->Cell(25, 5, 'A�o acad�mico:', 0, 0, 'R', 0 );
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(50, 5, $GLOBALS['CURSO_ACTUAL'], 0, 1, 'L', 0 );
	$MiPDF->Ln(10);
	
	// CUERPO DE LA CARTA
	$MiPDF->SetFont('NewsGotT', 'B', 10);
	$MiPDF->Multicell(0, 5, mb_strtoupper('E V A L U A C I � N', 'iso-8859-1'), 0, 'C', 0 );
	$MiPDF->Ln(5);
	
	$MiPDF->SetFillColor(239,240,239);
	$MiPDF->Cell(75, 5, 'M A T E R I A S', 1, 0, 'C', 1 );
	$MiPDF->Cell(15, 5, $evaluacion, 1, 1, 'C', 1 );
	
	$MiPDF->SetFont('NewsGotT', '', 10);
	
	// OBTENEMOS LAS ASIGNATURAS
	$result_asig = mysqli_query($db_con, "SELECT DISTINCT a_asig, asig, c_asig FROM horw WHERE a_grupo='$unidad' AND nivel <> '' AND n_grupo <> '' AND a_asig NOT LIKE '%TUT%' AND a_asig <> 'PT' ORDER BY asig ASC");
	
	while ($row_asig = mysqli_fetch_array($result_asig)) {
		
		$MiPDF->Cell(75, 5, $row_asig['asig'], 1, 0, 'L', 0 );
		
		// OBTENEMOS LAS CALIFICACIONES
		$result_calif = mysqli_query($db_con, "SELECT calificaciones FROM evaluaciones WHERE unidad='$unidad' AND evaluacion='$evaluacion' AND asignatura='".$row_asig['c_asig']."'");
		
		if (mysqli_num_rows($result_calif)) {
			$row_calif = mysqli_fetch_array($result_calif);
			$calificaciones = unserialize($row_calif['calificaciones']);
			
			$MiPDF->Cell(15, 5, $calificaciones[$i]['nota'], 1, 1, 'C', 0);
		}
		else {
			$MiPDF->Cell(15, 5, '', 1, 1, 'C', 0);
		}
	}
	
	$MiPDF->Ln(5);
	$MiPDF->SetFont('NewsGotT', 'B', 10);
	$MiPDF->Cell(90, 5, 'Observaciones:', 0, 1, 'L', 0 );
	
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Multicell( 0, 5, '' , 0, 'L', 0 );
	$MiPDF->Ln(10);
	

	//FIRMAS
	$MiPDF->Cell(90, 5, 'Sello del Centro', 0, 0, 'L', 0 );
	$MiPDF->Cell(55, 5, 'Les saludo cordialmente,', 0, 1, 'L', 0 );
	$MiPDF->Cell(55, 20, '', 0, 0, 'L', 0 );
	$MiPDF->Cell(55, 20, '', 0, 1, 'L', 0 );
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell(90, 5, 'Firma del Padre, Madre, o Tutor/a', 0, 0, 'L', 0 );
	$MiPDF->Cell(55, 5, 'Tutor/a: '.mb_convert_case($tutor['TUTOR'], MB_CASE_TITLE, "iso-8859-1"), 0, 1, 'L', 0 );
	
	$i++;
}

$MiPDF->Output();
?>