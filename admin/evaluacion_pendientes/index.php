<?php
if (isset($_POST['consultar'])) {
	include("consulta_pendientes.php");
	exit();
}

require('../../bootstrap.php');

include("../../menu.php"); 
include("menu.php"); 

$depto = $_SESSION ['dpt'];
$profe_dep = $_SESSION ['profi'];
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display:inline;">Evaluación de Pendientes <small>Listado de pendientes por asignatura</small></h2>
	</div>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	<br>
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-5 col-sm-offset-1">
			
			<div class="well">
				
				<form method="post" action="lista_pendientes.php">
					<fieldset>
						<legend>Registro de Calificaciones de Pendientes</legend>
						
						<div class="form-group">
						  <select class="form-control" name="select">
							<?php 

							$query_pendientes = "SELECT DISTINCT `codigo`, `curso`, `nombre`, `abrev` FROM `asignaturas` WHERE `abrev` LIKE '%\_%' AND `curso` NOT LIKE '1º %' ORDER BY `curso` ASC, `nombre` ASC";
							$result_pendientes = mysqli_query($db_con, $query_pendientes);

							$curso_aux = "";
							$i = 0;

							while ($row_pendientes = mysqli_fetch_array($result_pendientes)) { 
								if ($curso_aux != $row_pendientes['curso']) {
									if ($i > 0) {
										echo '</optgroup>';
									}
									echo '<optgroup label="'.$row_pendientes['curso'].'">';
								}

								$result_alumnos_pendientes = mysqli_query($db_con, "SELECT `claveal` FROM `pendientes` WHERE `codigo` = '".$row_pendientes['codigo']."'");
								if (mysqli_num_rows($result_alumnos_pendientes)) {
									echo '<option value="'.$row_pendientes['codigo'].'">'.str_pad($row_pendientes['codigo'], 6, 0, STR_PAD_LEFT).' - '.$row_pendientes['nombre'].' ('.$row_pendientes['abrev'].')</option>';
								}
							    								
								$curso_aux = $row_pendientes['curso'];
								$i++;
							}
							unset($aux);
							unset($i);
							?>
						  </select>
						</div>
					  
					  <button type="submit" class="btn btn-primary" name="poner">Registrar Calificaciones</button>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		<div class="col-sm-5">
			
			<div class="well">
			
			<form method="post" action="index.php">
					<fieldset>
						<legend>Consulta de Calificaciones</legend>
						
						<div class="form-group">
						<label>Curso</label>
						  <select class="form-control" name="curso" onChange="submit()" required="required">
<?php 
if(isset($_POST['curso'])){
echo "<option>".$_POST['curso']."</option>";
}
else{
echo "<option></option>";
}
	$asig2 = mysqli_query($db_con,"select distinct curso from alma, cursos where curso=nomcurso and unidad in (select distinct unidad from pendientes) and curso not like '1%' order by idcurso");
	while($asignatur2 = mysqli_fetch_row($asig2)){
	$curso1 = $asignatur2[0];
?>
    <option><?php  echo $curso1;?></option>
    <?php 
	}
?>
						  </select>
						</div>
						
						<div class="form-group">
						<label>Grupo</label>
						  <select class="form-control" name="unidad">
						  <option>Cualquiera</option>

<?php 
	$uni = mysqli_query($db_con,"select distinct unidad from alma where curso = '".$_POST['curso']."' order by unidad");
	while($uni2 = mysqli_fetch_row($uni)){
	$unidad = $uni2[0];
?>
    <option><?php  echo $unidad;?></option>
    <?php 
	}
?>

						  </select>
						</div>
						
						<div class="form-group">
						<label>Evaluación</label>
						  <select class="form-control" name="evaluacion">

    <option value='1'>1ª Evaluación</option>
     <option value='2'>2ª Evaluación</option>
      <option value='3'>Evaluación Ordinaria</option>
       <option value='4'>Evaluación Extraordinaria</option>

						  </select>
						</div>
					  <button type="submit" class="btn btn-primary" name="consultar">Consultar Calificaciones</button>
				  </fieldset>
				</form>
				
			</div>
			
			</div>
	
	</div><!-- /.row -->
	
</div><!-- /.container -->

<?php include("../../pie.php"); ?>
</body>
</html>
