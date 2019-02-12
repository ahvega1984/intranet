<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

/***************************************************
 *	MODO DEPURACION: (0) Desactivado | (1) Activado
 ***************************************************/
$MODO_DEPURACION=0;

// No tocar las siguientes variables de control
$cont_alum=0;
$cont_falt=0;



if (!isset($_GET['iniciofalta']) && !isset($_GET['finfalta'])) {
	die("Error: Debe introducir los parámetros FECHA_DESDE y FECHA_HASTA para generar el archivo.");
}

$FECHA_DESDE = $_GET['iniciofalta'];
$FECHA_HASTA = $_GET['finfalta'];

function fecha_mysql($fecha) {
	$trozo = explode("/", $fecha);
	return $trozo[2]."-".$trozo[1]."-".$trozo[0];
}

function fecha_seneca($fecha_mysql) {
	$trozo = explode("-", $fecha_mysql);
	return $trozo[2]."/".$trozo[1]."/".$trozo[0];
}

$mysqli_FECHA_DESDE = fecha_mysql($FECHA_DESDE);
$mysqli_FECHA_HASTA = fecha_mysql($FECHA_HASTA);

$fechaHoy = date('d/m/Y H:i:s');
$anio_curso = substr($config['curso_inicio'],0,4);

// FLAGS DE CONTROL
$flag_fincurso=0;	// Controla que no imprima las etiquetas </UNIDADES> u </CURSO> al comienzo.
$flag_curso="";		// Controla que no imprima el curso por cada unidad.


// CABECERA DEL DOCUMENTO XML
$docXML  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
$docXML .= "<SERVICIO>\n";
$docXML .= "\t<DATOS_GENERALES>\n";
$docXML .= "\t\t<MODULO>FALTAS DE ASISTENCIA</MODULO>\n";
$docXML .= "\t\t<TIPO_INTERCAMBIO>I</TIPO_INTERCAMBIO> \n";
$docXML .= "\t\t<AUTOR>SENECA</AUTOR>\n";
$docXML .= "\t\t<FECHA>$fechaHoy</FECHA>\n";
$docXML .= "\t\t<C_ANNO>$anio_curso</C_ANNO>\n";
$docXML .= "\t\t<FECHA_DESDE>$FECHA_DESDE</FECHA_DESDE>\n";
$docXML .= "\t\t<FECHA_HASTA>$FECHA_HASTA</FECHA_HASTA>\n";
$docXML .= "\t\t<CODIGO_CENTRO>".$config['centro_codigo']."</CODIGO_CENTRO>\n";
$docXML .= "\t\t<NOMBRE_CENTRO>".$config['centro_denominacion']."</NOMBRE_CENTRO>\n";
$docXML .= "\t\t<LOCALIDAD_CENTRO>".$config['centro_localidad']." (".$config['centro_provincia'].")</LOCALIDAD_CENTRO>\n";
$docXML .= "\t</DATOS_GENERALES>\n";
$docXML .= "\t<CURSOS>\n";


