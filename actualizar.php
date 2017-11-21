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
	@fecha: 21 de Noviembre de 2017
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
	@fecha: 21 de Noviembre de 2017
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