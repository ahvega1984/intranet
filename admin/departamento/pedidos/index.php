<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 4));

if (file_exists('config.php')) {
	include('config.php');
}

$PLUGIN_DATATABLES = 1;

if (isset($_GET['action'])) {
	
	switch ($_GET['action']) {
		case 'eliminar' :
			
			if (isset($_GET['id'])) {
				$id = mysqli_real_escape_string($db_con, $_GET['id']);
				$result = mysqli_query($db_con, "DELETE FROM depto_pedidos WHERE id = '$id' LIMIT 1");
				mysqli_query($db_con, "DELETE FROM depto_pedidos_detalles WHERE id_pedido = '$id'");
				
				if (! $result) $msg_error = "Ha ocurrido un error al eliminar el pedido. Error: ".mysqli_error($db_con);
				else $msg_success = "El pedido ha sido eliminado";
			}
			
	}
	
}

include("../../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Pedido de material <small>Listado de pedidos</small></h2>
	</div>
	
	<!-- MENSAJES -->
	<?php if (isset($msg_error)): ?>
	<div class="alert alert-danger alert-fadeout">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>
	
	<?php if (isset($msg_success)): ?>
	<div class="alert alert-success alert-fadeout">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>


	<div class="row">

		<div class="col-sm-12">
			
			<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
			<br>
			
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#depto"  role="tab" data-toggle="tab">Departamento de <?php echo $dpto; ?></a></li>
				<li><a href="#todos" role="tab" data-toggle="tab">Todos los departamentos</a></li>
			</ul>
			
			<br>
			
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="depto">
				
					<table class="table table-condensed table-bordered table-hover datatable">
						<thead>
							<tr>
								<th>Num.</th>
								<th>Departamento</th>
								<th>Responsable</th>
								<th>Fecha</th>
								<th>Total</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<?php $result = mysqli_query($db_con, "SELECT depto_pedidos.*, c_profes.profesor, (SELECT SUM(importe * cantidad) FROM depto_pedidos_detalles WHERE id_pedido = depto_pedidos.id) AS total FROM depto_pedidos, c_profes WHERE depto_pedidos.responsable = c_profes.idea AND departamento = '$dpto'"); ?>
							
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<tr>
								<td><?php echo $row['id']; ?></td>
								<td><?php echo $row['departamento']; ?></td>
								<td><?php echo $row['profesor']; ?></td>
								<td><?php echo $row['fechaRegistro']; ?></td>
								<td><?php echo $row['total']; ?> &euro;</td>
								<td>
									<?php if (! $row['entregado']): ?>
									<a href="pedido.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar pedido"><span class="fa fa-edit fa-lg fa-fw"></span></a>
									<?php endif; ?>
									<!--
									<a href="imprimir.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Imprimir"><span class="fa fa-print fa-lg fa-fw"></span></a>
									-->
									<a href="../../rd/story.php?id=<?php echo $row['id_acta']; ?>" data-bs="tooltip" title="Ver acta"><span class="fa fa-file fa-lg fa-fw"></span></a>
									<a href="index.php?action=eliminar&id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar pedido" data-bb="confirm-delete"><span class="fa fa-trash-o fa-lg fa-fw"></span></a>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				
				</div>
				
				
				<div role="tabpanel" class="tab-pane" id="todos">
					
					<table class="table table-condensed table-bordered table-hover datatable">
						<thead>
							<tr>
								<th>Num.</th>
								<th>Departamento</th>
								<th>Responsable</th>
								<th>Fecha</th>
								<th>Total</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<?php $result = mysqli_query($db_con, "SELECT depto_pedidos.*, c_profes.profesor, (SELECT SUM(importe * cantidad) FROM depto_pedidos_detalles WHERE id_pedido = depto_pedidos.id) AS total FROM depto_pedidos, c_profes WHERE depto_pedidos.responsable = c_profes.idea"); ?>
							
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<tr>
								<td><?php echo $row['id']; ?></td>
								<td><?php echo $row['departamento']; ?></td>
								<td><?php echo $row['profesor']; ?></td>
								<td><?php echo $row['fechaRegistro']; ?></td>
								<td><?php echo $row['total']; ?> &euro;</td>
								<td>
									<?php if (! $row['entregado']): ?>
									<a href="pedido.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar pedido"><span class="fa fa-edit fa-lg fa-fw"></span></a>
									<?php endif; ?>
									<!--
									<a href="imprimir.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Imprimir"><span class="fa fa-print fa-lg fa-fw"></span></a>
									-->
									<a href="../../rd/story.php?id=<?php echo $row['id_acta']; ?>" data-bs="tooltip" title="Ver acta"><span class="fa fa-file fa-lg fa-fw"></span></a>
									<a href="index.php?action=eliminar&id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar pedido" data-bb="confirm-delete"><span class="fa fa-trash-o fa-lg fa-fw"></span></a>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				
				</div>
			</div>
			 
			<br>
			<?php else: ?>
			
			<table class="table table-condensed table-bordered table-hover datatable">
				<thead>
					<tr>
						<th>Num.</th>
						<th>Departamento</th>
						<th>Responsable</th>
						<th>Fecha</th>
						<th>Total</th>
						<th>Opciones</th>
					</tr>
				</thead>
				<tbody>
					<?php $result = mysqli_query($db_con, "SELECT depto_pedidos.*, c_profes.profesor, (SELECT SUM(importe * cantidad) FROM depto_pedidos_detalles WHERE id_pedido = depto_pedidos.id) AS total FROM depto_pedidos, c_profes WHERE depto_pedidos.responsable = c_profes.idea AND departamento = '$dpto'"); ?>
					
					<?php while ($row = mysqli_fetch_array($result)): ?>
					<tr>
						<td><?php echo $row['id']; ?></td>
						<td><?php echo $row['departamento']; ?></td>
						<td><?php echo $row['profesor']; ?></td>
						<td><?php echo $row['fechaRegistro']; ?></td>
						<td><?php echo $row['total']; ?> &euro;</td>
						<td>
							<?php if (! $row['entregado']): ?>
							<a href="pedido.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar pedido"><span class="fa fa-edit fa-lg fa-fw"></span></a>
							<?php endif; ?>
							<!--
							<a href="imprimir.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Imprimir"><span class="fa fa-print fa-lg fa-fw"></span></a>
							-->
							<a href="../../rd/story.php?id=<?php echo $row['id_acta']; ?>" data-bs="tooltip" title="Ver acta"><span class="fa fa-file fa-lg fa-fw"></span></a>
							<a href="index.php?action=eliminar&id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar pedido" data-bb="confirm-delete"><span class="fa fa-trash-o fa-lg fa-fw"></span></a>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			
			<?php endif; ?>
			
		</div>

	</div>

</div>




<?php include("../../../pie.php"); ?>

<script>
$(document).ready(function() {
	var table = $('.datatable').DataTable({
	"paging":   true,
    "ordering": true,
    "info":     false,
    "searching":   false,	    

	"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
		
	"order": [[ 0, "asc" ]],
		
	"language": {
		            "lengthMenu": "_MENU_",
		            "zeroRecords": "Sin resultados.",
		            "info": "Página _PAGE_ de _PAGES_",
		            "infoEmpty": "No hay resultados disponibles.",
		            "infoFiltered": "(filtrado de _MAX_ resultados)",
		            "search": "",
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
