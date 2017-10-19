<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

if (isset($config['mod_centrotic']) && $config['mod_centrotic']) { 

	$directorio			= INTRANET_DIRECTORY.'/xml/jefe/TIC/';

	// GESUSER
	$gesuser_alumnos	= 'alumnos.txt';
	$gesuser_profesores	= 'profesores.txt';

	$cuota_alumnos 		= '300'; // Capacidad en megabytes
	$cuota_profesores 	= '300'; // Capacidad en megabytes
	$cuota_gestion		= '300'; // Capacidad en megabytes

	// Perfil alumno
	if (!$fp = fopen($directorio.$gesuser_alumnos, 'w+')) {
		die ("Error: No se puede crear o abrir el archivo ".$directorio.$gesuser_alumnos);
	}
	else {
		$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre FROM alma ORDER BY unidad ASC, apellidos ASC, nombre ASC");

		while ($row = mysqli_fetch_array($result)) {
			fwrite($fp, $row['claveal'].";".$row['nombre']." ".$row['apellidos'].";a;;".$cuota_alumnos."\r\n");
		}
		fclose($fp);
	}

	// Perfil profesor y gestión
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
	}

	// PLATAFORMA MOODLE
	$moodle_alumnos	= 'alumnos_moodle.txt';
	$moodle_profesores	= 'profesores_moodle.txt';

	

	// Perfil alumno
	if (!$fp = fopen($directorio.$moodle_alumnos, 'w+')) {
		die ("Error: No se puede crear o abrir el archivo ".$directorio.$moodle_alumnos);
	}
	else {
		// Cabecera del archivo
		fwrite($fp, "username;password;firstname;lastname;email;city;country\r\n");

		$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre, correo FROM alma ORDER BY unidad ASC, apellidos ASC, nombre ASC");

		while ($row = mysqli_fetch_array($result)) {
			$correo_electronico = mb_strtolower('alumno.'.$row['claveal'].'@'.$config['dominio'], 'UTF-8');

			fwrite($fp, $row['claveal'].";".substr(sha1($row['claveal']),0,8).";".$row['nombre'].";".$row['apellidos'].";".$correo_electronico.";".$config['centro_localidad'].";ES\r\n");
		}
		fclose($fp);
	}

	// Perfil profesor, excepto Administración y Conserjería
	if (!$fp = fopen($directorio.$moodle_profesores, 'w+')) {
		die ("Error: No se puede crear o abrir el archivo ".$directorio.$moodle_profesores);
	}
	else {
		// Cabecera del archivo
		fwrite($fp, "username;password;firstname;lastname;email;city;country\r\n");
		
		// Perfil profesor: Todos los profesores excepto equipo directivo
		$result = mysqli_query($db_con, "SELECT DISTINCT d.nombre, d.idea, d.departamento, d.dni, c.correo FROM departamentos AS d JOIN c_profes AS c ON d.idea = c.idea WHERE d.departamento <> 'Admin' AND d.departamento <> 'Administracion' AND d.departamento <> 'Conserjeria' AND d.departamento <> 'Educador' ORDER BY d.nombre ASC") or die (mysqli_query($db_con));
		while ($row = mysqli_fetch_array($result)) {
			$exp_nombre = explode(', ', $row['nombre']);
			$nombre = trim($exp_nombre[1]);
			$apellidos = trim($exp_nombre[0]);
			$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);
			$correo_electronico = ($row['correo'] == "") ? 'profesor.'.$row['idea'].'@'.mb_strtolower($config['dominio'],'UTF-8') : mb_strtolower($row['correo'], 'UTF-8');

			fwrite($fp, $row['idea'].";".$row['dni'].";".$nombre.";".$apellidos.";".$correo_electronico.";".$config['centro_localidad'].";ES\r\n");
		}

		fclose($fp);
	}

	// GOOGLE SUITE
	$gsuite_profesores	= 'profesores_gsuite.csv';
	$array_correos = array();

	// Perfil profesor, excepto Administración y Conserjería
	if (!$fp = fopen($directorio.$gsuite_profesores, 'w+')) {
		die ("Error: No se puede crear o abrir el archivo ".$directorio.$gsuite_profesores);
	}
	else {
		// Cabecera del archivo
		fwrite($fp, "First Name,Last Name,Email Address,Password,Secondary Email,Work Phone 1,Home Phone 1,Mobile Phone 1,Work address 1,Home address 1,Employee Id,Employee Type,Employee Title,Manager,Department,Cost Center\r\n");
		
		// Perfil profesor: Todos los profesores excepto equipo directivo
		$result = mysqli_query($db_con, "SELECT DISTINCT d.nombre, d.idea, d.departamento, d.dni, c.correo FROM departamentos AS d JOIN c_profes AS c ON d.idea = c.idea WHERE d.departamento <> 'Admin' AND d.departamento <> 'Administracion' AND d.departamento <> 'Conserjeria' AND d.departamento <> 'Educador' ORDER BY d.nombre ASC") or die (mysqli_query($db_con));
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
			$correo = str_ireplace('Mª', 'María', $correo);
			$correo = str_ireplace('M.', 'María', $correo);
			$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
			$correo = mb_strtolower($correo, 'UTF-8');
			$correo = $correo.'@'.$config['dominio'];

			// Si ya existe la cuenta de correo, añadimos el segundo apellido
			if (in_array($correo, $array_correos)) {
				$correo = $primer_nombre.$primer_apellido.$segundo_apellido;
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
	}

	if (isset($mostrarMensaje) && $mostrarMensaje) {
		echo '
		<div class="alert alert-success">
			<h4><span class="fa fa-user-plus fa-lg"></span> Alta masiva de usuarios para Gesuser</h4>
			Se han generado los archivos <strong>'.$gesuser_alumnos.'</strong> y <strong>'.$gesuser_profesores.'</strong> para realizar el 
			alta masiva de usuarios en Gesuser. Puede descargar los archivos desde el menú de la <strong>Administración de la Intranet</strong> 
			en el apartado <strong>Centro TIC</strong>. En un perfil fijo el usuario sólo posee un directorio en el servidor de contenidos 
			que podrá contener todos los archivos que desee y que se mantendrán de un equipo a otro. De este modo se podrá acceder a ellos 
			desde cualquier ordenador del centro. Hay que destacar que no se mantendrán las configuraciones de correo, preferencias del 
			navegador, fondo de escritorio, etc. en los equipos a los que se mueva posteriormente.
		</div>

		<div class="alert alert-success">
			<h4><span class="fa fa-user-plus fa-lg"></span> Alta masiva de usuarios para plataforma Moodle</h4>
			Se han generado los archivos <strong>'.$moodle_alumnos.'</strong> y <strong>'.$moodle_profesores.'</strong> para realizar el 
			alta masiva de usuarios en la plataforma Moodle. Puede descargar los archivos desde el menú de la <strong>Administración de 
			la Intranet</strong> en el apartado <strong>Centro TIC</strong>. Debe recordar a los usuarios que actualicen sus direcciones
			de correo electrónico para la recuperación de la contraseña.
		</div>

		<div class="alert alert-success">
		<h4><span class="fa fa-user-plus fa-lg"></span> Alta masiva de usuarios para G Suite</h4>
		Se ha generado el archivo <strong>'.$gsuite_profesores.'</strong> para realizar el alta masiva de usuarios en G Suite. Los profesores
		disponen de espacio ilimitado en sus cuentas corporativas para el uso docente.
	</div>
		';
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

