<?php
require('../../../bootstrap.php');

if (isset($_POST['idempleado'])) {
	$idempleado = $_POST['idempleado'];
}

$actividades_seneca = array();
$result_actividades_seneca = mysqli_query($db_con, "SELECT `idactividad` FROM `actividades_seneca`");
while($row = mysqli_fetch_array($result_actividades_seneca)) array_push($actividades_seneca, $row['idactividad']);

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

	$result_dependencia = mysqli_query($db_con, "SELECT `iddependencia` FROM `dependencias` WHERE `nomdependencia` = '".$row["a_aula"]."'");
	$row_dependencia = mysqli_fetch_array($result_dependencia);

	$fecha_inicio_exp = explode('-', $config['curso_inicio']);
	$fecha_inicio = $fecha_inicio_exp['2'].'/'.$fecha_inicio_exp['1'].'/'.$fecha_inicio_exp['0'];

	$fecha_fin_exp = explode('-', $config['curso_fin']);
	$fecha_fin = $fecha_fin_exp['2'].'/'.$fecha_fin_exp['1'].'/'.$fecha_fin_exp['0'];

	if (in_array($row['c_asig'], $actividades_seneca) && ($row['a_grupo'] == "" || $row['a_aula'] == "")) {

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
		$regular = array(
			'dia_semana'					=> $row['dia'],
			'tramo_horario'				=> $row_tramo['tramo'],
			'codigo_dependencia'	=> $row_dependencia['iddependencia'],
			'codigo_unidad'				=> $row_unidad['idunidad'],
			'codigo_asignatura'		=> $row['c_asig'],
			'fecha_inicio'				=> $fecha_inicio,
			'fecha_fin'						=> $fecha_fin,
			'hora_inicio'					=> $row_tramo['horini'],
			'hora_fin'						=> $row_tramo['horfin'],
			'codigo_actividad' 		=> '1'
		);

		array_push($horario_regular, $regular);
	}

}
unset($regular);
unset($noregular);

header("Content-type: text/xml");
?>
<?//xml version="1.0" encoding="iso-8859-1"?>

<SERVICIO modulo="HORARIOS" tipo="I" autor="<?php echo $config['centro_denominacion']; ?>" fecha="<?php echo date('d/m/Y H:i:s'); ?>">
	<BLOQUE_DATOS>
		<grupo_datos seq="ANNO_ACADEMICO">
			<dato nombre_dato="C_ANNO"><?php echo substr($config['curso_actual'], 0, 4); ?></dato>
		</grupo_datos>
		<grupo_datos seq="HORARIOS_NO_REGULARES" registros="1">
			<grupo_datos seq="HORARIO_NO_REGULAR_PROFESOR_1" registros="<?php echo count($horario_noregular); ?>">
				<dato nombre_dato="X_EMPLEADO"><?php echo $idempleado; ?></dato>
				<dato nombre_dato="F_TOMAPOS"><?php echo $fecha_toma_posesion; ?></dato>
				<?php $i = 1; ?>
				<?php foreach ($horario_noregular as $horario): ?>
				<grupo_datos seq="ACTIVIDAD_<?php echo $i; ?>">
					<dato nombre_dato="X_ACTIVIDAD"><?php echo $horario['codigo_actividad']; ?></dato>
					<dato nombre_dato="N_MINSEN"><?php echo $horario['numero_minutos']; ?></dato>
				</grupo_datos>
				<?php $i++; ?>
				<?php endforeach; ?>
				<?php unset($horario); ?>
				<?php unset($i); ?>
			</grupo_datos>
		</grupo_datos>
		<grupo_datos seq="HORARIOS_REGULARES" registros="1">
			<grupo_datos seq="HORARIO_REGULAR_PROFESOR_1" registros="<?php echo count($horario_regular); ?>">
				<dato nombre_dato="X_EMPLEADO"><?php echo $idempleado; ?></dato>
				<dato nombre_dato="F_TOMAPOS"><?php echo $fecha_toma_posesion; ?></dato>
				<?php $i = 1; ?>
				<?php foreach ($horario_regular as $horario): ?>
				<grupo_datos seq="ACTIVIDAD_<?php echo $i; ?>">
					<dato nombre_dato="N_DIASEMANA"><?php echo $horario['dia_semana']; ?></dato>
					<dato nombre_dato="X_TRAMO"><?php echo $horario['tramo_horario']; ?></dato>
					<?php echo (! empty ($horario['codigo_dependencia'])) ? '<dato nombre_dato="X_DEPENDENCIA">'.$horario['codigo_dependencia'].'</dato>' : ''; ?>
					<dato nombre_dato="X_UNIDAD"><?php echo $horario['codigo_unidad']; ?></dato>
					<dato nombre_dato="X_OFERTAMATRIG">100323</dato>
					<dato nombre_dato="X_MATERIAOMG"><?php echo $horario['codigo_asignatura']; ?></dato>
					<dato nombre_dato="F_INICIO"><?php echo $horario['fecha_inicio']; ?></dato>
					<dato nombre_dato="F_FIN"><?php echo $horario['fecha_fin']; ?></dato>
					<dato nombre_dato="N_HORINI"><?php echo $horario['hora_inicio']; ?></dato>
					<dato nombre_dato="N_HORFIN"><?php echo $horario['hora_fin']; ?></dato>
					<dato nombre_dato="X_ACTIVIDAD"><?php echo $horario['codigo_actividad']; ?></dato>
				</grupo_datos>
				<?php $i++; ?>
				<?php endforeach; ?>
			</grupo_datos>
		</grupo_datos>
	</BLOQUE_DATOS>
</SERVICIO>
