<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
<div class="container hidden-print">

	<?php if (acl_permiso($carg, array('1'))): ?>
	<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fas fa-cog fa-lg"></span></a>
	<?php endif; ?>

	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'sms/alumnado.php') == TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/sms/alumnado.php">SMS a familia y alumnado</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'sms/profesorado.php') == TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/sms/profesorado.php">SMS a profesorado</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'sms/sms_cpadres.php') == TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/sms/sms_cpadres.php">SMS de Faltas de Asistencia a familias</a></li>
	</ul>

</div>
