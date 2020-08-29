<?php
require('../../bootstrap.php');

// acl_acceso($_SESSION['cargo'], array('1','4','5'));

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("menu.php");
?>

<style type="text/css">
@media print {
	html, body {
		padding: 0;
		margin: 0;
	}
}
</style>

<div class='container'>
<div class="page-header">
<h2>Actividades Complementarias y Extraescolares <small>Administración</small></h2>
</div>

<div class="row">

<div class="col-sm-12"><?php
if ($_GET['eliminar']=="1") {
	mysqli_query($db_con, "delete from calendario where id = '".$id."'");
	if (mysqli_affected_rows($db_con)>'0') {
		echo '
<br /><div><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			La actividad ha sido borrada correctamente de la base de datos.
			</div></div>';
	}
}

if(acl_permiso($_SESSION['cargo'], array('1','5')) && isset($confirmado) && $confirmado) {
	$result_confirmado = mysqli_query($db_con, "SELECT `confirmado` FROM `calendario` WHERE `id` = '".$id."' LIMIT 1");
	$row_confirmado = mysqli_fetch_array($result_confirmado);
	$estado_confirmado = $row_confirmado['confirmado'];

	if ($estado_confirmado == 0) {
		mysqli_query($db_con, "UPDATE calendario SET confirmado =  '1' WHERE id = '".$id."' LIMIT 1");
		echo '
		<div>
			<div class="alert alert-success alert-block fade in">
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	            La actividad extraescolar ha sido autorizada.
			</div>
		</div>';
	}
	elseif ($estado_confirmado == 1) {
		mysqli_query($db_con, "UPDATE calendario SET confirmado =  '0' WHERE id = '".$id."' LIMIT 1");
		echo '
		<div>
			<div class="alert alert-success alert-block fade in">
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	            La actividad extraescolar ha sido desautorizada.
			</div>
		</div>';
	}

}
if($detalles == '1')
{
	?> <?php
	$datos0 = "select * from calendario where id = '$id'";
	$datos1 = mysqli_query($db_con, $datos0);
	$datos = mysqli_fetch_array($datos1);
	$fecha00 = explode("-",$datos[4]);
	$fecha0 = explode("-",$datos[6]);
	$fecha_ini = "$fecha00[2]-$fecha00[1]-$fecha00[0]";
	$fecha_fin = "$fecha0[2]-$fecha0[1]-$fecha0[0]";

	if ($fecha_ini !== $fecha_fin) {
		$fecha = $fecha_ini." || ".$fecha_fin;
	}
	else{
		$fecha = $fecha_ini;
	}
	$registro = $datos[13];
	?>
<div>
<h3>Información completa de Actividad Extraescolar</h3>
<br />
</div>
<div>
<table align="center" class="table table-bordered table-striped" style="width:auto;">
	<tr>
		<th colspan="2">
		<h4 class="text-info"><?php echo $datos[2];?></h4>
		</th>
	</tr>
	<tr>
		<th>Grupos</th>
		<td><?php echo substr($datos[11],0,-1);?></td>
	</tr>
	<tr>
		<th>Descripción</th>
		<td><?php echo $datos[3];?></td>
	</tr>
	<tr>
		<th>Departamento</th>
		<td><?php echo $datos[9];?></td>
	</tr>
	<tr>
		<th>Profesores</th>
		<td><?php echo $datos[10];?></td>
	</tr>
	<tr>
		<th>Horario</th>
		<td><?php
		if ($datos[5]=="00:00:00") {
			echo "Todo el día.";
		}
		else{
		echo $datos[5]." - ".$datos[7];
		}
		?>
		</td>
	</tr>
	<tr>
		<th>Lugar</th>
		<td><?php echo $datos[8];?></td>
	</tr>
	<tr>
		<th>Fecha</th>
		<td><?php echo $fecha;?></td>
	</tr>
	<tr>
		<th>Registro</th>
		<td><?php echo $registro;?></td>
	</tr>
	<tr>
		<th>Autorizada</th>
		<td><?php
		if ($datos['confirmado']=="1") {
			echo "SÍ";
		}
		else{
			echo "NO";
		}
		?></td>
	</tr>
		<tr>
		<th>Observaciones</th>
		<td><?php echo $datos[16];?></td>
	</tr>
</table>
</div>
<br />


	<?php
}
?>


