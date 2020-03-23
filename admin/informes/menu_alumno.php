<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
<div class="container hidden-print">

	<ul class="nav nav-tabs">
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'cdatos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/datos/cdatos.php">Datos</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'ccursos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/ccursos.php">Listas</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'informes/')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/cinforme.php">Informes</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'mesas_consulta.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/mesas_consulta.php">Mesas</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'pendientes')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/pendientes/index.php">Pendientes</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'fotos/')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/fotos/index.php">Fotografías</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'seleccion_alumnos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/seleccion_alumnos.php">Selección de alumnos</a></li>
	</ul>

</div>
