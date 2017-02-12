<?php
require('../../../bootstrap.php');


if (isset($_GET['evaluacion'])) $evaluacion_seleccionada = $_GET['evaluacion'];
else $evaluacion_seleccionada = 'evi';

// Inicializamos variables
$evaluaciones = array('evi' => 'notas0', '1ev' => 'notas1', '2ev' => 'notas2', 'ord' => 'notas3', 'ext' => 'notas4');
$evaluacionSinNotas = 1;
$resultados_evaluaciones = array();

// Consultamos la evaluación seleccionada
$result = mysqli_query($db_con, "SELECT * FROM notas WHERE ".$evaluaciones[$evaluacion_seleccionada]." IS NOT NULL");
$existenNotas = mysqli_num_rows($result);
mysqli_free_result($result);

if ($existenNotas) {
	
	// Obtenemos las unidades del centro
	$result_unidades = mysqli_query($db_con, "SELECT cursos.nomcurso, unidades.nomunidad FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso ORDER BY cursos.nomcurso ASC, unidades.nomunidad ASC");
	$row_unidades = mysqli_fetch_all($result_unidades, MYSQLI_ASSOC);
	
	foreach ($row_unidades as $unidades) {
		
		$curso = $unidades['nomcurso'];
		
		$unidad = $unidades['nomunidad'];
		$unidades['alumnos_repiten'] = 0;
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
		$result_alumnos_unidad = mysqli_query($db_con, "SELECT alma.claveal, alma.apellidos, alma.nombre, alma.matriculas, notas.".$evaluaciones[$evaluacion_seleccionada]." FROM alma JOIN notas ON alma.claveal1 = notas.claveal WHERE alma.unidad = '".$unidades['nomunidad']."' AND alma.curso = '".$unidades['nomcurso']."'");
		$unidades['total_alumnos'] = mysqli_num_rows($result_alumnos_unidad);
		
		// Obtenemos las notas de los alumnos
		$row_alumnos_unidad = mysqli_fetch_all($result_alumnos_unidad, MYSQLI_ASSOC);
		foreach ($row_alumnos_unidad as $alumno) {
			
			$suspensos = 0;
			
			$alumno[$evaluaciones[$evaluacion_seleccionada]] = rtrim($alumno[$evaluaciones[$evaluacion_seleccionada]], ';');
			
			$exp_asignaturas = explode(';', $alumno[$evaluaciones[$evaluacion_seleccionada]]);
			
			foreach ($exp_asignaturas as $asignatura) {
			
				$exp_notas_asignatura = explode(':', trim($asignatura));
				$result_calificacion = mysqli_query($db_con, "SELECT nombre FROM calificaciones WHERE codigo = '".trim($exp_notas_asignatura[1])."'");
				$row_calificacion = mysqli_fetch_array($result_calificacion);
				if ($row_calificacion['nombre'] != '' && $row_calificacion['nombre'] < 5) $suspensos++;
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
			if ($alumno['matriculas'] > 1) $unidades['alumnos_repiten']++;
			
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
		
		// Añadimos la información al array
		array_push($resultados_evaluaciones, $unidades);
	}
	
	mysqli_free_result($result_cursos);
	
	$evaluacionSinNotas = 0;
}



include("../../../menu.php");
?>
	
	<div class="container">
		
		<div class="page-header">
			<h2>Informes de evaluaciones <small>Estadísticas de calificaciones</small></h2>
		</div>
		
		<div class="row">
		
			<div class="col-sm-12">
			
				<ul class="nav nav-pills">
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], 'evi') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=evi">Evaluación Inicial</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], '1ev') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=1ev">1ª Evaluación</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], '2ev') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=2ev">2ª Evaluación</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], 'ord') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=ord">Evaluación Ordinaria</a></li>
				  <li<?php echo (stristr($_SERVER['REQUEST_URI'], 'ext') == true) ? ' class="active"' : ''; ?>><a href="?evaluacion=ext">Evaluación Extraordinaria</a></li>
				</ul>
				
				<br>
				<br>
				
				<?php if ($evaluacionSinNotas): ?>
				<p>No se han importado las notas de esta evaluación</p>
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
						<th><?php echo $alumnos_repiten; ?></th>
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
						<th><?php echo round($promocionan/($num_unidades-1)); ?> % / <?php echo $titulan; ?></th>
					</tfoot>
				</table>
				
				<hr>
				
				<?php endif; ?>
				<?php 
				$flag = 1;
				$num_unidades = 1;
				
				$total_alumnos = 0;
				$alumnos_repiten = 0;
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
							<td><?php echo $evaluacion['alumnos_repiten']; ?></td>
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
				$alumnos_repiten += $evaluacion['alumnos_repiten'];
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
						<th><?php echo $alumnos_repiten; ?></th>
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
						<th><?php echo round($promocionan/($num_unidades-1)); ?> % / <?php echo $titulan; ?></th>
					</tfoot>
				</table>
				<?php endif; ?>
			</div>
			
		</div>
		
	</div>

	<?php include("../../../pie.php"); ?> 

</body>
</html>