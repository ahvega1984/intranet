<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'ISO-8859-1'));
}

if (isset($_POST['btnGuardar'])) {

	$prefSecretarioDFEIE	= limpiar_string($_POST['prefSecretarioDFEIE']);
	$prefSecretarioED		= limpiar_string($_POST['prefSecretarioED']);
	$prefSecretarioETCP		= limpiar_string($_POST['prefSecretarioETCP']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE ACTAS DE DEPARTAMENTOS\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_dfeie']\t= '$prefSecretarioDFEIE';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_ed']\t= '$prefSecretarioED';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_etcp']\t= '$prefSecretarioETCP';\r\n");
		
		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");
		
		fclose($file);
		
		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}
	
}

if (file_exists('config.php')) {
	include('config.php');
}


$exp_directivo_secretaria = explode(' ', $config['directivo_secretaria']);
$directivo_secretaria = $exp_directivo_secretaria[0];

include("../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Actas de departamentos <small>Preferencias</small></h2>
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
							<label for="prefSecretarioDFEIE" class="col-sm-4 control-label">Secretario/a del DFEIE</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioDFEIE" name="prefSecretarioDFEIE">
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%f%' ORDER BY nombre ASC"); ?>
									<?php if (mysqli_num_rows($result) > 1): ?>
									<option value=""></option>
									<?php endif; ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_dfeie']) && $config['actas_depto']['secretario_dfeie'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioETCP" class="col-sm-4 control-label">Secretario/a de ETCP</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioETCP" name="prefSecretarioETCP">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%9%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_etcp']) && $config['actas_depto']['secretario_etcp'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioED" class="col-sm-4 control-label">Secretario/a del Equipo directivo</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioED" name="prefSecretarioED">
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%1%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (! isset($config['actas_depto']['secretario_ed']) && $config['directivo_secretaria'] != "" && stristr($row['nombre'], $directivo_secretaria) == true) ? 'selected' : ''; ?><?php echo (isset($config['actas_depto']['secretario_ed']) && $config['actas_depto']['secretario_ed'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
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
