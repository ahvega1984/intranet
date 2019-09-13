<?php
require_once("../../bootstrap.php");
include("../../lib/pclzip.lib.php");

acl_acceso($_SESSION['cargo'], array('1'));


class my_ZipArchive extends ZipArchive
{
	public function extractSubdirTo($destination, $subdir)
	{
		$errors = array();

		// Prepare dirs
		$destination = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $destination);
		$subdir = str_replace(array("/", "\\"), "/", $subdir);

		if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, "UTF-8") * -1) != DIRECTORY_SEPARATOR)
		$destination .= DIRECTORY_SEPARATOR;

		if (substr($subdir, -1) != "/")
		$subdir .= "/";

		// Extract files
		for ($i = 0; $i < $this->numFiles; $i++)
		{
		$filename = $this->getNameIndex($i);

		if (substr($filename, 0, mb_strlen($subdir, "UTF-8")) == $subdir)
		{
			$relativePath = substr($filename, mb_strlen($subdir, "UTF-8"));
			$relativePath = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $relativePath);

			if (mb_strlen($relativePath, "UTF-8") > 0)
			{
			if (substr($filename, -1) == "/")  // Directory
			{
				// New dir
				if (!is_dir($destination . $relativePath))
				if (!@mkdir($destination . $relativePath, 0755, true))
					$errors[$i] = $filename;
			}
			else
			{
				if (dirname($relativePath) != ".")
				{
				if (!is_dir($destination . dirname($relativePath)))
				{
					// New dir (for file)
					@mkdir($destination . dirname($relativePath), 0755, true);
				}
				}

				// New file
				if (@file_put_contents($destination . $relativePath, $this->getFromIndex($i)) === false)
				$errors[$i] = $filename;
			}
			}
		}
		}

		return $errors;
	}
}

function getLatestVersion() {

	$context = array(
		'http' => array('header' => "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n")
	);

	$file = @json_decode(@file_get_contents("https://api.github.com/repos/IESMonterroso/intranet/tags", false, stream_context_create($context)));
	return sprintf("%s", $file ? reset($file)->name : INTRANET_VERSION);
}

$ultima_version = ltrim(getLatestVersion(), 'v');


