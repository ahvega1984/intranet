<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
    
    <footer class="hidden-print">
			<div class="container-fluid">
				<hr>
				<p class="pull-left text-muted">&copy; <?php echo date('Y'); ?>, I.E.S. Monterroso</p>

				<ul class="pull-right list-inline">
					<li>Versión <?php echo INTRANET_VERSION; ?></li>
					<li><a href="//<?php echo $config['dominio']; ?>/intranet/LICENSE.md" target="_blank">Licencia</a></li>
					<li><a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a></li>
				</ul>
			</div>

			<br>
		</footer>
    
    <?php if(isset($_SESSION['user_admin'])): ?>
    <div class="hidden-print" style="z-index: 1000; clear: both; position: fixed; bottom: -10px; width: 100%; padding: 15px 20px; padding-bottom: 0; background-color: rgba(0,0,0,.8); color: #fff; font-size: 90%;">
    	<a href="#" id="debug_button" style="position: absolute; margin-top: -40px; padding: 5px 10px; background-color: rgba(0,0,0,.8); color: #fff; font-size: 90%; text-transform: uppercase;"><span class="fa fa-user-plus fa-fw"></span> Cambiar perfil</a>
    	<div id="debug" class="row" style="display: none;">
    			<form method="post" class="col-sm-4" action="<?php echo $_SERVER['REQUEST_URI']; ?>" style="height: 50px;">
							<select class="form-control" id="view_as_user" name="view_as_user" onchange="submit()" style="height: 30px; font-size: 90%;">
								<?php $result = mysqli_query($db_con, "SELECT nombre, idea FROM departamentos ORDER BY nombre ASC"); ?>
								<?php while($row = mysqli_fetch_assoc($result)): ?>
								<option value="<?php echo $row['nombre']; ?>"<?php echo ($row['nombre'] == $_SESSION['profi']) ? ' selected' : ''; ?>><?php echo $row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
    			</form>
    		</div>   		
    	</div>
    	
    </div>
    <?php endif; ?>

    <!-- MODAL SESIÓN-->
	<div class="modal fade" id="session_expired" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    <h4 class="modal-title">Inactividad de la cuenta</h4>
			  </div>
			  <div class="modal-body">
			    <p>Hemos detectado inactividad en su cuenta. Por seguridad, la sesión se cerrará automáticamente dentro de 
			    	<strong>3 minutos</strong>. Realice alguna actividad en la aplicación para cancelar esta acción.</p>
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
    <script src="//<?php echo $config['dominio'];?>/intranet/js/summernote/summernote-es-ES.js"></script>
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
	$(document).ready(function() {
		var expired_time = (<?php echo ini_get("session.gc_maxlifetime"); ?> * 60000) - 180000;
		setTimeout(function() {
			$("#session_expired").modal('show');
		}, expired_time);
	});
	</script>
