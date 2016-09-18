<?php
require('bootstrap.php');

if ($_GET['resetea_mensaje']==1) {
	mysqli_query($db_con,"update mens_profes set recibidoprofe='1' where profesor='".$_GET['idea_mensaje']."'");
}

include("menu.php");
?>	

	<div class="container-fluid" style="padding-top: 15px;">
		
		<div class="row">
			
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-md-3">
				
				<div id="bs-tour-menulateral">
				<?php include("menu_lateral.php"); ?>
				</div>
				
				<div id="bs-tour-ausencias">
				<?php include("admin/ausencias/widget_ausencias.php"); ?>
				</div>
				
				<div id="bs-tour-destacadas" class="hidden-xs">
				<?php include ("admin/noticias/widget_destacadas.php"); ?>
				</div>
	
			</div><!-- /.col-md-3 -->
			
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-md-5">
				
				<?php 
				if (acl_permiso($carg, array('2'))) {
					include("admin/tutoria/inc_pendientes.php");
				}
				?>
				<div id="bs-tour-pendientes">
				<?php include ("pendientes.php"); ?>
				</div>
				
				<?php if (acl_permiso($carg, array('1'))): ?>
				<h4><span class="fa fa-pie-chart fa-fw"></span> Estadísticas del día</h4>
				
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
				<?php endif; ?>
				
		        <div class="bs-module">
		        <?php include("admin/noticias/widget_noticias.php"); ?>
		        </div>
		        
		        <br>
				
			</div><!-- /.col-md-5 -->
			
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-md-4">
				
				<div id="bs-tour-buscar">
				<?php include("buscar.php"); ?>
				</div>
				
				<br><br>
				
				<div id="bs-tour-calendario">
				<?php
				define('MOD_CALENDARIO', 1);
				include("calendario/widget_calendario.php");
				?>
				</div>
				
				<br><br>
				
				<?php if($config['mod_horarios'] and ($n_curso > 0)){ ?>
				<div id="bs-tour-horario">
				<h4><span class="fa fa-clock-o fa-fw"></span> Horario</h4>	
				<?php include("horario.php"); ?>
				</div>
				<?php } elseif($dpto!=="Admin" and $dpto!=="Administracion" and $dpto!=="Conserjeria") { ?>
				<h4><span class="fa fa-clock-o fa-fw"></span> Horario</h4>	
				<a class="btn btn-sm btn-default btn-block" href="xml/jefe/horarios/index.php" style="margin-top:18px;">Crear/Modificar horario</a>
				<?php } ?>
				
			</div><!-- /.col-md-4 -->
			
		</div><!-- /.row -->
		
	</div><!-- /.container-fluid -->

	<?php include("pie.php"); ?>
	
	<?php if (acl_permiso($carg, array('1'))): ?>
	<script src="//<?php echo $config['dominio'];?>/intranet/js/estadisticas.js"></script>
	<?php endif; ?>
	
	<script>
	function notificar_mensajes(nmens) {
		if(nmens > 0) {
			$('#icono_notificacion_mensajes').addClass('text-warning');
		}
		else {
			$('#icono_notificacion_mensajes').removeClass('text-warning');
		}	
	}
	
	<?php if (isset($mensajes_pendientes) && $mensajes_pendientes): ?>
	var mensajes_familias = $("#lista_mensajes_familias li").size();
	var mensajes_profesores = $("#lista_mensajes li").size();
	var mensajes_pendientes = <?php echo $mensajes_pendientes; ?>;
	notificar_mensajes(mensajes_pendientes);
	<?php endif; ?>
	
	$('.modalmens').on('hidden.bs.modal', function (event) {
		var idp = $(this).data('verifica');
	  var noleido = $(this).find('#noleido-' + idp).attr('aria-pressed');
	  
	  // OJO: true o false se pasa como cadena de texto, no como binario
	  if (noleido == 'false') {
	  	
		  $.post( "./admin/mensajes/post_verifica.php", { "idp" : idp }, null, "json" )
		      .done(function( data, textStatus, jqXHR ) {
		          if ( data.status ) {
		              if (mensajes_profesores < 2) {
		              	$('#alert_mensajes').slideUp();
		              }
		              else {
		              	$('#mensaje_link_' + idp).slideUp();
		              }
		              $('#menu_mensaje_' + idp + ' div').removeClass('text-warning');
		              mensajes_profesores--;
		              mensajes_pendientes--;
		              notificar_mensajes(mensajes_pendientes);
		          }
		  });
		  
		 
		}
		
	});
	
	$('.modalmensfamilia').on('hidden.bs.modal', function (event) {
		var idf = $(this).data("verifica-familia");
	  
	  $.post( "./admin/mensajes/post_verifica.php", { "idf" : idf }, null, "json" )
	      .done(function( data, textStatus, jqXHR ) {
	          if ( data.status ) {
	              if (mensajes_familias < 2 ) {
	              	$('#alert_mensajes_familias').slideUp();
	              }
	              else {
	              	$('#mensaje_link_familia_' + idf).slideUp();
	              }
	              mensajes_familias--;
	              mensajes_pendientes--;
	              notificar_mensajes(mensajes_pendientes);
	          }
	  });
	});
	</script>
	
	<?php if(isset($_GET['tour']) && $_GET['tour']): ?>
	<script src="//<?php echo $config['dominio'];?>/intranet/js/bootstrap-tour/bootstrap-tour.min.js"></script>
	<script src="//<?php echo $config['dominio'];?>/intranet/js/bootstrap-tour/intranet-tour.js"></script>
	<?php endif; ?>

</body>
</html>