$directorio = scandir("./origen/");
sort($directorio);
foreach ($directorio as $archivo) {
	
    if (!is_dir($archivo) and stristr($archivo,".xml")==TRUE)
    {               
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->load( './origen/'.$archivo );
        
        // Obtenemos los datos del curso
        $tag_xofertamatrig	= $doc->getElementsByTagName("X_OFERTAMATRIG");
        $tag_dofertamatrig	= $doc->getElementsByTagName("D_OFERTAMATRIG");
        $tag_xunidad 		= $doc->getElementsByTagName("X_UNIDAD");
        $tag_tnombre		= $doc->getElementsByTagName("T_NOMBRE");
        $X_OFERTAMATRIG 	= $tag_xofertamatrig->item(0)->nodeValue;
        $D_OFERTAMATRIG		= $tag_dofertamatrig->item(0)->nodeValue;
        $X_UNIDAD			= $tag_xunidad->item(0)->nodeValue;
        $T_NOMBRE			= $tag_tnombre->item(0)->nodeValue;
        
        
        // COMIENZO/FIN DE UNIDADES Y CURSOS DEL CENTRO
        if ($flag_curso != $X_OFERTAMATRIG) {
        	if ($flag_fincurso) {
        		$docXML .= "\t\t\t</UNIDADES>\n";
        		$docXML .= "\t\t</CURSO>\n";
        	}
        	$docXML .= "\t\t<CURSO>\n";
        	$docXML .= "\t\t\t<X_OFERTAMATRIG>$X_OFERTAMATRIG</X_OFERTAMATRIG>\n";
        	$docXML .= "\t\t\t<D_OFERTAMATRIG>$D_OFERTAMATRIG</D_OFERTAMATRIG>\n";
        	$docXML .= "\t\t\t<UNIDADES>\n";
        	
        	$flag_fincurso = 1;
        }
        $flag_curso = $X_OFERTAMATRIG;
        
        
        // COMIENZO DE UNIDAD
        $docXML .= "\t\t\t\t<UNIDAD>\n";
        $docXML .= "\t\t\t\t\t<X_UNIDAD>$X_UNIDAD</X_UNIDAD>\n";
        $docXML .= "\t\t\t\t\t<T_NOMBRE>$T_NOMBRE</T_NOMBRE>\n";
        
        
        // ALUMNOS DE LA UNIDAD
        $docXML .= "\t\t\t\t\t<ALUMNOS>\n";
        
        $tag_alumno = $doc->getElementsByTagName("ALUMNO");
        foreach( $tag_alumno as $alumno ) {
        	$tag_xmatricula = $alumno->getElementsByTagName("X_MATRICULA");
        	$tag_cnumescolar = $alumno->getElementsByTagName("C_NUMESCOLAR");
        	$X_MATRICULA = $tag_xmatricula->item(0)->nodeValue;	
        	$C_NUMESCOLAR = $tag_cnumescolar->item(0)->nodeValue;
        	
        	// COMIENZO ALUMNO
        	$docXML .= "\t\t\t\t\t\t<ALUMNO>\n";
        	$docXML .= "\t\t\t\t\t\t\t<X_MATRICULA>$X_MATRICULA</X_MATRICULA>\n";
        	
        	if ($MODO_DEPURACION) {
        		$alumnos[$cont_alum] = $X_MATRICULA;
        		$cont_alum++;
        	}
        		
        	
        	// COMIENZO FALTAS DE ASISTENCIA
        	$docXML .= "\t\t\t\t\t\t\t<FALTAS_ASISTENCIA>\n";
          	
        	$result = mysqli_query($db_con, "SELECT FALTAS.FECHA, FALTAS.HORA, FALTAS.FALTA FROM FALTAS WHERE FALTAS.FECHA BETWEEN '$mysqli_FECHA_DESDE' AND '$mysqli_FECHA_HASTA' AND (FALTAS.FALTA='F' OR FALTAS.FALTA='J') AND FALTAS.CLAVEAl='$C_NUMESCOLAR'");
        	if (!$result) echo mysqli_error($db_con);
        	
        	while($faltas = mysqli_fetch_array($result)) {
	        	if ($faltas[2]=="F") {
	        		$faltas_tipo = "I";
	        	}
	        	elseif($faltas[2]=="J"){
	        		$faltas_tipo = "J";
	        	}
	        	elseif($faltas[2]=="R"){
	        		$faltas_tipo = "R";
	        	}
	        	// Obtenemos la fecha de la falta en formato Séneca
	        	$F_FALASI = fecha_seneca($faltas[0]);
	        	
	        	// Obtenemos el código de tramo
	        	// if ($faltas[1] > 3) $faltas[1]++; // No es lo más óptimo, pero soluciona el problema... :/
	        	$result_tramos = mysqli_query($db_con, "SELECT tramo FROM tramos WHERE hora='$faltas[1]'");
	        	$tramos = mysqli_fetch_array($result_tramos);
	        	
	        	$docXML .= "\t\t\t\t\t\t\t\t<FALTA_ASISTENCIA>\n";
	        	$docXML .= "\t\t\t\t\t\t\t\t\t<F_FALASI>$F_FALASI</F_FALASI>\n";
	        	$docXML .= "\t\t\t\t\t\t\t\t\t<X_TRAMO>$tramos[0]</X_TRAMO>\n";
	        	$docXML .= "\t\t\t\t\t\t\t\t\t<C_TIPFAL>$faltas_tipo</C_TIPFAL>\n";
	        	$docXML .= "\t\t\t\t\t\t\t\t\t<L_DIACOM>N</L_DIACOM>\n";
	        	$docXML .= "\t\t\t\t\t\t\t\t</FALTA_ASISTENCIA>\n";
        	}
        	
        	if ($MODO_DEPURACION) {
        		$dias[$cont_falt] = $F_FALASI;
        		$cont_falt++;
        	}

        	
        	// FIN FALTAS DE ASISTENCIA
        	$docXML .= "\t\t\t\t\t\t\t</FALTAS_ASISTENCIA>\n";
        	
        	// FIN ALUMNO
        	$docXML .= "\t\t\t\t\t\t</ALUMNO>\n";
        }
		
		// FIN DE ALUMNOS DE LA UNIDAD
        $docXML .= "\t\t\t\t\t</ALUMNOS>\n";
        
        // FIN DE UNIDAD
        $docXML .= "\t\t\t\t</UNIDAD>\n";
    }
}

