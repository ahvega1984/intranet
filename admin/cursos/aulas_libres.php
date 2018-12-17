<?php
require('../../bootstrap.php');


$profesor = $_SESSION['profi'];

if (isset($_POST['n_dia'])) {$n_dia = $_POST['n_dia'];} elseif (isset($_GET['n_dia'])) {$n_dia = $_GET['n_dia'];} else{$n_dia="";}
if ($n_dia == 'Lunes') {	$dia = '1';}
if ($n_dia == 'Martes') { $dia = '2';}
if ($n_dia == 'Miércoles') {	$dia = '3';}
if ($n_dia == 'Jueves') {	$dia = '4';}
if ($n_dia == 'Viernes') {	$dia = '5';}

include("../../menu.php");

$week = date('W');
$year = date('Y');
?>

<div class="container">

	<div class="page-header">

		<h2 style="display: inline;"><?php echo $n_dia; ?> <small>Consulta de aulas libres</small></h2>

		<?php $dias = array('Lunes','Martes','Miércoles','Jueves','Viernes'); ?>		
		<form class="pull-right col-sm-2" method="post" action="">
			<select class="form-control" id="n_dia" name="n_dia" onChange="submit()">
				<?php for($i = 0; $i < count($dias); $i++): ?>
				<?php
				$primer_dia0 = date('Y-m-d', strtotime($year . 'W' . str_pad($week , 2, '0', STR_PAD_LEFT)));
				$dia_sem0 = $i;
				$nuevafecha0 = strtotime ( '+'.$dia_sem0.' day' , strtotime ( $primer_dia0 ) ) ;
				$fecha_dia0 = date ( 'Y-m-d' , $nuevafecha0 );
				?>	
				<option value="<?php echo $dias[$i]; ?>" <?php echo ($dias[$i] == $n_dia) ? 'selected' : ''; ?>><?php echo $dias[$i]." ".cambia_fecha($fecha_dia0).""; ?></option>
				<?php endfor; ?>
			</select>
		</form>

	</div><!-- /.page-header -->

	<!-- SCAFFOLDING -->
	<div class="row">

		<div class="col-sm-12">

			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
						<?php
						$hr = mysqli_query($db_con,"select hora_inicio, hora_fin from tramos where hora <> 'R' AND hora <> 'Rn' ORDER BY idjornada ASC, horini ASC");
						$count_cols = 0;
						while ($hor = mysqli_fetch_array($hr)) {
							echo "<th nowrap>".substr($hor[0],0,5)." - ".substr($hor[1],0,5)."</th>";
							$count_cols++;
						}
						?>
						</tr>
					</thead>
					<tbody>
						<tr>
						<?php for($i = 1; $i < $count_cols; $i++): ?>
							<td>
								<?php
								$primer_dia = date('Y-m-d', strtotime($year . 'W' . str_pad($week , 2, '0', STR_PAD_LEFT)));
								$dia_sem = $dia-1;
								$nuevafecha = strtotime ( '+'.$dia_sem.' day' , strtotime ( $primer_dia ) ) ;
								$fecha_dia = date ( 'Y-m-d' , $nuevafecha );
								$tr_fecha = explode("-", $fecha_dia);
								$anio0 = $tr_fecha[0];
								$mes0 = $tr_fecha[1];
								$dia0 = $tr_fecha[2];
								?>
								<?php $result = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula FROM horw WHERE c_asig NOT LIKE '25' AND a_aula NOT LIKE '' AND a_aula NOT LIKE 'ACO%' AND a_aula NOT LIKE 'DI%' ORDER BY n_aula ASC"); ?>
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<?php $grupo = mysqli_query($db_con, "SELECT a_grupo FROM horw where a_aula = '$row[0]' AND dia='$dia' AND hora='$i' AND c_asig NOT LIKE '25' ORDER BY a_grupo ASC"); ?>

							<?php $asig = mysqli_fetch_array($grupo); ?>

							<?php $res = mysqli_query($db_con,"select * from reservas where date(eventdate) = '$fecha_dia' and event".$i." not like '' and servicio = '$row[0]'");
							$ya_reserva = mysqli_num_rows($res);?>
							<?php if($asig['a_grupo'] == '' and $ya_reserva != 1): ?>
							<p><a href="../../reservas/reservar/index_aulas.php?year=<?php echo $anio0;?>&today=<?php echo $dia0;?>&month=<?php echo $mes0;?>&servicio=<?php echo $row[0]; ?>"><?php echo $row['n_aula']; ?></a></p>
							<?php endif; ?> <?php endwhile; ?></td>
							<?php endfor; ?>
						</tr>
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
