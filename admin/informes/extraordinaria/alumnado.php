<?php
require('../../../bootstrap.php');

//PROCESAR DATOS ENVIADOS
if (isset($_POST['enviar_datos'])) {
	$id_informe = $_POST['id_informe'];
	mysqli_query($db_con,"delete from informe_extraordinaria_alumnos where id_informe = '".$id_informe."'");
	foreach ($_POST as $clave => $val) {
		unset($observaciones);
		if (!empty($val) and $clave !== 'enviar_datos' and $clave !== 'grupo' and $clave !== 'id_informe') {	
		$tr_clave = explode("-", $clave);
		$claveal = $tr_clave[0];
		$id_contenido = $tr_clave[2];
			if ($id_contenido == 0) {
				$observaciones = $val;
			}
		//echo "$clave => $val<br>";
		$result = mysqli_query($db_con,"INSERT into informe_extraordinaria_alumnos (id_informe,id_contenido,claveal,actividades) VALUES ('$id_informe','$id_contenido','$claveal','$observaciones')");
			if (! $result) {
				$msg_error = "No se ha podido guardar algún elemento del informe. Error: ".mysqli_error($db_con);
			}
			else{
				$msg_acierto = "Se han guardado correctamente los datos del informe sobre la evaluación extraordinaria para los alumnos afectados";
			}
		}
	}
		$id_informe = $_POST['id_informe'];
		$grupo = $_POST['grupo'];
}

// COMPROBAMOS SI SE HA SELECCIONADO LA UNIDAD
if (isset($_GET['grupo'])) {	$grupo = $_GET['grupo'];}
if (isset($_GET['id_informe'])) {	$id_informe = $_GET['id_informe'];}

$materias = mysqli_query($db_con,"select codigo, nombre, curso from materias where grupo = '$grupo' and nombre = (select asignatura from informe_extraordinaria where id_informe = '$id_informe') and abrev not like '%\_%'");
if (mysqli_num_rows($materias)>1) {
	$nb="";
	while ($materia_informe = mysqli_fetch_array($materias)) {
		$nb++;
		${codigo.$nb}=$materia_informe['codigo'];
	}
		$asignatura = $materia_informe['nombre'];
}
else{
	$materia_informe = mysqli_fetch_array($materias);
	$codigo1 = $materia_informe['codigo'];
	$asignatura = $materia_informe['nombre'];
}

