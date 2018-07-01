<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
$PLUGIN_DATATABLES = 1;
include("../../../menu.php"); 
?>
	
	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		
		<div class="page-header">
			<h2 class="page-title" align="center">Informe de accesos de padres/alumnos</h2>
		</div>
		
		
		<!-- CONTENIDO DE LA PAGINA -->
		
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">

				<?php
				if (file_exists('../../../../alumnado/login.php')) {
					$query_accesos = mysqli_query($db_con, "SELECT rp.claveal, COUNT(*) AS accesos FROM reg_principal AS rp GROUP BY claveal, pagina HAVING pagina='/alumnado/login.php' ORDER BY accesos DESC");
				}
				elseif (file_exists('/home/e-smith/files/ibays/Primary/html/notas/control.php')) {
					$query_accesos = mysqli_query($db_con, "SELECT rp.claveal, COUNT(*) AS accesos FROM reg_principal AS rp GROUP BY claveal, pagina HAVING pagina='/notas/control.php' ORDER BY accesos DESC");
				}
				?>
				<?php if (!(mysqli_num_rows($query_accesos)>0)): ?>
				<br>
				<div align="center">
					<div class="alert alert-warning alert-block fade in">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="fas fa-exclamation-triangle fa-4x" ></i><br><hr>
						<p class="text-left">No hay datos de acceso de padres o alumnos. Parece que no has instalado la página web del Centro en tu servidor. <br>Puedes encontrar más información sobre esta extensión de la Intranet en esta dirección: <a href="https://github.com/IESMonterroso/webcentros" target="_blank">https://github.com/IESMonterroso/webcentros</a>. 
						<br>También puedes descargarla e instalarla siguiendo las instrucciones en la misma página.
						</p>
					</div>
				</div>

				<?php else: ?>
						
			    <div class="no_imprimir">
			      <a href="../../index.php" class="btn btn-default">Volver</a>
			      <a href="#" class="btn btn-primary" onclick="print()"><i class="fas fa-print"></i> Imprimir</a>
			      <br><br>
			    </div>
				
				<table class="table table-bordered table-condensed table-striped datatable">
					<thead>
						<tr>
							<th>Alumno/a</th>
							<th>Unidad</th>
							<th>Total accesos</th>
							<th>Fecha último acceso</th>
						</tr>
					</thead>
					<tbody>
					  <?php 
					  while ($row = mysqli_fetch_object($query_accesos)):
					  	
					  	$subquery = mysqli_query($db_con, "SELECT CONCAT(apellidos,', ',nombre) AS alumno, unidad FROM alma WHERE claveal = '".$row->claveal."' LIMIT 1");
					  	$datos = mysqli_fetch_object($subquery);
					  	mysqli_free_result($subquery);
					  	
					  	$subquery2 = mysqli_query($db_con, "SELECT fecha FROM reg_principal WHERE claveal = '".$row->claveal."' ORDER BY fecha DESC LIMIT 1");
					  	$fecha = mysqli_fetch_object($subquery2);
					  	mysqli_free_result($subquery2);
					  	
					  	if($datos->alumno != "" && $datos->unidad != ""):
					  ?>
					  	<tr>
					  		<td><?php echo $datos->alumno; ?></td>
							<td><?php echo $datos->unidad; ?></td>
					  		<td><?php echo $row->accesos; ?></td>
							<td><?php echo $fecha->fecha; ?></td>
					  	</tr>
					  <?php 
					  	endif;
					  endwhile;
					  mysqli_free_result($query_accesos);
					  ?>
					</tbody>
				</table>

				<?php endif; ?>

			</div><!-- /.col-sm-12 -->
		</div><!-- /.row -->  
	</div><!-- /.container -->
	
	<br>

<?php include('../../../pie.php'); ?>

	<script>
	$(document).ready(function() {
		var table = $('.datatable').DataTable({
			"paging":   true,
	    "ordering": true,
	    "info":     false,
	    
			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
			
			"order": [[ 3, "desc" ]],
			
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