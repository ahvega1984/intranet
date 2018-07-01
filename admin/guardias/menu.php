<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

if (acl_permiso($_SESSION['cargo'], array(1))) {

	$activo1="";
	$activo2="";
	$activo3="";
	$activo4="";

	if (strstr($_SERVER['REQUEST_URI'],'index_admin.php')==TRUE) {$activo1 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'procesa_guardias.php')==TRUE) {$activo1 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'consulta_profesores.php')==TRUE) {$activo2 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'consulta_fechas.php')==TRUE) {$activo3 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'informe_guardias.php')==TRUE) {$activo4 = ' class="active" ';}
?>
	<div class="container hidden-print">
		
		<?php if (acl_permiso($carg, array('1'))): ?>
		<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="far fa-cog fa-lg"></span></a>
		<?php endif; ?>
		
		<ul class="nav nav-tabs">
			<li <?php echo $activo1;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/guardias/index_admin.php">Registrar guardia</a></li>
			<li <?php echo $activo2;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/guardias/consulta_profesores.php">Consulta por profesores</a></li>
			<li <?php echo $activo3;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/guardias/consulta_fechas.php">Consulta por fechas</a></li>
			<li <?php echo $activo4;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/guardias/informe_guardias.php?menu=guardias">Informe sobre las Guardias</a></li>
		</ul>
	</div>
<?php
}
else {

	$activo1="";
	$activo2="";

	if (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) {$activo1 = ' class="active" ';}
	if (strstr($_SERVER['REQUEST_URI'],'consulta_profesores.php')==TRUE) {$activo2 = ' class="active" ';}
?>

	<div class="container hidden-print">
		<ul class="nav nav-tabs">
			<li <?php echo $activo1;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/guardias/index.php?diasem=<?php echo $_GET['diasem']; ?>&hora=<?php echo $_GET['hora']; ?>">Registrar guardia</a></li>
			<li <?php echo $activo2;?>><a href="//<?php echo $config['dominio']; ?>/intranet/admin/guardias/consulta_profesores.php?diasem=<?php echo $_GET['diasem']; ?>&hora=<?php echo $_GET['hora']; ?>">Histórico de guardias</a></li>
		</ul>
	</div>
<?php
}
?>
