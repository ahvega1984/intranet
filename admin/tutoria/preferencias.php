<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'ISO-8859-1'));
}

if (isset($_POST['btnGuardar'])) {
	
	$prefImpExpulsionAula	= limpiar_string($_POST['prefImpExpulsionAula']);
	$prefImpAmonestacion	= limpiar_string($_POST['prefImpAmonestacion']);
	$prefAmonReiteracion	= limpiar_string($_POST['prefAmonReiteracion']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE TUTORÍA\r\n");
		fwrite($file, "\$config['tutoria']['impresion_expulsion_aula']\t= $prefImpExpulsionAula;\r\n");
		fwrite($file, "\$config['tutoria']['impresion_amonestacion']\t= $prefImpAmonestacion;\r\n");
		fwrite($file, "\$config['tutoria']['amonestacion_reiteracion']\t= $prefAmonReiteracion;\r\n");
		
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
		<h2>Control de tutorías <small>Preferencias</small></h2>
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
							<label for="prefImpExpulsionAula" class="col-sm-4 control-label">Imprimir parte de expulsión del aula</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefImpExpulsionAula" name="prefImpExpulsionAula">
									<option value="0" <?php echo (isset($config['tutoria']['impresion_expulsion_aula']) && $config['tutoria']['impresion_expulsion_aula'] == 0) ? 'selected' : ''; ?>>Habilitado</option>
									<option value="1" <?php echo (isset($config['tutoria']['impresion_expulsion_aula']) && $config['tutoria']['impresion_expulsion_aula'] == 1) ? 'selected' : ''; ?>>Deshabilitado</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefImpAmonestacion" class="col-sm-4 control-label">Imprimir amonestaciones escritas</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefImpAmonestacion" name="prefImpAmonestacion">
									<option value="0" <?php echo (isset($config['tutoria']['impresion_amonestacion']) && $config['tutoria']['impresion_amonestacion'] == 0) ? 'selected' : ''; ?>>Habilitado</option>
									<option value="1" <?php echo (isset($config['tutoria']['impresion_amonestacion']) && $config['tutoria']['impresion_amonestacion'] == 1) ? 'selected' : ''; ?>>Deshabilitado</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefAmonReiteracion" class="col-sm-4 control-label">Amonestación escrita automática por reiteración en el mismo trimestre de cinco o más faltas leves</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefAmonReiteracion" name="prefAmonReiteracion">
									<option value="0" <?php echo (isset($config['tutoria']['amonestacion_reiteracion']) && $config['tutoria']['amonestacion_reiteracion'] == 0) ? 'selected' : ''; ?>>Habilitado</option>
									<option value="1" <?php echo (isset($config['tutoria']['amonestacion_reiteracion']) && $config['tutoria']['amonestacion_reiteracion'] == 1) ? 'selected' : ''; ?>>Deshabilitado</option>
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

<script>
$(document).ready(function() {
	$('#prefCondiciones').summernote({
		height: 260,
		lang: 'es-ES'
	});
});
</script>

</body>
</html>
