<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

if (isset($_GET['curso'])) {$curso = $_GET['curso'];}elseif (isset($_POST['curso'])) {$curso = $_POST['curso'];}else{$curso="";}
if (isset($_GET['dni'])) {$dni = $_GET['dni'];}elseif (isset($_POST['dni'])) {$dni = $_POST['dni'];}else{$dni="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
if (isset($_GET['listados'])) {$listados = $_GET['listados'];}elseif (isset($_POST['listados'])) {$listados = $_POST['listados'];}else{$listados="";}
if (isset($_GET['listado_total'])) {$listado_total = $_GET['listado_total'];}elseif (isset($_POST['listado_total'])) {$listado_total = $_POST['listado_total'];}else{$listado_total="";}
if (isset($_GET['imprimir'])) {$imprimir = $_GET['imprimir'];}elseif (isset($_POST['imprimir'])) {$imprimir = $_POST['imprimir'];}else{$imprimir="";}
if (isset($_GET['cambios'])) {$cambios = $_GET['cambios'];}elseif (isset($_POST['cambios'])) {$cambios = $_POST['cambios'];}else{$cambios="";}
if (isset($_GET['sin_matricula'])) {$sin_matricula = $_GET['sin_matricula'];}elseif (isset($_POST['sin_matricula'])) {$sin_matricula = $_POST['sin_matricula'];}else{$sin_matricula="";}
?>
	
	<div class="container hidden-print">

		<!-- Button trigger modal -->
		<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
			<span class="fa fa-question fa-lg"></span>
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
						<p>Este módulo permite matricular a los alumnos a través de un formulario accesible desde internet. La matriculación puede realizarse a través de la propia intranet o bien desde la página pública del Centro. Este segundo método es el preferido para que los alumnos se registren masivamente, y la intranet se reserva para que la Dirección peueda matricular casos especiales (alumnos que no pertenecen al Centro, alumnos que se matriculan tarde, etc.). El módulo que permite el registro de datos desde la página pública está incluído en el código de nuestra página en este mismo repositorio <a href="https://github.com/IESMonterroso/pagina_del_centro">aquí</a>.</p>
						<p>El módulo de la página pública se activa entre las fechas de inicio y fin que deben seleccionarse en las <em> <a href="preferencias.php">Opciones</a></em>. El módulo de la Intranet aparece en el menú de los perfiles Dirección, Orientación y Administración durante los meses que van de Junio a Diciembre, aunque la Dirección siempre puede acceder desde la <em>Administarción de la Intranet</em>. Tanto la intranet como la página pública contienen un formulario de registro idéntico. Si el alumno pertenece al Centro aparecen en primer lugar los datos personales y familiares que ya se habían registrado en Séneca. Si el alumno pertenece a un C.E.I.P. o I.E.S. adscritos hay que pedir al Director del mismo que nos envíe una copia de la exportación de alumnos desde Séneca (Alumnado --&gt; Alumnado --&gt; Alumnado del Centro --&gt; Aceptar (arriba a la derecha) --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano). Una vez tengamos los archivos de los centros adscritos debemos importarlos desde la página correspondiente en el menú <b>Herramientas</b>. Una vez comprobados los datos personales el alumno debe seleccionar las asignaturas optativas, actividades de refuerzo, religión, etc. Debe elegir las optativas y refuerzos tanto del siguiente curso como del mismo en el que ya se encuentra. De este modo, al importar las notas de la Evaluación Extraordinaria en Septiembre la matrícula se asigna automáticamente en función de si este ha promocionado de curso o no. <br>Cuando se alcanza la fecha de fin de matriculación ya podemos imprimir de forma masiva los formularios y autorizaciones de la matrícula para entregar a los alumnos.</p>
						<p>Nosotros seguimos el siguiente método concreto durante el proceso. Durante el mes de Mayo se informa a los tutores de un nivel del proceso de registro de datos poniendo un ejemplo visual de un alumno. Los alumnos son informados por el tutor del proceso y se les recuerda el NIE. Durante las dos primeras semanas de Junio los alumnos se matriculan. Les dividimos en dos grupos: los de 1º y 2º de ESO realizan la matriculación en el aula con un carro de ordenadores portátiles o en las salas de Informática; los alumnos mayores lo hacen desde su casa. Los Directores de los Centros adscritos informan a los padres del proceso, y les ofrecen el NIE para se matriculen desde la página pública o pasen por nuestro Centro para que les registremos.<br>
						Cuando el regsitro masivo ha terminado, se imprimen los formularios. Los Centros adscritos reciben el conjunto de matrículas de sus alumnos; los tutores los distribuyen para que los nenes los lleven a casa; los padres firman el impreso y lo devuelven al Centro; este nos devuelve el conjunto de los impresos para que los administrativos puedan porceder a la matriculación en Séneca. Nuestro alumnos reciben el formulario el mismo día en que se entregan las notas de la Evaluación Ordinaria y los libros de texto gratuitos, y entregan la matrícula firmada por sus padres en las fechas elegidas para cada nivel o grupos.</p>

						<h5>Opciones del módulo</h5>
						<p>Para utilizar el módulo de matriculación es necesario precisar tanto las fechas de inicio y fin del proceso de matrciculación como el nombre de itinerarios, optativas y actividades en los distintos niveles.<br>
						<br>
						La segunda parte se ocupa de los nombres de asignaturas en los distintos niveles. Cada Centro debe escribir las optativas y actividades que se imparten en su IES. Se ha mantenido el conjunto de asignaturas del IES Monterroso como base para trabajar y como ejemplo para simplificar la tarea. 
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>

		<?php if (acl_permiso($carg, array('1'))): ?>
		<a href="preferencias.php" class="btn btn-sm btn-default pull-right" style="margin-right:2px;"><span class="fa fa-cog fa-lg"></span></a>
		<?php endif; ?>
		
		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'previsiones.php')==TRUE) ? ' class="active"' : ''; ?>><a href="previsiones.php">Previsiones de matrícula</a></li>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'consultas')==TRUE) ? ' active' : ''; ?>">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			    Consultas <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="consultas.php">Matriculas de ESO</a></li>
			    <li><a href="consultas_bach.php">Matriculas de Bachillerato</a></li>
			  </ul>
			</li>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'index')==TRUE) ? ' active' : ''; ?>">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			    Matriculación <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="index.php">Matricular en ESO</a></li>
			    <li><a href="index_bach.php">Matricular en Bachillerato</a></li>
			  </ul>
			</li>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'importar')==TRUE) ? ' active' : ''; ?>">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			    Herramientas <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="index_primaria.php">Importar Alumnado de Primaria</a></li>
			  	<li><a href="index_secundaria.php">Importar Alumnado de ESO</a></li>
			  	<li><a href="activar_matriculas.php?activar=1">Activar matriculación</a></li>
			  	<li><a href="activar_matriculas.php?activar=2">Desactivar matriculación</a></li>
			  </ul>
			</li>
			<li><a href="consulta_transito.php">Informes de Tránsito</a></li>
		</ul>
		
	</div>
	