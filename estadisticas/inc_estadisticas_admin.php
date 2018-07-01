<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<h4><span class="fas fa-chart-pie fa-fw"></span> Estadísticas del día</h4>

<div class="row">

	<div class="col-xs-6 col-sm-20">
		<h5 class="text-center">
			<a href="#" data-toggle="modal" data-target="#accesos">
				<span class="lead"><span id="stats-numprof"></span> <span class="text-muted">(<span id="stats-totalprof"></span>)</span></span><br>
				<small class="text-uppercase text-muted">Profesores sin entrar</small>
			</a>
		</h5>

		<!-- MODAL ACCESOS -->
		<div id="accesos" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title">Profesores que no han accedido hoy</h4>
					</div>

					<div id="stats-accesos-modal" class="modal-body">
					</div>

					<div class="modal-footer">
						<a href="./xml/jefe/informes/accesos.php" class="btn btn-info">Ver accesos</a>
					</div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL ACCESOS -->


	</div><!-- /.col-sm-2 -->

	<div class="col-xs-6 col-sm-20">

		<h5 class="text-center">
			<a href="#" data-toggle="modal" data-target="#fechoria">
				<span class="lead"><span id="stats-convivencia"></span></span><br>
				<small class="text-uppercase text-muted">Problemas convivencia</small>
			</a>
		</h5>

		<!-- MODAL FECHORIAS -->
		<div id="fechoria" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title">Problemas de convivencia</h4>
					</div>

					<div id="stats-convivencia-modal" class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL FECHORIAS -->

	</div><!-- /.col-sm-2 -->


	<div class="col-xs-6 col-sm-20">

		<h5 class="text-center">
			<a href="#" data-toggle="modal" data-target="#expulsiones">
				<span class="lead"><span id="stats-expulsados"></span> / <span id="stats-reingresos"></span></span><br>
				<small class="text-uppercase text-muted">Expulsiones Reingresos</small>
			</a>
		</h5>

		<!-- MODAL EXPULSIONES Y REINGRESOS -->
		<div id="expulsiones" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title">Expulsiones y reingresos</h4>
					</div>

					<div class="modal-body">
						<h4 class="text-info">Alumnos expulsados</h4>

						<div id="stats-expulsados-modal"></div>

						<hr>

						<h4 class="text-info">Reincorporaciones</h4>

						<div id="stats-reincorporaciones-modal"></div>

					</div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL EXPULSIONES Y REINGRESOS -->

	</div><!-- /.col-sm-2 -->


	<div class="col-xs-6 col-sm-20">

		<h5 class="text-center">
			<a href="#" data-toggle="modal" data-target="#visitas">
				<span class="lead"><span id="stats-visitas"></span></span><br>
				<small class="text-uppercase text-muted">Visitas de padres</small>
			</a>
		</h5>

		<!-- MODAL VISITAS PADRES -->
		<div id="visitas" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title">Visitas de padres</h4>
					</div>

					<div id="stats-visitas-modal" class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL VISITAS PADRES -->

	</div><!-- /.col-sm-2 -->


	<div class="col-xs-6 col-sm-20">

		<h5 class="text-center">
			<a href="#" data-toggle="modal" data-target="#noleidos">
				<span class="lead"><span id="stats-mensajes"></span></span><br>
				<small class="text-uppercase text-muted">+25 Mensajes sin leer</small>
			</a>
		</h5>

		<!-- MODAL noleidos -->
		<div id="noleidos" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title">Profesores con más de 25 mensajes sin leer</h4>
					</div>

					<div id="stats-mensajes-modal" class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL ACCESOS -->

	</div><!-- /.col-sm-2 -->

</div>

<br>
