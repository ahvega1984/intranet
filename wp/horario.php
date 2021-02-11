<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<style>
			.fa-user-circle {
				font-size: 7em;
			}
			body {
				overflow-x: hidden;
			}
			.card {
				padding: 20px;
				min-height: 250px;
			}
		</style>
    </head>
    <body>
		
		<?php

			include 'lib.php';
			$curso = $_GET['curso'];
			$extra_asig="";
			$extra_al="";
			$db_con = conectar_db();
		?>

		<div class="container">
			<br>
			<h1>Horario <?=$curso?></h1>
			<br>
			<!-- SCAFFOLDING -->
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th>Lunes</th>
									<th>Martes</th>
									<th>Miércoles</th>
									<th>Jueves</th>
									<th>Viernes</th>
								</tr>
							</thead>
							<tbody>
							<?php $horas = array(); ?>
							<?php $result_horas = mysqli_query($db_con, "SELECT `hora`, `hora_inicio`, `hora_fin` FROM `tramos` ORDER BY `idjornada` ASC, `horini` ASC"); ?>
							<?php while ($row_horas = mysqli_fetch_array($result_horas)) array_push($horas, $row_horas); ?>
							<?php foreach ($horas as $hora): ?>
								<tr>
									<th>
										<?php echo ($hora['hora'] != 'R' && $hora['hora'] != 'Rn') ? $hora['hora'].'ª' : 'Recreo'; ?>
										<hr style="margin: 5px 0;">
										<p>
						  					<small><?php echo substr($hora['hora_inicio'], 0, 5); ?><br>
						  					<?php echo substr($hora['hora_fin'], 0, 5); ?></small>
										</p>
					  				</th>
									<?php for ($i = 1; $i < 6; $i++): ?>
									<td width="20%" style="border-right: 2px solid #ddd;">
										<?php $result = mysqli_query($db_con, "SELECT DISTINCT `asig`, `a_aula`, `n_aula` FROM `horw` WHERE `a_grupo` = '$curso' AND `dia` = '$i' AND `hora` = '".$hora['hora']."' $extra_al"); ?>
										<?php while($row = mysqli_fetch_array($result)): ?> <?php echo '<div style="display: block; font-size: 0.9em; margin-bottom: 5px;">'; ?>
										<?php echo ($row['a_aula']) ? '<abbr class="text-danger pull-right" data-bs="tooltip" title="'.$row['n_aula'].'">'.$row['a_aula'].'</abbr>' : '<abbr class="text-danger pull-right" data-bs="tooltip" title="Sin asignar o sin aula">Sin aula</abbr>'; ?>
										<br>
										<?php echo $row['asig']; ?> <?php echo '</div>'; ?> <?php endwhile; ?>
									</td>
									<?php endfor; ?>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div><!-- /.col-sm-12 -->
			</div><!-- /.row -->
		</div><!-- /.container -->
		<?php mysqli_query($db_con,"DROP TABLE asig_tmp"); ?>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>
