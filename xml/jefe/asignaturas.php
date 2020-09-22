<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

				// Vaciamos o borramos tablas
				mysqli_query($db_con, "TRUNCATE TABLE calificaciones");
				mysqli_query($db_con, "drop table materias_seg");
				mysqli_query($db_con, "drop table asignaturas_seg");
				mysqli_query($db_con, "create table materias_seg select * from materias");
				mysqli_query($db_con, "create table asignaturas_seg select * from asignaturas");
				mysqli_query($db_con, "TRUNCATE TABLE asignaturas");
				mysqli_query($db_con, "drop table materias");

				// Crear la tabla temporal donde guardar todas las asignaturas de todos los gruposy la tabla del sistema de calificaciones
				$crear = "CREATE TABLE IF NOT EXISTS `materias_temp` (
				`CODIGO` varchar( 10 ) default NULL ,
			 	`NOMBRE` varchar( 64 ) default NULL ,
			 	`ABREV` varchar( 10 ) default NULL ,
				`CURSO` varchar( 128 ) default NULL,
				`GRUPO` varchar( 255 ) default NULL
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_general_ci" ;
				mysqli_query($db_con, $crear);
				mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `calificaciones_temp` (
			  `codigo` varchar(5) CHARACTER SET latin1 NOT NULL DEFAULT '',
			  `nombre` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
			  `abreviatura` varchar(4) CHARACTER SET latin1 DEFAULT NULL,
			  `orden` varchar(4) CHARACTER SET latin1 DEFAULT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_general_ci");

				// Claveal primaria e índice
				mysqli_query($db_con, "ALTER TABLE  `materias_temp` ADD  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
				mysqli_query($db_con, "ALTER TABLE  `materias_temp` ADD INDEX (  `CODIGO` )");

				mysqli_query($db_con, "ALTER TABLE  `calificaciones_temp` ADD INDEX (  `CODIGO` )");
				$num="";
				// Recorremos directorio donde se encuentran los ficheros y aplicamos la plantilla.
				if ($handle = opendir('../exporta')) {
					while (false !== ($file = readdir($handle))) {
						if ($file != "." && $file != ".." && $file != "index.php" ) {
							//echo $file."<br />";
							$num+=1;
							$doc = new DOMDocument('1.0', 'UTF-8');

							/*Cargo el XML*/
							$doc->load( '../exporta/'.$file );

							/*Obtengo el nodo MATERIA del XML
							 a traves del metodo getElementsByTagName,
							 este nos entregara una lista de todos los
							 nodos encontrados */
							$cursos = $doc->getElementsByTagName( "D_OFERTAMATRIG");
							$cur = $cursos->item(0)->nodeValue;

							$unidades = $doc->getElementsByTagName( "T_NOMBRE");
							$unidad = $unidades->item(0)->nodeValue;

							$materias = $doc->getElementsByTagName( "MATERIA" );

							/*Al ser $materias una lista de nodos
							 lo puedo recorrer y obtener todo
							 su contenido*/
							foreach( $materias as $materia )
							{
								$codigos = $materia->getElementsByTagName( "X_MATERIAOMG" );

								/*Obtengo el valor del primer elemento 'item(0)'
								 de la lista $codigos.
								 Si existiera un atriburto en el nodo para obtenerlo
								 usaria $codigos->getAttribute('atributo');
								 */
								$codigo = $codigos->item(0)->nodeValue;
								$codigo = trim($codigo);
								$nombres = $materia->getElementsByTagName( "D_MATERIAC" );
								$nombre = $nombres->item(0)->nodeValue;
								$nombre = trim($nombre);
								$nombre = str_replace("  ", " ", $nombre);
								$abrevs = $materia->getElementsByTagName( "T_ABREV" );
								$abrev = $abrevs->item(0)->nodeValue;
								$abrev = trim($abrev);
								mysqli_query($db_con, "INSERT INTO  `materias_temp` (
								`CODIGO` ,
								`NOMBRE` ,
								`ABREV` ,
								`CURSO` ,
								`GRUPO`
								)
								VALUES ('$codigo',  '$nombre',  '$abrev',  '$cur', '$unidad')");
							}

								//
								if ($num=="3") {
									///*Obtengo el nodo Calificación del XML
									//a traves del metodo getElementsByTagName,
									//este nos entregara una lista de todos los
									//nodos encontrados */
									//
									$calificaciones = $doc->getElementsByTagName( "CALIFICACION" );

									/*Al ser $calificaciones una lista de nodos
									 lo puedo recorrer y obtener todo
									 su contenido*/
									foreach( $calificaciones as $calificacion )
									{
										/*Obtengo el valor del primer elemento 'item(0)'
										 de la lista $codigos.
										 Si existiera un atributo en el nodo para obtenerlo
										 usaria $codigos->getAttribute('atributo');
										 */
										$codigos0 = $calificacion->getElementsByTagName( "X_CALIFICA" );
										$codigo0 = $codigos0->item(0)->nodeValue;
										$nombres0 = $calificacion->getElementsByTagName( "D_CALIFICA" );
										$nombre0 = $nombres0->item(0)->nodeValue;
										$abrevs0 = $calificacion->getElementsByTagName( "T_ABREV" );
										$abrev0 = $abrevs0->item(0)->nodeValue;
										$ordenes0 = $calificacion->getElementsByTagName( "N_ORDEN" );
										$orden0 = $ordenes0->item(0)->nodeValue;
										mysqli_query($db_con, "INSERT INTO  `calificaciones_temp` VALUES ('$codigo0',  '$nombre0',  '$abrev0',  '$orden0')");
									}
								}
							}
						}
					unlink('../exporta/'.$file);
					closedir($handle);
				}
				else{
					echo '<div class="alert alert-danger alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCIÓN:</h5>
			No se han colocado los ficheros de Evaluación de Séneca en el directorio exporta/.<br> Descárgalos de Séneca y colócalos allí antes de continuar.
			</div><br />
			<div align="center">
			  <input type="button" value="Volver atrás" name="boton" onClick="history.back(2)" class="btn btn-primary" />
			</div>';
					exit();
				}

				//Tabla calificaciones
				mysqli_query($db_con, "insert into calificaciones select distinct codigo, nombre, abreviatura, orden from calificaciones_temp");

				//Creamos tabla materias y arreglamos problema de codificación.

				mysqli_query($db_con, "create table materias select * from materias_temp");
				mysqli_query($db_con, "ALTER TABLE  `materias` DROP  `id`");
				mysqli_query($db_con, "ALTER TABLE  `materias` ADD  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
				//mysqli_query($db_con, "ALTER TABLE `materias` DROP `GRUPO`");

				$pr1 = mysqli_query($db_con, "select * from materias");
				while ($pr10 = mysqli_fetch_array($pr1)){
					$nombr = $pr10[1];
					$abre = $pr10[2];
					$cu = $pr10[3];
					$id = $pr10[5];
					mysqli_query($db_con, "update materias set nombre = '$nombr', curso = '$cu', abrev = '$abre' where id = '$id'");
				}


				// Refuerzos en la tabla de materias
				$rf = mysqli_query($db_con,"select distinct c_asig, asig, a_asig, a_grupo from horw where asig like 'Refuerzo%' and a_grupo not like ''");

				while ($ref = mysqli_fetch_array($rf)) {
					$cr = mysqli_query($db_con,"select distinct curso from alma where unidad = '$ref[3]'");
					$crs = mysqli_fetch_array($cr);
					mysqli_query($db_con, "INSERT INTO  `materias` (
					`CODIGO` ,
					`NOMBRE` ,
					`ABREV` ,
					`CURSO` ,
					`GRUPO`
					)
					VALUES ('$ref[0]',  '$ref[1]',  '$ref[2]',  '$crs[0]', '$ref[3]')");
				}

				//Borramos tablas temporales
				mysqli_query($db_con, "drop table materias_temp");
				mysqli_query($db_con, "drop table calificaciones_temp");

				// Depuramos los códigos de las asignaturas eliminando duplicados y creamos tabla definitiva asignaturas.
				$crear = "insert into asignaturas select distinct CODIGO, NOMBRE, ABREV, CURSO from materias order by CODIGO";
				mysqli_query($db_con, $crear) or die ("Error al importar materias en tabla asignaturas: ".mysqli_error($db_con));

				// Añadimos excepciones
				mysqli_query($db_con,"INSERT INTO `asignaturas` (`CODIGO`, `NOMBRE`, `ABREV`, `CURSO`) VALUES
					('2', 'Tutoría con alumnos (ESO)', 'TALU', '1º de E.S.O.'),
					('2', 'Tutoría con alumnos (ESO)', 'TALU', '2º de E.S.O.'),
					('2', 'Tutoría con alumnos (ESO)', 'TALU', '3º de E.S.O.'),
					('2', 'Tutoría con alumnos (ESO)', 'TALU', '4º de E.S.O.'),
					('118', 'Tutoría para tareas administrativas', 'TTA', '1º de E.S.O.'),
					('118', 'Tutoría para tareas administrativas', 'TTA', '2º de E.S.O.'),
					('118', 'Tutoría para tareas administrativas', 'TTA', '3º de E.S.O.'),
					('118', 'Tutoría para tareas administrativas', 'TTA', '4º de E.S.O.'),
					('356', 'Tutoría de atención personalizada al alumnado y familia (ESO)', 'TAPAF', '1º de E.S.O.'),
					('356', 'Tutoría de atención personalizada al alumnado y familia (ESO)', 'TAPAF', '2º de E.S.O.'),
					('356', 'Tutoría de atención personalizada al alumnado y familia (ESO)', 'TAPAF', '3º de E.S.O.'),
					('356', 'Tutoría de atención personalizada al alumnado y familia (ESO)', 'TAPAF', '4º de E.S.O.'),
					('117', 'Tutoría de Atención a Padres y Madres', 'TAPM', '1º de E.S.O.'),
					('117', 'Tutoría de Atención a Padres y Madres', 'TAPM', '2º de E.S.O.'),
					('117', 'Tutoría de Atención a Padres y Madres', 'TAPM', '3º de E.S.O.'),
					('117', 'Tutoría de Atención a Padres y Madres', 'TAPM', '4º de E.S.O.'),
					('861', 'Tutoría P.M.A.R. (Orientador/a)', 'TPMAR', '2º de E.S.O.'),
					('861', 'Tutoría P.M.A.R. (Orientador/a)', 'TPMAR', '3º de E.S.O.'),
					('136', 'Pedagogía Terapéutica', 'PTER', '1º de E.S.O.'),
					('136', 'Pedagogía Terapéutica', 'PTER', '2º de E.S.O.'),
					('136', 'Pedagogía Terapéutica', 'PTER', '3º de E.S.O.'),
					('136', 'Pedagogía Terapéutica', 'PTER', '4º de E.S.O.'),
					('21', 'Refuerzo Pedagógico', 'RPED', '1º de E.S.O.'),
					('21', 'Refuerzo Pedagógico', 'RPED', '2º de E.S.O.'),
					('21', 'Refuerzo Pedagógico', 'RPED', '3º de E.S.O.'),
					('21', 'Refuerzo Pedagógico', 'RPED', '4º de E.S.O.')"
				);

				$result_bach = mysqli_query($db_con, "SELECT `nomcurso` FROM `cursos` WHERE `nomcurso` LIKE '%Bachillerato%'");
				while ($row_bach = mysqli_fetch_array($result_bach)) {
					$nomcurso = $row_bach['nomcurso'];
					mysqli_query($db_con, "INSERT INTO `asignaturas` (`CODIGO`, `NOMBRE`, `ABREV`, `CURSO`) VALUES ('118', 'Tutoría para tareas administrativas', 'TTA', '".$nomcurso."')");
					mysqli_query($db_con, "INSERT INTO `asignaturas` (`CODIGO`, `NOMBRE`, `ABREV`, `CURSO`) VALUES ('117', 'Tutoría de Atención a Padres y Madres', 'TAPM', '".$nomcurso."')");
				}

				$result_fpb = mysqli_query($db_con, "SELECT `nomcurso` FROM `cursos` WHERE `nomcurso` LIKE '%F.P.B.%'");
				while ($row_fpb = mysqli_fetch_array($result_fpb)) {
					$nomcurso = $row_fpb['nomcurso'];
					mysqli_query($db_con, "INSERT INTO `asignaturas` (`CODIGO`, `NOMBRE`, `ABREV`, `CURSO`) VALUES ('820', 'Tutoría con alumnos FPB', 'TAF', '".$nomcurso."')");
					mysqli_query($db_con, "INSERT INTO `asignaturas` (`CODIGO`, `NOMBRE`, `ABREV`, `CURSO`) VALUES ('117', 'Tutoría de Atención a Padres y Madres', 'TAPM', '".$nomcurso."')");
				}

				echo '<br />
				<div class="alert alert-success alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ASIGNATURAS y CALIFICACIONES:</h5> Los datos se han introducido correctamente en la Base de Datos.
			</div><br />';


				// Alumnos con pendientes
				include("pendientes.php");
