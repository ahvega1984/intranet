<?php
require('../../bootstrap.php');

$texto_acta_eso = '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active"><b>Rendimiento global</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud general ante el estudio</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud ante las normas y la convivencia</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Asistencia a clase</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos sobre el proceso individual</h4><h5>2.1.- Necesidades educativas</h5><table class="table table-bordered"><tbody><tr><td width="30%" class="active"><b>Refuerzo educativo</b></td><td width="70%"><br></td></tr><tr><td class="active"><b>A.C.I</b></td><td><br></td></tr><tr><td class="active"><b>Propuestas entrada en el PMAR 2º / PMAR 3º / FP Básica</b></td><td><br></td></tr><tr><td class="active"><b>Programa de enriquecimiento</b></td><td><br></td></tr><tr><td class="active"><b>A tener en cuenta por el Depto. de Orientación</b></td><td><br></td></tr></tbody></table><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td>{NOMBRE_Y_APELLIDOS}</td><td style="text-align: center; ">{FALTAS_DE_ASISTENCIA}</td><td style="text-align: center; ">{PROBLEMAS_DISCIPLINARIOS}</td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>5.- Acuerdos tomados por el equipo docente:</h4><p><br></p><p><br></p>';
$texto_acta_eso_adultos = '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active"><b>Rendimiento global</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud general ante el estudio</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud ante las normas y la convivencia</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Asistencia a clase</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos o consideraciones sobre el proceso de evaluación final individual:</h4><p><br></p><p><br></p><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td>{NOMBRE_Y_APELLIDOS}</td><td style="text-align: center; ">{FALTAS_DE_ASISTENCIA}</td><td style="text-align: center; ">{PROBLEMAS_DISCIPLINARIOS}</td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>5.- Alumnado que se considera no reúne perfil de hacer estudios de ESO para Adultos</h4><p><br></p><p><br></p><p><br></p><h4>6.- Alumnado excelente a felicitar</h4><p><br></p><p><br></p>';
$texto_acta_bach = '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active"><b>Rendimiento global</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud general ante el estudio</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud ante las normas y la convivencia</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Asistencia a clase</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos o consideraciones sobre el proceso de evaluación final individual:</h4><p><br></p><p><br></p><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td>{NOMBRE_Y_APELLIDOS}</td><td style="text-align: center; ">{FALTAS_DE_ASISTENCIA}</td><td style="text-align: center; ">{PROBLEMAS_DISCIPLINARIOS}</td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>5.- Alumnado que se considera no reúne perfil de hacer estudios de Bachillerato</h4><p><br></p><p><br></p><p><br></p><h4>6.- Alumnado excelente a felicitar</h4><p><br></p><p><br></p>';
$texto_acta_bach_adultos = '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active"><b>Rendimiento global</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud general ante el estudio</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud ante las normas y la convivencia</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Asistencia a clase</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos o consideraciones sobre el proceso de evaluación final individual:</h4><p><br></p><p><br></p><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td>{NOMBRE_Y_APELLIDOS}</td><td style="text-align: center; ">{FALTAS_DE_ASISTENCIA}</td><td style="text-align: center; ">{PROBLEMAS_DISCIPLINARIOS}</td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>5.- Alumnado que se considera no reúne perfil de hacer estudios de Bachillerato para Adultos</h4><p><br></p><p><br></p><p><br></p><h4>6.- Alumnado excelente a felicitar</h4><p><br></p><p><br></p>';
$texto_acta_fp = '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active"><b>Rendimiento global</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud general ante el estudio</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Actitud ante las normas y la convivencia</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active"><b>Asistencia a clase</b></td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos o consideraciones sobre el proceso de evaluación individual</h4><p><br></p><p><br></p><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td>{NOMBRE_Y_APELLIDOS}</td><td style="text-align: center; ">{FALTAS_DE_ASISTENCIA}</td><td style="text-align: center; ">{PROBLEMAS_DISCIPLINARIOS}</td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p>';

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {

	$prefActaEso	= limpiar_string($_POST['prefActaEso']);
	$prefActaBach	= limpiar_string($_POST['prefActaBach']);
	$prefActaFP	  = limpiar_string($_POST['prefActaFP']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+')) {
		fwrite($file, "<?php \r\n");

		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE SESIONES DE EVALUACIÓN\r\n");
		fwrite($file, "\$config['evaluaciones']['acta_eso']\t= '$prefActaEso';\r\n");
		fwrite($file, "\$config['evaluaciones']['acta_eso_adultos']\t= '$prefActaEsoAdultos';\r\n");
	    fwrite($file, "\$config['evaluaciones']['acta_bach']\t= '$prefActaBach';\r\n");
	    fwrite($file, "\$config['evaluaciones']['acta_bach_adultos']\t= '$prefActaBachAdultos';\r\n");
	    fwrite($file, "\$config['evaluaciones']['acta_fp']\t= '$prefActaFP';\r\n");

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
							<textarea class="form-control" id="prefActaEso" name="prefActaEso" rows="20"><?php echo (isset($config['evaluaciones']['acta_eso'])) ? $config['evaluaciones']['acta_eso'] : $texto_acta_eso; ?></textarea>
						</div>

						<div class="form-group">
							<label for="prefActaESOAdultos" class="control-label">Modelo de acta para evaluaciones de ESO para Adultos</label>
							<textarea class="form-control" id="prefActaESOAdultos" name="prefActaESOAdultos" rows="20"><?php echo (isset($config['evaluaciones']['acta_eso_adultos'])) ? $config['evaluaciones']['acta_eso_adultos'] : $texto_acta_eso_adultos; ?></textarea>
						</div>

						<div class="form-group">
							<label for="prefActaBach" class="control-label">Modelo de acta para evaluaciones de Bachillerato</label>
							<textarea class="form-control" id="prefActaBach" name="prefActaBach" rows="20"><?php echo (isset($config['evaluaciones']['acta_bach'])) ? $config['evaluaciones']['acta_bach'] : $texto_acta_bach; ?></textarea>
						</div>

						<div class="form-group">
							<label for="prefActaBachAdultos" class="control-label">Modelo de acta para evaluaciones de Bachillerato para Adultos</label>
							<textarea class="form-control" id="prefActaBachAdultos" name="prefActaBachAdultos" rows="20"><?php echo (isset($config['evaluaciones']['acta_bach_adultos'])) ? $config['evaluaciones']['acta_bach_adultos'] : $texto_acta_bach_adultos; ?></textarea>
						</div>

						<div class="form-group">
							<label for="prefActaEso" class="control-label">Modelo de acta para evaluaciones de Formación Profesional</label>
							<textarea class="form-control" id="prefActaEso" name="prefActaFP" rows="20"><?php echo (isset($config['evaluaciones']['acta_fp'])) ? $config['evaluaciones']['acta_fp'] : $texto_acta_fp; ?></textarea>
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
 	tinymce.init({
		selector: 'textarea',
		language: 'es_ES',
		height: 500,
		plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars',
		imagetools_cors_hosts: ['picsum.photos'],
		menubar: 'file edit view insert format tools table help',
		toolbar: 'undo redo | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap | fullscreen  preview save print | insertfile image media template link anchor | ltr rtl',
		toolbar_sticky: true,
		autosave_ask_before_unload: true,
		autosave_interval: "30s",
		autosave_prefix: "{path}{query}-{id}-",
		autosave_restore_when_empty: false,
		autosave_retention: "2m",
		image_advtab: true,
		
		/* enable title field in the Image dialog*/
		image_title: true,
		/* enable automatic uploads of images represented by blob or data URIs*/
		automatic_uploads: true,
		/*
		URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
		images_upload_url: 'postAcceptor.php',
		here we add custom filepicker only to Image dialog
		*/
		file_picker_types: 'image',
		/* and here's our custom image picker*/
		file_picker_callback: function (cb, value, meta) {
		var input = document.createElement('input');
		input.setAttribute('type', 'file');
		input.setAttribute('accept', 'image/*');

		/*
		  Note: In modern browsers input[type="file"] is functional without
		  even adding it to the DOM, but that might not be the case in some older
		  or quirky browsers like IE, so you might want to add it to the DOM
		  just in case, and visually hide it. And do not forget do remove it
		  once you do not need it anymore.
		*/

		input.onchange = function () {
		  var file = this.files[0];

		  var reader = new FileReader();
		  reader.onload = function () {
		    /*
		      Note: Now we need to register the blob in TinyMCEs image blob
		      registry. In the next release this part hopefully won't be
		      necessary, as we are looking to handle it internally.
		    */
		    var id = 'blobid' + (new Date()).getTime();
		    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
		    var base64 = reader.result.split(',')[1];
		    var blobInfo = blobCache.create(id, file, base64);
		    blobCache.add(blobInfo);

		    /* call the callback and populate the Title field with the file name */
		    cb(blobInfo.blobUri(), { title: file.name });
		  };
		  reader.readAsDataURL(file);
		};

		input.click();
		}
	});
    </script>

</body>
</html>
