<?php
require('../../bootstrap.php');

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("../../faltas/menu.php");

?>
<div class="container">

	<div class="page-header">
		<h2>Informe sobre Faltas de Asistencia <small>Profesores</small></h2>
	</div>

	<div id="status-loading" class="text-center">
		<br><br>
		<p class="lead">
			<span class="far fa-circle-o-notch fa-spin"></span> Cargando datos...<br>
			<small>El proceso puede tomar algún tiempo.</small>
		</p>
		<br><br>
	</div>

	<div id="wrap" class="row" style="display: none;">

		<div class="col-sm-12">

			<?php include("menu_informes.php"); ?>

			<br>

			<div class="alert alert-info">
				La columna <strong>Por grupo</strong> presenta el número de faltas que el profesor ha puesto en cada uno de sus grupos (entre paréntesis, primero la abreviatura de la asignatura y luego el número de alumnos de su asignatura); la columna <strong>Media grupos</strong> presenta la media de faltas que todos los profesores han puesto en cada uno de esos grupos.
			</div>
			
			<br>
  
			<table class='table table-bordered table-striped table-vcentered datatable'>
				<thead>
					<tr>
						<th>Profesor</th>
						<th>Total Faltas</th>
						<th>Grupos</th>
						<th data-bs='tooltip' data-html='true' title='Faltas del Profesor por cada Grupo'>Por Grupo</th>
						<th data-bs='tooltip' data-html='true' title='Media de Faltas de todos los Profesores por cada grupo'>Media Grupos</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$profe = mysqli_query($db_con, "SELECT profesor, count(*) as numero FROM FALTAS where profesor not like '' group by profesor order by profesor desc");
			 	while($fprofe = mysqli_fetch_array($profe))
			        {
			          $alumnos_profesor = "";
			          $alumnos_totales="";
			          $unidades="";
			          $faltas_gr_total="";
			          $profes_gr_total="";
			          $media_total_profe_grupo="";
			          $extra_profe="";
			          $extra_total="";
			          $extra_pr_gr="";
			
			          $c_prof = $fprofe[0];
			          $numero_faltas = $fprofe[1];
			
			          $nombre = mysqli_query($db_con, "SELECT prof FROM horw where c_prof = '$c_prof'");
			          $nombre_profe = mysqli_fetch_array($nombre);
			          $nombre_profesor = $nombre_profe[0];
			
			          $grupos = mysqli_query($db_con, "SELECT distinct a_grupo, c_asig FROM horw where c_prof = '$c_prof' and a_grupo in (select distinct grupo from profesores where profesor = '$nombre_profesor')");
			          $n_grupos = mysqli_num_rows($grupos);
			          while ($asig_al = mysqli_fetch_array($grupos)) {
			
			            $asig = mysqli_query($db_con,"select distinct nombre, abrev from asignaturas where codigo = '$asig_al[1]' and abrev not like '%\_%'");
			            $asignatu = mysqli_fetch_array($asig);
			            if (!empty($asignatu[0])) {
			              $unidades.=$asig_al[0]." (".$asignatu[0]."); <br>";
			            }
			
			            $num_al = mysqli_query($db_con,"select * from alma where unidad = '$asig_al[0]' and combasi like '%$asig_al[1]:%'");
			            $alumno_total = mysqli_num_rows($num_al);
			
			            $num_prof = mysqli_query($db_con,"select distinct prof from horw where a_grupo = '$asig_al[0]' and c_asig = '$asig_al[1]'");
			            $profesores_asig = mysqli_num_rows($num_prof);
			
			            $alumnos_profesor+=round($alumno_total/$profesores_asig);
			            //echo "$asig_al[0] -> $asig_al[1]: $alumno_total - $alumnos_profesor<br>";
			
			            $pr_gr = mysqli_query($db_con, "SELECT * FROM FALTAS where profesor = '$c_prof' and unidad = '$asig_al[0]'");
			            $extra_pr_gr = mysqli_num_rows($pr_gr);
			
			            $extra_profe.="<b>$asig_al[0]</b> ($asignatu[1]) ($alumno_total) => <span class='text-info'>$extra_pr_gr</span><br>";
			
			            $faltas_total_grupo = mysqli_query($db_con, "SELECT * FROM FALTAS where unidad = '$asig_al[0]'");
			            $faltas_gr_total = mysqli_num_rows($faltas_total_grupo);
			            $profes_total_grupo = mysqli_query($db_con, "SELECT distinct profesor FROM profesores where grupo = '$asig_al[0]'");
			            $profes_gr_total = mysqli_num_rows($profes_total_grupo);
			
			            $media_total_profe_grupo=round($faltas_gr_total/$profes_gr_total);
			            $extra_total.="<b>$asig_al[0]</b> => <span class='text-warning'>$media_total_profe_grupo</span><br>";
			
			          }
			          $alumnos_profesor = round($alumnos_profesor);
			
			          $profe_grupos = round($numero_faltas/$n_grupos);
			
			          $media_al = round($numero_faltas/$alumnos_profesor);
			
			          echo "<tr><td>$nombre_profesor </td> <td>Faltas: <b>$numero_faltas</b><br>Alumnos: <b>$alumnos_profesor</b><br>Media Alumno/Falta: <b>$media_al</b></td> <td data-bs='tooltip' data-html='true' title='$unidades'>$n_grupos</td><td>$extra_profe</td> <td>$extra_total</td></tr>";
			        }
				?>
				</tbody>
			<table>

		</div>
	</div>
</div>

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

	<script>
	function espera() {
	  document.getElementById("wrap").style.display = '';
	  document.getElementById("status-loading").style.display = 'none';
	}
	window.onload = espera;
	</script>

</body>
</html>
