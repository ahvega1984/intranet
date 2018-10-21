<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

    <footer class="hidden-print">
			<div class="container-fluid">
				<hr>
				<p class="pull-left text-muted">&copy; <?php echo date('Y'); ?>, I.E.S. Monterroso</p>

				<ul class="pull-right list-inline">
					<li>Versión <?php echo INTRANET_VERSION; ?></li>
					<li><a href="//<?php echo $config['dominio']; ?>/intranet/aviso-legal/">Aviso legal</a></li>
					<li><a href="//<?php echo $config['dominio']; ?>/intranet/LICENSE.md" target="_blank">Licencia</a></li>
					<li><a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a></li>
				</ul>
			</div>

      </div>

			<br>
		</footer>

    <!-- MODAL SESIÓN-->
    <div class="modal fade" id="sesionAviso" tabindex="-1" role="dialog">
    	<div class="modal-dialog">
    		<div class="modal-content">
    		  <div class="modal-header">
    		    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    		    <h4 class="modal-title">Cierre automático de sesión</h4>
    		  </div>
    		  <div class="modal-body">
    		    <p>La sesión se cerrará automáticamente dentro de <strong id="sesionAvisoTiempo">5 minutos</strong>. Si está rellenando algún formulario, guarde inmediatamente los
    				 cambios antes de que finalice el tiempo. Esta acción detendrá el cierre automático de sesión y podrá continuar.</p>
    		  </div>
    		  <div class="modal-footer">
    		    <button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
    		  </div>
    		</div><!-- /.modal-content -->
    	</div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- FIN MODAL SESIÓN -->

    <!-- BOOTSTRAP JS CORE -->
    <script src="//<?php echo $config['dominio'];?>/intranet/js/jquery-2.1.1.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/bootstrap.min.js"></script>

    <!-- PLUGINS JS -->
    <script src="//<?php echo $config['dominio'];?>/intranet/js/bootbox.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/validator/validator.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/summernote/summernote.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/summernote/lang/summernote-es-ES.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/summernote/plugin/summernote-cleaner.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/datetimepicker/moment.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/datetimepicker/moment-es.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/datetimepicker/bootstrap-datetimepicker.js"></script>
    <?php if(isset($PLUGIN_DATATABLES) && $PLUGIN_DATATABLES): ?>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/datatables/jquery.dataTables.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/datatables/dataTables.bootstrap.js"></script>
    <?php endif; ?>
    <?php if(isset($PLUGIN_COLORPICKER) && $PLUGIN_COLORPICKER): ?>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <?php endif; ?>
    <script src="//<?php echo $config['dominio'];?>/intranet/js/ajax_alumnos.js"></script>


	<script>
	$(function () {
	  var nua = navigator.userAgent
	  var isAndroid = (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1)
	  if (isAndroid) {
	    $('select.form-control').removeClass('form-control').css('width', '100%')
	  }

	  $("#debug_button").click(function() {
	    $('#debug').slideToggle();
	  });

	  $("#toggleMenu").click(function() {
	    $('#accordion').toggleClass("hidden-xs");
	  });

	})
	</script>

	<script>
	$("[data-bs=tooltip]").tooltip({
		container: 'body'
	});

	$(document).on("click", "a[data-bb]", function(e) {
	    e.preventDefault();
	    var type = $(this).data("bb");
			var link = $(this).attr("href");

			if (type == 'confirm-delete') {
				bootbox.setDefaults({
				  locale: "es",
				  show: true,
				  backdrop: true,
				  closeButton: true,
				  animate: true,
				  title: "Confirmación para eliminar",
				});

				bootbox.confirm("Esta acción eliminará permanentemente el elemento seleccionado ¿Seguro que desea continuar?", function(result) {
				    if (result) {
				    	document.location.href = link;
				    }
				});
			}
	});
	</script>

	<script>
  var finSesion = new Date().getTime() + (<?php echo ini_get("session.cookie_lifetime"); ?> * 1000);
  var formatoSegundos = 1000;
  var formatoMinutos = formatoSegundos * 60;
  var formatoHoras = formatoMinutos * 60;
  var formatoDias = formatoHoras * 24;
  var mostrarAviso = 0;
  var tiempo;

  function tiempoSesion() {
      var comienzoSesion = new Date();
      var tiempoActual = finSesion - comienzoSesion;

      var dias = Math.floor(tiempoActual / formatoDias);
      var horas = Math.floor((tiempoActual % formatoDias) / formatoHoras);
      var minutos = Math.floor((tiempoActual % formatoHoras) / formatoMinutos);
      var segundos = Math.floor((tiempoActual % formatoMinutos) / formatoSegundos);

      $("#sesionMenuTiempo").html(((horas < 10) ? '0' : '') + horas + ':' + ((minutos < 10) ? '0' : '') + minutos + ':'  + ((segundos < 10) ? '0' : '') + segundos);
      $("#sesionAvisoTiempo").html((minutos < 1) ? segundos + ' segundos' : minutos + ' minutos');

      if (mostrarAviso == 0 && (dias == 0 && horas == 0 && minutos < 5)) {
        mostrarAviso = 1;
        $("#sesionAviso").modal('show');
        $("#sesionMenuTiempo").removeClass('hidden');
      }

      if (tiempoActual <= 0) {
          clearInterval(tiempo);
          document.location.href = 'http://<?php echo $config['dominio']; ?>/intranet/logout.php';
          return;
      }
  }

  tiempo = setInterval(tiempoSesion, 1000);
	</script>
