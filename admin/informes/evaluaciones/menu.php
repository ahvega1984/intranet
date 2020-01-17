<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
	
	<div class="container-fluid hidden-print">
		
		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'], 'niveles.php') == true) ? ' class="active"' : ''; ?>><a href="index.php">Estadísticas por niveles</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'], 'asignaturas.php') == true) ? ' class="active"' : ''; ?>><a href="asignaturas.php">Estadísticas por asignaturas</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'], 'pendientes.php') == true) ? ' class="active"' : ''; ?>><a href="pendientes.php">Estadísticas por alumnos con pendientes</a></li>
			<?php if (stristr($_SERVER['SERVER_NAME'],"iesmonterroso") == true) {?>
			<li><a href="../informe_notas1.php">Modelo antiguo</a></li>
			<?php }	?>
		</ul>
	
	</div>
