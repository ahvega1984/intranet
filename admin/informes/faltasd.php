<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODULO DETALLADO FALTAS DE ASISTENCIA -->

<?php
function tipo_falta($falta) {

	switch ($falta) {
		case 'J' : $tipo = 'Justificada'; break;
		case 'F' : $tipo = 'Injustificada'; break;
		case 'I' : $tipo = 'Injustificada'; break;
		case 'R' : $tipo = 'Retraso'; break;
	}

	return $tipo;
}
?>

<h3>Informe detallado de faltas de asistencia</h3>
<br>

<?php $result = mysqli_query($db_con, "SELECT DISTINCT fecha FROM FALTAS WHERE claveal = '$claveal' ORDER BY fecha DESC"); ?>
<?php if (mysqli_num_rows($result)): ?>
<div class="table-responsive">
	<table class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr>
				<th>Fecha</th>
				<?php for ($i = 1; $i < 9; $i++): ?>
				<th><?php echo $i; ?>ª hora</th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php while ($row = mysqli_fetch_array($result)): ?>
			<tr>
				<th><abbr data-bs="tooltip" title="<?php echo strftime('%A', strtotime($row['fecha'])); ?>"><?php echo $row['fecha']; ?></abbr></th>
				<?php for ($i = 1; $i < 9; $i++): ?>
				<?php
				$faltas_tramo = array();
				$result_falta = mysqli_query($db_con, "SELECT falta, codasi FROM FALTAS WHERE claveal = '$claveal' AND fecha = '".$row['fecha']."' AND hora = '$i'");
				while ($row_falta = mysqli_fetch_array($result_falta)) {

					$abrev_asignatura = "";
					$nombre_asignatura = "";
					$result_asig = mysqli_query($db_con, "SELECT DISTINCT abrev, nombre FROM asignaturas WHERE codigo = '".$row_falta['codasi']."' AND abrev NOT LIKE '%\_%' LIMIT 1");
					if (mysqli_num_rows($result_asig)) {
						$row_asig = mysqli_fetch_array($result_asig);
						$abrev_asignatura = $row_asig['abrev'];
						$nombre_asignatura = $row_asig['nombre'];
					}

					$falta_tramo = array(
						'tipo' => $row_falta['falta'],
						'asignatura' => $nombre_asignatura,
						'abreviatura' => $abrev_asignatura,
					);

					array_push($faltas_tramo, $falta_tramo);
				}
				unset($falta_tramo);
				?>

				<td>
					<?php foreach ($faltas_tramo as $falta_tramo): ?>
					<p style="margin-bottom: 0;">
						<abbr data-bs="tooltip" title="<?php echo $falta_tramo['asignatura']; ?>">
							<span class="label label-default"><?php echo $falta_tramo['abreviatura']; ?></span>
						</abbr>

						<abbr data-bs="tooltip" title="<?php echo tipo_falta($falta_tramo['tipo']); ?>">
						<?php echo ($falta_tramo['tipo'] == "I" || $falta_tramo['tipo'] == "F") ? '<span class="label label-danger">'.$falta_tramo['tipo'].'</label>' : ''; ?>
						<?php echo ($falta_tramo['tipo'] == "R") ? '<span class="label label-warning">'.$falta_tramo['tipo'].'</label>' : ''; ?>
						<?php echo ($falta_tramo['tipo'] == "J") ? '<span class="label label-success">'.$falta_tramo['tipo'].'</label>' : ''; ?>
						</abbr>
					</p>
					<?php endforeach; ?>
				</td>
				<?php endfor; ?>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>

<!-- FIN MODULO DETALLADO FALTAS DE ASISTENCIA -->
