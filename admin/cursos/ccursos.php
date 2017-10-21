<?php
require('../../bootstrap.php');

$todos = (isset($_POST['todos']) && $_POST['todos'] == 1) ? 1: 0; 

$profesor = $_SESSION['profi'];

include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Listado de alumnos <small>Consultas</small></h2>
	</div>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form method="post" action="">
					<fieldset>
						<legend>Alumnos por grupo</legend>
						
						<div class="form-group">
							<?php 
							$result = mysqli_query($db_con, "SELECT nomunidad FROM unidades ORDER BY nomunidad ASC");
							$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo WHERE m.abrev LIKE 'AMB%' ORDER BY u.nomunidad ASC");

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
						    <p class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el ratón para seleccionar múltiples grupos.</p>
						  </div>
						  
						  <div class="checkbox">
						  	<label>
						    	<input type="checkbox" name="todos" value="1" onclick="submit()" <?php echo ($todos == 1) ? 'checked' : '' ;?>> Mostrar todos los grupos
						    </label>
						  </div>
						  
						  <button type="submit" class="btn btn-primary" name="listadoSimple" formaction="listados.php" formtarget="_blank">Listado simple</button>
						  <button type="submit" class="btn btn-primary" name="listadoAsignaturas" formaction="listados_asigmat.php" formtarget="_blank">Listado con asignaturas</button>
						</fieldset>
						
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		
		<!-- COLUMNA DERECHA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form method="post" action="excel.php">
<?php
if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE or stristr($_SESSION['cargo'],'5') == TRUE or stristr($_SESSION['cargo'],'d') == TRUE or $todos == "1"){
	$query_Recordset1 = "SELECT distinct unidad FROM alma ORDER BY unidad ASC";
}
else{
	$query_Recordset1 = "SELECT grupo, materia, nivel FROM profesores WHERE profesor = '$profesor'";
}
$Recordset1 = mysqli_query($db_con, $query_Recordset1) or die(mysqli_error($db_con));
$row_Recordset1 = mysqli_fetch_array($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
$query_Recordset2 = "SELECT * FROM alma ORDER BY apellidos ASC";
$Recordset2 = mysqli_query($db_con, $query_Recordset2) or die(mysqli_error($db_con));
$row_Recordset2 = mysqli_fetch_array($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);
?>
					<fieldset>
						<legend>Exportar en formato XLS</legend>
						
						<div class="form-group">
					    <select class="form-control" name="select">
					    	     <?php 
					    	 do {  
					    	 ?>
					    	     <option><?php  echo $row_Recordset1[0]?></option>
					    	     <?php 
					    	 } while ($row_Recordset1 = mysqli_fetch_array($Recordset1));
					    	   $rows = mysqli_num_rows($Recordset1);
					    	 ?>
					    </select>
					    <p class="help-block">Selecciona el grupo para exportar los datos al formato de las hojas de cálculo, como Calc o Excel.</p>
					  </div>
					  
					  <div class="checkbox">
					  	<label>
					    	<input type="checkbox" name="asignaturas" value="1"> Mostrar asignaturas
					    </label>
					  </div>
					  <div class="checkbox">
					  	<label>
					    	<input type="checkbox" name="datos" value="1"> Mostrar datos del alumno
					    </label>
					  </div>
					  <br>
					  <button type="submit" class="btn btn-primary" name="boton1">Exportar</button>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
	
	</div><!-- /.row -->
	
</div><!-- /.container -->

<?php include("../../pie.php"); ?>
</body>
</html>
