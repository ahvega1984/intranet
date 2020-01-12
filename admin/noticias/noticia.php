<?php
require('../../bootstrap.php');

$id_noticia = limpiarInput($_GET['id'], 'numeric');

include("../../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Noticias <small>Noticias y novedades del centro</small></h2>
		</div>
		
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<?php $result = mysqli_query($db_con, "SELECT `titulo`, `contenido`, `autor`, `fechapub` FROM `noticias` WHERE `id` = ".$id_noticia." LIMIT 1"); ?>
				
				<?php if (mysqli_num_rows($result)): ?>
					
					<?php $row = mysqli_fetch_array($result); ?>
					<?php $exp_profesor = explode(',', $row['autor']); ?>
					<?php $profesor = $exp_profesor[1].' '.$exp_profesor[0]; ?>
					<?php mysqli_query($db_con, "UPDATE `noticias` SET `vistas` = `vistas` + 1 WHERE `id` = ".$id_noticia." LIMIT 1"); ?>
					
					<h3><?php echo $row['titulo']; ?></h3>
					<h5 class="text-muted">Por <?php echo nomprofesor($profesor); ?> el <?php echo fecha_actual2($row['fechapub']); ?></h5>
					
					<br>
					
					<?php echo stripslashes(html_entity_decode($row['contenido'], ENT_QUOTES, 'UTF-8')); ?>
					
					<br>
					<br>
					
					<?php mysqli_free_result($result); ?>
					
					<div class="hidden-print">
						<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
						<?php if(stristr($_SESSION['cargo'],'1') == TRUE || $_SESSION['profi'] == $row['autor']): ?>
						<a class="btn btn-info" href="redactar.php?id=<?php echo $_GET['id']; ?>">Editar</a>
						<a class="btn btn-danger" href="index.php?id=<?php echo $_GET['id']; ?>&borrar=1">Eliminar</a>
						<?php endif; ?>
						<?php if(isset($_GET['widget']) && $_GET['widget'] == 1): ?>
						<a class="btn btn-default" href="../../index.php">Volver</a>
						<?php else: ?>
						<a class="btn btn-default" href="index.php">Volver</a>
						<?php endif; ?>
					</div>
					
				<?php else: ?>
					
					<h3>La noticia a la que intenta acceder no existe.</h3>
					<h4 class="text-muted">Será redirigido automáticamente a la página anterior . . .</h4>
					
					<meta http-equiv="refresh" content="5;url=javascript:history.back()">
					
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					
				<?php endif; ?>
				
			</div><!-- /.col-sm-12 -->
					
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>

</body>
</html>
