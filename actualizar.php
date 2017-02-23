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