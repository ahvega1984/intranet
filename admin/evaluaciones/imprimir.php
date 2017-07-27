<?php
require('../../bootstrap.php');
require('inc_evaluaciones.php');

if (file_exists('config.php')) {
	include('config.php');
}

if ((stristr($_SESSION['cargo'],'1') == false) && (stristr($_SESSION['cargo'],'2') == false)) {
	die ("<h1>FORBIDDEN</h1>");
}

if (isset($_GET['id'])) $id = $_GET['id'];

if (!$id) {
	die ("<h1>FORBIDDEN</h1>");
}

require_once("../../pdf/dompdf_config.inc.php"); 

// REGISTRAMOS LA ACCION
mysqli_query($db_con, "UPDATE evaluaciones_actas SET impresion = 1 WHERE id = ".$id);

// OBTENEMOS LOS DATOS
$result = mysqli_query($db_con, "SELECT unidad, evaluacion, texto_acta, fecha FROM evaluaciones_actas WHERE id = ".$id);

if (mysqli_num_rows($result)) {
	$row = mysqli_fetch_array($result);

	$result_curso = mysqli_query($db_con, "SELECT cursos.nomcurso FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso WHERE unidades.nomunidad = '".$row['unidad']."'");
	$row_curso = mysqli_fetch_array($result_curso);
	$curso = $row_curso['nomcurso'];

	$result_tutor = mysqli_query($db_con, "SELECT tutor FROM FTUTORES WHERE unidad = '".$row['unidad']."' LIMIT 1");
	$row_tutor = mysqli_fetch_array($result_tutor);
	$tutor = nomprofesor($row_tutor['tutor']);
	
	$unidad = $row['unidad'];
	$evaluacion = $row['evaluacion'];
	$texto_acta = $row['texto_acta'];
	$fecha = $row['fecha'];
	$fecha_alt = strftime('%d-%m-%Y', strtotime($row['fecha']));
	$dia = strftime('%e', strtotime($row['fecha']));
	$mes = strftime('%B', strtotime($row['fecha']));
	$anio = strftime('%Y', strtotime($row['fecha']));

	$texto_acta = '
	<header>
		<style type="text/css">
		body {
			font-size: 9.5pt;
			margin: 10mm 10mm 20mm 10mm; 
		}
		#footer {
			position: fixed;
		left: 0;
			right: 0;
			bottom: 0;
			color: #aaa;
			font-size: 0.9em;
			text-align: right;
		}
		.page-number:before {
		content: counter(page);
		}

		h1 > small {
			font-size: 14pt;
			color: #666;
		}

		.texto-consejeria {
			font-size: 8.5pt;
			font-weight: bold;
			color: rgb(0, 109, 38);
		}

		.table-no-border td {
			border: 0;
			padding: 2mm 0;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 5mm 
		}
		table th {
			background-color: #333;
			color: #fff;
			padding: 2mm 2mm;
		}
		table td {
			border: 1px solid #666;
			padding: 2mm 2mm;

		}
	</style>
	</header>
	<body>
		<table class="table-no-border">
			<tr>
				<td width="50%"><img src="../../img/encabezado.png" alt=""></td>
				<td class="texto-consejeria" width="50%">CONSEJERÍA DE EDUCACIÓN, CULTURA Y DEPORTE<br>
				'.$config['centro_denominacion'].'</td>
			</tr>
		</table>
		
		<br>

		<h1>Acta de evaluación <small>Curso escolar '.$config['curso_actual'].'</small></h1>

		<table class="table-no-border">
			<tr>
				<td><strong>Unidad:</strong> '.$unidad.' ('.$curso.')</td>
				<td><strong>Evaluación:</strong> '.$evaluaciones[$evaluacion].'</td>
			</tr>
			<tr>
				<td><strong>Tutor/a:</strong> '.$tutor.'</td>
				<td><strong>Fecha:</strong> '.$fecha_alt.'</td>
				
			</tr>
		</table>

		<br>
		<br>

		'.$texto_acta.'

		En '.$config['centro_localidad'].' a '.$dia.' de '.$mes.' de '.$anio.'.
	</body>
	<div id="footer">
	  Página <span class="page-number"></span>
	</div>';
	$html = mb_convert_encoding($texto_acta, 'UTF-8', 'UTF-8');
	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->render();
	$dompdf->stream("Acta de evaluación $evaluacion - $unidad.pdf", array("Attachment" => 0));
}
?>