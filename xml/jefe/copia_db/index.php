<?php
include("../../../bootstrap.php");

acl_acceso($_SESSION['cargo'], array('0', '1'));

$profe = $_SESSION['profi'];

function copia_bd($host, $user, $pass, $name) {
	$backup_file = escapeshellarg('db-backup_'.$name.'_'.date('YmdHis').'.sql.gz');

	$command = "mysqldump --opt --ignore-table=$name.fotos -h $host -u $user -p$pass $name | gzip -9 > $backup_file";

	// ejecución y salida de éxito o errores
	system($command,$output);
	return $output;
}

function mb_file($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

// CREAR COPIA DE SEGURIDAD
if(isset($_GET['action']) && $_GET['action']=="crear") {

	$result = copia_bd($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

	if ($result === NULL) {
		$msg_error = "No ha sido posible crear la copia de seguridad. Probablemente su proveedor no permite la ejecución de comandos a través del servidor web. Realice una copia de seguridad a través de phpMyAdmin u otro gestor de base de datos.";
	}
	else {
		$msg_success = "Se ha creado una nueva copia de seguridad de la base de datos " . $config['db_name'] . ".";
	}
}

// DESCARGA DE ARCHIVO
if((isset($_GET['action']) && $_GET['action']=="descargar") && (isset($_GET['archivo']) && file_exists($_GET['archivo']))) {
	$file = limpiarInput($_GET['archivo'], 'alphanumericspecial');
	if (strstr($file, 'db-backup_') == true) {
		header("Content-disposition: attachment; filename=".$file."");
		header("Content-type: application/octet-stream");
		readfile(INTRANET_DIRECTORY . '/xml/jefe/copia_db/' . $file);
	}
}

// ELIMINAR COPIA DE SEGURIDAD
if((isset($_GET['action']) && $_GET['action']=="eliminar") && (isset($_GET['archivo']) && file_exists($_GET['archivo']))) {
	$file = limpiarInput($_GET['archivo'], 'alphanumericspecial');
	if (strstr($file, 'db-backup_') == true) {
		unlink(INTRANET_DIRECTORY . '/xml/jefe/copia_db/' . $file);
	}
}


include("../../../menu.php");
?>

<div class="container">

	<div class="page-header">
	  <h2>Administración <small> Copia de seguridad de la base de datos</small></h2>
	</div>

	<?php if(isset($msg_success)): ?>
	<div class="alert alert-success">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>

	<?php if(isset($msg_error)): ?>
	<div class="alert alert-danger">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>

	<div class="row">

		<div class="col-sm-12">

			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Archivo</th>
							<th>Tamaño</th>
							<th>Fecha</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
            <?php foreach (array_reverse(glob("*.sql.gz")) as $archivo): ?>
            <?php
            $exp_archivo = explode('_', $archivo);
            $total_exp_archivo = count($exp_archivo);
            $fecha_completa = rtrim($exp_archivo[$total_exp_archivo-1], '.sql.gz');
            $fecha_formateada = preg_replace('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/i', '$3-$2-$1 $4:$5:$6', $fecha_completa);
            ?>
						<tr>
							<td><?php echo $archivo; ?></td>
							<td><?php echo mb_file(filesize($archivo)); ?></td>
							<td><?php echo $fecha_formateada; ?></td>
							<td nowrap>
								<a href="restaurar.php?archivo=<?php echo $archivo; ?>" data-bb="confirm-restore" data-bs="tooltip" title="Restaurar"><span class="fas fa-undo fa-lg fa-fw"></span></a>
								<a href="index.php?action=descargar&archivo=<?php echo $archivo; ?>" data-bs="tooltip" title="Descargar"><span class="fas fa-download fa-lg fa-fw"></span></a>
								<a href="index.php?action=eliminar&archivo=<?php echo $archivo; ?>" data-bb="confirm-delete" data-bs="tooltip" title="Eliminar"><span class="far fa-trash-alt fa-lg fa-fw"></span></a>
							</td>
						</tr>
            <?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<a class="btn btn-primary" href="index.php?action=crear">Crear copia</a>
			<a class="btn btn-default" href="restaurar.php">Restaurar desde archivo</a>
			<a class="btn btn-default" href="../../index.php">Volver</a>

		</div>

	</div>

</div>

<?php include("../../../pie.php"); ?>

<script>
$(document).on("click", "a[data-bb]", function(e) {
    e.preventDefault();
    var type = $(this).data("bb");
    var link = $(this).attr("href");

    if (type == 'confirm-restore') {
      bootbox.setDefaults({
        locale: "es",
        show: true,
        backdrop: true,
        closeButton: true,
        animate: true,
        title: "Confirmación para restaurar la base de datos",
      });

      bootbox.confirm("Esta acción eliminará el contenido actual de la base de datos y restaurará la copia de seguridad. ¿Seguro que desea continuar?", function(result) {
          if (result) {
            document.location.href = link;
          }
      });
    }
});
</script>

</body>
</html>
