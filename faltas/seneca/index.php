<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$error=0;
if(isset($_POST['Submit'])) {

	$tipo = $_POST['tipo'];
	$iniciofalta = $_POST['iniciofalta'];
	$finfalta = $_POST['finfalta'];

	if (!empty($iniciofalta) && !empty($finfalta)) {
		switch ($tipo) {
			default :
			case 1 : header("Location:"."exportarSeneca.php?iniciofalta=$iniciofalta&finfalta=$finfalta"); break;
			case 2 : header("Location:"."exportar.php?iniciofalta=$iniciofalta&finfalta=$finfalta"); break;
		}
	}
	else {
		$error=1;
	}
}

include("../../menu.php");
include("../menu.php");
?>
<div class="container">

	<div class="page-header">
	  <h2>Faltas de Asistencia <small> Exportar faltas a Séneca</small></h2>
	</div>

	<?php if (isset($_GET['msg_error']) && $_GET['msg_error'] == 1): ?>
	<div class="alert alert-danger alert-block fade in">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Seleccione fecha <strong>Primer día</strong> y <strong>Último día</strong> válidos para exportar las faltas de asistencia.
	</div>
	<?php endif; ?>

	<?php if (isset($_GET['msg_error']) && $_GET['msg_error'] == 2): ?>
	<div class="alert alert-danger alert-block fade in">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		No ha importado los datos de los alumnos. Siga las instrucciones del formulario de la izquierda.
	</div>
	<?php endif; ?>

	<?php if (isset($_GET['msg_error']) && $_GET['msg_error'] == 3): ?>
	<div class="alert alert-danger alert-block fade in">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Se ha producido un error al generar el archivo comprimido con las faltas de asistencia por cada unidad.
	</div>
	<?php endif; ?>

	<?php
	if(isset($_POST['enviar'])) {
		 // Descomprimimos el zip de las calificaciones en el directorio origen/ tras eliminar los antiguos
		$dir = "./origen/";
		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if (is_file($dir.$file) && strstr($file,"xml") == TRUE) {
				unlink($dir.$file);
			}
		}

		include('../../lib/pclzip.lib.php');
		$archive = new PclZip($_FILES['archivo1']['tmp_name']);
		if ($archive->extract(PCLZIP_OPT_PATH, $dir) == 0) {
			die("Error : ".$archive->errorInfo(true));
		}
		echo '
		<div class="alert alert-success alert-block fade in">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			La relación de alumnos y unidades del Centro se ha actualizado.
		</div>
		<br />';
	}
	?>

	<div class="row">

		<div class="col-sm-6">

			<form enctype="multipart/form-data" action="index.php" method="post" role="form">
			  <div class="well well-large">
					<fieldset>
						<legend>Importar datos de los alumnos</legend>

						<div class="form-group">
							<label for="archivo1">Exportacion_Faltas_Alumnado.zip</label>
							<input type="file" id="archivo1" name="archivo1" class="input input-file" id="file1">
						</div>

						<br>

					  <button type="submit" name="enviar" class="btn btn-primary">Importar datos</button>
					</fieldset>
			  </div><!-- ./well -->

			  <div style="text-align:justify; <?php if($error) echo 'color: red;';?>">
					<h4>Información sobre la importación</h4>
					<p class="help-block">Para poder subir las faltas de los alumnos a Séneca, es necesario en primer lugar descargar un archivo desde Séneca, <strong>Utilidades</strong> > <strong>Importación/Exportación de Datos</strong>, luego seleccione <strong>Exportar datos desde Séneca</strong> y <strong>Exportación Faltas Alumnado</strong>.</p>
					<p class="help-block">Selecciona todos los grupos y acepta la fecha propuesta. Descarga el archivo y selecciónalo para proceder.</p>
					<p class="help-block">Es necesario repetir este procedimiento si se han realizado cambios de grupo a lo largo del curso para actualizar la lista de los alumnos.</p>
			 </div>
			</form>

		</div><!-- ./col-sm-6 -->


		<div class="col-sm-6">

			<div class="well">

			  <form id="form1" name="form1" method="post" action="index.php">
				  <fieldset>
						<legend>Exportar faltas a Séneca</legend>

						<div class="clearfix">
							<div class="form-group col-md-6" id="datetimepicker1" style="display: inline;">
								<label class="control-label" for="iniciofalta">Primer día:</label>
								<div class="input-group">
									<input name="iniciofalta" type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" data-date-format="DD/MM/YYYY" id="iniciofalta" required>
									<span class="input-group-addon"><i class="far fa-calendar"></i></span>
								</div>
							</div>

							<div class="form-group col-md-6" id="datetimepicker2" style="display: inline;">
								<label class="control-label" for="finfalta">Último día:</label>
								<div class="input-group">
									<input name="finfalta" type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" data-date-format="DD/MM/YYYY" id="finfalta" required>
									<span class="input-group-addon"><i class="far fa-calendar"></i></span>
								</div>
							</div>
						<div>

						<br>

				    <p><strong>Tipo de exportación:</strong></p>

				    <div class="radio">
							<label>
								<input type="radio" name="tipo" value="1" checked>
								Generar un archivo con todas las unidades.
							</label>
						</div>

						<div class="radio">
							<label>
								<input type="radio" name="tipo" value="2">
								Generar un archivo por cada unidad.
							</label>
						</div>

						<br>
				  	<button type="submit" name="Submit" class="btn btn-primary btn-block">Exportar Datos</button>
					</fieldset>
				</form>

		  </div>

			<div style="text-align:justify; <?php if($error) echo 'color: red;';?>">
				<h4>Información sobre la exportación</h4>
		    <p class="help-block">La condición esencial que debe cumplirse para poder subir las faltas a Séneca es que el horario de los profesores esté correctamente registrado en Séneca. El 99% de los problemas que puedan surgir al subir las faltas se deben al horario. Revisa el horario con detenimiento antes de proceder, con especial cuidado a los cursos de Bachillerato.<p>
		    <p class="help-block">Es importante que los datos de los alumnos estén actualizados para evitar errores en la importación de las faltas. El formulario de la izquierda permite actualizar la información. Además, ten en cuenta que Séneca sólo acepta importaciones de un mes máximo de faltas de asistencia. Por esta razón, el <strong>Primer día</strong> que introduces debe ser el primer día del mes (o el mas próximo en caso de que sea un mes de vacaciones, o puente coincidente con los primeros dias de un mes, etc.). El mismo criterio se aplica para el <strong>Último día</strong> del mes.</p>
				<p class="help-block">Una vez le damos a enviar se generan los ficheros (o el fichero comprimido, según la opción elegida) que posteriormente se importan a Séneca, así que ya puedes abrir la pagina de Séneca para hacerlo. La opción por defecto, más simple y cómoda, es la creación de un archivo comprimido con todos los grupos. El archivo comprimido se genera en el navegador preparado para subir.<br /> La opción de crear tantos archivos como grupos tiene una función de ayuda en caso de problemas al subir las faltas a Séneca (ayuda a determinar dóde se encuentra el problema y solucionarlo).</p>
			</div>

		</div><!-- ./col-sm-6 -->

	</div><!-- ./row -->

