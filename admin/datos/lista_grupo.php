<?php 
require('../../bootstrap.php');

if (isset($_POST['unidad'])) {$unidad = $_POST['unidad'];} elseif (isset($_GET['unidad'])) {$unidad = $_GET['unidad'];} else{$unidad="";}
if (isset($_POST['nombre'])) {$nombre = $_POST['nombre'];} elseif (isset($_GET['nombre'])) {$nombre = $_GET['nombre'];} else{$nombre="";}
if (isset($_POST['apellidos'])) {$apellidos = $_POST['apellidos'];} elseif (isset($_GET['apellidos'])) {$apellidos = $_GET['apellidos'];} else{$apellidos="";}
if (isset($_GET['clave_al'])) {$clave_al = $_GET['clave_al'];} else{$clave_al="";}

# para el pdf
require_once('../../pdf/class.ezpdf.php');
$pdf=new Cezpdf('a4','landscape');

$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);

$options_center = array(
				'justification' => 'center'
			);
$options_right = array(
				'justification' => 'right'
			);
$options_left = array(
				'justification' => 'left'
					);

// Número de grupo para los saltos de página
$numg=0;
$grupo=$_POST['unidad'];
$n=count($grupo);
foreach ($grupo as $grupo1)
{ 
$numg++;
//$g=$grupo[$i];

$sqldatos="SELECT CONCAT(alma.apellidos,', ',alma.nombre) AS alumno, claveal, fecha, matriculas, padre, domicilio, localidad, telefonourgencia, telefono FROM alma WHERE unidad='".$grupo1."' ORDER BY apellidos ASC, nombre ASC";
//echo $sqldatos;
$lista= mysqli_query($db_con, $sqldatos) or die (mysqli_error($db_con));
$num=0;
unset($data);
$nc = 0;
while($datatmp = mysqli_fetch_array($lista)) { 
	$nc++;
	$tels = trim($datatmp['telefono']." | ".$datatmp['telefonourgencia']);
	if ($datatmp['matriculas']>1) {
		$repite="Sí";
	}
	else{
		$repite="No";
	}

	$exp_fecha = explode('-',cambia_fecha($datatmp['fecha']));
	$fecha_ncto = $exp_fecha[2].'/'.$exp_fecha[1].'/'.$exp_fecha[0];

	$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp['alumno']),
				'nie'=>utf8_decode($datatmp['claveal']),
				'fecha'=>$fecha_ncto,
				'Repite'=>utf8_decode($repite),
				'Tutor'=>utf8_decode($datatmp['padre']),
				'Domicilio'=>utf8_decode($datatmp['domicilio'].'. '.$datatmp['localidad']),
				'Telefonos'=>$tels
				);
}
$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno/a</b>',
				'nie'=>'<b>NIE</b>',
				'fecha'=>'<b>Fecha ncto.</b>',
				'Repite'=>'<b>Rep.</b>',
				'Tutor'=>'<b>Padre / madre</b>',
				'Domicilio'=>'<b>Domicilio</b>',
				'Telefonos'=>utf8_decode('<b>Teléfono(s)</b>')
			);
$options = array(
				'showLines'=> 2,
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'fontSize' => 8,
				'width'=>775
			);
$txttit = "<b>Datos del grupo ".utf8_decode($grupo1)."</b>\n";
	
$pdf->ezText($txttit, 14,$options_center);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezText("\n\n", 4);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,$options_left);
#####  Hasta aquí la lista con cuadrícula

//echo $numg;
if ($numg!=$n){$pdf->ezNewPage();unset($data);unset($titles);}
} #del for
$pdf->ezStream();
?>
