<?php 
require('../../bootstrap.php');
require("../../pdf/mc_table.php");

if (isset($_GET['unidad'])) {
	$unidad = $_GET['unidad'];
}

$result = mysqli_query($db_con, "SELECT tutor FROM FTUTORES WHERE unidad='$unidad'");
$row = mysqli_fetch_array($result);
$tutor = $row['tutor'];
mysqli_free_result($result);


// Variables globales para el encabezado y pie de pagina
$GLOBALS['CENTRO_NOMBRE'] = $config['centro_denominacion'];
$GLOBALS['CENTRO_DIRECCION'] = $config['centro_direccion'];
$GLOBALS['CENTRO_CODPOSTAL'] = $config['centro_codpostal'];
$GLOBALS['CENTRO_LOCALIDAD'] = $config['centro_localidad'];
$GLOBALS['CENTRO_TELEFONO'] = $config['centro_telefono'];
$GLOBALS['CENTRO_FAX'] = $config['centro_fax'];
$GLOBALS['CENTRO_CORREO'] = $config['centro_email'];
$GLOBALS['CENTRO_PROVINCIA'] = $config['centro_provincia'];

class GranPDF extends PDF_MC_Table {
	function Header() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJERÍA DE EDUCACIÓN, CULTURA Y DEPORTE',0,1);
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


$MiPDF = new GranPDF('P', 'mm', 'A4');
$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');

$MiPDF->SetMargins(25, 20, 20);
$MiPDF->SetDisplayMode('fullpage');

$titulo = "Listado de clase";

$MiPDF->Addpage();
$MiPDF->SetY(35);

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Multicell(0, 5, mb_strtoupper($titulo, 'UTF-8'), 0, 'C', 0 );
$MiPDF->Ln(3);

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(15, 5, 'Unidad: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(80, 5, $unidad, 0, 0, 'L', 0 );

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(14, 5, 'Fecha: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(22, 5, date('d/m/Y'), 0, 1, 'L', 0 );

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(15, 5, 'Tutor/a: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(64, 5, nomprofesor($tutor), 0, 1, 'L', 0 );

$MiPDF->Ln(3);

$MiPDF->SetWidths(array(8, 65, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9));
$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->SetTextColor(255, 255, 255);
$MiPDF->SetFillColor(61, 61, 61);

$MiPDF->Row(array('NC', 'Alumno/a', '', '', '', '', '', '', '', '', '', ''), 0, 6);	

$result = mysqli_query($db_con, "SELECT nc, apellidos, nombre FROM FALUMNOS WHERE unidad='$unidad' ORDER BY nc ASC");

$MiPDF->SetTextColor(0, 0, 0);
$MiPDF->SetFont('NewsGotT', '', 12);

$MiPDF->SetFillColor(239,240,239);

$fila = 1;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	if ($fila % 2 == 0) $fill = 'DF';
	else $fill = '';
	
	$MiPDF->Row(array($row['nc'], $row['apellidos'].', '.$row['nombre'], '', '', '', '', '', '', '', '', '', ''), $fill, 6);	
	
	$fila++;
}

mysqli_free_result($result);

$MiPDF->Output();
?>