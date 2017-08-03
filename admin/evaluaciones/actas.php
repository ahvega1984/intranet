<?php
require('../../bootstrap.php');
require('inc_evaluaciones.php');

if (file_exists('config.php')) {
	include('config.php');
}

mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `evaluaciones_actas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `unidad` varchar(64) NOT NULL,
  `evaluacion` char(3) NOT NULL,
  `fecha` date NOT NULL,
  `texto_acta` mediumtext NOT NULL,
  `impresion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


if (isset($_POST['curso'])) $curso = $_POST['curso'];
if (isset($_POST['curso'])) $evaluacion = $_POST['evaluacion'];
if (isset($_GET['id'])) $id = $_GET['id'];

// Comprobamos el nivel educativo para cargar el modelo de acta predefinida por el centro
$result = mysqli_query($db_con, "SELECT cursos.nomcurso FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso WHERE unidades.nomunidad = '".$curso."' LIMIT 1");
$row = mysqli_fetch_array($result);
$nivel = $row['nomcurso'];
if (stristr($nivel, 'E.S.O.') == true) {
	if (! isset($config['evaluaciones']['acta_eso'])) {
		$texto_acta = '<h4>1.- Acuerdos de carácter general sobre el grupo:</h4><table class="table table-bordered"><thead><tr><th width="30%"><br></th><th>Muy bueno</th><th>Bueno</th><th>Regular</th><th>Malo</th><th>Muy malo</th></tr></thead><tbody><tr><td class="active">Rendimiento global</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active">Actitud general ante el estudio</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active">Actitud ante las normas y la convivencia</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td class="active">Asistencia a clase</td><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><b>Otros:</b></p><p><br></p><p><br></p><p><br></p><h4>2.- Acuerdos sobre el proceso individual</h4><p>2.1.- Necesidades educativas</p><table class="table table-bordered"><tbody><tr><td width="30%" class="active">Refuerzo educativo</td><td width="70%"><br></td></tr><tr><td class="active">A.C.I</td><td><br></td></tr><tr><td class="active">Propuestas entrada en el PMAR 2º / PMAR 3º / FP Básica</td><td><br></td></tr><tr><td class="active">Programa de enriquecimiento</td><td><br></td></tr><tr><td class="active">A tener en cuenta por el Depto. de Orientación</td><td><br></td></tr></tbody></table><p><br></p><h4>3.- Alumnos con problemas graves de convivencia y/o absentismo: <small>(marcar con X según cada caso)</small></h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th style="text-align: center; ">Faltas de asistencia</th><th style="text-align: center; ">Problemas disciplinarios</th></tr></thead><tbody><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center;"><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr><tr><td><br></td><td style="text-align: center; "><br></td><td style="text-align: center; "><br></td></tr></tbody></table><p><br></p><h4>4.- Otros alumnos con algún otro tipo de problema manifiesto (integración escolar, problemas con los compañeros, autoestima, ambiente familiar,...):</h4><table class="table table-bordered"><thead><tr><th width="50%">Nombre y apellidos</th><th>Tipo de problema</th></tr></thead><tbody><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>5.- Acuerdos tomados por el equipo docente:</h4><p><br></p><p><br></p><p><br></p><p><br></p><h4>Profesores asistentes</h4><table class="table table-bordered"><thead><tr><th>Nombre y apellidos</th><th>Firma</th><th>Nombre y apellidos</th><th>Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>Profesores ausentes</h4><table class="table table-bordered"><thead><tr><th>Nombre y apellidos</th><th>Firma</th><th>Nombre y apellidos</th><th>Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>';
	}
	else {
		$texto_acta = $config['evaluaciones']['acta_eso'];
	}
}
else if (stristr($nivel, 'Bachillerato') == true) {
	if (! isset($config['evaluaciones']['acta_bach'])) {
		$texto_acta = '<h4>1.- Acuerdos o consideraciones sobre el proceso de evaluación final individual:</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><h4>2.- Alumnado que se considera no reúne perfil de hacer estudios de Bachillerato</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><h4>3.- Alumnado excelente a felicitar</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><span style="color: inherit; font-size: 19px;">Profesores asistentes</span><br></p><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>Profesores ausentes</h4><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>';
	}
	else {
		$texto_acta = $config['evaluaciones']['acta_bach'];
	}
}
else if (stristr($nivel, 'F.P.') == true) {
	if (! isset($config['evaluaciones']['acta_fp'])) {
		$texto_acta = '<h4>1.- Acuerdos o consideraciones sobre el proceso de evaluación individual</h4><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><h4>Profesores asistentes</h4><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p><h4>Profesores ausentes</h4><table class="table table-bordered"><thead><tr><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th><th style="background-color: rgb(236, 240, 241);">Nombre y apellidos</th><th style="background-color: rgb(236, 240, 241);">Firma</th></tr></thead><tbody><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>';
	}
	else {
		$texto_acta = $config['evaluaciones']['acta_fp'];
	}
}


