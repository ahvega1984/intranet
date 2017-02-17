<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("inc_calendario.php");

include("../../menu.php");
include("menu.php");

$fecha = date("Y-m-d", mktime(0,0,0, $mes, $dia, $anio)); 
?>

<style type="text/css">
@media print {
	.col-print-100 {
		width: 100% !important;
	}
}
</style>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display: inline;">Guardias de aula <small>Consulta por fechas</small></h2>
		<h4 class="text-info"><span class="fa fa-calendar-o fa-fw"></span> <?php echo strftime("%e, %B %Y",strtotime($fecha)); ?></h4>
	</div>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<div class="col-sm-3 hidden-print">
		
			<?php vista_mes($calendario, $dia, $mes, $anio); ?>
				
		</div><!-- /.col-sm-3 -->
		
		<div class="col-sm-9 col-print-100">
			
			<div class="table-responsive">
				<table class="table table-bordered table-striped" style="font-size: 0.9em;">
					<thead>
						<tr>
						<th>Hora</th>
						<th>Profesor de guardia</th>
						<th>Profesor ausente</th>
						<th>Unidad</th>
						<th>Aula</th>
						<th>Turno</th>
						</tr>
					</thead>
					<tbody>
						<?php $result = mysqli_query($db_con,"SELECT hora_inicio, hora_fin, hora FROM tramos WHERE hora < 7 OR hora LIKE 'R' ORDER BY tramo ASC"); ?>
						<?php while ($row = mysqli_fetch_array($result)): ?>
	
							<?php $result2 = mysqli_query($db_con, "SELECT profesor, profe_aula, turno, a_grupo, n_aula FROM guardias, horw WHERE guardias.profe_aula = horw.prof AND guardias.hora = horw.hora AND guardias.dia = horw.dia AND guardias.hora ='".$row[2]."'AND fecha_guardia = '".$fecha."' ORDER BY turno ASC"); ?>
							
							<?php $num_results2 = mysqli_num_rows($result2); ?>
							
							<?php if ($num_results2): ?>
							<?php $rows = 0; ?>
							<?php while ($row2 = mysqli_fetch_array($result2)): ?>
							<tr>
								<?php if ($rows < 1): ?>
								<th <?php echo ($num_results2 > 1) ? 'rowspan="'.$num_results2.'"' : ''; ?> nowrap>
									<span class="text-info"><?php echo $row['hora'] == 'R' ? 'Recreo' : $row['hora'].'ª hora'; ?></span><br>
									<small class="text-muted"><?php echo $row['hora_inicio'].' - '.$row['hora_fin']; ?></small></th>
								</th>
								<?php $rows++; ?>
								<?php endif; ?>
								<td><a href="consulta_profesores.php?profesor=<?php echo nomprofesor($row2['profesor']); ?>"><?php echo nomprofesor($row2['profesor']); ?></a></td>
								<td><?php echo nomprofesor($row2['profe_aula']); ?></td>
								<td><?php echo $row2['a_grupo']; ?></td>
								<td><?php echo $row2['n_aula']; ?></td>
								<td><?php if ($row2['turno'] == 1) echo 'Hora completa'; elseif ($row2['turno'] == 2) echo '1ª media hora'; else echo '2ª media hora'; ?></td>
							</tr>
							<?php endwhile; ?>
							<?php else: ?>
							<tr>
								<th nowrap>
									<span class="text-info"><?php echo $row['hora'] == 'R' ? 'Recreo' : $row['hora'].'ª hora'; ?></span><br>
									<small class="text-muted"><?php echo $row['hora_inicio'].' - '.$row['hora_fin']; ?></small></th>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<?php endif; ?>

						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		
		</div><!-- /.col-sm-9 -->
		
	</div><!-- /.row -->
	
	<br>
	
	<div class="row">
		
		<div class="col-sm-12">
			<div class="hidden-print">
				<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
			</div>
		</div>
		
	</div>
		
</div>
<?php unset($fecha); ?>

	<?php include("../../pie.php"); ?>

</body>
</html>
