<?php
require('../../../bootstrap.php');

if (isset($_POST['idempleado'])) {
	$idempleado = $_POST['idempleado'];
}
else {
	die('No direct script access allowed');
}

$actividades_seneca_noregular = array('661','156','289','286','284','5','346','285','36','780','96');

$horario_regular = array();
$horario_noregular = array();
$noregular_aux = array();

$result = mysqli_query($db_con, "SELECT `dia`, `hora`, `c_asig`, `prof`, `a_aula`, `a_grupo` FROM `horw` WHERE `c_prof` = '$idempleado' ORDER BY `dia` ASC, `hora` ASC");
while ($row = mysqli_fetch_array($result)) {

	$result_empleado = mysqli_query($db_con, "SELECT `fechatoma`, `fechacese` FROM `departamentos` WHERE `nombre` = '".$row["prof"]."' LIMIT 1");
	$row_empleado = mysqli_fetch_array($result_empleado);
	$fecha_toma_posesion_exp = explode('-', $row_empleado['fechatoma']);
	$fecha_toma_posesion = $fecha_toma_posesion_exp['2'].'/'.$fecha_toma_posesion_exp['1'].'/'.$fecha_toma_posesion_exp['0'];

	$fecha_cese_posesion_exp = explode('-', $row_empleado['fechacese']);
	$fecha_cese_posesion = $fecha_cese_posesion_exp['2'].'/'.$fecha_cese_posesion_exp['1'].'/'.$fecha_cese_posesion_exp['0'];

	$result_tramo = mysqli_query($db_con, "SELECT `tramo`, `horini`, `horfin`, `hora_inicio`, `hora_fin` FROM `tramos` WHERE `hora` = '".$row["hora"]."' LIMIT 1");
	$row_tramo = mysqli_fetch_array($result_tramo);

	$result_unidad = mysqli_query($db_con, "SELECT `idunidad` FROM `unidades` WHERE `nomunidad` = '".$row["a_grupo"]."'");
	$row_unidad = mysqli_fetch_array($result_unidad);

	$result_curso_por_asignatura = mysqli_query($db_con, "SELECT `idcurso` FROM `materias_seneca` WHERE `idmateria` = '".$row["c_asig"]."'  LIMIT 1");
	$row_curso_por_asignatura = mysqli_fetch_array($result_curso_por_asignatura);
	$id_curso = $row_curso_por_asignatura['idcurso'];

	$result_unidad_por_curso = mysqli_query($db_con, "SELECT `idunidad` FROM `unidades` WHERE `nomunidad` = '".$row["a_grupo"]."' AND `idcurso` = '".$id_curso."'");
	$row_unidad_por_curso = mysqli_fetch_array($result_unidad_por_curso);
	if ($row_unidad['idunidad'] != $row_unidad_por_curso['idunidad']) {
		$id_unidad = $row_unidad_por_curso['idunidad'];
	}
	else {
		$id_unidad = $row_unidad['idunidad'];
	}

	$result_dependencia = mysqli_query($db_con, "SELECT `iddependencia` FROM `dependencias` WHERE `nomdependencia` = '".$row["a_aula"]."'");
	$row_dependencia = mysqli_fetch_array($result_dependencia);

	$fecha_inicio_exp = explode('-', $config['curso_inicio']);
	$fecha_inicio = $fecha_inicio_exp['2'].'/'.$fecha_inicio_exp['1'].'/'.$fecha_inicio_exp['0'];

	$fecha_fin_exp = explode('-', $config['curso_fin']);
	$fecha_fin = $fecha_fin_exp['2'].'/'.$fecha_fin_exp['1'].'/'.$fecha_fin_exp['0'];

	if (in_array($row['c_asig'], $actividades_seneca_noregular) && ($row['a_grupo'] == "" || $row['a_aula'] == "")) {

		if (! in_array($row['c_asig'], $noregular_aux)) {
			array_push($noregular_aux, $row['c_asig']);

			$noregular = array(
				'codigo_actividad' 	=> $row['c_asig'],
				'numero_minutos'		=> ($row_tramo['horfin'] - $row_tramo['horini']),
			);

			array_push($horario_noregular, $noregular);
		}
		else {
			$clave_noregularaux = array_search($row['c_asig'], array_column($horario_noregular, 'codigo_actividad'));
			$horario_noregular[$clave_noregularaux]['numero_minutos'] += ($row_tramo['horfin'] - $row_tramo['horini']);
		}

	}
	else {
		if (empty($id_unidad) && empty($id_curso)) {
			$codigo_actividad = $row['c_asig'];
			$codigo_asignatura = '';
		}
		else {
			$codigo_actividad = '1';
			$codigo_asignatura = $row['c_asig'];
		}

		$regular = array(
			'dia_semana'					=> $row['dia'],
			'tramo_horario'				=> $row_tramo['tramo'],
			'codigo_dependencia'	=> $row_dependencia['iddependencia'],
			'codigo_unidad'				=> $id_unidad,
			'codigo_curso'				=> $id_curso,
			'codigo_asignatura'		=> $codigo_asignatura,
			'fecha_inicio'				=> '01/09/'.substr($config['curso_inicio'], 0, 4),
			'fecha_fin'						=> '31/08/'.(substr($config['curso_inicio'], 0, 4) + 1),
			'hora_inicio'					=> $row_tramo['horini'],
			'hora_fin'						=> $row_tramo['horfin'],
			'codigo_actividad' 		=> $codigo_actividad
		);

		array_push($horario_regular, $regular);
	}

}
unset($regular);
unset($noregular);

