<?php
require('../../bootstrap.php');

// Obtenemos todas las tutorias
$tutorias = array();
$result = mysqli_query($db_con, "SELECT unidad, tutor FROM FTUTORES ORDER BY unidad ASC");
if (mysqli_num_rows($result)) {
	while ($row = mysqli_fetch_array($result)) {
		$tutoria = array(
			'unidad' => $row['unidad'],
			'tutor'  => $row['tutor']
		);

		array_push($tutorias, $tutoria);
	}
	unset($tutoria);
}

if (! isset($_POST['tutor'])) {
	$unidad = $tutorias[0]['unidad'];
	$tutor = $tutorias[0]['tutor'];
}
else {
	$exp_tutor = explode('==>', $_POST['tutor']);
	$unidad = trim($exp_tutor[0]);
	$tutor = trim($exp_tutor[1]);
}

function obtenerAlumno($var_nie, $var_grupo) {
	global $db_con;
	$result = mysqli_query($db_con, "SELECT `apellidos`, `nombre` FROM `alma` WHERE `unidad` = '".$var_grupo."' AND `claveal` = '".$var_nie."' ORDER BY `apellidos` ASC, `nombre` ASC LIMIT 1");
	if (mysqli_num_rows($result)) {
		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);
		return $row['apellidos'].', '.$row['nombre'];
	}
	else {
		return '';
	}
}


// OBTENEMOS LOS PUESTOS
$result = mysqli_query($db_con, "SELECT `unidad`, `puestos`, `estructura` FROM `puestos_alumnos` WHERE `unidad` = '".$unidad."' LIMIT 1");
$estructura_clase = '222';
if (mysqli_num_rows($result)) {
	$row = mysqli_fetch_array($result);
	$cadena_puestos = $row['puestos'];
	$estructura_clase = $row['estructura'];
	mysqli_free_result($result);

	$matriz_puestos = explode(';', $cadena_puestos);

	foreach ($matriz_puestos as $value) {
		$los_puestos = explode('|', $value);

		if ($los_puestos[0] == 'allItems') {
			$sin_puesto[] = $los_puestos[1];
		}
		else {
			$con_puesto[$los_puestos[0]] = $los_puestos[1];
		}

	}

	if ($estructura_clase == '242') { $mesas_col = 9; $mesas = 48; $col_profesor = 9; }
	if ($estructura_clase == '232') { $mesas_col = 8; $mesas = 42; $col_profesor = 8; }
	if ($estructura_clase == '222') { $mesas_col = 7; $mesas = 36; $col_profesor = 7; }
}

include("../../menu.php");
include("../informes/menu_alumno.php");
?>

	<style class="text/css">
	table tr td {
		vertical-align: top;
	}

	table tr td.active {
		background-color: #333;
	}

	table {
		margin: 0 auto;
	}

	table tr td div {
		border: 1px solid #ecf0f1;
		margin: 0 5px 10px 5px;
	}

	table tr td div {
	width: 120px;
	}

	table tr td div p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 2px;
		margin-bottom: 4px;
	}

	table tr td div ul {
		margin: 0 4px 4px 4px;
		min-height: 50px;
		background-color: #efefef;
	}

	table tr td div ul li {
		height: 100%;
	}

	.text-sm {
		font-size: 0.7em;
	}

	.col-sm-9 {
		padding-left: 0;
		padding-right: 0;
	}

	@media print {
		html, body {
			padding: 0;
		}

		.page-header {
			margin: 5px 0;
		}

  	.page-header h2 {
			font-size: 120%;
		}
		.page-header h4 {
			font-size: 100%;
		}
	}
	</style>

<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<?php $result = mysqli_query($db_con, "SELECT unidad, tutor FROM FTUTORES ORDER BY unidad ASC"); ?>
			<form class="pull-right col-sm-3 hidden-print" method="post" action="">
				<select class="form-control" id="tutor" name="tutor" onChange="submit()">
					<?php while($row = mysqli_fetch_array($result)): ?>
					<option value="<?php echo $row['unidad'].'==>'.$row['tutor']; ?>" <?php echo ($row['unidad'].'==>'.$row['tutor'] == $unidad.'==>'.$tutor) ? 'selected' : ''; ?>><?php echo $row['unidad']; ?> - <?php echo nomprofesor($row['tutor']); ?></option>
					<?php endwhile; ?>
				</select>
			</form>

			<h2 style="display: inline;">Alumnos y Grupos <small>Asignación de mesas</small></h2>
			<h4 class="text-info">Unidad: <?php echo $unidad; ?> - Tutor/a: <?php echo nomprofesor($tutor); ?></h4>
		</div>


		<!-- SCAFFOLDING -->
		<div id="dhtmlgoodies_dragDropContainer" class="row">

			<div id="dhtmlgoodies_mainContainer" class="col-sm-12">

				<?php if (count($con_puesto) > 0): ?>
				<table>
					<?php for ($i = 1; $i < 7; $i++): ?>
					<tr>
						<?php for ($j = 1; $j < $mesas_col; $j++): ?>
						<td>
							<div><p class="text-center">Mesa <?php echo $mesas; ?></p>
								<ul id="<?php echo $mesas; ?>" class="list-unstyled text-sm">
									<?php if (isset($con_puesto[$mesas])): ?>
									    <li id="<?php echo $con_puesto[$mesas]; ?>"><?php echo obtenerAlumno($con_puesto[$mesas], $unidad); ?></li>
									<?php endif; ?>
								</ul>
							</div>
						</td>
						<?php if ($j == 2 || $j == $mesas_col-3): ?>
						<td class="text-center active">|</td>
						<?php endif; ?>
						<?php $mesas--; ?>
						<?php endfor; ?>
					</tr>
					<?php endfor; ?>
					<tr>
						<td colspan="<?php echo $col_profesor; ?>">
						</td>
						<td class="text-center">
							<div>
								<p>Profesor/a</p>
								<br><br><br>
							</div>
						</td>
					</tr>
				</table>
				<?php else: ?>
				<br><br><br><br>
				<p class="lead text-muted text-center">El tutor no ha asignado mesas a los alumnos.<p>
				<br><br><br><br>
				<?php endif; ?>

			</div><!-- /.col-sm-12 -->

		</div><!-- /.row -->

		<?php if (count($con_puesto) > 0): ?>
		<br>

		<div class="row">

			<div class="col-sm-12">

				<div class="hidden-print">
						<a href="#" class="btn btn-primary" onclick="javascript:print();">Imprimir</a>
				</div>

			</div><!-- /col-sm-12 -->

		</div><!-- /.row -->
		<?php endif; ?>

	</div><!-- /.container -->


<?php include("../../pie.php"); ?>



</body>
</html>
