<?php
require('../../bootstrap.php');
require('inc_evaluaciones.php');

if (file_exists('config.php')) {
	include('config.php');
}

if (isset($_POST['curso'])) $curso = $_POST['curso'];
if (isset($_POST['evaluacion']) && !empty($_POST['evaluacion'])) $evaluacion = $_POST['evaluacion'];

$esTutorUnidad = 0;
if (stristr($_SESSION['cargo'],'2') == true) {
	
	if (isset($curso) && $curso == $_SESSION['mod_tutoria']['unidad']) {
		$esTutorUnidad = 1;
	}
	
}

include("../../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Actas de evaluación <small>Sesiones de evaluación</small></h2>
		</div>
		
		<!-- MENSAJES -->
		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<?php if (isset($msg_success)): ?>
		<div class="alert alert-success">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		
		<div class="row hidden-print">
		
			<div class="col-sm-12">
			
				<form id="form" method="post" action="">
					
					<fieldset>
					
						<div class="well">
							
							<legend>Seleccione unidad y evaluación</legend>
							
							<div class="row">
								
								<div class="col-sm-6">
								
									<div class="form-group">
										<label for="curso">Unidad</label>
										<?php if (strstr($_SESSION['cargo'], '1') == true): ?>
										<?php $result = mysqli_query($db_con, "SELECT DISTINCT grupo AS unidad FROM profesores ORDER BY grupo ASC"); ?>
										<?php else: ?>
										<?php $result = mysqli_query($db_con, "SELECT DISTINCT unidad FROM FTUTORES WHERE tutor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY unidad ASC"); ?>
										<?php endif; ?>
										<select class="form-control" id="curso" name="curso" onchange="submit()">
											<option value=""></option>
											<?php while ($row = mysqli_fetch_array($result)): ?>
											<option value="<?php echo $row['unidad']; ?>" <?php echo (isset($curso) && $curso == $row['unidad']) ? 'selected' : ''; ?>><?php echo $row['unidad']; ?></option>
											<?php endwhile; ?>
										</select>
									</div>
									
								</div>
								
								<div class="col-sm-6">
									
									<div class="form-group">
										<label for="evaluacion">Evaluación</label>
										<select class="form-control" id="evaluacion" name="evaluacion" onchange="submit()">
											<option value=""></option>
											<?php foreach ($evaluaciones as $eval => $desc): ?>
											<option value="<?php echo $eval; ?>" <?php echo (isset($evaluacion) && $evaluacion == $eval) ? 'selected' : ''; ?>><?php echo $desc; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									
								</div>
								
							</div>
								
							
						</div><!-- /.well -->
						
					</fieldset>
					
				</form>
				
			</div><!-- /.col-sm-12 -->
			
		</div><!-- /.row -->
		

		<?php if (isset($curso) && isset($evaluacion)): ?>
		<div class="row">
		
			<div class="col-sm-12">
			
				<div class="visible-print">
					<h3><?php echo $evaluaciones[$evaluacion]; ?>  de <?php echo $curso; ?></h3>
				</div>
				
				<div class="hidden-print">
					<?php if (stristr($_SESSION['cargo'],'1') == true || (stristr($_SESSION['cargo'],'2') == true && $esTutorUnidad)): ?>
					<form class="form-horizontal" method="post" action="actas.php">
						<input type="hidden" name="curso" value="<?php echo $curso; ?>">
						<input type="hidden" name="evaluacion" value="<?php echo $evaluacion; ?>">
						<button type="submit" class="btn btn-primary" name="enviar">Redactar acta</button>
					</form>
					<?php else: ?>
					<a href="#" class="btn btn-primary" onclick="javascript:print();">Imprimir</a>
					<?php endif; ?>
				</div>
				
			</div><!-- /.col-sm-12 -->
			
		</div><!-- /.row -->
		<?php endif; ?>
	
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>
 
</body>
</html>