// COMPROBAMOS SI ES ADMINISTRADOR DE LA INTRANET
if (! isset($_SESSION['user_admin']) || ! $_SESSION['user_admin']) {
	acl_acceso();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Intranet del <?php echo $config['centro_denominacion']; ?>">
	<meta name="author" content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">
	<meta name="robots" content="noindex, nofollow">

	<title>Intranet &middot; <?php echo $config['centro_denominacion']; ?></title>

	<link href="//<?php echo $config['dominio']; ?>/intranet/css/bootstrap.min.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/vendor/fontawesome-free-5.8.2-web/css/all.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/animate.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/otros.css" rel="stylesheet">

	<script src="//<?php echo $config['dominio']; ?>/intranet/js/jquery-2.1.1.min.js"></script>
	<script src="//<?php echo $config['dominio']; ?>/intranet/js/bootstrap.min.js"></script>

</head>

<body>

	<div id="wrapper">

		<div class="container">

			<div class="page-header">
				<h2 class="text-center">Actualización de la Intranet</h2>
			</div>

			<div class="row">

				<div class="col-sm-offset-3 col-sm-6">

					<div class="well">

						<div class="text-center">
							<br><br>
							<p id="icon"><i class="fas fa-sync-alt fa-spin fa-5x fa-fw"></i></p>
							<br>
							<p id="status" class="lead text-muted"></p>
							<br><br>
						</div>

					</div>

				</div>

			</div>

		</div>

	</div><!-- /#wrap -->

	<footer class="hidden-print">
		<div class="container-fluid">
			<p class="pull-left text-muted">&copy; <?php echo date('Y'); ?>, IES Monterroso</p>

			<ul class="pull-right list-inline">
				<li>Versión <?php echo INTRANET_VERSION; ?></li>
				<li><a href="//<?php echo $config['dominio']; ?>/intranet/aviso-legal/">Aviso legal</a></li>
				<li><a href="//<?php echo $config['dominio']; ?>/intranet/LICENSE.md" target="_blank">Licencia</a></li>
				<li><a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a></li>
			</ul>
		</div>
	</footer>

	<script>
	<?php
	if(version_compare($ultima_version, INTRANET_VERSION, '>')) {

		echo '$("#status").html("Iniciando actualización...");';

		// Asignamos el nombre al archivo de descarga con la actualización
		$zipfile = INTRANET_DIRECTORY.'/xml/actualizaciones/intranet-'.$ultima_version.'.zip';

		// Eliminamos el archivo si existiese y creamos uno nuevo
		if (file_exists($zipfile)) unlink($zipfile);

		$fp = fopen($zipfile, "w");

		// Cargamos una sesión cURL
		$ch = curl_init();

		// Establecemos las opciones de conexión
		curl_setopt($ch, CURLOPT_URL, "https://github.com/IESMonterroso/intranet/archive/v".$ultima_version .".zip");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FILE, $fp);

		// Ejecutamos la sesión cURL

		echo '$("#status").html("Descargando archivo de actualización...");';

		$result = curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		// En caso de error, detenemos el proceso de actualización
		if (! $result) {
			echo '$("#icon").html("<i class=\"fas fa-sync-alt fa-5x fa-fw\"></i>");';
			echo '$("#status").html("<strong class=\"text-danger\">Error al descargar el fichero de actualización. Realice la actualización manualmente.</strong>");';
			flush();
			ob_flush();
		}
		else {

			$zip = new my_ZipArchive;

			if ($zip->open($zipfile) === TRUE) {

				echo '$("#status").html("Descomprimiendo archivo de actualización...");';

				// Descomprimimos el contenido en la carpeta raíz de la Intranet
				$result = $zip->extractSubdirTo(INTRANET_DIRECTORY.'/', 'intranet-'.$ultima_version);
				$zip->close();

				// Comprobamos si se produjeron errores en la descompresión
				if (count($result) > 2) {
					$msg_error = 'Se han producido '.count($result) .' errores';

					$msg_error_list = '<ul>';
					foreach ($result as $error) {
						$msg_error_list .= '<li>Error al copiar directorio o fichero: '.$error.'</li>';
					}
					$msg_error_list .= '</ul>';

					echo '$("#icon").html("<i class=\"fas fa-sync-alt fa-5x fa-fw text-danger\"></i>");';
					echo '$("#status").html("<strong class=\"text-danger\">'.$msg_error.'</strong></p>'.$msg_error_list.'<p>Realice la actualización manualmente.");';

				}
				else {

					// Eliminamos archivo .gitignore de la raíz de la Intranet
					if (file_exists(INTRANET_DIRECTORY.'/.gitignore')) {
						unlink(INTRANET_DIRECTORY.'/.gitignore');
					}

					// Actualizaciones de la base de datos
					echo '$("#status").html("Aplicando actualizaciones de la base de datos");';

					include(INTRANET_DIRECTORY.'/actualizar.php');

					// Enviamos analíticas de uso al IES Monterroso
					$analitica = array(
						'centro_denominacion' => $config['centro_denominacion'],
						'centro_codigo' => $config['centro_codigo'],
						'centro_direccion' => $config['centro_direccion'],
						'centro_localidad' => $config['centro_localidad'],
						'centro_codpostal' => $config['centro_codpostal'],
						'centro_provincia' => $config['centro_provincia'],
						'centro_telefono' => $config['centro_telefono'],
						'centro_email' => $config['centro_email'],
						'centro_telefono' => $config['centro_telefono'],
						'dominio' => $config['dominio'],
						'https' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 1 : 0),
						'request' => $_SERVER['REQUEST_URI'],
						'ip' => $_SERVER['SERVER_ADDR'],
						'osname' => php_uname('s'),
						'server' => $_SERVER['SERVER_SOFTWARE'],
						'php_version' => phpversion(),
						'mysql_version' => mysqli_get_server_info($db_con),
						'intranet_version' => $ultima_version
					);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,"https://iesmonterroso.org/intranet/analitica/baliza.php");
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $analitica);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					curl_exec($ch);
					curl_close($ch);

					// Finalizamos la actualización

					echo '$("#icon").html("<i class=\"fas fa-check-circle fa-5x fa-fw text-success\"></i>");';
					echo '$("#status").html("<strong class=\"text-success\">Actualización completada</strong><br><br><a href=\"//'.$config['dominio'].'/intranet/index.php\" class=\"btn btn-primary\">Ir a la página principal</a>");';
				}

			} else {
				echo '$("#icon").html("<i class=\"fas fa-sync-alt fa-5x fa-fw\"></i>");';
				echo '$("#status").html("<strong class=\"text-danger\">Error al abrir el archivo de actualización. Realice la actualización manualmente.</strong>");';
			}
		}

		// Eliminamos el archivo de actualización
		unlink($zipfile);

	}
	else {
		echo '$("#icon").html("<i class=\"fas fa-check-circlefa-5x fa-fw\"></i>");';
		echo '$("#status").html("<strong>No hay actualizaciones disponibles</strong><br><br><a href=\"//'.$config['dominio'].'/intranet/index.php\" class=\"btn btn-primary\">Ir a la página principal</a>");';
	}
	?>
	</script>

</body>
</html>
