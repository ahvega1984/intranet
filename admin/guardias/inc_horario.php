<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<h4><span class="far fa-clock fa-fw"></span> Horario</h4>

<div class="panel panel-default">

	<table class="table table-bordered table-condensed table-striped table-centered">
		<thead>
		  <tr>
			<th width="20">&nbsp;</th>
			<th width="20">Lun</th>
			<th width="20">Mar</th>
			<th width="20">Mié</th>
			<th width="20">Jue</th>
			<th width="20">Vié</th>
		  </tr>
		</thead>
		<tbody>
		<?php
		// Horas del dÃ­a
		$t_hora = mysqli_query($db_con,"select hora, hora_inicio, hora_fin from tramos");
		while($todas_horas=mysqli_fetch_array($t_hora)){
		$n_hora = $todas_horas[0];
		$hora_inicio = $todas_horas[1];
		$nombre = $hora_inicio;
		$algo = 0;
		for($i = 1; $i < 6; $i ++) {
			$hay_algo = mysqli_query($db_con, "SELECT distinct  c_asig, a_asig, a_grupo, asig FROM  horw where prof = '$profesor' and dia = '$i' and hora = '$n_hora' ORDER BY a_grupo" );
			if (mysqli_num_rows($hay_algo) > 0) {
				$algo = 1;
			}

		}

		if ($algo) {
			echo '<tr><th>'.$nombre.'</th>';

			//DÃ­as
			for($z = 1; $z < 6; $z ++) {

				?>
		<td>
		      <?php
				if (! (empty ( $z ) and ! ($n_hora))) {
					$extra = "and dia = '$z' and hora = '$n_hora'";
				}

				$asignatur1 = mysqli_query($db_con, "SELECT distinct  c_asig, a_asig, a_grupo, asig FROM  horw where prof = '$profesor' $extra ORDER BY a_grupo" );
				$rowasignatur1 = mysqli_fetch_row ( $asignatur1 );
				$act_seneca = mysqli_query($db_con, "SELECT * FROM  actividades_seneca where idactividad = '$rowasignatur1[0]' and nomactividad not like 'TUT%' and idactividad not like '21'" );
				$asig_seneca = mysqli_query($db_con, "SELECT * FROM  asignaturas where codigo = '$rowasignatur1[0]'" );

				if(mysqli_num_rows($act_seneca)>0 and ! ($rowasignatur1 [0] == "25" or $rowasignatur1 [0] == "44")){
					echo "<span class='label label-default' data-bs='tooltip' title='".$rowasignatur1[3]."'>" . $rowasignatur1 [1] . "</span><br />";
				}
				elseif (mysqli_num_rows($asig_seneca)>0 and ! ($rowasignatur1 [0] == "25" or $rowasignatur1 [0] == "44")) {
					echo "<span class='label label-info' data-bs='tooltip' title='".$rowasignatur1[3]."'>" . $rowasignatur1 [1] . "</span><br />";
				}
				elseif (empty ( $rowasignatur1 [2] ) and ! ($rowasignatur1 [0] == "25" or $rowasignatur1 [0] == "44")) {
					echo "<span class='label label-warning' data-bs='tooltip' title='".$rowasignatur1[3]."'>" . $rowasignatur1 [1] . "</span><br />";
				}
				elseif ($rowasignatur1 [1]=="GUCON") {
					echo "<span class='label label-danger' data-bs='tooltip' title='".$rowasignatur1[3]."'>".$rowasignatur1[1]."</span>";
				}
				elseif ((($rowasignatur1 [0] == "25" and $rowasignatur1 [1]!=="GUCON") or $rowasignatur1 [0] == "44") and $config['mod_asistencia']) {
					if (strstr($_SESSION ['cargo'],"1")==TRUE and $rowasignatur1 [0] == "44") {
						echo "<span class='label label-success' data-bs='tooltip' title='".$rowasignatur1[3]."'>".$rowasignatur1[1]."</span>";
					}
					else{
						echo "<span class='label label-danger' data-bs='tooltip' title='".$rowasignatur1[3]."'>" . $rowasignatur1 [1] . "</span>";
					}
				}
				// Recorremos los grupos a los que da en ese hora.
				$rep_grupo = "";
				$cont = 1;
				$asignaturas1 = mysqli_query($db_con, "SELECT distinct  c_asig, a_grupo FROM  horw where prof = '$profesor' and dia = '$z' and hora = '$n_hora' ORDER BY a_grupo" );
				while ( $rowasignaturas1 = mysqli_fetch_array ( $asignaturas1 ) ) {
					$grupo = $rowasignaturas1 [1];

					echo "<span style='font-size:0.8em'>";
					if (is_numeric ( substr ( $grupo, 0, 1 ) )) {
						if ($grupo != $rep_grupo) {

							if($cont > 1) {
								if (stristr($grupo, '-') == TRUE) {
									$exp_grupo = explode('-', $grupo);
									echo "/".$exp_grupo[1];
								}
								elseif (stristr($grupo, 'º') == TRUE) {
									$exp_grupo = explode('º', $grupo);
									echo "/".$exp_grupo[1];
								}
								else {
									echo $grupo.'<br>';
								}
							}
							else {
								echo $grupo;
							}
						}
					}
					echo "</span>";
					$rep_grupo = $grupo;
					$cont++;
				}
				?>
		    </span>
		</td>
		<?php
			}
		}
		?>
		</tr>
		<?php
		}
		?>
		</tbody>
	</table>

</div>
