<?php
require('../../bootstrap.php');

if (isset($config['mod_centrotic']) && $config['mod_centrotic'] && isset($_GET['exportar']) && ! empty($_GET['exportar'])) {

	$directorio			= INTRANET_DIRECTORY.'/xml/jefe/TIC/';

	// GESUSER
	$gesuser_alumnos	= 'alumnos.txt';
	$gesuser_profesores	= 'profesores.txt';

	$cuota_alumnos 		= '300'; // Capacidad en megabytes
	$cuota_profesores 	= '300'; // Capacidad en megabytes
	$cuota_gestion		= '300'; // Capacidad en megabytes

	if (file_exists($directorio.$gesuser_alumnos)) unlink($directorio.$gesuser_alumnos);
	if (file_exists($directorio.$gesuser_profesores)) unlink($directorio.$gesuser_profesores);

	// Perfil alumno
	if ($_GET['exportar'] == $gesuser_alumnos) {
		if (!$fp = fopen($directorio.$gesuser_alumnos, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$gesuser_alumnos);
		}
		else {
			$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre FROM alma ORDER BY unidad ASC, apellidos ASC, nombre ASC");

			while ($row = mysqli_fetch_array($result)) {
				fwrite($fp, $row['claveal'].";".$row['nombre']." ".$row['apellidos'].";a;;".$cuota_alumnos."\r\n");
			}
			fclose($fp);

			if (is_file($directorio.$gesuser_alumnos)) {
				$size = filesize($directorio.$gesuser_alumnos);
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($directorio.$gesuser_alumnos);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $directorio.$gesuser_alumnos);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Set Headers
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$gesuser_alumnos");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Download File
				readfile($directorio.$gesuser_alumnos);
			}
			unlink($directorio.$gesuser_alumnos);
		}
	}

	// Perfil profesor y gestión
	if ($_GET['exportar'] == $gesuser_profesores) {
		if (!$fp = fopen($directorio.$gesuser_profesores, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$gesuser_profesores);
		}
		else {
			// Perfil profesor: Todos los profesores excepto equipo directivo
			$result = mysqli_query($db_con, "SELECT nombre, idea, departamento FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Educador' AND cargo NOT LIKE '%1%' ORDER BY nombre ASC");
			while ($row = mysqli_fetch_array($result)) {
				$exp_nombre = explode(', ', $row['nombre']);
				$nombre = trim($exp_nombre[1]);
				$apellidos = trim($exp_nombre[0]);
				$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);

				fwrite($fp, $row['idea'].";".$nombre_completo.";p;;".$cuota_profesores."\r\n");
			}

			// Perfil gestión: Equipo directivo y Administración
			$result = mysqli_query($db_con, "SELECT nombre, idea, cargo, departamento FROM departamentos WHERE departamento = 'Administracion' OR (cargo LIKE '%1%' AND departamento <> 'Admin') ORDER BY nombre ASC");
			while ($row = mysqli_fetch_array($result)) {
				$exp_nombre = explode(', ', $row['nombre']);
				$nombre = trim($exp_nombre[1]);
				$apellidos = trim($exp_nombre[0]);
				$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);

				fwrite($fp, $row['idea'].";".$nombre_completo.";g;;".$cuota_gestion."\r\n");
			}

			fclose($fp);

			if (is_file($directorio.$gesuser_profesores)) {
				$size = filesize($directorio.$gesuser_profesores);
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($directorio.$gesuser_profesores);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $directorio.$gesuser_profesores);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Set Headers
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$gesuser_profesores");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Download File
				readfile($directorio.$gesuser_profesores);
			}
			unlink($directorio.$gesuser_profesores);
		}
	}

	// PLATAFORMA MOODLE
	$moodle_alumnos	= 'alumnos_moodle.txt';
	$moodle_profesores	= 'profesores_moodle.txt';

	if (file_exists($directorio.$moodle_alumnos)) unlink($directorio.$moodle_alumnos);
	if (file_exists($directorio.$moodle_profesores)) unlink($directorio.$moodle_profesores);

	// Perfil alumno
	if ($_GET['exportar'] == $moodle_alumnos) {
		if (!$fp = fopen($directorio.$moodle_alumnos, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$moodle_alumnos);
		}
		else {
			// Cabecera del archivo
			fwrite($fp, "username;password;firstname;lastname;email;city;country;institution\r\n");

			$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre, correo FROM alma ORDER BY unidad ASC, apellidos ASC, nombre ASC");

			while ($row = mysqli_fetch_array($result)) {
				$correo_electronico = mb_strtolower('alumno.'.$row['claveal'].'@'.$config['dominio'], 'UTF-8');

				fwrite($fp, $row['claveal'].";".substr(sha1($row['claveal']),0,8).";".$row['nombre'].";".$row['apellidos'].";".$correo_electronico.";".$config['centro_localidad'].";ES;alumnos\r\n");
			}
			fclose($fp);

			if (is_file($directorio.$moodle_alumnos)) {
				$size = filesize($directorio.$moodle_alumnos);
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($directorio.$moodle_alumnos);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $directorio.$moodle_alumnos);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Set Headers
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$moodle_alumnos");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Download File
				readfile($directorio.$moodle_alumnos);
			}
			unlink($directorio.$moodle_alumnos);
		}
	}

	// Perfil profesor, excepto Administración y Conserjería
	if ($_GET['exportar'] == $moodle_profesores) {
		if (!$fp = fopen($directorio.$moodle_profesores, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$moodle_profesores);
		}
		else {
			// Cabecera del archivo
			fwrite($fp, "username;password;firstname;lastname;email;city;country;institution\r\n");

			// Perfil profesor: Todos los profesores excepto equipo directivo
			$result = mysqli_query($db_con, "SELECT DISTINCT d.nombre, d.idea, d.departamento, d.dni, c.correo FROM departamentos AS d JOIN c_profes AS c ON d.idea = c.idea WHERE d.departamento <> 'Admin' AND d.departamento <> 'Administracion' AND d.departamento <> 'Conserjeria' AND d.departamento <> 'Educador' ORDER BY d.nombre ASC") or die (mysqli_query($db_con));
			while ($row = mysqli_fetch_array($result)) {
				$exp_nombre = explode(', ', $row['nombre']);
				$nombre = trim($exp_nombre[1]);
				$apellidos = trim($exp_nombre[0]);
				$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);
				$correo_electronico = ($row['correo'] == "") ? 'profesor.'.$row['idea'].'@'.mb_strtolower($config['dominio'],'UTF-8') : mb_strtolower($row['correo'], 'UTF-8');

				fwrite($fp, $row['idea'].";".$row['dni'].";".$nombre.";".$apellidos.";".$correo_electronico.";".$config['centro_localidad'].";ES;profesores\r\n");
			}

			fclose($fp);

			if (is_file($directorio.$moodle_profesores)) {
				$size = filesize($directorio.$moodle_profesores);
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($directorio.$moodle_profesores);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $directorio.$moodle_profesores);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Set Headers
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$moodle_profesores");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Download File
				readfile($directorio.$moodle_profesores);
			}
			unlink($directorio.$moodle_profesores);
		}
	}

	// GOOGLE SUITE
	$gsuite_profesores	= 'profesores_gsuite.csv';

	if (file_exists($directorio.$gsuite_profesores)) unlink($directorio.$gsuite_profesores);

	$array_correos = array();

	if ($_GET['exportar'] == $gsuite_profesores) {
		if (!$fp = fopen($directorio.$gsuite_profesores, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$gsuite_profesores);
		}
		else {
			// Cabecera del archivo
			fwrite($fp, "First Name,Last Name,Email Address,Password,Secondary Email,Work Phone 1,Home Phone 1,Mobile Phone 1,Work address 1,Home address 1,Employee Id,Employee Type,Employee Title,Manager,Department,Cost Center\r\n");

			$result = mysqli_query($db_con, "SELECT DISTINCT d.nombre, d.idea, d.departamento, d.dni, c.correo FROM departamentos AS d JOIN c_profes AS c ON d.idea = c.idea WHERE d.departamento <> 'Admin' ORDER BY d.nombre ASC") or die (mysqli_query($db_con));
			while ($row = mysqli_fetch_array($result)) {
				$exp_nombre = explode(', ', $row['nombre']);

				$nombre = trim($exp_nombre[1]);
				$exp_nombrecomp = explode(' ',$nombre);
				$primer_nombre = trim($exp_nombrecomp[0]);

				$apellidos = trim($exp_nombre[0]);
				$exp_apellidos = explode(' ',$apellidos);
				$primer_apellido = trim($exp_apellidos[0]);
				$segundo_apellido = trim($exp_apellidos[1]);

				$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);

				$caracteres_no_permitidos = array('\'','-','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù', 'á', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü','ñ');
				$caracteres_permitidos = array('','','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n');

				$correo = $primer_nombre.$primer_apellido;
				$correo = str_ireplace('M ª', 'María', $correo);
				$correo = str_ireplace('Mª', 'María', $correo);
				$correo = str_ireplace('M.', 'María', $correo);
				$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
				$correo = mb_strtolower($correo, 'UTF-8');
				$correo = $correo.'@'.$config['dominio'];

				// Si ya existe la cuenta de correo, añadimos el segundo apellido
				if (in_array($correo, $array_correos)) {
					$correo = $primer_nombre.$primer_apellido.$segundo_apellido;
					$correo = str_ireplace('M ª', 'María', $correo);
					$correo = str_ireplace('Mª', 'María', $correo);
					$correo = str_ireplace('M.', 'María', $correo);
					$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
					$correo = mb_strtolower($correo, 'UTF-8');
					$correo = $correo.'@'.$config['dominio'];
				}

				array_push($array_correos, $correo);

				fwrite($fp, utf8_decode($nombre).",".utf8_decode($apellidos).",".$correo.",".$row['dni']."\r\n");
			}

			fclose($fp);

			if (is_file($directorio.$gsuite_profesores)) {
				$size = filesize($directorio.$gsuite_profesores);
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($directorio.$gsuite_profesores);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $directorio.$gsuite_profesores);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Set Headers
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$gsuite_profesores");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Download File
				readfile($directorio.$gsuite_profesores);
			}
				unlink($directorio.$gsuite_profesores);
		}
	}

	// OFFICE 365
	$office365_profesores	= 'profesores_office365.csv';

	if (file_exists($directorio.$office365_profesores)) unlink($directorio.$office365_profesores);

	$array_correos = array();

	if ($_GET['exportar'] == $office365_profesores) {
		if (!$fp = fopen($directorio.$office365_profesores, 'w+')) {
			die ("Error: No se puede crear o abrir el archivo ".$directorio.$office365_profesores);
		}
		else {
			// Cabecera del archivo
			fwrite($fp, utf8_decode("Nombre de usuario,Nombre,Apellidos,Nombre para mostrar,Puesto,Departamento,Número del trabajo,Teléfono del trabajo,Teléfono móvil,Número de fax,Dirección,Ciudad,Estado o provincia,Código postal,País o región\r\n"));

			$result = mysqli_query($db_con, "SELECT DISTINCT d.nombre, d.idea, d.departamento, d.dni, c.correo FROM departamentos AS d JOIN c_profes AS c ON d.idea = c.idea WHERE d.departamento <> 'Admin' ORDER BY d.nombre ASC") or die (mysqli_query($db_con));
			while ($row = mysqli_fetch_array($result)) {
				$exp_nombre = explode(', ', $row['nombre']);

				$nombre = trim($exp_nombre[1]);
				$exp_nombrecomp = explode(' ',$nombre);
				$primer_nombre = trim($exp_nombrecomp[0]);

				$apellidos = trim($exp_nombre[0]);
				$exp_apellidos = explode(' ',$apellidos);
				$primer_apellido = trim($exp_apellidos[0]);
				$segundo_apellido = trim($exp_apellidos[1]);

				$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);

				$caracteres_no_permitidos = array('\'','-','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù', 'á', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü','ñ');
				$caracteres_permitidos = array('','','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n');

				$correo = $primer_nombre.$primer_apellido;
				$correo = str_ireplace('M ª', 'María', $correo);
				$correo = str_ireplace('Mª', 'María', $correo);
				$correo = str_ireplace('M.', 'María', $correo);
				$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
				$correo = mb_strtolower($correo, 'UTF-8');
				$correo = $correo.'@'.$config['dominio'];

				// Si ya existe la cuenta de correo, añadimos el segundo apellido
				if (in_array($correo, $array_correos)) {
					$correo = $primer_nombre.$primer_apellido.$segundo_apellido;
					$correo = str_ireplace('M ª', 'María', $correo);
					$correo = str_ireplace('Mª', 'María', $correo);
					$correo = str_ireplace('M.', 'María', $correo);
					$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
					$correo = mb_strtolower($correo, 'UTF-8');
					$correo = $correo.'@'.$config['dominio'];
				}

				array_push($array_correos, $correo);

				fwrite($fp, $correo.",".utf8_decode($nombre).",".utf8_decode($apellidos).",".utf8_decode($nombre_completo)."\r\n");
			}

			fclose($fp);

			if (is_file($directorio.$office365_profesores)) {
				$size = filesize($directorio.$office365_profesores);
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($directorio.$office365_profesores);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $directorio.$office365_profesores);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Set Headers
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$office365_profesores");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Download File
				readfile($directorio.$office365_profesores);
			}
				unlink($directorio.$office365_profesores);
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
}
