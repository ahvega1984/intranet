<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {

	$prefSecretarioDFEIE	= limpiar_string($_POST['prefSecretarioDFEIE']);
	$prefSecretarioED		= limpiar_string($_POST['prefSecretarioED']);
	$prefSecretarioETCP		= limpiar_string($_POST['prefSecretarioETCP']);
	$prefSecretarioCEB		= limpiar_string($_POST['prefSecretarioCEB']);
	$prefSecretarioACA		= limpiar_string($_POST['prefSecretarioACA']);
	$prefSecretarioACCT		= limpiar_string($_POST['prefSecretarioACCT']);
	$prefSecretarioACSL		= limpiar_string($_POST['prefSecretarioACSL']);
	$prefSecretarioAFP		= limpiar_string($_POST['prefSecretarioAFP']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE ACTAS DE DEPARTAMENTOS\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_dfeie']\t= '$prefSecretarioDFEIE';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_ed']\t= '$prefSecretarioED';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_etcp']\t= '$prefSecretarioETCP';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_ceb']\t= '$prefSecretarioCEB';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_aca']\t= '$prefSecretarioACA';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_acct']\t= '$prefSecretarioACCT';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_acsl']\t= '$prefSecretarioACSL';\r\n");
		fwrite($file, "\$config['actas_depto']['secretario_afp']\t= '$prefSecretarioAFP';\r\n");
		
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

include("../../../menu.php");
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
						<legend>Secretario / Coordinador</legend>
						
						<div class="form-group">
							<label for="prefSecretarioDFEIE" class="col-sm-4 control-label">DFEIE</label>
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
							<label for="prefSecretarioETCP" class="col-sm-4 control-label">ETCP</label>
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
							<label for="prefSecretarioED" class="col-sm-4 control-label">Equipo directivo</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioED" name="prefSecretarioED">
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%1%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (! isset($config['actas_depto']['secretario_ed']) && $config['directivo_secretaria'] != "" && stristr($row['nombre'], $directivo_secretaria) == true) ? 'selected' : ''; ?><?php echo (isset($config['actas_depto']['secretario_ed']) && $config['actas_depto']['secretario_ed'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioCEB" class="col-sm-4 control-label">Equipo Coord. Enseñanzas Bilingüe</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioCEB" name="prefSecretarioCEB">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%a%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_ceb']) && $config['actas_depto']['secretario_ceb'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioACA" class="col-sm-4 control-label">Área de Competencia Artística</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioACA" name="prefSecretarioACA">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%9%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_aca']) && $config['actas_depto']['secretario_aca'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioACCT" class="col-sm-4 control-label">Área de Competencia Científico-Tecnológica</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioACCT" name="prefSecretarioACCT">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%9%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_acct']) && $config['actas_depto']['secretario_acct'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioACSL" class="col-sm-4 control-label">Área de Competencia Social-Lingüistica</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioACSL" name="prefSecretarioACSL">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%9%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_acsl']) && $config['actas_depto']['secretario_acsl'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefSecretarioAFP" class="col-sm-4 control-label">Área de Formación Profesional</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefSecretarioAFP" name="prefSecretarioAFP">
									<option value=""></option>
									<?php $result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%9%' ORDER BY nombre ASC"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($config['actas_depto']['secretario_afp']) && $config['actas_depto']['secretario_afp'] == $row['nombre']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						
					</fieldset>
					
				</div>
				
				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<?php if (isset($_GET['esAdmin']) && $_GET['esAdmin'] == 1): ?>
				<a href="../../../../xml/index.php" class="btn btn-default">Volver</a>
				<?php else: ?>
				<a href="index.php" class="btn btn-default">Volver</a>
				<?php endif; ?>
			
			</form>
		
		</table>

		</div>

	</div>

</div>

<?php include("../../../pie.php"); ?>

</body>
</html>
