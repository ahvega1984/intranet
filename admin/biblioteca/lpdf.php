<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 'c'));

require_once('../../pdf/class.ezpdf.php');
$pdf = new Cezpdf('a4');
$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
# hasta aquÃ­ lo del pdf
$options_center = array(
				'justification' => 'center'
			);
$options_right = array(
				'justification' => 'right'
			);
$options_left = array(
				'justification' => 'left'
			);

$extra_orden = "";

if (isset($_GET['fecha_moroso'])) {
	$extra_orden = "order by devolucion asc, curso, apellidos ";
}
else{
	$extra_orden = "order by curso, apellidos, devolucion";
}

$fecha_act = date('Y-m-d');	
$lista=mysqli_query($db_con, "select curso,apellidos,nombre,ejemplar,devolucion, id from morosos $extra_orden") or die ("error query lista");
while($datatmp = mysqli_fetch_array($lista)) {
  if(strstr($datatmp[0],"Monter")==TRUE){$datatmp[0]="Prof.";}
	$data[] = array(
				'id'=>$datatmp[5],
				'curso'=>utf8_decode($datatmp[0]),
				'nombre'=>utf8_decode($datatmp[1]).', '.utf8_decode($datatmp[2]),
				'ejemplar'=>utf8_decode($datatmp[3]),
				'devol'=>utf8_decode($datatmp[4])
				);
											}
$titles = array(
				'id'=>'<b>Id</b>',
				'curso'=>'<b>Curso</b>',
				'nombre'=>'<b>Alumno/a</b>',
				'ejemplar'=>'<b>Ejemplar</b>',
				'devol'=>utf8_decode('<b>Devolución</b>')

			);
$options = array(
				'showHeadings'=>1,
				'shadeCol'=>array(0.9,0.9,0.9),
				'justification'=>'center',
				'xPos' => 'center',
				'xOrientation'=>'center',
				'fontSize' => 8,
				'width'=>475,
				// justificacion y tamaÃ±o de columnas de manera independiente
				'cols'=>array(
"id" => array('justification'=>'center', 'width' => '48'),
"curso" => array('justification'=>'center', 'width' => '35'),
"nombre" => array('justification'=>'left'),
"ejemplar" => array('justification'=>'left'),
"devol" => array('justification'=>'center', 'width' => '65'))
	
			);


if ($_SERVER['SERVER_NAME'] == 'iesmonterroso.org') {
	$biblio= utf8_decode("Biblioteca Julio Pérez Santander");
}
else {
	$biblio= "Biblioteca";
}
$txttit= $biblio.'. 
' . utf8_decode($config['centro_denominacion']).". Curso ".$config['curso_actual'].".\n";
$txttit.= "Lista de morosos con fecha ". $fecha ."\n";
	
$pdf->ezText($txttit, 13, $options_center);
$pdf->ezTable($data, $titles,'', $options);
//$pdf->ezText("\n\n\n", 10);


$pdf->ezStream();


?>
