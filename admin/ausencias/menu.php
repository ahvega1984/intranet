<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

	$activo1="";
	$activo2="";
	$activo3="";

	if (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) {$activo1 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'diario.php')==TRUE) {$activo2 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'ausencias_profes.php')==TRUE) {$activo3 = ' class="active" ';}
?>
	<div class="container hidden-print">
		<ul class="nav nav-tabs">
			<li <?php echo $activo1;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/ausencias/index.php">Registrar ausencia</a></li>
			<li <?php echo $activo2;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/ausencias/diario.php">Profesores ausentes</a></li>
			<li <?php echo $activo3;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/ausencias/ausencias_profes.php">Informe sobre Ausencias</a></li>
		</ul>
	</div>

