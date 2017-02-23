<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------
	obtenerIdCurso: La función devuelve el ID del curso
 * ----------------------------------------------------------------------*/
function obtenerIdCurso($curso) {
	include_once '../../config.php';
	$result = mysqli_query($db_con, "SELECT idcurso FROM cursos WHERE nomcurso='$curso' LIMIT 1");
	$idcurso = mysqli_fetch_array($result);
	
	if(!$curso) $idcurso[0] = 'NULL';
		
	return $idcurso[0];
}


/* ----------------------------------------------------------------------
	obtenerIdUnidad: La función devuelve el ID de la unidad
 * ----------------------------------------------------------------------*/
function obtenerIdUnidad($unidad) {
include_once '../../config.php';
	$result = mysqli_query($db_con, "SELECT idunidad FROM unidades WHERE nomunidad='$unidad' LIMIT 1");
	$idunidad = mysqli_fetch_array($result);
	
	if(!$unidad) $idunidad[0] = 'NULL';
		
	return $idunidad[0];
}


/* ----------------------------------------------------------------------
	limpiarNombreDepartamento: La función elimina caracteres y devuelve
	el nombre del departamento
 * ----------------------------------------------------------------------*/
function limpiarNombreDepartamento($departamento) {
	$departamento = str_replace("(Inglés)","",$departamento);
	$departamento = str_replace("(Francés)","",$departamento);
	$departamento = str_replace("(Alemán)","",$departamento);
	$departamento = str_replace("P.E.S.","",$departamento);
	$departamento = str_replace("P.T.F.P","",$departamento);
	$departamento = str_replace("(Secundaria)","",$departamento);
	$departamento = str_replace("Laboral Religión (Sec-Ere) Jor.Completa","Religión",$departamento);
	for($i=1;$i<21;$i++) {
		$departamento = str_replace("Contr. Lab. Religión (Sec-Ere) $i Horas","Religión",$departamento);
	}
	$departamento = trim($departamento);
	$departamento = rtrim($departamento,'.');
	
	return $departamento;
}


/* ----------------------------------------------------------------------
	obtenerIdDepartamento: La función devuelve el ID del departamento
 * ----------------------------------------------------------------------*/
function obtenerIdDepartamento($departamento) {
	include_once '../../config.php';
	
	// Limpiamos el nombre del departamento
	$departamento = limpiarNombreDepartamento($departamento);
	
	$result = mysqli_query($db_con, "SELECT iddepartamento FROM departamentos WHERE nomdepartamento='$departamento' LIMIT 1");
	$iddepartamento = mysqli_fetch_array($result);
	
	if(!$departamento) $iddepartamento[0] = 'NULL';
		
	return $iddepartamento[0];
}


/* ----------------------------------------------------------------------
	importarDatos: La función crea la tabla de alumnos, sistemas de
	calificaciones y relación de matrículas de los alumnos
 * ----------------------------------------------------------------------*/