<table class="table table-striped table-hover datatable">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Actividad</th>
			<th>Unidades</th>
			<th>Profesores</th>
			<th class="hidden-print"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$meses = "select distinct month(fechaini) from calendario where categoria='2' order by fechaini";
	$meses0 = mysqli_query($db_con, $meses);
	while ($mes = mysqli_fetch_array($meses0))
	{
		$mes1 = $mes[0];
		if($mes1 ==  "01") $mes2 = "Enero";
		if($mes1 ==  "02") $mes2 = "Febrero";
		if($mes1 ==  "03") $mes2 = "Marzo";
		if($mes1 ==  "04") $mes2 = "Abril";
		if($mes1 ==  "05") $mes2 = "Mayo";
		if($mes1 ==  "06") $mes2 = "Junio";
		if($mes1 ==  "09") $mes2 = "Septiembre";
		if($mes1 ==  "10") $mes2 = "Octubre";
		if($mes1 ==  "11") $mes2 = "Noviembre";
		if($mes1 ==  "12") $mes2 = "Diciembre";
		$datos0 = "select id, unidades, nombre, descripcion, departamento, profesores, concat(horaini,'-',horafin) as hora, fechaini, confirmado, profesorreg from calendario where month(fechaini) = '$mes1' and date(fechaini) > '".$config['curso_inicio']."' and categoria='2' order by fechaini";
		$datos1 = mysqli_query($db_con, $datos0);
		while($datos = mysqli_fetch_array($datos1))
		{
			$profes_actividad=	$datos['profesores'];
			$fecha0 = explode("-",$datos['fechaini']);
			$fecha = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
			if (substr($fecha0[1],0,1)=="0") {$mes=str_replace("0","",$fecha0[1]);}else{$mes=$fecha0[1];}
			$cal_act = '//'.$config['dominio'].'/intranet/calendario/index.php?mes='.$mes.'&anio='.$fecha0[0].'&viewModal='.$datos['0'];

			$autoriz = $datos['confirmado'];
			$datos['nombre']= str_replace("\\","",$datos['nombre']);
			?>
		<tr>
			<td nowrap><?php echo $datos['fechaini'];?><br><small><span class="label label-default"><?php echo $mes2; ?></span></small></td>
			<td class="col-md-5"><?php echo $datos['nombre'];?><br><small class="text-muted">Responsable: <strong><?php echo obtener_nombre_profesor_por_idea($datos['profesorreg']); ?></strong></small></td>
			<td><?php echo (! empty($datos['unidades'])) ? $datos['unidades'] : '<strong class="text-danger">No se han seleccionado unidades</strong>';?></td>
			<td><?php echo $datos['profesores'];?></td>
			<td class="hidden-print" nowrap>
			<?php
			$result_actividad = mysqli_query($db_con, "SELECT cod_actividad FROM `actividadalumno` WHERE cod_actividad = '".$datos['id']."' LIMIT 1");

				if (mysqli_num_rows($result_actividad)):
				echo '<a href="extraescolares.php?id='.$datos[0].'&ver_lista=1">
						<span class="fas fa-users fa-fw fa-lg" data-bs="tooltip" title="Ver la lista de alumnos seleccionados que realizan la actividad"></span>
					</a>';
				endif;
			?>
			<?php if(stristr($_SESSION['cargo'],'1') == TRUE OR stristr($_SESSION['cargo'],'5') == TRUE OR (stristr($_SESSION['cargo'],'4') == TRUE and $_SESSION['dpt'] == $datos[4]) or (strstr(mb_strtoupper($profes_actividad),mb_strtoupper($_SESSION['profi']))==TRUE)){ ?>
			<?php if (! empty($datos['unidades'])): ?>
			<a href="extraescolares.php?id=<?php echo $datos[0];?>&profesores=<?php  echo $datos[5];?>"><span class="far fa-user fa-fw fa-lg" data-bs="tooltip" title="Seleccionar alumnos que realizan la Actividad"></span></a>
			<?php endif; ?>
			<?php } ?>
			<a href="indexextra.php?id=<?php echo $datos[0];?>&detalles=1"
				data-bs="tooltip" title="Detalles"><span
				class="fas fa-search fa-fw fa-lg"></span></a>
			<?php
					if(stristr($_SESSION['cargo'],'1') == TRUE OR stristr($_SESSION['cargo'],'5') == TRUE OR (stristr($_SESSION['cargo'],'4') == TRUE and $_SESSION['dpt'] == $datos[4])){
			?>
				<a href="<?php echo $cal_act;?>" data-bs="tooltip" title="Editar"><span class="far fa-edit fa-fw fa-lg"></span></a>
			<?php
					}
					?>
					<?php if((stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'5') == TRUE)){ ?>
					<?php if($autoriz == 1) { ?>
						<a href="indexextra.php?id=<?php echo $datos[0];?>&confirmado=1" data-bs="tooltip" title="Desautorizar"><span class="fas fa-check-circle fa-fw fa-lg text-success"></span></a>
					<?php } else { ?>
					<a href="indexextra.php?id=<?php echo $datos[0];?>&confirmado=1" data-bs="tooltip" title="Autorizar"><span class="fas fa-check-circle fa-fw fa-lg text-danger"></span></a>
					<?php } ?>
					<?php if(stristr($_SESSION['cargo'],'1') == TRUE OR stristr($_SESSION['cargo'],'5') == TRUE  OR (stristr($_SESSION['cargo'],'4') == TRUE and $_SESSION['dpt'] == $datos[4])){ ?>
					<a href="indexextra.php?id=<?php echo $datos[0];?>&eliminar=1" data-bs="tooltip" title="Eliminar" data-bb="confirm-delete"><span class="far fa-trash-alt fa-fw fa-lg"></span></a>
					<?php } ?>
				</td>
				<?php }?>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
</div>
</div>
</div>
	<?php include("../../pie.php");?>

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