</div><!-- ./container -->

<?php include("../../pie.php"); ?>

<?php
$exp_inicio_curso = explode('-', $config['curso_inicio']);
$inicio_curso = $exp_inicio_curso[2].'/'.$exp_inicio_curso[1].'/'.$exp_inicio_curso[0];

$exp_fin_curso = explode('-', $config['curso_fin']);
$fin_curso = $exp_fin_curso[2].'/'.$exp_fin_curso[1].'/'.$exp_fin_curso[0];

$result = mysqli_query($db_con, "SELECT fecha FROM festivos ORDER BY fecha ASC");
$festivos = '';
while ($row = mysqli_fetch_array($result)) {
	$exp_festivo = explode('-', $row['fecha']);
	$dia_festivo = $exp_festivo[2].'/'.$exp_festivo[1].'/'.$exp_festivo[0];

	$festivos .= '"'.$dia_festivo.'", ';
}

$festivos = substr($festivos,0,-2);
?>
	<script>
	$(function ()
	{
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false,
			minDate:'<?php echo $inicio_curso; ?>',
			maxDate:'<?php echo $fin_curso; ?>',
			disabledDates: [<?php echo $festivos; ?>],
			daysOfWeekDisabled:[0,6]
		});

		$('#datetimepicker2').datetimepicker({
			language: 'es',
			pickTime: false,
			minDate:'<?php echo $inicio_curso; ?>',
			maxDate:'<?php echo $fin_curso; ?>',
			disabledDates: [<?php echo $festivos; ?>],
			daysOfWeekDisabled:[0,6]
		});
	});
	</script>
</body>
</html>
