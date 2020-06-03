<?php
require('../../bootstrap.php');
?>

<?php
include("../../menu.php");

include("menu_alumno.php");

?>

<?php 

if(!isset($_POST['consultar_notas']) and empty($_POST['unidad'])){
?>
<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display:inline;">Alumnos y Grupos <small>Calificaciones de un grupo></small></h2>
	</div>
	
	<br>

		<div class="row">
	
		<div class="col-sm-6 col-sm-offset-3">

			<div class="well">
				
				<form action="notas_grupo.php" method="post">

					<fieldset>
						
						<legend>Informe de evaluaciones por grupo</legend>
						
						<div class="form-group">							
							<select class="form-control" name="unidad">
							<?php 
							$grupos = mysqli_query($db_con,"select nomunidad from unidades order by idcurso, idunidad");
							while ($sel_grupo = mysqli_fetch_array($grupos)) { ?>
								<option><?php echo $sel_grupo['nomunidad']; ?></option>
							<?php 
							}
							?>
							</select>
					  	</div>

						<p class="help-block">Esta consulta presenta las calificaciones de los alumnos de un grupo en las distintas evaluaciones de las asignaturas del curso escolar.</p>

						<br>

						<button type="submit" class="btn btn-primary" name="consultar_notas">Consultar notas de las evaluaciones</button>
						

				  </fieldset>
				  
				</form>
				
			</div><!-- /.well -->
		</div>
	</div>
<?php 
}
else{
?>


<div class="container-fluid">

	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display:inline;">Alumnos y Grupos <small>Calificaciones del grupo <?php echo $_POST['unidad'];?></small></h2>
	
		<form method="post" action="">
			<div class="pull-right">
				<select	class="form-control input-sm hidden-print" name="unidad" onchange="submit()" style="width:280px;">
					<?php 
					$grupos = mysqli_query($db_con,"select nomunidad from unidades order by idcurso, idunidad");
					while ($sel_grupo = mysqli_fetch_array($grupos)) { ?>
						<option value="<?php echo $sel_grupo['nomunidad']; ?>" <?php echo ($_POST['unidad'] == $sel_grupo['nomunidad']) ? 'selected' : ''; ?>><?php echo $sel_grupo['nomunidad']; ?></option>
					<?php 
					}
					?>
				</select> 
				<input name="consultar_notas" type="hidden">			
			</div>
		</form>

	</div>

	<div class="row">
		<br>
		<table class="table" style="width: auto">
			<tr>
				<td style="vertical-align: top; padding: 1px">
				<table class='table table-bordered table-condensed table-striped'	style='width: auto;'>
					<tr>
						<td class='info'>Materias</td>
						<?php
						$curso0 = "select distinct codigo, abrev from materias where materias.grupo = '".$_POST['unidad']."' and abrev not like '%\_%' and abrev not like 'LIBD%' and abrev not like 'VE' and abrev not like 'Re%'";
						$curso20 = mysqli_query($db_con, $curso0);

						unset($asignaturas);

						while ($curso10 = mysqli_fetch_array($curso20))
						{
							echo "<td colspan='4' class='text-center info'><strong  style='color:#444'>".$curso10[1]."</strong></td>";
							$asignaturas[] = $curso10[1].":".$curso10[0];
						}

						?>
						<td colspan='4' class='text-center danger'><strong style='color:#ccc'>Nº Susp.</strong></td>
					</tr>
					<tr class="success">
						<td>Evaluaciones</td>

						<?php
						foreach ($asignaturas as $columnas) {
							for ($i=1; $i < 5; $i++) {
								if ($i<3) {
									echo "<td nowrap style='color:#666'><strong>".$i."ª</strong></td>";
								}
								elseif ($i==3) {
									echo "<td nowrap style='color:#666'><strong>Ord.</strong></td>";
								}
								else{
									echo "<td nowrap style='color:#666'><strong>Extr.</strong></td>";
								}
							}
						}
						?>
						<?php 
							for ($i=1; $i < 5; $i++) {
								if ($i<3) {
									echo "<td nowrap class='warning'><strong style='color:#ddd'>".$i."ª</strong></td>";
								}
								elseif ($i==3) {
									echo "<td nowrap class='warning'><strong style='color:#ddd'>Ord.</strong></td>";
								}
								else{
									echo "<td nowrap class='warning'><strong style='color:#ddd'>Extr.</strong></td>";
								}
							}
						?>
					</tr>
					<?php
					// Alumnos para presentar que tengan esa asignatura en combasi
					$resul = "select distinctrow alma.CLAVEAL, alma.matriculas, alma.APELLIDOS, alma.NOMBRE, alma.MATRICULAS, alma.combasi, alma.unidad, alma.curso from alma WHERE alma.unidad = '".$_POST['unidad']."' order by alma.apellidos, alma.nombre ASC";

					// echo $resul;
					$result = mysqli_query($db_con, $resul);
					while($row = mysqli_fetch_array($result))
					{
						for ($i=1; $i < 5; $i++) { 
							${n_susp.$i}="";				
						}
						$claveal = $row[0];
						$nombre_al =   $row[3];
						$apellidos =   $row[2];
						$nc = $claveal;					
						?>
					<tr>
						<td nowrap><?php	echo $apellidos.", ".$nombre_al;?></td>
					<?php

						$abrev_asig="";

						foreach ($asignaturas as $abrev_asig) {
							
							$tr_asig = explode(":", $abrev_asig);
							
							$asignatura = $tr_asig[1];
							$abrev = $tr_asig[0];
							

							for ($i=1; $i < 5; $i++) {
							echo "<td id='$claveal-$abrev-$i'>";
							${seneca.$i} = mysqli_query($db_con, "select notas".$i." from notas where claveal = (select claveal1 from alma where claveal = '$claveal')");

							${dato_seneca.$i} = mysqli_fetch_array(${seneca.$i});
							$tr_n = explode(";", ${dato_seneca.$i}[0]);
							foreach ($tr_n as $value) {

								$tr_d = explode(":", $value);
								if ($tr_d[0]==$asignatura) {
									$califica = "select abreviatura from calificaciones where codigo = '" . $tr_d[1] . "'";
									//echo $califica;
									$calificacion = mysqli_query($db_con, $califica);
									$rown = mysqli_fetch_array($calificacion);
									if ($rown[0]<5) {
										${n_susp.$i}++;
										?>
										<script type="text/javascript">
										celda = document.getElementById("<?php echo "$claveal-$abrev-$i"; ?>");
										celda.style.backgroundColor="#aaa";
										celda.style.color="#333";
										</script>
										<?php
										echo "<span>$rown[0]</span>";
									}
									else{
										echo "<span class='text-info'>$rown[0]</span>";
									}
								}
							}
							echo "</td>";
						}	
						
					}	
					
					for ($i=1; $i < 5; $i++) { 
							echo "<td class='text-danger'><strong>".${n_susp.$i}."</strong></td>";				
				}

				?>
					
				</tr>
				
				<?php
				}
		?>

		</table>

	</div>

</div>
<?php } ?>			

<?php include("../../pie.php"); ?>
</body>
</html>