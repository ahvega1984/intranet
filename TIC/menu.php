<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

	<div class="container hidden-print">

		<?php if (acl_permiso($carg, array('1'))): ?>
		<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fas fa-cog fa-lg"></span></a>
		<?php endif; ?>

		<!-- Button trigger modal -->
		<a href="#" class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda" style="margin-right: 5px;">
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
						<p>Este módulo incluye varias funciones propias de un Centro con proyecto
						TIC. La primera de todas es la presentación de un formulario para registrar
						incidencias en el uso de las TIC (conexión, avería de máquinas, problemas
						de software, etc.). Las incidencias pueden ser vistas y filtradas, y
						también editadas por parte del autor de la incidencia y el Coordinador TIC.</p>
						<p>Los perfiles de profesores y alumnos nos ofrecen los datos (Usuario y
						Contraseña) para el acceso a la red Andared, la Plataforma Educativa o Moodle.
						La aplicación crea una tabla en la Base de Datos con los nombres de usuario y
						contraseña de alumnos y profesores al importar y actualizar los datos de ambos,
						y crea un par de archivos de texto preparados para la importación masiva de
						los mismos en Gesuser, Moodle, etc.</p>
						<p>El Perfil del Profesor nos presenta nuestro nombre de usuario y clave de
						acceso en Andared y sus servicios. <br>El Perfil del Alumno nos presenta los
						mismos datos para los alumnos de nuestros grupos, de tal manera que podamos
						ofrecérselo, por ejemplo, para el acceso a Moodle. Un formulario nos presenta
						nuestros Grupos y Asignaturas en el Curso Escolar para seleccionar. Si un alumno
						al que damos clase no aparece en el listado, seguramente el problema se encuentra
						en un error de matriculación del mismo (no se ha matriculado en nuestra
						asignatura). Es conveniente comunicar este dato a la Administración del Centro.</p>
						<p>Las Estadísticas TIC nos ofrecen los datos de uso de ordenadores y profesores
						tal como queda reflejado en el sistema de reservas de los mismos. Los ordenadores
						o carros con portátiles deben haber sido registrados durante la instalación, o
						a través de Administración de la Intranet  --> Configuración de la aplicación.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Incidencias TIC</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'incidencia.php')==TRUE) ? ' class="active"' : ''; ?>><a href="incidencia.php">Nueva incidencia</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'inventario.php')==TRUE) ? ' class="active"' : ''; ?>><a href="inventario.php">Inventario TIC</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'mesas_tic')==TRUE) ? ' class="active"' : ''; ?>><a href="mesas_tic_seleccion.php">Asignación de mesas</a></li>
			<li	class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'perfiles_')==TRUE) ? ' active' : ''; ?>">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Perfiles <span class="caret"></span> </a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="perfiles_alumnos.php">Perfiles de alumnos</a></li>
					<li><a href="perfiles_profesores.php">Perfiles de profesores</a></li>
				</ul>
			</li>
			<li	class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Manuales <span class="caret"></span> </a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2017/02/Manual_Guadalinex_Edu_Slim.pdf" target="_blank">Manual de Usuario de Guadalinex Edu Slim</a></li>
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2017/02/Manual_Guadalinex_Edu_Next.pdf" target="_blank">Manual de Usuario de Guadalinex Edu Next</a></li>
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2017/03/Manual_de-_Usuario_HGR.pdf" target="_blank">Herramienta de Gestión de Recursos (HGR)</a></li>
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2015/09/Guia_Rapida_SDI11.pdf" target="_blank">Guía Rápida Solución Digital Integral (SDI)</a></li>
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2017/03/Manual-Gesuser-3.2.pdf" target="_blank">Manual de Gesuser 3.2</a></li>
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2013/10/Manual-Administración-BiblioWeb.pdf" target="_blank">Manual de Administración de BiblioWeb</a></li>
					<li><a href="http://www.juntadeandalucia.es/educacion/cga/portal/wp-content/uploads/2013/12/Sincronización-de-BiblioWeb-con-Windows-72.pdf" target="_blank">Sincronización BiblioWeb con ABIES en Windows 7</a></li>
				</ul>
			</li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'estadisticas.php')==TRUE) ? ' class="active"' : ''; ?>><a href="estadisticas.php">Estadísticas TIC</a></li>
		</ul>

	</div>
