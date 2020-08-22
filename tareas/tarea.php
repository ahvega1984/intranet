<?php
require('../bootstrap.php');

if (isset($_GET['id']) && isset($_GET['accion'])) {
	$id = intval($_GET['id']);
	$accion = htmlspecialchars($_GET['accion']);

	switch ($accion) {
		case 'eliminar' :

			$result = mysqli_query($db_con, "DELETE FROM tareas WHERE id = ".$id." AND idea = '".$idea."' LIMIT 1");
			if (! $result) $msg_error = "Error al eliminar la tarea. ".mysqli_error($db_con);

			break;

		case 'finalizar' :

			$result = mysqli_query($db_con, "UPDATE tareas SET estado = 1 WHERE id = ".$id." AND idea = '".$idea."' AND estado = 0 LIMIT 1");
			if (! $result) $msg_error = "Error al actualizar el estado de la tarea. ".mysqli_error($db_con);

			break;

		case 'rehacer' :

			$result = mysqli_query($db_con, "UPDATE tareas SET estado = 0 WHERE id = ".$id." AND idea = '".$idea."' AND estado = 1 LIMIT 1");
			if (! $result) $msg_error = "Error al actualizar el estado de la tarea. ".mysqli_error($db_con);

			break;

		default:
			$msg_error = "Acción no disponible";
			break;
	}
}

$texto_boton = "Crear tarea";

// Enviamos el formulario
if (isset($_POST['submit'])) {
	$titulo = htmlspecialchars(trim($_POST['titulo']));
	$_tarea = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $_POST['tarea']);
	$tarea = addslashes($_tarea);
	$fechareg = date('Y-m-d H:i:s');

	if (! isset($_GET['id'])) {
		$result = mysqli_query($db_con, "INSERT tareas (idea, titulo, tarea, estado, fechareg, prioridad) VALUES ('".$idea."', '".$titulo."', '".$tarea."', 0, '".$fechareg."', 0)");
		if (! $result) {
			$msg_error = "Ha ocurrido un error al guardar la tarea. ".mysqli_error($db_con);
		}
		else {
			header("Location:"."index.php");
			exit();
		}
	}
	else {
		$id = intval($_GET['id']);

		$result = mysqli_query($db_con, "UPDATE tareas SET titulo = '".$titulo."', tarea = '".$tarea."' WHERE id = '".$id."' LIMIT 1");
		if (! $result) {
			$msg_error = "Ha ocurrido un error al actualizar la tarea. ".mysqli_error($db_con);
		}
		else {
			header("Location:"."index.php");
			exit();
		}
	}
}

// Obtenemos la información de la tarea si se ha seleccionado una
if (isset($_GET['id'])) {
	$id = intval($_GET['id']);

	$result = mysqli_query($db_con, "SELECT id, titulo, tarea, fechareg, estado, prioridad FROM tareas WHERE id = ".$id." AND idea = '".$idea."'");
	if (! mysqli_num_rows($result)) {
		header("Location:"."index.php?error=noExiste");
		exit();
	}
	$row = mysqli_fetch_array($result);
	$titulo = $row['titulo'];
	$tarea = stripslashes($row['tarea']);
	$tarea.= "<br>Fecha: ".formatea_fecha(date('Y-m-d'));
	$fechareg = $row['fechareg'];
	$estado = $row['estado'];
	$prioridad = $row['prioridad'];

	$texto_boton = "Actualizar";
}


include("../menu.php");
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Tareas</h2>
		</div>

		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<strong>Error:</strong> <?php echo $msg_error; ?>
		</div>
		<?php endif; ?>

		<div class="row">

			<div class="col-md-offset-2 col-md-8">

				<div class="well">

					<form action="" method="post">

						<fieldset>

							<div class="form-group">
								<label for="titulo">Nombre de la tarea</label>
								<input type="text" class="form-control" name="titulo" id="titulo" placeholder="Nombre de la tarea" value="<?php echo (isset($titulo)) ? $titulo : ''; ?>">
							</div>

							<div class="form-group">
								<label for="tarea">Descripción de la tarea</label>
								<textarea class="form-control" name="tarea" id="tarea" rows="7"><?php echo (isset($tarea)) ? $tarea : ''; ?></textarea>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary" name="submit"><?php echo $texto_boton; ?></button>
								<?php if (isset($_GET['id']) && $estado == 0): ?>
								<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php?id=<?php echo $id; ?>&amp;accion=finalizar" class="btn btn-success">Finalizar</a>
								<?php elseif (isset($_GET['id']) && $estado == 1): ?>
								<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php?id=<?php echo $id; ?>&amp;accion=rehacer" class="btn btn-warning">Rehacer</a>
								<?php endif; ?>
								<?php if (isset($_GET['id'])): ?>
								<a href="//<?php echo $config['dominio']; ?>/intranet/tareas/tarea.php?id=<?php echo $id; ?>&amp;accion=eliminar" class="btn btn-danger" data-bb="confirm-delete">Eliminar</a>
								<?php endif; ?>
								<?php if (preg_match('#<a id="enlace_respuesta" href="(.*)"></a>#', $tarea, $enlace)): ?>
								<a href="<?php echo $enlace[1]; ?>" class="btn btn-info">Responder mensaje</a>
								<?php endif; ?>
								<a href="index.php" class="btn btn-default">Volver</a>
							</div>

						</fieldset>

					</form>

				</div>

			</div><!-- /.col-md-12 -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<?php include("../pie.php"); ?>

	<script>
	// EDITOR DE TEXTO
	tinymce.init({
			selector: 'textarea#tarea',
			language: 'es',
			height: 300,
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
