<?php
require('../../bootstrap.php');
include("../../menu.php");
if (isset($_POST['profesor'])) $profesor = $_POST['profesor']; else $profesor=$_SESSION['profi'];
if (isset($_POST['actividad'])) $actividad = $_POST['actividad'];
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
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos WHERE departamento NOT LIKE 'Administracion' AND departamento NOT LIKE 'admin' AND departamento NOT LIKE 'Conserjeria' ORDER BY nombre ASC"); ?>
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
						    <label for="actividad">Grupo (Asignatura)</label>
						    <?php $result = mysqli_query($db_con, "SELECT id, a_asig, a_grupo, c_asig, a_aula, dia, hora FROM horw WHERE a_asig NOT LIKE '' AND a_aula NOT LIKE '' AND prof='".$profesor."' GROUP BY a_grupo, a_asig ORDER BY dia, hora, a_grupo"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="actividad" name="actividad" onchange="submit()">
						    	<option></option>
								<?php $dia=0; $hora=0;$cont=0;$grupo='';?>
						    	<?php while($row = mysqli_fetch_array($result)):?>
								<?php if(($row['dia']==$dia) && ($row['hora']==$hora)): ?>
								<?php		$grupo=$grupo.'+'.$row['a_grupo'];$cont=$cont+1; ?>
								<?php else: ?>
								<?php 		if($cont>0):?>
												<option value="<?php echo $profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$casig.'==>'.$id; ?>"<?php echo (isset($actividad) && $profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$casig == $actividad) ? 'selected' : ''; ?>><?php echo $grupo.' ('.$asig.')'; ?></option>
								<?php 		endif; ?>
								<?php		$id=$row['id'];$dia=$row['dia']; $hora=$row['hora']; $grupo=$row['a_grupo']; $asig=$row['a_asig']; $aula=$row['a_aula']; $casig=$row['c_asig']; $cont=1; ?>
								<?php endif; ?>		  
							   	<?php endwhile; ?>
								<option value="<?php echo $profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$casig.'==>'.$id; ?>"<?php echo (isset($actividad) && $profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$casig == $actividad) ? 'selected' : ''; ?>><?php echo $grupo.' ('.$asig.')'; ?></option>
						    </select>
						    <?php else: ?>
						    <select class="form-control" id="actividad" name="actividad" disabled>
						    	<option value=""></option> 
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>
						  <!--  <button type="submit" class="btn btn-primary" name="enviar">Consultar</button> -->
						</fieldset>
					</form>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
	
<?php include("../../pie.php"); ?>

</body>
</html>
