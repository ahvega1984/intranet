<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

if (file_exists('config.php')) {
	include('config.php');
}

$verTodas = 0;
if (isset($_GET['verTodas']) && $_GET['verTodas'] == 1) {
	$verTodas = 1;
}

$organos = array('DFEIE', 'Equipo directivo', 'ETCP', 'Coord. Enseñanzas Bilingües', 'Área Artística', 'Área Científico-Tecnológica', 'Área Social-Lingüística', 'Área Formación Profesional');

// ELIMINAR ACTAS
if (isset($_GET['eliminar_depto'])) {
	
	$eliminar_depto = mysqli_real_escape_string($db_con, $_GET['eliminar_depto']);
	
	$result = mysqli_query($db_con, "DELETE FROM r_departamento WHERE departamento = $eliminar_depto");
	if (! $result) $msg_error = "Ha ocurrido un error al eliminar las actas del departamento. Error: ".mysqli_error($db_con);
	else $msg_success = "Las actas han sido eliminadas correctamente.";
}

// URI módulo
if (isset($_GET['verTodas'])) {
	$uri = 'index.php?verTodas=1&amp;';
}
else {
	$uri = 'index.php?';
}

include ("../../../menu.php");
include ("menu.php");
?>

<div class="container">
	
	<div class="page-header">
		<h2>Actas de departamentos <small>Administrar actas</small></h2>
	</div>
	
	<div class="row">
		
		<div class="col-sm-12">
			
			<h3>Órganos del centro</h3>
			
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="col-sm-3">Órgano</th>
						<th class="col-sm-4">Secretario / Coordinador</th>
						<th class="col-sm-2 text-center">Nº Actas / Impresas</th>
						<th class="col-sm-2">Consultar acta</th>
						<th class="col-sm-1">Opciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($organos as $organo): ?>
					<?php 
					switch ($organo) {
						case 'DFEIE' : $secretario = $config['actas_depto']['secretario_dfeie']; break;
						case 'Equipo directivo' : $secretario = $config['actas_depto']['secretario_ed']; break;
						case 'ETCP' : $secretario = $config['actas_depto']['secretario_etcp']; break;
						case 'Coord. Enseñanzas Bilingües' : $secretario = $config['actas_depto']['secretario_ceb']; break;
						case 'Área Artística' : $secretario = $config['actas_depto']['secretario_aca']; break;
						case 'Área Científico-Tecnológica' : $secretario = $config['actas_depto']['secretario_acct']; break;
						case 'Área Social-Lingüística' : $secretario = $config['actas_depto']['secretario_acsl']; break;
						case 'Área Formación Profesional' : $secretario = $config['actas_depto']['secretario_afp']; break;
					}
					
					$total = 0;
					$total_impresas = 0;
					if ($verTodas) {
						$result = mysqli_query($db_con, "SELECT id, numero, fecha, impreso FROM r_departamento WHERE departamento = '$organo' ORDER BY numero DESC");
					}
					else {
						$result = mysqli_query($db_con, "SELECT id, numero, fecha, impreso FROM r_departamento WHERE departamento = '$organo' AND fecha BETWEEN '".$config['curso_inicio']."' AND '".$config['curso_fin']."' ORDER BY numero DESC");
					}
					while ($row = mysqli_fetch_array($result)) {
						$total++;
						if ($row['impreso']) $total_impresas++;
					}
					?>
					<tr>
						<td><a href="index.php?organo=<?php echo $organo; ?>"><?php echo $organo; ?></a></td>
						<td><?php echo ($secretario != "") ? $secretario : '<a href="preferencias.php">Seleccionar</a>'; ?></td>
						<td class="text-center"><?php echo $total; ?> / <?php echo $total_impresas; ?></td>
						<td>
							<div class="btn-group btn-block">
								<?php if (! mysqli_num_rows($result)): ?>
								<button type="button" class="btn btn-default btn-sm btn-block disabled">
									Seleccione <span class="caret"></span>
								</button>
								<?php else: ?>
								<button type="button" class="btn btn-default btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Seleccione <span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<?php mysqli_data_seek($result, 0); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
									<li><a href="pdf.php?id=<?php echo $row['id']; ?>"><?php echo ($row['impreso']) ? '<span class="fa fa-check-circle fa-fw"></span>' : '<span class="fa fa-exclamation-circle fa-fw"></span>'; ?> &nbsp;Acta nº <?php echo $row['numero'].' - '.$row['fecha']; ?></a></li>
									<?php endwhile; ?>
									<li class="divider"></li>
									<?php if (! $verTodas): ?>
									<li><a href="administracion.php?verTodas=1">Ver todas las actas</a></li>
									<?php else: ?>
									<li><a href="administracion.php">Ver actas de este curso</a></li>
									<?php endif; ?>
								</ul>
								<?php endif; ?>
							</div>
						</td>
						<td>
							<a href="pdf.php?depto=<?php echo $organo; ?>" data-bs="tooltip" title="Imprimir actas" data-bb="confirm-print"><span class="fa fa-print fa-fw fa-lg"></span></a>
							<a href="<?php echo $uri; ?>eliminar_depto=<?php echo $organo; ?>" data-bs="tooltip" title="Eliminar actas" data-bb="confirm-delete2"><span class="fa fa-trash-o fa-fw fa-lg"></span></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
			<br>
			<hr>
			
			<h3>Departamentos del centro</h3>
			
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="col-sm-3">Departamento</th>
						<th class="col-sm-4">Jefe de Departamento</th>
						<th class="col-sm-2 text-center">Nº Actas / Impresas</th>
						<th class="col-sm-2">Consultar acta</th>
						<th class="col-sm-1">Opciones</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$mostrar_alerta = 0;
					$result = mysqli_query($db_con, "SELECT DISTINCT departamento, (SELECT nombre FROM departamentos AS depto WHERE depto.departamento = departamentos.departamento AND depto.cargo LIKE '%4%' LIMIT 1) AS nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' ORDER BY departamento ASC"); ?>
					<?php while ($row = mysqli_fetch_array($result)): ?>
					
					<?php 
					$total = 0;
					$total_impresas = 0;
					if ($verTodas) {
						$result_actas_depto = mysqli_query($db_con, "SELECT id, numero, fecha, impreso FROM r_departamento WHERE departamento = '".$row['departamento']."' ORDER BY numero DESC"); 
						
					}
					else {
						$result_actas_depto = mysqli_query($db_con, "SELECT id, numero, fecha, impreso FROM r_departamento WHERE departamento = '".$row['departamento']."' AND fecha BETWEEN '".$config['curso_inicio']."' AND '".$config['curso_fin']."' ORDER BY numero DESC"); 
					}
					while ($row_actas_depto = mysqli_fetch_array($result_actas_depto)) {
						$total++;
						if ($row_actas_depto['impreso']) $total_impresas++;
					}
					?>
					<tr>
						<td><?php echo $row['departamento']; ?></td>
						<td><?php 
							if ($row['nombre'] != "") {
								echo $row['nombre'];
							}
							else {
								$mostrar_alerta = 1;
								echo '<a href="../../config/cargos.php">Asignar perfil</a>';
							}
							?></td>
						<td class="text-center"><?php echo $total; ?> / <?php echo $total_impresas; ?></td>
						<td>
							<div class="btn-group btn-block">
								<?php if (! mysqli_num_rows($result_actas_depto)): ?>
								<button type="button" class="btn btn-default btn-sm btn-block disabled">
									Seleccione <span class="caret"></span>
								</button>
								<?php else: ?>
								<button type="button" class="btn btn-default btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Seleccione <span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<?php mysqli_data_seek($result_actas_depto, 0); ?>
									<?php while ($row_actas = mysqli_fetch_array($result_actas_depto)): ?>
									<li><a href="pdf.php?id=<?php echo $row_actas['id']; ?>"><?php echo ($row_actas['impreso']) ? '<span class="fa fa-check-circle fa-fw"></span>' : '<span class="fa fa-exclamation-circle fa-fw"></span>'; ?> &nbsp;Acta nº <?php echo $row_actas['numero'].' - '.$row_actas['fecha']; ?></a></li>
									<?php endwhile; ?>
									<li class="divider"></li>
									<?php if (! $verTodas): ?>
									<li><a href="administracion.php?verTodas=1">Ver todas las actas</a></li>
									<?php else: ?>
									<li><a href="administracion.php">Ver actas de este curso</a></li>
									<?php endif; ?>
								</ul>
								<?php endif; ?>
							</div>
						</td>
						<td>
							<a href="pdf.php?depto=<?php echo $row['departamento']; ?>" data-bs="tooltip" title="Imprimir actas" data-bb="confirm-print"><span class="fa fa-print fa-fw fa-lg"></span></a>
							<a href="<?php echo $uri; ?>eliminar_depto=<?php echo $row['departamento']; ?>" data-bs="tooltip" title="Eliminar actas" data-bb="confirm-delete2"><span class="fa fa-trash-o fa-fw fa-lg"></span></a>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			
			<?php if ($mostrar_alerta): ?>
			<div class="alert alert-warning">
				<strong>Advertencia:</strong> Los Departamentos mostrados en esta página han sido creados a partir de la información aportada en la Administración de la Intranet. Si la relación de Departamentos y Jefes de Departamentos no es correcta, diríjase a <a href="../../xml/jefe/gest_dep.php" class="alert-link">Gestión de Departamentos</a> para modificar los Departamentos del centro y a <a href="../../../config/cargos.php" class="alert-link">Perfiles de los profesores</a> para asignar el perfil de Jefe de Departamento.
			</div>
			<?php endif; ?>
			
		</div>
		
	</div>


