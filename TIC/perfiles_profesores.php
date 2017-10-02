<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

include("../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Perfiles de profesores</small></h2>
		</div>
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-8 col-sm-offset-2">
				
				<div class="table-responsive">	
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Profesor</th>
								<th>Usuario</th>
								<th>Contraseña Gesuser</th>
								<th>Contraseña Moodle</th>
							</tr>
						</thead>
						<tbody>
							<?php if (stristr($_SESSION['cargo'],'1') == TRUE) $sql_where = ''; else $sql_where = 'AND idea=\''.$_SESSION['ide'].'\''; ?>
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT idea, nombre, dni, departamento FROM departamentos WHERE departamento <> 'Admin' $sql_where ORDER BY nombre ASC"); ?>
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<tr>
								<td><?php echo $row['nombre']; ?></td>
								<td><?php echo $row['idea']; ?></td>
								<td><?php echo $row['idea']; ?></td>
								<td><?php echo $row['dni']; ?></td>
							</tr>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</tbody>
					</table>
				</div>
				
				<div class="hidden-print">
					<a href="#" class="btn btn-primary" onclick="javascript:print();">Imprimir</a>
				</div>
					
				
			</div><!-- /.col-sm-6 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../pie.php"); ?>

</body>
</html>
