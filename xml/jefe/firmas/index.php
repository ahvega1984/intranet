<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array('z', '1'));

include("../../../menu.php");
include("../menu.php");

?>

<div class="container">

	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display:inline;">Hoja de Firmas del profesorado<small> Opciones</small></h2>

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
						<p>En la selección de la semana, basta con elegir cualquier día de la semana que se quiera imprimir y 
						se obtendrá el listado de horarios desde ese día y los 5 días siguientes. Si no se elige día, cogerá el primer lunes siguiente
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>

	</div>


	<!-- SCAFFOLDING -->
	<div class="row">

		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-8 col-sm-offset-2">

			<div class="well">

				<form name="flistado" method="POST">
					<fieldset>
						<legend>Opciones</legend>

						<div class="row">
							<!-- FORMULARIO COLUMNA IZQUIERDA -->
	
							<div class="col-sm-4">

								<div class="form-group" id="datetimepicker1">
								  <label for="dia">Semana</label>
								  <div class="input-group">
								  	<input type="text" class="form-control" name="dia" id="dia" placeholder="Fecha" data-date-format="DD-MM-YYYY">
								  	<span class="input-group-addon"><span class="far fa-calendar"></span></span>
								  </div>
								</div>
							</div>

							<!-- FORMULARIO COLUMNA CENTRAL -->
							<div class="col-sm-4">
								<!-- Opcion apaisado -->
								<label for="orienta">Orientación</label>
								<div class="custom-control custom-radio custom-control-inline">
  									<input type="radio" class="custom-control-input" value="oriVertical" name="orientacion" checked>
  									<label class="custom-control-label" for="defaultUnchecked">Vertical</label>
								</div>
								<!-- Opcion vertical -->
								<div class="custom-control custom-radio custom-control-inline">
  									<input type="radio" class="custom-control-input" value="oriHorizontal" name="orientacion">
  									<label class="custom-control-label" for="defaultChecked">Apaisado</label>
								</div>		
							</div>	

							<!-- FORMULARIO COLUMNA DERECHA -->
							<div class="col-sm-4">
								<!-- Opcion diurno -->
								<label for="turno">Turno</label>
								<div class="custom-control custom-radio custom-control-inline">
  									<input type="radio" class="custom-control-input" value="diurno" name="turno" checked>
  									<label class="custom-control-label" for="defaultUnchecked">Mañana</label>
								</div>
								<!-- Opcion nocturno -->
								<div class="custom-control custom-radio custom-control-inline">
  									<input type="radio" class="custom-control-input" value="nocturno" name="turno">
  									<label class="custom-control-label" for="defaultChecked">Tarde</label>
								</div>		
							</div>	
						</div>
						<button type="submit" class="btn btn-primary" name="submit2" onclick="generarPDF();return true;">Generar</button>
				  </fieldset>
				</form>
			</div><!-- /.well -->
		</div><!-- /.col-sm-6 -->
	</div><!-- /.row -->
</div><!-- /.container -->

<?php include("../../../pie.php"); ?>

<script language="javascript">
	$(function ()
	{
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		})
	});

	function generarPDF() {
		var orientacion = document.flistado.orientacion.value;
		var turno = document.flistado.turno.value;

		if (orientacion == 'oriHorizontal' && turno == 'diurno')
	    	document.flistado.action = 'horasM_Apaisado.php';
		else if (orientacion == 'oriVertical' && turno == 'diurno')
			document.flistado.action = 'horasM_Vertical.php';
		else if (orientacion == 'oriHorizontal' && turno == 'nocturno')
			document.flistado.action = 'horasT_Apaisado.php';
		else
			document.flistado.action = 'horasT_Vertical.php';
	}
</script>

