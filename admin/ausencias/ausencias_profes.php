<?php
require('../../bootstrap.php');
include("../../menu.php");
include("menu.php");

$PLUGIN_DATATABLES = 1;
?>

<div class="container">

	<div class="page-header">
		<h2 style="display: inline;">Ausencias del profesorado <small> Informe sobre las Ausencias</small></h2>
	</div>

	<div class="row">

		<div class="col-sm-10 col-sm-offset-1">

			<br>
				<table class="table table-bordered table-striped table-vcentered datatable">
					<thead>
						<tr>
				      		<th><small>Profesor</small></th>
				      		<th><small>Total Días</small></th>
				      		<th><small>Días completos</small></th>
				      		<th><small>Días con horas</small></th>
				      		<th><small>Nº de horas</small></th>
				  		</tr>
				  	</thead>

			<?php $result = mysqli_query($db_con, "SELECT distinct profesor, count(*) as numero FROM ausencias group by profesor order by profesor ASC"); ?>
			<?php while ($total = mysqli_fetch_array($result)){
				$profe = $total[0];
				$num_total = $total[1];
				$num_horas = "";
				$horas = "";
				$dias = "";
			?>
				<?php $result1 = mysqli_query($db_con, "SELECT horas FROM ausencias where profesor = '$profe' and (horas = '0' or horas = '123456')");
					$dias = mysqli_num_rows($result1);
					if($dias==0) $dias="";
				?>

				<?php $result2 = mysqli_query($db_con, "SELECT horas FROM ausencias where profesor = '$profe' and horas not like '0' and horas not like '123456'");
					$horas = mysqli_num_rows($result2);
					if($horas==0) $horas="";
					while ($sueltas = mysqli_fetch_array($result2)) {
						$num_horas+=strlen($sueltas[0]);
					}
				?>

					<tr>
	  					<td><a href="index.php?pra=<?php echo $profe;?>#history"><?php echo nomprofesor($profe);?></td>
	  					<td class="text-center"><?php echo "<strong>".$num_total."</strong>";?></td>
	  					<td class="text-center"><?php echo "<span class='text-danger'>".$dias."</span>";?></td>
	  					<td class="text-center"><?php echo "<span class='text-warning'>".$horas."</span>";?></td>
	  					<td class="text-center"><?php echo "<span class='text-info'>".$num_horas."</span>";?></td>
					</tr>
			<?php } ?>

			</table>

		</div><!-- /.col-sm-6 -->

	</div><!-- /.row -->

</div><!-- /.container -->

	<?php include("../../pie.php"); ?>

<script>
  $(document).ready(function() {
    var table = $('.datatable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false,

        "lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],

        "order": [[ 1, "asc" ]],

        "language": {
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "No se ha encontrado ningún resultado con ese criterio.",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay resultados disponibles.",
                    "infoFiltered": "(filtrado de _MAX_ resultados)",
                    "search": "Buscar: ",
                    "paginate": {
                          "first": "Primera",
                          "next": "Última",
                          "next": "",
                          "previous": ""
                        }
                }
      });
  });
  </script>
</body>
</html>
