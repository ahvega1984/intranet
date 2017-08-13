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
	@descripcion: Elimina actualizacion tabla de mensajes
	@fecha: 13 de agosto de 2017
*/
$actua = mysqli_query($db_con, "SELECT d FROM actualizacion WHERE modulo = 'Actualizacion tabla mensajes'");
if (mysqli_num_rows($actua)) {
	$row_actua = mysqli_fetch_array($actua);

	mysqli_query($db_con, "ALTER TABLE `mens_profes` DROP `esTarea`, DROP `estadoTarea`;");
	mysqli_query($db_con, "DELETE FROM actualizacion WHERE d = ".$row_actua['d']." LIMIT 1");

	unset($row_actua);
}
