<?php
ini_set("memory_limit","1024M");
require('../../../bootstrap.php');
require_once("../../../pdf/dompdf_config.inc.php");

define("DOMPDF_ENABLE_PHP", true);

	$claveal = mysqli_real_escape_string($db_con, $_GET['claveal']);


	$html="<html><head>
	<meta charset='UTF-8'>";
	$html.="<style>
		html {
		 font-family: sans-serif;
		 font-size:16px;
		 -webkit-text-size-adjust: 100%;
		 -ms-text-size-adjust: 100%;
		}
	  	.table {
	    border-collapse: collapse !important;
	  	width: auto;
	  	}
	  	.table td
	  	{
	    background-color: #fff !important;
	    padding:15px 12px;
	    vertical-align:top;
	  	}
	  	.table th {
	    background-color: #ccc !important;
	    padding:8px -3px;
	    margin:0px;
	  	}
	  	.table-bordered td, th {
	    border: 1px solid #999 !important;
	  	}
	  	hr {
	  	border: 1px solid #ddd !important;
	  	}
	</style>";
	
	$html .= "
	<body>";

	$html.="<h2 align='center'><u>Informe de evaluación de alumnos con materias pendientes</u></h2>";

	$alum = mysqli_fetch_array(mysqli_query($db_con,"select concat(nombre,' ',apellidos) as nombre_al, curso, unidad from alma where claveal='".$claveal."'"));

	$html.="<br><h2 align='center'>".$alum['nombre_al']." <small>(".$alum['unidad'].")</small></h2><br>";

	$html.="<p style='border: solid 1px #ccc; padding: 10px 10px; text-align: justify; color:#444'>Este documento presenta los <b>contenidos y actividades</b> que el alumno deberá preparar y realizar para superar la prueba en aquellas asignaturas que tenga pendientes de cursos anteriores o bien no haya superado en la evaluación ordinaria de junio. También contiene la <b>fecha</b> en la que habrá de realizarse el examen de la evaluación, si ese fuera el método de recuperación requerido por el profesor y la materia corespondientes; o bien las instrucciones para presentar las actividades encomendadas si ese fuera el método elegido para su evaluación.</p>";


	$materias = mysqli_query($db_con, "SELECT distinct informe_pendientes.asignatura, informe_pendientes.id_informe, informe_pendientes.fecha, informe_pendientes.curso FROM informe_pendientes, informe_pendientes_alumnos WHERE informe_pendientes.id_informe = informe_pendientes_alumnos.id_informe and claveal = '$claveal'");
	$num_informes = mysqli_num_rows($materias);
	while($materia_curso = mysqli_fetch_array($materias)){

		$n_inf++;

		$id_informe = $materia_curso['id_informe'];
		$curso_pend = $materia_curso['curso'];

		$fecha_reg = explode(" ", $materia_curso['fecha']);
		$dia_reg = $fecha_reg[0];
		$hora_reg = $fecha_reg[1];

		$html.="<br><h3>".$materia_curso['asignatura']."<small> (".$curso_pend.")</small><hr><small style='color:#666'>Fecha del examen o entrega de actividades:  el día <u>".cambia_fecha($dia_reg)."</u> a las <u>".$hora_reg."</u> horas</small></h3><br>";
		
		$html.="
			<table class='table table-bordered'>
			<thead>
			<tr>
			<th>Contenidos</th><th>Actividades</th>
			</tr>
			</thead>
			<tbody>
			";
		
		$act_personal="";
		
		$query_id= mysqli_query($db_con, "SELECT distinct id_informe, id_contenido, actividades FROM informe_pendientes_alumnos WHERE claveal = '$claveal' and id_informe = '$id_informe' order by id_informe, id_contenido");
		while ($informe = mysqli_fetch_array($query_id)) {
			if (strlen($informe['actividades'])>0) {
				$act_personal = $informe['actividades'];
			}
			
			
			$content = mysqli_query($db_con,"select unidad, titulo, contenidos, actividades from informe_pendientes_contenidos where id_informe = ".$informe['id_informe']." and id_contenido = '".$informe['id_contenido']."'");
			
			while($datos = mysqli_fetch_array($content)){
				$html.="<tr><td><u><b>".$datos['unidad']."</b>: ".$datos['titulo']."</u><br><br>".$datos['contenidos']."</td><td>".$datos['actividades']."</td></tr>";
			}
			
		}
		
			$html.="<tr><td colspan='2'><b><u>Observaciones complementarias</u></b>: <br>".$act_personal."</td></tr>";

	$html.="</tbody>
	</table>";

	if ($num_informes != $n_inf) {
		$html .= '<div style="page-break-before: always;"></div>';
	}

	}
		
	$html .= '</body></html>';
	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->render();
	
	$dompdf->stream("Informe de ".$alum['nombre_al'].".pdf", array("Attachment" => 0));

	
?>