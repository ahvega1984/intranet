<?php
require('../../../bootstrap.php');

// COMPROBAMOS SI SE HA SELECCIONADO LA UNIDAD
if (isset($_GET['id_informe'])) {
	$id_informe = $_GET['id_informe'];
	$edicion  = mysqli_fetch_array(mysqli_query($db_con, "select * from informe_pendientes where id_informe = '$id_informe'"));
	$edita = 1;
	$unidad = limpiarInput($edicion['unidad'], 'alphanumericspecial');
	$materia = limpiarInput($edicion['asignatura'], 'alphanumericspecial');
	$fecha_0 = explode(" ",$edicion['fecha']);
	$fecha_1 = explode("-", $fecha_0[0]);
	$fecha_hora = "$fecha_1[2]/$fecha_1[1]/$fecha_1[0] $fecha_0[1]";
	$modalidad = limpiarInput($edicion['modalidad'], 'alphanumericspecial');	
}

// OBTENEMOS LAS UNIDADES DONDE IMPARTE MATERIA EL PROFESOR
$unidades = array();
$result = mysqli_query($db_con, "SELECT DISTINCT `grupo` FROM `profesores` WHERE `profesor` = '".$_SESSION['profi']."' ORDER BY `grupo` ASC");
while ($row = mysqli_fetch_array($result)) {
	array_push($unidades, $row['grupo']);
}

// COMPROBAMOS SI SE HA SELECCIONADO LA UNIDAD
if (isset($_POST['unidad']) && in_array($_POST['unidad'], $unidades)) {
	$unidad = limpiarInput($_POST['unidad'], 'alphanumericspecial');
}

// OBTENEMOS LAS MATERIAS QUE IMPARTE EL PROFESOR
$materias = array();
if (isset($unidad)) {
	$result = mysqli_query($db_con, "SELECT distinct `materia`, `nivel` FROM `profesores` WHERE `profesor` = '".$_SESSION['profi']."' AND `grupo` = '".$unidad."'");
}
else {
	$result = mysqli_query($db_con, "SELECT distinct `materia`, `nivel` FROM `profesores` WHERE `profesor` = '".$_SESSION['profi']."'");
}
while ($row = mysqli_fetch_array($result)) {
	array_push($materias, $row['materia']);
}


// COMPROBAMOS SI SE HA SELECCIONADO LA MATERIA
if (isset($_POST['asignatura']) && in_array($_POST['asignatura'], $materias)) {
	$materia = limpiarInput($_POST['asignatura'], 'alphanumericspecial');
}

// MODALIDADES
$modalidades = array(
	1 => 'Presencial', 
	2 => 'Telemático'
);

// CREAR INFORME
if (isset($_POST['crear_informe'])) {

	// Variables
	$profesor = $_SESSION['ide'];
	$unidad = limpiarInput($_POST['unidad'], 'alphanumericspecial');
	$curso = limpiarInput($_POST['curso_inf'], 'alphanumericspecial');
	$materia = limpiarInput($_POST['asignatura'], 'alphanumericspecial');
	$fecha_hora = limpiarInput($_POST['fecha_hora'], 'alphanumericspecial');
	$fecha_sql = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})\ ([0-9]{2}):([0-9]{2})/', '$3-$2-$1 $4:$5:00', $fecha_hora);
	$modalidad = limpiarInput($_POST['modalidad'], 'alpha');

	$nivel_grupo = mysqli_fetch_array(mysqli_query($db_con,"select distinct nivel from profesores where grupo='$unidad'"));
	$curso = $nivel_grupo['nivel'];

	$hay_informe  = mysqli_query($db_con, "select * from informe_pendientes where profesor = '$profesor' and asignatura = '$materia' and unidad = '$unidad'");
	if (mysqli_num_rows($hay_informe)>0) {
		$id_inf = mysqli_fetch_array($hay_informe);
		$id_informe = $id_inf['id_informe'];
		$result = mysqli_query($db_con, "update `informe_pendientes` set fecha='".$fecha_sql."', modalidad='".$modalidad."' where id_informe = '".$id_informe."'");
		if (! $result) {
			$msg_error = "No se ha podido actualizar el informe. Error: ".mysqli_error($db_con);
		}
		else {
			$msg_success = "Se ha actualizado el informe correctamente.";
		}
	}	
	else{
		$result = mysqli_query($db_con, "INSERT INTO `informe_pendientes` (`profesor`, `asignatura`, `unidad`, `fecha`, `modalidad`, `curso`) VALUES ('".$profesor."', '".$materia."', '".$unidad."', '".$fecha_sql."', '".$modalidad."', '".$curso."')");
		if (! $result) {
			$msg_error = "No se ha podido crear el informe. Error: ".mysqli_error($db_con);
		}
		else {
			$id_informe = mysqli_insert_id($db_con);
			header('Location:'.'contenidos.php?id_informe='.$id_informe);
			exit();
		}
	}
}