// ENVIO DEL FORMULARIO
if (isset($_POST['submit'])) {
	
	$evaluacion = $_POST['evaluacion'];
	$curso = $_POST['unidad'];
	$fecha = $_POST['fecha'];
	$exp_fecha = explode('-', $fecha);
	$fecha_sql = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
	$texto_acta = trim($_POST['texto_acta']);
	
	if (!empty($evaluacion) && !empty($curso) && !empty($fecha) && !empty($texto_acta)) {
		
		if (isset($id)) {
			
			$result = mysqli_query($db_con, "UPDATE evaluaciones_actas SET fecha='$fecha_sql', texto_acta='$texto_acta' WHERE id=$id LIMIT 1");
			
			if (!$result) $msg_error = "El acta no ha podido ser actualizado. Error: ".mysqli_error($db_con);
			else $msg_success = "El acta ha sido actualizado.";
		}
		else {
			
			$result = mysqli_query($db_con, "INSERT INTO evaluaciones_actas (unidad, evaluacion, fecha, texto_acta) VALUES ('$curso', '$evaluacion', '$fecha_sql', '$texto_acta')");
			
			if (!$result) $msg_error = "El acta no ha podido ser registrado. Error: ".mysqli_error($db_con);
			else $msg_success = "El acta ha sido registrado.";
		}
		
	}

}

// RECOGEMOS LOS DATOS SI SE TRATA DE UNA ACTUALIZACION
if (isset($id) && (isset($_GET['action']) && $_GET['action'] == 'edit')) {
	$result = mysqli_query($db_con, "SELECT unidad, evaluacion, texto_acta FROM evaluaciones_actas WHERE id = ".$id." LIMIT 1");
	
	if (!$result) {
		$msg_error = "El acta al que intenta acceder no existe.";
		unset($id);
	}
	else {
		$row = mysqli_fetch_array($result);
		
		$curso = $row['unidad'];
		$evaluacion = $row['evaluacion'];
		$texto_acta = $row['texto_acta'];
	}
}


// ELIMINAR UN ACTA
if (isset($id) && (isset($_GET['action']) && $_GET['action'] == 'delete')) {
	$result = mysqli_query($db_con, "DELETE FROM evaluaciones_actas WHERE id = ".$id." LIMIT 1");
	
	if (!$result) $msg_error = "El acta no ha podido ser eliminado. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido eliminado.";
}


