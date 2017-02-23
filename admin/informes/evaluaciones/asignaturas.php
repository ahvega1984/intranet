<?php
require('../../../bootstrap.php');


if (isset($_GET['evaluacion'])) $evaluacion_seleccionada = $_GET['evaluacion'];
else $evaluacion_seleccionada = 'evi';

// Si el usuario desea eliminar los datos y recalcular
if (isset($_GET['recalcular']) && $_GET['recalcular']) {
	mysqli_query($db_con, "DROP TABLE `informe_evaluaciones_unidades_".$evaluacion_seleccionada."`;");
}

// Inicializamos variables
$evaluaciones = array('evi' => 'notas0', '1ev' => 'notas1', '2ev' => 'notas2', 'ord' => 'notas3', 'ext' => 'notas4');
$evaluacionSinNotas = 1;
$resultados_evaluaciones = array();

// Consultamos la evaluación seleccionada
$result = mysqli_query($db_con, "SELECT * FROM notas WHERE ".$evaluaciones[$evaluacion_seleccionada]." IS NOT NULL");
$existenNotas = mysqli_num_rows($result);
mysqli_free_result($result);

if ($existenNotas) {
	
	// Comprobamos si se ha creado el informe
	$result_informe2 = mysqli_query($db_con, "SELECT * FROM `informe_evaluaciones_asignaturas_".$evaluacion_seleccionada."` JOIN cursos ON `informe_evaluaciones_asignaturas_".$evaluacion_seleccionada."`.idcurso = cursos.idcurso JOIN unidades ON `informe_evaluaciones_asignaturas_".$evaluacion_seleccionada."`.idunidad = unidades.idunidad");
	if (mysqli_num_rows($result_informe2)) {
		$resultados_evaluaciones = mysqli_fetch_all($result_informe2, MYSQLI_ASSOC);
	}
	else {
	/*
		// Creamos las tablas necesarias para el funcionamiento del módulo
		mysqli_query($db_con, "CREATE TABLE `informe_evaluaciones_asignaturas_".$evaluacion_seleccionada."` (
		  `idcurso` int(12) NOT NULL,
		  `idunidad` int(12) NOT NULL,
		  `total_alumnos` tinyint(4) NOT NULL,
		  `repiten_alumnos` tinyint(4) NOT NULL,
		  `cero_suspensos` tinyint(4) NOT NULL,
		  `uno_suspensos` tinyint(4) NOT NULL,
		  `dos_suspensos` tinyint(4) NOT NULL,
		  `tres_suspensos` tinyint(4) NOT NULL,
		  `cuatro_suspensos` tinyint(4) NOT NULL,
		  `cinco_suspensos` tinyint(4) NOT NULL,
		  `seis_suspensos` tinyint(4) NOT NULL,
		  `siete_suspensos` tinyint(4) NOT NULL,
		  `ocho_suspensos` tinyint(4) NOT NULL,
		  `nueve_o_mas_suspensos` tinyint(4) NOT NULL,
		  `promocionan` decimal(5,2) NOT NULL,
		  `titulan` tinyint(4) NOT NULL,
		  PRIMARY KEY(`idcurso`,`idunidad`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
	*/
		// Obtenemos las unidades del centro
		if ($evaluacion_seleccionada == 'evi') {
			$result_unidades = mysqli_query($db_con, "SELECT cursos.idcurso, cursos.nomcurso, unidades.idunidad, unidades.nomunidad FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso WHERE cursos.nomcurso LIKE 'E.S.O.' OR cursos.nomcurso LIKE 'Bachillerato' ORDER BY cursos.nomcurso ASC, unidades.nomunidad ASC");
		}
		else {
			$result_unidades = mysqli_query($db_con, "SELECT cursos.idcurso, cursos.nomcurso, unidades.idunidad, unidades.nomunidad FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso ORDER BY cursos.nomcurso ASC, unidades.nomunidad ASC");
		}
		$row_unidades = mysqli_fetch_all($result_unidades, MYSQLI_ASSOC);
		
		foreach ($row_unidades as $unidades) {
			
			$idcurso = $unidades['idcurso'];
			$curso = $unidades['nomcurso'];
			
			$idunidad = $unidades['idunidad'];
			$unidad = $unidades['nomunidad'];
			
			// Obtenemos las asignaturas de la unidad
			$result_asignaturas_unidad = mysqli_query($db_con, "SELECT DISTINCT codigo, nombre, abrev FROM materias WHERE curso = '".$unidades['nomcurso']."' AND abrev NOT LIKE '%\_%' ORDER BY codigo ASC");
			$row_asignaturas_unidad = mysqli_fetch_all($result_asignaturas_unidad, MYSQLI_ASSOC);
			
			$columna = 0;
			foreach ($row_asignaturas_unidad as $asignatura) {
				$unidades['asignaturas'][$columna]['codigo'] = $asignatura['codigo'];
				$unidades['asignaturas'][$columna]['nombre'] = $asignatura['nombre'];
				$unidades['asignaturas'][$columna]['abrev'] = $asignatura['abrev'];
				
				$unidades['asignaturas'][$columna]['matriculados'] = 0;
				$unidades['asignaturas'][$columna]['aprobados'] = 0;
				$unidades['asignaturas'][$columna]['suspensos'] = 0;
				
				// Obtenemos el número de alumnos
				$result_alumnos_unidad = mysqli_query($db_con, "SELECT alma.claveal1, notas.".$evaluaciones[$evaluacion_seleccionada]." FROM alma JOIN notas ON alma.claveal1 = notas.claveal WHERE alma.unidad = '".$unidades['nomunidad']."' AND alma.curso = '".$unidades['nomcurso']."'");
				$unidades['total_alumnos'] = mysqli_num_rows($result_alumnos_unidad);
				
				// Obtenemos las notas de los alumnos
				$row_alumnos_unidad = mysqli_fetch_all($result_alumnos_unidad, MYSQLI_ASSOC);
				foreach ($row_alumnos_unidad as $alumno) {

					$alumno[$evaluaciones[$evaluacion_seleccionada]] = rtrim($alumno[$evaluaciones[$evaluacion_seleccionada]], ';');
					
					$exp_asignaturas = explode(';', $alumno[$evaluaciones[$evaluacion_seleccionada]]);
					
					foreach ($exp_asignaturas as $asignatura_alumno) {
						
						$exp_notas_asignatura = explode(':', trim($asignatura_alumno));
						$idasignatura = trim($exp_notas_asignatura[0]);
						$idcalificacion = trim($exp_notas_asignatura[1]);
						
						if ($idasignatura == $asignatura['codigo']) {
							$unidades['asignaturas'][$columna]['matriculados']++;
							
							if ($idcalificacion != '') {
								$result_calificacion = mysqli_query($db_con, "SELECT abreviatura FROM calificaciones WHERE codigo = '".$idcalificacion."'");
								$row_calificacion = mysqli_fetch_array($result_calificacion);
								if (! intval($row_calificacion['abreviatura']) || $row_calificacion['abreviatura'] < 5) {
									$unidades['asignaturas'][$columna]['suspensos']++;
								}
								else {
									$unidades['asignaturas'][$columna]['aprobados']++;
								}
							}
						}
					}
				}
				
				if (!$unidades['asignaturas'][$columna]['matriculados']) {
					$unidades['asignaturas'][$columna]['matriculados'] = -1;
					$unidades['asignaturas'][$columna]['aprobados'] = -1;
					$unidades['asignaturas'][$columna]['suspensos'] = -1;
				}
				
				$columna++;
			}

			/*
			// Añadimos a la base de datos
			mysqli_query($db_con, "INSERT INTO `informe_evaluaciones_asignaturas_".$evaluacion_seleccionada."` (`idcurso`, `idunidad`, `total_alumnos`, `repiten_alumnos`, `cero_suspensos`, `uno_suspensos`, `dos_suspensos`, `tres_suspensos`, `cuatro_suspensos`, `cinco_suspensos`, `seis_suspensos`, `siete_suspensos`, `ocho_suspensos`, `nueve_o_mas_suspensos`, `promocionan`, `titulan`) VALUES ('".$idcurso."', '".$idunidad."', '".$unidades['total_alumnos']."', '".$unidades['repiten_alumnos']."', '".$unidades['cero_suspensos']."', '".$unidades['uno_suspensos']."', '".$unidades['dos_suspensos']."', '".$unidades['tres_suspensos']."', '".$unidades['cuatro_suspensos']."', '".$unidades['cinco_suspensos']."', '".$unidades['seis_suspensos']."', '".$unidades['siete_suspensos']."', '".$unidades['ocho_suspensos']."', '".$unidades['nueve_o_mas_suspensos']."', '".$unidades['promocionan']."', '".$unidades['titulan']."');");
			*/
			
			// Añadimos la información al array
			array_push($resultados_evaluaciones, $unidades);
		}
		
		mysqli_free_result($result_cursos);
	}
	
	$evaluacionSinNotas = 0;
}

