<?php
ini_set("memory_limit","1024M");
require('../../bootstrap.php');
require_once("../../pdf/dompdf_config.inc.php");

define("DOMPDF_ENABLE_PHP", true);


if (isset($_GET['depto']) && acl_permiso($_SESSION['cargo'], array(1))) {
	$departamento = mysqli_real_escape_string($db_con, $_GET['depto']);
	
	mysqli_query($db_con, "UPDATE r_departamento SET impreso = '1' WHERE departamento = '$departamento'");
	
	$result = mysqli_query($db_con, "SELECT contenido, fecha, departamento FROM r_departamento WHERE departamento = '$departamento' ORDER BY fecha DESC");
	
}
elseif (isset($_GET['id']) && isset($_GET['imprimir']) && acl_permiso($_SESSION['cargo'], array('1','4'))) {
	$id = mysqli_real_escape_string($db_con, $_GET['id']);
	
	mysqli_query($db_con, "UPDATE r_departamento SET impreso = '1' WHERE id = '$id'");
	
	$result = mysqli_query($db_con, "SELECT contenido, fecha, departamento FROM r_departamento WHERE id = '$id'");
}
elseif (isset($_GET['id'])) {
	$id = mysqli_real_escape_string($db_con, $_GET['id']);
	
	$result = mysqli_query($db_con, "SELECT contenido, fecha, departamento FROM r_departamento WHERE id = '$id'");
}

if ($result) {
	$html .= '<html><body>';
	
	$html .= '
	<style type="text/css">
	html {
	  margin: 0 !important;
	}
	body {
	  font-family: Arial, Helvetica, sans-serif !important;
	  font-size: 11pt !important;
	  margin: 20mm 20mm 30mm 25mm !important;
	}
	</style>';
	
	$sonVarias = 0;
	while ($row = mysqli_fetch_array($result)) {
		$sonVarias++;
		
		if ($sonVarias > 2) {
			$html .= '<div style="page-break-before: always;"></div>';
		}
		
		$html .= mb_convert_encoding($row['contenido'], 'UTF-8', 'UTF-8');
	}
	
	$html .= '</body></html>';
	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->render();
	if ($sonVarias > 2) {
		$dompdf->stream("Actas $departamento.pdf", array("Attachment" => 0));
	}
	else {
		$departamento = $row['departamento'];
		$fecha = $row['fecha'];
		$dompdf->stream("Actas $departamento - $fecha.pdf", array("Attachment" => 0));
	}
}
?>