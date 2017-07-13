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

/*
	@descripcion: Ancho de las columnas en la tabla morosos
	@fecha: 31 de mayo de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Ancho columna tabla morosos'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE `morosos` CHANGE `apellidos` `apellidos` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `nombre` `nombre` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `ejemplar` `ejemplar` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Ancho columna tabla morosos', NOW())");
}

/*
	@descripcion: Ancho de las columnas en la tabla lectores
	@fecha: 02 de junio de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Ancho columna tabla lectores'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE  `biblioteca_lectores` CHANGE  `Apellidos`  `Apellidos` VARCHAR( 96 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , CHANGE  `Nombre`  `Nombre` VARCHAR( 96 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , CHANGE  `Grupo`  `Grupo` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Ancho columna tabla lectores', NOW())");
}


/*
	@descripcion: Ajustes en tabla de matrículas #1.
	@fecha: 08 de junio de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Nueva columna tabla matriculas_bach'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE  `matriculas_bach` ADD  `optativa2b9` TINYINT( 1 ) NULL");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Nueva columna tabla matriculas_bach', NOW())");
}

/*
	@descripcion: Ajustes en tabla de matrículas #2.
	@fecha: 08 de julio de 2017
*/
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Ajustes matriculas'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE  `matriculas_bach` ADD  `opt_aut28` INT( 1 ) NULL");

	// Garantizar compatibilidad con viejo sistema de asignaturas. Eliminar con el nuevo curso 2017-18 y los archivos antiguos de asignaturas.

	if (file_exists("admin/matriculas/config.php")) {}
	else{
	
		include("admin/matriculas/asignaturas.php");
    	include("admin/matriculas/asignaturas_bach.php");
    	

    	 $asignaturas = file("admin/matriculas/asignaturas.php");
	     $asignaturas_bach = file("admin/matriculas/asignaturas_bach.php");

	    // Abrir el archivo:
	     $archivo = fopen("admin/matriculas/config.php", "w+");
		     	fwrite($archivo, "<?php \r\n\r\n// CONFIGURACIÓN MÓDULO DE MATRICULACIÓN\r\n");
				fwrite($archivo, "\$config['matriculas']['fecha_inicio']\t= '2017-06-05';\r\n");	
				fwrite($archivo, "\$config['matriculas']['fecha_fin']\t= '2017-06-22';\r\n");    
			// Guardar los cambios en el archivo:
	     foreach( $asignaturas as $linea )
	     	if (stristr($linea, "('INTRANET_DIRECTORY')")==TRUE) {
	     	}
	     	else{
	     		fwrite($archivo, $linea);
	     	}
	     		fwrite($archivo, "\n\n");
	     foreach( $asignaturas_bach as $linea2 )
	     	if (stristr($linea2, "('INTRANET_DIRECTORY')")==TRUE) {
	     		$linea2="<?php\n";	
		        fwrite($archivo, $linea2);
	     	}
	     	elseif(stristr($linea2, "?>")==TRUE)
	     	{}
	     	else{
	     		fwrite($archivo, $linea2);
	     	}	

	     	for ($i=1; $i < 5; $i++) { 
    			${c_.$i} = count(${opt.$i});
    		}
    		for ($i=1; $i < 4; $i++) { 
    			${ca_.$i} = count(${a.$i});
    		}
    		for ($i=1; $i < 5; $i++) { 
    			${c_1.$i} = count(${opt1.$i});
    		}
    		for ($i=1; $i < 5; $i++) { 
    			${c_2.$i} = count(${opt2.$i});
    		}
    			$c_aut2 = count($opt_aut2);

	     		fwrite($archivo, "\n\n"); 

				fwrite($archivo, "\$it11\t= array('Bachillerato de Ciencias','Arquitectura e Ingeniería y Ciencias','Matemáticas','Física y Química','Dibujo Técnico','Tecnología Industrial');\r\n");
				fwrite($archivo, "\$it12\t= array('Bachillerato de Ciencias','Ciencias y Ciencias de la Salud','Matemáticas','Física y Química','Biología y Geología','Anatomía Aplicada');\r\n");
				fwrite($archivo, "\$it13\t= array('Bachillerato de Humanidades','Humanidades','Latín','Patrimonio Cultural y Artístico','Griego','TIC');\r\n");
				fwrite($archivo, "\$it14\t= array('Bachillerato de Ciencias Sociales','Ciencias Sociales y Jurídicas','Matemáticas de las Ciencias Sociales II','Economía','Cultura Emprendedora','TIC');\r\n");

				fwrite($archivo, "\n\n");    			

			    fwrite($archivo, "\$count_1\t= '$c_1';\r\n");
				fwrite($archivo, "\$count_2\t= '$c_2';\r\n");
				fwrite($archivo, "\$count_3\t= '$c_3';\r\n");
				fwrite($archivo, "\$count_4\t= '$c_4';\r\n");

				fwrite($archivo, "\$count_a1\t= '$ca_1';\r\n");
				fwrite($archivo, "\$count_a2\t= '$ca_2';\r\n");
				fwrite($archivo, "\$count_a3\t= '$ca_3';\r\n");

				fwrite($archivo, "\$count_11\t= '$c_11';\r\n");
				fwrite($archivo, "\$count_12\t= '$c_12';\r\n");
				fwrite($archivo, "\$count_13\t= '$c_13';\r\n");
				fwrite($archivo, "\$count_14\t= '$c_14';\r\n");

				fwrite($archivo, "\$count_21\t= '$c_21';\r\n");
				fwrite($archivo, "\$count_22\t= '$c_22';\r\n");
				fwrite($archivo, "\$count_23\t= '$c_23';\r\n");
				fwrite($archivo, "\$count_24\t= '$c_24';\r\n");

				fwrite($archivo, "\$count_2b2\t= '$c_aut2';\r\n");			

				fwrite($archivo, "\n\n?>\n");

     fclose($archivo);
	}

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Ajustes matriculas', NOW())");
}

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

