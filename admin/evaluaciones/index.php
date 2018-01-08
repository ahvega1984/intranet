<?php
require('../../bootstrap.php');
acl_acceso($_SESSION['cargo'], array(1, 2));

require('inc_evaluaciones.php');

if (file_exists('config.php')) {
	include('config.php');
}

if (isset($_POST['curso'])) $curso = $_POST['curso'];
if (isset($_POST['evaluacion']) && !empty($_POST['evaluacion'])) $evaluacion = $_POST['evaluacion'];

$esTutorUnidad = 0;
if (stristr($_SESSION['cargo'],'2') == true) {

	// COMPROBAMOS SI ES UN PMAR
	$esPMAR = (stristr($curso, ' (PMAR)') == true) ? 1 : 0;
	if ($esPMAR) {
		$curso_pmar = str_ireplace(' (PMAR)', '', $curso);

		if (isset($curso) && $curso_pmar == $_SESSION['mod_tutoria']['unidad']) {
			$esTutorUnidad = 1;
		}
	}
	else {
		if (isset($curso) && $curso == $_SESSION['mod_tutoria']['unidad']) {
			$esTutorUnidad = 1;
		}
	}

	
	
}

// COMPROBAMOS SI EL ACTA HA SIDO RELLENADO Y REDIRIGIMOS AL USUARIO
if (isset($_POST['curso']) && isset($_POST['curso'])) {
	
	$versionImprimir = 0;

	$result_acta = mysqli_query($db_con, "SELECT id, impresion FROM evaluaciones_actas WHERE unidad = '".$curso."' AND evaluacion = '".$evaluacion."' LIMIT 1");
	if (mysqli_num_rows($result_acta)) {
		$row_acta = mysqli_fetch_array($result_acta);

		if ($row_acta['impresion']) {
			$versionImprimir = 1;
			$id = $row_acta['id'];
		}
		
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
										<?php
										if (strstr($_SESSION['cargo'], '1') == true) {
											$result = mysqli_query($db_con, "SELECT DISTINCT nomunidad FROM unidades ORDER BY nomunidad ASC"); 
											$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo WHERE m.abrev LIKE '%**%' ORDER BY u.nomunidad ASC");
										}
										else {
											$result = mysqli_query($db_con, "SELECT DISTINCT unidad AS nomunidad FROM FTUTORES WHERE tutor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY unidad ASC");
											$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo JOIN FTUTORES AS t ON u.nomunidad = t.unidad WHERE m.abrev LIKE '%**%' AND t.tutor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY u.nomunidad ASC");											
										}
										
										$array_unidades = array();
										while ($row = mysqli_fetch_array($result)) {
											array_push($array_unidades, $row);
										}
										while ($row = mysqli_fetch_array($result_pmar)) {
											array_push($array_unidades, $row);
										}

										asort($array_unidades);
										?>
										<select class="form-control" id="curso" name="curso" onchange="submit()">
											<option value=""></option>
											<?php foreach ($array_unidades as $unidad): ?>
											<option value="<?php echo $unidad['nomunidad']; ?>" <?php echo (isset($curso) && $curso == $unidad['nomunidad']) ? 'selected' : ''; ?>><?php echo $unidad['nomunidad']; ?></option>
											<?php endforeach; ?>
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
					<?php if (! $versionImprimir): ?>
					<?php if (stristr($_SESSION['cargo'],'1') == true || (stristr($_SESSION['cargo'],'2') == true && $esTutorUnidad)): ?>
					<form class="form-horizontal" method="post" action="actas.php">
						<input type="hidden" name="curso" value="<?php echo $curso; ?>">
						<input type="hidden" name="evaluacion" value="<?php echo $evaluacion; ?>">
						<button type="submit" class="btn btn-primary" name="enviar">Redactar acta</button>
					</form>
					<?php endif; ?>
					<?php else: ?>
					<a href="imprimir.php?id=<?php echo $id; ?>" class="btn btn-primary">Imprimir acta</a>
					<?php endif; ?>
				</div>
				
			</div><!-- /.col-sm-12 -->
			
		</div><!-- /.row -->
		<?php endif; ?>
	
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>
 
</body>
</html>
