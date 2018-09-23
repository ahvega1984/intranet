<?php
require('../../bootstrap.php');


$profesor = $_SESSION['profi'];

if (isset($_POST['aula'])) {$aula = $_POST['aula'];} elseif (isset($_GET['aula'])) {$aula = $_GET['aula'];} else{$aula="";}

include("../../menu.php");
?>

<div class="container">

	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">

		<h2 style="display: inline;"><?php echo $aula; ?> <small>Consulta de horario</small></h2>

		<form class="pull-right col-sm-2" method="post" action="">
			<?php $result = mysqli_query($db_con, "SELECT DISTINCT n_aula FROM horw where n_aula not like 'G%' ORDER BY n_aula ASC"); ?>
			<select class="form-control" id="aula" name="aula" onChange="submit()">
				<?php while($row = mysqli_fetch_array($result)): ?>
				<option value="<?php echo $row['n_aula']; ?>" <?php echo ($row['n_aula'] == $aula) ? 'selected' : ''; ?>><?php echo $row['n_aula']; ?></option>
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
							<td width="20%"><?php $result = mysqli_query($db_con, "SELECT DISTINCT asig, prof FROM horw WHERE n_aula='$aula' AND dia='$i' AND hora='".$hora['hora']."'"); ?>
							<?php $result2 = mysqli_query($db_con, "SELECT DISTINCT a_grupo FROM horw WHERE n_aula='$aula' AND dia='$i' AND hora='".$hora['hora']."'"); ?>
							<?php $grupo=""; $asignatura=""; $profesor="";?>
							<?php while($row = mysqli_fetch_array($result)): ?>
							<?php while($row2 = mysqli_fetch_array($result2)): ?>
							<?php $grupo .= "<abbr class='text-warning'>".$row2['a_grupo']."<abbr>&nbsp;&nbsp;"; ?>
							<?php endwhile; ?>
							<?php $asignatura = $row['asig']; ?>
							<?php $profesor .= nomprofesor($row['prof'])."<br>";?>
							<?php endwhile; ?>
							<?php $profesor=substr($profesor,0,-4);?>
							<?php echo "<span class='text-danger'>$asignatura</span><br><span class='text-info'>$profesor</span><br>$grupo";?>
							</td>
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
