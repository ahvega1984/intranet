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

	$html.="<h2 align='center'><u>Informe de materias pendientes para la evaluación extraordinaria</u></h2>";

	$alum = mysqli_fetch_array(mysqli_query($db_con,"select concat(nombre,' ',apellidos) as nombre_al, curso, unidad from alma where claveal='".$claveal."'"));

	$html.="<br><h2 align='center'>".$alum['nombre_al']." <small>(".$alum['unidad'].")</small></h2>";

	$materias = mysqli_query($db_con, "SELECT distinct informe_extraordinaria.asignatura, informe_extraordinaria.id_informe FROM informe_extraordinaria, informe_extraordinaria_alumnos WHERE informe_extraordinaria.id_informe = informe_extraordinaria_alumnos.id_informe and claveal = '$claveal'");
	$num_informes = mysqli_num_rows($materias);
	while($materia_curso = mysqli_fetch_array($materias)){

		$n_inf++;

		$id_informe = $materia_curso['id_informe'];

		$html.="<br><h3><em>".$materia_curso['asignatura']."<small> (".$alum['curso'].")</small></em></h3><br>";
		
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
		
		$query_id= mysqli_query($db_con, "SELECT distinct id_informe, id_contenido, actividades FROM informe_extraordinaria_alumnos WHERE claveal = '$claveal' and id_informe = '$id_informe' order by id_informe, id_contenido");
		while ($informe = mysqli_fetch_array($query_id)) {
			if (strlen($informe['actividades'])>0) {
				$act_personal = $informe['actividades'];
			}
			
			
			$content = mysqli_query($db_con,"select unidad, titulo, contenidos, actividades from informe_extraordinaria_contenidos where id_informe = ".$informe['id_informe']." and id_contenido = '".$informe['id_contenido']."'");
			
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
	
	$dompdf->stream("Informe de materias pendientes para la evaluacion extraordinaria - $claveal.pdf", array("Attachment" => 0));
	

?>