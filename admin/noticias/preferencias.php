<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {
	
	$prefWeb	= limpiar_string($_POST['prefWeb']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		// Categorías
			if (isset($_POST['cat_0'])) {
				$array_cat="";
				for ($z=0; $z<10 ; $z++) {
					if (strlen($_POST['cat_'.$z.''])>0) {
						$array_cat.= '"'.$_POST['cat_'.$z.''].'",';
					}					
				}
				$array_cat=substr($array_cat, 0, -1);
			}

		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE NOTICIAS\r\n");
		fwrite($file, "\$config['noticias']['web_centro']\t= $prefWeb;\r\n");
		fwrite($file, "\$cat = array($array_cat);\r\n");
		
		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");
		
		fclose($file);
		
		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}
	
}

if (file_exists('config.php')) {
	include('config.php');
}


include("../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Noticias <small>Preferencias</small></h2>
	</div>
	
	<!-- MENSAJES -->
	<?php if (isset($msg_error)): ?>
	<div class="alert alert-danger alert-fadeout">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>
	
	<?php if (isset($msg_success)): ?>
	<div class="alert alert-success alert-fadeout">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>


	<div class="row">

		<div class="col-sm-12">
			
			<form class="form-horizontal" method="post" action="preferencias.php">
				
				<div class="well">
					
					<fieldset>
						<h3>Integración en la Web pública del Centro</h3>
						<br>
						<div class="form-group">
							<label for="prefWeb" class="col-sm-3 control-label">Opción de publicar noticias en la Web del Centro (<span class="text-info">*</span>)</label>
							<div class="col-sm-3">
								<select class="form-control" id="prefWeb" name="prefWeb">
									<option value="0" <?php echo (isset($config['noticias']['web_centro']) && $config['noticias']['web_centro'] == 0) ? 'selected' : ''; ?>>Deshabilitado</option>
									<option value="1" <?php echo (isset($config['noticias']['web_centro']) && $config['noticias']['web_centro'] == 1) ? 'selected' : ''; ?>>Habilitado</option>
								</select>
							</div>
							
						</div>
						<p class="help-block"><span class="text-info">*</span> Si el Centro ha instalado la <a href"https://github.com/IESMonterroso/pagina_centro" target="_blank">Página del Centro</a> integrada en la Intranet, tenemos la opción de <br>publicar noticias en ambas páginas (Intranet y Web del Centro) o bien sólo en una de ellas.</p>
						<hr>
						<h3>Categorías para el autor de la publicación</h3>
						<br>
						<div class="form-group">
							<label for="cat_0" class="col-sm-3 control-label">Categoría 1</label>
							<div class="input-group col-sm-5">
							<input name="cat_0" type="text"
								class="form-control" value="<?php echo (isset($cat[0])) ? $cat[0] : 'Dirección del Centro'; ?>" id="cat_0" readonly> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_1" class="col-sm-3 control-label">Categoría 2</label>
							<div class="input-group col-sm-5">
							<input name="cat_1" type="text"
								class="form-control" value="<?php echo (isset($cat[1])) ? $cat[1] : 'Jefatura de Estudios'; ?>" id="cat_1" readonly> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_2" class="col-sm-3 control-label">Categoría 3</label>
							<div class="input-group col-sm-5">
							<input name="cat_2" type="text"
								class="form-control" value="<?php echo (isset($cat[2])) ? $cat[2] : 'Secretaría'; ?>" id="cat_2" readonly> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_3" class="col-sm-3 control-label">Categoría 4</label>
							<div class="input-group col-sm-5">
							<input name="cat_3" type="text"
								class="form-control" value="<?php echo (isset($cat[3])) ? $cat[3] : 'Actividades Extraescolares'; ?>" id="cat_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_4" class="col-sm-3 control-label">Categoría 5</label>
							<div class="input-group col-sm-5">
							<input name="cat_4" type="text"
								class="form-control" value="<?php echo (isset($cat[4])) ? $cat[4] : 'Proyecto Escuela de Paz'; ?>" id="cat_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_5" class="col-sm-3 control-label">Categoría 6</label>
							<div class="input-group col-sm-5">
							<input name="cat_5" type="text"
								class="form-control" value="<?php echo (isset($cat[5])) ? $cat[5] : 'Centro Bilingue'; ?>" id="cat_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_6" class="col-sm-3 control-label">Categoría 7</label>
							<div class="input-group col-sm-5">
							<input name="cat_6" type="text"
								class="form-control" value="<?php echo (isset($cat[6])) ? $cat[6] : 'Centro TIC'; ?>" id="cat_6"> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_7" class="col-sm-3 control-label">Categoría 8</label>
							<div class="input-group col-sm-5">
							<input name="cat_7" type="text"
								class="form-control" value="<?php echo (isset($cat[7])) ? $cat[7] : 'Ciclos Formativos'; ?>" id="cat_7"> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_8" class="col-sm-3 control-label">Categoría 9</label>
							<div class="input-group col-sm-5">
							<input name="cat_8" type="text"
								class="form-control" value="<?php echo (isset($cat[8])) ? $cat[8] : ''; ?>" id="cat_8"> 
							</div>
						</div>
						<div class="form-group">
							<label for="cat_9" class="col-sm-3 control-label">Categoría 10</label>
							<div class="input-group col-sm-5">
							<input name="cat_9" type="text"
								class="form-control" value="<?php echo (isset($cat[9])) ? $cat[9] : ''; ?>" id="cat_9"> 
							</div>
						</div>
							
						
					</fieldset>
					
				</div>
				
				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<?php if (isset($_GET['esAdmin']) && $_GET['esAdmin'] == 1): ?>
				<a href="../../../xml/index.php" class="btn btn-default">Volver</a>
				<?php else: ?>
				<a href="index.php" class="btn btn-default">Volver</a>
				<?php endif; ?>
			
			</form>
		
		</table>

		</div>

	</div>

</div>

<?php include("../../pie.php"); ?>

</body>
</html>
