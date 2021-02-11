<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function conectar_db () {
	if (file_exists('../config.php')) {
		include('../config.php');	
		$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
		mysqli_query($db_con,"SET NAMES 'utf8'");
		return $db_con;
	} else {
		die('La intranet no esta instalada');
	}
	
}


function sanear($cadena) {
	
	//pasamos todo a minuscula
	$cadena = strtolower($cadena);
	
	//reemplazamos espacio por "_"
	$cadena = str_replace(' ', '_', $cadena);
	
	//Reemplazamos la A y a
	$cadena = str_replace(
			array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
			array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
			$cadena
	);

	//Reemplazamos la E y e
	$cadena = str_replace(
			array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
			array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
			$cadena);

	//Reemplazamos la I y i
	$cadena = str_replace(
			array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
			array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
			$cadena);

	//Reemplazamos la O y o
	$cadena = str_replace(
			array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
			array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
			$cadena);

	//Reemplazamos la U y u
	$cadena = str_replace(
			array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
			array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
			$cadena);

	//Reemplazamos la N, n, C y c
	$cadena = str_replace(
			array('Ñ', 'ñ', 'Ç', 'ç'),
			array('N', 'n', 'C', 'c'),
			$cadena
	);
	
	//quitar caracteres extraños (ñ, acentos, ...)
	$cadena = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $cadena);

	return $cadena;
}

function getCorreo($nombre) {
	$db_con = conectar_db();
	$correo='';
	$result = mysqli_query($db_con, "select correo from c_profes where PROFESOR LIKE '$nombre' LIMIT 1");
	while($row = mysqli_fetch_array($result)){
		$correo = $row['correo'];
	}
	return $correo;
}

function getTutoria($tutorias_all, $shortcodes) {
	$array = array();
	foreach ($shortcodes as $shortcode) {
		foreach ($tutorias_all as $tutoria) {
			if ($tutoria['subcurso'] == $shortcode) {
				$array[] = $tutoria;
			}
		}	
	}
	usort($array, function ($item1, $item2) {
		return $item1['grupo'] <=> $item2['grupo'];
	});
	return $array;
}

function getTutorias() {
	$tutorias_all = getTutorias2();
	$return = Array(
		'Educación Secundaria' => getTutoria($tutorias_all, array(' de E.S.O.')),
		'Bachillerato' => getTutoria($tutorias_all, array(' de Bachille')),
		'Educación Secundaria Personas Adultas' => getTutoria($tutorias_all, array('vel I Esa Se', 'vel II Esa P', 'vel II Esa S')),
		'Bachillerato Personas Adultas'=> getTutoria($tutorias_all, array(' Bach.Pers.A')),
		'Ciclos Formativos de Grado Medio'=> getTutoria($tutorias_all, array(' F.P.I.G.M. ')),
		'Ciclos Formativos de Grado Superior'=> getTutoria($tutorias_all, array(' F.P.I.G.S. ')),
		'Formación Profesional Básica' => getTutoria($tutorias_all, array(' de F.P.B. (')),
		'Prueba de acceso a los Ciclos Formativos' => getTutoria($tutorias_all, array('rso de forma'))
	);
	return $return;
}


