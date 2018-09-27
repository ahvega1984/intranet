<?php
require('../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {

	$prefCoordinador				= limpiar_string($_POST['prefCoordinador']);
	$prefNotificaciones				= limpiar_string($_POST['prefNotificaciones']);
	$prefNotificacionesSolicitante	= limpiar_string($_POST['prefNotificacionesSolicitante']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");

		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE CENTRO TIC\r\n");
		fwrite($file, "\$config['tic']['coordinador']\t= '$prefCoordinador';\r\n");
		fwrite($file, "\$config['tic']['notificaciones']\t= $prefNotificaciones;\r\n");
		fwrite($file, "\$config['tic']['notificaciones_solicitante']\t= $prefNotificacionesSolicitante;\r\n");

		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");

		fclose($file);

		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}

}

if (file_exists('config.php')) {
	include('config.php');
}

// Buscamos al coordinador TIC en el horario. La consulta busca a la persona que más horas tenga asignadas, por si hay centros
// con horas de Coord. TIC repartidas a varios profesores o chanchullos varios.
$result = mysqli_query($db_con, "SELECT prof, COUNT(*) AS horas FROM `horw` WHERE c_asig = '281' GROUP BY prof ORDER BY horas DESC");
$row = mysqli_fetch_array($result);
$coordinador_en_horario = $row['prof'];


include("../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Centro TIC <small>Preferencias</small></h2>
	</div>

	<!-- MENSAJES -->
	<?php if (isset($msg_error)): ?>
	<div class="alert alert-danger alert-fadeout">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>

	<?php if (isset($msg_success)): ?>
	<div class="alert alert-success alert-fadeout">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>


	<div class="row">

		<div class="col-sm-12">

			<form class="form-horizontal" method="post" action="preferencias.php">

				<div class="well">

					<fieldset>
						<legend>Preferencias</legend>

						<div class="form-group">
							<label for="prefCoordinador" class="col-sm-4 control-label">Coordinador TIC</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefCoordinador" name="prefCoordinador">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Servicio Técnico y/o Mantenimiento' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (! isset($config['tic']['coordinador']) && $coordinador_en_horario != "" && $coordinador_en_horario == $row['nombre']) ? 'selected' : ''; ?><?php echo (isset($config['tic']['coordinador']) && $config['tic']['coordinador'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="prefNotificaciones" class="col-sm-4 control-label">Notificar al Coordinador/a TIC de una nueva incidencia mediante mensaje interno</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefNotificaciones" name="prefNotificaciones">
									<option value="1" <?php echo (isset($config['tic']['notificaciones']) && $config['tic']['notificaciones'] == 1) ? 'selected' : ''; ?>>Habilitado</option>
									<option value="0" <?php echo (isset($config['tic']['notificaciones']) && $config['tic']['notificaciones'] == 0) ? 'selected' : ''; ?>>Deshabilitado</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="prefNotificaciones" class="col-sm-4 control-label">Notificar al solicitante del estado de la incidencia mediante mensaje interno</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefNotificacionesSolicitante" name="prefNotificacionesSolicitante">
									<option value="1" <?php echo (isset($config['tic']['notificaciones_solicitante']) && $config['tic']['notificaciones_solicitante'] == 1) ? 'selected' : ''; ?>>Habilitado</option>
									<option value="0" <?php echo (isset($config['tic']['notificaciones_solicitante']) && $config['tic']['notificaciones_solicitante'] == 0) ? 'selected' : ''; ?>>Deshabilitado</option>
								</select>
							</div>
						</div>

					</fieldset>

				</div>

				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<?php if (isset($_GET['esAdmin']) && $_GET['esAdmin'] == 1): ?>
				<a href="../../../xml/index.php" class="btn btn-default">Volver</a>
				<?php else: ?>
				<a href="index.php" class="btn btn-default">Volver</a>
				<?php endif; ?>

			</form>

		</table>

		</div>

	</div>

</div>

<?php include("../pie.php"); ?>

</body>
</html>
