<?php
require('../bootstrap.php');


// ELIMINAR INCIDENCIA
if (isset($_GET['parte']) && isset($_GET['borrar']) && $_GET['borrar'] == 1) {
	$result = mysqli_query($db_con, "DELETE FROM partestic WHERE parte=".$_GET['parte']." LIMIT 1");
	
	if(!$result) $msg_error = "No se ha podido eliminar la incidencia. Error: ".mysqli_error($db_con);
	else $msg_success = "La incidencia ha sido eliminada.";
}

$PLUGIN_DATATABLES = 1;

include("../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Lista de incidencias</small></h2>
		</div>
		
		
		<!-- MENSAJES -->
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">
				<?php $result = mysqli_query($db_con, "SELECT parte, nincidencia, carro, nserie, fecha, hora, profesor, descripcion, estado FROM partestic WHERE fecha >= '".$config['curso_inicio']."' ORDER BY parte DESC"); ?>
				
				<?php if (mysqli_num_rows($result)): ?>
				<table class="table table-bordered table-striped table-hover datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Recurso</th>
							<th>Ordenador</th>
							<th>Incidencia</th>
							<th>Profesor/a</th>
							<th>Estado</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						<tr>
							<td nowrap><?php echo $row['fecha']; ?></td>
							<td><?php echo $row['carro']; ?></td>
							<td><?php echo $row['nserie']; ?></td>
							<td><?php echo $row['descripcion']; ?></td>
							<td><?php echo $row['profesor']; ?></td>
							<td>
								<?php echo ($row['estado'] == 'activo' || $row['estado'] == 'Activo') ? '<span class="sr-only">Pendiente</span><span class="fa fa-exclamation-triangle fa-fw fa-lg" data-bs="tooltip" title="Pendiente"></span>' : ''; ?>
								<?php echo ($row['estado'] == 'solucionado' || $row['estado'] == 'Solucionado') ? '<span class="sr-only">Solucionado</span><span class="fa fa-check-circle fa-fw fa-lg" data-bs="tooltip" title="Solucionado"></span>' : ''; ?>
							</td>
							<td nowrap>
								<?php if(stristr($_SESSION['cargo'],'1') == TRUE): ?>
								<a href="index.php?id=<?php echo $row['parte']; ?>"><span class="fa fa-edit fa-fw fa-lg" data-bs="tooltip" title="Editar"></span></a>
								<?php endif; ?>
								<?php if(stristr($_SESSION['cargo'],'1') == TRUE || $row['profesor'] == $pr): ?>
								<a href="incidencias.php?parte=<?php echo $row['parte']; ?>&borrar=1" data-bb="confirm-delete"><span class="fa fa-trash-o fa-fw fa-lg" data-bs="tooltip" title="Eliminar"></span></a>
								<?php endif; ?>
							</td>
						</tr>
						<?php endwhile; ?>
						<?php mysqli_free_result($result); ?>
					</tbody>
				</table>

				
				<div class="hidden-print">
					<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
					<?php if(stristr($_SESSION['cargo'],'1') == TRUE): ?>
					<div class="btn-group">
					  <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    Imprimir PDF <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
					     <li><a href="incidencias_pdf.php?order=fecha" target="_blank">Ordenado por fecha</a></li>
					     <li><a href="incidencias_pdf.php?order=estado" target="_blank">Ordenado por pendientes</a></li>
					  </ul>
					</div>
					<?php endif; ?>
					<a href="index.php" class="btn btn-default">Volver</a>
				</div>
				
				
				<?php else: ?>
				
				<h3>No se ha registrado ninguna incidencia</h3>
				
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				
				
				<?php endif; ?>
				
								
			</div><!-- /.col-sm-12 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../pie.php"); ?>

<script>
$(document).ready(function() {
  var table = $('.datatable').DataTable({
  		"paging":   true,
      "ordering": true,
      "info":     false,
      
  		"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
  		
  		"order": [[ 0, "desc" ]],
  		
  		"language": {
  		            "lengthMenu": "_MENU_",
  		            "zeroRecords": "No se ha encontrado ningún resultado con ese criterio.",
  		            "info": "Página _PAGE_ de _PAGES_",
  		            "infoEmpty": "No hay resultados disponibles.",
  		            "infoFiltered": "(filtrado de _MAX_ resultados)",
  		            "search": "Buscar: ",
  		            "paginate": {
  		                  "first": "Primera",
  		                  "next": "Última",
  		                  "next": "",
  		                  "previous": ""
  		                }
  		        }
  	});
});
</script>

</body>
</html>
