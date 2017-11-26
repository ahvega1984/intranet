<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$activo1="";
$activo2="";
$activo3="";
$activo4="";
$activo5="";

if (strstr($_SERVER['REQUEST_URI'],'cinforme.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'cdatos.php')==TRUE){ $activo2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'ccursos.php')==TRUE){ $activo3 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'pendientes')==TRUE){ $activo4 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'cexporta.php')==TRUE){ $activo5 = ' class="active" ';}

?>
<div class="container">
	
	<ul class="nav nav-tabs hidden-print">
		<li <?php echo $activo1;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/admin/informes/cinforme.php">
		Informe de un Alumno</a></li>
		<li <?php echo $activo2;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/admin/datos/cdatos.php">
		Datos de Alumnos y Grupos</a></li>
		<li <?php echo $activo3;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/ccursos.php">
		Listas de los Grupos</a></li>
		<li <?php echo $activo4;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/admin/pendientes/index.php">
		Alumnos con Asignaturas Pendientes</a></li>
		<li <?php echo $activo5;?>><a
			href="//<?php echo $config['dominio']; ?>/intranet/admin/cursos/cexporta.php">
		Exportar Datos</a></li>
	
	</ul>
</div>

