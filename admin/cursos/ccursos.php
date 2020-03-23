<?php
require('../../bootstrap.php');

$mostrarTodas = (isset($_POST['mostrarTodas']) && $_POST['mostrarTodas'] == 1) ? 1 : 0;
$profesor = $_SESSION['profi'];

include("../../menu.php");
include("../informes/menu_alumno.php");

?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Alumnos y Grupos <small>Listas de los Grupos</small></h2>
	</div>
	
	<br>
	<br>

	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6 col-sm-offset-3">
			
			<div class="well">
				
				<form method="post" action="">
					<fieldset>
						<legend>Alumnos por Grupo</legend>
						
						<div class="form-group">
							<?php 
							if (acl_permiso($carg, array('1','7')) || $mostrarTodas == 1) {
								$result = mysqli_query($db_con, "SELECT DISTINCT nomunidad FROM unidades ORDER BY nomunidad ASC");
								$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo WHERE m.abrev LIKE '%**%' ORDER BY u.nomunidad ASC");
							}
							else {
								$result = mysqli_query($db_con, "SELECT DISTINCT grupo AS nomunidad FROM profesores WHERE profesor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY grupo ASC");
								$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo JOIN profesores AS p ON u.nomunidad = p.grupo WHERE m.abrev LIKE '%**%' AND p.profesor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY u.nomunidad ASC");											
							}

							$array_unidades = array();
							while ($row = mysqli_fetch_array($result)) {
								array_push($array_unidades, $row);
							}
							while ($row = mysqli_fetch_array($result_pmar)) {
								array_push($array_unidades, $row);
							}

							asort($array_unidades);
							?>
							<select class="form-control" name="unidad[]" size="6" multiple<?php if ($todos) echo ' disabled'; ?>>
							<?php foreach ($array_unidades as $unidad): ?>
								<option value="<?php echo $unidad['nomunidad']; ?>" <?php echo (isset($curso) && $curso == $unidad['nomunidad']) ? 'selected' : ''; ?>><?php echo $unidad['nomunidad']; ?></option>
							<?php endforeach; ?>
							</select>
							</div>
							
							<div class="checkbox">
								<label>
									<input type="checkbox" name="mostrarTodas" value="1" onClick="submit();" <?php echo ($mostrarTodas == 1) ? 'checked' : ''; ?>> Mostrar todas las unidades
								</label>
							</div>

							<p class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples unidades. Si no seleccionas ninguna se mostrarán todas unidades en el listado.</p>

							<input type="hidden" name="todasUnidades" value="<?php echo $mostrarTodas; ?>">

							<button type="submit" class="btn btn-primary" name="listadoSimple" formaction="listados.php" formtarget="_blank">Listado simple</button>
							<button type="submit" class="btn btn-primary" name="listadoAsignaturas" formaction="listados_asigmat.php" formtarget="_blank">Listado con asignaturas</button>
						</fieldset>
						
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->

	</div><!-- /.row -->
	
</div><!-- /.container -->

<?php include("../../pie.php"); ?>
</body>
</html>
