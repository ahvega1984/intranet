<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODULO DE NOTICIAS -->

<h4><span class="fa fa-th-list fa-fw"></span> Noticias</h4>
<hr>

<!-- NOTICIAS DESTACADAS -->
<?php $result = mysqli_query($db_con, "SELECT id, titulo, contenido, fechapub, categoria from noticias where pagina like '%1%' and fechafin >= '".date('Y-m-d H:i:s')."' ORDER BY fechapub DESC"); ?>
<?php $noticias_destacadas = mysqli_num_rows($result); ?>
<?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)): ?>
<article class="well">
	<h4 class="media-heading h5"><a href="admin/noticias/noticia.php?id=<?php echo $row['id']; ?>&amp;widget=1"><span class="fa fa-star fa-fw"></span> <?php echo $row['titulo']; ?></a></h4>
	<h6 class="text-muted"><?php echo ($row['categoria']) ? $row['categoria'] : 'Sin categoría'; ?>&nbsp;&nbsp;·&nbsp;&nbsp;<?php echo strftime('%e %B', (strtotime($row['fechapub']))); ?></h6>
</article>
<?php endwhile; ?>
<?php mysqli_free_result($result); ?>

<?php echo ($noticias_destacadas) ? '<hr>' : ''; ?>

<!-- ÚLTIMAS NOTICIAS -->
<?php $result = mysqli_query($db_con, "SELECT id, titulo, contenido, fechapub, categoria FROM noticias WHERE fechapub <= '".date('Y-m-d H:i:s')."' AND pagina LIKE '%1%' AND id NOT IN (SELECT id FROM noticias WHERE pagina LIKE '%1%' AND fechafin >= '".date('Y-m-d H:i:s')."' ORDER BY fechapub DESC) ORDER BY fechapub DESC LIMIT 6"); ?>
<?php if (mysqli_num_rows($result)): ?>
	
<?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)): ?>
		
<?php $exp_profesor = explode(',', $row['autor']); ?>
<?php $profesor = $exp_profesor[1].' '.$exp_profesor[0]; ?>

<article>
	<h4 class="h5"><a href="admin/noticias/noticia.php?id=<?php echo $row['id']; ?>&amp;widget=1"><?php echo $row['titulo']; ?></a></h4>
	<h6 class="text-muted"><?php echo ($row['categoria']) ? $row['categoria'] : 'Sin categoría'; ?>&nbsp;&nbsp;·&nbsp;&nbsp;<?php echo strftime('%e %B', (strtotime($row['fechapub']))); ?></h6>
</article>

<hr>
		
<?php endwhile; ?>
<?php mysqli_free_result($result); ?>
	
<?php else: ?>

	<div class="text-center">
		<br><br>
		<span class="fa fa-th-list fa-5x text-muted"></span>
		<p class="lead text-muted">No se ha publicado ninguna noticia.</p>
		<br><br>
	</div>

<?php endif; ?>

<a class="btn btn-primary btn-sm" href="admin/noticias/redactar.php">Nueva noticia</a>
<a class="btn btn-default btn-sm" href="admin/noticias/index.php">Ver todas las noticias</a>

<!-- FIN MODULO DE NOTICIAS -->