include("../../../menu.php");
include("menu.php");
?>	
	<style type="text/css">
	td.active {
		background-color: #dedede !important;
	}
	</style>
	
	<div class="container-fluid">
		
		<div class="page-header">
			<h2>Informes de evaluaciones <small>Estadísticas por asignaturas</small></h2>
		</div>
		
		<div class="row">
			<div class="col-sm-10">
				<ul class="nav nav-pills">
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], 'evi') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=evi">Evaluación Inicial</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], '1ev') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=1ev">1ª Evaluación</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], '2ev') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=2ev">2ª Evaluación</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], 'ord') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=ord">Evaluación Ordinaria</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], 'ext') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=ext">Evaluación Extraordinaria</a></li>
				</ul>
			</div>
			
			<div class="col-sm-2">
				<?php if (! $evaluacionSinNotas): ?>
				<a href="index.php?evaluacion=<?php echo $evaluacion_seleccionada; ?>&amp;recalcular=1" class="btn btn-sm btn-warning pull-right"><span class="fa fa-refresh fa-fw"></span> Recalcular</a>
				<?php endif; ?>
			</div>
		</div>
		
		<br>
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<?php if ($evaluacionSinNotas): ?>
				<div class="text-center">
					<br><br><br>
					<p class="lead">No se han importado las notas de esta evaluación</p>
					<br><br><br><br>
				</div>
				<?php else: ?>
				
				<?php 
				$flag = 0;
				$num_unidades = 1;
				$num_columnas = 0;
				$nomcurso = "";
				?>
				<?php foreach ($resultados_evaluaciones as $evaluacion): ?>
				
				<?php if ($nomcurso != $evaluacion['nomcurso']): ?>
				<?php if ($flag == 1): ?>
				</tbody>
					<tfoot>
						<tr>
							<th>Totales</th>
							<th><?php echo $total_alumnos; ?></th>
							<?php for ($i = 0; $i < $num_columnas; $i++): ?>
							<th class="text-center"><?php echo ${matriculados.$i}; ?></th>
							<th class="text-center text-success"><?php echo ${aprobados.$i}; ?></th>
							<th class="text-center text-danger"><?php echo ${suspensos.$i}; ?></th>
							<?php unset(${matriculados.$i}); ?>
							<?php unset(${aprobados.$i}); ?>
							<?php unset(${suspensos.$i}); ?>
							<?php endfor; ?>
						</tr>
					</tfoot>
				</table>
				
				<hr>
				
				<?php endif; ?>
				<?php 
				$flag = 1;
				$num_unidades = 1;
				$num_columnas = 0;
				$total_alumnos = 0;
				?>
				
				<h4 class="text-info"><?php echo $evaluacion['nomcurso']; ?></h4>
				
				<table class="table table-bordered table-fixed" style="font-size: 11px;">
					<thead>
						<tr>
							<th width="9.15%" rowspan="2">Unidad</th>
							<th width="6.15%" rowspan="2">T.A.</th>
							<?php for ($i = 0; $i < count($evaluacion['asignaturas']); $i++): ?>
							<?php $tamano_celda = (84.7 / count($evaluacion['asignaturas'])); ?>
							<th class="text-center" width="<?php echo $tamano_celda; ?>%" colspan="3"><abbr data-bs="tooltip" data-html="true" title="<?php echo $evaluacion['asignaturas'][$i]['nombre']; ?><br>[Cód.: <?php echo $evaluacion['asignaturas'][$i]['codigo']; ?>]"><?php echo $evaluacion['asignaturas'][$i]['abrev']; ?></abbr></th>
							<?php endfor; ?>
						</tr>
						<tr>
							<?php for ($i = 0; $i < count($evaluacion['asignaturas']); $i++): ?>
							<th class="text-center" width="4.14%"><abbr data-bs="tooltip" title="Matriculados">M.</abbr></th>
							<th class="text-center" width="4.14%"><abbr data-bs="tooltip" title="Aprobados">A.</abbr></th>
							<th class="text-center" width="4.14%"><abbr data-bs="tooltip" title="Suspensos">S.</abbr></th>
							<?php endfor; ?>
						</tr>
					</thead>
					<tbody>
				<?php endif; ?>
						<tr>
							<th><?php echo $evaluacion['nomunidad']; ?></th>
							<td><?php echo $evaluacion['total_alumnos']; ?></td>
							<?php for ($i = 0; $i < count($evaluacion['asignaturas']); $i++): ?>
							<?php if ($evaluacion['asignaturas'][$i]['matriculados'] < 0): ?>
							<td class="text-center active"><?php $evaluacion['asignaturas'][$i]['matriculados'] = 0; ?></td>
							<td class="text-center active"><?php $evaluacion['asignaturas'][$i]['aprobados'] = 0; ?></td>
							<td class="text-center active"><?php $evaluacion['asignaturas'][$i]['suspensos'] = 0; ?></td>
							<?php else: ?>
							<td class="text-center"><?php echo $evaluacion['asignaturas'][$i]['matriculados']; ?></td>
							<td class="text-center text-success"><?php echo $evaluacion['asignaturas'][$i]['aprobados']; ?></td>
							<td class="text-center text-danger"><?php echo $evaluacion['asignaturas'][$i]['suspensos']; ?></td>
							<?php endif; ?>
							<?php endfor; ?>
						</tr>
				
				<?php 
				$total_alumnos += $evaluacion['total_alumnos'];
				$num_columnas = count($evaluacion['asignaturas']);
				for ($i = 0; $i < count($evaluacion['asignaturas']); $i++) {
					${matriculados.$i} += $evaluacion['asignaturas'][$i]['matriculados'];
					${aprobados.$i} += $evaluacion['asignaturas'][$i]['aprobados'];
					${suspensos.$i} += $evaluacion['asignaturas'][$i]['suspensos'];
				}
				$num_unidades++;
				$nomcurso = $evaluacion['nomcurso']; 
				?>
				<?php endforeach; ?>
				
				</tbody>
					<tfoot>
						<tr>
							<th>Totales</th>
							<th><?php echo $total_alumnos; ?></th>
							<?php for ($i = 0; $i < $num_columnas; $i++): ?>
							<th class="text-center"><?php echo ${matriculados.$i}; ?></th>
							<th class="text-center text-success"><?php echo ${aprobados.$i}; ?></th>
							<th class="text-center text-danger"><?php echo ${suspensos.$i}; ?></th>
							<?php unset(${matriculados.$i}); ?>
							<?php unset(${aprobados.$i}); ?>
							<?php unset(${suspensos.$i}); ?>
							<?php endfor; ?>
						</tr>
					</tfoot>
				</table>
				<?php endif; ?>
			</div>
			
		</div>
		
	</div>

	<?php include("../../../pie.php"); ?>

</body>
</html>