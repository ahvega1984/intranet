<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<div class="container hidden-print">

	<!-- Button trigger modal -->
	<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
		<span class="fas fa-question fa-lg"></span>
	</a>

	<!-- Modal -->
	<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
					<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
				</div>
				<div class="modal-body">
					<p>Los informes de evaluación de alumnos con materias pendientes permiten al profesor crear documentos para estos en los que se especifican las unidades, contenidos y actividades de las distintas materias que el alumno debe preparar para superar las pruebas correspondientes. </p>
					<p>Algunas instrucciones para que sepáis operar el mecanismo para crear informes de evaluación de alumnos con pendientes.</p>
					<p>Necesitamos preparar primero los contenidos y actividades de los informes de los distintos niveles, así que nos vamos a <b>Alumnos —> Evaluaciones —> Evaluación de pendientes</b>. Eso os coloca delante de los informes elaborados por vuestro departamento en las distintas materias y niveles. Podemos crear un informe, o bieneditar un informe ya preparado y cambiarlo puntualmente; o bien podemos borrar un informe y partir de cero creando uno fresco. Solo es necesario crear una plantilla / informe por nivel educativo puesto que el informe será común para todo el nivel. </p>
					<p>Una vez preparados los informes, pasamos a elaborar y registrar los informes individuales de cada alumnocon pendientes en las materias y niveles del departamento. Pulsais sobre el enlace <b>Evaluación de alumnos pendientes</b> en la parte superior de la página, o bien volvéis a <b>Alumnos --> Evaluaciones --> Evaluación de pendientes</b>. Al final de la lista de alumnos con esa materia pendiente encontramos un icono de edición que nos lleva al informe que el Departamento redactó para la evaluación de septiembre. Suponiendo que el informe de septiembre sea válido para los pendientes de la materia, simplemente seleccionamos los contenidos, redactamos las observaciones si procede y listos. Si no es el caso, pasamos al tercer punto.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'evaluacion_pendientes/index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/evaluacion_pendientes/index.php">Evaluación de alumnos con pendientes</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'admin/pendientes/')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/pendientes/index.php">Consulta de alumnos con pendientes</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'/pendientes/index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/pendientes/index.php">Mis informes</a></li>
		<?php if (stristr($_SESSION['cargo'], "1") OR stristr($_SESSION['cargo'], "2") OR stristr($_SESSION['cargo'], "4")) { ?>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'admin.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/pendientes/admin.php">Administrar informes</a></li>
		<?php } ?>
	</ul>

</div>