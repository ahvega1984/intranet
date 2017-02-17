<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

// CALENDARIO
$dia_actual = date('d');
$mes_actual = date('n');
$anio_actual = date('Y');

$dia  = isset($_GET['dia'])  ? $_GET['dia']  : date('d');
$mes  = isset($_GET['mes'])  ? $_GET['mes']  : date('n');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$semana = 1;

for ($i = 1; $i <= date('t', strtotime($anio.'-'.$mes)); $i++) {
	
	$dia_semana = date('N', strtotime($anio.'-'.$mes.'-'.$i));
	
	$calendario[$semana][$dia_semana] = $i;
	if ($dia_semana == 7) $semana++;
	
}


// HTML CALENDARIO MENSUAL
function vista_mes ($calendario, $dia, $mes, $anio) {
	// NAVEGACION
	$mes_ant = $mes - 1;
	$anio_ant = $anio;
	
	if ($mes == 1) {
		$mes_ant = 12;
		$anio_ant = $anio - 1;
	}
	
	
	$mes_sig = $mes + 1;
	$anio_sig = $anio;
	
	if ($mes == 12) {
		$mes_sig = 1;
		$anio_sig = $anio + 1;
	}
	
	// Corrección en mes
	(strlen($mes) < 2) ? $mes = '0'.$mes : $mes = $mes;
	
	echo '<div class"table-responsive">';
	echo '<table id="calendar" class="table table-bordered table-condensed" style="font-size: 0.9em;">';
	echo '	<thead>';
	echo '      <tr>';
	echo '			<th class="text-center"><h5><a href="?mes='.$mes_ant.'&anio='.$anio_ant.'"><span class="fa fa-chevron-left fa-fw"></span></a></h5></th>';
	echo '			<th colspan="5" class="text-center"><h5>'.strftime('%B, %Y', strtotime($anio.'-'.$mes)).'</h5></th>';
	echo '			<th class="text-center"><h5><a href="?mes='.$mes_sig.'&anio='.$anio_sig.'"><span class="fa fa-chevron-right fa-fw"></span></a></h5></th>';
	echo '		</tr>';
	echo '		<tr>';
	echo '			<th class="text-center">L</th>';
	echo '			<th class="text-center">M</th>';
	echo '			<th class="text-center">X</th>';
	echo '			<th class="text-center">J</th>';
	echo '			<th class="text-center">V</th>';
	echo '			<th class="text-center">S</th>';
	echo '			<th class="text-center">D</th>';
	echo '		</tr>';
	echo '	</thead>';
	echo '	<tbody>';
	
	foreach ($calendario as $dias) {
		echo '		<tr>';
	
		for ($i = 1; $i <= 7; $i++) {
			
			if ($i > 5) {
				if (isset($dias[$i]) && ($dias[$i] == $dia)) {
					echo '			<td class="text-muted" style="background-color: #ecf0f1; font-weight: bold;" width="14.28%">';
				}
				else {
					echo '			<td class="text-muted" width="14.28%">';
				}
			}
			else {
				if (isset($dias[$i]) && ($dias[$i] == $dia)) {
					echo '			<td class="today" style="font-weight: bold;" width="14.28%">';
				}
				else {
					echo '			<td width="14.28%">';
				}
			}
			
			if (isset($dias[$i])) {
				
				if ($i < 6) {
					echo '				<a class="text-center" href="?dia='.$dias[$i].'&mes='.$mes.'&anio='.$anio.'" style="display: block;">'.$dias[$i].'</a>';
					// Corrección en día
					($dias[$i] < 10) ? $dia0 = '0'.$dias[$i] : $dia0 = $dias[$i];
				}
				else {
					echo '<div class="text-center">'.$dias[$i].'</div>';
				} 
				
			}
			else {
				echo '&nbsp;';
			}
			
			echo '			</td>';
		}
		echo '		</tr>';
	}
	
	echo '	</tbody>';
	echo '</table>';
	echo '</div>';
}
?>