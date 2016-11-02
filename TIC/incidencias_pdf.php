<?php
require('../bootstrap.php');

$get_order = $_GET['order'];

switch ($get_order) {
	case 'fecha' : $order = 'ORDER BY fecha DESC'; break;
	case 'estado' : $order = 'ORDER BY estado ASC, fecha DESC'; break;
	default : $order = 'ORDER BY fecha DESC'; break;
}

require("../pdf/mc_table.php");

class GranPDF extends PDF_MC_Table {
	function Header() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->MultiCell(170, 5, utf8_decode('CONSEJERÍA DE EDUCACIÓN, CULTURA Y DEPORTE'), 0,'R', 0);
		$this->Ln(15);
	}
	function Footer() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../img/pie.jpg', 0, 160, 24, '', 'jpg' );
	}
}

$pdf = new GranPDF('L', 'mm', 'A4');

$pdf->AddFont('NewsGotT','','NewsGotT.php');
$pdf->AddFont('NewsGotT','B','NewsGotTb.php');
$pdf->AddFont('ErasDemiBT','','ErasDemiBT.php');
$pdf->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$pdf->AddFont('ErasMDBT','','ErasMDBT.php');
$pdf->AddFont('ErasMDBT','I','ErasMDBT.php');

$pdf->SetMargins(25, 20, 20);
$pdf->SetDisplayMode('fullpage');

$titulo = "Listado de incidencias TIC - Curso ".$config['curso_actual'];


$pdf->Addpage();

$pdf->SetFont('NewsGotT', 'B', 12);
$pdf->Multicell(0, 5, mb_strtoupper($titulo, 'iso-8859-1'), 0, 'C', 0 );
$pdf->Ln(5);

$pdf->SetFont('NewsGotT', '', 12);

$pdf->Multicell(0, 5, 'Fecha: '.date('d/m/Y'), 0, 'L', 0 );

$pdf->Ln(5);
				
if (mysqli_num_rows($result)) {

}
$pdf->SetWidths(array(25, 15, 15, 120, 60, 15));
$pdf->SetFont('NewsGotT', 'B', 12);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(61, 61, 61);

$pdf->Row(array('Fecha', 'Rec.', 'Ord.', 'Incidencia', 'Profesor/a', 'Estado'), 0, 6);

$result = mysqli_query($db_con, "SELECT parte, nincidencia, carro, nserie, fecha, hora, profesor, descripcion, estado FROM partestic WHERE fecha >= '".$config['curso_inicio']."' $order");

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('NewsGotT', '', 12);

while ($row = mysqli_fetch_array($result)) {
	if($row['estado'] == 'activo' || $row['estado'] == 'Activo') $estado = 'PEND.';
	if($row['estado'] == 'solucionado' || $row['estado'] == 'Solucionado') $estado = 'SOLUC.';
	$pdf->Row(array($row['fecha'], $row['carro'], $row['nserie'], $row['descripcion'], $row['profesor'], $estado), 1, 6);	
}

mysqli_free_result($result);


// SALIDA

$pdf->Output();

?>