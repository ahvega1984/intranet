<?php
require('../../bootstrap.php');

// VALIDACION DE FORMULARIOS
if (isset($_POST['submit1'])) {
	include 'datos.php';
	exit();
}
elseif (isset($_POST['submit2'])) {
	include 'lista_grupo.php';
	exit();
}
elseif (isset($_POST['submit0'])) {
	include 'datos.php';
	exit();
}


include("../../menu.php");

if (isset($_POST['unidad'])) {
	$unidad = $_POST['unidad'];
}
elseif (isset($_GET['unidad'])) {
	$unidad = $_GET['unidad'];
} 
else
{
$unidad="";
}
if (isset($_GET['nombre_al'])) {
	$nombre = $_GET['nombre_al'];
}
else{
	$nombre = $nombre_al;
}

?>
<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Información de los alumno <small>Informes y Datos</small></h2>
	</div>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">

		<div class="col-sm-8">
			<legend>Informe de un alumno</legend>	
			<br>
		</div>

	</div>

	<div class="row">
	
		<!-- MODULO -->
		<div class="col-sm-8">
			
			<div class="well">
				
				<form method="post" action="">
					
					<div class="row">
						
						<div class="col-sm-6">
							
							<fieldset>
								<legend>Datos del alumno</legend>
								
								<div class="row">
									<!--FORMLISTACURSOS
									<div class="col-sm-6">
											<div class="form-group">
												<label for="curso">Curso</label>
											</div>
									</div>
									FORMLISTACURSOS//-->
									
									<div class="col-sm-8">
										<div class="form-group">
										  <label for="unidad">Unidad</label>
											<?php $result = mysqli_query($db_con, "SELECT DISTINCT unidad, SUBSTRING(unidad,2,1) AS orden FROM alma ORDER BY orden ASC"); ?>
											<?php if(mysqli_num_rows($result)): ?>
											<select class="form-control" id="unidad" name="unidad" onchange="submit()">
												<option></option>
												<?php while($row = mysqli_fetch_array($result)): ?>
												<option value="<?php echo $row['unidad']; ?>" <?php echo ($row['unidad'] == $unidad) ? 'selected' : ''; ?>><?php echo $row['unidad']; ?></option>
												<?php endwhile; ?>
												<?php mysqli_free_result($result); ?>
											</select>
											<?php else: ?>
											<select class="form-control" name="unidad" disabled>
												<option></option>
											</select>
											<?php endif; ?>
										</div>
									</div>
								</div>
								
								<div class="form-group">
							    <label for="nombre">Alumno/a</label>
							    <?php $result = mysqli_query($db_con, "SELECT DISTINCT APELLIDOS, NOMBRE, CLAVEAL FROM FALUMNOS WHERE unidad='$unidad' ORDER BY APELLIDOS ASC"); ?>
							    <?php if(mysqli_num_rows($result)): ?>
							    <select class="form-control" id="nombre" name="nombre">
							    	<option></option>
							    	<?php while($row = mysqli_fetch_array($result)): ?>
							    	<option value="<?php echo $row['APELLIDOS'].', '.$row['NOMBRE'].' --> '.$row['CLAVEAL']; ?>" <?php echo (isset($nombre) && $row['APELLIDOS'].', '.$row['NOMBRE'].' --> '.$row['CLAVEAL'] == $nombre) ? 'selected' : ''; ?>><?php echo $row['APELLIDOS'].', '.$row['NOMBRE']; ?></option>
							    	<?php endwhile; ?>
							    	<?php mysqli_free_result($result); ?>
							    </select>
							    <?php else: ?>
							    <select class="form-control" name="nombre" disabled>
							    	<option></option>
							    </select>
							    <?php endif; ?>
							  </div>
							  
							  <?php if (file_exists(INTRANET_DIRECTORY . '/config_datos.php')): ?>
							  <div class="form-group">
							    <label for="c_escolar">Curso escolar</label>
							    
							    <select class="form-control" id="c_escolar" name="c_escolar">
							    	<?php $exp_c_escolar = explode("/", $config['curso_actual']); ?>
							    	<?php for($i=0; $i<5; $i++): ?>
							    	<?php $anio_escolar = $exp_c_escolar[0] - $i; ?>
							    	<?php $anio_escolar_sig = substr(($exp_c_escolar[0] - $i + 1), 2, 2); ?>
							    	<?php if($i == 0 || (isset($config['db_host_c'.$anio_escolar]) && $config['db_host_c'.$anio_escolar] != "")): ?>
							    	<option value="<?php echo $anio_escolar.'/'.$anio_escolar_sig; ?>"><?php echo $anio_escolar.'/'.$anio_escolar_sig; ?></option>
							    	<?php endif; ?>
							    	<?php endfor; ?>
							    </select>
							  </div>
							  <?php endif; ?>
							  
							</fieldset>
							
						</div><!-- /.col-sm-6 -->
						
						
						
						<div class="col-sm-6">
							
							<fieldset>
								<legend>Opciones del informe</legend>
								
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="faltas" value="faltas" <?php echo ($config['mod_asistencia']) ? '' : 'disabled' ?> checked> Resumen de faltas de asistencia
									</label>
								</div>
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="faltasd" value="faltasd" <?php echo ($config['mod_asistencia']) ? '' : 'disabled' ?> checked> Faltas de asistencia detalladas
									</label>
								</div>
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fechorias" value="fechorias" checked> Problemas de convivencia
									</label>
								</div>
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="tutoria" value="tutoria" checked> Informes de tutoría
									</label>
								</div>
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="notas" value="notas" checked> Notas de evaluación
									</label>
								</div>
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="act_tutoria" value="act_tutoria" checked> Acciones de tutoría
									</label>
								</div>
								
								<div class="checkbox">
									<label>
										<input type="checkbox" name="horarios" value="horarios" <?php echo ($config['mod_horarios']) ? '' : 'disabled' ?> checked> Horario del alumno
									</label>
								</div>
							</fieldset>
							
							<button type="submit" class="btn btn-primary" name="submit1" formaction="index.php" checked>Consultar</button>
							
						</div><!-- /.col-sm-6 -->
						
					</div><!-- /.row -->
					
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-8 -->
	
	</div><!-- /.row -->
	
	<br>
	<br>

	<!-- SCAFFOLDING -->
	<div class="row">
		<div class="col-sm-8">
			<legend>Datos de los Alumnos por Nombre o Grupo</legend>	
			<br>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4">
		<!-- COLUMNA IZQUIERDA -->			
			<div class="well">
				
				<form method="post" action="">
					<fieldset>
						<legend>Búsqueda por Nombre/Apellidos</legend>
						
						<div class="form-group">
					    <label for="campoApellidos">Apellidos</label>
					    <input type="text" class="form-control" name="apellidos" id="campoApellidos" placeholder="Apellidos" maxlength="60">
					  </div>
					  
					  <div class="form-group">
					    <label for="campoNombre">Nombre</label>
					    <input type="text" class="form-control" name="nombre" id="campoNombre" placeholder="Nombre" maxlength="60">
					    <p class="help-block">No es necesario escribir el nombre o los apellidos completos de los alumnos.</p>
					  </div>
					  
					  <button type="submit" class="btn btn-primary" name="submit0">Consultar</button>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
		</div><!-- /.col-sm-6 -->
		
		
		<!-- COLUMNA DERECHA -->
		<div class="col-sm-4">
			
			<div class="well">
				
				<form method="post" action="">
					<fieldset>
						<legend>Datos por Grupos</legend>
						
						<div class="form-group">
					    <select class="form-control" name="unidad[]" multiple size="6">
					    	 <?php unidad($db_con); ?>
					    </select>
					    <p class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el ratón para seleccionar múltiples grupos.</p>
					  </div>
					  <div class="checkbox">
					  <label>
					    <input type="checkbox" name="sin_foto" value="1" checked> Con foto</label>
					  </div>
					  <button type="submit" class="btn btn-primary" name="submit1">Consultar</button>
					  <button type="submit" class="btn btn-primary" name="submit2">Imprimir</button>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
	
	</div><!-- /.row -->
	
</div><!-- /.container -->

<?php include("../../pie.php"); ?>
</body>
</html>