<?php
require('../bootstrap.php');

$esTIC = 0;
if (file_exists("config.php")) {
	include("config.php");
	$esTIC = ($config['tic']['coordinador'] == $_SESSION['profi']) ? 1 : 0;
}

if (isset($_POST['profesor'])) $profesor = $_POST['profesor']; else $profesor=$_SESSION['profi'];
if (isset($_POST['actividad'])) $actividad = $_POST['actividad'];

include("../menu.php");
include("menu.php");
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Asignación de mesas TIC</small></h2>
		</div>


		<!-- SCAFFOLDING -->
		<div class="row">

			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-6 col-sm-offset-3">

				<div class="well">

					<form method="post" action="">

						<fieldset>

							<?php if (acl_permiso($_SESSION['cargo'], array('1')) || $esTIC): ?>
							<legend>Seleccione profesor, unidad y asignatura</legend>
							<?php else: ?>
							<legend>Seleccione unidad y asignatura</legend>
							<?php endif; ?>

							<!-- El equipo directivo y el coordinador TIC tienen acceso a las plantillas de todos los profesores -->
							<?php if (acl_permiso($_SESSION['cargo'], array('1')) || $esTIC): ?>
							<div class="form-group">
						    <label for="profesor">Profesor</label>
								<?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Servicio Técnico y/o Mantenimiento' AND departamento <> '' ORDER BY nombre ASC"); ?>
						    <?php if (mysqli_num_rows($result)): ?>
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
							<?php endif; ?>

							<div class="form-group">
						    <label for="actividad">Unidad (Asignatura)</label>
						    <?php $result = mysqli_query($db_con, "SELECT DISTINCT a_grupo, a_asig, asig, c_asig, a_aula FROM horw WHERE prof='".$profesor."' AND a_grupo <> '' AND a_aula <> '' ORDER BY dia, hora, a_grupo"); ?>
						    <?php if (mysqli_num_rows($result)): ?>
						    <select class="form-control" id="actividad" name="actividad">
						    	<option value=""></option>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<option value="<?php echo $profesor.'==>'.$row['a_grupo'].'==>'.$row['a_asig'].'==>'.$row['a_aula'].'==>'.$row['c_asig']; ?>"><?php echo $row['a_grupo'].' ('.$row['asig'].')'; ?></option>
									<?php endwhile; ?>
								</select>
						    <?php else: ?>
						    <select class="form-control" id="actividad" name="actividad" disabled>
						    	<option value=""></option>
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>

							<button type="submit" class="btn btn-primary" formaction="mesas_tic_consulta.php" name="enviar">Consultar</button>
						</fieldset>
					</form>

				</div><!-- /.well -->

			</div><!-- /.col-sm-6 -->


		</div><!-- /.row -->

	</div><!-- /.container -->

<?php include("../pie.php"); ?>

</body>
</html>
