<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

	<div class="container hidden-print">
		
		<!-- Button trigger modal -->
		<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
			<span class="far fa-question fa-lg"></span>
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
						<p>Los jefes de departamento utilizan este formulario para registrar los 
						libros de texto que afectan a sus asignaturas en los distintos niveles educativos.</p>
						<p>Los libros de texto pueden ser consultados por todos los profesores 
						dentro de la Intranet, pero también se trasladan a la página Web del centro, 
						desde la cual pueden ser consultados por las librerías y las familias de 
						los alumnos.</p>
						<p>El módulo permite a los tutores consultar el estado de los libros de texto del 
						 Programa de Gratuidad, así como utilizar la aplicación para registrarlo al final 
						 de cada curso escolar. También permite consultar el estado de los libros por nivel 
						 y grupo, así como generar Certificados de Reposición y Entrega de libros
						 preparados para entregar oficialmente a las familias del alumno.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>
	
		<ul class="nav nav-tabs">
			<li<?php echo ((strstr($_SERVER['REQUEST_URI'],'libros-texto/index.php') == true)) ? ' class="active"' : '' ; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/libros-texto/index.php">Libros de texto</a></li>
			<?php if (acl_permiso($_SESSION['cargo'], array(1, 2))): ?>
			<li<?php echo ((strstr($_SERVER['REQUEST_URI'],'libros-texto/programa-gratuidad/index.php') == true)) ? ' class="active"' : '' ; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/libros-texto/programa-gratuidad/index.php">Programa de Gratuidad</a></li>
			<?php endif; ?>
		</ul>
		
	</div>