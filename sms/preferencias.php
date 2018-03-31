<?php
require('../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

if (isset($_POST['btnGuardar'])) {

	//$_POST['prefProfSMS'] = limpiar_string($_POST['prefProfSMS']);

	$array_profes="";

	foreach($_POST['prefProfSMS'] as $profe_sms){		
		$array_profes.= '"'.$profe_sms.'",';
	}

	$array_profes=substr($array_profes, 0, -1);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE SMS\r\n");
		fwrite($file, "\$permiso_sms\t= array($array_profes);\r\n");
		
		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");
		
		fclose($file);
		
		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}
	
}

if (file_exists('config.php')) {
	include('config.php');
}


include("../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>SMS <small>Preferencias</small></h2>
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
							<label for="prefProfSMS" class="col-sm-4 control-label">Profesores con permiso especial para enviar SMS</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefProfSMS" name="prefProfSMS[]" multiple="multiple" style='height: 450px;width: 360px;'>
									<?php
									$profes = mysqli_query($db_con, "select nombre, idea from departamentos where cargo not like '%1%' and cargo not like '%6%' and cargo not like '%7%' and cargo not like '%8%' and cargo not like '%a%' order by nombre");
										while($p_profe = mysqli_fetch_array($profes))
										{
											$sel="";

												foreach($permiso_sms as $n_profe){					
													if ($n_profe==$p_profe[1]){
														$sel = " selected ";
													}

												}

											echo "<OPTION value='$p_profe[1]'  $sel>$p_profe[0]</OPTION>";

										}
									?>
								
								</select>
							</div>
						</div>
						
					</fieldset>
					
				</div>
				
				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<a href="index.php" class="btn btn-default">Volver</a>
			
			</form>
		
		</table>

		</div>

	</div>

</div>

<?php include("../pie.php"); ?>

</body>
</html>
