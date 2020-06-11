<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<div class="container hidden-print">

	<!-- Button trigger modal -->
	<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
		<span class="fas fa-question fa-lg"></span>
	</a>

	<!-- Modal -->
	<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
					<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
				</div>
				<div class="modal-body">
					<p>Ayuda</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/index.php">Mis informes</a></li>
		<?php if (stristr($_SESSION['cargo'], "1") OR stristr($_SESSION['cargo'], "2")) { ?>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'admin.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/extraordinaria/admin.php">Administrar informes</a></li>
		<?php } ?>
	</ul>

</div>