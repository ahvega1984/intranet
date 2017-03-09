<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {
	
	$prefNotificacionPadres	= limpiar_string($_POST['prefNotificacionPadres']);
	$prefMostrarDescripcion	= limpiar_string($_POST['prefMostrarDescripcion']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE PROBLEMAS DE CONVIVENCIA\r\n");
		fwrite($file, "\$config['convivencia']['notificaciones_padres']\t= $prefNotificacionPadres;\r\n");
		fwrite($file, "\$config['convivencia']['mostrar_descripcion']\t= $prefMostrarDescripcion;\r\n");
		
		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");
		
		fclose($file);
		
		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}
	
}

if (file_exists('config.php')) {
	include('config.php');
}


include("../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Problemas de convivencia <small>Preferencias</small></h2>
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
							<label for="prefNotificacionPadres" class="col-sm-4 control-label">Enviar notificación a padres</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefNotificacionPadres" name="prefNotificacionPadres">
									<option value="1" <?php echo (isset($config['convivencia']['notificaciones_padres']) && $config['convivencia']['notificaciones_padres'] == 1) ? 'selected' : ''; ?>>Habilitado</option>
									<option value="0" <?php echo (isset($config['convivencia']['notificaciones_padres']) && $config['convivencia']['notificaciones_padres'] == 0) ? 'selected' : ''; ?>>Deshabilitado</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefMostrarDescripcion" class="col-sm-4 control-label">Descripción del problema en impresión de partes</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefMostrarDescripcion" name="prefMostrarDescripcion">
									<option value="0" <?php echo (isset($config['convivencia']['mostrar_descripcion']) && $config['convivencia']['mostrar_descripcion'] == 0) ? 'selected' : ''; ?>>Deshabilitado</option>
									<option value="1" <?php echo (isset($config['convivencia']['mostrar_descripcion']) && $config['convivencia']['mostrar_descripcion'] == 1) ? 'selected' : ''; ?>>Habilitado</option>
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

<?php include("../../pie.php"); ?>

</body>
</html>
