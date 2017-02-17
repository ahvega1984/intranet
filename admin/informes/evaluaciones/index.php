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
	$result_informe2 = mysqli_query($db_con, "SELECT * FROM `informe_evaluaciones_unidades_".$evaluacion_seleccionada."` JOIN cursos ON `informe_evaluaciones_unidades_".$evaluacion_seleccionada."`.idcurso = cursos.idcurso JOIN unidades ON `informe_evaluaciones_unidades_".$evaluacion_seleccionada."`.idunidad = unidades.idunidad");
	if (mysqli_num_rows($result_informe2)) {
		$resultados_evaluaciones = mysqli_fetch_all($result_informe2, MYSQLI_ASSOC);
	}
	else {
		
		// Creamos las tablas necesarias para el funcionamiento del módulo
		mysqli_query($db_con, "CREATE TABLE `informe_evaluaciones_unidades_".$evaluacion_seleccionada."` (
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
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;");
		
		// Obtenemos las unidades del centro
		$result_unidades = mysqli_query($db_con, "SELECT cursos.idcurso, cursos.nomcurso, unidades.idunidad, unidades.nomunidad FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso ORDER BY cursos.nomcurso ASC, unidades.nomunidad ASC");
		$row_unidades = mysqli_fetch_all($result_unidades, MYSQLI_ASSOC);
		
		foreach ($row_unidades as $unidades) {
			
			$idcurso = $unidades['idcurso'];
			$curso = $unidades['nomcurso'];
			
			$idunidad = $unidades['idunidad'];
			$unidad = $unidades['nomunidad'];
			$unidades['repiten_alumnos'] = 0;
			$unidades['cero_suspensos'] = 0;
			$unidades['uno_suspensos'] = 0;
			$unidades['dos_suspensos'] = 0;
			$unidades['tres_suspensos'] = 0;
			$unidades['cuatro_suspensos'] = 0;
			$unidades['cinco_suspensos'] = 0;
			$unidades['seis_suspensos'] = 0;
			$unidades['siete_suspensos'] = 0;
			$unidades['ocho_suspensos'] = 0;
			$unidades['nueve_o_mas_suspensos'] = 0;
			$unidades['promocionan'] = 0;
			$unidades['titulan'] = 0;
			
			// Obtenemos el número de alumnos
			$result_alumnos_unidad = mysqli_query($db_con, "SELECT alma.claveal1, notas.".$evaluaciones[$evaluacion_seleccionada]." FROM alma JOIN notas ON alma.claveal1 = notas.claveal WHERE alma.unidad = '".$unidades['nomunidad']."' AND alma.curso = '".$unidades['nomcurso']."'");
			$unidades['total_alumnos'] = mysqli_num_rows($result_alumnos_unidad);
			
			// Obtenemos las notas de los alumnos
			$row_alumnos_unidad = mysqli_fetch_all($result_alumnos_unidad, MYSQLI_ASSOC);
			foreach ($row_alumnos_unidad as $alumno) {
				
				$suspensos = 0;
				
				$alumno[$evaluaciones[$evaluacion_seleccionada]] = rtrim($alumno[$evaluaciones[$evaluacion_seleccionada]], ';');
				
				$exp_asignaturas = explode(';', $alumno[$evaluaciones[$evaluacion_seleccionada]]);
				
				foreach ($exp_asignaturas as $asignatura) {
					
					$exp_notas_asignatura = explode(':', trim($asignatura));
					$idasignatura = trim($exp_notas_asignatura[0]);
					$idcalificacion = trim($exp_notas_asignatura[1]);
					
					if ($idcalificacion != '') { 
						$result_calificacion = mysqli_query($db_con, "SELECT abreviatura FROM calificaciones WHERE codigo = '".$idcalificacion."'");
						$row_calificacion = mysqli_fetch_array($result_calificacion);
						if (! intval($row_calificacion['abreviatura']) || $row_calificacion['abreviatura'] < 5) $suspensos++;
					}
				}
				
				switch ($suspensos) {
					case 0 : $unidades['cero_suspensos']++; break;
					case 1 : $unidades['uno_suspensos']++; break;
					case 2 : $unidades['dos_suspensos']++; break;
					case 3 : $unidades['tres_suspensos']++; break;
					case 4 : $unidades['cuatro_suspensos']++; break;
					case 5 : $unidades['cinco_suspensos']++; break;
					case 6 : $unidades['seis_suspensos']++; break;
					case 7 : $unidades['siete_suspensos']++; break;
					case 8 : $unidades['ocho_suspensos']++; break;
					default: $unidades['nueve_o_mas_suspensos']++;
				}
				
				// Comprobamos si repite curso o no
				if ($alumno['matriculas'] > 1) $unidades['repiten_alumnos']++;
				
				// Comprobamos si promociona
				if ($suspensos < 3) $unidades['promocionan']++;
				
				// Comprobamos si titula
				if (stristr($curso, '4º de E.S.O.') == true) {
					if ($suspensos == 0) $unidades['titulan']++;
				}
				else if (stristr($curso, '2º de Bachillerato') == true) {
					if ($suspensos == 0) $unidades['titulan']++;
				}
				else {
					if ($suspensos < 3) $unidades['titulan']++;
				}
				
			}
			
			// Cambiamos el número de alumnos que promocionan por el porcentaje
			$unidades['promocionan'] = ($unidades['promocionan'] * 100) / $unidades['total_alumnos'];
			
			// Añadimos a la base de datos
			mysqli_query($db_con, "INSERT INTO `informe_evaluaciones_unidades_".$evaluacion_seleccionada."` (`idcurso`, `idunidad`, `total_alumnos`, `repiten_alumnos`, `cero_suspensos`, `uno_suspensos`, `dos_suspensos`, `tres_suspensos`, `cuatro_suspensos`, `cinco_suspensos`, `seis_suspensos`, `siete_suspensos`, `ocho_suspensos`, `nueve_o_mas_suspensos`, `promocionan`, `titulan`) VALUES ('".$idcurso."', '".$idunidad."', '".$unidades['total_alumnos']."', '".$unidades['repiten_alumnos']."', '".$unidades['cero_suspensos']."', '".$unidades['uno_suspensos']."', '".$unidades['dos_suspensos']."', '".$unidades['tres_suspensos']."', '".$unidades['cuatro_suspensos']."', '".$unidades['cinco_suspensos']."', '".$unidades['seis_suspensos']."', '".$unidades['siete_suspensos']."', '".$unidades['ocho_suspensos']."', '".$unidades['nueve_o_mas_suspensos']."', '".$unidades['promocionan']."', '".$unidades['titulan']."');");
			
			// Añadimos la información al array
			array_push($resultados_evaluaciones, $unidades);
		}
		
		mysqli_free_result($result_cursos);
	}
	
	$evaluacionSinNotas = 0;
}



