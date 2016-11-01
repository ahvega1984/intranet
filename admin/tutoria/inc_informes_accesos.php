<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
	
<!-- INFORMES DE ACCESOS -->

			
	<?php
	if($_SERVER['SERVER_NAME'] == 'iesantoniomachado.es' || $_SERVER['SERVER_NAME'] == 'iesbahiamarbella.es') {
		$query_accesos = mysqli_query($db_con, "SELECT rp.claveal, COUNT(*) AS accesos FROM reg_principal AS rp GROUP BY claveal, pagina HAVING pagina='/alumnos/login.php' and claveal in (select distinct claveal from alma where unidad = '".$_SESSION['mod_tutoria']['unidad']."') ORDER BY accesos DESC");
	}
	else {
		$query_accesos = mysqli_query($db_con, "SELECT rp.claveal, COUNT(*) AS accesos FROM reg_principal AS rp GROUP BY claveal, pagina HAVING pagina='/notas/control.php' and claveal in (select distinct claveal from alma where unidad = '".$_SESSION['mod_tutoria']['unidad']."') ORDER BY accesos DESC");
	}
	if (!mysqli_num_rows($query_accesos)>0) {	
	}
	elseif($_SESSION['pagina_centro']==1 and mysqli_num_rows($query_accesos)>0)
	{
	?>
			<h3 class="text-info">Accesos de Padres/Alumnos</h3>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Alumno/a</th>
							<th>Accesos (Último)</th>
						</tr>
					</thead>
					<tbody>
					  <?php 
					  while ($row = mysqli_fetch_object($query_accesos)):
					  	
					  	$subquery = mysqli_query($db_con, "SELECT CONCAT(apellidos,', ',nombre) AS alumno, unidad FROM alma WHERE claveal=$row->claveal LIMIT 1");
					  	$datos = mysqli_fetch_object($subquery);
					  	mysqli_free_result($subquery);
					  	
					  	$subquery2 = mysqli_query($db_con, "SELECT fecha FROM reg_principal WHERE claveal=$row->claveal ORDER BY fecha DESC LIMIT 1");
					  	$fecha = mysqli_fetch_object($subquery2);
					  	mysqli_free_result($subquery2);
					  	
					  	if($datos->alumno != "" && $datos->unidad != ""):
					  ?>
					  	<tr>
					  		<td><a><?php echo $datos->alumno; ?></a></td>
								<td> <span class="text-danger"><b><?php echo $row->accesos; ?></b></span> <small class="text-muted">(<?php echo $fecha->fecha; ?>)</small></td>
					  	</tr>
					  <?php 
					  	endif;
					  endwhile;
					  mysqli_free_result($query_accesos);
					  ?>
					</tbody>
				</table>
				<hr>
				<?php
				}
				?>  