include("../../../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<div class="page-header">
			<h2>Informe individual para la evaluación extraordinaria <small> <br>Selección de alumnos y actividades</small></h2>
		</div>

		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger alert-fadeout">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		<?php if(isset($msg_acierto) && $msg_acierto): ?>
		<div class="alert alert-success alert-fadeout">
			<?php echo $msg_acierto; ?>
		</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-sm-12">
				<p class="help-block">En esta lista se presentan los alumnos que tienen al menos 1 evaluación suspensa en tu asignatura y son, por lo tanto, posibles candidados a un informe individual de cara a la <u>evaluación extraordinaria</u>. Marca las casillas de los contenidos (<em>UNIDADES</em>) que el alumno debe preparar para la evaluación extraordinaria. Si no marcas ninguna casilla en un alumno, éste no tendrá informe individualizado en tu asignatura.<br>Si necesitas personalizar las actividades y tareas de un alumno, puedes añadir información únuca para él en el campo de observaciones.</p>
				<br>
				<table class="table table-striped table-bordered table-condensed">
					<thead>
						<tr>
							<th>Alumno</th>
							<th>Unidades</th>
							<th>Observaciones individuales</th>
						</tr>
					</thead>
					<tbody>
						<form action="alumnado.php" method="post">
						<?php 
						

						$alumno = mysqli_query($db_con,"select claveal, claveal1, unidad, curso, concat(nombre,' ',apellidos) as nombre_alumno from alma where unidad = '".$grupo."'");
						while($alumnos = mysqli_fetch_array($alumno)):
							
							$candidato="";

							$nota = mysqli_query($db_con, "select notas1, notas2, notas3 from notas where claveal = '".$alumnos['claveal1']."'");
							$notas = mysqli_fetch_array($nota);
							for ($i=1; $i < 4 ; $i++) { 
								${tr_.$i} = explode(";", $notas['notas'.$i]);
								foreach (${tr_.$i} as $val) {
									$nota_asig = explode(":",$val);
									$asignatura_al = $nota_asig[0];
									$nota_al = $nota_asig[1];
									if (stristr($alumnos['curso'], "E.S.O")==TRUE) {
										if ($asignatura_al == $codigo1 and $nota_al < "347" and $nota_al !== '339') {
										$candidato = 1;
									}
									}
									elseif (stristr($alumnos['curso'], "Bachillerato")==TRUE) {
										if (($asignatura_al == $codigo1 OR $asignatura_al == $codigo2) and $nota_al < "427") {
										$candidato = 1;
									}
									}
									
								}
							}
							if ($candidato==1) { 
								$al_reg = mysqli_query($db_con,"select * from informe_extraordinaria_alumnos where claveal = '".$alumnos['claveal']."'");
								while($reg = mysqli_fetch_array($al_reg)){
								$reg_informe = $reg['id_informe'];
								$reg_contenido = $reg['id_contenido'];
								$reg_observaciones = $reg['actividades'];
								$reg_datos = $alumnos['claveal']."-".$reg_informe."-".$reg_contenido;
								$ya_datos[] = $reg_datos;
								}
								?>
							<tr>
								<td class="text-danger" style="width:260px; vertical-align:middle; font-weight:bold; "><?php echo $alumnos['nombre_alumno']; ?></td>
								<td><div class="form-group"><?php 
								$tema = mysqli_query($db_con,"select id_contenido, unidad, titulo from informe_extraordinaria_contenidos where id_informe = '".$id_informe."' order by id_contenido");
								while($temas = mysqli_fetch_array($tema)) { 
									$sel = "";
									if (in_array($alumnos['claveal']."-".$id_informe."-".$temas['id_contenido'], $ya_datos)) {
										$sel = 'checked';
									}
									else{
										$sel = '';
									}
									?>
									<div class="checkbox">
										<label class="checkbox-inline" for="checkbox_1">
									  	<input type="checkbox" name="<?php echo $alumnos['claveal']."-".$id_informe."-".$temas['id_contenido']; ?>" value="1" id="checkbox_1" <?php echo $sel; ?>>
									  	<?php echo $temas['unidad']." (<b>".$temas['titulo']."</b>)"; ?>	  
									  </label>
									</div>

								<?php }?>	
								</div>								
								</td>
								<td>
									<?php
									unset($actividad);
									$al_act = mysqli_query($db_con,"select actividades from informe_extraordinaria_alumnos where claveal = '".$alumnos['claveal']."' and id_informe = '$id_informe' and id_contenido='0'");
									if (mysqli_num_rows($al_act)>0) {
										$actividad = mysqli_fetch_array($al_act);
									}

									?>
									<textarea class="form-control" name="<?php echo $alumnos['claveal']."-".$id_informe."-0";?>" rows="8"><?php echo $actividad['actividades'];?></textarea></td>
							</tr>
							<?php }	?>
						
							<?php endwhile; ?>
							<tr><td colspan="3">  
								<input type="hidden" name="id_informe" value="<?php echo $id_informe;?>" >
								<input type="hidden" name="grupo" value="<?php echo $grupo;?>" >
								<input type="submit" class="btn btn-primary" name="enviar_datos" value="Guardar los informes" ></td></tr>
						</form>
					</tbody>
				</table>

			</div><!-- /.col-sm-12-->

		</div><!-- /.row -->
		


	</div>

	<?php include("../../../pie.php"); ?>

</body>
</html>
