<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$profesor = $_SESSION ['profi'];
if($_POST['token']) $token = $_POST['token'];
if(!isset($token)) $token = time(); 


if (isset($_GET['id'])) $id = $_GET['id'];

// ENVIO DEL FORMULARIO
if (isset($_POST['enviar'])) {
	
	// VARIABLES DEL FORMULARIO
	$titulo = $_POST['titulo'];
	$contenido = addslashes($_POST['contenido']);
	$caracteres_contenido = strlen(strip_tags($_POST['contenido']));
	$autor = $_POST['autor'];
	$fechapub = $_POST['fechapub'];
	$categoria = $_POST['categoria'];
	$ndias = $_POST['ndias'];
	$intranet = $_POST['intranet'];
	$principal = $_POST['principal'];
	$permanente = $_POST['permanente'];
	$pagina = $intranet.$principal.$permanente;
	if (empty($titulo) || empty($contenido) || empty($fechapub)) {
		$msg_error = "Todos los campos del formulario son obligatorios.";
	}
	elseif ($caracteres_contenido < 150) {
		$msg_error = "Debe introducir al menos un párrafo con 150 caracteres.";
	}
	else {
		
			if ($ndias == 0) $fechafin = '';
			else $fechafin = date("Y-m-d", strtotime("$fechapub +$ndias days"));
			
			if(empty($intranet) && empty($principal)) {
				$msg_error = "Debe indicar dónde desea publicar la noticia.";
			}
			else {
				// COMPROBAMOS SI INSERTAMOS O ACTUALIZAMOS
				if(isset($id)) {
					// ACTUALIZAMOS LA NOTICIA
					$result = mysqli_query($db_con, "UPDATE noticias SET titulo='$titulo', contenido='$contenido', autor='$autor', fechapub='$fechapub', fechafin='$fechafin', categoria='$categoria', pagina='$pagina' WHERE id = $id LIMIT 1");
					if (!$result) $msg_error = "No se ha podido actualizar la noticia. Error: ".mysqli_error($db_con);
					else $msg_success = "La noticia ha sido actualizada correctamente.";
				}
				else {
					// INSERTAMOS LA NOTICIA
					$result = mysqli_query($db_con, "INSERT INTO noticias (titulo, contenido, autor, fechapub, fechafin, categoria, pagina) VALUES ('$titulo','$contenido','$autor','$fechapub','$fechafin','$categoria','$pagina')");
					if (!$result) $msg_error = "No se ha podido publicar la noticia. Error: ".mysqli_error($db_con);
					else $msg_success = "La noticia ha sido publicada correctamente.";
				}
			}	
		}	
	}
	

// OBTENEMOS LOS DATOS SI SE OBTIENE EL ID DE LA NOTICIA
if (isset($id) && (int) $id) {
	
	$result = mysqli_query($db_con, "SELECT titulo, contenido, autor, fechapub, DATEDIFF(fechafin, fechapub) AS ndias, categoria, pagina FROM noticias WHERE id = $id LIMIT 1");
	if (!mysqli_num_rows($result)) {
		$msg_error = "La noticia que intenta editar no existe.";
		unset($id);
	}
	else {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (stristr($_SESSION['cargo'],'1') == TRUE || $row['autor'] == $_SESSION['profi']) {
			$titulo = ((strstr($row['titulo'], ' [Actualizado]') == true) || (stristr($row['titulo'], ' (Actualizado)') == true)) ? $row['titulo'] : $row['titulo'].' [Actualizado]';
			$contenido = $row['contenido'];
			$autor = $row['autor'];
			$fechapub = $row['fechapub'];
			$categoria = $row['categoria'];
			$ndias = $row['ndias'];
			$pagina = $row['pagina'];
			
			// OBTENEMOS LOS LUGARES DONDE SE HA PUBLICADO LA NOTICIA
			if (strstr($pagina, '1') == true) $intranet = 1;
			if (strstr($pagina, '2') == true) $principal = 2;
			if (strstr($pagina, '3') == true) $permanente = 3;
		}
		else {
			$msg_error = "No eres el autor o no tienes privilegios administrativos para editar esta noticia.";
			unset($id);
		}
		
		mysqli_free_result($result);
	}
	
}


