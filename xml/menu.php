<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<ul class="nav nav-pills nav-stacked">

	<?php if (acl_permiso($_SESSION['cargo'], array('0', '1'))): ?>
	<!-- CONFIGURACIÓN -->
	<li class="nav-header">Configuración</li>
	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<li><a href="../config/config.php">Configuración general y módulos</a></li>
	<?php endif;?>
	<li><a href="//<?php echo $config['dominio']; ?>/intranet/xml/actualizaciones/index.php">Comprobar actualizaciones</a></li>
	<?php if ($_SERVER['SERVER_NAME'] == 'iesmonterroso.org'): ?>
	<li><a href="//<?php echo $config['dominio']; ?>/intranet/analitica/index.php">Instalaciones de la Intranet</a></li>
	<?php endif; ?>
	<?php endif;?>

	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<!-- A PRINCIPIO DE CURSO -->
	<li class="nav-header">A principio de curso...</li>
	<li><a href="jefe/index2.php">Importar Alumnos y Asignaturas</a></li>
	<li><a href="jefe/index_xml.php">Importar datos del Centro</a></li>
	<li><a href="jefe/index_departamentos.php">Importar Profesores y Departamentos</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/horario.php">Importar/Preparar Horarios </a></li>
	<?php endif; ?>
	<li><a href="jefe/index_profesores.php">Importar Profesores, Unidades y Materias</a></li>
	<li><a href="jefe/index_pas.php">Importar Personal no docente</a></li>
	<li><a href="jefe/index_festivos.php">Importar días festivos</a></li>
	<li><a href="jefe/rof/index.php">Modificar ROF</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<!--<li><a href="jefe/index_limpia.php">Limpiar Horarios</a></li>-->
	<?php endif; ?>
	<?php endif; ?>

	<?php if (acl_permiso($_SESSION['cargo'], array('0', '1'))): ?>
	<!-- ACTUALIZACIÓN -->
	<li class="nav-header">Actualización</li>
	<li><a href="jefe/index.php">Actualizar Alumnos</a></li>
	<li><a href="jefe/index_xml.php">Actualizar datos del Centro</a></li>
	<li><a href="jefe/index_departamentos2.php">Actualizar Profesores y Departamentos</a></li>
	<li><a href="jefe/horario.php">Actualizar Horarios</a></li>
	<li><a href="jefe/index_profesores.php">Actualizar Profesores, Unidades y Materias</a></li>
	<li><a href="jefe/index_pas.php">Actualizar Personal no docente</a></li>

	<!-- PERSONAL DEL CENTRO -->
	<li class="nav-header">Personal del Centro</li>
	<li><a href="../config/cargos.php">Perfiles de los profesores</a></li>
	<li><a href="jefe/gest_dep.php">Gestión de los Departamentos</a></li>
	<li><a href="jefe/reset_password.php">Restablecer contraseñas</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/horarios/index.php">Crear / Modificar horarios</a></li>
	<li><a href="jefe/horas.php">Hoja de Firmas para Profesores</a></li>
	<?php endif; ?>
	<li><a href="jefe/index_hor.php">Sustitución de profesores</a></li>
	<li><a href="jefe/index_fotos_profes.php">Subir fotos de profesores</a></li>
	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<li><a href="jefe/informes/accesos.php">Informe de accesos</a></li>
	<?php endif; ?>

	<!-- ALUMNOS -->
	<li class="nav-header">Alumnos</li>
	<li><a href="../admin/cursos/listados.php?todasUnidades=1" target="_blank">Listas de todos los Grupos</a></li>
	<li><a href="jefe/form_carnet.php">Carnet de los alumnos</a></li>
	<li><a href="jefe/index_fotos.php">Subir fotos de alumnos</a></li>
	<?php if(isset($config['mod_matriculacion']) && $config['mod_matriculacion']): ?>
	<li><a href="../admin/matriculas/index.php">Matriculación de alumnos</a></li>
	<?php endif; ?>
	<li><a href="./jefe/index_mayores.php">Alumnos mayores de 18 años</a></li>
	<?php if($_SESSION['pagina_centro']==1): ?>
	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<li><a href="jefe/informes/accesos_alumnos.php">Informe de accesos</a></li>
	<?php endif; ?>
	<?php endif; ?>
	<li><a href="../admin/libros-texto/programa-gratuidad/index.php" target="_blank">Libros de texto gratuitos</a></li>
	<!-- CALIFICACIONES -->
	<li class="nav-header">Notas de evaluación</li>
	<li><a href="jefe/index_notas.php">Importar Calificaciones</a></li>
	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<li><a href="jefe/calificaciones/index.php">Imprimir Calificaciones</a></li>
	<?php endif; ?>

	<?php if(isset($config['mod_centrotic']) && $config['mod_centrotic']): ?>
	<!-- CENTRO TIC -->
	<li class="nav-header">Centro TIC</li>
	<li><a href="jefe/exportaTIC.php?exportar=alumnos.txt" target="_blank">Usuarios alumnos (Gesuser)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=alumnos_moodle.txt" target="_blank">Usuarios alumnos (Moodle)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=alumnos_gsuite.csv" target="_blank">Usuarios alumnos (G Suite)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=alumnos_office365.csv" target="_blank">Usuarios alumnos (Office 365)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=profesores.txt" target="_blank">Usuarios profesores (Gesuser)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=profesores_moodle.txt" target="_blank">Usuarios profesores (Moodle)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=profesores_gsuite.csv" target="_blank">Usuarios profesores (G Suite)</a></li>
	<li><a href="jefe/exportaTIC.php?exportar=profesores_office365.csv" target="_blank">Usuarios profesores (Office 365)</a></li>
	<?php endif; ?>

	<!-- SISTEMA DE RESERVAS -->
	<li class="nav-header">Sistema de Reservas</li>
	<li><a href="../reservas/gestion_tipo.php">Gestión de reservas</a></li>
	<?php endif; ?>

	<li class="nav-header">Bases de datos</li>
	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<li><a href="../config/config_datos.php">Bases de datos de cursos anteriores</a></li>
	<?php endif; ?>
	<li><a href="jefe/copia_db/index.php">Copias de seguridad</a></li>

	<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
	<?php if(isset($config['mod_sms']) && $config['mod_sms']): ?>
	<li class="nav-header">SMS</li>
	<li><a href="jefe/index_sms.php">Información envíos SMS</a></li>
	<?php endif; ?>
	<?php endif; ?>
</ul>
