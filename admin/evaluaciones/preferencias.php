<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {
	
	$prefActaEso	= limpiar_string($_POST['prefActaEso']);
    $prefActaBach	= limpiar_string($_POST['prefActaBach']);
    $prefActaFP	    = limpiar_string($_POST['prefActaFP']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE SESIONES DE EVALUACIÓN\r\n");
		fwrite($file, "\$config['evaluaciones']['acta_eso']\t= \"$prefActaEso\";\r\n");
        fwrite($file, "\$config['evaluaciones']['acta_bach']\t= \"$prefActaBach\";\r\n");
        fwrite($file, "\$config['evaluaciones']['acta_fp']\t= \"$prefActaFP\";\r\n");
		
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
		<h2>Actas de evaluación <small>Preferencias</small></h2>
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
			
			<form method="post" action="preferencias.php">
				
				<div class="well">
					
					<fieldset>
						<legend>Preferencias</legend>
						
						<div class="form-group">
							<label for="prefActaEso" class="control-label">Modelo de acta para evaluaciones de ESO</label>
                                <textarea class="form-control" id="prefActaEso" name="prefActaEso" rows="20"><?php echo (isset($config['evaluaciones']['acta_eso'])) ? $config['evaluaciones']['acta_eso'] : '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active">Rendimiento global</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active">Actitud general ante el estudio</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active">Actitud ante las normas y la convivencia</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active">Asistencia a clase</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos sobre el proceso individual</h4><p>2.1.- Necesidades educativas</p><table class="table table-bordered"><tbody><tr><td width="30%" class="active">Refuerzo educativo</td><td width="70%"><br></td></tr><tr><td class="active">A.C.I</td><td><br></td></tr><tr><td class="active">Propuestas entrada en el PMAR 2º / PMAR 3º / FP Básica</td><td><br></td></tr><tr><td class="active">Programa de enriquecimiento</td><td><br></td></tr><tr><td class="active">A tener en cuenta por el Depto. de Orientación</td><td><br></td></tr></tbody></table><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center;"><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>5.- Acuerdos tomados por el equipo docente:</h4><p><br></p><p><br></p><p><br></p><p><br></p><h4>Profesores asistentes</h4><table class="table table-bordered"><thead><tr><th>Nombre y apellidos</th><th>Firma</th><th>Nombre y apellidos</th><th>Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>Profesores ausentes</h4><table class="table table-bordered"><thead><tr><th>Nombre y apellidos</th><th>Firma</th><th>Nombre y apellidos</th><th>Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>'; ?></textarea>
                        </div>
                        
                        <div class="form-group">
							<label for="prefActaEso" class="control-label">Modelo de acta para evaluaciones de Bachillerato</label>
                                <textarea class="form-control" id="prefActaEso" name="prefActaEso" rows="20"><?php echo (isset($config['evaluaciones']['acta_bach'])) ? $config['evaluaciones']['acta_bach'] : '<h4>1.- Acuerdos o consideraciones sobre el proceso de evaluación final individual:</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><h4>2.- Alumnado que se considera no reúne perfil de hacer estudios de Bachillerato</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><h4>3.- Alumnado excelente a felicitar</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><span style="color: inherit; font-size: 19px;">Profesores asistentes</span><br></p><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>Profesores ausentes</h4><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>'; ?></textarea>
                        </div>
                        
                        <div class="form-group">
							<label for="prefActaEso" class="control-label">Modelo de acta para evaluaciones de Formación Profesional</label>
                                <textarea class="form-control" id="prefActaEso" name="prefActaEso" rows="20"><?php echo (isset($config['evaluaciones']['acta_fp'])) ? $config['evaluaciones']['acta_fp'] : '<h4>1.- Acuerdos o consideraciones sobre el proceso de evaluación individual</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><h4>Profesores asistentes</h4><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>Profesores ausentes</h4><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>'; ?></textarea>
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

    <script>
    // EDITOR DE TEXTO
 	$('textarea').summernote({
 		height: 500,
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
		]
 	});
    </script>

</body>
</html>