if (file_exists('ExportacionHorario_'.$idempleado.'.xml')) unlink('ExportacionHorario_'.$idempleado.'.xml');
if (!$fp = fopen('ExportacionHorario_'.$idempleado.'.xml', 'w+')) {
	die ("Error: No se puede crear o abrir el archivo ".'ExportacionHorario_'.$idempleado.'.xml');
}
else {

fwrite($fp,'<?xml version="1.0" encoding="iso-8859-1"?>
<SERVICIO modulo="HORARIOS" tipo="I" autor="'.$config['centro_denominacion'].'" fecha="'.date('d/m/Y H:i:s').'">
	<BLOQUE_DATOS>
		<grupo_datos seq="ANNO_ACADEMICO">
			<dato nombre_dato="C_ANNO">'.substr($config['curso_actual'], 0, 4).'</dato>
		</grupo_datos>
		<grupo_datos seq="HORARIOS_NO_REGULARES" registros="1">
			<grupo_datos seq="HORARIO_NO_REGULAR_PROFESOR_1" registros="'.count($horario_noregular).'">
				<dato nombre_dato="X_EMPLEADO">'.$idempleado.'</dato>
				<dato nombre_dato="F_TOMAPOS">'.$fecha_toma_posesion.'</dato>');
				$i = 1;
				foreach ($horario_noregular as $horario):
				fwrite($fp,'<grupo_datos seq="ACTIVIDAD_'.$i.'">
					<dato nombre_dato="X_ACTIVIDAD">'.$horario['codigo_actividad'].'</dato>
					<dato nombre_dato="N_MINSEN">'.$horario['numero_minutos'].'</dato>
				</grupo_datos>');
				$i++;
				endforeach;
				unset($horario);
				unset($i);
			fwrite($fp, '</grupo_datos>
		</grupo_datos>
		<grupo_datos seq="HORARIOS_REGULARES" registros="1">
			<grupo_datos seq="HORARIO_REGULAR_PROFESOR_1" registros="'.count($horario_regular).'">
				<dato nombre_dato="X_EMPLEADO">'.$idempleado.'</dato>
				<dato nombre_dato="F_TOMAPOS">'.$fecha_toma_posesion.'</dato>');
				$i = 1;
				foreach ($horario_regular as $horario):
				fwrite($fp, '<grupo_datos seq="ACTIVIDAD_'.$i.'">
					<dato nombre_dato="N_DIASEMANA">'.$horario['dia_semana'].'</dato>
					<dato nombre_dato="X_TRAMO">'.$horario['tramo_horario'].'</dato>');
					fwrite($fp, (! empty ($horario['codigo_dependencia'])) ? '<dato nombre_dato="X_DEPENDENCIA">'.$horario['codigo_dependencia'].'</dato>' : '');
					fwrite($fp, (! empty ($horario['codigo_unidad'])) ? '<dato nombre_dato="X_UNIDAD">'.$horario['codigo_unidad'].'</dato>' : '');
					fwrite($fp, (! empty ($horario['codigo_curso'])) ? '<dato nombre_dato="X_OFERTAMATRIG">'.$horario['codigo_curso'].'</dato>' : '');
					fwrite($fp, '<dato nombre_dato="X_MATERIAOMG">'.$horario['codigo_asignatura'].'</dato>
					<dato nombre_dato="F_INICIO">'.$horario['fecha_inicio'].'</dato>
					<dato nombre_dato="F_FIN">'.$horario['fecha_fin'].'</dato>
					<dato nombre_dato="N_HORINI">'.$horario['hora_inicio'].'</dato>
					<dato nombre_dato="N_HORFIN">'.$horario['hora_fin'].'</dato>
					<dato nombre_dato="X_ACTIVIDAD">'.$horario['codigo_actividad'].'</dato>
				</grupo_datos>');
				$i++;
				endforeach;
			fwrite($fp, '</grupo_datos>
		</grupo_datos>
	</BLOQUE_DATOS>
</SERVICIO>');

fclose($fp);

if (is_file('ExportacionHorario_'.$idempleado.'.xml')) {
	$size = filesize('ExportacionHorario_'.$idempleado.'.xml');
	if (function_exists('mime_content_type')) {
		$type = mime_content_type('ExportacionHorario_'.$idempleado.'.xml');
	} else if (function_exists('finfo_file')) {
		$info = finfo_open(FILEINFO_MIME);
		$type = finfo_file('ExportacionHorario_'.$idempleado.'.xml');
		finfo_close($info);
	}
	if ($type == '') {
		$type = "application/force-download";
	}
	// Set Headers
	header("Content-Type: $type");
	header("Content-Disposition: attachment; filename=ExportacionHorario_$idempleado.xml");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . $size);
	// Download File
	readfile('ExportacionHorario_'.$idempleado.'.xml');
}
	unlink('ExportacionHorario_'.$idempleado.'.xml');
}
?>
