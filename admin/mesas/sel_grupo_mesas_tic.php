<?php
require('../../bootstrap.php');

//if (file_exists('config.php')) {
//	include('config.php');
//}

// acl_acceso($_SESSION['cargo'], array(1, 8));

//if (isset($_SESSION['mod_tutoria'])) {
//	header('Location:'.'index.php');
//}


include("../../menu.php");
if (isset($_POST['profesor'])) $profesor = $_POST['profesor'];
if (isset($_POST['grupo'])) $grupo = $_POST['grupo'];
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Alumnos y Grupos <small>Asignación de mesas en aulas TIC</small></h2>
		</div>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-6 col-sm-offset-3">
				
				<div class="well">
				<!-- El Equipo Directivo y el Coordinador tienen acceso a las plantillas de todos los profesores-->
				<?php $esTIC = 0; ?>
				<?php if (file_exists("../../TIC/config.php")) {
						include("../../TIC/config.php");
						$esTIC = ($config['tic']['coordinador'] == $_SESSION['profi']) ? 1 : 0;
						} ?>
					<?php if($_SESSION['cargo']=='1' || $esTIC ) {?>
					<form method="post" action=""> 
						<fieldset>
							<legend>Seleccione profesor, grupo y asignatura</legend>
							
							<div class="form-group">
						    <label for="profesor">Profesor</label>
						    <?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos ORDER BY nombre ASC"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="profesor" name="profesor" onchange="submit()">
						    	<option></option>
						    	<?php while($row = mysqli_fetch_array($result)): ?>
						    	<option value="<?php echo $row['nombre']; ?>"<?php echo (isset($profesor) && $row['nombre'] == $profesor) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
						    	<?php endwhile; ?>
						    </select>
						    <?php else: ?>
						    <select class="form-control" id="profesor" name="profesor" disabled>
						    	<option value=""></option> 
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>
						  
						  <!--<button type="submit" class="btn btn-primary" name="enviar">Consultar</button> -->
					  </fieldset>
					</form>
					<?php } ?>
										
					<form method="post" action="consulta_mesas_tic.php">
						<fieldset>
														
							<div class="form-group">
						    <label for="actividad">Grupo (Asignatura) [Aula]</label>
						    <?php $result = mysqli_query($db_con, "SELECT DISTINCT a_asig, a_grupo, a_aula, c_asig FROM horw WHERE prof='".$profesor."' ORDER BY a_grupo ASC"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="actividad" name="actividad">
						    	<option></option>
						    	<?php while($row = mysqli_fetch_array($result)): ?>
								<?php if($row['a_grupo']<>'' && $row['a_aula']<>''):?>
						    	<option value="<?php echo $profesor.'==>'.$row['a_grupo'].'==>'.$row['a_asig'].'==>'.$row['a_aula'].'==>'.$row['c_asig']; ?>"<?php echo (isset($grupo) && $row['a_grupo'].'==>'.$row['a_asig'].'==>'.$row['a_aula']  == $grupo) ? 'selected' : ''; ?>><?php echo $row['a_grupo'].' ('.$row['a_asig'].')'.' ['.$row['a_aula'].']'; ?></option>
								<?php endif; ?>
						    	<?php endwhile; ?>
						    </select>
						    <?php else: ?>
						    <select class="form-control" id="actividad" name="actividad" disabled>
						    	<option value=""></option> 
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>
						  
						  <button type="submit" class="btn btn-primary" name="enviar">Consultar</button>
					  </fieldset>
					</form>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
	
<?php include("../../pie.php"); ?>

</body>
</html>
