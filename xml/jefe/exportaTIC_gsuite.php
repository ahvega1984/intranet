<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array('0', '1'));

if (isset($config['mod_centrotic']) && $config['mod_centrotic'] && isset($_GET['unidad']) && ! empty($_GET['unidad'])) {

	$directorio	= INTRANET_DIRECTORY.'/xml/jefe/TIC/';

	$unidad = $_GET['unidad'];

	$gsuite_alumnos	= 'gsuite_'.$unidad.'.csv';

	if (file_exists($directorio.$gsuite_alumnos)) unlink($directorio.$gsuite_alumnos);

	$array_correos = array();

		if (!$fp = fopen($directorio.$gsuite_alumnos, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$gsuite_alumnos);
		}
		else {
		// Cabecera del archivo
		fwrite($fp, "Group Email [Required],Member Email,Member Type,Member Role\r\n");

		$result = mysqli_query($db_con, "SELECT DISTINCT apellidos, nombre, claveal, unidad FROM alma where unidad='$unidad' ORDER BY apellidos, nombre ASC") or die (mysqli_query($db_con));
		while ($row = mysqli_fetch_array($result)) {
			$nombre = trim($row['nombre']);
			$apellidos = trim($row['apellidos']);

			$nie = trim($row[2]);
			$correo_grupo = trim($row[3])."@".$config['dominio'];
			$correo_grupo = strtolower($correo_grupo);
			
			$caracteres_no_permitidos = array('\'','-','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù', 'á', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü','ñ');
			$caracteres_permitidos = array('','','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n');

			if ($_SERVER['SERVER_NAME'] == "iesmonterroso.org" OR $_SERVER['SERVER_NAME'] == "localhost") {
				$exp_nombre = explode(' ',$nombre);
				$primer_nombre = trim($exp_nombre[0]);
				$primer_nombre = str_replace("Á", "A", $primer_nombre);

				$exp_apellidos = explode(' ',$apellidos);
				$primer_apellido = trim($exp_apellidos[0]);
				$primer_apellido = str_replace("Á", "A", $primer_apellido);
				$segundo_apellido = trim($exp_apellidos[1]);

				$primer_nombre = str_replace($caracteres_no_permitidos, $caracteres_permitidos, $primer_nombre);
				$primer_apellido = str_replace($caracteres_no_permitidos, $caracteres_permitidos, $primer_apellido);
				$iniciales = strtolower(substr($primer_nombre, 0,1).substr($primer_apellido, 0,1));
				$iniciales = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $iniciales);

				$pass_alumno = $iniciales.".".$row['claveal'];
			}
			else {
				$pass_alumno = substr(sha1($row['claveal']),0,8);
			}

			$correo = str_ireplace('M ª', 'María', $correo);
			$correo = str_ireplace('Mª', 'María', $correo);
			$correo = str_ireplace('M.', 'María', $correo);
			$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
			$correo = mb_strtolower($correo, 'UTF-8');
			if ($_SERVER['SERVER_NAME'] == "iesmonterroso.org" OR $_SERVER['SERVER_NAME'] == "localhost") {
				$correo = 'al.'.$nie.'@iesmonterroso.org';
			}
			else {
				$correo = $nie.'.alumno@'.$config['dominio'];
			}

			array_push($array_correos, $correo);
			$member_type = "USER";
			$member_role = "MEMBER";
			fwrite($fp, $correo_grupo.",".$correo.",".$member_type.",".$member_role."\r\n");

		}

		fclose($fp);

		if (is_file($directorio.$gsuite_alumnos)) {
			$size = filesize($directorio.$gsuite_alumnos);
			if (function_exists('mime_content_type')) {
				$type = mime_content_type($directorio.$gsuite_alumnos);
			} else if (function_exists('finfo_file')) {
				$info = finfo_open(FILEINFO_MIME);
				$type = finfo_file($info, $directorio.$gsuite_alumnos);
				finfo_close($info);
			}
			if ($type == '') {
				$type = "application/force-download";
			}
			// Set Headers
			header("Content-Type: $type");
			header("Content-Disposition: attachment; filename=$gsuite_alumnos");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . $size);
			// Download File
			readfile($directorio.$gsuite_alumnos);
		}
			unlink($directorio.$gsuite_alumnos);
		}
	}


	// Eliminamos las variables usadas en este script
	unset($correo_electronico);
	unset($directorio);
	unset($gesuser_alumnos);
	unset($gesuser_profesores);
	unset($moodle_alumnos);
	unset($moodle_profesores);
	unset($cuota_alumnos);
	unset($cuota_profesores);
	unset($cuota_gestion);

