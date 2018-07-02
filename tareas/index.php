<?php
require('../bootstrap.php');

if ((isset($_GET['id']) && isset($_GET['accion'])) || (isset($_GET['accion']) && $_GET['accion'] == "eliminar-todo")) {
	if (isset($_GET['id'])) $id = intval($_GET['id']);
	$accion = htmlspecialchars($_GET['accion']);

	switch ($accion) {
		case 'eliminar-todo' :

			$result = mysqli_query($db_con, "DELETE FROM tareas WHERE idea = '".$idea."'");
			if (! $result) $msg_error = "Error al eliminar el listado de tareas. ".mysqli_error($db_con);

			break;

		case 'eliminar' :

			$result = mysqli_query($db_con, "DELETE FROM tareas WHERE id = ".$id." AND idea = '".$idea."' LIMIT 1");
			if (! $result) $msg_error = "Error al eliminar la tarea. ".mysqli_error($db_con);

			break;

		case 'finalizar' :

			$result = mysqli_query($db_con, "UPDATE tareas SET estado = 1 WHERE id = ".$id." AND idea = '".$idea."' AND estado = 0 LIMIT 1");
			if (! $result) $msg_error = "Error al actualizar el estado de la tarea. ".mysqli_error($db_con);

			break;

		case 'rehacer' :

			$result = mysqli_query($db_con, "UPDATE tareas SET estado = 0 WHERE id = ".$id." AND idea = '".$idea."' AND estado = 1 LIMIT 1");
			if (! $result) $msg_error = "Error al actualizar el estado de la tarea. ".mysqli_error($db_con);

			break;

		case 'con-prioridad' :

			$result = mysqli_query($db_con, "UPDATE tareas SET prioridad = 1 WHERE id = ".$id." AND idea = '".$idea."' AND prioridad = 0 LIMIT 1");
			if (! $result) $msg_error = "Error al actualizar el estado de la tarea. ".mysqli_error($db_con);

			break;

		case 'sin-prioridad' :

			$result = mysqli_query($db_con, "UPDATE tareas SET prioridad = 0 WHERE id = ".$id." AND idea = '".$idea."' AND prioridad = 1 LIMIT 1");
			if (! $result) $msg_error = "Error al actualizar el estado de la tarea. ".mysqli_error($db_con);

			break;

		default:
			$msg_error = "Acción no disponible";
			break;
	}
}

$tareas_pendientes = array();
$result = mysqli_query($db_con, "SELECT id, titulo, tarea, prioridad FROM tareas WHERE idea = '".$idea."' AND estado = 0 ORDER BY prioridad DESC, fechareg DESC");
while ($row = mysqli_fetch_array($result)) {
	$tarea = array(
		'id' 		=> $row['id'],
		'titulo' 	=> $row['titulo'],
		'tarea' 	=> strip_tags($row['tarea']),
		'prioridad' => $row['prioridad']
	);

	array_push($tareas_pendientes, $tarea);
}
unset($tarea);

$tareas_finalizadas = array();
$result = mysqli_query($db_con, "SELECT id, titulo, tarea, prioridad FROM tareas WHERE idea = '".$idea."' AND estado = 1 ORDER BY prioridad DESC, fechareg DESC");
while ($row = mysqli_fetch_array($result)) {
	$tarea = array(
		'id' 		=> $row['id'],
		'titulo' 	=> $row['titulo'],
		'tarea' 	=> strip_tags($row['tarea']),
		'prioridad' => $row['prioridad']
	);

	array_push($tareas_finalizadas, $tarea);
}
unset($tarea);


include("../menu.php");
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<div class="pull-right hidden-print">
				<a href="tarea.php" class="btn btn-primary">Crear tarea</a>
			</div>

			<h2 style="display: inline;">Tareas</h2>
		</div>

		<?php if (isset($_GET['error']) && $_GET['error'] == 'noExiste'): ?>
		<div class="alert alert-danger">
			<strong>Error:</strong> La tarea a la que intenta acceder no existe o no te pertenece
		</div>
		<?php endif; ?>

		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<strong>Error:</strong> <?php echo $msg_error; ?>
		</div>
		<?php endif; ?>


		<div class="row">

			<!-- Tareas pendientes -->
			<div class="col-md-6">

				<div class="well">
					<h3 class="text-warning text-center">Tareas pendientes</h3>

					<?php if (count($tareas_pendientes)): ?>
					<ul class="list-group">
						<?php foreach ($tareas_pendientes as $tarea): ?>
						<li class="list-group-item">
							<div class="row">
								<div class="col-sm-1">
									<?php if ($tarea['prioridad']): ?>
									<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?id=<?php echo $tarea['id']; ?>&amp;accion=sin-prioridad" data-bs="tooltip" title="Prioridad"><span class="far fa-star fa-lg"></span></a>
									<?php else: ?>
									<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?id=<?php echo $tarea['id']; ?>&amp;accion=con-prioridad" data-bs="tooltip" title="Prioridad"><span class="far fa-starfa-lg"></span></a>
									<?php endif; ?>
								</div>

								<div class="col-sm-11">
									<div class="pull-right">
										<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?id=<?php echo $tarea['id']; ?>&amp;accion=finalizar" data-bs="tooltip" title="Finalizar tarea"><span class="fas fa-check fa-lg"></span></a>&nbsp;
										<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?id=<?php echo $tarea['id']; ?>&amp;accion=eliminar" data-bb="confirm-delete" data-bs="tooltip" title="Eliminar"><span class="far fa-trash-alt fa-lg"></span></a>
									</div>
									<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php?id=<?php echo $tarea['id']; ?>">
										<strong><?php echo $tarea['titulo']; ?></strong><br>
										<?php echo substr($tarea['tarea'], 0, 86); ?>
									</a>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php else: ?>

					<br><br>
					<div class="text-center text-muted">
						<span class="fas fa-tasks fa-4x"></span>
						<p class="lead">No hay tareas pendientes</p>
					</div>
					<br><br>
					<?php endif; ?>
				</div>

			</div><!-- /.col-md-6 -->


			<!-- Tareas realizadas -->
			<div class="col-md-6">

				<div class="well">
					<h3 class="text-info text-center">Tareas finalizadas</h3>

					<?php if (count($tareas_finalizadas)): ?>
					<ul class="list-group">
						<?php foreach ($tareas_finalizadas as $tarea): ?>
						<li class="list-group-item">
							<div class="pull-right">
								<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?id=<?php echo $tarea['id']; ?>&amp;accion=rehacer" data-bs="tooltip" title="Rehacer tarea"><span class="fas fa-undo fa-lg"></span></a>&nbsp;
								<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?id=<?php echo $tarea['id']; ?>&amp;accion=eliminar" data-bb="confirm-delete" data-bs="tooltip" title="Eliminar"><span class="far fa-trash-alt fa-lg"></span></a>
							</div>
							<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php?id=<?php echo $tarea['id']; ?>">
								<strong><?php echo $tarea['titulo']; ?></strong><br>
								<del><?php echo substr($tarea['tarea'], 0, 86); ?></del>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>

					<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/index.php?accion=eliminar-todo" class="btn btn-sm btn-danger" data-bb="confirm-delete" data-bs="tooltip" title="Eliminar">Eliminar todo</a>

					<?php else: ?>

					<br><br>
					<div class="text-center text-muted">
						<span class="fas fa-tasks fa-4x"></span>
						<p class="lead">No hay tareas finalizadas</p>
					</div>
					<br><br>
					<?php endif; ?>
				</div>


			</div><!-- /.col-md-6 -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<?php include("../pie.php"); ?>

</body>
</html>
