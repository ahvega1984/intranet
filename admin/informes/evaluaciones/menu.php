<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
	
	<div class="container-fluid hidden-print">
		
		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'], 'index.php') == true) ? ' class="active"' : ''; ?>><a href="index.php">Estadísticas por niveles</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'], 'asignaturas.php') == true) ? ' class="active"' : ''; ?>><a href="asignaturas.php">Estadísticas por asignaturas</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'], 'profesores.php') == true) ? ' class="active"' : ''; ?>><a href="profesores.php">Estadísticas por profesores</a></li>
		</ul>
	
	</div>
