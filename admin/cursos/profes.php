<?php
require('../../bootstrap.php');


$profesor = $_SESSION['profi'];


if (isset($_POST['profeso'])) {$profeso = $_POST['profeso'];} elseif (isset($_GET['profeso'])) {$profeso = $_GET['profeso'];} else{$profeso="";}

$profe = explode(", ",$profeso);

include("../../menu.php");
?>
<div class="container">

	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display: inline;"><?php echo nomprofesor($profe[1].' '.$profe[0]); ?> <small>Consulta de horario</small></h2>

		<?php $result = mysqli_query($db_con, "SELECT DISTINCT prof FROM horw WHERE prof NOT LIKE '' ORDER BY prof ASC"); ?>
		<form class="pull-right col-sm-3" method="post" action="">
			<select class="form-control" id="profeso" name="profeso" onChange="submit()">
				<?php while($row = mysqli_fetch_array($result)): ?>
				<option value="<?php echo $row['prof']; ?>" <?php echo ($row['prof'] == $profeso) ? 'selected' : ''; ?>><?php echo $row['prof']; ?></option>
				<?php endwhile; ?>
			</select>
		</form>
	</div>

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
							<?php for($i = 1; $i < 6; $i++): ?>
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT a_asig, asig, a_grupo, a_aula, n_aula FROM horw WHERE prof='$profeso' AND dia='$i' AND hora='".$hora['hora']."'"); ?>
							<td width="20%" style="border-right: 2px solid #ddd;"><?php while($row = mysqli_fetch_array($result)): ?>
							<abbr data-bs="tooltip" title="<?php echo $row['asig']; ?>"><?php echo $row['a_asig']; ?></abbr><br>
							<?php echo (!empty($row['n_aula']) && $row['n_aula'] != 'Sin asignar o sin aula' && $row['n_aula'] != 'NULL') ? '<abbr class="pull-right text-danger" data-bs="tooltip" title="'.$row['n_aula'].'">'.$row['a_aula'].'</abbr>' : ''; ?>
							<?php echo (!empty($row['a_grupo'])) ? '<span class="text-warning">'.$row['a_grupo'].'</span>' : ''; ?><br>
							<?php endwhile; ?> <?php mysqli_free_result($result); ?></td>
							<?php endfor; ?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="hidden-print">
				<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
				<a class="btn btn-default" href="chorarios.php">Volver</a>
			</div>

		</div><!-- /.col-sm-12 -->

	</div><!-- /.row -->

</div><!-- /.container -->

	<?php include("../../pie.php"); ?>

</body>
</html>
