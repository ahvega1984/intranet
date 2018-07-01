<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<h4><span class="far fa-pie-chart fa-fw"></span> Estadísticas del día</h4>

<div class="row">

	<div class="col-xs-6 col-sm-3">
		<h5 class="text-center">
			<a href="#" data-toggle="modal" data-target="#asistencia">
				<span class="lead"><span id="stats-numfaltas"></span></span><br>
				<small class="text-uppercase text-muted">Asistencias</small>
			</a>
		</h5>
		
		<!-- MODAL ACCESOS -->
		<div id="asistencia" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title">Asistencias</h4>
					</div>
					
					<div id="stats-asistencia-modal" class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<!-- FIN MODAL ACCESOS -->
		
		
	</div><!-- /.col-sm-2 -->
		
	<div class="col-xs-6 col-sm-3">
		
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
		
		
	<div class="col-xs-6 col-sm-3">
		
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
		
		
	<div class="col-xs-6 col-sm-3">
		
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
				
</div>

<br>
