<?php
if (file_exists("config.php")) {
  include("config.php");
}

if (isset($_GET['id'])) {$id = limpiarInput($_GET['id'], 'numeric');}elseif (isset($_POST['id'])) {$id = limpiarInput($_POST['id'], 'numeric');}else{$id="";}
if (isset($_GET['eliminar'])) {$id = limpiarInput($_GET['eliminar'], 'numeric');}elseif (isset($_POST['eliminar'])) {$eliminar = limpiarInput($_POST['eliminar'], 'numeric');}else{$eliminar="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['crear'])) {$crear = $_GET['crear'];}elseif (isset($_POST['crear'])) {$crear = $_POST['crear'];}else{$crear="";}
if (isset($_GET['buscar'])) {$buscar = limpiarInput($_GET['buscar'], 'alphanumericspecial');}elseif (isset($_POST['buscar'])) {$buscar = limpiarInput($_POST['buscar'], 'alphanumericspecial');}else{$buscar="";}
if (isset($_GET['modificar'])) {$modificar = $_GET['modificar'];}elseif (isset($_POST['modificar'])) {$modificar = $_POST['modificar'];}else{$modificar="";}
if (isset($_GET['confirmado'])) {$confirmado = limpiarInput($_GET['confirmado'], 'alphanumericspecial');}elseif (isset($_POST['confirmado'])) {$confirmado = limpiarInput($_POST['confirmado'], 'alphanumericspecial');}else{$confirmado="";}
if (isset($_GET['calendario'])) {$calendario = limpiarInput($_GET['calendario'], 'alphanumericspecial');}elseif (isset($_POST['calendario'])) {$calendario = limpiarInput($_POST['calendario'], 'alphanumericspecial');}else{$calendario="";}
if (isset($_GET['detalles'])) {$detalles = limpiarInput($_GET['detalles'], 'alphanumericspecial');}elseif (isset($_POST['detalles'])) {$detalles = limpiarInput($_POST['detalles'], 'alphanumericspecial');}else{$detalles="";}

if (isset($_GET['departamento'])) {$departamento = limpiarInput($_GET['departamento'], 'alphanumericspecial');}elseif (isset($_POST['departamento'])) {$departamento = limpiarInput($_POST['departamento'], 'alphanumericspecial');}else{$departamento="";}
if (isset($_GET['act_calendario'])) {$act_calendario = limpiarInput($_GET['act_calendario'], 'alphanumericspecial');}elseif (isset($_POST['act_calendario'])) {$act_calendario = limpiarInput($_POST['act_calendario'], 'alphanumericspecial');}else{$act_calendario="";}
if (isset($_GET['fecha'])) {$fecha = limpiarInput($_GET['fecha'], 'alphanumericspecial');}elseif (isset($_POST['fecha'])) {$fecha = limpiarInput($_POST['fecha'], 'alphanumericspecial');}else{$fecha="";}
if (isset($_GET['fecha_act'])) {$fecha_act = limpiarInput($_GET['fecha_act'], 'alphanumericspecial');}elseif (isset($_POST['fecha_act'])) {$fecha_act = limpiarInput($_POST['fecha_act'], 'alphanumericspecial');}else{$fecha_act="";}
if (isset($_GET['horario'])) {$horario = limpiarInput($_GET['horario'], 'alphanumericspecial');}elseif (isset($_POST['horario'])) {$horario = limpiarInput($_POST['horario'], 'alphanumericspecial');}else{$horario="";}
if (isset($_GET['profesor'])) {$profesor = limpiarInput($_GET['profesor'], 'alphanumericspecial');}elseif (isset($_POST['profesor'])) {$profesor = limpiarInput($_POST['profesor'], 'alphanumericspecial');}else{$profesor="";}
if (isset($_GET['actividad'])) {$actividad = limpiarInput($_GET['actividad'], 'alphanumericspecial');}elseif (isset($_POST['actividad'])) {$actividad = limpiarInput($_POST['actividad'], 'alphanumericspecial');}else{$actividad="";}
if (isset($_GET['descripcion'])) {$descripcion = limpiarInput($_GET['descripcion'], 'alphanumericspecial');}elseif (isset($_POST['descripcion'])) {$descripcion = limpiarInput($_POST['descripcion'], 'alphanumericspecial');}else{$descripcion="";}
if (isset($_GET['justificacion'])) {$justificacion = limpiarInput($_GET['justificacion'], 'alphanumericspecial');}elseif (isset($_POST['justificacion'])) {$justificacion = limpiarInput($_POST['justificacion'], 'alphanumericspecial');}else{$justificacion="";}
if (isset($_GET['hoy'])) {$hoy = limpiarInput($_GET['hoy'], 'alphanumericspecial');}elseif (isset($_POST['hoy'])) {$hoy = limpiarInput($_POST['hoy'], 'alphanumericspecial');}else{$hoy="";}
if (isset($_GET['q'])) {$expresion = limpiarInput($_GET['q'], 'alphanumericspecial');}elseif (isset($_POST['q'])) {$expresion = limpiarInput($_POST['q'], 'alphanumericspecial');}else{$expresion="";}

$activo1="";
$activo2="";
$activo3="";
if (strstr($_SERVER['REQUEST_URI'],'indexextra.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE){ $activo2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'calendario/')==TRUE){ $activo3 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'informe.php')==TRUE){ $activo4 = ' class="active" ';}


?>
    <div class="container hidden-print">

      <?php if (acl_permiso($carg, array('1'))): ?>
    	<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fas fa-cog fa-lg"></span></a>
    	<?php endif; ?>

    	<!-- Button trigger modal -->
    	<a href="#" class="btn btn-default btn-sm pull-right hidden-print" style="margin-right: 5px;" data-toggle="modal" data-target="#modalAyuda">
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
  						<p>El módulo de Actividades Extraescolares y Complementarias está unido al Calendario de Actividades.
  						 En el Calendario se registran las nuevas actividades por parte de DACE, Jefes de Departamento, Tutores
  						 o Equipo Directivo. Una vez registrada la actividad, el funcionamiento es el siguiente.</p>
  						<p>La actividad debe ser aprobada por el Consejo Escolar, donde la presenta el Director. En la Lista de
  						 Actividades, un icono de verificación rojo indica que la actividad no ha sido aprobada aun por el Director.
  						 Una vez aprobada por éste, el icono se pone verde y aparece en el Calendario como autorizada.</p>
  						<p>La actividad puede ser visualizada por todos los usuarios de la Intranet; editada por parte de Jefes de
  						 Departamento y profesores asociados a la actividad; eliminada por parte de DACE, Jefes de Departamento y
  						 Equipo Directivo; y por último puede ser vinculada a un conjunto de alumnos que seleccionamos para realizar
  						 la actividad (icono de usuario en reunión). Esta última tarea es especialmente importante si utilizamos el
  						 sistema de faltas de asistencia porque bloquea las faltas de aquellos alumnos que están realizando una
  						 actividad; también muestra en el Calendario de la página principal un enlace a los alumnos que asisten a la
  						 actividad para que sea conocido por los profesores que les dan clase (evitando el trabajo de crear una lista
  						 de los alumnos participantes que se entrega a los profesores).</p>
  					</div>
  					<div class="modal-footer">
  						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
  					</div>
  				</div>
  			</div>
  		</div>

      <ul class="nav nav-tabs">
        <?php if (acl_permiso($_SESSION['cargo'], array('1','5'))): ?>
        <li<?php echo $activo1;?>><a href="indexextra.php">Administrar Actividades</a></li>
        <?php else: ?>
        <li<?php echo $activo1;?>><a href="indexextra.php">Lista de Actividades</a></li>
        <?php endif; ?>
        <?php if (acl_permiso($_SESSION['cargo'], array('1','2','4','5')) || (isset($config['extraescolares']['registro_profesores']) && $config['extraescolares']['registro_profesores'])): ?>
        <li<?php echo $activo2;?>><a href="../../calendario/index.php?action=nuevoEvento&calendario=Extraescolares">Introducir nueva actividad</a></li>
        <?php endif; ?>
        <li<?php echo $activo3;?>><a href="../../calendario/index.php">Calendario de actividades</a></li>
        <li<?php echo $activo4;?>><a href="informe_actividades.php">Datos e informes sobre las actividades</a></li>
      </ul>
  </div>
