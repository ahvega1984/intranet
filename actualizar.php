<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

/*
	@descripcion: Tabla para control de faltas de asistencia
	@fecha: 13 de julio de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla de control de faltas'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `control_faltas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profesor` int(11) NOT NULL,
  `unidad` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `dia` tinyint(1) NOT NULL,
  `hora` tinyint(1) NOT NULL,
  `fecha` date NOT NULL,
  `asignatura` varchar(12) COLLATE utf8_spanish_ci NOT NULL,
  `numero` tinyint(2) NOT NULL,
  `hoy` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla de control de faltas', NOW())");
}

/*
	@descripcion: Actualizacion tabla de noticias
	@fecha: 31 de julio de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Actualizacion tabla noticias'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `noticias` CHANGE `id` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `slug` `titulo` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `content` `contenido` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `contact` `autor` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `timestamp` `fechapub` DATETIME NOT NULL, CHANGE `clase` `categoria` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `fechafin` `fechafin` DATE NULL DEFAULT NULL, CHANGE `pagina` `pagina` CHAR(2) NOT NULL;");
	mysqli_query($db_con, "ALTER TABLE `noticias` CHANGE `fechafin` `fechafin` DATE NULL DEFAULT NULL AFTER `fechapub`;");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Actualizacion tabla noticias', NOW())");
}

/*
	@descripcion: Creación tabla tareas
	@fecha: 9 de septiembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla tareas'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con, "DROP TABLE IF EXISTS `tareas`");
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `tareas` (
		`id` int(10) unsigned AUTO_INCREMENT PRIMARY KEY,
		`idea` varchar(12) NOT NULL,
		`titulo` tinytext NOT NULL,
		`tarea` text NOT NULL,
		`estado` tinyint(1) unsigned NOT NULL DEFAULT '0',
		`fechareg` datetime NOT NULL,
		`prioridad` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla tareas', NOW())");
}

mysqli_free_result($actua);

/*
	@descripcion: Corrección estructura tabla evaluaciones_actas
	@fecha: 5 de octubre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Estructura tabla evaluaciones_actas'");
if (! mysqli_num_rows($actua)) {

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM evaluaciones_actas WHERE Field = 'asistentes'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `evaluaciones_actas` ADD `asistentes` VARCHAR(255) NULL AFTER `texto_acta`");
		mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Estructura tabla evaluaciones_actas', NOW())");
	}
	
}

mysqli_free_result($actua);

/*
	@descripcion: Ampliación columna asistentes en tabla evaluaciones_actas
	@fecha: 18 de octubre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Columna asistentes tabla evaluaciones_actas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `evaluaciones_actas` CHANGE `asistentes` `asistentes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Columna asistentes tabla evaluaciones_actas', NOW())");
}

mysqli_free_result($actua);

/*
	@descripcion: Ampliación de caracteres en la columna "página" para incluir sistema de documentación permanente
	@fecha: 21 de octubre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Estructura tabla Noticias'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE  `noticias` CHANGE  `pagina`  `pagina` CHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Estructura tabla Noticias', NOW())");
}

mysqli_free_result($actua);

/*
	@descripcion: Cambio del tipo de datos (varchar a time) de los campos hora_inicio y hora_fin
	@fecha: 21 de noviembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Estructura tabla Tramos'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE  `tramos` CHANGE  `hora_inicio`  `hora_inicio` TIME NOT NULL ,
CHANGE  `hora_fin`  `hora_fin` TIME NOT NULL");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Estructura tabla Tramos', NOW())");
}

mysqli_free_result($actua);


/*
	@descripcion: Eliminado archivos de exportación. A partir de ahora se genera y se fuerza la descarga. De esta manera evitamos que queden los archivos publicados en la red
	@fecha: 21 de noviembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Exportar usuarios TIC'");
if (! mysqli_num_rows($actua)) {
	if (file_exists(INTRANET_DIRECTORY.'/xml/jefe/TIC/download.php')) unlink(INTRANET_DIRECTORY.'/xml/jefe/TIC/download.php');
	if (file_exists(INTRANET_DIRECTORY.'/xml/jefe/TIC/alumnos.txt')) unlink(INTRANET_DIRECTORY.'/xml/jefe/TIC/alumnos.txt');
	if (file_exists(INTRANET_DIRECTORY.'/xml/jefe/TIC/profesores.txt')) unlink(INTRANET_DIRECTORY.'/xml/jefe/TIC/profesores.txt');
	if (file_exists(INTRANET_DIRECTORY.'/xml/jefe/TIC/alumnos_moodle.txt')) unlink(INTRANET_DIRECTORY.'/xml/jefe/TIC/alumnos_moodle.txt');
	if (file_exists(INTRANET_DIRECTORY.'/xml/jefe/TIC/profesores_moodle.txt')) unlink(INTRANET_DIRECTORY.'/xml/jefe/TIC/profesores_moodle.txt');
	if (file_exists(INTRANET_DIRECTORY.'/xml/jefe/TIC/profesores_gsuite.csv')) unlink(INTRANET_DIRECTORY.'/xml/jefe/TIC/profesores_gsuite.csv');

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Exportar usuarios TIC', NOW())");
}

/*
	@descripcion: Eliminado archivo de configuración de Trendoo
	@fecha: 2 de diciembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminado archivo config.php de Trendoo'");
if (! mysqli_num_rows($actua)) {
	if (file_exists(INTRANET_DIRECTORY.'/lib/trendoo/config.php')) unlink(INTRANET_DIRECTORY.'/lib/trendoo/config.php');

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminado archivo config.php de Trendoo', NOW())");
}

/*
	@descripcion: Nuevo campo en tabla reg_principal
	@fecha: 7 de diciembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Nuevo campo en tabla reg_principal'");
if (! mysqli_num_rows($actua)) {

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM reg_principal WHERE Field = 'tutorlegal'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `reg_principal` ADD `tutorlegal` VARCHAR(255) NULL");
		mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Nuevo campo en tabla reg_principal', NOW())");
	}
}

/*
	@descripcion: Nuevo campo en tabla Absentismo
	@fecha: 10 de diciembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Nuevo campo en tabla Absentismo'");
if (! mysqli_num_rows($actua)) {

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM absentismo WHERE Field = 'fecha_registro'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `absentismo` ADD `fecha_registro` DATE NOT NULL AFTER `serv_sociales`");
		mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Nuevo campo en tabla Absentismo', NOW())");
	}
}


/*
	@descripcion: Registro de agente de usuario en tabla reg_principal e reg_intranet
	@fecha: 16 de diciembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Registro agente de usuario'");
if (! mysqli_num_rows($actua)) {

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM reg_principal WHERE Field = 'useragent'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `reg_principal` ADD `useragent` VARCHAR(255) NULL");
	}

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM reg_intranet WHERE Field = 'useragent'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `reg_intranet` ADD `useragent` VARCHAR(255) NULL");
	}

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Registro agente de usuario', NOW())");
}

/*
	@descripcion: Eliminado archivo salir.php para evitar filtro de contenidos de la Junta.
	@fecha: 21 de diciembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminado archivo salir.php'");
if (! mysqli_num_rows($actua)) {

	if (file_exists(INTRANET_DIRECTORY.'/salir.php')) unlink(INTRANET_DIRECTORY.'/salir.php');

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminado archivo salir.php', NOW())");
}

/*
	@descripcion: Añadido campo para segundo factor de autenticación en tabla c_profes
	@fecha: 27 de diciembre de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Módulo TOTP'");
if (! mysqli_num_rows($actua)) {

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM c_profes WHERE Field = 'totp_secret'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `c_profes` ADD `totp_secret` CHAR(16) NULL");
	}

	// Las siguientes lineas solucionan un error en actualización anterior
	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM reg_principal WHERE Field = 'totp_secret'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `reg_principal` DROP `totp_secret`;");
	}

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Módulo TOTP', NOW())");
}

/*
	@descripcion: Cambio nombre de actividad Servicio de guardia
	@fecha: 8 de enero de 2018
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Nombre actividad Servicio de guardia'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con, "UPDATE `horw` SET `asig` = 'Servicio de guardia (No Lectiva)' WHERE `c_asig` = '25' AND `asig` = 'Servicio de guardia'");
	mysqli_query($db_con, "UPDATE `horw_faltas` SET `asig` = 'Servicio de guardia (No Lectiva)' WHERE `c_asig` = '25' AND `asig` = 'Servicio de guardia'");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Nombre actividad Servicio de guardia', NOW())");
}

/*
	@descripcion: Se añade columna con el número de Seguridad Social del alumno
	@fecha: 10 de enero de 2018
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Número seguridad social'");
if (! mysqli_num_rows($actua)) {

	$result_update = mysqli_query($db_con, "SHOW COLUMNS FROM alma WHERE Field = 'SEGSOCIAL'");
	if (! mysqli_num_rows($result_update)) {
		mysqli_query($db_con, "ALTER TABLE `alma` ADD `SEGSOCIAL` CHAR(12) NULL ;");
	}

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Número seguridad social', NOW())");
}

/*
	@descripcion: Nuevo módulo de incidencias TIC
	@fecha: 26 de enero de 2018
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Nuevo módulo incidencias TIC'");
if (! mysqli_num_rows($actua)) {

	// Creamos la tabla de inventario de material TIC
	mysqli_query($db_con, "DROP TABLE `inventario_tic`");
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `inventario_tic` (
	`numregistro` varchar(30) NOT NULL,
	`numserie` varchar(30) DEFAULT NULL,
	`tipo` varchar(80) NOT NULL,
	`articulo` int(6) unsigned NOT NULL,
	`proveedor` int(6) unsigned NOT NULL,
	`expediente` varchar(30) DEFAULT NULL,
	`procedencia` varchar(80) DEFAULT NULL,
	`localizacion` varchar(80) DEFAULT NULL,
	`adscripcion` varchar(80) DEFAULT NULL,
	`fechaalta` date DEFAULT NULL,
	`fechabaja` date DEFAULT NULL,
	`motivobaja` text,
	`estado` varchar(30) NOT NULL,
	`descripcion` text,
	`dotacionapae` text,
	`observaciones` text,
	`marcadobaja` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`numregistro`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;");

	// Creamos la nueva tabla de incidencias TIC
	mysqli_query($db_con, "DROP TABLE `incidencias_tic`");
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `incidencias_tic` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`fecha` date NOT NULL,
	`solicitante` varchar(12) NOT NULL,
	`dependencia` varchar(30) DEFAULT NULL,
	`problema` smallint(3) unsigned NOT NULL,
	`descripcion` text,
	`estado` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`fecha_estado` date NULL,
	`numincidencia` char(10) DEFAULT NULL,
	`resolucion` text NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;");

	// Migración de datos de la tabla partestic
	$result = mysqli_query($db_con, "SELECT `fecha`, `profesor`, `descripcion`, `estado`, `nincidencia` FROM `partestic` ORDER BY `parte` ASC") or die (mysqli_error($db_con));
	while($row = mysqli_fetch_array($result)) {
		$result_profesor = mysqli_query($db_con, "SELECT `idea` FROM `departamentos` WHERE `nombre` = '".$row['profesor']."'");
		$row_profesor = mysqli_fetch_array($result_profesor);

		if ($row['estado'] != 'solucionado') $migracion_estado = 1;
		else $migracion_estado = 3;

		if ($_SERVER['SERVER_NAME'] == 'iesantoniomachado.es' || $_SERVER['SERVER_NAME'] == 'iesbahiamarbella.es') {
			if (stristr($row['descripcion'], ':') == true) {
				$exp_aula_descripcion = explode(':', $row['descripcion']);
				$dependencia = trim($exp_aula_descripcion[0]);
				$descripcion = trim($exp_aula_descripcion[1]);
			}
			else {
				$dependencia = "";
				$descripcion = $row['descripcion'];
			}

			if (stristr($descripcion, '|') == true) {
				$exp_resolucion_descripcion = explode('|', $descripcion);
				$resolucion = trim($exp_resolucion_descripcion[1]);
				$descripcion = trim($exp_resolucion_descripcion[0]);
				$migracion_estado = 2;
			}
			else {
				$resolucion = "";
			}
		}
		else {
			$dependencia = "";
			$descripcion = $row['descripcion'];
			$resolucion = "";
		}
		
		mysqli_query($db_con, "INSERT INTO `incidencias_tic` (`fecha`, `solicitante`, `dependencia`, `problema`, `descripcion`, `estado`, `numincidencia`, `resolucion`) VALUES ('".$row['fecha']."', '".$row_profesor['idea']."', '".$dependencia."', 901, '".$descripcion."', $migracion_estado, '".$row['nincidencia']."', '".$resolucion."')");
	}

	// Eliminamos tabla antigua
	mysqli_query($db_con, "DROP TABLE `partestic`");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Nuevo módulo incidencias TIC', NOW())");
}

/*
	@descripcion: Modificación de la tabla tutoría para registrar intervenciones sobre el grupo
	@fecha: 12 de febrero de 2018
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Modificación tabla tutoria'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `tutoria` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `claveal` `claveal` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `apellidos` `apellidos` VARCHAR(42) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `nombre` `nombre` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `jefatura` `jefatura` TINYINT(1) NOT NULL DEFAULT '0';");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Modificación tabla tutoria', NOW())");
}