include("../../../menu.php");
?>
	
	<div class="container">
		
		<div class="page-header">
			<h2>Informes de evaluaciones <small>Estadísticas de calificaciones</small></h2>
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
				$nomcurso = "";
				?>
				<?php foreach ($resultados_evaluaciones as $evaluacion): ?>
				
				<?php if ($nomcurso != $evaluacion['nomcurso']): ?>
				<?php if ($flag == 1): ?>
				</tbody>
					<tfoot>
						<th>Totales</th>
						<th><?php echo $total_alumnos; ?></th>
						<th><?php echo $repiten_alumnos; ?></th>
						<th><?php echo $cero_suspensos; ?></th>
						<th><?php echo $uno_suspensos; ?></th>
						<th><?php echo $dos_suspensos; ?></th>
						<th><?php echo $tres_suspensos; ?></th>
						<th><?php echo $cuatro_suspensos; ?></th>
						<th><?php echo $cinco_suspensos; ?></th>
						<th><?php echo $seis_suspensos; ?></th>
						<th><?php echo $siete_suspensos; ?></th>
						<th><?php echo $ocho_suspensos; ?></th>
						<th><?php echo $nueve_o_mas_suspensos; ?></th>
						<th><span class="<?php echo round($promocionan/($num_unidades-1)) > 50 ? 'text-success' : 'text-danger'; ?>"><?php echo round($promocionan/($num_unidades-1)); ?> %</span> / <?php echo $titulan; ?></th>
					</tfoot>
				</table>
				
				<hr>
				
				<?php endif; ?>
				<?php 
				$flag = 1;
				$num_unidades = 1;
				
				$total_alumnos = 0;
				$repiten_alumnos = 0;
				$cero_suspensos = 0;
				$uno_suspensos = 0;
				$dos_suspensos = 0;
				$tres_suspensos = 0;
				$cuatro_suspensos = 0;
				$cinco_suspensos = 0;
				$seis_suspensos = 0;
				$siete_suspensos = 0;
				$ocho_suspensos = 0;
				$nueve_o_mas_suspensos = 0;
				$promocionan = 0;
				$titulan = 0;
				?>
				
				<h4 class="text-info"><?php echo $evaluacion['nomcurso']; ?></h4>
				
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="9.15%">Unidad</th>
							<th width="6.15%">Alumnos</th>
							<th width="6.15%">Repiten</th>
							<th width="4.14%">0</th>
							<th width="4.14%">1</th>
							<th width="4.14%">2</th>
							<th width="4.14%">3</th>
							<th width="4.14%">4</th>
							<th width="4.14%">5</th>
							<th width="4.14%">6</th>
							<th width="4.14%">7</th>
							<th width="4.14%">8 </th>
							<th width="4.14%">9+</th>
							<th width="7.15%">Promo. / Tit.</th>
						</tr>
					</thead>
					<tbody>
				<?php endif; ?>
						<tr>
							<th><?php echo $evaluacion['nomunidad']; ?></th>
							<td><?php echo $evaluacion['total_alumnos']; ?></td>
							<td><?php echo $evaluacion['repiten_alumnos']; ?></td>
							<td><?php echo $evaluacion['cero_suspensos']; ?></td>
							<td><?php echo $evaluacion['uno_suspensos']; ?></td>
							<td><?php echo $evaluacion['dos_suspensos']; ?></td>
							<td><?php echo $evaluacion['tres_suspensos']; ?></td>
							<td><?php echo $evaluacion['cuatro_suspensos']; ?></td>
							<td><?php echo $evaluacion['cinco_suspensos']; ?></td>
							<td><?php echo $evaluacion['seis_suspensos']; ?></td>
							<td><?php echo $evaluacion['siete_suspensos']; ?></td>
							<td><?php echo $evaluacion['ocho_suspensos']; ?></td>
							<td><?php echo $evaluacion['nueve_o_mas_suspensos']; ?></td>
							<td><span class="<?php echo $evaluacion['promocionan'] > 50 ? 'text-success' : 'text-danger'; ?>"><?php echo round($evaluacion['promocionan']); ?> %</span> / <?php echo $evaluacion['titulan']; ?></td>
						</tr>
				
				<?php 
				$total_alumnos += $evaluacion['total_alumnos'];
				$alumnos_repiten += $evaluacion['repiten_alumnos'];
				$cero_suspensos += $evaluacion['cero_suspensos'];
				$uno_suspensos += $evaluacion['uno_suspensos'];
				$dos_suspensos += $evaluacion['dos_suspensos'];
				$tres_suspensos += $evaluacion['tres_suspensos'];
				$cuatro_suspensos += $evaluacion['cuatro_suspensos'];
				$cinco_suspensos += $evaluacion['cinco_suspensos'];
				$seis_suspensos += $evaluacion['seis_suspensos'];
				$siete_suspensos += $evaluacion['siete_suspensos'];
				$ocho_suspensos += $evaluacion['ocho_suspensos'];
				$nueve_o_mas_suspensos += $evaluacion['nueve_o_mas_suspensos'];
				$promocionan += $evaluacion['promocionan'];
				$titulan += $evaluacion['titulan'];
				
				$num_unidades++;
				$nomcurso = $evaluacion['nomcurso']; 
				?>
				<?php endforeach; ?>
				
				</tbody>
					<tfoot>
						<th>Totales</th>
						<th><?php echo $total_alumnos; ?></th>
						<th><?php echo $repiten_alumnos; ?></th>
						<th><?php echo $cero_suspensos; ?></th>
						<th><?php echo $uno_suspensos; ?></th>
						<th><?php echo $dos_suspensos; ?></th>
						<th><?php echo $tres_suspensos; ?></th>
						<th><?php echo $cuatro_suspensos; ?></th>
						<th><?php echo $cinco_suspensos; ?></th>
						<th><?php echo $seis_suspensos; ?></th>
						<th><?php echo $siete_suspensos; ?></th>
						<th><?php echo $ocho_suspensos; ?></th>
						<th><?php echo $nueve_o_mas_suspensos; ?></th>
						<th><span class="<?php echo round($promocionan/($num_unidades-1)) > 50 ? 'text-success' : 'text-danger'; ?>"><?php echo round($promocionan/($num_unidades-1)); ?> %</span> / <?php echo $titulan; ?></th>
					</tfoot>
				</table>
				<?php endif; ?>
			</div>
			
		</div>
		
	</div>

	<?php include("../../../pie.php"); ?> 

</body>
</html>