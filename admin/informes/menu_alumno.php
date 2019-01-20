<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
<div class="container hidden-print">

	<ul class="nav nav-tabs">
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'cinforme.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/cinforme.php">Informe de un alumno</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'cdatos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/datos/cdatos.php">Datos de alumnos</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'mesas_consulta.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/mesas_consulta.php">Asignación de mesas</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'ccursos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/ccursos.php">Listas de los grupos</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'pendientes')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/pendientes/index.php">Alumnado con materias pendientes</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'cexporta.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/cexporta.php">Exportar datos</a></li>
	</ul>

</div>
