<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<div class="container">
	
	<?php if (acl_permiso($carg, array('1'))): ?>
	<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fa fa-cog fa-lg"></span></a>
	<?php endif; ?>
	
	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Lista de pedidos</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'pedido.php')==TRUE) ? ' class="active"' : ''; ?>><a href="pedido.php">Nuevo pedido</a></li>
	</ul>

</div>