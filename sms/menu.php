<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$activo1="";
$activo2="";
$activo3="";

if (strstr($_SERVER['REQUEST_URI'],'sms/index.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'sms/index_profes.php')==TRUE) {$activo2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'sms_cpadres.php')==TRUE) {$activo3 = ' class="active" ';}

?>
<div class="container hidden-print">
	
	<?php if (acl_permiso($carg, array('1'))): ?>
	<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fas fa-cog fa-lg"></span></a>
	<?php endif; ?>

	<ul class="nav nav-tabs">
	<li <?php echo $activo1;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/sms/index.php">
		SMS a Alumnos / Padres</a></li>
	<li <?php echo $activo2;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/sms/index_profes.php">
		SMS a Profesores</a></li>
		<li <?php echo $activo3;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/sms/sms_cpadres.php">
		SMS de Faltas de Asistencia a Padres</a></li>
	</ul>
	
</div>
