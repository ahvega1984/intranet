<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

/*
	@descripcion: Calificaciones de la Evaluación Inicial
	@fecha: 1 de enero de 2017
*/

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Calificaciones de Inicial en tabla notas'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"RENAME TABLE `notas_seg` TO `notas_seg_old`");
	mysqli_query($db_con,"create table `notas_seg` select * from `notas`");
	mysqli_query($db_con,"ALTER TABLE `notas` ADD `notas0` VARCHAR(200) NULL AFTER `claveal`");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Calificaciones de Inicial en tabla notas', NOW())");
	
}


/*
	@descripcion: Calificaciones de la Evaluación Inicial de cursos anteriores (evita errores en estadísticas de evaluación)
	@fecha: 23 de febrero de 2017
*/

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Evaluaciones iniciales en BD anteriores'");
if (! mysqli_num_rows($actua)) {

	for ($i=0; $i <= 6; $i++) { 

		if ($config['db_name_c201'.$i]!="") {
			$db_con = mysqli_connect($config['db_host_c201'.$i], $config['db_user_c201'.$i], $config['db_pass_c201'.$i], $config['db_name_c201'.$i]) or die("<h1>Error " . mysqli_connect_error() . "</h1>"); 
			
			mysqli_query($db_con,"RENAME TABLE ".$config['db_name_c201'.$i].".`notas_seg` TO ".$config['db_name_c201'.$i].".`notas_seg_old`");
			mysqli_query($db_con,"create table ".$config['db_name_c201'.$i].".`notas_seg` select * from ".$config['db_name_c201'.$i].".`notas`");
			mysqli_query($db_con,"ALTER TABLE ".$config['db_name_c201'.$i].".`notas` ADD `notas0` VARCHAR(200) NULL AFTER `claveal`");
		
		}

	}
	
	$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die("<h1>Error " . mysqli_connect_error() . "</h1>"); 

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES (''Evaluaciones iniciales en BD anteriores', NOW())");	
}


/*
	@descripcion: Cambio de codificación a UTF-8 para mejorar el rendimiento de las tablas. Se mantiene el motor MyISAM para obtener
	un mejor rendimiento en las consultas, ya que las relaciones entre las tablas se controlan mediante esta aplicación.	 En una futura
	versión se realizará el cambio a InnoDB.
	@fecha: 23 de febrero de 2017
*/

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Codificacion a UTF-8'");
if (! mysqli_num_rows($actua)) {
	$result_tables = mysqli_query($db_con, "SHOW TABLES FROM `".$config['db_name']."`");
	
	while ($row_table = mysqli_fetch_array($result_tables)) {
		mysqli_query($db_con, "ALTER TABLE `".$row_table[0]."` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
		mysqli_query($db_con, "ALTER TABLE ".$row_table[0]." ENGINE=MyISAM");
		mysqli_query($db_con, "ALTER DATABASE `".$config['db_name']."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	}
	
	mysqli_free_result($result_tables);
	unset($result_tables);
	unset($row_table);

	for ($i = 0; $i < 7; $i++) { 
	
		if ($config['db_name_c201'.$i] != "") {
			$db_con = mysqli_connect($config['db_host_c201'.$i], $config['db_user_c201'.$i], $config['db_pass_c201'.$i], $config['db_name_c201'.$i]) or die("<h1>Error " . mysqli_connect_error() . "</h1>"); 
			
			$result_tables = mysqli_query($db_con, "SHOW TABLES FROM `".$config['db_name_c201'.$i]."`");
			
			while ($row_table = mysqli_fetch_array($result_tables)) {
				mysqli_query($db_con, "ALTER TABLE `".$row_table[0]."` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
				mysqli_query($db_con, "ALTER TABLE ".$row_table[0]." ENGINE=MyISAM");
				mysqli_query($db_con, "ALTER DATABASE `".$config['db_name_c201'.$i]."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
			}
			
			mysqli_free_result($result_tables);
			unset($result_tables);
			unset($row_table);
		
		}

	}
	
	$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die("<h1>Error " . mysqli_connect_error() . "</h1>");
	mysqli_query($db_con,"SET NAMES 'utf8'");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Codificacion a UTF-8', NOW())");
}

/*
	@descripcion: Ampliación de las columnas de calificaciones en tabla nota
	@fecha: 22 de abril de 2017
*/

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Ancho columnas en tabla notas'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE  `notas` 
		CHANGE  `notas0`  `notas0` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
		CHANGE  `notas1`  `notas1` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
		CHANGE  `notas2`  `notas2` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
		CHANGE  `notas3`  `notas3` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
		CHANGE  `notas4`  `notas4` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Ancho columnas en tabla notas', NOW())");
	
}

/*
	@descripcion: Modificación asuntos reiteración de faltas leves
	@fecha: 05 de mayo de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Reiteracion de faltas leves'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"UPDATE `Fechoria` SET `ASUNTO` = 'Reiteración de cinco o más faltas leves' WHERE `ASUNTO` = 'Reiteración en el mismo trimestre de cinco o más faltas leves';");
	mysqli_query($db_con,"UPDATE `listafechorias` SET `fechoria` = 'Reiteración de cinco o más faltas leves' WHERE `ID` = 41;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Reiteracion de faltas leves', NOW())");
}

/*
	@descripcion: Ancho de las columnas de horas en la tabla reservas_hor
	@fecha: 13 de mayo de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Ancho columna tabla reservas_hor'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE `reservas_hor` CHANGE `hora1` `hora1` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `hora2` `hora2` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `hora3` `hora3` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `hora4` `hora4` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `hora5` `hora5` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `hora6` `hora6` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `hora7` `hora7` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
	mysqli_query($db_con,"ALTER TABLE `reservas_hor` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT AFTER `servicio`, ADD PRIMARY KEY (`id`)");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Ancho columna tabla reservas_hor', NOW())");
}


