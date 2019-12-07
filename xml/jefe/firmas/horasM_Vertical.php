<?php
require('../../../bootstrap.php');
require("../../../pdf/mc_table.php");

acl_acceso($_SESSION['cargo'], array('0', '1'));

if (!isset($_POST['dia']))  
	$fechaInicio = date("Y-m-d", strtotime("next Monday"));
else
	$fechaInicio = $_POST['dia'];
	
$arrayDias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');

$MiPDF = new PDF_MC_Table('P','mm', 'A4');
$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');

$MiPDF->SetMargins(8, 12, 5); //left, top, right
$MiPDF->SetDisplayMode('fullpage');
$MiPDF->SetAutoPageBreak(true, 8);


// Obtenemos las jornadas del centro
$result_jornadas = mysqli_query($db_con, "SELECT DISTINCT `idjornada` FROM `tramos` ORDER BY `horini` ASC, `horfin` ASC");
$num_horario = 0;
$turno = 'Mañana';

while ($row_jornada = mysqli_fetch_array($result_jornadas)) {
	$num_horario++;

	$horas_jornada = array();
	$result_horas_jornada = mysqli_query($db_con, "SELECT `tramo`, `hora`, `hora_inicio`, `hora_fin` FROM `tramos` WHERE `idjornada` = '".$row_jornada['idjornada']."'   and hora < 7 ORDER BY `horini` ASC, `horfin` ASC");
	while($row_horas_jornada = mysqli_fetch_array($result_horas_jornada)) {
		$hora_jornada = array(
			'tramo' => $row_horas_jornada['tramo'],
			'hora' => $row_horas_jornada['hora'],
			'hora_inicio' => substr($row_horas_jornada['hora_inicio'], 0, 5),
			'hora_fin' => substr($row_horas_jornada['hora_fin'], 0, 5)
		);

		array_push($horas_jornada, $hora_jornada);
	}
	unset($hora_jornada);


	
	for ($diasemana = 1; $diasemana < 6 ; $diasemana++) {

		$MiPDF->Addpage(); // Nueva página por cada jornada

		$MiPDF->SetFont('NewsGotT', 'B', 10);
		$MiPDF->Cell(85, 5, 'Registro de entrada y salida del profesorado', 0, 0, 'L');
		$MiPDF->Cell(48, 5, '[Turno de '.$turno.']', 0, 0, 'C');
		//$MiPDF->Cell(60, 5, $nombredia.'  ___ / ___ / ______', 0, 1, 'R');

		$dia = strtotime($fechaInicio. ' + '.($diasemana - 1).' days');
		$nombredia = $arrayDias[date('w', $dia)];	

		$MiPDF->Cell(60, 5, $nombredia.'  '.date('d/m/Y',$dia), 0, 1, 'R');
		$MiPDF->Ln(5);

		$flag_firma = 0;
		$i_firma = 0;
		$huecos_firma = array();
		$ancho_columnas = array();
		
		$titulos = array();
		array_push($titulos, "\nProfesor/a");
		array_push($ancho_columnas, 30);
		array_push($titulos, "Firma\nEntrada");
		//array_push($ancho_columnas, 27);
		array_push($ancho_columnas, 30);
		foreach ($horas_jornada as $hora_jornada) {
			if ($hora_jornada['hora_inicio'] > "15:30" && $hora_jornada['hora_inicio'] < "20:00" && $i_firma > 5) {
				if ($flag_firma == 0) {
					array_push($huecos_firma, $i_firma);
					array_push($titulos, "Firma\nSalida");
					array_push($ancho_columnas, 25);
					array_push($titulos, "Firma\nEntrada");
					array_push($ancho_columnas, 25);
					$flag_firma = 1;
				}
				array_push($titulos, $hora_jornada['hora_inicio']."\n".$hora_jornada['hora_fin']);
			}
			else {
				array_push($titulos, $hora_jornada['hora_inicio']."\n".$hora_jornada['hora_fin']);
			}
			//array_push($ancho_columnas, 13);
			array_push($ancho_columnas, 15);
			$i_firma++;
		}
		array_push($titulos, "Firma\nSalida");
		array_push($ancho_columnas, 30);

		$MiPDF->SetFont('NewsGotT', 'B', 9);
		$MiPDF->SetWidths($ancho_columnas);
		$MiPDF->SetTextColor(255, 255, 255);
		$MiPDF->SetFillColor(61, 61, 61);
		$MiPDF->aligns[1] = 'C';
		$MiPDF->aligns[9] = 'C';

		$MiPDF->Row($titulos, 0, 5);
		$MiPDF->SetTextColor(0, 0, 0);

  	    $MiPDF->SetFillColor(255, 255, 255);
		//$MiPDF->SetFont('NewsGotT', '', 9);

		$MiPDF->SetFont('NewsGotT', '', 9);

		$profesores = array();
		$result_profesores = mysqli_query($db_con, "SELECT DISTINCT `profesor` FROM `profesores` ORDER BY `profesor` ASC");
		while ($row_profesores = mysqli_fetch_array($result_profesores)) {

			$horario_profesor = array();
			foreach ($horas_jornada as $hora_jornada) {
				$result_horario = mysqli_query($db_con, "SELECT `a_asig`, `a_aula`, `a_grupo` FROM `horw` WHERE `prof` = '".$row_profesores['profesor']."' AND `dia` = '".date('w', $dia)."' AND `hora` = '".$hora_jornada['hora']."' LIMIT 1");
				$asignatura_tramo = "";
				$aula_tramo = "";
				$grupo_tramo = "";
				while ($row_horario = mysqli_fetch_array($result_horario)) {
					$abrev = "";
					$aula = "";
					$grupo = "";
					if (strlen($row_horario['a_asig']) > 7) {
						$abrev = substr($row_horario['a_asig'], 0, 7);
					}
					else {
						$abrev = $row_horario['a_asig'];
					}
					if (strlen($row_horario['a_aula']) > 7) {
						$aula = substr($row_horario['a_aula'], 0, 7);
					}
					else {
						$aula = $row_horario['a_aula'];
					}
					if (strlen($row_horario['a_grupo']) > 7) {
						$grupo = substr($row_horario['a_grupo'], 0, 7);
					}
					else {
						$grupo = $row_horario['a_grupo'];
					}
					$asignatura_tramo .= $abrev." ";
					//$aula_tramo .= $aula." ";
					$grupo_tramo .= $grupo." ";
				}
				$asignatura_tramo = rtrim($asignatura_tramo);
				$aula_tramo = rtrim($aula_tramo);
				$grupo_tramo = rtrim($grupo_tramo);

				//array_push($horario_profesor, $asignatura_tramo.((! empty($aula_tramo)) ? "\n".$aula_tramo : ""));
				array_push($horario_profesor, $asignatura_tramo.((! empty($grupo_tramo)) ? "\n".$grupo_tramo : "").((! empty($aula_tramo)) ? "\n".$aula_tramo : ""));
			}

			$profesor = array(
				'nombre' => $row_profesores['profesor'],
				'horario' => $horario_profesor
			);

			array_push($profesores, $profesor);
		}
		unset($profesor);

		$i = 0;
		//$cabecera = 15;
		$cabecera = 22;
		foreach ($profesores as $profesor) {

			$flag_mostrar = 0;
			$flag_firma = 0;
			$i_firma = 0;
			$datos = array();
			$nombre_profesor = str_ireplace(", ", ",\n", $profesor['nombre']);
			//$nombre_profesor = $profesor['nombre'] . "\n\n";
			array_push($datos, $nombre_profesor);
			array_push($datos, "\n\n"); // Hueco firma
			
			foreach ($profesor['horario'] as $tramo) {
				if (! empty($tramo)) 
					$flag_mostrar = 1;

				if (in_array($i_firma, $huecos_firma)) {
					if ($flag_firma == 0) {
						array_push($datos, "\n\n"); // Hueco firma
						array_push($datos, "\n\n"); // Hueco firma
						$flag_firma++;
					}
					if (stristr($tramo, "\n") == true) {
						array_push($datos, $tramo);
					}
					else {
						array_push($datos, $tramo."\n\n");
					}

				}
				else {
					if (stristr($tramo, "\n") == true) {
						array_push($datos, $tramo);
					}
					else {
						array_push($datos, $tramo."\n\n");
					}
				}
				$i_firma++;
			}
			array_push($datos, "\n\n"); // Hueco firma

			if ($flag_mostrar) {
				$i++;

				if ($i == $cabecera) {
					$MiPDF->SetFont('NewsGotT', 'B', 10);
					$MiPDF->SetTextColor(255, 255, 255);
					$MiPDF->SetFillColor(61, 61, 61);

					$MiPDF->Row($titulos, 0, 5);
					$MiPDF->SetTextColor(0, 0, 0);
					//$MiPDF->SetFillColor(255, 255, 255);
					$MiPDF->SetFont('NewsGotT', '', 10);
					$cabecera += 22;
				}

/*
				if ($i % 2 == 0)
					$MiPDF->SetFillColor(225, 225, 225);
				else
					$MiPDF->SetFillColor(255, 255, 255);
*/		
				$MiPDF->RowWithGrey($datos, 0, 6, 'M');
			}	
		}
	}
}
$MiPDF->Output();
