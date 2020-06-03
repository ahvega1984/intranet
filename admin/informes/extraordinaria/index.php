<?php
require('../../../bootstrap.php');


// BORRAR INFORMES
if (isset($_GET['borrar']) AND $_GET['borrar'] == 1) {
	$result = mysqli_query($db_con,"delete from informe_extraordinaria where id_informe= '".$_GET['id_informe']."'");
	$result2 = mysqli_query($db_con,"delete from informe_extraordinaria_contenidos where id_informe= '".$_GET['id_informe']."'");
	if (! $result) {
		$msg_error = "No se ha podido eliminar el informe. Error: ".mysqli_error($db_con);
		}
}


// MARCAR PLANTILLA
if (isset($_GET['plantilla']) AND $_GET['plantilla'] == 1) {
	$plant = mysqli_fetch_array(mysqli_query($db_con,"select * from informe_extraordinaria where id_informe = '".$_GET['id_informe']."'"));
	$curso_corto = substr($plant['curso'],0,19);
	mysqli_query($db_con,"update informe_extraordinaria set plantilla = '0' where asignatura like '".$plant['asignatura']."' and curso like '".$curso_corto."%'");
	$result = mysqli_query($db_con,"update informe_extraordinaria set plantilla = '1' where id_informe= '".$_GET['id_informe']."'");
	if (! $result) {
		$msg_error = "No se ha podido crear la plantilla del informe. Error: ".mysqli_error($db_con);
		}
}

// OBTENEMOS LAS UNIDADES DONDE IMPARTE MATERIA EL PROFESOR
$informes = array();
$result = mysqli_query($db_con, "SELECT `id_informe`, `asignatura`, `unidad`, `fecha`, `modalidad`, `plantilla` FROM `informe_extraordinaria` WHERE `profesor` = '".$_SESSION['ide']."' ORDER BY `id_informe` ASC");
while ($row = mysqli_fetch_array($result)) {
	$informe = array(
		'id_informe' => $row['id_informe'],
		'asignatura' => $row['asignatura'],
		'unidad' => $row['unidad'],
		'fecha' => preg_replace('/([0-9]{4})-([0-9]{2})-([0-9]{2})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})/', '$3/$2/$1 $4:$5', $row['fecha']),
		'modalidad' => $row['modalidad'],
		'plantilla' => $row['plantilla']
	);

	array_push($informes, $informe);

	unset($informe);
}

// COMPROBAMOS SI SE HA SELECCIONADO LA UNIDAD
if (isset($_POST['unidad']) && in_array($_POST['unidad'], $unidades)) {
	$unidad = limpiarInput($_POST['unidad'], 'alphanumericspecial');
}

include("../../../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<div class="page-header">
			<h2>Informe individual para la evaluación extraordinaria <small> <br>Mis informes</small></h2>
		</div>

		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger alert-fadeout">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>

		<div class="row">
			
			<div class="col-sm-12">

				<table class="table table-striped" style="width:auto">
					<thead>
						<tr>
							<th>Asignatura</th>
							<th>Unidad</th>
							<th>Fecha</th>
							<th>Modalidad</th>
							<th ></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($informes as $informe): ?>
						<tr>
							<td><?php echo $informe['asignatura']; ?></td>
							<td><?php echo $informe['unidad']; ?></td>
							<td><?php echo $informe['fecha']; ?></td>
							<td><?php echo $informe['modalidad']; ?></td>
							<td class="pull-right">
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/alumnado.php?id_informe=<?php echo $informe['id_informe']; ?>&grupo=<?php echo $informe['unidad']; ?>" data-bs="tooltip" title="Seleccionar alumnos para este informe."><span class="fas fa-user-friends fa-fw fa-lg"></span></a>&nbsp;
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/contenidos.php?id_informe=<?php echo $informe['id_informe']; ?>" data-bs="tooltip" title="Editar los contenidos y actividades de este informe."><span class="text-info fas fa-edit fa-fw fa-lg"></span></a>&nbsp;
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/index.php?plantilla=1&id_informe=<?php echo $informe['id_informe']; ?>" data-bs="tooltip" title="Haz clck sobre el icono para convertir este informe en plantilla de la asignatura para este nivel."><?php if ($informe['plantilla'] == 1) {?><span class="text-success far fa-check-square fa-fw fa-lg"></span><?php } else {?><span class="text-success far fa-square fa-fw fa-lg"></span><?php }?></a>&nbsp;								
								<a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/index.php?borrar=1&id_informe=<?php echo $informe['id_informe']; ?>" data-bb="confirm-delete" data-bs="tooltip" title="Borrar este informe"><span class="text-muted far fa-trash-alt fa-fw fa-lg"></span></a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

			</div><!-- /.col-sm-12 -->

		</div><!-- /.row -->
		


	</div>

	<?php include("../../../pie.php"); ?>

</body>
</html>