// PIE DEL DOCUMENTO
$docXML .= "\t\t\t</UNIDADES>\n";
$docXML .= "\t\t</CURSO>\n";
$docXML .= "\t</CURSOS>\n";
$docXML .= "</SERVICIO>";


// CREACIÃN DEL DOCUMENTO XML
$directorio = "./exportado/";
$archivo = "Importacion_Faltas_Alumnado.xml";
$fopen = fopen($directorio.$archivo, "w");
fwrite($fopen, $docXML);


if ($MODO_DEPURACION) {
	echo "<h2>COMPROBACION ALUMNOS</h2>";
	$i=0;
	while ($alumnos[$i] != FALSE) {
		$todos = mysqli_fetch_array(mysqli_query($db_con, "SELECT COUNT(*) FROM alma"));
		$result = mysqli_query($db_con, "SELECT CONCAT(APELLIDOS,', ',NOMBRE) AS alumnos, unidad FROM alma WHERE claveal1='$alumnos[$i]'");
		$filas = mysqli_num_rows($result);
		$alumno = mysqli_fetch_array($result);
		
		if (!$filas>1) echo "<span style='color:red'>$alumnos[$i]  -->  $alumno[1] - $alumno[0]</span><br>";
		
		$i++;
	}
	$reg = $i;
	if ($reg != $todos[0]) echo "Faltan alumnos";
	else echo "CORRECTO!";
	
	echo "<h2>COMPROBACION DIAS</h2>";
	$i=0;
	while ($dias[$i] != FALSE) {
		$dia = fecha_mysql($dias[$i]);
		$diasem = strftime('%w', strtotime("$dia"));
		
		$result = mysqli_query($db_con, "SELECT * FROM festivos WHERE fecha='$dia'");
		$filas = mysqli_num_rows($result);
		
		$error=0;
		if ($diasem == 0 || $diasem == 6 || $filas>1) {
			echo "<span style='color:red'>$dias[$i]  -->  $diasem (Es día festivo o fin de semana: (6) Sábado, (0) Domingo)</span><br>";
			$error=1;
		}
		
		$i++;
	}
	if (!$error) echo "CORRECTO!";
	
	echo "<h2>COMPROBACION TRAMOS</h2>";
	$tramos = mysqli_fetch_array(mysqli_query($db_con, "SELECT COUNT(*) FROM tramos"));
	$result = mysqli_query($db_con, "SELECT * FROM tramos");
	
	while($tramo = mysqli_fetch_array($result)) {
		echo "ID: $tramo[1] --> HORA: $tramo[0] (la hora debe ser un valor numerico)<br>";
	}
	
	if (!$tramos>1) echo "No hay tramos horarios";
	else echo "CORRECTO!";
	
}
else {
	header("Content-disposition: attachment; filename=$archivo");
	header("Content-type: application/octet-stream");
	readfile($directorio.$archivo);
}
?>