</div>

<?php include("../../../pie.php"); ?>

	<script>  
	$(document).ready(function() {

		$(document).on("click", "a[data-bb]", function(e) {
		    e.preventDefault();
		    var type = $(this).data("bb");
				var link = $(this).attr("href");
				
				if (type == 'confirm-print') {
					bootbox.setDefaults({
					  locale: "es",
					  show: true,
					  backdrop: true,
					  closeButton: true,
					  animate: true,
					  title: "Confirmación para imprimir",
					});
					
					bootbox.confirm("Esta acción bloqueará permanentemente la edición de las actas de este departamento. ¿Seguro que desea continuar? Antes de Aceptar, es recomendable que realice una copia de seguridad en la Administración de la Intranet.", function(result) {
					    if (result) {
					    	document.location.href = link;
					    }
					});
				}
				
				if (type == 'confirm-delete2') {
					bootbox.setDefaults({
					  locale: "es",
					  show: true,
					  backdrop: true,
					  closeButton: true,
					  animate: true,
					  title: "Confirmación para eliminar",
					});
					
					bootbox.confirm("Esta acción eliminará permanentemente las actas de este departamento. ¿Seguro que desea continuar? Antes de Aceptar, es recomendable que realice una copia de seguridad en la Administración de la Intranet.", function(result) {
					    if (result) {
					    	document.location.href = link;
					    }
					});
				}
		});
		
	});
	</script>
	
</body>
</html>
