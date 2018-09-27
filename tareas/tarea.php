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
	$tarea = addslashes($_POST['tarea']);
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
	$('#tarea').summernote({
		height: 300,
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
		cleaner: {
				action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
				newline: '<br>', // Summernote's default is to use '<p><br></p>'
				notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
				icon: '<i class="note-icon">[Your Button]</i>',
				keepHtml: false, // Remove all Html formats
				keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
				keepClasses: false, // Remove Classes
				badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
				badAttributes: ['style', 'start'], // Remove attributes from remaining tags
				limitChars: false, // 0/false|# 0/false disables option
				limitDisplay: 'both', // text|html|both
				limitStop: false // true/false
		}
	});
	</script>

</body>
</html>
