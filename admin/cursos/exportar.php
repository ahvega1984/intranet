<?php 
require('../../bootstrap.php');
require_once('../../lib/phpexcel/PHPExcel.php');

if (isset($_POST['unidad'])) $unidad = $_POST['unidad'];

// COMPROBAMOS SI ES UN PMAR
$esPMAR = (stristr($unidad, ' (PMAR)') == true) ? 1 : 0;
if ($esPMAR) {
	$unidad = str_ireplace(' (PMAR)', '', $unidad);
}

if (isset($_POST['datos']) && $_POST['datos'] == 1) {
	if ($esPMAR) {
		$result_codasig_pmar = mysqli_query($db_con, "SELECT codigo FROM materias WHERE grupo = '".$unidad."' AND abrev LIKE '%**%' LIMIT 1");
		$row_codasig_pmar = mysqli_fetch_array($result_codasig_pmar);
		$codasig_pmar = $row_codasig_pmar['codigo'];
		$result = mysqli_query($db_con, "SELECT apellidos, nombre, unidad, claveal, fecha, padre, domicilio, localidad, provinciaresidencia, telefono, telefonourgencia FROM alma WHERE unidad = '".$unidad."' AND combasi LIKE '%$codasig_pmar%' ORDER BY apellidos ASC, nombre ASC");			
	}
	else {
		$result = mysqli_query($db_con, "SELECT apellidos, nombre, unidad, claveal, fecha, padre, domicilio, localidad, provinciaresidencia, telefono, telefonourgencia FROM alma WHERE unidad = '".$unidad."' ORDER BY apellidos ASC, nombre ASC");			
	}
}
else {
	if ($esPMAR) {
		$result_codasig_pmar = mysqli_query($db_con, "SELECT codigo FROM materias WHERE grupo = '".$unidad."' AND abrev LIKE '%**%' LIMIT 1");
		$row_codasig_pmar = mysqli_fetch_array($result_codasig_pmar);
		$codasig_pmar = $row_codasig_pmar['codigo'];
		$result = mysqli_query($db_con, "SELECT apellidos, nombre, unidad FROM alma WHERE unidad = '".$unidad."' AND combasi LIKE '%$codasig_pmar%' ORDER BY apellidos ASC, nombre ASC");			
	}
	else {
		$result = mysqli_query($db_con, "SELECT apellidos, nombre, unidad FROM alma WHERE unidad = '".$unidad."' ORDER BY apellidos ASC, nombre ASC");				
	}
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($config['centro_denominacion'])
							 ->setLastModifiedBy($pr)
							 ->setTitle($unidad)
							 ->setSubject("Información de la unidad ".$unidad)
							 ->setDescription("Este archivo incluye información de carácter personal y la relación de alumnos/as de la unidad ".$unidad." del ".$config['centro_denominacion'])
							 ->setKeywords("informacion ".$unidad)
							 ->setCategory("Información de carácter personal");


$encabezado_idoceo = '';
if (isset($_POST['formato']) && $_POST['formato'] == 'idoceo') {
	$encabezado_idoceo = '!';
}

// Fila comienzo de escritura
$row_excel = 1;

// Encabezado
if (isset($_POST['datos']) && $_POST['datos'] == 1) {
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$row_excel, $encabezado_idoceo.'NC')
		->setCellValue('B'.$row_excel, $encabezado_idoceo.'Alumno/a')
		->setCellValue('C'.$row_excel, $encabezado_idoceo.'Unidad')
		->setCellValue('D'.$row_excel, $encabezado_idoceo.'NIE')
		->setCellValue('E'.$row_excel, $encabezado_idoceo.'Fecha de nacimiento')
		->setCellValue('F'.$row_excel, $encabezado_idoceo.'Representante legal')
		->setCellValue('G'.$row_excel, $encabezado_idoceo.'Domicilio')
		->setCellValue('H'.$row_excel, $encabezado_idoceo.'Localidad')
		->setCellValue('I'.$row_excel, $encabezado_idoceo.'Provincia')
		->setCellValue('J'.$row_excel, $encabezado_idoceo.'Teléfono')
		->setCellValue('K'.$row_excel, $encabezado_idoceo.'Teléfono urgencia');
}
else {
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$row_excel, $encabezado_idoceo.'NC')
		->setCellValue('B'.$row_excel, $encabezado_idoceo.'Alumno/a')
		->setCellValue('C'.$row_excel, $encabezado_idoceo.'Unidad');
}

// Datos
$nc = 0;
while ($row = mysqli_fetch_array($result)) {
	$row_excel++;
	$nc++;

	if (isset($_POST['datos']) && $_POST['datos'] == 1) {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$row_excel, $nc)
			->setCellValue('B'.$row_excel, $row['apellidos'].', '.$row['nombre'])
			->setCellValue('C'.$row_excel, $row['unidad'])
			->setCellValue('D'.$row_excel, $row['claveal'])
			->setCellValue('E'.$row_excel, $row['fecha'])
			->setCellValue('F'.$row_excel, $row['padre'])
			->setCellValue('G'.$row_excel, $row['domicilio'])
			->setCellValue('H'.$row_excel, $row['localidad'])
			->setCellValue('I'.$row_excel, $row['provinciaresidencia'])
			->setCellValue('J'.$row_excel, $row['telefono'])
			->setCellValue('K'.$row_excel, $row['telefonourgencia']);
	}
	else {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$row_excel, $nc)
			->setCellValue('B'.$row_excel, $row['apellidos'].', '.$row['nombre'])
			->setCellValue('C'.$row_excel, $row['unidad']);
	}

}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

if (isset($_POST['formato'])) {
	switch ($_POST['formato']) {
		case 'csv' : case 'idoceo' : 
			$mimetype = 'text/csv';
			$filename = $unidad.'.csv';
			$iofactory = 'CSV';

			break;
	
		case 'ods' : 
			$mimetype = 'application/vnd.oasis.opendocument.spreadsheet';
			$filename = $unidad.'.ods';
			$iofactory = 'OpenDocument';

			break;
		
		case 'xlsx' : default:
			$mimetype = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
			$filename = $unidad.'.xlsx';
			$iofactory = 'Excel2007';

			break;
	}
}
else {
	$mimetype = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	$filename = $unidad.'.xlsx';
	$iofactory = 'Excel2007';
}

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: '.$mimetype);
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $iofactory);
$objWriter->save('php://output');
exit;
