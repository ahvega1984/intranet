<?php
require_once('bootstrap.php');

header('Content-Type: text/html; charset=UTF-8');
// Fichero que realiza la consulta en la base de datos y devuelve los resultados
if(isset($_POST["word"]))
{

	$search_query = limpiarInput($_POST["word"], 'alphanumericspecial');

	if($_POST["word"]{0}=="*"){
		$result=mysqli_query($db_con, "SELECT CONCAT(apellidos,', ',nombre) AS alumno, claveal, unidad FROM alma WHERE CONCAT(apellidos,' ',nombre) LIKE '%".substr($search_query,1)."%' and CONCAT(apellidos,' ',nombre) <>'".$search_query."' OR claveal like '".$_POST["word"]."%' ORDER BY alumno LIMIT 10");}
	else{
		$result=mysqli_query($db_con, "SELECT CONCAT(apellidos,', ',nombre) AS alumno, claveal, unidad FROM alma WHERE (CONCAT(apellidos,' ',nombre) LIKE '%".$search_query."%' and CONCAT(apellidos,' ',nombre) like '%".$search_query."%') or (CONCAT(nombre,' ',apellidos) LIKE '%".$search_query."%' and CONCAT(nombre,' ',apellidos) like '%".$search_query."%') OR claveal like '".$search_query."%' ORDER BY alumno LIMIT 10");
	}
	echo '<ul class="list-group">';
	while ($row = mysqli_fetch_array($result)){
		// Mostramos las lineas que se mostraran en el desplegable.
		$datos=$row[0];
		$clave_al=$row[1];
		$curso_al=$row[2];

		if ($foto = obtener_foto_alumno($clave_al)) {
			$foto_alumno = '<img class="img-thumbnail" src="xml/fotos/'.$foto.'" style="width: 32px !important;" alt="">';
		}
		else {
			$foto_alumno = '<span class="img-thumbnail far fa-user fa-fw" style="width: 32px !important;"></span>';
		}

		echo '<a href="admin/informes/index.php?claveal='.$clave_al.'&todos=Ver Informe Completo" class="list-group-item">';
		echo '<span class="pull-right badge badge-default" style="margin-top: 8px;">'.$curso_al.'</span> '.$foto_alumno.' '.$datos;
		echo '</a>';
	}
	echo '</ul>';
}
?>
