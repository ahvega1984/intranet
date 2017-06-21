<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {

	if (isset($_POST['opt1_0'])) {
		$opt1[] = $_POST['opt1_0'];
		$opt1[] = $_POST['opt1_1'];
		$opt1[] = $_POST['opt1_2'];
		$opt1[] = $_POST['opt1_3'];
	}


	$prefInicio	= limpiar_string($_POST['prefInicio']);
	$prefFin	= limpiar_string($_POST['prefFin']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE MATRICULACIÓN\r\n");

		fwrite($file, "\$config['matriculas']['fecha_inicio']\t= '$prefInicio';\r\n");	
		fwrite($file, "\$config['matriculas']['fecha_fin']\t= '$prefFin';\r\n");

		fwrite($file, "\$opt1\t= array('$opt1[0]', '$opt1[1]', '$opt1[2]', '$opt1[3]');\r\n");

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
		<h2>Matriculación de alumnos <small>Preferencias</small></h2>
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
						<div class="col-sm-12"><h3>Fechas de Matriculación para los alumnos del Centro<br><br></h3></div>
						
						<div class="form-group">							
							<label for="prefInicio" class="col-sm-3 control-label">Fecha de inicio de Matriculación</label>
							<div class="col-sm-3" id="datetimepicker1">
							<div class="input-group">
							<input name="prefInicio" type="text"
								class="form-control" value="<?php echo $config['matriculas']['fecha_inicio']; ?>" data-date-format="YYYY-MM-DD" id="prefInicio"> 
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							</div>
						</div>

						<div class="form-group">
							<label for="prefFin" class="col-sm-3 control-label">Fecha de Fin de Matriculación</label>
							<div class="col-sm-3" id="datetimepicker2">
							<div class="input-group">
							<input name="prefFin" type="text"
								class="form-control" value="<?php echo $config['matriculas']['fecha_fin']; ?>" data-date-format="YYYY-MM-DD" id="prefFin"> 
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							</div>
						</div>
						<hr>
					
						<div class="col-sm-12"><h3>Opciones de 1º ESO<br><br></h3></div>
						
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt1_0" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt1_0" type="text"
								class="form-control" value="<?php echo $opt1[0]; ?>" id="opt1_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_1" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt1_1" type="text"
								class="form-control" value="<?php echo $opt1[1]; ?>" id="opt1_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_2" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt1_2" type="text"
								class="form-control" value="<?php echo $opt1[2]; ?>" id="opt1_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_3" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt1_3" type="text"
								class="form-control" value="<?php echo $opt1[3]; ?>" id="opt1_3"> 
							</div>
						</div>
								</td>
								<td>
									<legend>Ampliaciones y Refuerzos</legend>
						<div class="form-group">
							<label for="a1_0" class="col-sm-4 control-label">Actividad 1</label>
							<div class="input-group col-sm-5">
							<input name="a1_0" type="text"
								class="form-control" value="<?php echo $a1[0]; ?>" id="a1_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_1" class="col-sm-4 control-label">Actividad 2</label>
							<div class="input-group col-sm-5">
							<input name="a1_1" type="text"
								class="form-control" value="<?php echo $a1[1]; ?>" id="a1_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_2" class="col-sm-4 control-label">Actividad 3</label>
							<div class="input-group col-sm-5">
							<input name="a1_2" type="text"
								class="form-control" value="<?php echo $a1[2]; ?>" id="a1_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_3" class="col-sm-4 control-label">Actividad 4</label>
							<div class="input-group col-sm-5">
							<input name="a1_3" type="text"
								class="form-control" value="<?php echo $a1[3]; ?>" id="a1_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_4" class="col-sm-4 control-label">Actividad 5</label>
							<div class="input-group col-sm-5">
							<input name="a1_4" type="text"
								class="form-control" value="<?php echo $a1[4]; ?>" id="a1_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_5" class="col-sm-4 control-label">Actividad 6</label>
							<div class="input-group col-sm-5">
							<input name="a1_5" type="text"
								class="form-control" value="<?php echo $a1[5]; ?>" id="a1_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_6" class="col-sm-4 control-label">Actividad 7</label>
							<div class="input-group col-sm-5">
							<input name="a1_6" type="text"
								class="form-control" value="<?php echo $a1[6]; ?>" id="a1_6"> 
							</div>
						</div>
								</td>
							</tr>
						</table>
	
						<div class="col-sm-12"><h3>Opciones de 2º ESO<br><br></h3></div>
											
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt2_0" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt2_0" type="text"
								class="form-control" value="<?php echo $opt2[0]; ?>" id="opt2_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_1" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt2_1" type="text"
								class="form-control" value="<?php echo $opt2[1]; ?>" id="opt2_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_2" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt2_2" type="text"
								class="form-control" value="<?php echo $opt2[2]; ?>" id="opt2_2"> 
							</div>
						</div>
						<div class="form-group">
						</div>
								</td>
								<td>
									<legend>Ampliaciones y Refuerzos</legend>
						<div class="form-group">
							<label for="a2_0" class="col-sm-4 control-label">Actividad 1</label>
							<div class="input-group col-sm-5">
							<input name="a2_0" type="text"
								class="form-control" value="<?php echo $a2[0]; ?>" id="a2_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_1" class="col-sm-4 control-label">Actividad 2</label>
							<div class="input-group col-sm-5">
							<input name="a2_1" type="text"
								class="form-control" value="<?php echo $a2[1]; ?>" id="a2_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_2" class="col-sm-4 control-label">Actividad 3</label>
							<div class="input-group col-sm-5">
							<input name="a2_2" type="text"
								class="form-control" value="<?php echo $a2[2]; ?>" id="a2_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_3" class="col-sm-4 control-label">Actividad 4</label>
							<div class="input-group col-sm-5">
							<input name="a2_3" type="text"
								class="form-control" value="<?php echo $a2[3]; ?>" id="a2_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_4" class="col-sm-4 control-label">Actividad 5</label>
							<div class="input-group col-sm-5">
							<input name="a2_4" type="text"
								class="form-control" value="<?php echo $a2[4]; ?>" id="a2_4"> 
							</div>
						</div>
								</td>
							</tr>
						</table>


						<div class="col-sm-12"><h3>Opciones de 3º ESO<br><br></h3></div>
											
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt3_0" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt3_0" type="text"
								class="form-control" value="<?php echo $opt3[0]; ?>" id="opt3_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_1" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt3_1" type="text"
								class="form-control" value="<?php echo $opt3[1]; ?>" id="opt3_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_2" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt3_2" type="text"
								class="form-control" value="<?php echo $opt3[2]; ?>" id="opt3_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_3" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt3_3" type="text"
								class="form-control" value="<?php echo $opt3[3]; ?>" id="opt3_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_4" class="col-sm-4 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="opt3_4" type="text"
								class="form-control" value="<?php echo $opt3[4]; ?>" id="opt3_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_5" class="col-sm-4 control-label">Optativa 6</label>
							<div class="input-group col-sm-5">
							<input name="opt3_5" type="text"
								class="form-control" value="<?php echo $opt3[5]; ?>" id="opt3_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_6" class="col-sm-4 control-label">Optativa 7</label>
							<div class="input-group col-sm-5">
							<input name="opt3_6" type="text"
								class="form-control" value="<?php echo $opt3[6]; ?>" id="opt3_6"> 
							</div>
						</div>

								</td>
								<td>
									<legend>Ampliaciones y Refuerzos</legend>
						<div class="form-group">
							<label for="a3_0" class="col-sm-4 control-label">Actividad 1</label>
							<div class="input-group col-sm-5">
							<input name="a3_0" type="text"
								class="form-control" value="<?php echo $a3[0]; ?>" id="a3_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_1" class="col-sm-4 control-label">Actividad 2</label>
							<div class="input-group col-sm-5">
							<input name="a3_1" type="text"
								class="form-control" value="<?php echo $a3[1]; ?>" id="a3_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_2" class="col-sm-4 control-label">Actividad 3</label>
							<div class="input-group col-sm-5">
							<input name="a3_2" type="text"
								class="form-control" value="<?php echo $a3[2]; ?>" id="a3_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_3" class="col-sm-4 control-label">Actividad 4</label>
							<div class="input-group col-sm-5">
							<input name="a3_3" type="text"
								class="form-control" value="<?php echo $a3[3]; ?>" id="a3_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_4" class="col-sm-4 control-label">Actividad 5</label>
							<div class="input-group col-sm-5">
							<input name="a3_4" type="text"
								class="form-control" value="<?php echo $a3[4]; ?>" id="a3_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_5" class="col-sm-4 control-label">Actividad 6</label>
							<div class="input-group col-sm-5">
							<input name="a3_5" type="text"
								class="form-control" value="<?php echo $a3[5]; ?>" id="a3_5"> 
							</div>
						</div>
								</td>
							</tr>
						</table>

						<div class="col-sm-12"><h3>Opciones de 4º ESO<br><br></h3></div>
						
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt4_0" class="col-sm-2 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt4_0" type="text"
								class="form-control" value="<?php echo $opt4[0]; ?>" id="opt4_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_1" class="col-sm-2 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt4_1" type="text"
								class="form-control" value="<?php echo $opt4[1]; ?>" id="opt4_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_2" class="col-sm-2 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt4_2" type="text"
								class="form-control" value="<?php echo $opt4[2]; ?>" id="opt4_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_3" class="col-sm-2 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt4_3" type="text"
								class="form-control" value="<?php echo $opt4[3]; ?>" id="opt4_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_4" class="col-sm-2 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="opt4_4" type="text"
								class="form-control" value="<?php echo $opt4[4]; ?>" id="opt4_4"> 
							</div>
						</div>
								</td>			
							</tr>
							<tr>
								<td>
									
						<legend>Itinerarios 4º ESO</legend>

						<table class='table' style="background-color:transparent;">
						<tr>
							<td>
								<legend>Itinerario 1</legend>
						<div class="form-group">
							<label for="it41_0" class="col-sm-4 control-label">Descripción</label>
							<div class="input-group col-sm-5">
							<input name="it41_0" type="text"
								class="form-control" value="<?php echo $it41[0]; ?>" id="it41_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_1" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it41_1" type="text"
								class="form-control" value="<?php echo $it41[1]; ?>" id="it41_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_2" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it41_2" type="text"
								class="form-control" value="<?php echo $it41[2]; ?>" id="it41_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_3" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it41_3" type="text"
								class="form-control" value="<?php echo $it41[3]; ?>" id="it41_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_4" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="it41_4" type="text"
								class="form-control" value="<?php echo $it41[4]; ?>" id="it41_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_5" class="col-sm-4 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="it41_5" type="text"
								class="form-control" value="<?php echo $it41[5]; ?>" id="it41_5"> 
							</div>
						</div>
								</td>

								<td>
						<legend>Itinerario 2</legend>
						<div class="form-group">
							<label for="it42_0" class="col-sm-4 control-label">Descripción</label>
							<div class="input-group col-sm-5">
							<input name="it42_0" type="text"
								class="form-control" value="<?php echo $it42[0]; ?>" id="it42_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_1" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it42_1" type="text"
								class="form-control" value="<?php echo $it42[1]; ?>" id="it42_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_2" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it42_2" type="text"
								class="form-control" value="<?php echo $it42[2]; ?>" id="it42_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_3" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it42_3" type="text"
								class="form-control" value="<?php echo $it42[3]; ?>" id="it42_3"> 
							</div>
						</div>
								</td>	

								<td>
						<legend>Itinerario 3</legend>
						<div class="form-group">
							<label for="it43_0" class="col-sm-4 control-label">Descripción</label>
							<div class="input-group col-sm-5">
							<input name="it43_0" type="text"
								class="form-control" value="<?php echo $it43[0]; ?>" id="it43_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_1" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it43_1" type="text"
								class="form-control" value="<?php echo $it43[1]; ?>" id="it43_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_2" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it43_2" type="text"
								class="form-control" value="<?php echo $it43[2]; ?>" id="it43_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_3" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it43_3" type="text"
								class="form-control" value="<?php echo $it43[3]; ?>" id="it43_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_4" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="it43_4" type="text"
								class="form-control" value="<?php echo $it43[4]; ?>" id="it43_4"> 
							</div>
						</div>
								</td>		
							</tr>
						</table>

					</td>
					</tr>
					</table>	
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
$(function ()  
{ 
	$('#datetimepicker1').datetimepicker({
		language: 'es',
		pickTime: false
	});
	
	$('#datetimepicker2').datetimepicker({
		language: 'es',
		pickTime: false
	});
});  
</script>
</body>
</html>
