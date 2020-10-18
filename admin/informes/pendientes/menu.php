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
					<p>Los informes de evaluación extraordinaria y de materias pendientes permiten al profesor crear documentos para los alumnos en los que se especifican las unidades, contenidos y actividades de las distintas materias que el alumno debe preparar para superar las pruebas correspondientes. </p>
					<p>La página inicial del módulo nos permite crear informes para las distintas materias que impartimos (<em>Nuevo informe</em>) así como decidir la fecha y modalidad de la prueba. Una vez creado el informe, debemos generar las unidades, contenidos y actividades que se le exigirán al alumno en la prueba. Esta tarea es persistente y se mantiene a lo largo de los cursos académicos, pudiendo editarse y modificarse cuando se precise. Una vez creado el informe con sus contenidos y actividades, podemos <b>convertir el informe en plantilla</b>, de tal modo que el resto de los profesores que compartan cursos de la misma asignatura dentro del mismo nivel puedan incorporar automáticamente los datos generales del informe al crear sus propios informes. Por esta razón, es conveniente que el departamento se divida el trabajo de elaboración de las plantillas de las que se beneficiarán los demás miembros del mismo, ahorrando mucho trabajo. Al crear un nuevo informe se cargarán los datos de la plantilla si esta existe, pero una vez cargados los datos estos pueden ser editados y personalizados por cada profesor para adaptarlos a sus grupos.</p>
					<p>Una vez elaborados los detalles del informe a satisfacción del profesor, este debe volver a la página inicial de los informes para seleccionar los alumnos que deben presentarse a la prueba correspondiente, así como marcar las unidades y temas de la misma. El campo <em>Observaciones</em> perimite personalizar la selección de los contenidos para cada alumno, e incluir cualquier comentario o tarea que el profesor considere oportuno para cada alumno en concreto.</p>
					<p>Una vez creados los informes de los distintos alumnos, estos pueden ser imprimidos o eliminados desde la página <b>Administrar informes></b>. Los informes apareceán en la sección de calificaciones de las evaluaciones de la página personal del alumno listos para descargar en formato PDF, si el centro utiliza la página web que complementa a la intranet.</p>
					<p>Si en vez de seleccionar alumnos para la prueba extraordinaria queremos redactar un informe para alumnos con materias pendientes, debemos ir a la <em>evaluacion de materias pendientes</em>. Allí encontraremos, al lado de los campos donde introducimos las calificacciones de las distintas evaluaciones, un icono que nos enviará a la página donde seleccionaremos los contenidos y actividades de las pruebas que deberán superar a lo largo del curso. Los informes aparecerán como cualquier otro informe en la página de administración de los informes, o bien en la página personal del alumno en la web del centro.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'evaluacion_pendientes/index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/evaluacion_pendientes/index.php">Evaluación de alumnos con materias pendientes</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'/pendientes/index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/pendientes/index.php">Mis informes</a></li>
		<?php if (stristr($_SESSION['cargo'], "1") OR stristr($_SESSION['cargo'], "2") OR stristr($_SESSION['cargo'], "4")) { ?>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'/pendientes/admin.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/pendientes/admin.php">Administrar informes</a></li>
		<?php } ?>
	</ul>

</div>