function getTutorias2() {
	$path = '/var/www/vhosts/iesrioverde.es/intranet.iesrioverde.es/documentos/Tutorías/';
	$wwwroot = 'http://www.iesrioverde.es/documentos/Tutorías';
	
	if (!file_exists($path)) {
		mkdir($path);
	}
	
	$db_con = conectar_db();
	$tutorias = array();
	$results = mysqli_query($db_con, "SELECT DISTINCT SUBSTRING(cursos.nomcurso, 3, 12) AS subcurso, nomcurso as curso, unidades.nomunidad as grupo, FTUTORES.tutor
							FROM unidades JOIN cursos ON cursos.idcurso = unidades.idcurso 
							JOIN FTUTORES ON unidades.nomunidad = FTUTORES.unidad 
							GROUP BY grupo
							ORDER BY subcurso, unidades.nomunidad ASC");
	while ($row = mysqli_fetch_array($results)) {		
		$cursos = array();
		$result_cursos = mysqli_query($db_con, "SELECT cursos.nomcurso FROM cursos 
				JOIN unidades ON cursos.idcurso = unidades.idcurso 
				WHERE unidades.nomunidad = '".$row['grupo']."' 
				ORDER BY cursos.nomcurso ASC");
		while ($row_cursos = mysqli_fetch_array($result_cursos)) {
			array_push($cursos, $row_cursos['nomcurso']);
		}
		$equipo = array();		
		$result_equipo = mysqli_query($db_con, "select distinct prof, asig from horw where a_grupo='".$row['grupo']."' ORDER BY asig");
		while ($row_equipo = mysqli_fetch_array($result_equipo)) {
			$array = Array (
				'profesor'=> $row_equipo['prof'],
				'correo' => getCorreo($row_equipo['prof']),
				'asignatura'=> $row_equipo['asig']
			);
			array_push($equipo,$array);
		}
		
		$result_horario = mysqli_query($db_con, "SELECT `dia`, `hora` FROM `horw` WHERE (`c_asig` = '117' OR `c_asig` = '279') "
				. "AND (`a_grupo` = '".$row['grupo']."' OR `prof` = '".$row['tutor']."') LIMIT 1");
		$row_horario = mysqli_fetch_array($result_horario);
		$horario = obtenerHoraTutoria($row_horario['dia'], $row_horario['hora']);
		if (empty($horario)) $horario = '';
		mysqli_free_result($result_horario);
		
		$path_tutoria = $row['grupo'];
		if (!file_exists($path.$path_tutoria)) {
			mkdir($path.$path_tutoria);
		}
		$tutor_name = ucwords(mb_strtolower($row['tutor']));
		$tutor_correo = getCorreo($tutor_name);
		$tutoria = array(
			'subcurso'		=> $row['subcurso'],
			'grupo'			=> $row['grupo'],
			'cursos'		=> $cursos,
			'tutor'			=> $tutor_name,
			'tutor_correo'	=> $tutor_correo,
			'horario'		=> $horario,
			'equipo'		=> $equipo,
			'ficheros'		=> getFicheros($path.$path_tutoria.'/',$wwwroot.'/'.$path_tutoria.'/' )
		);
		array_push($tutorias, $tutoria);
	}
	return $tutorias;
}

function obtenerHoraTutoria($dia, $hora) {
	
	$db_con = conectar_db();

	$dia = limpiarInput($dia, 'numeric');
	$hora = limpiarInput($hora, 'numeric');

	if (empty($dia) && empty($hora)) {
		return false;
	}
	else {
		switch ($dia) {
			case '1': $diasem = "Lunes"; break;
			case '2': $diasem = "Martes"; break;
			case '3': $diasem = "Miércoles"; break;
			case '4': $diasem = "Jueves"; break;
			case '5': $diasem = "Viernes"; break;
			case '6': $diasem = "Sábado"; break;
			case '7': $diasem = "Domingo"; break;
		}
		$result = mysqli_query($db_con, "SELECT `hora_inicio`, `hora_fin` FROM `tramos` WHERE `hora` = '$hora' LIMIT 1");
		if (mysqli_num_rows($result)) {
			$row = mysqli_fetch_array($result);
			$hora_ini = substr($row['hora_inicio'], 0, 5);
			$hora_fin = substr($row['hora_fin'], 0, 5);
			return $diasem . " de " . $hora_ini . ' a ' . $hora_fin . ' horas';
		}
		else {
			return 1;
		}
	}
}

function limpiarInput($input, $type = 'alphanumeric') {

	switch ($type) {
		// ALLOW NUMBERS
		case 'numeric':
			if (! intval($input)) {
				$output = preg_replace('([^0-9])', '', $input);
			}
			else {
				$output = intval($input);
			}

			break;
		
		// ALLOW MAYUS
		case 'mayus':
			$output = preg_replace('([^A-ZÁÉÍÓÚÜÑ])', '', $input);

			break;

		// ALLOW MINUS
		case 'minus':
			$output = preg_replace('([^a-záéíóúüñ])', '', $input);

			break;

		// ALLOW LETTERS (MAYUS AND MINUS)
		case 'alpha':
			$output = preg_replace('([^A-ZÁÉÍÓÚÜÑa-záéíóúüñ])', '', $input);

			break;

		// ALLOW ALPHANUMERIC
		case 'alphanumeric':
			$output = preg_replace('([^A-ZÁÉÍÓÚÜÑa-záéíóúüñ0-9])', '', $input);

			break;

		// ALLOW ALPHANUMERIC AND SPECIAL CHARS: space,  !"#$%&'()*+,-./:;»=>?@[\]^_`{|}~
		case 'alphanumericspecial':
		default:
			$output = preg_replace('([^A-ZÁÉÍÓÚÜÑa-záéíóúüñºª0-9 !"#$%&\'()*+,-./:;»=>?@[\]^_`{|}~])', '', $input);

			break;

	}

	return $output;
}

function getDepartamentos() {
	//$path = '/var/www/webrioverde/documentos/departamentos/';
	//$wwwroot = 'http://localhost/webrioverde/documentos/Departamentos/';
	
	$path = '/var/www/vhosts/iesrioverde.es/intranet.iesrioverde.es/documentos/Departamentos/';
	$wwwroot = 'http://www.iesrioverde.es/documentos/Departamentos/';
	
	if (!file_exists($path)) {
		mkdir($path);
	}
	
	$db_con = conectar_db();
	$result = mysqli_query($db_con, "SELECT DISTINCT DEPARTAMENTO from departamentos 
			WHERE DEPARTAMENTO NOT LIKE 'Admin' 
				AND DEPARTAMENTO NOT LIKE 'Conserjeria' 
				AND DEPARTAMENTO NOT LIKE 'Administración' 
				AND DEPARTAMENTO NOT LIKE 'Tecnología' 
				AND DEPARTAMENTO NOT LIKE 'Formación y Orientación Laboral' 
				AND DEPARTAMENTO NOT LIKE 'Pedagogía Terapeutica Eso' 
				AND DEPARTAMENTO NOT LIKE 'Contr. Lab. Religión (Sec-Ere) 11 Horas' 
				AND DEPARTAMENTO NOT LIKE 'PROFESOR ADICIONAL'
				AND DEPARTAMENTO NOT LIKE 'Proc. Gestión Administrativa'
				AND DEPARTAMENTO NOT LIKE 'Area de Lengua y CC. Soc. (Apoyo Covid)'
				AND DEPARTAMENTO NOT LIKE 'Latin'
			ORDER BY DEPARTAMENTO");
	$departamentos = array ();
	while ($row = mysqli_fetch_array($result)) {
		$name = $row['DEPARTAMENTO'];
		if ($name == 'Informática'){
			$name = 'Tecnología e Informática';
			$miembros = array_merge(getMiembros('Tecnología'), getMiembros($row['DEPARTAMENTO']));
		} elseif ($name == 'Economía'){
			$name = 'Economía y F.O.L.';
			$miembros = array_merge(getMiembros('Formación y Orientación Laboral'), getMiembros($row['DEPARTAMENTO']));
		} elseif ($name == 'Orientación Educativa'){
			$name = 'Orientación Educativa';
			$miembros = array_merge(getMiembros('Pedagogía Terapeutica Eso'), getMiembros($row['DEPARTAMENTO']));
		} else if ($name == 'Contr. Lab. Religión (Sec-Ere) 07 Horas') {
			$name = 'Religión';
			$miembros = array_merge(getMiembros('Contr. Lab. Religión (Sec-Ere) 11 Horas'), getMiembros($row['DEPARTAMENTO']));
		} elseif ($name == 'Matemáticas') {
			$name = 'Matemáticas';
			$miembros = array_merge(getMiembros('Area Científico-Tecnolog. (Apoyo Covid)'), getMiembros($row['DEPARTAMENTO']));
		} elseif ($name == 'Lengua Castellana y Literatura') {
			$name = 'Lengua Castellana y Literatura';
			$miembros = array_merge(getMiembros('Area de Lengua y CC. Soc. (Apoyo Covid)'), getMiembros($row['DEPARTAMENTO']));
		} elseif ($name == 'Administración de Empresas') {
			$name = 'Administración';
			$miembros = array_merge(getMiembros('Proc. Gestión Administrativa'), getMiembros($row['DEPARTAMENTO']));
		} elseif($name == 'Area Científico-Tecnolog. (Apoyo Covid)') {
			$name = 'Apoyo Covid';
			$miembros = array_merge(getMiembros('Area de Lengua y CC. Soc. (Apoyo Covid)'), getMiembros($row['DEPARTAMENTO']));
		}elseif($name == 'Griego') {
			$name = 'Latín y Griego';
			$miembros = array_merge(getMiembros('Latin'), getMiembros($row['DEPARTAMENTO']));
		} else{
			$miembros = getMiembros($row['DEPARTAMENTO']);
		}
		
		usort($miembros, function ($item1, $item2) {
			return $item1['nombre'] <=> $item2['nombre'];
		});
		
		for($i = 0; $i < count($miembros); $i++) {
			$miembros[$i] = str_replace("000000 ", "", $miembros[$i]);
		}
		//$path_departamento = sanear($name);
		$path_departamento = $name;
		if (!file_exists($path.$path_departamento)) {
			mkdir($path.$path_departamento);
		}
		
		$departamentos[] = array(
			'name'=> $name, 
			'icon'=> addIcon($name),
			'miembros' => $miembros,
			'ficheros' =>getFicheros($path.$path_departamento.'/',$wwwroot.'/'.$path_departamento.'/' )
		);
	}
	
	usort($departamentos, function ($item1, $item2) {
		return $item1['name'] <=> $item2['name'];
	});
	
	return $departamentos;
}

function printArray($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
	die();
}

function esJefe($dni) {
	$db_con = conectar_db();
	$results = mysqli_query($db_con, "select cargo from cargos where dni like '$dni'");
	while ($row = mysqli_fetch_array($results)) {
		if ($row['cargo']==4) {
			return 1;
		}
	}
	return 0;
}


function getMiembros($departamento) {
	$db_con = conectar_db();
	$result = mysqli_query($db_con, "SELECT dept.NOMBRE, dept.CARGO, profes.correo AS CORREO, profes.DNI as dni "
			. "FROM departamentos dept "
			. "JOIN c_profes profes on profes.idea=dept.idea "
			. "WHERE DEPARTAMENTO LIKE '$departamento' order by NOMBRE");
	$miembros = array ();
	while ($row = mysqli_fetch_array($result)) {
		$member = Array();
		$member['nombre'] = $row['NOMBRE'];
		if (esJefe($row['dni']) ) {
			$member['nombre'] = "000000 ".$row['NOMBRE'];
			$member['cargo'] = 'Jefe/a de departamento';
		}
		if (isset($row['CORREO'])) {
			$member['correo'] = $row['CORREO'];
		} 
		$miembros[] = $member;		
	}
	return $miembros;
}

function addIcon($departamento) {
	switch ($departamento) {
		case 'Administración': return 'fa-business-time';
		case 'Biología y Geología': return 'fa-dna';
		case 'Religión': return 'fa-bible';
		case 'Dibujo': return 'fa-pencil-ruler';
		case 'Economía y F.O.L.': return 'fa-chart-bar';
		case 'Educación Física': return 'fa-vial';
		case 'Filosofía': return 'fa-university';
		case 'Física y Química': return 'fa-vials';
		case 'Formación y Orientación Laboral': return 'fa-briefcase';
		case 'Francés': return 'fa-language';
		case 'Geografía e Historia': return 'fa-globe-europe';
		case 'Griego': return 'fa-language';
		case 'Informática': return 'fa-laptop';
		case 'Inglés': return 'fa-language';
		case 'Latín': return 'fa-language';
		case 'Lengua Castellana y Literatura': return 'fa-book';
		case 'Matemáticas': return 'fa-square-root-alt';
		case 'Música': return 'fa-music';
		case 'Orientación Educativa': return 'fa-hands-helping';
		case 'Proc. Gestión Administrativa': return 'fa-tasks';
		case 'Tecnología e Informática': return 'fa-cogs';
		case 'Apoyo Covid': return 'fa-biohazard';
		case 'Latín y Griego': return 'fa-landmark';
		default: return 'fa-school';
	}
	
}

function buscarDepartamento($departamentos, $nombre) {
	$i=0;
	foreach ($departamentos as $departamento) {
		if ($departamento['name'] == $nombre) {
			return $i;
		}
		$i++;
	}
}

function getFicheros($path, $wwwroot) {
	$return = array();
	$ficheros  = scandir($path);
	if (count($ficheros)>2) {			
		foreach ($ficheros as $fichero) { 
			if ($fichero!='.' && $fichero!='..') {	
				$extension = pathinfo($fichero, PATHINFO_EXTENSION);
				$nombre_base = basename($fichero, '.'.$extension); 
				$return[] = Array (
					'wwwroot' => "{$wwwroot}{$fichero}",
					'name' => $nombre_base,	
				);					
			}
		}		
	}
	return $return;
}
