<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
<div class="container hidden-print">

	<ul class="nav nav-tabs">
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'cdatos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/datos/cdatos.php">Datos</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'ccursos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/ccursos.php">Listas</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'informes/')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/cinforme.php">Informes</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'chorarios.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/chorarios.php">Horarios</a></li>		
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'fotos/')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/fotos/index.php">Fotografías</a></li>
		<li<?php if (strstr($_SERVER['REQUEST_URI'],'seleccion_alumnos.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/seleccion_alumnos.php">Selección de alumnos</a></li>
		<li role="presentation" class="dropdown">
	    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
	      Más... <span class="caret"></span>
	    </a>
	    <ul class="dropdown-menu">
	      <li<?php if (strstr($_SERVER['REQUEST_URI'],'pendientes')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/pendientes/index.php">Alumnos con materias pendientes</a></li>
	      <li<?php if (strstr($_SERVER['REQUEST_URI'],'adaptaciones')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/departamento/adaptaciones/index.php">Adaptaciones curriculares (ACNS)</a></li>
	      <li<?php if (strstr($_SERVER['REQUEST_URI'],'mesas_consulta.php')==TRUE) echo ' class="active"'; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/mesas_consulta.php">Mesas de los grupos</a></li>
	    </ul>
	  </li>
	</ul>

</div>