include("../../../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<div class="page-header">
			<h2>Informe individual para la evaluación de alumnos con materias pendientes <small> <br>Nuevo informe</small></h2>
		</div>

		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger alert-fadeout">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success alert-fadeout">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>

		<div class="row">
			
			<div class="col-sm-6">
				
				<div class="well">
					
					<form action="informe.php" method="post">

						<fieldset>

							<legend>Crear informe</legend>

							<div class="form-group">
								<label for="unidad">Unidad</label>
								<select id="unidad" name="unidad" class="form-control" onchange="submit()" required>
									<option value=""></option>
									<?php foreach ($unidades as $unidad_cmp): ?>
									<option value="<?php echo $unidad_cmp; ?>" <?php echo ($unidad_cmp == $unidad) ? 'selected': ''; ?>><?php echo $unidad_cmp; ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group">
								<label for="asignatura">Asignatura</label>
								<select id="asignatura" name="asignatura" class="form-control" required>
									<option value=""></option>
									<?php if (isset($unidad) OR $edita==1): ?>
									<?php foreach ($materias as $materia_cmp): ?>
									<option value="<?php echo $materia_cmp; ?>" <?php echo ($materia_cmp == $materia) ? 'selected': ''; ?>><?php echo $materia_cmp; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>

							<div class="row">
			
								<div class="col-sm-6">

									<div class="form-group datetimepicker1">
										<label for="fecha_hora">Fecha y hora de la prueba</label>
										<div class="input-group">
											<input type="text" class="form-control" id="fecha_hora" name="fecha_hora" value="<?php if($edita==1 OR isset($fecha_hora)){ echo $fecha_hora;} else{ echo '01/09/'.date('Y').' 08:00';} ?>" data-date-format="DD/MM/YYYY HH:mm" required>
											<span class="input-group-addon"><span class="far fa-calendar">
										</div>
									</div>

								</div><!-- /.col-sm-6 -->

								<div class="col-sm-6">

									<div class="form-group">
										<label for="modalidad">Modalidad</label>
										<select id="modalidad" name="modalidad" class="form-control">
											<?php foreach ($modalidades as $modalidad_cmp): ?>
											<option value="<?php echo $modalidad_cmp; ?>" <?php echo ($modalidad_cmp == $modalidad ) ? 'selected': ''; ?>><?php echo $modalidad_cmp; ?></option>
											<?php endforeach; ?>
										</select>
									</div>

								</div><!-- /.col-sm-6 -->

							</div><!-- /.row -->

							<br>

							<div class="form-group">
								<button type="submit" class="btn btn-primary" name="crear_informe"><?php if($edita==1){ echo "Actualizar informe";} else{ echo "Crear informe"; }?></button>
							</div>

						</fieldset>

					</form>


				</div><!-- /.well -->

			</div><!-- /.col-sm-6 -->

			<div class="col-sm-6">
				<?php 
				// OBTENEMOS LAS UNIDADES DONDE IMPARTE MATERIA EL PROFESOR
				$informes = array();
				$result = mysqli_query($db_con, "SELECT `id_informe`, `asignatura`, `unidad`, `fecha`, `modalidad`, `plantilla` FROM `informe_pendientes` WHERE `profesor` = '".$_SESSION['ide']."' ORDER BY `id_informe` ASC");
				while ($row = mysqli_fetch_array($result)) {
					$informe = array(
						'id_informe' => $row['id_informe'],
						'asignatura' => $row['asignatura'],
						'unidad' => $row['unidad'],
						'fecha' => preg_replace('/([0-9]{4})-([0-9]{2})-([0-9]{2})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})/', '$3/$2/$1 $4:$5', $row['fecha']),
						'modalidad' => $row['modalidad'],
						'plantilla' => $row['plantilla']
					);

					array_push($informes, $informe);

					unset($informe);
				}

				?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Asignatura</th>
							<th>Unidad</th>
							<th>Modalidad</th>
							<th ></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($informes as $informe): ?>
						<tr>
							<td><?php echo $informe['asignatura']; ?></td>
							<td><?php echo $informe['unidad']; ?></td>
							<td><?php echo $informe['modalidad']; ?></td>
							<td class="pull-right">
								
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/pendientes/informe.php?id_informe=<?php echo $informe['id_informe']; ?>" data-bs="tooltip" title="Editar este informe."><span class="text-warning fas fa-edit fa-fw fa-lg"></span></a>
															
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/pendientes/index.php?borrar=1&id_informe=<?php echo $informe['id_informe']; ?>" data-bb="confirm-delete" data-bs="tooltip" title="Borrar este informe"><span class="text-danger far fa-trash-alt fa-fw fa-lg"></span></a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

			</div><!-- /.col-sm-12 -->

			<div class="col-sm-6">
			</div><!-- /.col-sm-6 -->

		</div><!-- /.row -->
		


	</div>

	<?php include("../../../pie.php"); ?>

	<script>
	$(function() {
		// DATETIMEPICKERS
		$('.datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: true,
			inline: true,
            sideBySide: true
		});
	});
	</script>

</body>
</html>