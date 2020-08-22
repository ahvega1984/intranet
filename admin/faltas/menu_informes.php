<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

// Comprobamos si el centro tiene ciclos formativos
$result_menu_ciclos = mysqli_query($db_con, "SELECT `cursos`.`nomcurso`, `unidades`.`nomunidad` FROM `cursos` JOIN `unidades` ON `cursos`.`idcurso` = `unidades`.`idcurso` WHERE `cursos`.`nomcurso` LIKE '%F.P.%' ORDER BY `cursos`.`nomcurso` ASC, `unidades`.`nomunidad` ASC");
$hayCiclosFormativos = mysqli_num_rows($result_menu_ciclos);

// Comprobamos si tiene perfil tutor y es de ciclo formativo
$esTutorCiclo = 0;
if (acl_permiso($_SESSION['cargo'], array('2'))) {
	$array_ciclos = array();
	while ($row_menu_ciclos = mysqli_fetch_array($result_menu_ciclos)) {
		array_push($array_ciclos, $row_menu_ciclos['nomunidad']);
	}

	if (in_array($_SESSION['mod_tutoria']['unidad'], $array_ciclos)) {
		$esTutorCiclo = 1;
	}
}
?>

<div class="hidden-print">

	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_grupos')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_grupos.php">Grupos</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_profesores.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_profesores.php">Profesores</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_materias.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_materias.php">Asignaturas</a></li>
		<?php if ($hayCiclosFormativos && (acl_permiso($_SESSION['cargo'], array('1')) || (acl_permiso($_SESSION['cargo'], array('2')) && $esTutorCiclo))): ?>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_ciclos.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_ciclos.php">Faltas Ciclos Formativos</a></li>
		<?php endif; ?>
		<?php if (acl_permiso($_SESSION['cargo'], array('1', '8'))): ?>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_faltas.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_faltas.php">Faltas no registradas</a></li>
		<?php endif; ?>
	</ul>

</div>