include ("../../menu.php");
include ("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Noticias <small>Redactar nueva noticia</small></h2>
		</div>
		
		<!-- MENSAJES -->
		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<?php if (isset($msg_success)): ?>
		<div class="alert alert-success">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			
			<form method="post" action="">
			
				<!-- COLUMNA IZQUIERDA -->
				<div class="col-sm-8">
					
					<div class="well">
						
						<fieldset>
							<legend>Redactar nueva noticia</legend>
							
							<input type="hidden" name="token" value="<?php echo $token; ?>">
							
								<div class="form-group">
									<label for="titulo">Título</label>
									<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título de la noticia" value="<?php echo (isset($titulo) && $titulo) ? $titulo : ''; ?>" maxlength="120" autofocus>
								</div>
								
								<div class="form-group">
									<label for="contenido" class="sr-only">Contenido</label>
									<textarea class="form-control" id="contenido" name="contenido" rows="10" maxlength="3000"><?php echo (isset($contenido) && $contenido) ? stripslashes($contenido) : ''; ?></textarea>
								</div>
								
								<button type="submit" class="btn btn-primary" name="enviar"><?php echo (isset($id) && $id) ? 'Actualizar' : 'Publicar'; ?></button>
								<button type="reset" class="btn btn-default">Cancelar</button>
							
						</fieldset>
						
					</div>
					
				</div><!-- /.col-sm-8 -->
				
				
				<!-- COLUMNA DERECHA -->
				<div class="col-sm-4">
					
					<div class="well">
						
						<fieldset>
							<legend>Opciones de publicación</legend>
							
							
							<div class="form-group">
								<label for="autor">Autor</label>
								<input type="text" class="form-control" id="autor" name="autor" value="<?php echo (isset($autor) && $autor) ? $autor : $_SESSION['profi']; ?>" readonly>
							</div>
							
							<div class="form-group" id="datetimepicker1">
								<label for="fechapub">Fecha de publicación</label>
								<div class="input-group">
									<input type="text" class="form-control" id="fechapub" name="fechapub" value="<?php echo (isset($fechapub) && $fecha_pub) ? $fechapub : date('Y-m-d H:i:s'); ?>" data-date-format="YYYY-MM-DD HH:mm:ss">
									<span class="input-group-addon"><span class="far fa-calendar"></span></span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="clase">Categoría</label>
								<select class="form-control" id="categoria" name="categoria">
								<?php foreach ($cat as $item_categoria): ?>
								<?php if(stristr($_SESSION['cargo'],'1') == FALSE and ($item_categoria=="Dirección del Centro" or $item_categoria=="Jefatura de Estudios" or $item_categoria=="Secretaría")) {} else {?>
									<option value="<?php echo $item_categoria; ?>" <?php echo (isset($item_categoria) && $item_categoria == $categoria) ? 'selected' : ''; ?>><?php echo $item_categoria; ?></option>
									<?php } ?>
								<?php endforeach; ?>
								</select>
							</div>
							
							<?php if (stristr($_SESSION['cargo'],'1') == TRUE): ?>
							
							<div class="form-group">
								<label for="ndias">Noticia destacada (en días)</label>
								<div class="row">
									<div class="col-sm-4">
										<input type="number" class="form-control" id="ndias" name="ndias" value="<?php echo (isset($ndias) && $ndias) ? $ndias : '0'; ?>" min="0" max="31" maxlength="2">
									</div>
								</div>
							</div>
							

							<?php 
							if ($config['noticias']['web_centro']==1) {
							?>
							<label>Publicar en...</label>
							
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="intranet" value="1" <?php echo (isset($intranet) && $intranet) ? 'checked' : ''; ?>> Intranet
									</label>
								</div>
							</div>
							
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="principal" value="2" <?php echo (isset($principal) && $principal) ? 'checked' : ''; ?>> Página externa
									</label>
								</div>
							</div>
								<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="permanente" value="3" <?php echo (isset($permanente) && $permanente) ? 'checked' : ''; ?>> Cómo se hace...
									</label>
								</div>
							</div>

							
							<?php
							}
							?>
							
							<?php else: ?>
							
							<input type="hidden" name="intranet" value="1">
							
							<?php endif; ?>
							
						</fieldset>
						
					</div>
					
				</div><!-- /.col-sm-4 -->
			
			</form>
			
					
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>
	
	<script>
	$(document).ready(function() {
		
		// EDITOR DE TEXTO
		$('#contenido').summernote({
			height: 400,
			lang: 'es-ES',
			toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic', 'underline', 'clear']],
				['font', ['strikethrough', 'superscript', 'subscript']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['media', ['link', 'picture', 'video']],
				['code', ['codeview']]
			],
			onChange: function(content) {
				var sHTML = $('#content').code();
		    	localStorage['summernote-<?php echo $token; ?>'] = sHTML;
			}
		});
		
		if (localStorage['summernote-<?php echo $token; ?>']) {
			$('#content').code(localStorage['summernote-<?php echo $token; ?>']);
		}
		
	});
	
	// DATETIMEPICKER
	$(function () {
	    $('#datetimepicker1').datetimepicker({
	    	language: 'es',
	    	useSeconds: true,
	    });
	});
	</script>

</body>
</html>
