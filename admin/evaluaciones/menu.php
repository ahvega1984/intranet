<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<div class="container  hidden-print">
	<?php if (acl_permiso($carg, array('1'))): ?>
	<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fa fa-cog fa-lg"></span></a>
	<?php endif; ?>

	<ul class="nav nav-tabs">
	  <li<?php echo (strstr($_SERVER['REQUEST_URI'],'index')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Sesiones de evaluación</a></li>
	  <?php if ((stristr($_SESSION['cargo'],'1') == true) || (stristr($_SESSION['cargo'],'2') == true)): ?>
	  <li<?php echo (strstr($_SERVER['REQUEST_URI'],'actas')==TRUE) ? ' class="active"' : ''; ?>><a href="actas.php">Actas de evaluación</a></li>
	  <?php endif; ?>
	</ul>
	
</div>