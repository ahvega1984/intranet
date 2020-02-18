<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../../menu.php");
?>

<div class="container">

	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administración <small>Generación de horario de evaluaciones</small></h2>
	</div>

	<!-- SCAFFOLDING -->
	<div class="row">

		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-4 col-sm-offset-4">

	<div class="alert alert-success">
		La selección de grupos para las reuniones de evaluación supone que <em>estas se organizan por pares de grupos</em>, y que no se tienen en cuenta <em><b>las Libres disposiciones en la ESO, ni en general la Religión y los Valores éticos</b></em>. Los grupos que están apareados no comparten ningún profesor de sus equipos educativos (con las excepciones mencionadas); los grupos que no forman par con otro grupo deben organizarse de forma separada.
	</div>

	<br>

			<table class="table table-bordered table-striped">
				<thead><tr class="warning"><td></td><td><b>AULA 1</b></td><td><b>AULA 2</b></td></tr></thead>
				<tbody>
<?php 
		$excluidos = '"Tarari"';
		$num=0;
		$unidad = mysqli_query($db_con, "select distinct unidad from alma where unidad not like '%T-%' and unidad not like '%S-%' order by unidad asc");
		while ($unidades = mysqli_fetch_array($unidad)) {
			
			$grupo_origen = $unidades[0];
			if (stristr($excluidos, $grupo_origen)==FALSE) {
				if (stristr($grupo_origen, "1E-")) {
					$orienta = " and grupo not like '3E-%'";
				}
				elseif (stristr($grupo_origen, "2E-")) {
					$orienta = " and grupo not like '4E-%'";
				}
				else{
					$orienta = "";
				}

			$grupo_ya='"Tarari"';
			$result = mysqli_query($db_con, "SELECT DISTINCT profesor FROM profesores where materia not like 'Libre disp%' and materia not like 'Relig%' and materia not like 'Valores%' and grupo = '$unidades[0]' order by grupo asc");

			while($profe2 = mysqli_fetch_array($result)){

				$result1 = mysqli_query($db_con, "SELECT DISTINCT grupo FROM profesores where materia not like 'Libre disp%' and materia not like 'Relig%' and materia not like 'Valores%' and profesor like '$profe2[0]' and grupo not like '%T-%' and grupo not like '%S-%'");

				while ($result2 = mysqli_fetch_array($result1)) {
					if (stristr($grupo_ya, $result2[0])) { }
						else{
						$grupo_ya.=',"'.$result2[0].'"';
						}
					}
					//echo $grupo_ya."<br><br>";
				}

				$result3 = mysqli_query($db_con,"select distinct grupo from profesores where materia not like 'Libre disp%' and materia not like 'Relig%' and materia not like 'Valores%' and grupo not in (".$excluidos.") and grupo not in (".$grupo_ya.") and grupo not like '%T-%' and grupo not like '%S-%' $orienta order by grupo asc limit 1");	

				$grupo_destino = "";
				
				if (mysqli_num_rows($result3)>0) {					
					$result4 = mysqli_fetch_array($result3);
					$grupo_destino = $result4[0];
					if (stristr($excluidos, $result4[0])==FALSE) { 
						$excluidos.=',"'.$grupo_origen.'","'.$grupo_destino.'"';
						}
					}
					else{
						$excluidos.=',"'.$grupo_origen.'","'.$grupo_destino.'"';
					}

					$num++;

?>
				<tr><td><?php echo $num; ?></td><td><?php echo $grupo_origen;?></td><td><?php echo $grupo_destino;?></td></tr>
<?php				
				}
			}
?>
</tbody>
</table>

<br>
			<div class="text-center">
				 <a href="../index.php" class="btn btn-primary">Volver a Administración</a>
			</div>
		</div>
	</div><!-- /.row -->

</div><!-- /.container -->

<?php include("../../pie.php"); ?>

</body>
</html>
