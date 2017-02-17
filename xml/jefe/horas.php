<?php
require('../../bootstrap.php');

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
$dias = array('1' => 'Lunes','2' => 'Martes','3' => 'Miercoles','4' => 'Jueves','5' => 'Viernes');
foreach ($dias as $i => $nombre_dia) {
	unset($data);

$sqldatos = "select distinct prof from horw where dia='$i' order by prof";
$lista= mysqli_query($db_con, $sqldatos );

while($datatmp = mysqli_fetch_array($lista)) {
	for ($z=1; $z < 7; $z++) { 
		${c.$z}="";
		$asig = mysqli_query($db_con,"select distinct a_asig, a_aula from horw where prof='$datatmp[0]' and dia='$i' and hora='$z'");
		while($dato_asig = mysqli_fetch_array($asig)){
			${c.$z}=$dato_asig[0].'
'.$dato_asig[1];
		}
	}
	
	$c7 = ' 	
	';
	$profes = explode(', ',$datatmp[0]);
	$profe = $profes[0].'
'.$profes[1];
	$profe=ucwords(mb_strtolower($profe));

	$data[] = array(
				'nombre'=>utf8_decode($profe),
				'c1'=>$c1,
				'c2'=>$c2,
				'c3'=>$c3,
				'c4'=>$c4,
				'c5'=>$c5,
				'c6'=>$c6,
				'c7'=>$c7
				);
			}
	$titles = array(
				'nombre'=>'<b>PROFESOR</b>',
				'c1'=>'1  ',
				'c2'=>'2  ',
				'c3'=>'3  ',
				'c4'=>'4  ',
				'c5'=>'5  ',
				'c6'=>'6  ',
				'c7'=>'      <b>FIRMA</b>        '
				);
	$options = array(
				'size' => array(280,100),
				'textCol' => array(0.2,0.2,0.2),
				'innerLineThickness'=>0.5,
				'outerLineThickness'=>0.7,
				'showLines'=> 2,
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>500,
				'rowGap'=>5
			);

$txttit= "                           Hoja de Firmas del Profesorado. ".'                     '.strtoupper($nombre_dia)."  ....................\n";
$pdf->ezText($txttit, 13,$options_center);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezText("\n\n\n", 10);
$pdf->ezNewPage();
}

$pdf->ezStream();
?>