$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Actas de evaluación <small>Actas de sesiones de evaluación</small></h2>
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
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			<?php if (!empty($curso) && !empty($evaluacion)): ?>
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">
				
				<h3>Redactar acta</h3>
				
				<form method="post" action="">
						
					<div class="well">
						
						<fieldset>
							
							<div class="row">
								
								<div class="col-sm-4">
								
									<div class="form-group">
										<label for="evaluacion">Evaluación</label>
										<input type="hidden" name="evaluacion" value="<?php echo $evaluacion ?>">
										<input type="text" class="form-control" id="texto_evaluacion" name="texto_evaluacion" value="<?php echo $evaluaciones[$evaluacion]; ?>" readonly>
									</div>
								
								</div>
								
								<div class="col-sm-2">
								
									<div class="form-group">
										<label for="unidad">Unidad</label>
										<input type="text" class="form-control" id="unidad" name="unidad" value="<?php echo $curso; ?>" readonly>
									</div>
								
								</div>
								
								<div class="col-sm-3">
								
									<div class="form-group">
										<label for="tutor">Tutor/a</label>
										<?php $result = mysqli_query($db_con, "SELECT tutor FROM FTUTORES WHERE unidad='$curso'"); ?>
										<?php $row = mysqli_fetch_array($result); ?>
										<?php $tutor = mb_convert_case($row['tutor'], MB_CASE_TITLE, "UTF-8"); ?>
										<input type="text" class="form-control" id="tutor" name="tutor" value="<?php echo $tutor; ?>" readonly>
									</div>
								
								</div>
								
								<div class="col-sm-3">
									
									<div class="form-group" id="datetimepicker1">
										<label for="fecha">Fecha</label>
										<div class="input-group">
											<input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo (isset($fecha)) ? $fecha : date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY">
											<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
										</div>
									</div>
									
								</div>
							
							</div>
							
							
							<div class="form-group">
								<textarea class="form-control" id="texto_acta" name="texto_acta">
								<?php if (isset($texto_acta)): ?>
								<?php echo $texto_acta; ?>
								<?php else: ?>
								<p><br></p>
								<?php endif; ?>
								</textarea>
							</div>
							
							<button type="submit" class="btn btn-primary" name="submit">Guardar</button>
							<button type="reset" class="btn btn-default">Cancelar</button>
							<a href="actas.php" class="btn btn-info">Ver actas</a> 	
						</fieldset>
						
					</div>
				
				</form>
				
				
			</div><!-- /.col-sm-12 -->
			
			<?php else: ?>
			
			<div class="col-sm-12">
				<?php if (stristr($_SESSION['cargo'],'2') == true): ?>
				<?php $result = mysqli_query($db_con, "SELECT ea.id, ea.unidad, t.tutor, ea.evaluacion, ea.fecha, ea.impresion FROM evaluaciones_actas AS ea JOIN FTUTORES AS t ON ea.unidad = t.unidad WHERE ea.unidad='".$_SESSION['mod_tutoria']['unidad']."'"); ?>
				<?php else: ?>
				<?php $result = mysqli_query($db_con, "SELECT ea.id, ea.unidad, t.tutor, ea.evaluacion, ea.fecha, ea.impresion FROM evaluaciones_actas AS ea JOIN FTUTORES AS t ON ea.unidad = t.unidad"); ?>
				<?php endif; ?>
				
				<?php if (mysqli_num_rows($result)): ?>
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Unidad</th>
								<th>Tutor/a</th>
								<th>Evaluación</th>
								<th>Fecha</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<tr>
								<td><?php echo $row['id']; ?></td>
								<td><?php echo $row['unidad']; ?></td>
								<td><?php echo mb_convert_case($row['tutor'], MB_CASE_TITLE, "UTF-8"); ?></td>
								<td><?php echo $evaluaciones[$row['evaluacion']]; ?></td>
								<td><?php echo $row['fecha']; ?></td>
								<td>
									<?php if (!$row['impresion']): ?>
									<a href="acta.php?id=<?php echo $row['id']; ?>&action=edit" data-bs="tooltip" title="Editar"><span class="fa fa-edit fa-fw fa-lg"></span></a>
									<?php endif; ?>
									<a href="imprimir.php?id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Imprimir"><span class="fa fa-print fa-fw fa-lg"></span></a>
									<a href="acta.php?id=<?php echo $row['id']; ?>&action=delete" data-bs="tooltip" title="Eliminar" data-bb="confirm-delete"><span class="fa fa-trash-o fa-fw fa-lg"></span></a>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
					<?php else: ?>
					
					<h3>No se ha redactado ningún acta de sesión de evaluación.</h3>
					<br>
					<br>
					
					<?php endif; ?>
				</div>
			
			</div><!-- /.col-sm-12 -->
			
			<?php endif; ?>
			
						
		</div><!-- /.row -->
			
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>

 <script>
 $(document).ready(function() {
 
 	// DATATABLES
	var table = $('.datatable').DataTable({
		"paging":   true,
    "ordering": true,
    "info":     false,
    
		"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
		
		"order": [[ 0, "desc" ]],
		
		"language": {
		            "lengthMenu": "_MENU_",
		            "zeroRecords": "No se ha encontrado ningún resultado con ese criterio.",
		            "info": "Página _PAGE_ de _PAGES_",
		            "infoEmpty": "No hay resultados disponibles.",
		            "infoFiltered": "(filtrado de _MAX_ resultados)",
		            "search": "Buscar: ",
		            "paginate": {
		                  "first": "Primera",
		                  "next": "Última",
		                  "next": "",
		                  "previous": ""
		                }
		        }
	});
 	
 	// EDITOR DE TEXTO
 	$('#texto_acta').summernote({
 		height: 500,
 		lang: 'es-ES',
		toolbar: [
			// [groupName, [list of button]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['media', ['link', 'picture', 'video']],
			['code', ['codeview']]
		],
 	});
 	
 	// DATETIMEPICKER
 	$(function () {
 	    $('#datetimepicker1').datetimepicker({
 	    	language: 'es',
 	    	pickTime: false,
 	    });
 	});
 	
 });
 </script>

</body>
</html>
