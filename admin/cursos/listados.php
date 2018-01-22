<?php 
require('../../bootstrap.php');
require("../../pdf/mc_table.php");

// Variables globales para el encabezado y pie de pagina
$GLOBALS['CENTRO_NOMBRE'] = $config['centro_denominacion'];
$GLOBALS['CENTRO_DIRECCION'] = $config['centro_direccion'];
$GLOBALS['CENTRO_CODPOSTAL'] = $config['centro_codpostal'];
$GLOBALS['CENTRO_LOCALIDAD'] = $config['centro_localidad'];
$GLOBALS['CENTRO_TELEFONO'] = $config['centro_telefono'];
$GLOBALS['CENTRO_FAX'] = $config['centro_fax'];
$GLOBALS['CENTRO_CORREO'] = $config['centro_email'];
$GLOBALS['CENTRO_PROVINCIA'] = $config['centro_provincia'];
$GLOBALS['CURSO_ACTUAL'] = $config['curso_actual'];

class GranPDF extends PDF_MC_Table {
	function Header() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJERÍA DE EDUCACIÓN',0,1);
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

$todasUnidades = (isset($_POST['todasUnidades']) && $_POST['todasUnidades'] == 1) ? 1 : 0;

$unidades = array();

if (isset($_GET['unidad']) || isset($_POST['unidad'])) {
	if (isset($_GET['unidad'])) $unidades[] = $_GET['unidad'];
	else $unidades = $_POST['unidad'];
}
else {
	if (acl_permiso($carg, array('1','7')) || $todasUnidades == 1) {
		$result_unidades = mysqli_query($db_con, "SELECT DISTINCT nomunidad FROM unidades ORDER BY nomunidad ASC");
		while ($row_unidades = mysqli_fetch_array($result_unidades)) $unidades[] = $row_unidades['nomunidad'];
		mysqli_free_result($result_unidades);
	}
	else {
		$result_unidades = mysqli_query($db_con, "SELECT DISTINCT grupo AS nomunidad FROM profesores WHERE profesor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY grupo ASC");
		$result_unidades_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo JOIN profesores AS p ON u.nomunidad = p.grupo WHERE m.abrev LIKE '%**%' AND p.profesor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY u.nomunidad ASC");											
		while ($row_unidades = mysqli_fetch_array($result_unidades)) $unidades[] = $row_unidades['nomunidad'];
		mysqli_free_result($result_unidades);
		while ($row_unidades = mysqli_fetch_array($result_unidades_pmar)) $unidades[] = $row_unidades['nomunidad'];
		mysqli_free_result($result_unidades_pmar);
		asort($unidades);
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

foreach ($unidades as $unidad) {
	
	// COMPROBAMOS SI ES UN PMAR
	$esPMAR = (stristr($unidad, ' (PMAR)') == true) ? 1 : 0;
	if ($esPMAR) {
		$unidad = str_ireplace(' (PMAR)', '', $unidad);
	}

	// Control en la obtención del listado. Solo los profesores que imparten materia en la unidad pueden visualizar el listado.
	if (! acl_permiso($carg, array('1','7')) && $todasUnidades != 1) {
		$result_unidades = mysqli_query($db_con, "SELECT * FROM profesores WHERE profesor='".$_SESSION['profi']."' AND grupo = '".$unidad."'");
		if (! mysqli_num_rows($result_unidades)) die ('FORBIDDEN');
	}

	// Comprobamos y obtenemos los alumnos del profesor en su asignatura
	$result_alumnos_profesor = mysqli_query($db_con, "SELECT alumnos FROM grupos WHERE profesor = '".$_SESSION['profi']."' AND curso = '".$unidad."' LIMIT 1");
	if (mysqli_num_rows($result_alumnos_profesor)) {
		$row_alumnos_profesor = mysqli_fetch_array($result_alumnos_profesor);

		$row_alumnos_profesor['alumnos'] = rtrim($row_alumnos_profesor['alumnos'], ',');
		$alumnos_profesor = explode(',', $row_alumnos_profesor['alumnos']);

		// Sustituimos el NC de la tabla FALUMNOS por el NIE del alumno
		$alumnos_profesor_por_claveal = array();
		foreach ($alumnos_profesor as $alumno_profesor_nc) {
			$result_falumnos = mysqli_query($db_con, "SELECT CLAVEAL FROM FALUMNOS WHERE NC = '".$alumno_profesor_nc."' AND unidad = '".$unidad."' LIMIT 1");
			$row_falumnos = mysqli_fetch_array($result_falumnos);
			array_push($alumnos_profesor_por_claveal, $row_falumnos['CLAVEAL']);
		}
	}

	$MiPDF->Addpage();
	$MiPDF->SetY(30);
	
	$MiPDF->SetFont('NewsGotT', 'B', 12);
	$MiPDF->Multicell(0, 5, mb_strtoupper("Listado de clase", 'UTF-8'), 0, 'C', 0 );
	$MiPDF->Ln(2);
	
	$MiPDF->SetFont('NewsGotT', 'B', 11);
	$MiPDF->Cell(15, 5, 'Unidad: ', 0, 0, 'L', 0);
	$MiPDF->SetFont('NewsGotT', '', 11);

	if ($esPMAR) {
		$MiPDF->Cell(80, 5, $unidad.' (PMAR)', 0, 0, 'L', 0 );
	}
	else {
		$MiPDF->Cell(80, 5, $unidad, 0, 0, 'L', 0 );
	}
	
	$MiPDF->SetFont('NewsGotT', 'B', 11);
	$MiPDF->Cell(32, 5, 'Curso académico: ', 0, 0, 'L', 0);
	$MiPDF->SetFont('NewsGotT', '', 11);
	$MiPDF->Cell(36, 5, $GLOBALS['CURSO_ACTUAL'], 0, 1, 'L', 0 );
	
	// Obtenemos el tutor/a de la unidad
	$result = mysqli_query($db_con, "SELECT tutor FROM FTUTORES WHERE unidad='$unidad'");
	$row = mysqli_fetch_array($result);
	$tutor = $row['tutor'];
	mysqli_free_result($result);
	
	$MiPDF->SetFont('NewsGotT', 'B', 11);
	$MiPDF->Cell(15, 5, 'Tutor/a: ', 0, 0, 'L', 0);
	$MiPDF->SetFont('NewsGotT', '', 11);
	$MiPDF->Cell(80, 5, nomprofesor($tutor), 0, 0, 'L', 0 );
	
	$MiPDF->SetFont('NewsGotT', 'B', 11);
	$MiPDF->Cell(14, 5, 'Fecha: ', 0, 0, 'L', 0);
	$MiPDF->SetFont('NewsGotT', '', 11);
	$MiPDF->Cell(54, 5, date('d/m/Y'), 0, 1, 'L', 0 );
	
	$MiPDF->Ln(2);
	
	$MiPDF->SetWidths(array(8, 65, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9));
	$MiPDF->SetFont('NewsGotT', 'B', 11);
	$MiPDF->SetTextColor(255, 255, 255);
	$MiPDF->SetFillColor(61, 61, 61);
	
	$MiPDF->Row(array('Nº', 'Alumno/a', '', '', '', '', '', '', '', '', '', ''), 'DF', 6);	
	
	if ($esPMAR) {
		$result_codasig_pmar = mysqli_query($db_con, "SELECT codigo FROM materias WHERE grupo = '".$unidad."' AND abrev LIKE '%**%' and abrev not like '%\_%' LIMIT 1");
		$row_codasig_pmar = mysqli_fetch_array($result_codasig_pmar);
		$codasig_pmar = $row_codasig_pmar['codigo'];
		$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre, matriculas FROM alma WHERE unidad='$unidad' AND combasi LIKE '%$codasig_pmar%' ORDER BY apellidos ASC, nombre ASC");	
	}
	else {
		$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre, matriculas FROM alma WHERE unidad='$unidad' ORDER BY apellidos ASC, nombre ASC");	
	}
	
	$MiPDF->SetTextColor(0, 0, 0);
	$MiPDF->SetFont('NewsGotT', '', 11);
	
	$MiPDF->SetFillColor(239,240,239);

	$nc = 0;
	$fila = 1;
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if ($fila % 2 == 0) $fill = 'DF';
		else $fill = '';
		
		$nc++;
		$aux = '';

		if ($row['matriculas'] > 1) { 
			$aux = ' (Rep.)';
		}

		// Comprobamos si el centro utiliza el módulo de matriculaciones y obtenemos si el alumno es bilingüe o está exento de alguna materia
		$result_datos_matricula = mysqli_query($db_con, "SELECT bilinguismo, exencion FROM matriculas WHERE claveal = '".$row['claveal']."'");
		$row_datos_matricula = mysqli_fetch_array($result_datos_matricula);

		if ($row['bilinguismo'] == 'Si') { 
			$aux = ' (Bil.)';
		}

		if ($row['exencion'] == 1) { 
			$aux = ' (Exe.)';
		}
		
		$alumno = $row['apellidos'].', '.$row['nombre'].$aux;

		if (! isset($alumnos_profesor_por_claveal) || (isset($alumnos_profesor_por_claveal) && in_array($row['claveal'], $alumnos_profesor_por_claveal))) {
			$MiPDF->Row(array($nc, $alumno, '', '', '', '', '', '', '', '', '', ''), $fill, 6);	

			$fila++;
		}
		
	}
	
	mysqli_free_result($result);
}

$MiPDF->Output();
?>