function importarDatos($db_con) {
	$ExpGenHor = $_FILES['ExpGenHor']['tmp_name'];	
	$curso_escolar = $_POST['curso_escolar'];      	
    
	 // Cargamos el archivo XML
	$xml = simplexml_load_file($ExpGenHor);	
	// Comprobamos que se trata del archivo que necesitamos
	if($xml->attributes()->modulo != "HORARIOS" || $xml->attributes()->tipo != "E") {
		echo '<div class="row">
		  <div class="col-md-12">
		    <div class="alert alert-danger">
		      <span class="fa fa-times-circle fa-lg"></span> El archivo seleccionado no es correcto.
		    </div>
		  </div>
		</div>';
	}
	elseif(($curso =$xml->BLOQUE_DATOS->grupo_datos[0]->dato[0]) != substr($curso_escolar,0,4)) {
		echo '<div class="row">
		  <div class="col-md-12">
		    <div class="alert alert-danger">
		      <span class="fa fa-times-circle fa-lg"></span> La información del archivo seleccionado no corresponde al curso escolar '.$config['curso_actual'].'.
		    </div>
		  </div>
		</div>';
	}
	else { 
		
		// Obtenemos el total de registros a importar
		$total = 0;
		for ($i=1; $i<9; $i++) {
			$total += $xml->BLOQUE_DATOS->grupo_datos[$i]->attributes()->registros;
			if($i==9) $total += $xml->BLOQUE_DATOS->grupo_datos[8]->attributes()->registros;
		}
		$unid = 100/$total;
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA CURSOS
		 * ----------------------------------------------------------------------*/
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `cursos` (
  `idcurso` int(12) unsigned NOT NULL,
  `nomcurso` varchar(80) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`idcurso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$tabla = 'cursos'; // Descripción del trabajo para la barra de progreso
		
		mysqli_query($db_con, "TRUNCATE TABLE cursos") or die("No existe la tabla Cursos. No podemos continuar.");
		foreach ($xml->BLOQUE_DATOS->grupo_datos[1]->grupo_datos as $curso) {
			$idcurso = $curso->dato[0];
			$nomcurso = $curso->dato[1];
			
			$result = mysqli_query($db_con, "INSERT cursos (idcurso, nomcurso) VALUES ('$idcurso','$nomcurso')");
			if (!$result) echo '<span class="text-danger">ERROR en la Importación</span><br>';
			
			// Vací­a los búferes de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		// Eliminamos cursos que no pertenecen al Centro
		mysqli_query($db_con,"delete from cursos where nomcurso not in (select distinct curso from alma)");
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA UNIDADES
		 * ----------------------------------------------------------------------*/
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `unidades` (
  `idunidad` int(12) unsigned NOT NULL,
  `nomunidad` varchar(64) COLLATE utf8_general_ci NOT NULL,
  `idcurso` int(12) unsigned NOT NULL,
  PRIMARY KEY (`idunidad`,`idcurso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$tabla = 'unidades'; // Descripción del trabajo para la barra de progreso
		
		mysqli_query($db_con, "TRUNCATE TABLE unidades") or die ("No existe la tabla Unidades. No podemos continuar.");
		
		foreach ($xml->BLOQUE_DATOS->grupo_datos[7]->grupo_datos as $tramos) {
			
			$idunidad = $tramos->dato[0];
			$nomunidad = $tramos->dato[1];
			$idcurso = $tramos->dato[2];
			
			$result = mysqli_query($db_con, "INSERT unidades (idunidad, nomunidad, idcurso) VALUES ('$idunidad','$nomunidad','$idcurso')");
			if (!$result) echo '<span class="text-danger">ERROR '.mysqli_errno().': '.mysqli_error($db_con).'</span><br>';
			
			// Vací­a los búferes de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA MATERIAS
		 * ----------------------------------------------------------------------*/
		mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `materias_seneca` (
  `idmateria` int(12) unsigned NOT NULL,
  `nommateria` varchar(80) COLLATE utf8_general_ci NOT NULL,
  `abrevmateria` varchar(8) COLLATE utf8_general_ci DEFAULT NULL,
  `idcurso` int(12) unsigned NOT NULL,
  PRIMARY KEY (`idmateria`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$tabla = 'materias_seneca'; // Descripción del trabajo para la barra de progreso
		
		mysqli_query($db_con, "TRUNCATE TABLE materias_seneca") or die (mysqli_error("No existe la tabla materias_seneca. No podemos continuar."));
		
		foreach ($xml->BLOQUE_DATOS->grupo_datos[2]->grupo_datos as $materias) {
		
			$idmateria = $materias->dato[1];
			$nommateria = $materias->dato[2];
			$idcurso = $materias->dato[0];
			
			$result = mysqli_query($db_con, "SELECT nomcurso FROM cursos WHERE idcurso='$idcurso'");
			$nomcurso = mysqli_fetch_array($result);
			
			$result = mysqli_query($db_con, "INSERT materias_seneca (idmateria, nommateria, idcurso) VALUES ('$nommateria','$idmateria','$idcurso')");
			if (!$result) echo '<span class="text-danger">ERROR '.mysqli_errno().': '.mysqli_error($db_con).'</span><br>';
			
			// Vací­a los búferes de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA ACTIVIDADES
		 * ----------------------------------------------------------------------*/
		mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `actividades_seneca` (
  `regactividad` char(1) COLLATE utf8_general_ci NOT NULL,
  `idactividad` int(12) unsigned NOT NULL,
  `nomactividad` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `requnidadactividad` char(1) COLLATE utf8_general_ci NOT NULL,
  `reqmateriaactividad` char(1) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`idactividad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
				
		$tabla = 'actividades_seneca'; // Descripción del trabajo para la barra de progreso
		
		mysqli_query($db_con, "TRUNCATE TABLE actividades_seneca") or die ("No existe la tabla actividades_seneca. No podemos continuar.");
	
		foreach ($xml->BLOQUE_DATOS->grupo_datos[3]->grupo_datos as $actividades) {
			
			$regular = $actividades->dato[0];
			$idactividad = $actividades->dato[1];
			$nomactividad = $actividades->dato[2];
			$requnidad = $actividades->dato[3];
			$reqmateria = $actividades->dato[4];
			
			$result = mysqli_query($db_con, "INSERT actividades_seneca (regactividad, idactividad, nomactividad, requnidadactividad, reqmateriaactividad) VALUES ('$regular',$idactividad,'$nomactividad','$requnidad','$reqmateria')");
			if (!$result) echo '<span class="text-danger">ERROR '.mysqli_errno().': '.mysqli_error($db_con).'</span><br>';
			
			// Vací­a los búferes de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA DEPENDENCIAS
		 * ----------------------------------------------------------------------*/
		mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `dependencias` (
  `iddependencia` int(12) unsigned NOT NULL,
  `nomdependencia` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `descdependencia` varchar(80) COLLATE utf8_general_ci DEFAULT NULL,
  `reservadependencia` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddependencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
				
		$tabla = 'dependencias'; // Descripción del trabajo para la barra de progreso
		
		if($truncate) mysqli_query($db_con, "TRUNCATE TABLE dependencias") or die (mysqli_error("No existe la tabla dependencias. No podemos continuar."));
	
		foreach ($xml->BLOQUE_DATOS->grupo_datos[4]->grupo_datos as $actividades) {
			
			$iddependencia = $actividades->dato[0];
			$nomdependencia = $actividades->dato[1];
			
			$result = mysqli_query($db_con, "INSERT dependencias (iddependencia, nomdependencia, descdependencia, reservadependencia) VALUES ('$iddependencia','$nomdependencia','$nomdependencia',0)");
			
			if(mysqli_errno()==1062) mysqli_query($db_con, "UPDATE dependencias SET nomdependecia='$nomdependencia' WHERE iddependencia='$iddependencia'");
			elseif(mysqli_errno()!=0) echo '<span class="text-danger">ERROR '.mysqli_errno().': '.mysqli_error($db_con).'</span><br>';
			
			// Vací­a los búferes de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA TRAMOS
		 * ----------------------------------------------------------------------*/
		
		mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS tramos (
  `tramo` int(6) unsigned NOT NULL,
  `hora` varchar(80) COLLATE utf8_general_ci NOT NULL,
  `horini` int(4) unsigned NOT NULL,
  `horfin` int(4) unsigned NOT NULL,
  `hora_inicio` varchar(5) COLLATE utf8_general_ci NOT NULL,
  `hora_fin` varchar(5) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tramo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		mysqli_query($db_con, "truncate TABLE tramos");				
		
		$tabla = 'tramos'; // Descripción del trabajo para la barra de progreso
		
		$i = 1;
		foreach ($xml->BLOQUE_DATOS->grupo_datos[6]->grupo_datos as $tramos) {
		
			$idtramo = $tramos->dato[0];
			$nomtramo = $tramos->dato[1];
			$horini = $tramos->dato[2];
			$horfin = $tramos->dato[3];
			
			$div_ini = floor($horini / 60);
			$rest_ini = $horini % 60;
			$div_fin = floor($horfin / 60);
			$rest_fin = $horfin % 60;
			
			if (strlen($rest_ini)=="1") {
				$rest_ini.="0";
			}
			if (strlen($rest_fin)=="1") {
				$rest_fin.="0";
			}
			
			$hora_inicio = "$div_ini:$rest_ini";
			$hora_fin = "$div_fin:$rest_fin";
			
			if($i == 4) $numtramo = 'R';
			elseif($i > 4) $numtramo = $i-1;
			else $numtramo = $i;
			
			$result = mysqli_query($db_con, "INSERT tramos (tramo, hora, horini, horfin, hora_inicio, hora_fin) VALUES ('".$idtramo."','".$numtramo."','".$horini."','".$horfin."','".$hora_inicio."','".$hora_fin."')");
			if (!$result) echo '<span class="text-danger">ERROR '.mysqli_errno().': '.mysqli_error($db_con).'</span><br>';
			
			$i++;
			
			// Vací­a los búferes de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		unset($i);
		
		
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA PROFESORES
		 * ----------------------------------------------------------------------*/
		mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `profesores_seneca` (
  `idprofesor` int(9) unsigned NOT NULL,
  `nomprofesor` varchar(64) COLLATE utf8_general_ci NOT NULL,
  `deptoprofesor` varchar(80) COLLATE utf8_general_ci NOT NULL,
  `correoprofesor` varchar(80) COLLATE utf8_general_ci DEFAULT NULL,
  `telefonoprofesor` char(9) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`idprofesor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$tabla = 'profesores_seneca'; // Descripción del trabajo para la barra de progreso
		
		//mysqli_query($db_con, "TRUNCATE TABLE profesores_seneca") or die (mysqli_error("No existe la tabla profesores_seneca. No podemos continuar."));
	
		foreach ($xml->BLOQUE_DATOS->grupo_datos[8]->grupo_datos as $tramos) {
		
			$idprofesor = $tramos->dato[0];
			$ape1profesor = $tramos->dato[3];
			$ape2profesor = $tramos->dato[4];
			$nomprofesor = $tramos->dato[5];
			if (strlen($ape2profesor)>0) {
				$ape2profesor=" ".$ape2profesor;
			}
			else{
				$ape2profesor="";
			}
			$nombre_profesor = $ape1profesor.$ape2profesor.", ".$nomprofesor;
			$deptoprofesor = limpiarNombreDepartamento($tramos->dato[2]);
			
			$result = mysqli_query($db_con, "INSERT profesores_seneca (idprofesor, nomprofesor, deptoprofesor) VALUES ($idprofesor,'$nombre_profesor','$deptoprofesor')");
			
			if(mysqli_errno()==1062) mysqli_query($db_con, "UPDATE profesores_seneca SET nomprofesor='$nombre_profesor', deptoprofesor='$deptoprofesor' WHERE idprofesor=$idprofesor");
			elseif(mysqli_errno()!=0) echo '<span class="text-danger">ERROR '.mysqli_errno().': '.mysqli_error($db_con).'</span><br>';
			
			// Vací­a los búferes de escritura de PHP
			flush();
			ob_flush();
		}
		
			
		/* ----------------------------------------------------------------------
			CREACIÓN DE LA TABLA DEPARTAMENTOS
		 * ----------------------------------------------------------------------*/
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `departamentos_seneca` (
  `iddepartamento` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nomdepartamento` varchar(80) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`iddepartamento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_general_ci ");
			
		$tabla = 'departamentos_seneca'; // Descripción del trabajo para la barra de progreso
		
		mysqli_query($db_con, "TRUNCATE TABLE departamentos_seneca") or die (mysqli_error("No existe la tabla Departamentos. No podemos continuar."));
			
		// Añade el departamento para personal no docente
		$personal_no_docente = "Personal de Administración y Servicios";
		mysqli_query($db_con, "INSERT into departamentos_seneca (nomdepartamento) values('$personal_no_docente')");
		mysqli_query($db_con, "INSERT into departamentos_seneca (nomdepartamento) select distinct deptoprofesor from profesores_seneca");
	}		
	                	
}
?>
