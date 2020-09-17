<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array('z', '1'));

if (isset($config['mod_centrotic']) && $config['mod_centrotic']) {

	$directorio	= INTRANET_DIRECTORY.'/xml/jefe/TIC/';

	$gsuite_profes	= 'gsuite_grupo_profesores.csv';

	if (file_exists($directorio.$gsuite_profes)) unlink($directorio.$gsuite_profes);

	$array_correos = array();

		if (!$fp = fopen($directorio.$gsuite_profes, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$gsuite_profes);
		}
		else {
		// Cabecera del archivo
		fwrite($fp, "Group Email [Required],Member Email,Member Type,Member Role\r\n");

		$result = mysqli_query($db_con, "SELECT DISTINCT profesor, idea, correo FROM c_profes where correo not like '' ORDER BY profesor ASC") or die (mysqli_query($db_con));
		while ($row = mysqli_fetch_array($result)) {

			$correo_grupo = "profesores_clase@".$config['dominio'];
			$correo = $row['correo'];
			$member_type = "USER";
			$member_role = "MEMBER";
			fwrite($fp, $correo_grupo.",".$correo.",".$member_type.",".$member_role."\r\n");

		}

		fclose($fp);

		if (is_file($directorio.$gsuite_profes)) {
			$size = filesize($directorio.$gsuite_profes);
			if (function_exists('mime_content_type')) {
				$type = mime_content_type($directorio.$gsuite_profes);
			} else if (function_exists('finfo_file')) {
				$info = finfo_open(FILEINFO_MIME);
				$type = finfo_file($info, $directorio.$gsuite_profes);
				finfo_close($info);
			}
			if ($type == '') {
				$type = "application/force-download";
			}
			// Set Headers
			header("Content-Type: $type");
			header("Content-Disposition: attachment; filename=$gsuite_profes");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . $size);
			// Download File
			readfile($directorio.$gsuite_profes);
		}
			unlink($directorio.$gsuite_profes);
		}
	}


